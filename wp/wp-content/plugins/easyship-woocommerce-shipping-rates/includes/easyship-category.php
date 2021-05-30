<?php
/*
Class Easyship_Category
Author: Easyship
Developer: Sunny Cheung, Holubiatnikova Anna, Aloha Chen, Paul Lugagne Delpon
Version: 0.4.8
Author URI: https://www.easyship.com
*/


if ( ! class_exists( 'Easyship_Category' ) ) {
    class Easyship_Category {
        private static $easyship_categories = array(
                             'mobile_phones'         => 'Mobile Phones',
                             'tablets'               => 'Tablets',
                             'computers_laptops'     => 'Computers & Laptops',
                             'cameras'               => 'Cameras',
                             'accessory_no_battery'  => 'Accessory (no-battery)',
                             'accessory_with_battery'=> 'Accessory (with battery)',
                             'health_beauty'         => 'Health & Beauty',
                             'fashion'               => 'Fashion',
                             'watches'               => 'Watches',
                             'home_appliances'       => 'Home Appliances',
                             'home_decor'            => 'Home Decor',
                             'toys'                  => 'Toys',
                             'sport_leisure'         => 'Sport & Leisure',
                             'bags_luggages'         => 'Bags & Luggages',
                             'audio_video'           => 'Audio Video',
                             'documents'             => 'Documents',
                             'jewelry'               => 'Jewellery',
                             'dry_food_supplements'  => 'Dry Food & Supplements',
                             'books_collectibles'    => 'Books & Collectibles',
                             'pet_accessory'         => 'Pet Accessory',
                             'gaming'                => 'Gaming'
                        );

        /**
         *  get Category by Key
         *  @access public
         *  @param string
         *  @return string
         */
        public static function get_category_by_key( $key ) {
            return isset( self::$easyship_categories[$key] )? self::$easyship_categories[$key] : null;
        }

        /**
         * get All categories
         * @access public
         * @return array
        */
        public static function get_categories() {
            return self::$easyship_categories;
        }

    }
}
