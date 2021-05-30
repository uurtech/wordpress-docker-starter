<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * @extends WC_REST_Controller
 */
class Easyship_REST_Token_V1_Controller extends WP_REST_Controller
{

    /**
     * Endpoint namespace.
     *
     * @var string
     */
    protected $namespace = 'easyship/v1';

    /**
     * Route base.
     *
     * @var string
     */
    protected $rest_base = 'token';


    public function register_routes()
    {
        register_rest_route($this->namespace, '/' . $this->rest_base, array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'createToken'),
                'permission_callback' => array($this, 'create_item_permissions_check'),
                'args' => $this->get_endpoint_args_for_item_schema(true),
            ),
        ));
    }

    /**
     * Check if a given request has access to create items
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function create_item_permissions_check($request)
    {
        $check = $this->perform_oauth_authentication($request->get_params());

        return is_null($check) ? true : $check;
    }

    protected function perform_oauth_authentication($params)
    {
        $param_names = array(
            'oauth_consumer_key',
            'oauth_timestamp',
            'oauth_nonce',
            'oauth_signature',
            'oauth_signature_method'
        );

        // Check for required OAuth parameters
        foreach ($param_names as $param_name) {
            if (empty($params[$param_name])) {
                return new WP_Error('woocommerce_rest_authentication_error',
                    __('Invalid signature - failed to sort parameters.', 'woocommerce'),
                    array('status' => 401));
            }
        }

        // Fetch WP user by consumer key
        try {
            $keys = $this->get_keys_by_consumer_key($params['oauth_consumer_key']);
        } catch (\Exception $exception) {
            return new WP_Error('woocommerce_rest_authentication_error',
                __($exception->getMessage()),
                array('status' => 401));
        }

        // Perform OAuth validation
        $this->check_oauth_signature($keys, $params);
    }

    protected function get_keys_by_consumer_key($consumer_key)
    {
        global $wpdb;

        $consumer_key = wc_api_hash(sanitize_text_field($consumer_key));

        $keys = $wpdb->get_row($wpdb->prepare("
			SELECT *
			FROM {$wpdb->prefix}woocommerce_api_keys
			WHERE consumer_key = '%s' 
			LIMIT 1
		", $consumer_key), ARRAY_A);

        if (empty($keys)) {
            throw new Exception(__('Consumer key is invalid.', 'woocommerce'), 401);
        }

        return $keys;
    }

    protected function check_oauth_signature($keys, $params)
    {
        unset($params['store_id']);
        unset($params['token']);

        $http_method = strtoupper($_SERVER['REQUEST_METHOD']);
        $request_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $wp_base = get_home_url(null, '/', 'relative');
        if (substr($request_path, 0, strlen($wp_base)) === $wp_base) {
            $request_path = substr($request_path, strlen($wp_base));
        }
        $base_request_uri = rawurlencode(get_home_url(null, $request_path, is_ssl() ? 'https' : 'http'));

        // Get the signature provided by the consumer and remove it from the parameters prior to checking the signature.
        $consumer_signature = rawurldecode(str_replace(' ', '+', $params['oauth_signature']));
        unset($params['oauth_signature']);

        // Sort parameters.
        if (!uksort($params, 'strcmp')) {
            return new WP_Error('woocommerce_rest_authentication_error', __('Invalid signature - failed to sort parameters.', 'woocommerce'), array('status' => 401));
        }

        // Normalize parameter key/values.
        $params = $this->normalize_parameters($params);
        $query_string = implode('%26', $this->join_with_equals_sign($params)); // Join with ampersand.
        $string_to_sign = $http_method . '&' . $base_request_uri . '&' . $query_string;

        if ('HMAC-SHA1' !== $params['oauth_signature_method'] && 'HMAC-SHA256' !== $params['oauth_signature_method']) {
            return new WP_Error('woocommerce_rest_authentication_error', __('Invalid signature - signature method is invalid.', 'woocommerce'), array('status' => 401));
        }

        $hash_algorithm = strtolower(str_replace('HMAC-', '', $params['oauth_signature_method']));
        $secret = $keys['consumer_secret'] . '&';
        $signature = base64_encode(hash_hmac($hash_algorithm, $string_to_sign, $secret, true));

        if (!hash_equals($signature, $consumer_signature)) {
            return new WP_Error('woocommerce_rest_authentication_error', __('Invalid signature - provided signature does not match.', 'woocommerce'), array('status' => 401));
        }

        return true;
    }

    private function join_with_equals_sign($params, $query_params = array(), $key = '')
    {
        foreach ($params as $param_key => $param_value) {
            if ($key) {
                $param_key = $key . '%5B' . $param_key . '%5D'; // Handle multi-dimensional array.
            }

            if (is_array($param_value)) {
                $query_params = $this->join_with_equals_sign($param_value, $query_params, $param_key);
            } else {
                $string = $param_key . '=' . $param_value; // Join with equals sign.
                $query_params[] = wc_rest_urlencode_rfc3986($string);
            }
        }

        return $query_params;
    }

    protected function normalize_parameters($parameters)
    {

        $normalized_parameters = array();

        foreach ($parameters as $key => $value) {

            // Percent symbols (%) must be double-encoded
            $key = str_replace('%', '%25', rawurlencode(rawurldecode($key)));
            $value = str_replace('%', '%25', rawurlencode(rawurldecode($value)));

            $normalized_parameters[$key] = $value;
        }

        return $normalized_parameters;
    }

    /**
     * Prepare the item for create or update operation
     *
     * @param WP_REST_Request $request Request object
     * @return WP_Error|object $prepared_item
     */
    protected function prepare_item_for_database($request)
    {

        $item = new StdClass();
        $item->token = $request->get_param('token');
        $item->store_id = $request->get_param('store_id');

        if (empty($item->token)) {
            return new WP_Error('easyship_bad_param', __('token is required field'), array('status' => 400));
        } elseif (empty($item->store_id)) {
            return new WP_Error('easyship_bad_param', __('store_id is required field'), array('status' => 400));
        }

        return $item;
    }


    /**
     * Create one item from the collection
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function createToken($request)
    {
        $item = $this->prepare_item_for_database($request);
        $option_name = 'es_access_token_' . $item->store_id;
        try {
            if (get_option($option_name) !== false) {
                update_option($option_name, $item->token);
            } else {
                add_option($option_name, $item->token, null, 'yes');
            }
        } catch (Exception $exception) {
            return new WP_Error('easyship_internal_error', __('Something went wrong.'), array('status' => 500));
        }

        $response = new WP_REST_Response();
        $response->set_data(array('success' => true));

        return $response;
    }

}
