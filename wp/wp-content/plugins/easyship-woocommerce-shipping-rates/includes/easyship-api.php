<?php
/*
Class Easyship_Shipping_API
Author: Easyship
Developer: Sunny Cheung, Holubiatnikova Anna, Aloha Chen, Paul Lugagne Delpon, Bernie Chiu
Version: 0.8.5
Author URI: https://www.easyship.com
*/
if (!defined('WPINC')) {
    die;
}
if (!class_exists('Easyship_Shipping_API')) {
    class Easyship_Shipping_API
    {
        private static $apikey = '';
        private static $apiSecret = '';
        private static $accessToken = '';
        private static $is_insured = false;
        private static $taxes_duties_paid_by = 'Sender';
        private static $default_category = 'home_appliances';
        private static $oauth_url = "https://auth.easyship.com/oauth2/token";
        private static $api_url = "https://api.easyship.com/rate/v1/woocommerce";
        // Since 0.2.7
        private static $_currency;
        /**
         * @param null $token
         * @throws Exception
         */
        public static function init($token = null)
        {
            $Easyship_Shipping_Method = new Easyship_Shipping_Method();
            self::$apikey = trim(esc_attr($Easyship_Shipping_Method->settings['es_api_key']), " ");
            self::$apiSecret = str_replace('\n', "\n", $Easyship_Shipping_Method->settings['es_api_secret']);
            // if incoterms/insurance already exist on WC, send it with API
            self::$is_insured = 0;
            self::$taxes_duties_paid_by = $Easyship_Shipping_Method->settings['es_taxes_duties'];
            self::$_currency = get_woocommerce_currency();
            // feature/access_token
            if (!is_null($token)) {
                self::$accessToken = trim(esc_attr($token), " ");
            } else {
                $token_option_name = 'es_access_token_' . get_current_network_id();
                self::$accessToken = trim(esc_attr(get_option($token_option_name)), " ");
            }

            // sandbox mode
            // if ( $Easyship_Shipping_Method->settings['es_sandbox_mode'] == 'yes' ) {
            //     self::$oauth_url = "https://auth-sandbox.easyship.com/oauth2/token";
            //     self::$api_url = "https://api-sandbox.easyship.com/rate/v1/woocommerce";
            // }
            if ((self::$apikey == '' || self::$apiSecret == '') && self::$accessToken == '') {
                throw new Exception('Missing API Key and API Secret OR Access Token!');
            }
        }
        /**
         * auth
         *
         * @access protected
         * @return void
         */
        protected static function auth()
        {
            $now = time();
            // if access token is found in session and not expired, will reuse the access token
            if (isset($_SESSION['access_token']) && ($_SESSION['expires_in'] - $now) > 0) {
                self::$accessToken = $_SESSION['access_token'];
                return;
            }
            $jwtHead = rtrim(strtr(base64_encode('{"typ":"JWT","alg":"RS256"}'), '+/', '-_'), '=');
            $url = self::$oauth_url;
            $jwtClaimSetJSON = '{"iss":"' . self::$apikey . '","aud":"' . $url . '","scope":"rate","exp":' . ($now + 3600) . ',"iat":' . $now . '}';
            $jwtClaimSet = rtrim(strtr(base64_encode($jwtClaimSetJSON), '+/', '-_'), '=');
            $private_key = openssl_get_privatekey(self::$apiSecret);
            if (!$private_key) {
                throw new Exception('API Secret is incorrect');
            }
            $signature = '';
            openssl_sign($jwtHead . '.' . $jwtClaimSet, $signature, $private_key, 'sha256');
            $signature = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');
            $jwtToken = $jwtHead . '.' . $jwtClaimSet . '.' . $signature;
            /*  API Document - request access token
                POST https://auth.easyship.com/oauth2/token
                {
                    "grant_type": "assertion",
                    "assertion": "YOUR_JWT",
                    "assertion_type": "urn:ietf:params:oauth:grant-type:jwt-bearer"
                }
            */
            $requestArray = array(
                "grant_type" => "assertion",
                "assertion" => $jwtToken,
                "assertion_type" => "urn:ietf:params:oauth:grant-type:jwt-bearer"
            );
            $response = wp_remote_post($url, array(
                'headers' => array('content-type' => 'application/json'),
                'body' => json_encode($requestArray),
                'method' => 'POST'
            ));
            if (is_wp_error($response)) {
                $error_message = $response->get_error_message();
                throw new Exception($error_message);
            } else {
                if ($response['response']['code'] == 200) {
                    $body = json_decode($response['body'], true);
                    self::$accessToken = $body["access_token"];
                    $_SESSION['access_token'] = self::$accessToken;
                    $_SESSION['expires_in'] = $body["created_at"] + $body["expires_in"];
                }
            }
        }
        public static function getShippingRate($destination, $items)
        {
            $url = self::$api_url;
            if (self::$accessToken == '') { // access token is not set yet
                try {
                    self::auth();
                } catch (Exception $e) {
                    $message = sprintf(__('Error: %s', 'tutsplus'), $e->getMessage());
                    $messageType = "error";
                    wc_add_notice($message, $messageType);
                    // error but return empty array
                    return array();
                }
            }
            // access token should be ready
            $WC_Country = new WC_Countries();

            // since 0.2.7
            // we want to get back shipping rate base on store setting
            if (defined('WCML_VERSION')) {
                self::$_currency = get_option('woocommerce_currency');
            }
            // since 0.4.2
            // Support WooCommerce Currency Switcher
            if (defined('WOOCS_VERSION')) {
                global $WOOCS;
                self::$_currency = $WOOCS->current_currency;
            }
            $requestArray = array(
                "destination_country_alpha2"    => $destination["country"],
                "destination_postal_code"       => ($destination["postcode"] == '') ? 0 : $destination["postcode"],
                "destination_address_line_1"    => $destination['address'],
                "destination_address_line_2"    => $destination['address_2'],
                "destination_city"              => isset($destination['city']) ? $destination['city'] : '',
                "destination_state"             => isset($destination['state']) ? $destination['state'] : '',
                "taxes_duties_paid_by"          => self::$taxes_duties_paid_by,
                "is_insured"                    => self::$is_insured,
                "output_currency"               => self::$_currency,
                "items"                         => $items
            );
            $response = wp_remote_post($url, array(
                'headers' => array(
                    'content-type' => 'application/json',
                    'Authorization' => 'Bearer ' . self::$accessToken,
                    'X-Woocommerce' => 'woocommerce-easyship'
                ),
                'body' => json_encode($requestArray),
                'method' => 'POST',
                'timeout' => 25
            ));
            if (is_wp_error($response)) {
                $error_message = $response->get_error_message();
                if ($error_message == 'fsocket timed out') {
                    throw new Exception("Sorry, the shipping rates are currently unavailable, please refresh the page or try again later");
                } else {
                    // $message = sprintf( __( 'Sorry, something went wrong with the shipping rates. If the problem persists, please contact us! %s'), $error_message );
                    throw new Exception("Sorry, something went wrong with the shipping rates. If the problem persists, please contact us!");
                }
            } else {
                if ($response['response']['code'] == 200) {
                    $body = json_decode($response['body'], true);
                    return $body["rates"];
                }
            }
            // should never reach here
            return array(); // return empty array
        }
    }
}
