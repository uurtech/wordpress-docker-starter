<?php

// Multisite not supported

if (! defined( 'WP_UNINSTALL_PLUGIN' ) )
    die();


// uninstall delete option
delete_option( 'woocommerce_easyship_settings' );
