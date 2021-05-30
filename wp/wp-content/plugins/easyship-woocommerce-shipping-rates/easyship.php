<?php
/**
 * @package Easyship
 * @version 0.8.5
 */
/*
Plugin Name: Easyship
Plugin URI: http://wordpress.org/plugins/easyship
Description: Easyship plugin for easy shipping method
Author: Easyship
Developer: Sunny Cheung, Holubiatnikova Anna, Aloha Chen, Paul Lugagne Delpon, Bernie Chiu
Version: 0.8.5
Author URI: https://www.easyship.com
*/

define('EASYSHIP_VERSION', '0.8.0');

if ( ! defined( 'WPINC' ) ) {

    die;
}

include_once 'includes/easyship-registration.php';
include_once 'includes/easyship-endpoints.php';
/*
 * Check if WooCommerce is active
 */
// if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    if ( ! class_exists( 'WC_Integration_Easyship' ) ) {
        class WC_Integration_Easyship
        {
            protected $endpoints;
            /**
             * Construct the plugin.
             */
            public function __construct() {
                add_action('init', array( $this, 'register_session' ) );
                add_action('woocommerce_shipping_init', array( $this, 'init' ) );
                add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'plugin_action_links'));
                add_action('wp_ajax_oauth_es', array($this, 'oauth_es_callback'));
                add_action('wp_ajax_es_disabled', array($this, 'es_disabled_callback'));
                $this->endpoints = new Easyship_Endpoints();
            }
            /**
             * Initialize the plugin.
             */
            public function init()
            {
                // start a session
                add_filter('woocommerce_shipping_methods', array($this, 'add_shipping_method'));
                // Checks if WooCommerce is installed.
                if (class_exists('WC_Integration')) {
                    // Include our integration class.
                    include_once 'includes/easyship-shipping.php';
                    // Register the integration.
                    add_filter('woocommerce_shipping_methods', array($this, 'add_integration'));
                }
            }
            /**
             * Add a new integration to WooCommerce.
             */
            public function add_integration($integrations)
            {
                $integrations[] = 'Easyship_Shipping_Method';
                return $integrations;
            }
            /**
             *  Register a session
             */
            public function register_session()
            {
                if (session_status() == PHP_SESSION_NONE && !headers_sent())
                    session_start();
                    session_write_close();
            }
            /**
             *  Add Settings link to plugin page
             */
            public function plugin_action_links($links)
            {
                return array_merge(
                    $links,
                    array('<a href="' . admin_url('admin.php?page=wc-settings&tab=shipping&section=easyship') . '"> ' . __('Settings', 'easyship') . '</a>')
                );
            }
            public function add_shipping_method($methods)
            {
                if (is_array($methods)) {
                    $methods['easyship'] = 'Easyship_Shipping_Method';
                }
                return $methods;
            }
            public function oauth_es_callback()
            {
                $obj = new Easyship_Registration();
                echo $obj->sendRequest();
                die;
            }
            public function es_disabled_callback()
            {
                $option_name = 'es_access_token_' . get_current_network_id();
                update_option($option_name, '');
                echo 'success';
                wp_die();
            }
        }
        $WC_Integration_Easyship = new WC_Integration_Easyship();
    }
//}
// /**
//  *  Plugin Activation function
//  *  @return void
//  */
// function easyship_plugin_activate() {
//     $easyship_categories = Easyship_Category::get_categories();
//     foreach ( $easyship_categories as $key => $cat ) {
//         wp_insert_term(
//             $cat,
//             'product_cat',
//             array(
//                 'description' => $cat,
//                 'slug' => $key
//             )
//         );
//     }
// }
// register_activation_hook( __FILE__, 'easyship_plugin_activate' );
