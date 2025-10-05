#!/bin/bash

cd $(dirname $0)

. $HOME/files/.env

STARTDATE=$(date +%d-%m-%Y" "%H:%M:%S);

echo ""
echo "------ START: $STARTDATE ------"
echo "CG: Markets"
echo "CG: Downloading data..."

wget -O data.json -q --show-progress -T 5 --tries=3 --header="accepts: application/json" "https://pro-api.coingecko.com/api/v3/coins/markets?x_cg_pro_api_key=$CN_COINGECKO_API_KEY&vs_currency=usd&order=id_asc&ids=$CN_TICKER_IDS&sparkline=false&price_change_percentage=24h%2C7d"

echo ""
echo "CG: Formatting data..."

php task.php $CN_TICKER_IDS

ENDDATE=$(date +%d-%m-%Y" "%H:%M:%S);

echo ""
echo "CG: Done"
echo "------ END: $ENDDATE ------"
echo ""