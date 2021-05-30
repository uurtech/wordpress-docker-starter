<?php
/**
 * Composite Container Cart Item Data
 *
 * Override this template by copying it to 'yourtheme/woocommerce/cart/composite-container-cart-item-data.php'.
 *
 * On occasion, this template file may need to be updated and you (the theme developer) will need to copy the new files to your theme to maintain compatibility.
 * We try to do this as little as possible, but it does happen.
 * When this occurs the version of the template file will be bumped and the readme will list any important changes.
 *
 * @version 3.14.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<dl class="variation composite_configuration">
	<?php foreach ( $data as $item_data ) : ?>
		<dt class="component_key"><?php echo wp_kses_post( $item_data[ 'key' ] ); ?>:</dt>
		<dd class="component_value"><?php echo wp_kses_post( wpautop( $item_data[ 'value' ] ) ); ?></dd>
	<?php endforeach; ?>
</dl>
