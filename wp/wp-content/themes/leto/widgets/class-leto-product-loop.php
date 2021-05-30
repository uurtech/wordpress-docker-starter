<?php
/**
 * Product loop widget
 *
 * @package Leto
 */

class Leto_Product_Loop extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'leto_product_loop', 'description' => __( 'Display products in a grid or carousel.', 'leto') );
		parent::__construct(false, $name = __('Leto: Product loop', 'leto'), $widget_ops);
		$this->alt_option_name = 'leto_product_loop';
	}
	
	function form($instance) {
		$title     		= isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$includes       = isset( $instance['includes'] ) ? array_map( 'esc_attr', $instance['includes'] ) : '';
		$number         = isset( $instance['number'] ) ? intval( $instance['number'] ) : 6;
		$show_filter    = isset( $instance['show_filter'] ) ? (bool) $instance['show_filter'] : true;
		$mode  			= isset( $instance['mode'] ) ? esc_attr( $instance['mode'] ) : 'grid';
		$orderby  		= isset( $instance['orderby'] ) ? esc_attr( $instance['orderby'] ) : 'date';
		$button_url		= isset( $instance['button_url'] ) ? esc_url( $instance['button_url'] ) : '';
		$button_text	= isset( $instance['button_text'] ) ? esc_attr( $instance['button_text'] ) : '';
	?>
	<p>
	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'leto'); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p><label for="<?php echo $this->get_field_id('includes'); ?>"><?php _e('Choose the categories you wish to display products from:', 'leto'); ?></label>
        <select data-placeholder="<?php echo __('Select the categories you wish to display posts from.', 'leto'); ?>" multiple="multiple" name="<?php echo $this->get_field_name('includes'); ?>" id="<?php echo $this->get_field_id('includes'); ?>" class="widefat chosen-dropdown">
		<?php
		$cats = get_categories( array('taxonomy' => 'product_cat' ) );
		foreach( $cats as $cat ) : ?>
                <?php printf(
                    '<option value="%s" %s>%s</option>',
                    $cat->cat_ID,
                    in_array( $cat->cat_ID, (array)$includes) ? 'selected="selected"' : '',
                    $cat->cat_name
                );?>
               <?php endforeach; ?>
       	</select>
	</p>  
   <p><input class="checkbox" type="checkbox" <?php checked( $show_filter ); ?> id="<?php echo $this->get_field_id( 'show_filter' ); ?>" name="<?php echo $this->get_field_name( 'show_filter' ); ?>" />
   <label for="<?php echo $this->get_field_id( 'show_filter' ); ?>"><?php _e( 'Show navigation filter? (category slugs must be specified)', 'leto' ); ?></label></p>
   <p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of products to show:', 'leto' ); ?></label>
   <input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" value="<?php echo $number; ?>" size="3" /></p>
	<p><label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Order products by', 'leto'); ?></label>
        <select name="<?php echo $this->get_field_name('orderby'); ?>" id="<?php echo $this->get_field_id('orderby'); ?>">		
			<option value="date" <?php selected( $orderby, 'date' ); ?>><?php echo __('Date', 'leto'); ?></option>
			<option value="meta_value_num" <?php selected( $orderby, 'meta_value_num' ); ?>><?php echo __('Best sellers', 'leto'); ?></option>
			<option value="rand" <?php selected( $orderby, 'rand' ); ?>><?php echo __('Random', 'leto'); ?></option>
       	</select>
    </p>
	<p><label for="<?php echo $this->get_field_id('mode'); ?>"><?php _e('Pick the style for this widget:', 'leto'); ?></label>
        <select name="<?php echo $this->get_field_name('mode'); ?>" id="<?php echo $this->get_field_id('mode'); ?>">		
			<option value="grid" <?php selected( $mode, 'grid' ); ?>><?php echo __('Grid', 'leto'); ?></option>
			<option value="best-seller-slider" <?php selected( $mode, 'best-seller-slider' ); ?>><?php echo __('Carousel', 'leto'); ?></option>
       	</select>
    </p>

    <p><em><?php echo esc_html__( 'You can add a button under the products', 'leto' ); ?></em></p>
   <p><label for="<?php echo $this->get_field_id( 'button_url' ); ?>"><?php _e( 'Button URL', 'leto' ); ?></label>
   <input class="widefat" id="<?php echo $this->get_field_id( 'button_url' ); ?>" name="<?php echo $this->get_field_name( 'button_url' ); ?>" type="url" value="<?php echo $button_url; ?>" size="3" /></p>    
   <p><label for="<?php echo $this->get_field_id( 'button_text' ); ?>"><?php _e( 'Button text', 'leto' ); ?></label>
   <input class="widefat" id="<?php echo $this->get_field_id( 'button_text' ); ?>" name="<?php echo $this->get_field_name( 'button_text' ); ?>" type="text" value="<?php echo $button_text; ?>" size="3" /></p>    
	<?php
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] 			= sanitize_text_field($new_instance['title']);
		$instance['includes'] 		= array_map( 'sanitize_text_field', (array)$new_instance['includes'] );			
		$instance['number']         = intval($new_instance['number']);
		$instance['show_filter']    = isset( $new_instance['show_filter'] ) ? (bool) $new_instance['show_filter'] : false;
	    $instance['mode'] 			= sanitize_text_field($new_instance['mode']);
	    $instance['orderby'] 		= sanitize_text_field($new_instance['orderby']);
	    $instance['button_url'] 	= esc_url_raw($new_instance['button_url']);
	    $instance['button_text'] 	= sanitize_text_field($new_instance['button_text']);


		return $instance;
	}
		
	function widget($args, $instance) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		$title 			= ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';
		$includes       = isset( $instance['includes'] ) ? array_map( 'esc_attr', $instance['includes'] ) : '';

    	if ( !empty( $includes ) ) {

    		$includes = array_filter( $includes );
    		$tax_query = array(                           
				array(
					'taxonomy' => 'product_cat',
					'field' => 'term_id',
					'terms' => $includes,
					)
				);
    	} else {
    		$tax_query = array();
    	}

	    $number        	= ( ! empty( $instance['number'] ) ) ? intval( $instance['number'] ) : 6;
	    if ( ! $number ) {
	      $number = 6;
	    } 
	    $show_filter   	= isset( $instance['show_filter'] ) ? (bool) $instance['show_filter'] : true;
		$mode 			= isset( $instance['mode'] ) ? esc_html($instance['mode']) : 'grid';
		$orderby 		= isset( $instance['orderby'] ) ? esc_html($instance['orderby']) : 'date';
		$button_url 	= isset( $instance['button_url'] ) ? esc_url($instance['button_url']) : '';
		$button_text 	= isset( $instance['button_text'] ) ? esc_html($instance['button_text']) : '';


		$query = new WP_Query( array (
			'post_type' => 'product',
			'posts_per_page' => $number,
			'category_name' => '',
			'meta_key' => 'total_sales',
			'orderby' => $orderby,
			'tax_query' => $tax_query
		) );

		echo $args['before_widget'];

		if ( $query->have_posts() ) : ?>

			<?php if ( $title ) echo $args['before_title'] . $title . $args['after_title']; ?>

			<div class="best-seller-section wrap-<?php echo $mode; ?>">
				
				<?php if ( $mode == 'grid' ) : ?>
				    <?php //Begin product category filter
				      if ( $includes && $show_filter == true ) :
				         $included_ids = array();

				         foreach( $includes as $term ) {
				            $term_obj = get_term_by( 'id', $term, 'product_cat');
				            if (is_object($term_obj)) {
								$term_id  = $term_obj->term_id;
								$included_ids[] = $term_id;
				            }
				         }

				         $id_string = implode( ',', $included_ids );
				         $terms = get_terms( 'product_cat', array( 'include' => $id_string ) );
				    ?>

					<ul class="best-seller-categories">
						<li class="active"><a href="#" data-filter="*"><?php echo esc_html__( 'All', 'leto' ); ?></a></li>
				          <?php $count = count($terms);
				              if ( $count > 0 ){
				                foreach ( $terms as $term ) { ?>
				                <li><a href='#' data-filter=".<?php echo $term->slug; ?>"><?php echo $term->name; ?></a></li>
				              <?php }
				          } ?>
					</ul>
					<?php endif; ?>
				<?php endif; ?>
							
				<div class="woocommerce best-seller-products products <?php echo $mode; ?>">
			
				<?php while ( $query->have_posts() ) : $query->the_post(); ?>

					<?php 
						global $post;
						$id = $post->ID;
						$termsArray = get_the_terms( $id, 'product_cat' );
						$termsString = "";

						if ( $termsArray) {
							foreach ( $termsArray as $term ) {
								$termsString .= $term->slug . ' ';
							}
						}
					?>
	
					<div class="col-xs-6 col-sm-6 col-md-3 product <?php echo $termsString; ?>"> 
						<div class="product-inner">
							<?php if ( has_post_thumbnail() ) : ?>
							<div class="product-thumb">
								<a href="<?php the_permalink(); ?>" class="product-thumb__link">
									<?php echo woocommerce_get_product_thumbnail(); ?>
								</a>
							</div>
							<?php endif; ?>
							<div class="product-details">
								<?php woocommerce_template_loop_rating(); ?>
								<h2 class="product-title">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h2>
								<div class="product-price-button">
									<span class="product-price">
										<?php woocommerce_template_single_price(); ?>
									</span>
									<div class="product-button">
										<a href="#modal-quickview" class="product-quickview"><?php esc_html_e( 'Show more', 'leto' ); ?></a>
									</div>
								</div>
								<?php get_template_part( 'template-parts/woocommerce', 'modal' ); ?>
							</div>
						</div>
					</div>

				<?php endwhile; ?>

				</div>

				<?php if ( $button_url ) : ?>
				<a href="<?php echo $button_url; ?>" class="button-underline"><?php echo $button_text; ?></a>
				<?php endif; ?>

			</div><!-- /.best-seller-section -->


	<?php
		wp_reset_postdata();
		endif;
		echo $args['after_widget'];
	}
	
}