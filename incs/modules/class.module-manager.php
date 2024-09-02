<?php

use function Smartshop\incs\smartshop_get_option;
 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Smartshop_Module_Manager {

    private static $_instance = null;

    /**
     * Instance
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        // Ensure the plugin.php file is loaded
        if ( ! function_exists( 'is_plugin_active' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        if ( is_admin() ) {
            $this->include_under_admin();
        }

        $this->include_file();
    }

    /**
     * [include_under_admin] Necessary files required if admin page.
     * @return [void]
     */
    public function include_under_admin() {

        // Post Duplicator
        if ( !is_plugin_active('ht-mega-for-elementor/htmega_addons_elementor.php') ) {
            if ( smartshop_get_option( 'postduplicator', 'smartshop_others_tabs', 'off' ) === 'on' ) {
                require_once ( SMARTSHOP_ADDONS_PL_PATH . 'incs/modules/post-duplicator/class.post-duplicator.php' );
            }
        }

    }
    /**
     * [include_file] Necessary files required
     * @return [void]
     */
    public function include_file() {

        // Rename Label
        if ( !is_admin() && smartshop_get_option( 'enablerenamelabel', 'smartshop_rename_label_tabs', 'off' ) == 'on' ) {
            require( SMARTSHOP_ADDONS_PL_PATH . 'incs/modules/rename-label/rename_label.php' );
        }  

        // Backorder
        if( smartshop_get_option( 'enable', 'smartshop_backorder_settings', 'off' ) == 'on' ){
            require_once( SMARTSHOP_ADDONS_PL_PATH .'incs/modules/backorder/class.backorder.php' );
        }

    // Wishlist
        if( smartshop_get_option( 'wishlist', 'smartshop_others_tabs', 'off' ) == 'on' ){
            // $this->deactivate( 'wishsuite/wishsuite.php' );
            if( ! class_exists('WooWishSuite_Base') ){
                require_once( SMARTSHOP_ADDONS_PL_PATH .'incs/modules/wishlist/init.php' );
            }
        }

        // Pro-Modules
        if ( is_plugin_active('smartshop-addons-pro/smartshop_addons_pro.php') && defined( "SMARTSHOP_ADDONS_PL_PATH_PRO" ) ) {

            // Partial payment
            if ( ( smartshop_get_option( 'enable', 'smartshop_partial_payment_settings', 'off' ) == 'on' ) ) {
                require_once( SMARTSHOP_ADDONS_PL_PATH_PRO . 'incs/modules/partial-payment/partial-payment.php' );
            }
        }
    }

    /**
     * [include_product_filter_module_file] Include product filter module file
     * @return [void]
     */
    public function include_product_filter_module_file() {
        if ( file_exists( SMARTSHOP_ADDONS_PL_PATH_PRO . 'incs/modules/product-filter/product-filter.php' ) ) {
            require_once( SMARTSHOP_ADDONS_PL_PATH_PRO . 'incs/modules/product-filter/product-filter.php' );

            if ( smartshop_get_option( 'enable', 'smartshop_product_filter_settings', 'off' ) == 'on' ) {
                smartshop_product_filter( true );
            } else {
                smartshop_product_filter( false );
            }
        }
    }

    /**
     * [deactivate] Deactivate a plugin
     * @return [void]
     */
    public function deactivate( $slug ) {
        if ( is_plugin_active( $slug ) ) {
            return deactivate_plugins( $slug );
        }
    }
}

Smartshop_Module_Manager::instance();
