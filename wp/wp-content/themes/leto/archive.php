<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Leto
 */

get_header(); 

$sidebar = get_theme_mod( 'leto_fullwidth_blog_archives' );
if ( !$sidebar ) {
	$cols = 'col-md-12';
} else {
	$cols = 'col-md-9';
}
?>

	<div id="primary" class="content-area <?php echo $cols; ?>">
		<main id="main" class="site-main">

		<?php
		if ( have_posts() ) : ?>

			<div class="blog-layout-grid clearfix">

			<?php
			/* Start the Loop */
			while ( have_posts() ) : the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				get_template_part( 'template-parts/content', get_post_format() );

			endwhile; ?>

			</div>

			<?php
			the_posts_navigation();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
if ( $sidebar ) {
	get_sidebar();	
}
get_footer();