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
        error_log('xxxxbutton_shortcode');
        $atts = \shortcode_atts([
            'product_id' => \get_the_ID(),
        ], $atts);
        $product_id = intval($atts['product_id']);
        $list = \Shopxpert\ProductComparison\Manage_Comparison::instance()->get_comparison_list();
        $in_list = in_array($product_id, $list);
        $button_text = $in_list
            ? \Shopxpert\incs\shopxpert_get_option('remove_button_text', 'product_comparison_settings_tabs', __( 'Remove from Compare', 'shopxpert' ))
            : \Shopxpert\incs\shopxpert_get_option('button_text', 'product_comparison_settings_tabs', __( 'Add to Compare', 'shopxpert' ));
        $button_class = $in_list ? 'remove-from-compare' : 'add-to-compare';
        $max_products = intval(\Shopxpert\incs\shopxpert_get_option('max_products', 'product_comparison_settings_tabs', 4));
        if (!$in_list && count($list) >= $max_products) {
            return '<button class="shopxpert-compare-btn add-to-compare" disabled>' . esc_html__('Max products reached', 'shopxpert') . '</button>';
        }
        return '<button class="shopxpert-compare-btn ' . \esc_attr($button_class) . '" data-product-id="' . \esc_attr($product_id) . '">' . \esc_html($button_text) . '</button>';
    }

    /**
     * [table_shortcode] Table Shortcode callable function
     */
    public function table_shortcode( $atts, $content = '' ) {
        $list = \Shopxpert\ProductComparison\Manage_Comparison::instance()->get_comparison_list();
        $table_title = \Shopxpert\incs\shopxpert_get_option('table_title', 'product_comparison_settings_tabs', __( 'Product Comparison', 'shopxpert' ));
        if ( empty( $list ) ) {
            return '<div class="shopxpert-compare-table-empty">' . \__( 'No products in comparison.', 'shopxpert' ) . '</div>';
        }
        ob_start();
        echo '<div class="shopxpert-compare-table-title">' . \esc_html($table_title) . '</div>';
        echo '<div class="shopxpert-compare-feedback" style="color:green;display:none;"></div>';
        echo '<button class="shopxpert-compare-btn remove-all-compare" style="margin-bottom:10px;">' . \__( 'Remove All', 'shopxpert' ) . '</button>';
        echo '<table class="shopxpert-compare-table"><thead><tr>';
        echo '<th>' . \__( 'Image', 'shopxpert' ) . '</th>';
        echo '<th>' . \__( 'Product', 'shopxpert' ) . '</th>';
        echo '<th>' . \__( 'Price', 'shopxpert' ) . '</th>';
        echo '<th>' . \__( 'Action', 'shopxpert' ) . '</th>';
        echo '</tr></thead><tbody>';
        foreach ( $list as $product_id ) {
            $product = \wc_get_product( $product_id );
            if ( ! $product ) continue;
            $remove_text = \Shopxpert\incs\shopxpert_get_option('remove_button_text', 'product_comparison_settings_tabs', __( 'Remove', 'shopxpert' ));
            echo '<tr>';
            echo '<td>' . $product->get_image( 'thumbnail' ) . '</td>';
            echo '<td><a href="' . \esc_url( get_permalink( $product_id ) ) . '">' . \esc_html( $product->get_name() ) . '</a></td>';
            echo '<td>' . $product->get_price_html() . '</td>';
            echo '<td><button class="shopxpert-compare-btn remove-from-compare" data-product-id="' . \esc_attr( $product_id ) . '">' . \esc_html($remove_text) . '</button></td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
        return ob_get_clean();
    }
} 