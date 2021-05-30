<?php
/**
 * Plugin Name:  Load More Anything
 * Plugin URI:   https://github.com/akshuvo/load-more-anything/
 * Author:       Akhtarujjaman Shuvo
 * Author URI:   https://www.facebook.com/akhterjshuvo/
 * Version: 	  2.4.0
 * Description:  A simple plugin that help you to Load more any item with jQuery. You can use Ajaxify Load More button for your blog post, Comments, page, Category, Recent Posts, Sidebar widget Data, Woocommerce Product, Images, Photos, Videos, custom Div or whatever you want.
 * License:      GPL2
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  aldtd
 * Domain Path:  /lang
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
* Including Plugin file for security
* Include_once
*
* @since 1.0.0
*/
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if ( !defined('ALD_PLUGIN_VERSION') ) {
    define('ALD_PLUGIN_VERSION', '2.3.1');
}

// Custom Functions
require_once( __DIR__ . '/inc/ald-functions.php' );

/**
* Add Setting Page Submenu
*/
function ald_add_submenu_page() {
	add_submenu_page(
		'options-general.php',
		'Load More Anything Settings page by AddonMaster',
		'Load More Anything',
		'manage_options',
		'ald_setting',
		'ald_settings_callback'
	);
}
add_action( 'admin_menu', 'ald_add_submenu_page' );

// Loading Text Domain
function ald_load_text_domain() {
	load_plugin_textdomain( 'aldtd', false, dirname( plugin_basename(__FILE__) ) . '/lang/' );
}
add_action('plugins_loaded', 'ald_load_text_domain', 10, 2);

// Load Scripts
function ald_enqueue_scripts(){

	wp_enqueue_style('ald-styles', plugin_dir_url( __FILE__ ) . 'css/ald-styles.css', null, ALD_PLUGIN_VERSION );
    wp_enqueue_script( 'ald-script', plugin_dir_url( __FILE__ ) . 'js/ald-scripts.js', array('jquery'), ALD_PLUGIN_VERSION, true );

	wp_localize_script( 'ald-script', 'ald_params',
		array(
	        'nonce' => wp_create_nonce( 'nonce' ),
	        'ajax_url' => admin_url( 'admin-ajax.php' ),
	    )
    );
}
add_action( 'wp_enqueue_scripts', 'ald_enqueue_scripts' );

// Admin Scripts
function ald_admin_scripts() {
	wp_enqueue_script('jquery');
	wp_enqueue_script( 'jquery-form' );
}
add_action( 'admin_enqueue_scripts', 'ald_admin_scripts' );

// Plugin activation hook
function ald_plugin_activation() {
    update_option('ald_plugin_version', '2.3.1');
}
register_activation_hook(__FILE__, 'ald_plugin_activation');

// Plugin Loaded
function ald_plugin_loaded_action() {
	if ( !is_admin() ) {
		return;
	}

	if ( false === get_option('ald_plugin_version') ){
    	update_option( 'ald_plugin_version', '2.3.1' );
	}

}
add_action('plugins_loaded', 'ald_plugin_loaded_action');

/**
* Design Setting Page
**/
function ald_settings_callback(){
$load_more_button_wrapper = __( 'Load More Button Selector', 'aldtd' );
$load_more_button_wrapper_desc = __( 'Load more button will be insert end of this selector', 'aldtd' );

$load_more_item_selector = __( 'Load More Items Selector', 'aldtd' );
$load_more_item_selector_desc = __( 'Selector for load more items. Example: <code>.parent_selector .items</code>', 'aldtd' );

$visiable_items = __( 'Visiable Items', 'aldtd' );
$visiable_items_desc = __( 'How many item will show initially', 'aldtd' );

$load_items = __( 'Load Items', 'aldtd' );
$load_items_desc = __( 'How Many Item Will Load When Click Load More Button?', 'aldtd' );

$button_label = __( 'Load More Button Label', 'aldtd' );
$button_label_desc = __( 'Enter the name of Load More Button <br> Use <code>+[count]</code> for countable button like +15 more', 'aldtd' );
?>
<div class="wrap">
<h1><?php esc_html_e( 'Load More Anyting', 'aldtd' ); ?></h1>

<form method="post" action="options.php" id="ald_option_form">
    <?php settings_fields( 'ald-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'ald-plugin-settings-group' ); ?>

	<table class="form-table">
		<tr>
			<td class="left-col">
				<h2 class="fakespace">&nbsp;</h2>
				<!-- Wrapper One Start -->
				<div id="postimagediv" class="postbox">
					<a class="header" data-toggle="collapse" href="#divone">
						<span id="poststuff">
							<h2 class="hndle"><?php esc_html_e( 'Wrapper - 1', 'aldtd' ); ?></h2>
						</span>
					</a>
					<div id="divone" class="collapse show">
						<div class="inside">
							<table class="form-table">
								<tr valign="top">
									<th scope="row"><?php _e( $load_more_button_wrapper ); ?></th>
									<td>
										<input class="regular-text" type="text" name="ald_wrapper_class" value="<?php echo esc_attr( get_option('ald_wrapper_class') ); ?>" />
										<p><?php _e( $load_more_button_wrapper_desc ); ?></p>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><?php _e( $load_more_item_selector ); ?></th>
									<td>
										<input class="regular-text" type="text" name="ald_load_class" value="<?php echo esc_attr( get_option('ald_load_class') ); ?>" />
										<p><?php _e( $load_more_item_selector_desc ); ?></p>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><?php _e( $visiable_items ); ?></th>
									<td>
										<input class="regular-text" type="number" name="ald_item_show" value="<?php echo esc_attr( get_option('ald_item_show') ); ?>" />
										<p><?php _e( $visiable_items_desc ); ?></p>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><?php _e( $load_items ); ?></th>
									<td>
										<input class="regular-text" type="number" name="ald_item_load" value="<?php echo esc_attr( get_option('ald_item_load') ); ?>" />
										<p><?php _e( $load_items_desc ); ?></p>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><?php _e( $button_label ); ?></th>
									<td>
										<input class="regular-text" type="text" name="ald_load_label" value="<?php echo esc_attr( get_option('ald_load_label') ); ?>" />
										<p><?php _e( $button_label_desc ) ?></p>
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
				<!-- Wrapper One end -->

				<!-- Wrapper Two Start -->
				<div id="postimagediv" class="postbox">
					<a class="header" data-toggle="collapse" href="#divtwo">
						<span id="poststuff">
							<h2 class="hndle"><?php esc_html_e( 'Wrapper - 2', 'aldtd' ); ?></h2>
						</span>
					</a>
					<div id="divtwo" class="collapse">
						<div class="inside">
							<table class="form-table">
								<tr valign="top">
									<th scope="row"><?php _e( $load_more_button_wrapper ); ?></th>
									<td>
										<input class="regular-text" type="text" name="ald_wrapper_classa" value="<?php echo esc_attr( get_option('ald_wrapper_classa') ); ?>" />
										<p><?php _e( $load_more_button_wrapper_desc ); ?></p>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><?php _e( $load_more_item_selector ); ?></th>
									<td>
										<input class="regular-text" type="text" name="ald_load_classa" value="<?php echo esc_attr( get_option('ald_load_classa') ); ?>" />
										<p><?php _e( $load_more_item_selector_desc ); ?></p>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><?php _e( $visiable_items ); ?></th>
									<td>
										<input class="regular-text" type="number" name="ald_item_showa" value="<?php echo esc_attr( get_option('ald_item_showa') ); ?>" />
										<p><?php _e( $visiable_items_desc ); ?></p>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><?php _e( $load_items ); ?></th>
									<td>
										<input class="regular-text" type="number" name="ald_item_loada" value="<?php echo esc_attr( get_option('ald_item_loada') ); ?>" />
										<p><?php _e( $load_items_desc ); ?></p>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><?php _e( $button_label ); ?></th>
									<td>
										<input class="regular-text" type="text" name="ald_load_labela" value="<?php echo esc_attr( get_option('ald_load_labela') ); ?>" />
										<p><?php _e( $button_label_desc ) ?></p>
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
				<!-- Wrapper Two end -->

				<!-- Wrapper Three Start -->
				<div id="postimagediv" class="postbox">
					<a class="header" data-toggle="collapse" href="#divthree">
						<span id="poststuff">
							<h2 class="hndle"><?php esc_html_e( 'Wrapper - 3', 'aldtd' ); ?></h2>
						</span>
					</a>
					<div id="divthree" class="collapse">
						<div class="inside">
							<table class="form-table">
								<tr valign="top">
									<th scope="row"><?php _e( $load_more_button_wrapper ); ?></th>
									<td>
										<input class="regular-text" type="text" name="ald_wrapper_classb" value="<?php echo esc_attr( get_option('ald_wrapper_classb') ); ?>" />
										<p><?php _e( $load_more_button_wrapper_desc ); ?></p>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><?php _e( $load_more_item_selector ); ?></th>
									<td>
										<input class="regular-text" type="text" name="ald_load_classb" value="<?php echo esc_attr( get_option('ald_load_classb') ); ?>" />
										<p><?php _e( $load_more_item_selector_desc ); ?></p>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><?php _e( $visiable_items ); ?></th>
									<td>
										<input class="regular-text" type="number" name="ald_item_showb" value="<?php echo esc_attr( get_option('ald_item_showb') ); ?>" />
										<p><?php _e( $visiable_items_desc ); ?></p>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><?php _e( $load_items ); ?></th>
									<td>
										<input class="regular-text" type="number" name="ald_item_loadb" value="<?php echo esc_attr( get_option('ald_item_loadb') ); ?>" />
										<p><?php _e( $load_items_desc ); ?></p>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><?php _e( $button_label ); ?></th>
									<td>
										<input class="regular-text" type="text" name="ald_load_labelb" value="<?php echo esc_attr( get_option('ald_load_labelb') ); ?>" />
										<p><?php _e( $button_label_desc ) ?></p>
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
				<!-- Wrapper Three end -->

				<!-- Wrapper Four Start -->
				<div id="postimagediv" class="postbox">
					<a class="header" data-toggle="collapse" href="#divfour">
						<span id="poststuff">
							<h2 class="hndle"><?php esc_html_e( 'Wrapper - 4', 'aldtd' ); ?></h2>
						</span>
					</a>
					<div id="divfour" class="collapse">
						<div class="inside">
							<table class="form-table">
								<tr valign="top">
									<th scope="row"><?php _e( $load_more_button_wrapper ); ?></th>
									<td>
										<input class="regular-text" type="text" name="ald_wrapper_classc" value="<?php echo esc_attr( get_option('ald_wrapper_classc') ); ?>" />
										<p><?php _e( $load_more_button_wrapper_desc ); ?></p>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><?php _e( $load_more_item_selector ); ?></th>
									<td>
										<input class="regular-text" type="text" name="ald_load_classc" value="<?php echo esc_attr( get_option('ald_load_classc') ); ?>" />
										<p><?php _e( $load_more_item_selector_desc ); ?></p>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><?php _e( $visiable_items ); ?></th>
									<td>
										<input class="regular-text" type="number" name="ald_item_showc" value="<?php echo esc_attr( get_option('ald_item_showc') ); ?>" />
										<p><?php _e( $visiable_items_desc ); ?></p>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><?php _e( $load_items ); ?></th>
									<td>
										<input class="regular-text" type="number" name="ald_item_loadc" value="<?php echo esc_attr( get_option('ald_item_loadc') ); ?>" />
										<p><?php _e( $load_items_desc ); ?></p>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><?php _e( $button_label ); ?></th>
									<td>
										<input class="regular-text" type="text" name="ald_load_labelc" value="<?php echo esc_attr( get_option('ald_load_labelc') ); ?>" />
										<p><?php _e( $button_label_desc ) ?></p>
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
				<!-- Wrapper Four end -->

				<!-- Wrapper Five Start -->
				<div id="postimagediv" class="postbox">
					<a class="header" data-toggle="collapse" href="#divfive">
						<span id="poststuff">
							<h2 class="hndle"><?php esc_html_e( 'Wrapper - 5', 'aldtd' ); ?></h2>
						</span>
					</a>
					<div id="divfive" class="collapse">
						<div class="inside">
							<table class="form-table">
								<tr valign="top">
									<th scope="row"><?php _e( $load_more_button_wrapper ); ?></th>
									<td>
										<input class="regular-text" type="text" name="ald_wrapper_classd" value="<?php echo esc_attr( get_option('ald_wrapper_classd') ); ?>" />
										<p><?php _e( $load_more_button_wrapper_desc ); ?></p>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><?php _e( $load_more_item_selector ); ?></th>
									<td>
										<input class="regular-text" type="text" name="ald_load_classd" value="<?php echo esc_attr( get_option('ald_load_classd') ); ?>" />
										<p><?php _e( $load_more_item_selector_desc ); ?></p>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><?php _e( $visiable_items ); ?></th>
									<td>
										<input class="regular-text" type="number" name="ald_item_showd" value="<?php echo esc_attr( get_option('ald_item_showd') ); ?>" />
										<p><?php _e( $visiable_items_desc ); ?></p>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><?php _e( $load_items ); ?></th>
									<td>
										<input class="regular-text" type="number" name="ald_item_loadd" value="<?php echo esc_attr( get_option('ald_item_loadd') ); ?>" />
										<p><?php _e( $load_items_desc ); ?></p>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><?php _e( $button_label ); ?></th>
									<td>
										<input class="regular-text" type="text" name="ald_load_labeld" value="<?php echo esc_attr( get_option('ald_load_labeld') ); ?>" />
										<p><?php _e( $button_label_desc ) ?></p>
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
				<!-- Wrapper Five end -->

				<!-- Wrapper Five Start -->
				<div id="postimagediv" class="postbox">
					<a class="header" data-toggle="collapse" href="#divsix">
						<span id="poststuff">
							<h2 class="hndle"><?php esc_html_e( 'Wrapper - 6 ( For Flex Display )', 'aldtd' ); ?></h2>
						</span>
					</a>
					<div id="divsix" class="collapse">
						<div class="inside">
							<table class="form-table">
								<tr valign="top">
									<th scope="row"><?php _e( $load_more_button_wrapper ); ?></th>
									<td>
										<input class="regular-text" type="text" name="ald_wrapper_classe" value="<?php echo esc_attr( get_option('ald_wrapper_classe') ); ?>" />
										<p><?php _e( $load_more_button_wrapper_desc ); ?></p>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><?php _e( $load_more_item_selector ); ?></th>
									<td>
										<input class="regular-text" type="text" name="ald_load_classe" value="<?php echo esc_attr( get_option('ald_load_classe') ); ?>" />
										<p><?php _e( $load_more_item_selector_desc ); ?></p>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><?php _e( $visiable_items ); ?></th>
									<td>
										<input class="regular-text" type="number" name="ald_item_showe" value="<?php echo esc_attr( get_option('ald_item_showe') ); ?>" />
										<p><?php _e( $visiable_items_desc ); ?></p>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><?php _e( $load_items ); ?></th>
									<td>
										<input class="regular-text" type="number" name="ald_item_loade" value="<?php echo esc_attr( get_option('ald_item_loade') ); ?>" />
										<p><?php _e( $load_items_desc ); ?></p>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><?php _e( $button_label ); ?></th>
									<td>
										<input class="regular-text" type="text" name="ald_load_labele" value="<?php echo esc_attr( get_option('ald_load_labele') ); ?>" />
										<p><?php _e( $button_label_desc ) ?></p>
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
				<!-- Wrapper Five end -->

			</td>
			<td class="right-col">
				<h2><?php esc_html_e( 'Custom CSS', 'aldtd' ); ?></h2>
				<pre><textarea style="width:100%" name="asr_ald_css_class" id="" rows="10"><?php if(empty(get_option('asr_ald_css_class'))){echo ".btn.loadMoreBtn {
    color: #333333;
    text-align: center;
}

.btn.loadMoreBtn:hover {
    text-decoration: none;
}";}else {echo __( get_option('asr_ald_css_class') ); } ?></textarea></pre>

			<table>
				<tr>
					<td>
						<strong>
							<h3 style=" margin: 0 0 2px 0; "><?php esc_html_e( 'Do you have any work need to be done ?', 'aldtd' ); ?></h3>
							<?php esc_html_e( 'We do WordPress Theme & Plugin development or Customization  and Website Maintainance', 'aldtd' ); ?>
							<a class="button" title="Get me in touch if you have any custom request" href="mailto:akhtarujjamanshuvo@gmail.com" style="vertical-align: middle; margin-left: 4px;"><?php esc_html_e( 'Email Us', 'aldtd' ); ?></a>
						</strong>
						<hr>
					</td>
				</tr>

				<tr><td><strong><?php esc_html_e( 'If you like my plugin please leave a review for inspire me', 'aldtd' ); ?> <a class="button" target="_blank" href="https://wordpress.org/support/plugin/ajax-load-more-anything/reviews/#new-post" style=" vertical-align: middle; margin-left: 4px; "><?php esc_html_e( 'Review Now', 'aldtd' ); ?></a></strong><hr></td></tr>

				<tr>
					<td>
						<div><strong><?php esc_html_e( 'Questions/Suggestions/Support:', 'aldtd' ); ?></strong></div>
						<a class="button" target="_blank" href="https://www.youtube.com/watch?v=km6V2bcfc6o" style="margin-left: 4px; "><?php esc_html_e( 'Video Tutorial', 'aldtd' ); ?></a>

						<a class="button" target="_blank" href="https://wordpress.org/support/plugin/ajax-load-more-anything" style="margin-left: 4px; "><?php esc_html_e( 'View Support Forum', 'aldtd' ); ?></a>

						<a class="button" target="_blank" href="https://github.com/akshuvo/load-more-anything/issues" style="margin-left: 4px; "><?php esc_html_e( 'Create Issue on Github', 'aldtd' ); ?></a>
					</td>
				</tr>

			</table>
			</td>
		</tr>
	</table>

    <?php ald_ajax_save_btn(); ?>

</form>
</div>



<?php }

/*
* Register settings fields to database
*/
function register_ald_plugin_settings() {

	// wrapper one option data
	register_setting( 'ald-plugin-settings-group', 'ald_wrapper_class' );
	register_setting( 'ald-plugin-settings-group', 'ald_load_class' );
	register_setting( 'ald-plugin-settings-group', 'ald_item_show' );
	register_setting( 'ald-plugin-settings-group', 'ald_item_load' );
	register_setting( 'ald-plugin-settings-group', 'ald_load_label' );
	register_setting( 'ald-plugin-settings-group', 'asr_ald_css_class' );

	// wrapper two option data
	register_setting( 'ald-plugin-settings-group', 'ald_wrapper_classa' );
	register_setting( 'ald-plugin-settings-group', 'ald_load_classa' );
	register_setting( 'ald-plugin-settings-group', 'ald_item_showa' );
	register_setting( 'ald-plugin-settings-group', 'ald_item_loada' );
	register_setting( 'ald-plugin-settings-group', 'ald_load_labela' );

	// wrapper three option data
	register_setting( 'ald-plugin-settings-group', 'ald_wrapper_classb' );
	register_setting( 'ald-plugin-settings-group', 'ald_load_classb' );
	register_setting( 'ald-plugin-settings-group', 'ald_item_showb' );
	register_setting( 'ald-plugin-settings-group', 'ald_item_loadb' );
	register_setting( 'ald-plugin-settings-group', 'ald_load_labelb' );

	// wrapper four option data
	register_setting( 'ald-plugin-settings-group', 'ald_wrapper_classc' );
	register_setting( 'ald-plugin-settings-group', 'ald_load_classc' );
	register_setting( 'ald-plugin-settings-group', 'ald_item_showc' );
	register_setting( 'ald-plugin-settings-group', 'ald_item_loadc' );
	register_setting( 'ald-plugin-settings-group', 'ald_load_labelc' );

	// wrapper five option data
	register_setting( 'ald-plugin-settings-group', 'ald_wrapper_classd' );
	register_setting( 'ald-plugin-settings-group', 'ald_load_classd' );
	register_setting( 'ald-plugin-settings-group', 'ald_item_showd' );
	register_setting( 'ald-plugin-settings-group', 'ald_item_loadd' );
	register_setting( 'ald-plugin-settings-group', 'ald_load_labeld' );

	// wrapper five option data
	register_setting( 'ald-plugin-settings-group', 'ald_wrapper_classe' );
	register_setting( 'ald-plugin-settings-group', 'ald_load_classe' );
	register_setting( 'ald-plugin-settings-group', 'ald_item_showe' );
	register_setting( 'ald-plugin-settings-group', 'ald_item_loade' );
	register_setting( 'ald-plugin-settings-group', 'ald_load_labele' );
}
add_action( 'admin_init', 'register_ald_plugin_settings' );

/**
 * Adds plugin action links.
 *
 * @since 1.0.0
 * @version 4.0.0
 */
function wi_plugin_action_links( $links ) {
	$plugin_links = array(
		'<a href="options-general.php?page=ald_setting">' . esc_html__( 'Settings', 'ald' ) . '</a>',
	);
	return array_merge( $plugin_links, $links );
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wi_plugin_action_links' );