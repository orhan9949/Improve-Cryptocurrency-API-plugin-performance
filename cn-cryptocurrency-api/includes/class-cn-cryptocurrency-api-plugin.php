<?php

class CN_Cryptocurrency_Api_Plugin {
    /**
     * Constructor.
     */
    public function __construct() {
        add_action( 'plugins_loaded', [ $this, 'load_textdomain' ] );
        add_action( 'init', [ $this, 'init' ], 1 );
    }

    /**
     * Init.
     */
    public function init() {
    }

    /**
     * Fired when the plugin is activated.
     */
    public function activate() {
        $this->init();
        flush_rewrite_rules();
    }

    /**
     * Fired when the plugin is deactivated.
     */
    public function deactivate() {
        flush_rewrite_rules();
    }

    /**
     * Loads the plugin's translated strings.
     */
    public function load_textdomain() {
        load_plugin_textdomain( 'cn-cryptocurrency-api', false, dirname( plugin_basename( __FILE__ ) ) . '/../languages' );
    }
}
