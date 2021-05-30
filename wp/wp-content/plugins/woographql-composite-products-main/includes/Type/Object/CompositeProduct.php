<?php

namespace WPGraphQL\WooCommerce\CompositeProducts;

use WPGraphQL\WooCommerce\Type\WPObject\Product_Types;
use WPGraphQL\WooCommerce\Type\WPInterface\Product;

const TYPE_COMPOSITE_PRODUCT = 'CompositeProduct';

add_action( 'graphql_register_types', function () {
	
	function get_composite_product_fields(): array {
		return [
            'compositeAddToCartFormLocation' => [ 
				'type'        => 'String',
				'description' => __( 'Add to Cart Form Location', 'woographql-composite-products' ),
				'resolve'     => function ( $source ) {
					$add_to_cart_form_location = $source->get_add_to_cart_form_location();

					return ! empty( $add_to_cart_form_location ) ? $add_to_cart_form_location : null;
				},
			],
            'compositeShopPriceCalc' => [
				'type'        => 'String',
				'description' => __( 'Shop Price Calculation', 'woographql-composite-products' ),
				'resolve'     => function ( $source ) {
					$shop_price_calc = $source->get_shop_price_calc();

					return ! empty( $shop_price_calc ) ? $shop_price_calc : null;
				},
			],
            'compositeLayout' => [
				'type'        => 'String',
				'description' => __( 'Layout', 'woographql-composite-products' ),
				'resolve'     => function ( $source ) {
					$layout = $source->get_layout();

					return ! empty( $layout ) ? $layout : null;
				},
			],
            'compositeEditableInCart' => [
				'type'        => 'Boolean',
				'description' => __( 'Editable In Cart', 'woographql-composite-products' ),
				'resolve'     => function ( $source ) {
					return $source->get_editable_in_cart();
				},
			],
            'compositeSoldIndividually' => [
				'type'        => 'String',
				'description' => __( 'Provides context when the "Sold Individually" option is set to "yes": "product" or "configuration"', 'woographql-composite-products' ),
				'resolve'     => function ( $source ) {
					$sold_individually_context = $source->get_sold_individually_context();
					
					return ! empty( $sold_individually_context ) ? $sold_individually_context : null;
				},
			],
            'compositeComponents' => [
				'type'        => [ 'list_of' => 'compositeComponent' ],
				'description' => __( 'Array of composite product components', 'woographql-composite-products' ),
				'resolve'     => function ( $source ) {
                    $components    = array();
                    $component_ids = $source->get_component_ids();

                    if ( empty( $component_ids ) ) {
                        return [];
                    }

                    foreach ( $component_ids as $component_id ) {
                        if ( $component_data = $source->get_component( $component_id )->get_data() ) {
                            error_log(print_r($component_data, true)); 
                            $component = [
                                // 'id' => $component_data['component_id'],
                                'databaseId' => $component_data['component_id'],
                                'title' => $component_data['title'],
                                'description' => $component_data['description'],
                                'queryType' => $component_data['query_type'],
                                'assignedIds' => $component_data['assigned_ids'],
                                'selectionMode' => $component_data['selection_mode'],
                                'thumbnailId' => $component_data['thumbnail_id'],
                                'quantityMin' => $component_data['quantity_min'],
                                'quantityMax' => $component_data['quantity_max'],
                                'discount' => $component_data['discount'],
                                'pricedIndividually' => $component_data['priced_individually'],
                                'shippedIndividually' => $component_data['shipped_individually'],
                                'optional' => $component_data['optional'],
                                'displayPrices' => $component_data['display_prices'],
                                // 'selectAction' => $component_data['select_action'],
                                'hideProductTitle' => $component_data['hide_product_title'],
                                'hideProductDescription' => $component_data['hide_product_description'],
                                'hideProductThumbnail' => $component_data['hide_product_thumbnail'],
                                'hideProductPrice' => $component_data['hide_product_price'],
                                'hideSubtotalPrice' => $component_data['hide_subtotal_price'],
                                'hideSubtotalProduct' => $component_data['hide_subtotal_product'],
                                'hideSubtotalCart' => $component_data['hide_subtotal_cart'],
                                'hideSubtotalOrders' => $component_data['hide_subtotal_orders'],
                                'showOrderby' => $component_data['show_orderby'],
                                'showFilters' => $component_data['show_filters'],
                                'position' => $component_data['position'],
                                // 'composite_id' => $component_data['composite_id'],
                                // 'paginationStyle' => $component_data['pagination_style'],
                            ];
                            $components[] = $component;
                        }
                    }
    				
					return ! empty( $components ) ? $components : [];
				},
			],
		];
	}
	
	register_graphql_object_type(
		TYPE_COMPOSITE_PRODUCT,
		[
			'description' => __( 'A composite product object', 'woographql-composite-products' ),
			'interfaces'  => Product_Types::get_product_interfaces(),
			'fields'      =>
				array_merge(
					Product::get_fields(),
					Product_Types::get_pricing_and_tax_fields(),
                    Product_Types::get_inventory_fields(),
					Product_Types::get_shipping_fields(),
					get_composite_product_fields(),
				),
		]
	);

} );

add_filter( 'graphql_product_types_enum_values', function ( $values ) {
	
    $values['COMPOSITE'] = [
		'value'       => 'composite',
		'description' => __( 'A composite product', 'woographql-composite-products' ),
	];
	
	return $values;

} );

add_filter( 'graphql_woocommerce_product_types', function ( $product_types ) {
	
    $product_types['composite'] = TYPE_COMPOSITE_PRODUCT;
	
	return $product_types;

} );
