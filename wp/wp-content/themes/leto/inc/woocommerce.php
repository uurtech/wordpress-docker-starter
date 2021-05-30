<?php
/**
 * Woocommerce compatibility
 *
 * @package Leto
 */

/**
 * Declare support
 */
function leto_wc_support() {

	add_theme_support( 'woocommerce', array(
		'gallery_thumbnail_image_width' => 600,
	) );
    add_theme_support( 'wc-product-gallery-lightbox' );
}
add_action( 'after_setup_theme', 'leto_wc_support' );

/**
 * Add and remove actions
 */
function leto_woocommerce_actions() {

	$fullwidth_archives = get_theme_mod( 'leto_fullwidth_shop_archives', 0 );

	//Theme wrappers
	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
	add_action('woocommerce_before_main_content', 'leto_wc_wrapper_start', 10);
	add_action('woocommerce_after_main_content', 'leto_wc_wrapper_end', 10);
	//Custom pricing and modal opener
	add_action( 'woocommerce_after_shop_loop_item_title', 'leto_loop_pricing_button' );
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price' );
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
	add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 11 );
	//Inner loop product wrapper
	add_action( 'woocommerce_before_shop_loop_item', 'leto_wrap_loop_product_content_before', 9 );
	add_action( 'woocommerce_after_shop_loop_item', 'leto_wrap_loop_product_content_after', 6 );
	//Remove all WC styling
	add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
	//Add classes to loop products
	add_filter( 'post_class', 'leto_wc_loop_classes' );
	//Move ratings before title
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
	add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_rating', 9 );
	//Remove sidebar on single products
	if ( is_single() || ( is_archive() && $fullwidth_archives ) ) {
		remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar' );
	}	
	//Single product wrappers
	add_action( 'woocommerce_before_single_product_summary', 'leto_wrap_single_product_before', 1 );
	add_action( 'woocommerce_after_single_product_summary', 'leto_wrap_single_product_after', 1 );
	//Single product gallery and details wrappers
	add_action( 'woocommerce_before_single_product_summary', 'leto_wrap_single_product_gallery_before', 1 );
	add_action( 'woocommerce_before_single_product_summary', 'leto_wrap_single_product_gallery_after', 999 );
	add_action( 'woocommerce_after_single_product_summary', 'leto_wrap_single_product_details_after', 0 );
	//Single gallery thumbs navigation
	add_action( 'woocommerce_before_single_product_summary', 'leto_single_gallery_nav', 21 );
	//Quantity buttons
	add_action( 'woocommerce_single_product_summary', 'leto_qty_buttons', 31 );
	//Remove page title from archives
	add_filter( 'woocommerce_show_page_title', '__return_false' );
	//Remove breadcrumbs
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
	//Shop results and ordering wrappers
	add_action( 'woocommerce_before_shop_loop', 'leto_before_shop_results', 19 );
	add_action( 'woocommerce_before_shop_loop', 'leto_after_shop_results', 21 );
	add_action( 'woocommerce_before_shop_loop', 'leto_before_shop_sorting', 29 );
	add_action( 'woocommerce_before_shop_loop', 'leto_after_shop_sorting', 31 );
	//Cart
	remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
	add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display' );
	//Relatd products
	if ( get_theme_mod( 'leto_wcs_single_hide_related' ) ) {
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
	}

}
add_action( 'wp', 'leto_woocommerce_actions' );

/**
 * Theme wrappers
 */
function leto_wc_wrapper_start() {

	$fullwidth_archives = get_theme_mod( 'leto_fullwidth_shop_archives', 0 );

	if ( is_archive() && $fullwidth_archives ) {
		$cols = 'col-md-12';
	} else {
		$cols = 'col-md-9';
	}

    echo '<div id="primary" class="content-area ' . $cols . '">';
        echo '<main id="main" class="site-main" role="main">';
}

function leto_wc_wrapper_end() {
        echo '</main>';
    echo '</div>';
}

/**
 * Helper function to check if current page is WC archive
 */
function leto_wc_archive_check() {
    if ( is_shop() || is_product_category() || is_product_tag() ) {
        return true;
    } else {
        return false;
    }
}

/**
 * Adds classes to Woocommerce products in the loop
 */
function leto_wc_loop_classes( $classes ) {

	$loop_check = leto_wc_archive_check();
	$columns_no = get_theme_mod( 'leto_columns_number', '4' );

	if ( $loop_check ) {
		if ( $columns_no == '4' ) {
			$classes[] = 'col-xs-6 col-sm-6 col-md-3 shop-4-cols';
		} elseif ( $columns_no == '3' ) {
			$classes[] = 'col-xs-6 col-sm-6 col-md-4 shop-3-cols';
		} elseif ( $columns_no == '2' ) {
			$classes[] = 'col-xs-6 col-sm-6 col-md-6 shop-2-cols';
		}
	}
	return $classes;
}

/**
 * Add inner wrapper for loop products
 */

function leto_wrap_loop_product_content_before() {
	echo '<div class="product-inner">';
}
function leto_wrap_loop_product_content_after() {
	echo '</div>';
}

/**
 * Wrapper for single products
 */
function leto_wrap_single_product_before() {
	echo '<div class="product-detail">';
	echo 	'<div class="row">';

}
function leto_wrap_single_product_after() {
	echo 	'</div>';	
	echo '</div>';
}

/**
 * Wrapper for single product galleries and product details
 */
function leto_wrap_single_product_gallery_before() {

	$product_layout = get_theme_mod( 'leto_product_layout', 'product-layout-1');

	global $product, $post;
	$gallery_ids = $product->get_gallery_image_ids();

	$gallery = empty( $gallery_ids ) ? 'no-gallery' : 'has-gallery';	

	if ( $product_layout == 'product-layout-2' ) {
		echo '<div class="col-xs-12 col-sm-12 col-md-5 product-images-wrapper">';
	} else {
		echo '<div class="col-xs-12 col-sm-12 col-md-6 product-images-wrapper ' . $gallery . '">';
	}


}
function leto_wrap_single_product_gallery_after() {
	echo '</div>';
	//Spacer
	echo '<div class="col-xs-12 col-sm-12 col-md-1"></div>';
	//Open product details wrapper
	$product_layout = get_theme_mod( 'leto_product_layout', 'product-layout-1');

	if ( $product_layout == 'product-layout-2' ) {
		echo '<div class="col-xs-12 col-sm-12 col-md-6 product-detail-summary sticky-element">';	
	} else {
		echo '<div class="col-xs-12 col-sm-12 col-md-5 product-detail-summary">';		
	}


}
function leto_wrap_single_product_details_after() {
	echo '</div>';
}

/**
 * Pricing and modal opener on loop products
 */
function leto_loop_pricing_button() {
	echo '<div class="product-price-button">';
	echo 	'<span class="product-price">';
			woocommerce_template_loop_price();
	echo 	'</span>';
	echo 	'<div class="product-button">';
	echo 		'<a href="#modal-quickview" class="product-quickview">' . esc_html__( 'Show more', 'leto' ) . '</a>';
	echo 	'</div>';
	echo '</div>';
	
	$hide_modal = get_theme_mod( 'leto_disable_quickview' );
	if ( !$hide_modal ) {
		get_template_part( 'template-parts/woocommerce', 'modal' );
	}
}

/**
 * Single gallery thumbs navigation
 */
function leto_single_gallery_nav() {

	$product_layout = get_theme_mod( 'leto_product_layout', 'product-layout-1' );

	global $product, $post;
	$gallery_ids = $product->get_gallery_image_ids();


	if ( ( $product_layout == 'product-layout-2' ) || empty( $gallery_ids ) ) {
		return;
	}

	if ( ( $product_layout == 'product-layout-1' ) || ( $product_layout == 'product-layout-4' ) ) {
		$vertical 	= 'true';
	} else {
		$vertical = 'false';
	}


	?>
	<div class="product-thumbnails row" data-vertical="<?php echo esc_attr( $vertical ); ?>">
	<?php
		$post_id = $post -> ID;
		$post_thumb = get_post_thumbnail_id( $post_id );
		echo '<div class="slick-slide">' . wp_get_attachment_image( $post_thumb, 'medium' ) . '</div>';
		foreach( $gallery_ids as $gallery_id ) {
			echo '<div class="slick-slide">' . wp_get_attachment_image( $gallery_id, 'medium' ) . '</div>';
		} 							
	?>
	</div>	
	<?php
}

/**
 * Increase/decrease quantity buttons
 */
function leto_qty_buttons() {
	echo '<a href="#" class="q-plus add"><i class="ion-plus"></i></a>
		 <a href="#" class="q-min min"><i class="ion-minus"></i></a>';
}

/**
 * Update cart
 */
function leto_header_add_to_cart_fragment( $fragments ) {
	ob_start();
	?>
	<?php $cart_content = WC()->cart->cart_contents_count; ?>

	<span class="cart-count">(<?php echo intval($cart_content); ?>)</span>

	<?php
	$fragments['.cart-count'] = ob_get_clean();


	ob_start();
	?>

	<div class="cart-mini-wrapper__inner"><?php woocommerce_mini_cart(); ?></div>

	<?php
	$fragments['.cart-mini-wrapper__inner'] = ob_get_clean();	
	
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'leto_header_add_to_cart_fragment' );

/**
 * Shop results and ordering wrappers
 */
function leto_before_shop_results() {
	echo '<div class="shop-filter-toolbar"><div class="row"><div class="hidden-xs col-sm-6 col-md-9"><span class="product-result-count">';
}
function leto_after_shop_results() {
	echo '</span></div>';
}
function leto_before_shop_sorting() {
	echo '<div class="col-xs-12 col-sm-6 col-md-3 text-right"><div class="product-ordering"><div class="select-wrapper">';
}
function leto_after_shop_sorting() {
	echo '</div></div></div></div></div><!-- /.shop-filter-toolbar -->';
}



function leto_before_checkout_details() {
	echo '<div class="col-xs-12 col-sm-12 col-md-7 col-customer-detail">';
}
add_action( 'woocommerce_checkout_before_customer_details', 'leto_before_checkout_details' );

function leto_after_checkout_details() {
	echo '</div>';
}
add_action( 'woocommerce_checkout_after_customer_details', 'leto_after_checkout_details' );



function leto_before_checkout_order() {
	echo '<div class="col-xs-12 col-sm-12 col-md-5 col-review-order">';
}
add_action( 'woocommerce_checkout_before_order_review', 'leto_before_checkout_order' );

function leto_after_checkout_order() {
	echo '</div>';
}
add_action( 'woocommerce_checkout_after_order_review', 'leto_after_checkout_order' );



function leto_wc_category_description ($category) {
    $id 			= $category->term_id;
    $term 			= get_term( $id, 'product_cat' );
    $description 	= $term->description;
	echo '<div class="product-category-desc"><p>' . $description . '</p></div>';
}
add_action( 'woocommerce_after_subcategory_title', 'leto_wc_category_description', 11 );

/**
 * Number of products
 */
function leto_woocommerce_products_number() {

    $number  = get_theme_mod( 'leto_archives_products_no', '10' );

    return $number;
}
add_filter( 'loop_shop_per_page', 'leto_woocommerce_products_number', 20 );

/**
 * Breadcrumbs
 */
function leto_breadcrumb_wrapper() {
	if ( is_product() ) { ?>
	<div class="page-header">
		<div class="container">
			<div class="page-breadcrumbs clearfix">
			<?php woocommerce_breadcrumb(); ?>
			</div>
		</div>
	</div>
	<?php
	}
}
add_action( 'leto_before_container', 'leto_breadcrumb_wrapper' );