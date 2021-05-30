<?php
/**
 * Leto Theme Customizer
 *
 * @package Leto
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function leto_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
    $wp_customize->get_section( 'colors' )->panel 				= 'leto_colors';
    $wp_customize->get_section( 'colors' )->priority 			= '7';
    $wp_customize->get_section( 'colors' )->title = 			__( 'General' , 'leto');
    $wp_customize->get_section( 'header_image' )->priority 		= '6';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => 'leto_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => 'leto_customize_partial_blogdescription',
		) );
	}
}
add_action( 'customize_register', 'leto_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function leto_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function leto_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Kirki
 */
require get_template_directory() . '/inc/kirki/include-kirki.php';
require get_template_directory() . '/inc/kirki/class-leto-kirki.php';

Leto_Kirki::add_config( 'leto', array(
	'capability'    => 'edit_theme_options',
	'option_type'   => 'theme_mod',
) );
Leto_Kirki::add_section( 'leto_footer', array(
    'title'          => esc_attr__( 'Footer', 'leto' ),
    'panel'          => '',
    'priority'       => 21,
) );
Leto_Kirki::add_field( 'leto', array(
	'type'        => 'checkbox',
	'settings'    => 'leto_boxed_footer',
	'label'       => esc_attr__( 'Make the footer boxed?', 'leto' ),
	'section'     => 'leto_footer',
	'default'     => '0',
	'priority'    => 10,
) );

/* Search */
Leto_Kirki::add_section( 'leto_search', array(
    'title'          => esc_attr__( 'Search', 'leto' ),
    'panel'          => 'leto_header_panel',
    'priority'       => 31,
) );
Leto_Kirki::add_field( 'leto', array(
	'type'        => 'checkbox',
	'settings'    => 'leto_enable_search',
	'label'       => esc_attr__( 'Enable search popup', 'leto' ),
	'section'     => 'leto_search',
	'default'     => 1,
	'priority'    => 10,
) );
Leto_Kirki::add_field( 'leto', array(
	'type'        => 'text',
	'settings'    => 'leto_search_title',
	'label'       => esc_attr__( 'Search title', 'leto' ),
	'section'     => 'leto_search',
	'default'     => esc_attr__( 'Search', 'leto' ),
	'priority'    => 10,
	'transport'	  => 'postMessage',
	'js_vars'   => array(
		array(
			'element'  => '.search-box__title .inner',
			'function' => 'html',
		),
	)	
) );

/* Woocommerce options */
Leto_Kirki::add_panel( 'leto_woocommerce_panel', array(
    'priority'    => 21,
    'title'       => esc_attr__( 'Leto: WooCommerce', 'leto' ),
) );


//General
Leto_Kirki::add_section( 'leto_woocommerce_general', array(
    'title'          => esc_attr__( 'General', 'leto' ),
    'panel'          => 'leto_woocommerce_panel',
    'priority'       => 1,
) );
Leto_Kirki::add_field( 'leto', array(
	'type'        => 'checkbox',
	'settings'    => 'leto_show_menu_additions',
	'label'       => esc_attr__( 'Show Login, Cart and Search in menu?', 'leto' ),
	'section'     => 'leto_woocommerce_general',
	'default'     => '1',
	'priority'    => 10,
) );



//Single product
Leto_Kirki::add_section( 'leto_single_product', array(
    'title'          => esc_attr__( 'Single products', 'leto' ),
    'panel'          => 'leto_woocommerce_panel',
    'priority'       => 1,
) );
Leto_Kirki::add_field( 'leto', array(
	'type'        => 'checkbox',
	'settings'    => 'leto_hide_product_ratings_singles',
	'label'       => esc_attr__( 'Hide product rating on single products?', 'leto' ),
	'section'     => 'leto_single_product',
	'default'     => '0',
	'priority'    => 10,
) );

//Product archives
Leto_Kirki::add_section( 'leto_product_archives', array(
    'title'          => esc_attr__( 'Product archives', 'leto' ),
    'panel'          => 'leto_woocommerce_panel',
    'priority'       => 2,
) );
Leto_Kirki::add_field( 'leto', array(
	'type'        => 'checkbox',
	'settings'    => 'leto_fullwidth_shop_archives',
	'label'       => esc_attr__( 'Fullwidth archives?', 'leto' ),
	'section'     => 'leto_product_archives',
	'default'     => '0',
	'priority'    => 10,
) );
Leto_Kirki::add_field( 'leto', array(
	'type'        => 'number',
	'settings'    => 'leto_archives_products_no',
	'label'       => esc_attr__( 'Number of products on shop archives', 'leto' ),
	'section'     => 'leto_product_archives',
	'default'     => 10,
	'priority'    => 10,
	'choices'     => array(
		'min'  => 2,
		'max'  => 50,
		'step' => 1,
	),
) );
Leto_Kirki::add_field( 'leto', array(
	'type'        => 'select',
	'settings'    => 'leto_columns_number',
	'label'       => __( 'Number of columns', 'leto' ),
	'section'     => 'leto_product_archives',
	'default'     => '4',
	'priority'    => 10,
	'multiple'    => 1,
	'choices'     => array(
		'2'	=> __( 'Two', 'leto' ),
		'3'	=> __( 'Three', 'leto' ),
		'4'	=> __( 'Four', 'leto' ),

	)
) );
Leto_Kirki::add_field( 'leto', array(
	'type'        => 'checkbox',
	'settings'    => 'leto_disable_quickview',
	'label'       => esc_attr__( 'Disable quickview links?', 'leto' ),
	'section'     => 'leto_product_archives',
	'default'     => '0',
	'priority'    => 10,
) );
Leto_Kirki::add_field( 'leto', array(
	'type'        => 'radio',
	'settings'    => 'leto_modal_images_display_mode',
	'label'       => __( 'Quickview image mode', 'leto' ),
	'section'     => 'leto_product_archives',
	'default'     => 'complete',
	'priority'    => 10,
	'multiple'    => 1,
	'choices'     => array(
		'complete'		=> __( 'Featured image + gallery', 'leto' ),
		'only-thumb'	=> __( 'Only featured image', 'leto' ),
	)
) );
Leto_Kirki::add_field( 'leto', array(
	'type'        => 'checkbox',
	'settings'    => 'leto_hide_product_price',
	'label'       => esc_attr__( 'Hide product price?', 'leto' ),
	'section'     => 'leto_product_archives',
	'default'     => '0',
	'priority'    => 10,
) );
Leto_Kirki::add_field( 'leto', array(
	'type'        => 'checkbox',
	'settings'    => 'leto_hide_product_ratings',
	'label'       => esc_attr__( 'Hide product ratings?', 'leto' ),
	'section'     => 'leto_product_archives',
	'default'     => '0',
	'priority'    => 10,
) );
Leto_Kirki::add_field( 'leto', array(
	'type'        => 'checkbox',
	'settings'    => 'leto_hide_product_sorting',
	'label'       => esc_attr__( 'Hide sorting?', 'leto' ),
	'section'     => 'leto_product_archives',
	'default'     => '0',
	'priority'    => 10,
) );
Leto_Kirki::add_field( 'leto', array(
	'type'        => 'checkbox',
	'settings'    => 'leto_hide_results_number',
	'label'       => esc_attr__( 'Hide number of results?', 'leto' ),
	'section'     => 'leto_product_archives',
	'default'     => '0',
	'priority'    => 10,
) );


/* Typography */
Leto_Kirki::add_panel( 'leto_typography', array(
    'priority'    => 21,
    'title'       => esc_attr__( 'Typography', 'leto' ),
) );
Leto_Kirki::add_section( 'leto_font_families', array(
    'title'          => esc_attr__( 'Font families', 'leto' ),
    'priority'       => 31,
    'panel'			 => 'leto_typography'
) );

if ( class_exists( 'Kirki_Fonts' ) ) {
	Leto_Kirki::add_field( 'leto', array(
		'type'        => 'select',
		'settings'    => 'leto_body_fonts',
		'label'       => __( 'Body fonts', 'leto' ),
		'section'     => 'leto_font_families',
		'default'     => 'Rubik',
		'priority'    => 10,
		'multiple'    => 1,
		'choices'     => Kirki_Fonts::get_font_choices(),
		'transport'	  => 'refresh',
	    'output'      => array(
	        array(
	            'element'  => 'h1,h2,h3,h4,h5,h6,.site-title',
	            'property' => 'font-family',
	        ),
	    ),	
	) );
	Leto_Kirki::add_field( 'leto', array(
		'type'        => 'select',
		'settings'    => 'leto_headings_fonts',
		'label'       => __( 'Headings fonts', 'leto' ),
		'section'     => 'leto_font_families',
		'default'     => 'Rubik',
		'priority'    => 11,
		'multiple'    => 1,
		'choices'     => Kirki_Fonts::get_font_choices(),
		'transport'	  => 'refresh',
	    'output'      => array(
	        array(
	            'element'  => 'h1,h2,h3,h4,h5,h6,.site-title',
	            'property' => 'font-family',
	        ),
	    ),	
	) );
}
Leto_Kirki::add_section( 'leto_font_sizes', array(
    'title'          => esc_attr__( 'Font sizes', 'leto' ),
    'priority'       => 31,
    'panel'			 => 'leto_typography'    
) );


$font_sizes = array(
	'site_title' => array(
		'settings'  => 'leto_site_title_size',
		'label' 	=> __( 'Site title', 'leto' ),
		'default' 	=> 36,
		'choices'   => array(
			'min'  => 16,
			'max'  => 50,
			'step' => 1,
		),
		'element'  => '.site-title'
	),
	'site_description' => array(
		'settings'  => 'leto_site_description_size',
		'label' 	=> __( 'Site description', 'leto' ),
		'default' 	=> 14,
		'choices'   => array(
			'min'  => 12,
			'max'  => 24,
			'step' => 1,
		),
		'element'  => '.site-description'
	),
	'menu_items' => array(
		'settings'  => 'leto_menu_items_size',
		'label' 	=> __( 'Menu items', 'leto' ),
		'default' 	=> 16,
		'choices'   => array(
			'min'  => 12,
			'max'  => 24,
			'step' => 1,
		),
		'element'  => '.main-navigation ul li'
	),
);

foreach ( $font_sizes as $font_size_option ) {
	Leto_Kirki::add_field( 'leto', array(
		'type'     	  => 'slider',
		'settings'    => $font_size_option['settings'],
		'label'       => $font_size_option['label'],
		'section'     => 'leto_font_sizes',
		'default'     => $font_size_option['default'],
		'priority'    => 10,
		'multiple'    => 1,
		'choices'  => $font_size_option['choices'],
		'transport'	  => 'auto',
	    'output'      => array(
			array(
				'element'  => $font_size_option['element'],
				'property' => 'font-size',
				'units'    => 'px',
			),
	    ),	
	) );
}

/* Blog options */
Leto_Kirki::add_panel( 'leto_blog_panel', array(
    'priority'    => 31,
    'title'       => esc_attr__( 'Blog', 'leto' ),
) );
//Blog archives
Leto_Kirki::add_section( 'leto_blog_archives', array(
    'title'          => esc_attr__( 'Index/Archives', 'leto' ),
    'panel'          => 'leto_blog_panel',
    'priority'       => 1,
) );
Leto_Kirki::add_field( 'leto', array(
	'type'        => 'checkbox',
	'settings'    => 'leto_fullwidth_blog_archives',
	'label'       => esc_attr__( 'Show sidebar on blog index and archives?', 'leto' ),
	'section'     => 'leto_blog_archives',
	'default'     => '0',
	'priority'    => 10,
) );
Leto_Kirki::add_field( 'leto', array(
	'type'        => 'checkbox',
	'settings'    => 'leto_blog_excerpt',
	'label'       => esc_attr__( 'Show post excerpts?', 'leto' ),
	'section'     => 'leto_blog_archives',
	'default'     => '0',
	'priority'    => 10,
) );

Leto_Kirki::add_field( 'leto', array(
	'type'        => 'number',
	'settings'    => 'leto_exc_length',
	'label'       => esc_attr__( 'Excerpt length', 'leto' ),
	'section'     => 'leto_blog_archives',
	'default'     => 20,
	'priority'    => 10,
	'choices'     => array(
		'min'  => 5,
		'max'  => 60,
		'step' => 1,
	),
) );
Leto_Kirki::add_field( 'leto', array(
	'type'        => 'text',
	'settings'    => 'leto_custom_read_more',
	'label'       => esc_attr__( 'Custom read more text', 'leto' ),
	'section'     => 'leto_blog_archives',
	'default'     => '',
	'priority'    => 10,
) );
Leto_Kirki::add_field( 'leto', array(
	'type'        => 'checkbox',
	'settings'    => 'leto_index_hide_meta',
	'label'       => esc_attr__( 'Hide post categories?', 'leto' ),
	'section'     => 'leto_blog_archives',
	'default'     => '0',
	'priority'    => 10,
) );
Leto_Kirki::add_field( 'leto', array(
	'type'        => 'checkbox',
	'settings'    => 'leto_index_hide_date',
	'label'       => esc_attr__( 'Hide post date?', 'leto' ),
	'section'     => 'leto_blog_archives',
	'default'     => '0',
	'priority'    => 10,
) );
//Blog archives
Leto_Kirki::add_section( 'leto_blog_singles', array(
    'title'          => esc_attr__( 'Single posts', 'leto' ),
    'panel'          => 'leto_blog_panel',
    'priority'       => 2,
) );
Leto_Kirki::add_field( 'leto', array(
	'type'        => 'checkbox',
	'settings'    => 'leto_single_hide_thumb',
	'label'       => esc_attr__( 'Hide post thumbnail?', 'leto' ),
	'section'     => 'leto_blog_singles',
	'default'     => '0',
	'priority'    => 10,
) );
Leto_Kirki::add_field( 'leto', array(
	'type'        => 'checkbox',
	'settings'    => 'leto_single_hide_meta',
	'label'       => esc_attr__( 'Hide post categories?', 'leto' ),
	'section'     => 'leto_blog_singles',
	'default'     => '0',
	'priority'    => 10,
) );
Leto_Kirki::add_field( 'leto', array(
	'type'        => 'checkbox',
	'settings'    => 'leto_single_hide_date',
	'label'       => esc_attr__( 'Hide post date?', 'leto' ),
	'section'     => 'leto_blog_singles',
	'default'     => '0',
	'priority'    => 10,
) );


/* Colors */

Leto_Kirki::add_panel( 'leto_colors', array(
    'priority'    => 10,
    'title'       => esc_attr__( 'Colors', 'leto' ),
) );
Leto_Kirki::add_field( 'leto', array(
	'type'        => 'color',
	'settings'    => 'leto_body_color',
	'label'       => esc_attr__( 'Body (general)', 'leto' ),
	'section'     => 'colors',
	'default'     => '#333333',
	'priority'    => 10,
	'transport'	  => 'auto',
    'output'      => array(
        array(
            'element'  => 'body',
            'property' => 'color',
        ),
    ),	
) );
Leto_Kirki::add_field( 'leto', array(
	'type'        => 'color',
	'settings'    => 'leto_post_content_color',
	'label'       => esc_attr__( 'Body (post content)', 'leto' ),
	'section'     => 'colors',
	'default'     => '#666666',
	'priority'    => 10,
	'transport'	  => 'auto',
    'output'      => array(
        array(
            'element'  => '.entry-content',
            'property' => 'color',
        ),
    ),	
) );
Leto_Kirki::add_section( 'leto_header_colors', array(
    'title'          => esc_attr__( 'Header', 'leto' ),
    'panel'          => 'leto_colors',
    'priority'       => 10,
) );
Leto_Kirki::add_field( 'leto', array(
	'type'        => 'color',
	'settings'    => 'leto_site_title_color',
	'label'       => esc_attr__( 'Site title', 'leto' ),
	'section'     => 'leto_header_colors',
	'default'     => '#000000',
	'priority'    => 10,
	'transport'	  => 'auto',
    'output'      => array(
        array(
            'element'  => '.site-title,.site-title a',
            'property' => 'color',
        ),
    ),	
) );
Leto_Kirki::add_field( 'leto', array(
	'type'        => 'color',
	'settings'    => 'leto_site_desc_color',
	'label'       => esc_attr__( 'Site description', 'leto' ),
	'section'     => 'leto_header_colors',
	'default'     => '#333333',
	'priority'    => 10,
	'transport'	  => 'auto',
    'output'      => array(
        array(
            'element'  => '.site-description',
            'property' => 'color',
        ),
    ),	
) );
Leto_Kirki::add_field( 'leto', array(
	'type'        => 'color',
	'settings'    => 'leto_menu_items_color',
	'label'       => esc_attr__( 'Menu items', 'leto' ),
	'section'     => 'leto_header_colors',
	'default'     => '#000000',
	'priority'    => 10,
	'transport'	  => 'auto',
    'output'      => array(
        array(
            'element'  => '.main-navigation ul li a, .nav-link-right a',
            'property' => 'color',
        ),
    ),	
) );

//Footer colors
Leto_Kirki::add_section( 'leto_footer_colors', array(
    'title'          => esc_attr__( 'Footer', 'leto' ),
    'panel'          => 'leto_colors',
    'priority'       => 11,
) );
Leto_Kirki::add_field( 'leto', array(
	'type'        => 'color',
	'settings'    => 'leto_footer_background',
	'label'       => esc_attr__( 'Footer background', 'leto' ),
	'section'     => 'leto_footer_colors',
	'default'     => '#ffffff',
	'priority'    => 10,
	'transport'	  => 'auto',
    'output'      => array(
        array(
            'element'  => '.site-footer',
            'property' => 'background-color',
        ),
    ),	
) );


/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function leto_customize_preview_js() {
	wp_enqueue_script( 'leto-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'leto_customize_preview_js' );
