<?php

if (!class_exists('Easyship_Registration')) {
    class Easyship_Registration
    {
        private $wpdb;
        private $woocommerce;

        protected $endpoint = 'https://api.easyship.com/api/v1/woo_commerce_group/registrations';

        public function __construct()
        {
            global $wpdb;
            global $woocommerce;

            $this->wpdb = $wpdb;
            $this->woocommerce = $woocommerce;
        }

        public function sendRequest()
        {
            $request = [];
            $request['oauth'] = $this->_getOAuthInfo();
            $request['user'] = $this->_getUserInfo();
            $request['company'] = $this->_getCompanyInfo();
            $request['store'] = $this->_getStoreInfo();
            $request['address'] = $this->_getAddressInfo();

            $curl = curl_init($this->endpoint);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($request));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Cache-Control: no-cache'));
            $response = curl_exec($curl);
            curl_close($curl);
            header("Content-Type: application/json");
            try {
                $response = json_decode($response, true);
                if (is_null($response)) {
                    return json_encode(['error' => 'Service temporarily unavailable']);
                }
                return json_encode($response);
            } catch (\Exception $exception) {
                return json_encode(['error' => $exception->getMessage()]);
            }
        }

        /**
         * @return array|bool
         */
        protected function _getOAuthInfo()
        {
            $apiSource = $this->createApiKeys();
            return $apiSource;
        }

        protected function createApiKeys()
        {
            $consumer_key = 'ck_' . wc_rand_hash();
            $consumer_secret = 'cs_' . wc_rand_hash();

            $data = array(
                'user_id' => get_current_user_id(),
                'description' => 'Easyship Integration',
                'permissions' => 'read_write',
                'consumer_key' => wc_api_hash($consumer_key),
                'consumer_secret' => $consumer_secret,
                'truncated_key' => substr($consumer_key, -7),
            );

            $table = $this->wpdb->prefix . 'woocommerce_api_keys';

            $this->wpdb->query("DELETE FROM $table WHERE description = 'Easyship Integration'");

            $this->wpdb->insert(
                $table,
                $data,
                array(
                    '%d',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                )
            );

            return ['consumer_key' => $consumer_key, 'consumer_secret' => $consumer_secret];
        }

        protected function getApiKeys()
        {
            $table = $this->wpdb->prefix . 'woocommerce_api_keys';
            $api = $this->wpdb->get_row(
                $this->wpdb->prepare("SELECT * FROM `{$table}` WHERE `description` = '%s' LIMIT 1", [
                    'Easyship Integration'
                ]));

            return $api;
        }

        protected function _getUserInfo()
        {
            $user = wp_get_current_user();

            $response['email'] = $user->user_email;
            $response['first_name'] = 'test';//!empty($user->first_name) ? $user->first_name : $user->user_nicename;
            $response['last_name'] = 'test';//!empty($user->last_name) ? $user->last_name : '';
            $response['mobile_phone'] = !empty($user->billing_phone) ? $user->billing_phone : '';

            return $response;
        }

        protected function _getCompanyInfo()
        {
            $response = array();
            $country = explode(':', get_option('woocommerce_default_country'));

            $response['name'] = get_option('blogname');
            $response['country_code'] = !empty($country[0]) ? $country[0] : '';

            return $response;
        }

        protected function _getStoreInfo()
        {
            $response = array();
            $response['platform_store_id'] = get_current_network_id();
            $response['name'] = get_option('blogname');
            $response['url'] = get_option('home');
            $response['wc_version'] = $this->woocommerce->version;

            return $response;
        }

        protected function _getAddressInfo()
        {
            $response = array();
            $country = explode(':', get_option('woocommerce_default_country'));
            $city = get_option('woocommerce_store_city');
            $postal_code = get_option('woocommerce_store_postcode');
            $line_1 = get_option('woocommerce_store_address');
            $line_2 = get_option('woocommerce_store_address_2');

            $response['state'] = !empty($country[1]) ? $country[1] : '';
            $response['city'] = !empty($city) ? $city : '';
            $response['postal_code'] = !empty($postal_code) ? $postal_code : '';
            $response['line_1'] = !empty($line_1) ? $line_1 : '';
            $response['line_2'] = !empty($line_2) ? $line_2 : '';

            return $response;
        }
    }
}
