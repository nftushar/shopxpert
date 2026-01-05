<?php

namespace Shopxpert\ProductComparison;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'shortcode_atts' ) ) {
    require_once( ABSPATH . 'wp-includes/shortcodes.php' );
}
if ( ! function_exists( 'get_the_ID' ) ) {
    require_once( ABSPATH . 'wp-includes/post-template.php' );
}
if ( ! function_exists( '__' ) ) {
    require_once( ABSPATH . 'wp-includes/l10n.php' );
}
if ( ! function_exists( 'esc_attr' ) ) {
    require_once( ABSPATH . 'wp-includes/formatting.php' );
}
if ( ! function_exists( 'esc_html' ) ) {
    require_once( ABSPATH . 'wp-includes/formatting.php' );
}

require_once dirname(__FILE__) . '/Manage_Comparison.php';

/**
 * Shortcode handler class for Product Comparison
 */
class Shortcode {
    /**
     * [$_instance]
     * @var null
     */
    private static $_instance = null;

    /**
     * [instance] Initializes a singleton instance
     * @return [Shortcode]
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Initializes the class
     */
    public function __construct() {
        \add_shortcode( 'product_comparison_button', [ $this, 'button_shortcode' ] );
        \add_shortcode( 'product_comparison_table', [ $this, 'table_shortcode' ] );
    }

    /**
     * [button_shortcode] Button Shortcode callable function
     */
    public function button_shortcode( $atts, $content = '' ) {
       $enabled = \Shopxpert\incs\shopxpert_get_option(
            'enable',
            'shopxpert_product_comparison_settings',
            'off'
        );

        if ( $enabled !== 'on' ) {
            return '';
        }


        $atts = \shortcode_atts([
            'product_id' => \get_the_ID(),
            'show_icon' => 'true',
            'show_counter' => 'true',
            'button_style' => 'default', // default, icon-only, text-only
        ], $atts);
        
        $product_id = intval($atts['product_id']);
        $list = \Shopxpert\ProductComparison\Manage_Comparison::instance()->get_comparison_list();
        $in_list = in_array($product_id, $list);
        $button_text = $in_list
            ? \Shopxpert\incs\shopxpert_get_option('remove_button_text', 'shopxpert_product_comparison_settings', __( 'Remove from Compare', 'shopxpert' ))
            : \Shopxpert\incs\shopxpert_get_option('button_text', 'shopxpert_product_comparison_settings', __( 's Add to Compare', 'shopxpert' ));
        $button_class = $in_list ? 'remove-from-compare' : 'add-to-compare';
        $max_products = intval(\Shopxpert\incs\shopxpert_get_option('max_products', 'shopxpert_product_comparison_settings', 4));
        
        if (!$in_list && count($list) >= $max_products) {
            return '<button class="shopxpert-compare-btn add-to-compare" disabled>' . esc_html__('Max products reached', 'shopxpert') . '</button>';
        }
        
        // Build button content
        $button_content = '';
        
        if ($atts['show_icon'] === 'true' && $atts['button_style'] !== 'text-only') {
            $button_content .= '<svg class="compare-icon" viewBox="0 0 24 24" width="16" height="16"><path d="M9 3H5c-1.1 0-2 .9-2 2v4c0 1.1.9 2 2 2h4c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM5 9V5h4v4H5zm10-6h4c1.1 0 2 .9 2 2v4c0 1.1-.9 2-2 2h-4c-1.1 0-2-.9-2-2V5c0-1.1.9-2 2-2zm4 6V5h-4v4h4zm-9 4H5c-1.1 0-2 .9-2 2v4c0 1.1.9 2 2 2h4c1.1 0 2-.9 2-2v-4c0-1.1-.9-2-2-2zm0 6H5v-4h4v4zm10-6h-4c-1.1 0-2 .9-2 2v4c0 1.1.9 2 2 2h4c1.1 0 2-.9 2-2v-4c0-1.1-.9-2-2-2zm0 6h-4v-4h4v4z"/></svg>';
        }
        
        if ($atts['button_style'] !== 'icon-only') {
            $button_content .= '<span class="button-text">' . \esc_html($button_text) . '</span>';
        }
        
        if ($atts['show_counter'] === 'true' && count($list) > 0) {
            $button_content .= '<span class="shopxpert-compare-counter">' . count($list) . '</span>';
        }
        
        return '<button class="shopxpert-compare-btn ' . \esc_attr($button_class) . ' ' . \esc_attr($atts['button_style']) . '" data-product-id="' . \esc_attr($product_id) . '">' . $button_content . '</button>';
    }

    /**
     * [table_shortcode] Table Shortcode callable function
     */
    public function table_shortcode( $atts, $content = '' ) {
        $atts = \shortcode_atts([
            'show_fields' => 'image,title,rating,price,description,availability,sku,add_to_cart',
            'display_mode' => 'table', // table, popup
            'show_search' => 'true',
        ], $atts);
        
        $list = \Shopxpert\ProductComparison\Manage_Comparison::instance()->get_comparison_list();
        $table_title = \Shopxpert\incs\shopxpert_get_option('table_title', 'shopxpert_product_comparison_settings', __( 'Product Comparison', 'shopxpert' ));
        $show_fields = explode(',', $atts['show_fields']);
        
        if ( empty( $list ) ) {
            return '<div class="shopxpert-compare-table-empty">' . \__( 'No products in comparison.', 'shopxpert' ) . '</div>';
        }
        
        ob_start();
        echo '<div class="shopxpert-compare-table-container">';
        echo '<div class="shopxpert-compare-table-title">' . \esc_html($table_title) . '</div>';
        echo '<div class="shopxpert-compare-feedback" style="color:green;display:none;"></div>';
        
        // Search functionality
        if ($atts['show_search'] === 'true') {
            echo '<div class="shopxpert-compare-search-container" style="margin: 15px 0;">';
            echo '<input type="text" class="shopxpert-compare-search" placeholder="' . \__('Search products to add...', 'shopxpert') . '">';
            echo '<div class="shopxpert-compare-search-results"></div>';
            echo '</div>';
        }
        
        echo '<div style="margin-bottom: 15px;">';
        echo '<button class="shopxpert-compare-btn remove-all-compare">' . \__( 'Remove All', 'shopxpert' ) . '</button>';
        echo '<span class="shopxpert-compare-counter" style="margin-left: 10px; background: #007cba; color: white; padding: 5px 10px; border-radius: 15px;">' . count($list) . ' ' . \__('products', 'shopxpert') . '</span>';
        echo '</div>';
        
        echo '<div class="shopxpert-compare-table-wrapper" style="overflow-x: auto;">';
        echo '<table class="shopxpert-compare-table"><thead><tr>';
        
        // Build table headers based on selected fields
        foreach ($show_fields as $field) {
            $field = trim($field);
            switch ($field) {
                case 'image':
                    echo '<th>' . \__( 'Image', 'shopxpert' ) . '</th>';
                    break;
                case 'title':
                    echo '<th>' . \__( 'Product', 'shopxpert' ) . '</th>';
                    break;
                case 'rating':
                    echo '<th>' . \__( 'Rating', 'shopxpert' ) . '</th>';
                    break;
                case 'price':
                    echo '<th>' . \__( 'Price', 'shopxpert' ) . '</th>';
                    break;
                case 'description':
                    echo '<th>' . \__( 'Description', 'shopxpert' ) . '</th>';
                    break;
                case 'availability':
                    echo '<th>' . \__( 'Availability', 'shopxpert' ) . '</th>';
                    break;
                case 'sku':
                    echo '<th>' . \__( 'SKU', 'shopxpert' ) . '</th>';
                    break;
                case 'add_to_cart':
                    echo '<th>' . \__( 'Add to Cart', 'shopxpert' ) . '</th>';
                    break;
            }
        }
        echo '<th>' . \__( 'Action', 'shopxpert' ) . '</th>';
        echo '</tr></thead><tbody>';
        
        foreach ( $list as $product_id ) {
            $product = \wc_get_product( $product_id );
            if ( ! $product ) continue;
            
            echo '<tr>';
            
            // Build table cells based on selected fields
            foreach ($show_fields as $field) {
                $field = trim($field);
                echo '<td>';
                
                switch ($field) {
                    case 'image':
                        echo '<img src="' . \esc_url(get_the_post_thumbnail_url($product_id, 'medium')) . '" alt="' . \esc_attr($product->get_name()) . '" class="product-image">';
                        break;
                    case 'title':
                        echo '<a href="' . \esc_url( get_permalink( $product_id ) ) . '" class="product-title">' . \esc_html( $product->get_name() ) . '</a>';
                        break;
                    case 'rating':
                        $rating = $product->get_average_rating();
                        if ($rating > 0) {
                            echo '<div class="shopxpert-compare-rating">';
                            for ($i = 1; $i <= 5; $i++) {
                                $class = $i <= $rating ? 'star filled' : 'star';
                                echo '<span class="' . $class . '">★</span>';
                            }
                            echo '<span class="rating-text">(' . $rating . ')</span>';
                            echo '</div>';
                        } else {
                            echo '<span class="no-rating">' . \__('No rating', 'shopxpert') . '</span>';
                        }
                        break;
                    case 'price':
                        echo '<div class="shopxpert-compare-price">' . $product->get_price_html() . '</div>';
                        break;
                    case 'description':
                        $description = $product->get_short_description() ?: $product->get_description();
                        echo '<div class="shopxpert-compare-description">' . \wp_trim_words($description, 20) . '</div>';
                        break;
                    case 'availability':
                        $availability = $product->get_availability();
                        $class = 'in-stock';
                        if ($availability['class'] === 'out-of-stock') $class = 'out-of-stock';
                        if ($availability['class'] === 'available-on-backorder') $class = 'on-backorder';
                        echo '<span class="shopxpert-compare-availability ' . $class . '">' . \esc_html($availability['availability']) . '</span>';
                        break;
                    case 'sku':
                        $sku = $product->get_sku();
                        if ($sku) {
                            echo '<span class="shopxpert-compare-sku">' . \esc_html($sku) . '</span>';
                        } else {
                            echo '<span class="no-sku">' . \__('N/A', 'shopxpert') . '</span>';
                        }
                        break;
                    case 'add_to_cart':
                        if ($product->is_purchasable() && $product->is_in_stock()) {
                            echo '<button class="shopxpert-compare-add-to-cart" data-product-id="' . \esc_attr($product_id) . '">' . \__('Add to Cart', 'shopxpert') . '</button>';
                        } else {
                            echo '<button class="shopxpert-compare-add-to-cart" disabled>' . \__('Not Available', 'shopxpert') . '</button>';
                        }
                        break;  
                }
                echo '</td>';
            }
            
            // Action column
            $remove_text = \Shopxpert\incs\shopxpert_get_option('remove_button_text', 'shopxpert_product_comparison_settings', __( 'Remove', 'shopxpert' ));
            echo '<td><button class="shopxpert-compare-btn remove-from-compare" data-product-id="' . \esc_attr( $product_id ) . '">' . \esc_html($remove_text) . '</button></td>';
            echo '</tr>';
        }
        
        echo '</tbody></table>';
        echo '</div>'; // End table wrapper
        echo '</div>'; // End container
        return ob_get_clean();
    }
} 