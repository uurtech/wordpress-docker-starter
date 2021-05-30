<?php

add_action( 'graphql_register_types', function () {

    register_graphql_object_type(
		'CompositeComponent',
		[
			'description' => __( 'A composite component object', 'woographql-composite-products' ),
            'fields' => [
                // 'id' => array(
                //     'type'        => array( 'non_null' => 'ID' ),
                //     'description' => __( 'The globally unique identifier for the component', 'woographql-composite-products' ),
                // ),
                'databaseId' => array(
                    'type'        => array( 'non_null' => 'Int' ),
                    'description' => __( 'The ID of the component in the database', 'woographql-composite-products' ),
                ),
                'title' => [
					'type'        => 'String',
					'description' => __( 'Title', 'woographql-composite-products' ),
				],
                'description' => [
					'type'        => 'String',
					'description' => __( 'Description', 'woographql-composite-products' ),
				],
				'queryType' => [
					'type'        => 'String',
                    'description' => __( 'Query Type', 'woographql-composite-products' ),
				],
                'assignedIds' => [
					'type'        => [ 'list_of' => 'Number' ],
                    'description' => __( 'Assigned Ids', 'woographql-composite-products' ),
				],
                'selectionMode' => [
					'type'        => 'String',
                    'description' => __( 'Selection Mode', 'woographql-composite-products' ),
				],
                'thumbnailId' => [
					'type'        => 'String',
					'description' => __( 'Thumbnail ID', 'woographql-composite-products' ),
				],
                'quantityMin' => [
					'type'        => 'Number',
					'description' => __( 'Min Quantity', 'woographql-composite-products' ),
				],
                'quantityMax' => [
					'type'        => 'Number',
					'description' => __( 'Max Quantity', 'woographql-composite-products' ),
				],
                'discount' => [
					'type'        => 'String',
					'description' => __( 'Discount', 'woographql-composite-products' ),
				],
                'pricedIndividually' => [
					'type'        => 'Boolean',
					'description' => __( 'Priced Individually', 'woographql-composite-products' ),
				],
                'shippedIndividually' => [
					'type'        => 'Boolean',
					'description' => __( 'Shipped Individually', 'woographql-composite-products' ),
				],
                'optional' => [
					'type'        => 'Boolean',
					'description' => __( 'Optional', 'woographql-composite-products' ),
				],
                'displayPrices' => [
					'type'        => 'String',
					'description' => __( 'Display Prices', 'woographql-composite-products' ),
				],
                // 'selectAction' => [
				// 	'type'        => 'String',
				// 	'description' => __( 'Select Action', 'woographql-composite-products' ),
				// ],
                'hideProductTitle' => [
					'type'        => 'Boolean',
					'description' => __( 'Hide Product Title', 'woographql-composite-products' ),
				],
                'hideProductDescription' => [
					'type'        => 'Boolean',
					'description' => __( 'Hide Product Description', 'woographql-composite-products' ),
				],
                'hideProductThumbnail' => [
					'type'        => 'Boolean',
					'description' => __( 'Hide Product Thumbnail', 'woographql-composite-products' ),
				],
                'hideProductPrice' => [
					'type'        => 'Boolean',
					'description' => __( 'Hide Product Price', 'woographql-composite-products' ),
				],
                'hideSubtotalPrice' => [
					'type'        => 'Boolean',
					'description' => __( 'Hide Subtotal Price', 'woographql-composite-products' ),
				],
                'hideSubtotalProduct' => [
					'type'        => 'Boolean',
					'description' => __( 'Hide Subtotal Product', 'woographql-composite-products' ),
				],
                'hideSubtotalCart' => [
					'type'        => 'Boolean',
					'description' => __( 'Hide Subtotal Cart', 'woographql-composite-products' ),
				],
                'hideSubtotalOrders' => [
					'type'        => 'Boolean',
					'description' => __( 'Hide Subtotal Orders', 'woographql-composite-products' ),
				],
                'showOrderby' => [
					'type'        => 'Boolean',
					'description' => __( 'Show Orderby', 'woographql-composite-products' ),
				],
                'showFilters' => [
					'type'        => 'Boolean',
					'description' => __( 'Show Filters', 'woographql-composite-products' ),
				],
                'position' => [
					'type'        => 'Number',
					'description' => __( 'Position', 'woographql-composite-products' ),
				],
                // 'composite_id' => [
				// 	'type'        => 'String',
				// 	'description' => __( 'Composite ID', 'woographql-composite-products' ),
				// ],
                // 'paginationStyle' => [
				// 	'type'        => 'String',
				// 	'description' => __( 'Pagination Style', 'woographql-composite-products' ),
				// ],
			],
		]
	);

} );