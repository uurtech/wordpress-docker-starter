<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Leto
 */

?>

			</div>
		</div>
	</div><!-- #content -->

	<?php do_action( 'leto_before_footer' ); ?>

	<footer id="colophon" class="site-footer">
		<?php do_action( 'leto_footer' ); ?>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php do_action( 'leto_after_page' ); ?>

<?php wp_footer(); ?>

</body>
</html>
