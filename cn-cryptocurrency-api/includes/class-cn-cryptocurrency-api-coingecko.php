<?php

class CN_Cryptocurrency_Api_Coingecko {
    /**
     * Check for CoinGecko API key.
     *
     * @return boolean
     */
    public function has_api_key() {
        return defined( 'CN_COINGECKO_API_KEY' );
    }

    /**
     * Send reqest to the CoinGecko API
     *
     * @param  string  $endpoint
     * @param  array  $params
     * @param  integer $cache_duration
     *
     * @return array|boolean
     */
    public function send_request( $endpoint, array $params = [], $cache_duration = 3600 ) {

        $url = sprintf( 'https://pro-api.coingecko.com/api/v3%s?%s', $endpoint, http_build_query( $params ) );

        $cache_key = md5( $url );

        if ( $data_cached = wp_cache_get( $cache_key ) ) {
            return $data_cached;
        }

        if ( ! $this->has_api_key() ) {
            return __( 'No API Key provided', 'cn-cryptocurrencies' );
        }

        $response = wp_remote_get( $url, [
            'headers' => [
                'x-cg-pro-api-key' => CN_COINGECKO_API_KEY,
                'accept'           => 'application/json',
            ]
        ] );

        if ( ( ! is_wp_error( $response ) ) && ( 200 === wp_remote_retrieve_response_code( $response ) ) ) {
            $response_body = json_decode( wp_remote_retrieve_body( $response ), true );

            if( json_last_error() === JSON_ERROR_NONE ) {
                wp_cache_set( $cache_key, $response_body, '', $cache_duration );

                return $response_body;
            }
        }

        return false;
    }

    /**
     * Get Market data.
     *
     * @param  array $params
     * @param  array  $fields
     *
     * @return array
     */
    public function get_markets( $params, $fields = [] ) {
        $endpoint = '/coins/markets/';

        $data = $this->send_request( $endpoint, $params, 180 );

        $coins = [];

        foreach( $data as $item ) {
            $coin = [];

            if ( ! empty( $fields ) ) {
                foreach( $fields as $field ) {
                    $coin[$field] = $item[$field];
                }
            } else {
                $coin = $item;
            }

            $coin['link'] = cn_get_cryptocurrency_link_by_coingecko_id( $item['id'] );

            if ( $icon = cn_get_coin_icon( $item['id'] ) ) {
                $coin['image'] = $icon;
            }

            $coins[] = $coin;
        }

        return $coins;
    }

    /**
     * Get Coin data.
     *
     * @param  string $id
     * @param  array $params
     * @param  string $currency
     *
     * @return array
     */
    public function get_coin( $id, $params, $currency = 'usd' ) {
        $endpoint = sprintf( '/coins/%s/', $id );

        $response_body = $this->send_request( $endpoint, $params, 180 );

        $market_data = $response_body['market_data'];

        $market_data_fields = [
            'current_price',
            'ath',
            'ath_change_percentage',
            'ath_date',
            'atl',
            'atl_change_percentage',
            'atl_date',
            'market_cap',
            'market_cap_rank',
            'fully_diluted_valuation',
            'total_volume',
            'high_24h',
            'low_24h',
            'price_change_percentage_24h',
            'price_change_percentage_7d',
            'price_change_percentage_14d',
            'price_change_percentage_30d',
            'price_change_percentage_60d',
            'price_change_percentage_200d',
            'price_change_percentage_1y',
            'market_cap_change_24h',
            'market_cap_change_percentage_24h',
            'price_change_24h_in_currency',
            'price_change_percentage_1h_in_currency',
            'price_change_percentage_24h_in_currency',
            'price_change_percentage_7d_in_currency',
            'price_change_percentage_14d_in_currency',
            'price_change_percentage_30d_in_currency',
            'price_change_percentage_60d_in_currency',
            'price_change_percentage_200d_in_currency',
            'price_change_percentage_1y_in_currency',
            'market_cap_change_24h_in_currency',
            'market_cap_change_percentage_24h_in_currency',
            'total_supply',
            'max_supply',
            'circulating_supply',
            'last_updated',
        ];

        $data = [
            'id'     => $response_body['id'],
            'symbol' => $response_body['symbol'],
            'name'   => $response_body['name'],
            'image'  => $response_body['image']['large'],
            'link'   => false
        ];

        foreach ( $market_data_fields as $field ) {
            if ( is_array( $market_data[$field] ) && ! empty( $market_data[$field] ) ) {
                $data[$field] = $market_data[$field][$currency];
            } else {
                $data[$field] = $market_data[$field];
            }
        }

        if ( function_exists( 'cn_get_cryptocurrency_link_by_coingecko_id' ) ) {
            $data['link'] = cn_get_cryptocurrency_link_by_coingecko_id( $id );
        }

        if ( $icon = cn_get_coin_icon( $data['id'] ) ) {
            $data['image'] = $icon;
        }

        return $data;
    }

    /**
     * Get Gainers data.
     *
     * @param  array $params
     *
     * @return array
     */
    public function get_gainers( $params ) {
        $endpoint = '/coins/top_gainers_losers/';

        $response_body = $this->send_request( $endpoint, $params, 600 );

        return $response_body['top_gainers'];
    }

    /**
     * Get Losers data.
     *
     * @param  array $params
     *
     * @return array
     */
    public function get_losers( $params ) {
        $endpoint = '/coins/top_gainers_losers/';

        $response_body = $this->send_request( $endpoint, $params, 600 );

        return $response_body['top_losers'];
    }
}