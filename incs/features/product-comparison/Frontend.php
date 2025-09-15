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
        Product_Display::instance();
        \add_action('wp_enqueue_scripts', [ $this, 'enqueue_assets' ]);
        \add_action('admin_enqueue_scripts', [ $this, 'enqueue_assets' ]);
        \add_action('wp_footer', [ $this, 'add_footer_elements' ]);
    }

    /**
     * Require sub-classes
     */
    public function incs() {
        require_once __DIR__ . '/Manage_Comparison.php';
        require_once __DIR__ . '/Shortcode.php';
        require_once __DIR__ . '/Product_Display.php';
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

    /**
     * Add footer elements (popup, footer bar, etc.)
     */
    public function add_footer_elements() {
        // Check if function exists
        if (!function_exists('\\Shopxpert\\incs\\shopxpert_get_option')) {
            return;
        }

        // Only add if comparison is enabled
        if (!\Shopxpert\incs\shopxpert_get_option('enable', 'product_comparison_settings_tabs', 'on')) {
            return;
        }

        // Add popup
        echo '<div class="shopxpert-compare-popup">';
        echo '<div class="shopxpert-compare-popup-content">';
        echo '<button class="shopxpert-compare-popup-close">×</button>';
        echo '<div class="shopxpert-compare-popup-body"></div>';
        echo '</div>';
        echo '</div>';

        // Add footer bar if enabled
        if (\Shopxpert\incs\shopxpert_get_option('show_footer_bar', 'product_comparison_settings_tabs', 'on')) {
            echo '<div class="shopxpert-compare-footer-bar">';
            echo '<div class="shopxpert-compare-footer-products"></div>';
            echo '<div class="shopxpert-compare-footer-actions">';
            echo '<button class="shopxpert-compare-btn shopxpert-compare-footer-view">' . \__('View Comparison', 'shopxpert') . '</button>';
            echo '<button class="shopxpert-compare-btn remove-all-compare">' . \__('Clear All', 'shopxpert') . '</button>';
            echo '</div>';
            echo '</div>';
        }
    }
} 