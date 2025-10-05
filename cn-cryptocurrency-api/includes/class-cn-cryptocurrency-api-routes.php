<?php

class CN_Cryptocurrency_Api_Routes {
    /**
     * Constructor.
     */
    public function __construct() {
        add_action( 'rest_api_init', [ $this, 'register_rest_routes' ] );
    }

    /**
     * Register REST routes.
     *
     * @return void
     */
    public function register_rest_routes() {
        register_rest_route( 'crypto/v1', '/coins/(?P<id>[a-zA-Z0-9_-]+)', [
            'methods'             => 'GET',
            'callback'            => [ $this, 'get_coin_json' ],
            'permission_callback' => '__return_true',
        ] );

        register_rest_route( 'crypto/v1', '/markets/', [
            'methods'             => 'GET',
            'callback'            => [ $this, 'get_markets_json' ],
            'permission_callback' => '__return_true',
        ] );

        register_rest_route( 'crypto/v1', '/tickers/', [
            'methods'             => 'GET',
            'callback'            => [ $this, 'get_tickers_json' ],
            'permission_callback' => '__return_true',
        ] );

        register_rest_route( 'crypto/v1', '/gainers/', [
            'methods'             => 'GET',
            'callback'            => [ $this, 'get_gainers' ],
            'permission_callback' => '__return_true',
        ] );

        register_rest_route( 'crypto/v1', '/losers/', [
            'methods'             => 'GET',
            'callback'            => [ $this, 'get_losers' ],
            'permission_callback' => '__return_true',
        ] );
    }




    /**
     * Get Coin data.
     *
     * @param  WP_REST_Request $request
     *
     * @return WP_REST_Response
     */
    public function get_coin( $request ) {
        $request_params = $request->get_params();

        $coingecko = new CN_Cryptocurrency_Api_Coingecko();

        $data = $coingecko->get_coin( $request_params['id'], [
            'localization'     => false,
            'tickers'          => false,
            'community_data'   => false,
            'developer_data'   => false,
            'sparkline'        => false
        ] );

        return rest_ensure_response( $data );
    }


    /**
     * Get Coin data from json files.
     *
     * @param $request
     * @return WP_Error|WP_HTTP_Response|WP_REST_Response
     */
    public function get_coin_json( $request )
    {

        $request_params = $request->get_params();

        $coingecko = new CN_Cryptocurrency_Api_Coingecko_Json();

        $data = $coingecko->get_coin($request_params['id']);

        if ( !$data ){

            return self::get_coin( $request );

        }

        return rest_ensure_response( $data );
    }




    /**
     * Get Market data.
     *
     * @param  WP_REST_Request $request
     *
     * @return WP_REST_Response
     */
    public function get_markets( $request ) {

        $request_params = $request->get_params();

        $default_params = [
            'vs_currency'             => 'usd',
            'per_page'                => 250,
            'price_change_percentage' => '1h,24h,7d'
        ];

        $params = wp_parse_args( $request_params, $default_params );

        $coingecko = new CN_Cryptocurrency_Api_Coingecko();

        $data = $coingecko->get_markets( $params );

        return rest_ensure_response( $data );
    }




    /**
     * Get Market data from json file.
     *
     *
     * @return WP_Error|WP_HTTP_Response|WP_REST_Response
     */
    public function get_markets_json( $request ) {

        if( $request ) {

            return self::get_markets( $request );

        }

        $coingecko = new CN_Cryptocurrency_Api_Coingecko_Json();

        $data = $coingecko->get_markets();

        return rest_ensure_response( $data );

    }




    /**
     * Get Ticker data.
     *
     * @param  WP_REST_Request $request
     *
     * @return WP_REST_Response
     */
    public function get_tickers( $request ) {

        $request_params = $request->get_params();

        $default_params = [
            'vs_currency' => 'usd'
        ];

        $params = wp_parse_args( $request_params, $default_params );

        $fields = [
            'id', 'symbol', 'name', 'image', 'current_price', 'price_change_percentage_24h'
        ];

        $coingecko = new CN_Cryptocurrency_Api_Coingecko();

        $data = $coingecko->get_markets( $params, $fields );

        if ( array_key_exists( 'ids', $params ) ) {
            $data_raw    = $data;
            $data = array_flip( explode( ',', $params['ids'] ) );

            foreach ( $data_raw as $item ) {
                $data[$item['id']] = $item;
            }

            $data = array_values( $data );
        }

        return rest_ensure_response( $data );
    }


    /**
     * Get Ticker data from json files.
     *
     * @param $request
     * @return WP_Error|WP_HTTP_Response|WP_REST_Response
     */
    public function get_tickers_json( $request ) {

        $request_params = $request->get_params();

        $default_params = [
            'vs_currency' => 'usd'
        ];

        $params = wp_parse_args( $request_params, $default_params );

        $coingecko = new CN_Cryptocurrency_Api_Coingecko_Json();

        $data = $coingecko->get_tickers( $request_params );

        if ( array_key_exists( 'ids', $params ) ) {

            $data_raw = $data;

            $data = array_flip( explode( ',', $params['ids'] ) );

            foreach ( $data_raw as $item ) {

                $data[$item->id] = $item;

            }

            $data = array_values( $data );
        }

        return rest_ensure_response( $data );
    }



    /**
     * Get Gainers data.
     *
     * @param  WP_REST_Request $request
     *
     * @return WP_REST_Response
     */
    public function get_gainers( $request ) {
        $request_params = $request->get_params();

        $default_params = [
            'vs_currency' => 'usd'
        ];

        $params = wp_parse_args( $request_params, $default_params );

        $coingecko = new CN_Cryptocurrency_Api_Coingecko();
        $data = $coingecko->get_gainers( $params );

        return rest_ensure_response( $data );
    }




    /**
     * Get Losers data.
     *
     * @param  WP_REST_Request $request
     *
     * @return WP_REST_Response
     */
    public function get_losers( $request ) {
        $request_params = $request->get_params();

        $default_params = [
            'vs_currency' => 'usd'
        ];

        $params = wp_parse_args( $request_params, $default_params );

        $coingecko = new CN_Cryptocurrency_Api_Coingecko();
        $data = $coingecko->get_losers( $params );

        return rest_ensure_response( $data );
    }
}

new CN_Cryptocurrency_Api_Routes;