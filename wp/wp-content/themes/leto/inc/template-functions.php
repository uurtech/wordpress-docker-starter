<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Leto
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function leto_body_classes( $classes ) {


	$sidebar = get_theme_mod( 'leto_fullwidth_blog_archives' );

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	if ( ( is_single() && !is_page_template('post-templates/post_full.php' ) && is_active_sidebar( 'sidebar-1' ) ) || ( ( is_home() || is_archive() ) && $sidebar ) ) {
		$classes[] = 'has-sidebar';
	}

	if ( is_front_page() ) {
		$classes[] = 'has-hero';
	}

	$product_layout = get_theme_mod( 'leto_product_layout', 'product-layout-1' );
	if ( class_exists( 'WooCommerce' ) && is_product() ) {
		$classes[] = $product_layout;
	}

	return $classes;
}
add_filter( 'body_class', 'leto_body_classes' );

/**
 * Add hentry class to products in search
 *
 */
function leto_add_class_product_search( $classes, $class = '', $post_id = '' ) {

	$classes[] = '';

	if ( class_exists( 'WooCommerce' ) ) {
		$product = wc_get_product( $post_id );

		if( $product && is_search()  ) {
			$classes[] = 'hentry';
		}
	}

	return $classes;		

} 
add_filter( 'post_class', 'leto_add_class_product_search', 3, 20 );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function leto_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'leto_pingback_header' );

/**
 * Container type check
 */

function leto_container_type() {

	if ( class_exists( 'WooCommerce' ) ) {
		$wc_check = leto_wc_archive_check();
		if ( $wc_check ) {
			$container = 'container-full';
		} else {
			$container = 'container';
		}
	} else {
		$container = 'container';
	}

	return $container;
}


/**
 * Single comment template
 */
function leto_comment_template($comment, $args, $depth) {

	?>
	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( $comment->has_children ? 'parent' : '' ); ?>>
		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body comment-entry clearfix">
			<figure class="comment-avatar">
				<?php echo get_avatar( $comment, $args['avatar_size'] ); ?>
			</figure>

			<div class="comment-text">
				<header class="comment-head">
					<h5 class="comment-author-name">
						<span><?php printf( '<b class="fn">%s</b>', get_comment_author_link() ) ; ?></span>
					</h5>
					 &#8211; 
					<time class="comment-time" datetime="<?php comment_time( 'c' ); ?>">
						<?php printf( _x( '%1$s at %2$s', '1: date, 2: time', 'leto' ), get_comment_date(), get_comment_time() ); ?>
					</time>
				</header>

				<div class="comment-body">
					<?php if ( '0' == $comment->comment_approved ) : ?>
					<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'leto' ); ?></p>
					<?php endif; ?>

					<div class="comment-content">
						<?php comment_text(); ?>
					</div>
					<div class="comment-links">
					<?php edit_comment_link( __( 'Edit', 'leto' ), '<span class="edit-link">', '</span>' ); ?>
					&nbsp;
					<?php comment_reply_link( array_merge( $args, array( 'add_below' => 'edit-link', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>													
					</div>
				</div>
			</div>
		</article>
	<?php
}


/**
 * Excerpt length
 */
function leto_excerpt_length( $length ) {

	if ( is_admin() ) {
		return $length;
	}

	$excerpt = get_theme_mod('leto_exc_length', '20');
	return $excerpt;
}
add_filter( 'excerpt_length', 'leto_excerpt_length', 999 );

/**
 * Excerpt read more
 */
function leto_custom_excerpt( $more ) {

	if ( is_admin() ) {
		return $more;
	}

	$more = get_theme_mod('leto_custom_read_more');
  	if ( $more == '' ) {
    	return '&nbsp;[&hellip;]';
	} else {
		return ' <a class="read-more" href="' . get_permalink( get_the_ID() ) . '">' . esc_html( $more ) . '</a>';
	}
}
add_filter( 'excerpt_more', 'leto_custom_excerpt' );


/**
 * Dynamic styles
 */
function leto_dynamic_styles($custom) {

	$custom = '';

	//Get settings
	$quickview 				= get_theme_mod( 'leto_disable_quickview', 0 );
	$hide_product_price 	= get_theme_mod( 'leto_hide_product_price', 0 );
	$hide_product_rating 	= get_theme_mod( 'leto_hide_product_ratings', 0 );
	$hide_product_sorting 	= get_theme_mod( 'leto_hide_product_sorting', 0 );
	$hide_results_no 		= get_theme_mod( 'leto_hide_results_number', 0 );
	$hide_rating_single		= get_theme_mod( 'leto_hide_product_ratings_singles', 0 );

	if ( $quickview ) {
		$custom .= ".woocommerce.products .product:hover .product-price, .woocommerce .products .product:hover .product-price {transform:none;}"."\n";
		$custom .= ".woocommerce.products .product:hover .product-button, .woocommerce .products .product:hover .product-button {top:100%;}"."\n";
	}
	if ( $hide_product_price ) {
		$custom .= ".post-type-archive-product.woocommerce .products .product .product-button {top:0;opacity:1;visibility:visible;}"."\n";
		$custom .= ".post-type-archive-product.woocommerce .products .product .product-price {visibility:hidden;}"."\n";
	}
	if ( $hide_product_rating ) {
		$custom .= ".post-type-archive-product.woocommerce .products .product .star-rating {display:none;}"."\n";		
	}
	if ( $hide_product_sorting ) {
		$custom .= ".post-type-archive-product.woocommerce .product-ordering {display:none;}"."\n";		
	}
	if ( $hide_results_no ) {
		$custom .= ".post-type-archive-product.woocommerce .product-result-count {display:none;}"."\n";		
	}

	if ( $hide_rating_single ) {
		$custom .= ".woocommerce.single-product .product .star-rating {display:none;}"."\n";				
	}

	//Output all the styles
	wp_add_inline_style( 'leto-style', $custom );	
}
add_action( 'wp_enqueue_scripts', 'leto_dynamic_styles' );

