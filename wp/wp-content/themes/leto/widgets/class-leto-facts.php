<?php
/**
 * Facts widget
 *
 * @package Leto
 */

class Leto_Facts extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'leto_facts_widget', 'description' => __( 'Show your visitors some facts about your store.', 'leto') );
        parent::__construct(false, $name = __('Leto: Facts', 'leto'), $widget_ops);
		$this->alt_option_name = 'leto_facts_widget';
    }
	
	function form($instance) {
		$title     			= isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$fact_one   		= isset( $instance['fact_one'] ) ? esc_html( $instance['fact_one'] ) : __( 'Free Delivery', 'leto');
		$fact_one_text   	= isset( $instance['fact_one_text'] ) ? esc_html( $instance['fact_one_text'] ) : __( 'For orders over $99', 'leto');
		$fact_one_icon  	= isset( $instance['fact_one_icon'] ) ? esc_html( $instance['fact_one_icon'] ) : 'ion-ios-location-outline';		
		$fact_two   		= isset( $instance['fact_two'] ) ? esc_attr( $instance['fact_two'] ) : __( '90 Days Return', 'leto');
		$fact_two_text   	= isset( $instance['fact_two_text'] ) ? esc_html( $instance['fact_two_text'] ) :  __( 'No questions asked', 'leto');
		$fact_two_icon  	= isset( $instance['fact_two_icon'] ) ? esc_html( $instance['fact_two_icon'] ) : 'ion-ios-loop';
		$fact_three   		= isset( $instance['fact_three'] ) ? esc_attr( $instance['fact_three'] ) : __( 'Secure Payment', 'leto');
		$fact_three_text 	= isset( $instance['fact_three_text'] ) ? esc_html( $instance['fact_three_text'] ) :  __( '100% safe payment', 'leto');
		$fact_three_icon  	= isset( $instance['fact_three_icon'] ) ? esc_html( $instance['fact_three_icon'] ) : 'ion-ios-locked-outline';
		$fact_four   		= isset( $instance['fact_four'] ) ? esc_attr( $instance['fact_four'] ) : __( '24/7 Support', 'leto');	
		$fact_four_text  	= isset( $instance['fact_four_text'] ) ? esc_html( $instance['fact_four_text'] ) :  __( 'Dedicated support', 'leto');
		$fact_four_icon  	= isset( $instance['fact_four_icon'] ) ? esc_html( $instance['fact_four_icon'] ) : 'ion-ios-people-outline';	
	?>
	<p><?php _e('You can find a list of the available icons ', 'leto'); ?><a href="//ionicons.com/v2/cheatsheet.html" target="_blank"><?php _e('here.', 'leto'); ?></a>&nbsp;<?php _e('Usage example: <strong>ion-paper-airplane</strong>', 'leto'); ?></p>
	<p>
	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'leto'); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
	</p>

	<h4><?php _e('FACT ONE', 'leto'); ?></h4>
	<p style="display:inline-block;width:31%;margin:0 1%;">
	<label for="<?php echo $this->get_field_id('fact_one'); ?>"><?php _e('Fact name', 'leto'); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id('fact_one'); ?>" name="<?php echo $this->get_field_name('fact_one'); ?>" type="text" value="<?php echo $fact_one; ?>" />
	</p>

	<p style="display:inline-block;width:31%;margin:0 1%;">
	<label for="<?php echo $this->get_field_id('fact_one_text'); ?>"><?php _e('Fact text', 'leto'); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id('fact_one_text'); ?>" name="<?php echo $this->get_field_name('fact_one_text'); ?>" type="text" value="<?php echo $fact_one_text; ?>" />
	</p>

	<p style="display:inline-block;width:31%;margin:0 1%;">
	<label for="<?php echo $this->get_field_id('fact_one_icon'); ?>"><?php _e('Fact icon', 'leto'); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id('fact_one_icon'); ?>" name="<?php echo $this->get_field_name('fact_one_icon'); ?>" type="text" value="<?php echo $fact_one_icon; ?>" />
	</p>

	<h4><?php _e('FACT TWO', 'leto'); ?></h4>
	<p style="display:inline-block;width:31%;margin:0 1%;">
	<label for="<?php echo $this->get_field_id('fact_two'); ?>"><?php _e('Fact name', 'leto'); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id('fact_two'); ?>" name="<?php echo $this->get_field_name('fact_two'); ?>" type="text" value="<?php echo $fact_two; ?>" />
	</p>

	<p style="display:inline-block;width:31%;margin:0 1%;">
	<label for="<?php echo $this->get_field_id('fact_two_text'); ?>"><?php _e('Fact text', 'leto'); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id('fact_two_text'); ?>" name="<?php echo $this->get_field_name('fact_two_text'); ?>" type="text" value="<?php echo $fact_two_text; ?>" />
	</p>

	<p style="display:inline-block;width:31%;margin:0 1%;">
	<label for="<?php echo $this->get_field_id('fact_two_icon'); ?>"><?php _e('Fact icon', 'leto'); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id('fact_two_icon'); ?>" name="<?php echo $this->get_field_name('fact_two_icon'); ?>" type="text" value="<?php echo $fact_two_icon; ?>" />
	</p>	

	<h4><?php _e('FACT THREE', 'leto'); ?></h4>
	<p style="display:inline-block;width:31%;margin:0 1%;">
	<label for="<?php echo $this->get_field_id('fact_three'); ?>"><?php _e('Fact name', 'leto'); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id('fact_three'); ?>" name="<?php echo $this->get_field_name('fact_three'); ?>" type="text" value="<?php echo $fact_three; ?>" />
	</p>

	<p style="display:inline-block;width:31%;margin:0 1%;">
	<label for="<?php echo $this->get_field_id('fact_three_text'); ?>"><?php _e('Fact text', 'leto'); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id('fact_three_text'); ?>" name="<?php echo $this->get_field_name('fact_three_text'); ?>" type="text" value="<?php echo $fact_three_text; ?>" />
	</p>

	<p style="display:inline-block;width:31%;margin:0 1%;">
	<label for="<?php echo $this->get_field_id('fact_three_icon'); ?>"><?php _e('Fact icon', 'leto'); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id('fact_three_icon'); ?>" name="<?php echo $this->get_field_name('fact_three_icon'); ?>" type="text" value="<?php echo $fact_three_icon; ?>" />
	</p>

	<h4><?php _e('FACT FOUR', 'leto'); ?></h4>
	<p style="display:inline-block;width:31%;margin:0 1%;">
	<label for="<?php echo $this->get_field_id('fact_four'); ?>"><?php _e('Fact name', 'leto'); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id('fact_four'); ?>" name="<?php echo $this->get_field_name('fact_four'); ?>" type="text" value="<?php echo $fact_four; ?>" />
	</p>

	<p style="display:inline-block;width:31%;margin:0 1%;">
	<label for="<?php echo $this->get_field_id('fact_four_text'); ?>"><?php _e('Fact text', 'leto'); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id('fact_four_text'); ?>" name="<?php echo $this->get_field_name('fact_four_text'); ?>" type="text" value="<?php echo $fact_four_text; ?>" />
	</p>

	<p style="display:inline-block;width:31%;margin:0 1%;">
	<label for="<?php echo $this->get_field_id('fact_four_icon'); ?>"><?php _e('Fact icon', 'leto'); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id('fact_four_icon'); ?>" name="<?php echo $this->get_field_name('fact_four_icon'); ?>" type="text" value="<?php echo $fact_four_icon; ?>" />
	</p>							

	<?php
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] 			= sanitize_text_field($new_instance['title']);
		$instance['fact_one'] 		= sanitize_text_field($new_instance['fact_one']);
		$instance['fact_one_text'] 	= sanitize_text_field($new_instance['fact_one_text']);
		$instance['fact_one_icon'] 	= sanitize_text_field($new_instance['fact_one_icon']);
		$instance['fact_two'] 		= sanitize_text_field($new_instance['fact_two']);
		$instance['fact_two_text'] 	= sanitize_text_field($new_instance['fact_two_text']);
		$instance['fact_two_icon'] 	= sanitize_text_field($new_instance['fact_two_icon']);
		$instance['fact_three'] 	= sanitize_text_field($new_instance['fact_three']);
		$instance['fact_three_text']	= sanitize_text_field($new_instance['fact_three_text']);
		$instance['fact_three_icon']= sanitize_text_field($new_instance['fact_three_icon']);
		$instance['fact_four'] 		= sanitize_text_field($new_instance['fact_four']);
		$instance['fact_four_text'] 	= sanitize_text_field($new_instance['fact_four_text']);
		$instance['fact_four_icon'] = sanitize_text_field($new_instance['fact_four_icon']);
		return $instance;
	}
		
	function widget($args, $instance) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		$title 			= ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';
		$fact_one   	= isset( $instance['fact_one'] ) ? esc_html( $instance['fact_one'] ) : '';
		$fact_one_text  = isset( $instance['fact_one_text'] ) ? esc_html( $instance['fact_one_text'] ) : '';
		$fact_one_icon  = isset( $instance['fact_one_icon'] ) ? esc_html( $instance['fact_one_icon'] ) : '';
		$fact_two   	= isset( $instance['fact_two'] ) ? esc_attr( $instance['fact_two'] ) : '';
		$fact_two_text  = isset( $instance['fact_two_text'] ) ? esc_html( $instance['fact_two_text'] ) : '';
		$fact_two_icon  = isset( $instance['fact_two_icon'] ) ? esc_html( $instance['fact_two_icon'] ) : '';
		$fact_three   	= isset( $instance['fact_three'] ) ? esc_attr( $instance['fact_three'] ) : '';
		$fact_three_text= isset( $instance['fact_three_text'] ) ? esc_html( $instance['fact_three_text'] ) : '';
		$fact_three_icon= isset( $instance['fact_three_icon'] ) ? esc_html( $instance['fact_three_icon'] ) : '';
		$fact_four   	= isset( $instance['fact_four'] ) ? esc_attr( $instance['fact_four'] ) : '';		
		$fact_four_text = isset( $instance['fact_four_text'] ) ? esc_html( $instance['fact_four_text'] ) : '';
		$fact_four_icon = isset( $instance['fact_four_icon'] ) ? esc_html( $instance['fact_four_icon'] ) : '';

		echo $args['before_widget'];
?>

		<?php if ( $title ) echo $args['before_title'] . $title . $args['after_title']; ?>
		
		<div class="featured-box-section">
			<div class="row">
				<?php if ( $fact_one ) : ?>
				<div class="featured-box-item">
					<div class="featured-box-item__inner">
						<div class="featured-box-icon">
							<i class="<?php echo $fact_one_icon; ?>"></i>
						</div>
						<div class="featured-box__info">
							<div class="featured-box-title">
							<?php echo $fact_one; ?>
							</div>
							<div class="featured-box-desc">
							<?php echo $fact_one_text; ?>
							</div>
						</div>
					</div>
				</div>
				<?php endif; ?>
				<?php if ( $fact_two ) : ?>
				<div class="featured-box-item">
					<div class="featured-box-item__inner">
						<div class="featured-box-icon">
							<i class="<?php echo $fact_two_icon; ?>"></i>
						</div>
						<div class="featured-box__info">
							<div class="featured-box-title">
							<?php echo $fact_two; ?>
							</div>
							<div class="featured-box-desc">
							<?php echo $fact_two_text; ?>
							</div>
						</div>
					</div>
				</div>	
				<?php endif; ?>					
				<?php if ( $fact_three ) : ?>
				<div class="featured-box-item">
					<div class="featured-box-item__inner">
						<div class="featured-box-icon">
							<i class="<?php echo $fact_three_icon; ?>"></i>
						</div>
						<div class="featured-box__info">
							<div class="featured-box-title">
							<?php echo $fact_three; ?>
							</div>
							<div class="featured-box-desc">
							<?php echo $fact_three_text; ?>
							</div>
						</div>
					</div>
				</div>
				<?php endif; ?>				
				<?php if ( $fact_four ) : ?>
				<div class="featured-box-item">
					<div class="featured-box-item__inner">
						<div class="featured-box-icon">
							<i class="<?php echo $fact_four_icon; ?>"></i>
						</div>
						<div class="featured-box__info">
							<div class="featured-box-title">
							<?php echo $fact_four; ?>
							</div>
							<div class="featured-box-desc">
							<?php echo $fact_four_text; ?>
							</div>
						</div>
					</div>
				</div>
				<?php endif; ?>								
			</div>
		</div>


	<?php
		echo $args['after_widget'];
	}
	
}