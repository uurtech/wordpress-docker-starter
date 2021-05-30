<?php
/**
 * Gallery widget
 *
 * @package Leto
 */

class Leto_Gallery extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'leto_gallery_widget', 'description' => __( 'Display your choice of images (Page Builder use only)', 'leto') );
        parent::__construct(false, $name = __('Leto: Gallery', 'leto'), $widget_ops);
		$this->alt_option_name = 'leto_gallery_widget';
    }

	function form($instance) {
		$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$gallery_images = isset( $instance['gallery_images'] ) ? esc_html( $instance['gallery_images'] ) : '';

		?>

		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'leto'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>

		<p><input class="widefat custom_media_url custom_media_url5" id="<?php echo $this->get_field_id('gallery_images'); ?>" name="<?php echo $this->get_field_name('gallery_images'); ?>" type="text" value="<?php echo $gallery_images; ?>" size="3" />
			<input type="button" class="button button-primary custom_media_button custom_media_button5" id="custom_media_button" value="<?php echo esc_attr__('Upload images', 'leto'); ?>" /></p>
		<?php
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] 			= sanitize_text_field( $new_instance['title'] );
		$instance['gallery_images'] = sanitize_text_field( $new_instance['gallery_images'] );

		return $instance;
	}
		
	function widget($args, $instance) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		$title 			= ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';
		$gallery_images = isset( $instance['gallery_images'] ) ? esc_html( $instance['gallery_images'] ) : '';

		echo $args['before_widget'];
?>

		<?php $gallery_images = explode( ',', $gallery_images ); ?>
		<section class="brand-box-section">
			<?php if ( $title ) echo $args['before_title'] . $title . $args['after_title']; ?>			
			<div class="brand-box__inner">
				<div class="brand-slider">
					<?php foreach ( $gallery_images as $image_id ) : ?>
					<div class="brand-slide">
						<div class="brand-slide__inner">
							<?php echo wp_get_attachment_image( $image_id, 'medium' ); ?>
						</div>
					</div>
					<?php endforeach; ?>
				</div><!-- /.brand-slider -->
			</div><!-- /.brand-box__inner -->
		</section><!-- /.brand-box-section -->

	<?php
		echo $args['after_widget'];
	}
}