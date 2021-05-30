<?php
/**
 * Sample implementation of the Custom Header feature
 *
 * You can add an optional custom header image to header.php like so ...
 *
	<?php the_header_image_tag(); ?>
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 *
 * @package Leto
 */

/**
 * Set up the WordPress core custom header feature.
 *
 * @uses leto_header_style()
 */
function leto_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'leto_custom_header_args', array(
		'default-image'          => get_template_directory_uri() . '/images/header.jpeg',
		'video'					 => true,
		'default-text-color'     => '000000',
		'width'                  => 1920,
		'height'                 => 800,
		'flex-height'            => true,
		'flex-width'			 => true,
		'header-text'			 => false,
		'wp-head-callback'       => 'leto_header_style',
		'header-text'            => false,		
	) ) );
}
add_action( 'after_setup_theme', 'leto_custom_header_setup' );

/**
 * Video header settings
 */
function leto_video_settings( $settings ) {
	$settings['l10n']['play'] 	= '<i class="ionicons ion-play"></i>';
	$settings['l10n']['pause'] 	= '<i class="ionicons ion-pause"></i>';
	
	return $settings;
}
add_filter( 'header_video_settings', 'leto_video_settings' );

if ( ! function_exists( 'leto_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog.
 *
 * @see leto_custom_header_setup().
 */
function leto_header_style() {
	$header_text_color = get_header_textcolor();

	/*
	 * If no custom options for text are set, let's bail.
	 * get_header_textcolor() options: Any hex value, 'blank' to hide text. Default: add_theme_support( 'custom-header' ).
	 */
	if ( get_theme_support( 'custom-header', 'default-text-color' ) === $header_text_color ) {
		return;
	}

	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	<?php
		// Has the text been hidden?
		if ( ! display_header_text() ) :
	?>
		.site-title,
		.site-description {
			position: absolute;
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text use that.
		else :
	?>
		.site-title a,
		.site-description {
			color: #<?php echo esc_attr( $header_text_color ); ?>;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif;
