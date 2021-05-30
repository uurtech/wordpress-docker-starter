<div id="siteorigin-panels-metabox"
	data-builder-type="<?php echo esc_attr( $builder_type ) ?>"
	data-preview-url="<?php echo $preview_url; ?>"
	data-preview-markup="<?php echo esc_attr( json_encode( $preview_content ) ); ?>"
	data-builder-supports="<?php echo esc_attr( json_encode( $builder_supports ) ) ?>"
	<?php if( !empty( $_GET['so_live_editor'] ) ) echo 'data-live-editor="1"' ?>
	>
	<?php do_action('siteorigin_panels_before_interface') ?>
	<?php wp_nonce_field('save', '_sopanels_nonce') ?>

	<script type="text/javascript">
		( function( builderId, panelsData ){
			// Create the panels_data input
			document.write( '<input name="panels_data" type="hidden" class="siteorigin-panels-data-field" id="panels-data-field-' + builderId + '" />' );
			document.getElementById('panels-data-field-<?php echo esc_attr($builder_id) ?>').value = JSON.stringify( panelsData );
		} )( "<?php echo esc_attr($builder_id) ?>", <?php echo json_encode( $panels_data ); ?> );
	</script>

	<?php do_action('siteorigin_panels_metabox_end'); ?>
</div>
