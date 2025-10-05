<?php

/**
 * Get Coin data.
 *
 * @param  string $id
 *
 * @return object
 */
function cn_get_coin( $id ) {
    $endpoint = sprintf( '/crypto/v1/coins/%s', $id );

    $request = new WP_REST_Request( 'GET', $endpoint );
    $response = rest_do_request( $request );

    if ( $response->is_error() ) {
        return [];
    }

    $data = $response->get_data();

    return (object) $data;
}

/**
 * Get Market data.
 *
 * @param  array $params
 *
 * @return object
 */
function cn_get_markets( $params = [] ) {
    $request  = new WP_REST_Request( 'GET', '/crypto/v1/markets' );
    $request->set_query_params( $params );

    $response = rest_do_request( $request );

    if ( $response->is_error() ) {
        return [];
    }

    $data = $response->get_data();

    return json_decode( json_encode( $data ), false );
}

/**
 * Get Ticker data.
 *
 * @param  array $params
 *
 * @return object
 */
function cn_get_tickers( $params ) {
    $request  = new WP_REST_Request( 'GET', '/crypto/v1/tickers' );
    $request->set_query_params( $params );

    $response = rest_do_request( $request );

    if ( $response->is_error() ) {
        return [];
    }

    $data = $response->get_data();

    return json_decode( json_encode( $data ), false );
}

/**
 * Get Coin Icon.
 *
 * @param  string $symbol
 *
 * @return string
 */
function cn_get_coin_icon( $symbol ) {
    $path = sprintf( ABSPATH . '../images/icons/%s.svg', $symbol );
    $url  = sprintf( WP_HOME . '/images/icons/%s.svg', $symbol );

    if ( file_exists( $path ) ) {
        return $url;
    }

    return false;
}

/**
 * Get Gainers data.
 *
 * @param  array $params
 *
 * @return object
 */
function cn_get_gainers( $params ) {
    $request  = new WP_REST_Request( 'GET', '/crypto/v1/gainers' );
    $request->set_query_params( $params );

    $response = rest_do_request( $request );

    if ( $response->is_error() ) {
        return [];
    }

    $data = $response->get_data();

    return json_decode( json_encode( $data ), false );
}

/**
 * Get Losers data.
 *
 * @param  array $params
 *
 * @return object
 */
function cn_get_losers( $params ) {
    $request  = new WP_REST_Request( 'GET', '/crypto/v1/losers' );
    $request->set_query_params( $params );

    $response = rest_do_request( $request );

    if ( $response->is_error() ) {
        return [];
    }

    $data = $response->get_data();

    return json_decode( json_encode( $data ), false );
}