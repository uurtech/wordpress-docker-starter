<?php
/**
 * Woocommerce modal for loop products
 *
 * @package Leto
 */

?>
<?php global $product; ?>
<div id="modal-quickview" class="mfp-hide product-modal">
	<div class="modal-quickview__content single-product woocommerce">

		<div class="product">
		
			<div class="row">
				<div class="col-xs-12 col-sm-7 col-sm-push-5 modal-quickview__images modal-matchHeight">
					<div class="quickview-gallery">
					<?php
						$gallery_ids = $product->get_gallery_image_ids();
						$post_id = $post -> ID;
						$post_thumb = get_post_thumbnail_id( $post_id );
						
						//Display only thumb or thumb+gallery, based on user choices
						$display_mode = get_theme_mod( 'leto_modal_images_display_mode', 'complete' );
						if ( 'complete' === $display_mode ) {
							echo wp_get_attachment_image( $post_thumb, 'full' );
							foreach( $gallery_ids as $gallery_id ) {
								echo wp_get_attachment_image( $gallery_id, 'full' );
							} 	
						} else {
							echo wp_get_attachment_image( $post_thumb, 'full' );
						}

					?>
					</div>
				</div><!-- /.modal-quickview__images -->
				<div class="col-xs-12 col-sm-5 col-sm-pull-7 modal-quickview__detail modal-matchHeight">
					<div class="product-detail">
						
						<h2 class="product-title"><?php the_title(); ?></h2>
						<div class="product-rating clearfix">
							<?php woocommerce_template_loop_rating(); ?>

							<?php 
							$review_count = $product->get_review_count();
							if ( comments_open() ) : ?>
							<a href="<?php the_permalink(); ?>#reviews" class="review-link" rel="nofollow"><?php printf( _n( '%s customer review', '%s customer reviews', $review_count, 'leto' ), '<span class="count">' . esc_html( $review_count ) . '</span>' ); ?></a>
							<?php endif; ?>
						</div>

						<div class="price">
							<span class="product-price">
								<?php woocommerce_template_single_price(); ?>
							</span>
						</div>
					
						<div class="product-detail__short-description">
							<p><?php the_excerpt(); ?></p>
						</div>

						<div class="cart modal-cart">
							<div class="add-to-cart">
								<?php 
									woocommerce_template_single_add_to_cart();
									leto_qty_buttons(); 
								?>
							</div>
						</div>

						<div class="product-more-details">
							<a href="<?php the_permalink(); ?>"><?php echo esc_html__( 'More details', 'leto' ); ?></a>
						</div>				

					</div><!-- /.product-detail -->
				</div><!-- /.modal-quickview__detail -->
			</div>

		</div>
		
	</div><!-- /.modal-quickview__content -->
</div><!-- /.modal-quickview -->