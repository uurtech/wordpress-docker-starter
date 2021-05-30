<?php

namespace WPGraphQL\WooCommerce\CompositeProducts\Connection\CompositeComponents;

use WPGraphQL\AppContext;
use GraphQL\Type\Definition\ResolveInfo; 
use WPGraphQL\WooCommerce\Data\Connection\Product_Connection_Resolver;
// use const WPGraphQL\WooCommerce\CompositeProducts\TYPE_COMPOSITE_PRODUCT;  

add_action( 'graphql_register_types', function () {

	register_graphql_connection( [ 
		'fromType'      => 'CompositeComponent', 
		'toType'        => 'Product',
		'fromFieldName' => 'options',
		'resolve'       => function ( $source, $args, $context, $info ) {
			$resolver = new Product_Connection_Resolver( $source, $args, $context, $info );
            $product_ids = $source['assignedIds'];

            if ( empty( $product_ids ) ) {
                return [];
            }

            $resolver->set_query_arg( 'post__in', $product_ids ); 
            return $resolver->get_connection();
		}
	] );

} );
