<?php
/**
 * Footer functions
 *
 * @package Leto
 */


/**
 * Footer menu
 */
function leto_footer_menu() {
	if ( has_nav_menu( 'footer_menu' ) ) {
	?>
		<nav class="footer-navigation">
			<?php wp_nav_menu( array( 'theme_location' => 'footer_menu', 'fallback_cb' => false, 'menu_id' => 'footer-menu', 'depth' => 1 ) ); ?>
		</nav>		
	<?php
	}
}

/**
 * Footer sidebar
 */
function leto_footer_sidebar() {
	if ( is_active_sidebar( 'footer-1' ) ) {
		get_sidebar('footer');		
	}
}

/**
 * Footer credits
 */
function leto_footer_credits() {

	    $credits = get_theme_mod( 'leto_footer_credits' );
	?>
		<div class="site-info">
			<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'leto' ) ); ?>"><?php
				/* translators: %s: CMS name, i.e. WordPress. */
				printf( esc_html__( 'Powered by %s', 'leto' ), 'WordPress' );
			?></a>
			<span class="sep"> | </span>
			<?php
				/* translators: 1: Theme name, 2: Theme author. */
				printf( esc_html__( 'Theme: %2$s by %1$s.', 'leto' ), 'aThemes', '<a href="https://athemes.com/theme/leto/" rel="nofollow">Leto</a>' );
			?>
		</div><!-- .site-info -->
	<?php	
}

/**
 * Footer output
 */
function leto_footer_output() {

	$hide_footer_credits = get_theme_mod( 'leto_hide_footer_credits', 0 );

	leto_footer_menu();
	leto_footer_sidebar();
	if ( !$hide_footer_credits ) {
		leto_footer_credits();
	}
}
add_action( 'leto_footer', 'leto_footer_output' );

/**
 * Footer output
 */
function leto_search_popup() {

	$search_enable 	= get_theme_mod( 'leto_search_enable', 1 );

	$search_title 	= get_theme_mod( 'leto_search_title', __( 'Search', 'leto' ) );

	if ( !$search_enable ) {
		return;
	}

	?>
	<div class="search-box">
		
		<div class="search-box__header-container">
			<div class="container-full">
				<div class="search-box__header">
					<div class="search-box__title">
						<div class="inner">
							<?php echo esc_html( $search_title ); ?>
						</div>
					</div>
					<div class="search-box__close">
						<div class="inner">
							<span class="close-search-box"><i class="ion-android-close"></i></span>
						</div>
					</div>
				</div>
			</div>	
		</div>

		<div class="search-box__content">
			<div class="container">
				<div class="search-box__input">
					<?php get_search_form(); ?>
				</div>
			</div>
		</div>
		
	</div><!-- /.search-box -->
<?php
}
add_action( 'leto_after_page', 'leto_search_popup' );




/**
 * Comments template
 */
function leto_comments_template() {
	
	if ( class_exists( 'WooCommerce' ) && is_product() ) {
		return;
	}

	// If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) :
		comments_template();
	endif;
}
add_action( 'leto_before_footer', 'leto_comments_template' );
