<?php

namespace ShopXpert\Features\ProductComparison;

use function ShopXpert\shopxpert_get_option;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Product Display class for showing compare buttons on frontend
 */
class Product_Display {
    /**
     * [$_instance]
     * @var null
     */
    private static $_instance = null;

    /**
     * [instance] Initializes a singleton instance
     * @return [Product_Display]
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
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Wait for WordPress to be fully loaded
        \add_action('init', [ $this, 'setup_hooks' ]);
    }

    /**
     * Setup hooks after WordPress is loaded
     */
    public function setup_hooks() {
        // Check if comparison is enabled
        if (!function_exists('\\Shopxpert\\incs\\shopxpert_get_option')) {
            return;
        }

        if (!\Shopxpert\incs\shopxpert_get_option('enable', 'product_comparison_settings_tabs', 'on')) {
            return;
        }

        // Shop page hooks
        if (\Shopxpert\incs\shopxpert_get_option('show_on_shop', 'product_comparison_settings_tabs', 'on')) {
            \add_action('woocommerce_after_shop_loop_item', [ $this, 'add_compare_button_shop' ], 15);
            \add_action('woocommerce_after_shop_loop_item_title', [ $this, 'add_compare_button_shop' ], 15);
        }

        // Single product page hooks
        if (\Shopxpert\incs\shopxpert_get_option('show_on_single', 'product_comparison_settings_tabs', 'on')) {
            $position = \Shopxpert\incs\shopxpert_get_option('button_position', 'product_comparison_settings_tabs', 'after_add_to_cart');
            
            switch ($position) {
                case 'before_add_to_cart':
                    \add_action('woocommerce_before_add_to_cart_button', [ $this, 'add_compare_button_single' ]);
                    break;
                case 'after_add_to_cart':
                    \add_action('woocommerce_after_add_to_cart_button', [ $this, 'add_compare_button_single' ]);
                    break;
                case 'before_title':
                    \add_action('woocommerce_single_product_summary', [ $this, 'add_compare_button_single' ], 4);
                    break;
                case 'after_title':
                    \add_action('woocommerce_single_product_summary', [ $this, 'add_compare_button_single' ], 6);
                    break;
            }
        }

        // Add custom CSS
        \add_action('wp_head', [ $this, 'add_custom_css' ]);
    }

    /**
     * Add compare button to shop page
     */
    public function add_compare_button_shop() {
        global $product;
        
        if (!$product) return;
        
        if (!function_exists('\\Shopxpert\\incs\\shopxpert_get_option')) {
            return;
        }
        
        $button_style = \Shopxpert\incs\shopxpert_get_option('button_style', 'product_comparison_settings_tabs', 'default');
        $show_counter = \Shopxpert\incs\shopxpert_get_option('show_counter', 'product_comparison_settings_tabs', 'on');
        
        echo '<div class="shopxpert-compare-button-wrapper">';
        echo do_shortcode('[product_comparison_button product_id="' . $product->get_id() . '" button_style="' . $button_style . '" show_counter="' . $show_counter . '"]');
        echo '</div>';
    }

    /**
     * Add compare button to single product page
     */
    public function add_compare_button_single() {
        global $product;
        
        if (!$product) return;
        
        if (!function_exists('\\Shopxpert\\incs\\shopxpert_get_option')) {
            return;
        }
        
        $button_style = \Shopxpert\incs\shopxpert_get_option('button_style', 'product_comparison_settings_tabs', 'default');
        $show_counter = \Shopxpert\incs\shopxpert_get_option('show_counter', 'product_comparison_settings_tabs', 'on');
        
        echo '<div class="shopxpert-compare-button-wrapper single-product">';
        echo do_shortcode('[product_comparison_button product_id="' . $product->get_id() . '" button_style="' . $button_style . '" show_counter="' . $show_counter . '"]');
        echo '</div>';
    }

    /**
     * Add custom CSS from settings
     */
    public function add_custom_css() {
        if (!function_exists('\\Shopxpert\\incs\\shopxpert_get_option')) {
            return;
        }
        
        $custom_css = \Shopxpert\incs\shopxpert_get_option('custom_css', 'product_comparison_settings_tabs', '');
        
        if (!empty($custom_css)) {
            echo '<style type="text/css">' . $custom_css . '</style>';
        }
    }

    /**
     * Get compare button HTML
     */
    public function get_compare_button_html($product_id, $args = []) {
        if (!function_exists('\\Shopxpert\\incs\\shopxpert_get_option')) {
            $defaults = [
                'button_style' => 'default',
                'show_counter' => 'on',
                'show_icon' => 'true',
            ];
        } else {
            $defaults = [
                'button_style' => \Shopxpert\incs\shopxpert_get_option('button_style', 'product_comparison_settings_tabs', 'default'),
                'show_counter' => \Shopxpert\incs\shopxpert_get_option('show_counter', 'product_comparison_settings_tabs', 'on'),
                'show_icon' => 'true',
            ];
        }
        
        $args = wp_parse_args($args, $defaults);
        
        return do_shortcode('[product_comparison_button product_id="' . $product_id . '" button_style="' . $args['button_style'] . '" show_counter="' . $args['show_counter'] . '" show_icon="' . $args['show_icon'] . '"]');
    }

    /**
     * Get comparison table HTML
     */
    public function get_comparison_table_html($args = []) {
        if (!function_exists('\\Shopxpert\\incs\\shopxpert_get_option')) {
            $defaults = [
                'show_fields' => 'image,title,rating,price,description,availability,sku,add_to_cart',
                'show_search' => 'on',
            ];
        } else {
            $defaults = [
                'show_fields' => implode(',', \Shopxpert\incs\shopxpert_get_option('table_fields', 'product_comparison_settings_tabs', ['image', 'title', 'rating', 'price', 'description', 'availability', 'sku', 'add_to_cart'])),
                'show_search' => \Shopxpert\incs\shopxpert_get_option('enable_search', 'product_comparison_settings_tabs', 'on'),
            ];
        }
        
        $args = wp_parse_args($args, $defaults);
        
        return do_shortcode('[product_comparison_table show_fields="' . $args['show_fields'] . '" show_search="' . $args['show_search'] . '"]');
    }
}
