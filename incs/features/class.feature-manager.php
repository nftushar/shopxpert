<?php

use function Shopxpert\incs\shopxpert_get_option;
 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Shopxpert_Feature_Manager {

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
            if ( shopxpert_get_option( 'postduplicator', 'shopxpert_others_tabs', 'off' ) === 'on' ) {
                require_once ( SHOPXPERT_ADDONS_PL_PATH . 'incs/features/post-duplicator/class.post-duplicator.php' );
            }
        }

    }
    /**
     * [include_file] Necessary files required
     * @return [void]
     */
    public function include_file() {

        // Change Label
        if ( !is_admin() && shopxpert_get_option( 'enablerenamelabel', 'shopxpert_rename_label_tabs', 'off' ) == 'on' ) {
            require( SHOPXPERT_ADDONS_PL_PATH . 'incs/features/rename-label/rename_label.php' );
        }  

        // Search
        if( shopxpert_get_option( 'ajaxsearch', 'shopxpert_others_tabs', 'off' ) == 'on' ){
            require( SHOPXPERT_ADDONS_PL_PATH. 'incs/features/ajax-search/main.php' );
        }
        

        // Pending Stock
        if( shopxpert_get_option( 'enable', 'shopxpert_backorder_settings', 'off' ) == 'on' ){
            require_once( SHOPXPERT_ADDONS_PL_PATH .'incs/features/backorder/class.backorder.php' );
        }

        // Wishlist
        if( shopxpert_get_option( 'wishlist', 'shopxpert_others_tabs', 'off' ) == 'on' ){
            // $this->deactivate( 'wishsuite/wishsuite.php' );
            if( ! class_exists('WooWishSuite_Base') ){
                require_once( SHOPXPERT_ADDONS_PL_PATH .'incs/features/wishlist/init.php' );
            }
        }

        // Pro-Features
        if ( is_plugin_active('shopxpert-addons-pro/shopxpert_addons_pro.php') && defined( "SHOPXPERT_ADDONS_PL_PATH_PRO" ) ) {

            // Partial payment
            if ( ( shopxpert_get_option( 'enable', 'shopxpert_partial_payment_settings', 'off' ) == 'on' ) ) {
                require_once( SHOPXPERT_ADDONS_PL_PATH_PRO . 'incs/features/partial-payment/partial-payment.php' );
            }
        }
    }

    /**
     * [include_product_filter_Feature_file] Include product filter Feature file
     * @return [void]
     */
    public function include_product_filter_Feature_file() {
        if ( file_exists( SHOPXPERT_ADDONS_PL_PATH_PRO . 'incs/features/product-filter/product-filter.php' ) ) {
            require_once( SHOPXPERT_ADDONS_PL_PATH_PRO . 'incs/features/product-filter/product-filter.php' );

            if ( shopxpert_get_option( 'enable', 'shopxpert_product_filter_settings', 'off' ) == 'on' ) {
                shopxpert_product_filter( true );
            } else {
                shopxpert_product_filter( false );
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

Shopxpert_Feature_Manager::instance();
