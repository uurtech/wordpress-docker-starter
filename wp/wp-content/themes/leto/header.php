<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Leto
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<?php $header_type = get_theme_mod( 'header_type', 'header-type-2' ); ?>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php do_action( 'leto_before_page' ); ?>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'leto' ); ?></a>

	<?php do_action( 'leto_before_header' ); ?>

	<header id="masthead" class="site-header">
		<div class="header-floating-trigger">
			<div class="header-navigation header-floating">
				<div class="container-full">
					<div class="site-header__content">			
					<?php do_action( 'leto_inside_header' ); ?>
					</div>
				</div>
			</div>
		</div>
	</header><!-- #masthead -->

	<?php do_action( 'leto_after_header' ); ?>

	<?php $container = leto_container_type(); ?>
	<div id="content" class="site-content">
		<?php do_action( 'leto_before_container' ); ?>		
		<div class="<?php echo esc_attr( $container ); ?> clearfix">
			<div class="row">


