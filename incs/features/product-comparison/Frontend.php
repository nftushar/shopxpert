<?php

namespace Shopxpert\ProductComparison;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Frontend handlers class for Product Comparison
 */
class Frontend {
    /**
     * [$_instance]
     * @var null
     */
    private static $_instance = null;

    /**
     * [instance] Initializes a singleton instance
     * @return [Frontend]
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Initialize the class
     */
    private function __construct() {
        $this->incs();
        Shortcode::instance();
        Manage_Comparison::instance();
        \add_action('wp_enqueue_scripts', [ $this, 'enqueue_assets' ]);
        \add_action('admin_enqueue_scripts', [ $this, 'enqueue_assets' ]);
    }

    /**
     * Require sub-classes
     */
    public function incs() {
        require_once __DIR__ . '/Manage_Comparison.php';
        require_once __DIR__ . '/Shortcode.php';
    }

    /**
     * Enqueue frontend and admin assets for product comparison
     */
    public function enqueue_assets() {
        $css = PRODUCT_COMPARISON_URL . '/assets/css/product-comparison.css';
        $js = PRODUCT_COMPARISON_URL . '/assets/js/product-comparison.js';
        if ( \file_exists( PRODUCT_COMPARISON_DIR . '/assets/css/product-comparison.css' ) ) {
            \wp_enqueue_style( 'shopxpert-product-comparison', $css, [], null );
        }
        if ( \file_exists( PRODUCT_COMPARISON_DIR . '/assets/js/product-comparison.js' ) ) {
            \wp_enqueue_script( 'shopxpert-product-comparison', $js, [ 'jquery' ], null, true );
            \wp_localize_script( 'shopxpert-product-comparison', 'ShopxpertComparison', [
                'ajax_url' => \admin_url( 'admin-ajax.php' ),
                'nonce'    => \wp_create_nonce( 'shopxpert_comparison_nonce' ),
            ] );
        }
    }

    // Future: Add methods for asset loading, hooks, etc.
    // Note: The next step is to create the assets/js directory and product-comparison.js file for AJAX interactivity.
} 