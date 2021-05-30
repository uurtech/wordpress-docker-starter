<?php
/**
 * Header functions
 *
 * @package Leto
 */


/**
 * Site branding
 */
function leto_branding() {
	?>
		<div class="site-branding">
			<?php the_custom_logo(); ?>

			<div class="site-branding__content">
			<?php
			if ( is_front_page() && is_home() ) : ?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<?php else : ?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
			<?php
			endif;

			$description = get_bloginfo( 'description', 'display' );
			if ( $description || is_customize_preview() ) : ?>
				<p class="site-description"><?php echo $description; /* WPCS: xss ok. */ ?></p>
			<?php
			endif; ?>
			</div>
		</div><!-- .site-branding -->
		<?php
}
add_action( 'leto_inside_header', 'leto_branding', 8 );

/**
 * Main navigation
 */
function leto_main_navigation() {
	?>
		<nav id="site-navigation" class="main-navigation">
			<?php
				wp_nav_menu( array(
					'theme_location' => 'menu-1',
					'menu_id'        => 'primary-menu',
				) );
			?>
		</nav><!-- #site-navigation -->	

		<div class="header-mobile-menu">
			<div class="header-mobile-menu__inner">
				<button class="toggle-mobile-menu">
					<span><?php esc_html_e( 'Toggle menu', 'leto' ); ?></span>
				</button>
			</div>
		</div><!-- /.header-mobile-menu -->		


		<?php $show_menu_additions = get_theme_mod( 'leto_show_menu_additions', 1 ); ?>
		<?php if ( $show_menu_additions ) : ?>
		<ul class="nav-link-right">
			<li class="nav-link-account">
				<?php if ( class_exists( 'WooCommerce' ) ) : ?>
					<?php if ( is_user_logged_in() ) { ?>
						<a href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>" title="Account"><span class="prefix"><?php esc_html_e( 'My account', 'leto' ); ?></span> <span class="suffix ion-person"></span></a>
					<?php } 
					else { ?>
						<a href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>" title="Login"><span class="prefix"><?php esc_html_e( 'Login/Register', 'leto' ); ?></span> <span class="suffix ion-person"></span></a>
					<?php } ?>
				<?php else : ?>
					<a href="<?php echo esc_url( wp_login_url() ); ?>" title="Login"><span class="prefix"><?php esc_html_e( 'Login / Register', 'leto' ); ?></span> <span class="suffix ion-person"></span></a>
				<?php endif; ?>
			</li>

			<?php if ( class_exists( 'Woocommerce' ) ) : ?>

			<?php $cart_content = WC()->cart->cart_contents_count; ?>

			<li class="nav-link-cart">
				<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="header-cart-link">
					<i class="ion-bag"></i>
					<span class="screen-reader-text"><?php esc_html_e( 'Cart', 'leto' ); ?></span>
					<span class="cart-count">(<?php echo intval($cart_content); ?>)</span>
				</a>
				<div class="sub-menu cart-mini-wrapper">
					<div class="cart-mini-wrapper__inner">
					<?php woocommerce_mini_cart(); ?>
					</div>
				</div>
			</li>
			<?php endif; //end Woocommerce class_exists check ?>
			
			<?php
			$enable_search = get_theme_mod( 'leto_enable_search', 1 );
			if ( $enable_search ) : ?>
			<li class="nav-link-search">
				<a href="#" class="toggle-search-box">
					<i class="ion-ios-search"></i>
				</a>
			</li>
			<?php endif; ?>

		</ul>
		<?php endif; ?>

	<?php
}
add_action( 'leto_inside_header', 'leto_main_navigation', 9 );

/**
 * Mobile menu
 */
function leto_mobile_menu() {
	?>
	<div class="mobile-menu">
		<div class="container-full">
			<div class="mobile-menu__search">
				
				<?php get_search_form(); ?>

			</div><!-- /.mobile-menu__search -->

			<nav class="mobile-menu__navigation">
			<?php
				wp_nav_menu( array(
					'theme_location' => 'menu-1',
					'menu_id'        => 'primary-menu',
				) );
			?>
			</nav><!-- /.mobile-menu__navigation -->
		</div>
	</div><!-- /.mobile-menu -->
	<?php
}
add_action( 'leto_before_page', 'leto_mobile_menu' );

/**
 * Banner for pages and shop archives
 */
function leto_page_banner() {
	
	if ( is_home() || is_front_page() || is_page_template( 'page-templates/template_page-builder.php') ) {
		return;
	}

	$wc_check = class_exists( 'WooCommerce' );

	if ( $wc_check && is_shop() ) {	
		$hero = get_the_post_thumbnail_url( get_option( 'woocommerce_shop_page_id' ) );
	} elseif ( $wc_check &&  ( is_product_category() || is_product_tag() ) ) {
	    global $wp_query;
	    $cat = $wp_query->get_queried_object();
		$thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
		$hero = wp_get_attachment_url( $thumbnail_id );
	} elseif ( is_page() ) {
		global $post;
		$hero = get_the_post_thumbnail_url( $post->ID );
	} else {
		return;
	}

	if ( $hero != '' ) {
		$has_background = 'has-background';
	} else {
		$has_background = '';
	}

	?>
	<div class="page-header text-center">
		<div class="container">
			<?php if ( class_exists( 'WooCommerce' ) && is_woocommerce() ) : ?>
			<div class="page-breadcrumbs">
				<nav class="breadcrumbs">
					<?php woocommerce_breadcrumb(); ?>
				</nav>
			</div>
			<?php endif; ?>
			<?php if ( is_home() ) : ?>
				<h1 class="page-title"><?php single_post_title(); ?></h1>
			<?php elseif ( is_page() ) : ?>
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			<?php elseif ( class_exists( 'Woocommerce') && is_woocommerce() ) : ?>
				<h1 class="page-title"><?php woocommerce_page_title(); ?></h1>
			<?php endif; ?>
		</div>
	</div>		
	<?php
}
add_action( 'leto_after_header', 'leto_page_banner' );

/**
 * Page header for archives
 */
function leto_archives_header() {

	if ( ( !is_home() && !is_archive() ) || is_front_page() || ( class_exists( 'WooCommerce' ) && is_woocommerce() ) ) {
		return;
	}

	?>
	<div class="page-header text-center">
		<div class="container">
		<?php if ( is_archive() ) : ?>
			<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
		<?php elseif ( is_home() ) : ?>
			<h1 class="entry-title"><?php single_post_title(); ?></h1>
		<?php endif; ?>		
		</div>
	</div><!-- /.page-header -->

	<?php
}
add_action( 'leto_after_header', 'leto_archives_header', 11 );


/**
 * Slider defaults
 */
function leto_slider_defaults() {
	$defaults = array(
		array(
			'slider_image' 			=> get_stylesheet_directory_uri() . '/images/1.jpeg',
			'slider_text'			=> __( 'Welcome to our site', 'leto'),
			'slider_smalltext'		=> __( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'leto'),
			'slider_button_link'	=> '#primary',
			'slider_button_text'	=> __( 'I am ready', 'leto'),
		),
		array(
			'slider_image' 			=> get_stylesheet_directory_uri() . '/images/2.jpg',
			'slider_text'			=> __( 'Welcome to our site', 'leto'),
			'slider_smalltext'		=> __( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'leto'),
			'slider_button_link'	=> '#primary',
			'slider_button_text'	=> __( 'I am ready', 'leto'),	
		),			
	);
	return $defaults;
}

/**
 * Hero area for the homepage
 */
function leto_hero_slider() {

	if ( !is_front_page() ) {
		return;
	}

	echo '<div class="hero-area">';
		the_custom_header_markup();
	echo '</div>';		
}
add_action( 'leto_after_header', 'leto_hero_slider' );
