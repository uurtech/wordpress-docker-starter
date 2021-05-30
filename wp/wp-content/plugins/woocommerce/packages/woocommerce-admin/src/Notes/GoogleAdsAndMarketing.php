<?php
/**
 * WooCommerce Admin Google Ads and Marketing Note Provider.
 *
 * Adds notes to the merchant's inbox concerning Google Ads and Marketing Extension.
 *
 * @package WooCommerce\Admin
 */

namespace Automattic\WooCommerce\Admin\Notes;

defined( 'ABSPATH' ) || exit;

use \Automattic\WooCommerce\Admin\PluginsHelper;

/**
 * WC_Admin_Notes_Google_Ads_And_Marketing
 */
class GoogleAdsAndMarketing {
	/**
	 * Note traits.
	 */
	use NoteTraits;

	/**
	 * Name of the note for use in the database.
	 */
	const NOTE_NAME = 'wc-admin-google-ads-and-marketing';

	/**
	 * Name of plugin file.
	 */
	const PLUGIN_FILE = 'kliken-marketing-for-google/kliken-marketing-for-google.php';

	/**
	 * Possibly add note.
	 */
	public static function possibly_add_note() {

		// Check if the note can and should be added.
		if ( ! self::can_be_added() ) {
			return;
		}

		// Only add the note to stores with Google Ads and Marketing installed.
		if ( ! self::is_google_ads_and_marketing_installed() ) {
			return;
		}

		// Only add the note to stores with at least 20 orders in the last month.
		if ( self::orders_last_month() < 20 ) {
			return;
		}

		$note = self::get_note();
		$note->save();
	}

	/**
	 * Get the note.
	 *
	 * @return Note
	 */
	public static function get_note() {
		$note = new Note();
		$note->set_title( __( 'Get your products in front of millions of shoppers on Google to grow your sales', 'woocommerce' ) );
		$note->set_content( __( 'Google Ads & Marketing makes it easy to promote products on any budget. Run paid Smart Shopping campaigns to get your top selling products in front of buyers across the Google Network. You can also drive free traffic to your store with free listings for only $10 per month!', 'woocommerce' ) );
		$note->set_type( Note::E_WC_ADMIN_NOTE_INFORMATIONAL );
		$note->set_name( self::NOTE_NAME );
		$note->set_content_data( (object) array() );
		$note->set_source( 'woocommerce-admin' );
		$note->add_action(
			'get-started',
			__( 'Get started', 'woocommerce' ),
			'https://woocommerce.com/products/google-ads-and-marketing/',
			Note::E_WC_ADMIN_NOTE_ACTIONED,
			true
		);
		return $note;
	}

	/**
	 * Determine if Google Ads and Marketing is already active or installed
	 *
	 * @return bool
	 */
	protected static function is_google_ads_and_marketing_installed() {
		if ( function_exists( 'kk_wc_plugin' ) ) {
			return true;
		}
		return PluginsHelper::is_plugin_installed( self::PLUGIN_FILE );
	}

	/**
	 * Determine the number of orders in the last month
	 *
	 * @return int
	 */
	protected static function orders_last_month() {

		$date = new \DateTime();

		$args = array(
			'date_created' => '>' . $date->modify( '-1 month' )->format( 'Y-m-d' ),
			'return'       => 'ids',
		);

		return count( wc_get_orders( $args ) );
	}
}
