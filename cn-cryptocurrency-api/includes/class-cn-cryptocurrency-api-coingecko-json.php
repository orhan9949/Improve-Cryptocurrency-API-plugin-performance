<?php

class CN_Cryptocurrency_Api_Coingecko_Json {

    /**
     *  Get Market data from file json market-data-1.json.
     *
     * @return array
     */
    public function get_markets(): array
    {

        $get_market_data = file_get_contents('../data/markets/market-data-1.json');

        $get_market_data = json_decode( $get_market_data );

        $coins = [];

        foreach ( $get_market_data as $item ) {

            $item->link = cn_get_cryptocurrency_link_by_coingecko_id( $item->id );

            if ( $icon = cn_get_coin_icon( $item->id ) ) {

                $item->image = $icon;

            }

            $coins[] = $item;

        }

        return $coins;

    }


    /**
     * Return coin object from json files
     *
     * @param $name_id
     * @return false|mixed
     */
    public function get_coin( $name_id )
    {

        if( file_exists('../data/tokens/'.$name_id.'.json') ) {

            $get_ticket_data = file_get_contents( '../data/tokens/' . $name_id . '.json' );

        } else {

            return  false;

        }

        $get_ticket_data = json_decode( $get_ticket_data );

        $get_ticket_data->price_change_percentage_1y = $get_ticket_data->price_change_percentage_1y_in_currency;

        $get_ticket_data->price_change_percentage_7d = $get_ticket_data->price_change_percentage_7d_in_currency;

        $get_ticket_data->link = cn_get_cryptocurrency_link_by_coingecko_id( $get_ticket_data->id );

        return $get_ticket_data;

    }


    /**
     * Return array tokens from json files
     *
     * @param $params
     * @return array
     */
    public function get_tickers( $params ): array
    {

        $tickers_data = [];

        $params = explode( ",", $params['ids'] );

        foreach ( $params as $id ) {

            $get_ticket_data = file_get_contents('../data/tokens/' . $id . '.json');

            $get_ticket_data = json_decode( $get_ticket_data );

            $get_ticket_data->link = cn_get_cryptocurrency_link_by_coingecko_id( $get_ticket_data->id );

            if ( $icon = cn_get_coin_icon( $get_ticket_data->id ) ) {

                $get_ticket_data->image = $icon;

            }

            $tickers_data[] = $get_ticket_data;

        }

        return $tickers_data;

    }
}