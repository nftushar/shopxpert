<?php

namespace Shopxpert\ProductComparison;

use function Shopxpert\incs\shopxpert_get_option;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Product Comparison Main Class
 */
final class Shopxpert_Product_Comparison_Base {
    /**
     * [$_instance]
     * @var null
     */
    private static $_instance = null;

    /**
     * [instance] Initializes a singleton instance
     * @return [Base]
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * [__construct] Class Constructor
     */
    private function __construct() {
        $this->define_constants();
        $this->init_plugin();
    }

    /**
     * Define the required plugin constants
     *
     * @return void
     */
    public function define_constants() {
        define( 'PRODUCT_COMPARISON_FILE', __FILE__ );
        define( 'PRODUCT_COMPARISON_MODULE_PATH', __DIR__ );
        define( 'PRODUCT_COMPARISON_URL', \plugins_url( '', PRODUCT_COMPARISON_FILE ) );
        define( 'PRODUCT_COMPARISON_DIR', \plugin_dir_path( PRODUCT_COMPARISON_FILE ) );
        define( 'PRODUCT_COMPARISON_ASSETS', PRODUCT_COMPARISON_URL . '/assets' );
        define( 'PRODUCT_COMPARISON_BASE', \plugin_basename( PRODUCT_COMPARISON_FILE ) );
    }

    /**
     * Initialize the plugin
     *
     * @return void
     */
    public function init_plugin() {
        // Here you would load assets, hooks, and settings usage
        // Example: $this->load_settings();
        \add_action('wp_ajax_shopxpert_comparison_add', [ $this, 'ajax_add_product' ]);
        \add_action('wp_ajax_nopriv_shopxpert_comparison_add', [ $this, 'ajax_add_product' ]);
        \add_action('wp_ajax_shopxpert_comparison_remove', [ $this, 'ajax_remove_product' ]);
        \add_action('wp_ajax_nopriv_shopxpert_comparison_remove', [ $this, 'ajax_remove_product' ]);
        \add_action('wp_ajax_shopxpert_comparison_remove_all', [ $this, 'ajax_remove_all' ]);
        \add_action('wp_ajax_nopriv_shopxpert_comparison_remove_all', [ $this, 'ajax_remove_all' ]);
        if ( is_admin() ) {
            require_once __DIR__ . '/Admin_Fields.php';
            \add_action( 'admin_menu', [ $this, 'register_admin_menu' ] );
        }
    }

    /**
     * Example: Load settings (stub)
     */
    public function get_settings() {
        // Example usage of settings:
        $show_btn_product_list = shopxpert_get_option( 'show_btn_product_list', 'shopxpert_product_comparison_settings', 'on' );
        $button_text = shopxpert_get_option( 'button_text', 'shopxpert_product_comparison_settings', 'Compare' );
        // ... load other settings as needed
        return [
            'show_btn_product_list' => $show_btn_product_list,
            'button_text' => $button_text,
            // ...
        ];
    }

    /**
     * Example: Render compare button (stub)
     */
    public function render_compare_button( $product_id ) {
        $settings = $this->get_settings();
        // Output button HTML based on settings
        echo '<button class="shopxpert-compare-btn">' . \esc_html( $settings['button_text'] ) . '</button>';
    }

    /**
     * AJAX: Add product to comparison
     */
    public function ajax_add_product() {
        check_ajax_referer('shopxpert_comparison_nonce', 'nonce');
        $product_id = intval($_POST['product_id'] ?? 0);
        \Shopxpert\ProductComparison\Manage_Comparison::instance()->add_product($product_id);
        $button_text = \__( 'Remove from Compare', 'shopxpert' );
        $table_html = do_shortcode('[product_comparison_table]');
        wp_send_json_success([
            'button_text' => $button_text,
            'table_html' => $table_html,
        ]);
    }

    /**
     * AJAX: Remove product from comparison
     */
    public function ajax_remove_product() {
        check_ajax_referer('shopxpert_comparison_nonce', 'nonce');
        $product_id = intval($_POST['product_id'] ?? 0);
        \Shopxpert\ProductComparison\Manage_Comparison::instance()->remove_product($product_id);
        $button_text = \__( 'Add to Compare', 'shopxpert' );
        $table_html = do_shortcode('[product_comparison_table]');
        wp_send_json_success([
            'button_text' => $button_text,
            'table_html' => $table_html,
        ]);
    }

    /**
     * AJAX: Remove all products from comparison
     */
    public function ajax_remove_all() {
        check_ajax_referer('shopxpert_comparison_nonce', 'nonce');
        \Shopxpert\ProductComparison\Manage_Comparison::instance()->clear_comparison();
        $table_html = do_shortcode('[product_comparison_table]');
        wp_send_json_success([
            'table_html' => $table_html,
        ]);
    }

    /**
     * Register Product Comparison admin menu
     */
    public function register_admin_menu() {
        add_submenu_page(
            'shopxpert_page',
            esc_html__( 'Product Comparison', 'shopxpert' ),
            esc_html__( 'Product Comparison', 'shopxpert' ),
            'manage_options',
            'product_comparison',
            [ \Shopxpert\ProductComparison\Admin_Fields::instance(), 'plugin_page' ]
        );
    }

    // Add more methods for popup, table, etc. as needed
}

/**
 * Initializes the main Product Comparison plugin
 *
 * @return Shopxpert_Product_Comparison_Base
 */
function Shopxpert_Product_Comparison() {
    return Shopxpert_Product_Comparison_Base::instance();
}

// Initialize on plugins_loaded or as needed
// add_action( 'plugins_loaded', '\Shopxpert\ProductComparison\Shopxpert_Product_Comparison' );
