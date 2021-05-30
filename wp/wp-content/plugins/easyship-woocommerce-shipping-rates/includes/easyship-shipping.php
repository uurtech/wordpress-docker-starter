<?php
/*
Class Easyship_Shipping_Method
Author: Easyship
Developer: Sunny Cheung, Holubiatnikova Anna, Aloha Chen, Paul Lugagne Delpon, Bernie Chiu
Version: 0.8.5
Author URI: https://www.easyship.com
*/
if (!class_exists('Easyship_Shipping_Method')) {
    class Easyship_Shipping_Method extends WC_Shipping_Method
    {
        protected $discount_for_item = 0;
        protected $control_discount = 0;
        protected $token;
        protected $shipping_class;
        //  protected $easyship_categories = Easyship_Category::get_categories();
        /**
         * Constructor for your shipping class
         *
         * @access public
         * @param int $instance_id
         */
        public function __construct($instance_id = 0)
        {
            $this->id = 'easyship';
            $this->instance_id = empty($instance_id) ? 99 : absint($instance_id);
            $this->method_title = __('Easyship', 'easyship');
            $this->method_description = __('Dynamic Shipping Rates at Checkout, by <a href="https://www.easyship.com" target="_blank">Easyship</a>.', 'easyship');

            $this->supports = array(
                'shipping-zones',
                'settings',
                'instance-settings',
                'instance-settings-modal',
            );

            $this->init();
            $this->enabled = isset($this->settings['enabled']) ? $this->settings['enabled'] : 'yes';
            $this->title = isset($this->settings['title']) ? $this->settings['title'] : __('Easyship', 'easyship');
            add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
        }
        /**
         * Init your settings
         *
         * @access public
         * @return void
         */
        function init()
        {
            // Load the settings API
            $this->init_form_fields();
            $this->init_settings();
            add_filter('woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50);
            add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
            add_action('update_option_woocommerce_easyship_settings', array($this, 'clear_session'), 10, 2);

            add_action('woocommerce_update_options_shipping_easyship', array($this, 'saveOptions'));
        }
        public static function add_settings_tab($settings_tabs)
        {
            $settings_tabs['shipping&section=easyship'] = __('Easyship', 'easyship-shipping');
            return $settings_tabs;
        }
        /**
         * Clear session when option save
         *
         * @access public
         * @return void
         */
        public function clear_session($old_value, $new_value)
        {
            $_SESSION['access_token'] = null;
        }

        public function saveOptions()
        {
            $option_key = 'woocommerce_easyship_settings';
            $token_option = $this->get_token()['name'];
            $shopping_class_option = 'woocommerce_easyship_skip_shipping_class';

            $value = get_option($option_key);
            if (!empty($value)) {
                $value = unserialize($value);
            } else {
                $value = [];
            }

            if (isset($_POST[$shopping_class_option])) {
                $value[$shopping_class_option] = $_POST[$shopping_class_option];
            }

            if (isset($_POST['woocommerce_easyship_' . $token_option])) {
                update_option($token_option, $_POST['woocommerce_easyship_' . $token_option]);
                $value['woocommerce_easyship_' . $token_option] = $_POST['woocommerce_easyship_' . $token_option];
            }

            update_option($option_key, serialize($value));
        }

        /**
         * Notification when api key and secret is not set
         *
         * @access public
         * @return void
         */
        public function easyship_admin_notice()
        {
            $token = 'es_access_token_' . get_current_network_id();
            if (($this->get_option('es_api_key') == '' || $this->get_option('es_api_secret') == '') && (get_option($token) == '')) {
                echo '<div class="error">Please go to <bold>WooCommerce > Settings > Shipping > Easyship</bold> to add your API key and API Secret Or Access Token </div>';
            }
        }
        /**
         * Define settings field for this shipping
         * @return void
         */
        function init_form_fields()
        {
            // added: 4/9/2017
            // Display access token field to new customer, if api_key or secret already set for current customer, then
            // display api key and secret key information
            // new customer
            if (($this->get_option('es_api_key') == '' || $this->get_option('es_api_secret') == '')) {
                $this->form_fields = array_merge(
                    array(
                        'skip_shipping_class' => array(
                            'title' => __('Skip Shipping Class Slug', 'easyship-shipping'),
                            'type' => 'text',
                            'description' => __('Enter the shipping class slug for items which do not need to be shipped with Easyship. The slug can be found in "WooCommerce > Settings > Shipping > Shipping classes"', 'easyship-shipping'),
                            'desc_tip' => true,
                            'default' => $this->getShippingClass()
                        ),
                    ),
                    $this->form_fields
                );
                $token = $this->get_token();
                $token_fields = [];
                if ($token['value'] == '') {
                    $token_fields['es_oauth_ajax'] = [
                        'title' => __('Enable Easyship', 'easyship-shipping'),
                        'type' => 'button',
                        'description' => __('Click \'Enable\' will redirect you to Easyship to create an account for free, or connect to an existing Easyship account.<br/>If it doesn\'t work, don\'t worry, you can always create an account at <a href="https://www.easyship.com" target="_blank">www.easyship.com</a> to obtain your Access Token, and paste it below.', 'easyship-shipping'),
                        'default' => 'Enable',
                    ];
                } else {
                    $token_fields['es_ajax_disabled'] = [
                        'title' => __('Disable Easyship'),
                        'type' => 'button',
                        'description' => __('Click \'Disable\' will deactivate the dynamic shipping rates at checkout.'),
                        'default' => 'Disable'
                    ];
                }
                $token_fields[$token['name']] = [
                    'title' => __('Easyship Access Token', 'easyship-shipping'),
                    'type' => 'text',
                    'description' => __('Enter your Easyship Access Token. To retrieve it, connect to the Easyship dashboard and go to "Connect > Add New" to connect your WooCommerce store. You can then retrieve your Access Token from your store\'s page by clicking on "Activate Rates". This is also the place where you will be able to set all your shipping options and rules.', 'easyship-shipping'),
                    'desc_tip' => true,
                    'default' => $token['value']
                ];
                $this->form_fields = array_merge($token_fields, $this->form_fields);
            } else {
                $this->form_fields = array_merge(
                    array(
                        'enabled' => array(
                            'title' => __('Enable', 'easyship-shipping'),
                            'type' => 'checkbox',
                            'description' => __('Enable Easyship Rates. If unchecked, no rates will be shown at checkout.', 'easyship-shipping'),
                            'default' => 'yes'
                        ),
                        'es_api_key' => array(
                            'title' => __('API Key', 'easyship-shipping'),
                            'type' => 'text',
                            'description' => __('Enter your Easyship API Key. ', 'easyship-shipping'),
                            'desc_tip' => true,
                            'default' => ''
                        ),
                        'es_api_secret' => array(
                            'title' => __('API Secret', 'easyship-shipping'),
                            'type' => 'textarea',
                            'description' => __('Enter your Easyship API Secret. ', 'easyship-shipping'),
                            'desc_tip' => true,
                            'default' => ''
                        )
                    ),
                    $this->form_fields
                );
            }
            oauth_action_button_es();
            add_action('admin_enqueue_scripts', 'oauth_action_button_es');
        }
        public function generate_text_html( $key, $data ) {
            $field_key = $this->get_field_key( $key );
            $defaults  = array(
                'title'             => '',
                'disabled'          => false,
                'class'             => '',
                'css'               => '',
                'placeholder'       => '',
                'type'              => 'text',
                'desc_tip'          => false,
                'description'       => '',
                'custom_attributes' => array(),
            );
            $data = wp_parse_args( $data, $defaults );
            if (isset($data['type']) && ($data['type'] == 'button')) {
                $value = isset($data['default']) ? $data['default'] : '';
            } else {
                $value = esc_attr($this->get_option($key));
            }
            ob_start();
            ?>
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for="<?php echo esc_attr( $field_key ); ?>"><?php echo wp_kses_post( $data['title'] ); ?> <?php echo $this->get_tooltip_html( $data ); // WPCS: XSS ok. ?></label>
                </th>
                <td class="forminp">
                    <fieldset>
                        <legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>
                        <input class="input-text regular-input <?php echo esc_attr( $data['class'] ); ?>" type="<?php echo esc_attr( $data['type'] ); ?>" name="<?php echo esc_attr( $field_key ); ?>" id="<?php echo esc_attr( $field_key ); ?>" style="<?php echo esc_attr( $data['css'] ); ?>" value="<?php echo $value; ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" <?php disabled( $data['disabled'], true ); ?> <?php echo $this->get_custom_attribute_html( $data ); // WPCS: XSS ok. ?> />
                        <?php echo $this->get_description_html( $data ); // WPCS: XSS ok. ?>
                    </fieldset>
                </td>
            </tr>
            <?php
            return ob_get_clean();
        }
        /**
         * This function is used to calculate the shipping cost. Within this function we can check for weights, dimensions and other parameters.
         *
         * @access public
         * @param mixed $package
         * @return void
         */
        public function calculate_shipping($package = array())
        {
            if (!WC_Shipping_Zones::get_shipping_method($this->instance_id)) {
                return;
            }
            $destination = $package["destination"];
            $items = array();
            $product_factory = new WC_Product_Factory();
            $currency = get_woocommerce_currency();
            // since 0.4.2
            // Support WooCommerce Currency Switcher
            if (defined('WOOCS_VERSION')) {
                global $WOOCS;
                $currency = $WOOCS->current_currency;
                // Rates API already return rates with currency converted, so no need for WOOCS to convert
                $WOOCS->is_multiple_allowed = false;
            }
            if (method_exists(WC()->cart, 'get_discount_total')) {
                $total_discount = WC()->cart->get_discount_total();
            } elseif (method_exists(WC()->cart, 'get_cart_discount_total')) {
                $total_discount = WC()->cart->get_cart_discount_total();
            } else {
                $total_discount = 0;
            }
            if (method_exists(WC()->cart, 'get_subtotal')) {
                $total_cart_without_discount = WC()->cart->get_subtotal();
            } else {
                $total_cart_without_discount = WC()->cart->subtotal;
            }
            if (!empty($total_discount) && ($total_discount > 0)) {
                $discount_for_item = ($total_discount / $total_cart_without_discount) * 100;
                $this->setDiscountForItem($discount_for_item);
                unset($discount_for_item);
            }
            foreach ($package["contents"] as $key => $item) {
                // default product - assume it is simple product
                $product = $product_factory->get_product($item["product_id"]);
                $skip_shipping_class = $this->get_option('skip_shipping_class');
                if (!empty($skip_shipping_class) && ($product->get_shipping_class() === $skip_shipping_class)) {
                    continue;
                }
                // check version
                if (WC()->version < '2.7.0') {
                    // if this item is variation, get variation product instead
                    if ($item["data"]->product_type == "variation") {
                        $product = $product_factory->get_product($item["variation_id"]);
                    }
                    // exclude virtual and downloadable product
                    if ($item["data"]->virtual == "yes") {
                        continue;
                    }
                } else {
                    if ($item["data"]->get_type() == "variation") {
                        $product = $product_factory->get_product($item["variation_id"]);
                    }
                    if ($item["data"]->get_virtual() == "yes") {
                        continue;
                    }
                }

                $items[] = array(
                    "actual_weight" => $this->weightToKg($product->get_weight()),
                    "height" => $this->defaultDimension($this->dimensionToCm($product->get_height())),
                    "width" => $this->defaultDimension($this->dimensionToCm($product->get_width())),
                    "length" => $this->defaultDimension($this->dimensionToCm($product->get_length())),
                    "declared_currency" => $currency,
                    "declared_customs_value" => $this->declaredCustomsValue($item['line_subtotal'], $item['quantity']),
                    "identifier_id" => array_key_exists("variation_id", $item) ? ($item["variation_id"] == 0 ? $item["product_id"] : $item["variation_id"]) : $item["product_id"],
                    "sku" => $product->get_sku(),
                    "quantity" => $item['quantity']
                );
            }
            if (method_exists(WC()->cart, 'get_cart_contents_total')) {
                $total_cart_with_discount = (float)WC()->cart->get_cart_contents_total();
            } else {
                $total_cart_with_discount = WC()->cart->cart_contents_total;
            }
            if ($this->control_discount != $total_cart_with_discount) {
                if (is_array($items) && isset($items[0]) && isset($items[0]['declared_customs_value'])) {
                    $diff = round(($total_cart_with_discount - $this->control_discount), 2);
                    $items[0]['declared_customs_value'] += $diff;
                    $this->addControlDiscount($diff);
                    unset($diff);
                }
            }
            if (!class_exists('Easyship_Shipping_API')) {
                // Include Easyship API
                include_once 'easyship-api.php';
            }
            try {
                $token = $this->get_token();
                Easyship_Shipping_API::init($token['value']);
                $perferred_rates = Easyship_Shipping_API::getShippingRate($destination, $items);
            } catch (Exception $e) {
                // exception
                $perferred_rates = array();
            }
            foreach ($perferred_rates as $rate) {
                $shipping_rate = array(
                    'id' => $rate["courier_id"],
                    'label' => $rate["full_description"],
                    'cost' => $rate["total_charge"],
                    'meta_data' => array('courier_id' => $rate["courier_id"])
                );
                wp_cache_add('easyship' . $rate["courier_id"], $rate);
                $this->add_rate($shipping_rate);
            }
        }
        /**
         * @return array
         */
        protected function get_token()
        {
            if (!empty($this->token)) {
                return $this->token;
            }

            $token = 'es_access_token_' . get_current_network_id();
            if (!get_option($token) && !$this->get_option($token)) {
                $token = 'es_access_token';
            }

            $this->token = ['name' => $token, 'value' => $this->get_option($token) ?: get_option($token)];
            return $this->token;
        }

        protected function getShippingClass()
        {
            if (!empty($this->shipping_class)) {
                return $this->shipping_class;
            }

            $option_key = 'woocommerce_easyship_settings';
            $shopping_class_option = 'woocommerce_easyship_skip_shipping_class';

            $value = get_option($option_key);
            if (!empty($value) && is_string($value)) {
                $value = unserialize($value);
                $this->shipping_class = isset($value[$shopping_class_option]) ? $value[$shopping_class_option] : '';
            } else {
                $this->shipping_class = '';
            }

            return $this->shipping_class;
        }

        protected function setDiscountForItem($count)
        {
            $this->discount_for_item = $count;
        }
        protected function getDiscountForItem()
        {
            return $this->discount_for_item;
        }
        protected function addControlDiscount($val)
        {
            $this->control_discount += $val;
        }
        /**
         * @param $subtotal
         * @param $count
         * @return float
         */
        protected function declaredCustomsValue($subtotal, $count)
        {
            $price = (float)(($subtotal / $count) * ((100 - $this->getDiscountForItem()) / 100));
            $price = round($price, 2);
            $this->addControlDiscount(($price * $count));
            return $price;
        }
        /**
         * This function is convert weight to kg
         *
         * @access protected
         * @param number
         * @return number
         */
        protected function weightToKg($weight)
        {
            $weight_unit = get_option('woocommerce_weight_unit');
            // convert other unit into kg
            if ($weight_unit != 'kg') {
                if ($weight_unit == 'g') {
                    return $weight * 0.001;
                } else if ($weight_unit == 'lbs') {
                    return $weight * 0.453592;
                } else if ($weight_unit == 'oz') {
                    return $weight * 0.0283495;
                }
            }
            // already kg
            return $weight;
        }
        /**
         * This function is convert dimension to cm
         *
         * @access protected
         * @param number
         * @return number
         */
        protected function dimensionToCm($length)
        {
            $length = floatval($length);
            $dimension_unit = get_option('woocommerce_dimension_unit');
            // convert other units into cm
            if ($dimension_unit != 'cm') {
                if ($dimension_unit == 'm') {
                    return $length * 100;
                } else if ($dimension_unit == 'mm') {
                    return $length * 0.1;
                } else if ($dimension_unit == 'in') {
                    return $length * 2.54;
                } else if ($dimension_unit == 'yd') {
                    return $length * 91.44;
                }
            }
            // already in cm
            return $length;
        }
        /**
         * This function return default value for length
         *
         * @access protected
         * @param number
         * @return number
         */
        protected function defaultDimension($length)
        {
            // default dimension to 1 if it is 0
            return $length > 0 ? $length : 1;
        }
    }
}
function oauth_action_button_es()
{
    wp_enqueue_script(
        'oauth_action_button_es',
        plugin_dir_url(__FILE__) . 'assets/js/admin/ajax_oauth_es.js',
        array('jquery'),
        '5.0.4');
}
