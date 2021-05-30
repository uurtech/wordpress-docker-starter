<?php
/**
 *
 * @package Leto
 */
?>

	<?php

		//Set container boxed or full
		$container = get_theme_mod( 'leto_boxed_footer', 0 ) ? 'container' : 'container-full';

		//If only 3 widget areas are active, make the second larger
		if ( !is_active_sidebar( 'footer-4' ) ) {
			$area2_cols_no = 'col-lg-6 col-md-6 col-sm-6';
		} else {
			$area2_cols_no = 'col-sm-6 col-md-3 col-lg-3';
		}
	?>

	<div class="footer-widget">
		<div class="<?php echo $container; ?>">
			<div class="row">
				
				<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
				<div class="col-sm-6 col-md-3 col-lg-3">
					<div class="footer-widget__item">
						<?php dynamic_sidebar( 'footer-1'); ?>
					</div>
				</div>
				<?php endif; ?>

				<?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
				<div class="<?php echo $area2_cols_no; ?>">	
					<div class="footer-widget__item">
						<?php dynamic_sidebar( 'footer-2'); ?>
					</div>
				</div>
				<?php endif; ?>

				<?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
				<div class="col-sm-6 col-md-3 col-lg-3">
					<div class="footer-widget__item">
						<?php dynamic_sidebar( 'footer-3'); ?>
					</div>
				</div>
				<?php endif; ?>

				<?php if ( is_active_sidebar( 'footer-4' ) ) : ?>					
				<div class="col-sm-6 col-md-3 col-lg-3">
					<div class="footer-widget__item">
						<?php dynamic_sidebar( 'footer-4'); ?>
					</div>
				</div>
				<?php endif; ?>

			</div>
		</div>
	</div>	