<?php
namespace ShopXpert\Features\Wishlist\Frontend;

use function ShopXpert\shopxpert_get_option;

/**
 * Shortcode handler class
 */
class Shortcode {

    /**
     * Check if wishlist feature is enabled
     */
    private function is_wishlist_enabled() {
        return WishList_get_option(
            'wishlist',
            'shopxpert_others_tabs',
            'off'
        ) === 'on';
    }

    /**
     * Singleton instance
     */
    private static $_instance = null;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Register shortcodes
     */
    public function __construct() {
        add_shortcode( 'wishlist_button', [ $this, 'button_shortcode' ] );
        add_shortcode( 'wishlist_table', [ $this, 'table_shortcode' ] );
        add_shortcode( 'wishlist_counter', [ $this, 'counter_shortcode' ] );
    }

    /**
     * Wishlist Button Shortcode
     */
    public function button_shortcode( $atts, $content = '' ) {

        if ( ! $this->is_wishlist_enabled() ) {
            return '';
        }

        wp_enqueue_style( 'wishlist-frontend' );
        wp_enqueue_script( 'wishlist-frontend' );

        global $product;

        $product_id = '';
        if ( $product && is_a( $product, 'WC_Product' ) ) {
            $product_id = $product->get_id();
        } elseif ( get_post_type( get_the_ID() ) === 'product' ) {
            $product_id = get_the_ID();
        }

        $has_product = Manage_Wishlist::instance()->is_product_in_wishlist( $product_id );

        $myaccount_url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );

        $button_text       = WishList_get_option( 'button_text', 'wishlist_settings_tabs', 'Wishlist' );
        $button_added_text = WishList_get_option( 'added_button_text', 'wishlist_settings_tabs', 'Product Added' );
        $button_exist_text = WishList_get_option( 'exist_button_text', 'wishlist_settings_tabs', 'Product already added' );

        $shop_position    = WishList_get_option( 'shop_btn_position', 'wishlist_settings_tabs', 'after_cart_btn' );
        $product_position = WishList_get_option( 'product_btn_position', 'wishlist_settings_tabs', 'after_cart_btn' );
        $button_style     = WishList_get_option( 'button_style', 'wishlist_style_settings_tabs', 'default' );
        $login_limit      = WishList_get_option( 'enable_login_limit', 'wishlist_general_tabs', 'off' );

        if ( ! is_user_logged_in() && $login_limit === 'on' ) {
            $button_text = WishList_get_option( 'logout_button', 'wishlist_general_tabs', 'Please login' );
            $page_url    = $myaccount_url;
            $has_product = false;
        } else {
            $page_url = WishList_get_page_url();
        }

        $button_class = [
            'wishlist-btn',
            'wishlist-button',
            'wishlist-shop-' . $shop_position,
            'wishlist-product-' . $product_position,
        ];

        if ( $button_style === 'themestyle' ) {
            $button_class[] = 'button';
        }

        if ( $has_product ) {
            $button_class = array_diff( $button_class, [ 'wishlist-btn' ] );
        }

        $button_icon       = $this->icon_generate();
        $added_button_icon = $this->icon_generate( 'added' );

        $button_text_html = ! empty( $button_text )
            ? '<span class="wishlist-btn-text">' . esc_html( $button_text ) . '</span>'
            : '';

        $button_added_html = ! empty( $button_added_text )
            ? '<span class="wishlist-btn-text">' . esc_html( $button_added_text ) . '</span>'
            : '';

        $button_exist_html = ! empty( $button_exist_text )
            ? '<span class="wishlist-btn-text">' . esc_html( $button_exist_text ) . '</span>'
            : '';

        $default_atts = [
            'product_id'        => $product_id,
            'button_url'        => $page_url,
            'button_class'      => implode( ' ', $button_class ),
            'button_text'       => $button_text_html ? $button_icon . $button_text_html : '',
            'button_added_text' => $button_added_html ? $added_button_icon . $button_added_html : '',
            'button_exist_text' => $button_exist_html ? $added_button_icon . $button_exist_html : '',
            'has_product'       => $has_product,
            'template_name'     => $has_product ? 'exist' : 'add',
        ];

        $atts = shortcode_atts( $default_atts, $atts, $content );

        return Manage_Wishlist::instance()->button_html( $atts );
    }

    /**
     * Wishlist Table Shortcode
     */
    public function table_shortcode( $atts, $content = '' ) {

        if ( ! $this->is_wishlist_enabled() ) {
            return '';
        }

        wp_enqueue_style( 'wishlist-frontend' );
        wp_enqueue_script( 'wishlist-frontend' );

        $empty_text = WishList_get_option( 'empty_table_text', 'wishlist_table_settings_tabs' );

        $current_page     = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
        $products_perpage = (int) WishList_get_option( 'wishlist_product_per_page', 'wishlist_table_settings_tabs', 20 );

        $products = Manage_Wishlist::instance()->get_products_data( $products_perpage, $current_page );
        $fields   = Manage_Wishlist::instance()->get_all_fields();

        $heading = WishList_get_option( 'table_heading', 'wishlist_table_settings_tabs', [] );
        $login_limit = WishList_get_option( 'enable_login_limit', 'wishlist_general_tabs', 'off' );

        if ( ! is_user_logged_in() && $login_limit === 'on' ) {
            return do_shortcode( '[woocommerce_my_account]' );
        }

        $default_atts = [
            'shopxpert'   => Manage_Wishlist::instance(),
            'products'    => $products,
            'fields'      => $fields,
            'heading_txt' => $heading,
            'empty_text'  => $empty_text ?: '',
        ];

        $atts = shortcode_atts( $default_atts, $atts, $content );

        return Manage_Wishlist::instance()->table_html( $atts );
    }

    /**
     * Wishlist Counter Shortcode
     */
    public function counter_shortcode( $atts, $content = '' ) {

        if ( ! $this->is_wishlist_enabled() ) {
            return '';
        }

        wp_enqueue_style( 'wishlist-frontend' );

        $products = Manage_Wishlist::instance()->get_products_data();
        $page_url = WishList_get_page_url();

        $default_atts = [
            'products'   => $products,
            'item_count' => count( $products ),
            'page_url'   => $page_url,
            'text'       => '',
        ];

        $atts = shortcode_atts( $default_atts, $atts, $content );

        return Manage_Wishlist::instance()->count_html( $atts );
    }

    /**
     * Generate button icon
     */
    public function icon_generate( $type = '' ) {

        if ( ! $this->is_wishlist_enabled() ) {
            return '';
        }

        $icon_type = WishList_get_option(
            $type . 'button_icon_type',
            'wishlist_style_settings_tabs',
            'default'
        );

        if ( $icon_type === 'none' ) {
            return '';
        }

        if ( $icon_type === 'custom' ) {
            $custom_icon = WishList_get_option(
                $type . 'button_custom_icon',
                'wishlist_style_settings_tabs',
                ''
            );

            if ( $custom_icon ) {
                return '<img src="' . esc_url( $custom_icon ) . '" alt="">';
            }
        }

        return WishList_icon_list( 'default' )
            . '<span class="wishlist-loader">' . WishList_icon_list( 'loading' ) . '</span>';
    }
}
