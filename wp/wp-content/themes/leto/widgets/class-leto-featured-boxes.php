<?php
/**
 * Featured boxes widget
 *
 * @package Leto
 */

class Leto_Featured_Boxes extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'leto_featured_boxes_widget', 'description' => __( 'Display featured content in up to 4 boxes. (Page Builder use only)', 'leto') );
        parent::__construct(false, $name = __('Leto: Featured boxes', 'leto'), $widget_ops);
		$this->alt_option_name = 'leto_featured_boxes_widget';
    }

	function form($instance) {
		$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		?>

		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'leto'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>

		<?php
		for ( $i=1; $i < 5; $i++ ) { 
			${'background_image' . $i} 	= isset( $instance['background_image' . $i] ) ? esc_url( $instance['background_image' . $i] ) : '';
			${'featured_text' . $i} 	= isset( $instance['featured_text' . $i] ) ? wp_kses_post( $instance['featured_text' . $i] ) : '';
			${'button_link' . $i} 		= isset( $instance['button_link' . $i] ) ? esc_url( $instance['button_link' . $i] ) : '';
			${'button_text' . $i} 		= isset( $instance['button_text' . $i] ) ? esc_html( $instance['button_text' . $i] ) : '';
			?>

			<div class="featured-box-data">
				<h3 title="<?php _e( 'Click to expand', 'leto' ); ?>"><?php echo sprintf( __('Featured box %s', 'leto'), $i ); ?></h3>
				<div class="featured-box-inner">
					<?php if ( ${'background_image' . $i} ) : ?>
					<p><img class="custom_media_image custom_media_image<?php echo $i; ?>" style="max-width:100px;height:auto;" src="<?php echo ${'background_image' . $i}; ?>"/></p>
					<?php endif; ?>
					<p><input class="widefat custom_media_url custom_media_url<?php echo $i; ?>" id="<?php echo $this->get_field_id('background_image' . $i); ?>" name="<?php echo $this->get_field_name('background_image' . $i); ?>" type="text" value="<?php echo ${'background_image' . $i}; ?>" size="3" />
				    <input type="button" class="button button-primary custom_media_button custom_media_button<?php echo $i; ?>" id="custom_media_button<?php echo $i; ?>" value="<?php echo esc_attr__('Upload background image', 'leto'); ?>" /></p>
					<p>
					<label for="<?php echo $this->get_field_id('featured_text' . $i); ?>"><?php _e('Text', 'leto'); ?></label>
					<input class="widefat" id="<?php echo $this->get_field_id('featured_text' . $i); ?>" name="<?php echo $this->get_field_name('featured_text' . $i); ?>" type="text" value="<?php echo ${'featured_text' . $i}; ?>" />
					</p>
					<p>
					<label for="<?php echo $this->get_field_id('button_link' . $i); ?>"><?php _e('Button link', 'leto'); ?></label>
					<input class="widefat" id="<?php echo $this->get_field_id('button_link' . $i); ?>" name="<?php echo $this->get_field_name('button_link' . $i); ?>" type="text" value="<?php echo ${'button_link' . $i}; ?>" />
					</p>
					<p>
					<label for="<?php echo $this->get_field_id('button_text' . $i); ?>"><?php _e('Button text', 'leto'); ?></label>
					<input class="widefat" id="<?php echo $this->get_field_id('button_text' . $i); ?>" name="<?php echo $this->get_field_name('button_text' . $i); ?>" type="text" value="<?php echo ${'button_text' . $i}; ?>" />
					</p>
				</div>
			</div>
		<?php
		}
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		for ( $i=1; $i < 5; $i++ ) { 
			$instance['background_image' . $i] 	= esc_url_raw( $new_instance['background_image' . $i] );
			$instance['featured_text' . $i] 	= wp_kses_post( $new_instance['featured_text' . $i] );
			$instance['button_link' . $i] 		= esc_url_raw( $new_instance['button_link' . $i] );
			$instance['button_text' . $i] 		= sanitize_text_field( $new_instance['button_text' . $i] );
		}
		return $instance;
	}
		
	function widget($args, $instance) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		$title 	= ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';

		for ( $i=1; $i < 5; $i++ ) { 
			${'background_image' . $i} 	= isset( $instance['background_image' . $i] ) ? esc_url( $instance['background_image' . $i] ) : '';
			${'featured_text' . $i} 	= isset( $instance['featured_text' . $i] ) ? wp_kses_post( $instance['featured_text' . $i] ) : '';
			${'button_link' . $i} 		= isset( $instance['button_link' . $i] ) ? esc_url( $instance['button_link' . $i] ) : '';
			${'button_text' . $i} 		= isset( $instance['button_text' . $i] ) ? esc_html( $instance['button_text' . $i] ) : '';
		}

		if ( $background_image3 == '' ) {
			$format = 'two-cols';
		} else {
			$format = 'not-two-cols';
		}

		echo $args['before_widget'];
	?>

		<section class="categories-section">
			<?php if ( $title ) echo $args['before_title'] . $title . $args['after_title']; ?>			
			<div class="list-categories-section <?php echo $format; ?>">
				<?php for ( $i=1; $i < 5; $i++ ) { ?>
				<?php if ( ${'background_image' . $i} ) { ?>
				<div class="category-item">
					<div class="category-item__inner">
						<div class="category-item__img" style="background-image:url(<?php echo ${'background_image' . $i}; ?>)"></div>
						<div class="category-item__text">
							<h4 class="category-item__title"><?php echo ${'featured_text' . $i}; ?></h4>
							<?php if ( ${'button_link' . $i} ) : ?>
							<div class="category-item__link">
								<a href="<?php echo ${'button_link' . $i}; ?>"><?php echo ${'button_text' . $i}; ?> <i class="ion-ios-arrow-forward"></i></a>
							</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<?php }
				} //endfor ?>
			</div>
		</section><!-- /.categories-section -->

	<?php
		echo $args['after_widget'];
	}
}