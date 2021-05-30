<?php
/**
 * Leto functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Leto
 */

if ( ! function_exists( 'leto_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function leto_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Leto, use a find and replace
	 * to change 'leto' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'leto', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	add_image_size( 'leto-large-thumb', 780 );
	add_image_size( 'leto-medium-thumb', 400 );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'menu-1' 		=> esc_html__( 'Primary menu', 'leto' ),
		'footer_menu' 	=> esc_html__( 'Footer menu', 'leto' ),

	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'leto_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support( 'custom-logo', array(
		'height'      => 100,
		'width'       => 100,
		'flex-width'  => true,
		'flex-height' => true,
	) );
}
endif;
add_action( 'after_setup_theme', 'leto_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function leto_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'leto_content_width', 640 );
}
add_action( 'after_setup_theme', 'leto_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function leto_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'leto' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'leto' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	for ( $i=1; $i <= 4; $i++ ) {
		register_sidebar( array(
			'name'          => __( 'Footer ', 'leto' ) . $i,
			'id'            => 'footer-' . $i,
			'description'   => '',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
	}	

	if ( class_exists('WooCommerce') ) {
		register_widget( 'Leto_Product_Loop' );
	}

	register_widget( 'Leto_Facts' );
	register_widget( 'Leto_Featured_Boxes');
	register_widget( 'Leto_Blog' );
	register_widget( 'Leto_Sidebar_Posts' );
	register_widget( 'Leto_Gallery' );
	register_widget( 'Leto_Social' );
}
add_action( 'widgets_init', 'leto_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function leto_scripts() {
	wp_enqueue_style( 'leto-style', get_stylesheet_uri() );

	wp_enqueue_style( 'leto-fonts', '//fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i', array(), null );

	wp_enqueue_script( 'leto-scripts', get_template_directory_uri() . '/js/plugins.js', array('jquery'),'20170711', true );

	wp_enqueue_script( 'leto-main', get_template_directory_uri() . '/js/main.js', array('jquery', 'imagesloaded'), '20171108', true );

	wp_enqueue_style( 'ionicons', get_template_directory_uri() . '/css/ionicons.min.css' );

	wp_enqueue_style( 'leto-plugins-css', get_template_directory_uri() . '/css/plugins.css' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'leto_scripts' );

/**
 * Enqueue Bootstrap
 */
function leto_enqueue_bootstrap() {
	wp_enqueue_style( 'leto-bootstrap', get_template_directory_uri() . '/css/bootstrap/bootstrap.min.css', array(), true );
}
add_action( 'wp_enqueue_scripts', 'leto_enqueue_bootstrap', 9 );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';
require get_template_directory() . '/inc/footer-functions.php';
require get_template_directory() . '/inc/header-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load Woocommerce compatibility file.
 */
if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/inc/woocommerce.php';
}

/**
 * Load Page Builder compatibility file.
 */
require get_template_directory() . '/inc/page-builder.php';


/**
 * Widgets
 */
require get_template_directory() . '/widgets/class-leto-facts.php';
require get_template_directory() . '/widgets/class-leto-product-loop.php';
require get_template_directory() . '/widgets/class-leto-featured-boxes.php';
require get_template_directory() . '/widgets/class-leto-blog.php';
require get_template_directory() . '/widgets/class-leto-sidebar-posts.php';
require get_template_directory() . '/widgets/class-leto-gallery.php';
require get_template_directory() . '/widgets/class-leto-social.php';


/**
 * Recommend plugins
 */
require_once get_template_directory() . '/inc/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'leto_recommended_plugins' );
function leto_recommended_plugins() {

    $plugins[] = array(
            'name'               => 'Page Builder by SiteOrigin',
            'slug'               => 'siteorigin-panels',
            'required'           => false,
    );

    $plugins[] = array(
            'name'               => 'WooCommerce',
            'slug'               => 'woocommerce',
            'required'           => false,
    ); 

    $plugins[] = array(
            'name'               => 'Kirki',
            'slug'               => 'kirki',
            'required'           => false,
    );      

    tgmpa( $plugins);

}
