<?php
/**
 * Blog widget
 *
 * @package Leto
 */

class Leto_Blog extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'leto_blog_widget', 'description' => __( 'Show the latest news from your blog.', 'leto') );
        parent::__construct(false, $name = __('Leto: Blog', 'leto'), $widget_ops);
		$this->alt_option_name = 'leto_blog_widget';
		
    }
	
	function form($instance) {
		$title     			= isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$category  			= isset( $instance['category'] ) ? esc_attr( $instance['category'] ) : '';			
		$number   			= isset( $instance['number'] ) ? absint( $instance['number'] ) : 3;
	?>

	<p>
	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'leto'); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
	</p>

	<p><label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Pick a category to display posts from:', 'leto'); ?></label>
		<?php $args = array(
			'show_option_none'   => __( 'Show posts from all categories', 'leto'),
			'name'               => $this->get_field_name('category'),
			'id'                 => $this->get_field_id('category'),
			'class'              => 'chosen-dropdown',
			'selected'			 => $category,
		); ?>
       	<?php wp_dropdown_categories($args); ?>
    </p>  

	<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:', 'leto' ); ?></label>
	<input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" /></p>

	<?php
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] 			= sanitize_text_field($new_instance['title']);
		$instance['category'] 		= sanitize_text_field( $new_instance['category'] );
		$instance['number'] 		= (int) $new_instance['number'];		  
		return $instance;
	}
		
	function widget($args, $instance) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';
		$category = isset( $instance['category'] ) ? $instance['category'] : '';

		if ( $category == -1 ) {
			$cat = '';
		} else {
			$cat = $category;
		}


		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 3;
		if ( ! $number )
			$number = 3;

		$r = new WP_Query( array(
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'posts_per_page'	  => $number,
			'cat'		  		  => $cat,		
			'ignore_sticky_posts' => true
		) );

		echo $args['before_widget'];

		if ($r->have_posts()) :
		?>

			<section class="latest-blog-section">
				<?php if ( $title ) echo $args['before_title'] . $title . $args['after_title']; ?>
				<div class="row">
					<?php while ( $r->have_posts() ) : $r->the_post(); ?>
					<div class="col-sm-4 col-md-4">
						<article class="post hentry">
							<?php if ( has_post_thumbnail() ) : ?>
							<figure class="entry-thumbnail">
							<?php the_post_thumbnail( 'leto-medium-thumb' ); ?>
							</figure><!-- /.entry-thumbnail -->
							<?php endif; ?>	
							<header class="entry-header">
								<div class="entry-meta">
									<span class="meta-category"><?php leto_get_first_cat(); ?></span>
								</div>
								<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
								<div class="entry-meta">
									<?php leto_posted_on(); ?>
								</div><!-- .entry-meta -->
							</header><!-- .entry-header -->
						</article><!-- #post-## -->
					</div>
					<?php endwhile; ?>
				</div>
			</section><!-- /.latest-blog-section -->
			<?php wp_reset_postdata(); ?>
			<?php

		endif;

		echo $args['after_widget'];
	}
	
}