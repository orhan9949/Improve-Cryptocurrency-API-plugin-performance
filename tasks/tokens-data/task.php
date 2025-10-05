<?php

namespace tasks\tokensData;

class TokensData {

    private int $max_count_page;
    private int $max_per_page;

    function __construct(){

        $this->max_count_page = 5;
        $this->max_per_page = 250;
        $this->create_market_data_files_json( $this->max_count_page, $this->max_per_page );
    }

    /**
     * @param $per_page
     * @param $page_number
     * @return bool|string
     *
     * Get array tokens of Api request
     */
    private function coin_gecko_data( $per_page , $page_number ){

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://pro-api.coingecko.com/api/v3/coins/markets?vs_currency=usd&per_page={$per_page}&page={$page_number}&price_change_percentage=1h%2C24h%2C7d%2C1y",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'accept: application/json',
                'x-cg-pro-api-key: CG-XbuCsJZJs8TdYrMkDXnP2mSd',
              ),
        ));

        $response = curl_exec( $curl );

        curl_close( $curl );

        return $response;

    }


    /**
     * @param $max_count_page
     * @param $max_per_page
     * @return void
     *
     * Will be to creates files with data of every request and will be to write in files which to find in the folder data/markets/
     */
    private function create_market_data_files_json($max_count_page, $max_per_page){

        for( $page = 1; $page <= $max_count_page; $page++ ) {

            $result = $this->coin_gecko_data( $max_per_page, $page );

            file_put_contents('../data/markets/market-data-' . $page . '.json', $result);

        }

        $this->create_token_files_json();

    }


    /**
     * @return void
     *
     * Creates files json for ever tokens and writing in the folder /data/tokens/
     */
    private function create_token_files_json() {

        $this->delete_folder_tokens();

        for ( $i = 1; $i <= $this->max_count_page; $i++ ) {

            $get_market_data = file_get_contents( '../data/markets/market-data-' . $i . '.json' );

            $get_market_data = json_decode( $get_market_data );

            foreach ( $get_market_data as $token_data ) {

                $token_data_json = json_encode( $token_data );

                file_put_contents( '../data/tokens/' . $token_data->id . '.json', $token_data_json );

            }

        }

    }


    /**
     * @return void
     *
     * Deletes all files in the folder /data/tokens/
     */
    private function delete_folder_tokens(){

        array_map( 'unlink', glob( '../data/tokens/*.json') );

    }

}

new TokensData();