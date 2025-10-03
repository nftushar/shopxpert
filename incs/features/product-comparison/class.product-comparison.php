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
        \add_action('wp_ajax_shopxpert_comparison_count', [ $this, 'ajax_get_count' ]);
        \add_action('wp_ajax_nopriv_shopxpert_comparison_count', [ $this, 'ajax_get_count' ]);
        \add_action('wp_ajax_shopxpert_comparison_search', [ $this, 'ajax_search_products' ]);
        \add_action('wp_ajax_nopriv_shopxpert_comparison_search', [ $this, 'ajax_search_products' ]);
        \add_action('wp_ajax_shopxpert_comparison_footer_data', [ $this, 'ajax_get_footer_data' ]);
        \add_action('wp_ajax_nopriv_shopxpert_comparison_footer_data', [ $this, 'ajax_get_footer_data' ]);
       
        // Register widget
        \add_action( 'widgets_init', [ $this, 'register_widget' ] );
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
     * AJAX: Get comparison count
     */
    public function ajax_get_count() {
        check_ajax_referer('shopxpert_comparison_nonce', 'nonce');
        $list = \Shopxpert\ProductComparison\Manage_Comparison::instance()->get_comparison_list();
        wp_send_json_success([
            'count' => count($list),
        ]);
    }

    /**
     * AJAX: Search products
     */
    public function ajax_search_products() {
        check_ajax_referer('shopxpert_comparison_nonce', 'nonce');
        $query = sanitize_text_field($_POST['query'] ?? '');
        
        if (strlen($query) < 2) {
            wp_send_json_success(['products' => []]);
        }
        
        $args = [
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => 10,
            's' => $query,
            'meta_query' => [
                [
                    'key' => '_visibility',
                    'value' => ['visible', 'catalog'],
                    'compare' => 'IN'
                ]
            ]
        ];
        
        $products = get_posts($args);
        $results = [];
        
        foreach ($products as $product) {
            $wc_product = wc_get_product($product->ID);
            if (!$wc_product) continue;
            
            $results[] = [
                'id' => $product->ID,
                'name' => $product->post_title,
                'price' => $wc_product->get_price_html(),
                'image' => get_the_post_thumbnail_url($product->ID, 'thumbnail') ?: wc_placeholder_img_src('thumbnail'),
                'url' => get_permalink($product->ID)
            ];
        }
        
        wp_send_json_success(['products' => $results]);
    }

    /**
     * AJAX: Get footer data
     */
    public function ajax_get_footer_data() {
        check_ajax_referer('shopxpert_comparison_nonce', 'nonce');
        $list = \Shopxpert\ProductComparison\Manage_Comparison::instance()->get_comparison_list();
        $products = [];
        
        foreach ($list as $product_id) {
            $product = wc_get_product($product_id);
            if (!$product) continue;
            
            $products[] = [
                'id' => $product_id,
                'name' => $product->get_name(),
                'image' => get_the_post_thumbnail_url($product_id, 'thumbnail') ?: wc_placeholder_img_src('thumbnail'),
                'price' => $product->get_price_html()
            ];
        }
        
        wp_send_json_success(['products' => $products]);
    }
 

    /**
     * Register Product Comparison widget
     */
    public function register_widget() {
        require_once __DIR__ . '/Widget.php';
        \register_widget( '\Shopxpert\ProductComparison\Widget' );
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
