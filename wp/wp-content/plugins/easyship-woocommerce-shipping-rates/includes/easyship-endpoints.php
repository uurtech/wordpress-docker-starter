<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Easyship_Endpoints
{
    public function __construct()
    {
        $this->rest_api_init();
    }

    private function rest_api_init() {
        // REST API was included starting WordPress 4.4.
        if ( ! class_exists( 'WP_REST_Server' ) ) {
            return;
        }

        $this->rest_api_includes();

        // Init REST API routes.
        add_action( 'rest_api_init', array( $this, 'register_rest_routes' ), 10 );
    }

    private function rest_api_includes()
    {
        include_once(dirname( __FILE__ ) . '/api/v1/class-easyship-rest-token.php');
    }

    public function register_rest_routes()
    {
        $controllers = array(
            'Easyship_REST_Token_V1_Controller'
        );

        foreach ( $controllers as $controller ) {
            $this->$controller = new $controller();
            $this->$controller->register_routes();
        }
    }
}