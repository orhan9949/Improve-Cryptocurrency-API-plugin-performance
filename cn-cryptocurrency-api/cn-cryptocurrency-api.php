<?php
/*
Plugin Name: CN Cryptocurrency API
Plugin URI: https://crypto.news
Author: Igor Avramchuk
Author URI: https://crypto.news
Description: The Cryptocurrency API Plugin for WordPress
Version: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

require_once 'includes/class-cn-cryptocurrency-api-plugin.php';
require_once 'includes/class-cn-cryptocurrency-api-coingecko.php';
require_once 'includes/class-cn-cryptocurrency-api-coingecko-json.php';
require_once 'includes/class-cn-cryptocurrency-api-routes.php';
require_once 'includes/template-functions.php';

$cn_cryptocurrency_api_plugin = new CN_Cryptocurrency_Api_Plugin();

register_activation_hook( __FILE__, [ $cn_cryptocurrency_api_plugin, 'activate' ] );
register_deactivation_hook( __FILE__, [ $cn_cryptocurrency_api_plugin, 'deactivate' ] );