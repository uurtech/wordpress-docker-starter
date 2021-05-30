<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Leto
 */

	$hide_thumb   = get_theme_mod( 'leto_single_hide_thumb', 0 );
	$hide_meta	  = get_theme_mod( 'leto_single_hide_meta', 0 );
	$hide_date    = get_theme_mod( 'leto_single_hide_date', 0 );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if ( has_post_thumbnail() && !$hide_thumb ) : ?>
	<figure class="entry-thumbnail">
		<?php the_post_thumbnail( 'leto-large-thumb' ); ?>
	</figure>
	<?php endif; ?>

	<header class="entry-header">
		<?php if ( !$hide_meta ) : ?>
		<div class="meta-category">
			<?php leto_show_cats(); ?>
		</div>
		<?php endif; ?>

		<?php
			the_title( '<h1 class="entry-title">', '</h1>' );

		if ( ( 'post' === get_post_type() ) && !$hide_date ) : ?>
		<div class="entry-meta">
			<?php leto_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php
		endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php
			the_content( sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'leto' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			) );

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'leto' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer entry-footer-links">
		<?php leto_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
