<?php
/**
 * Page builder by SiteOrigin support
 *
 * @package Leto
 */


/* Defaults */
add_theme_support( 'siteorigin-panels', array( 
	'margin-bottom' => 0,
	'title-html' => '<h2 class="title-heading text-center">{{title}}</h2>'
) );

/* Theme widgets */
function leto_theme_widgets($widgets) {
	$theme_widgets = array(
		'Leto_Facts',
		'Leto_Product_Loop',
		'Leto_Featured_Boxes',
		'Leto_Blog',
		'Leto_Contact',
		'Leto_Tabbed_Products'
	);
	foreach($theme_widgets as $theme_widget) {
		if( isset( $widgets[$theme_widget] ) ) {
			$widgets[$theme_widget]['groups'] 	= array('leto-theme');
			$widgets[$theme_widget]['icon'] 	= 'dashicons dashicons-schedule';
		}
	}
	return $widgets;
}
add_filter('siteorigin_panels_widgets', 'leto_theme_widgets');

/* Add a tab for the theme widgets in the page builder */
function leto_theme_widgets_tab($tabs){
	$tabs[] = array(
		'title' => __('Leto Theme Widgets', 'leto'),
		'filter' => array(
			'groups' => array('leto-theme')
		)
	);
	return $tabs;
}
add_filter('siteorigin_panels_widget_dialog_tabs', 'leto_theme_widgets_tab', 20);



/**
 * Scripts and styles for the Page Builder plugin
 */
function leto_load_pagebuilder_scripts() {
	wp_enqueue_script( 'leto-pb-scripts', get_template_directory_uri() . '/js/pb-scripts.js', array('jquery'), '', true );

	wp_enqueue_style( 'leto-pb-styles', get_template_directory_uri() . '/css/pb-styles.css', array(), true );

	wp_enqueue_script( 'leto-chosen', get_template_directory_uri() . '/js/chosen.jquery.min.js', array('jquery', 'jquery-ui-sortable'), '', true );

	wp_enqueue_style( 'leto-chosen-styles', get_template_directory_uri() . '/css/chosen.min.css', array(), true );

}
add_action( 'siteorigin_panel_enqueue_admin_scripts', 'leto_load_pagebuilder_scripts' );