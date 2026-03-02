<?php
/*
* ShopXpert Rename Label Feature
* Allows customization of WooCommerce labels (Add to Cart, Place Order, Tabs, etc.)
*/

use function Shopxpert\incs\shopxpert_get_option_label_text;
use function Shopxpert\incs\shopxpert_get_option;

// Helper function to check if rename label feature is enabled
if ( ! function_exists('shopxpert_is_rename_label_enabled') ) {
    function shopxpert_is_rename_label_enabled() {
        // Check new feature card key first, then fallback to legacy key
        $enabled = shopxpert_get_option( 'rename_label_settings', 'shopxpert_others_tabs', 'off' ) === 'on';
        if ( ! $enabled ) {
            $enabled = shopxpert_get_option( 'enablerenamelabel', 'shopxpert_others_tabs', 'off' ) === 'on';
        }
        return $enabled;
    }
}

// Add to Cart Button Label - Shop Page
if ( ! function_exists('shopxpert_custom_add_cart_button_shop_page') ) {
    add_filter( 'woocommerce_product_add_to_cart_text', 'shopxpert_custom_add_cart_button_shop_page', 99, 2 );
    function shopxpert_custom_add_cart_button_shop_page( $label, $product = null ) {
        // Only apply if feature is enabled
        if ( ! shopxpert_is_rename_label_enabled() ) {
            return $label;
        }
        
        $options = get_option( 'shopxpert_others_tabs' );
        
        if ( isset( $options['wl_shop_add_to_cart_txt'] ) && ! empty( trim( $options['wl_shop_add_to_cart_txt'] ) ) ) {
            return esc_html( $options['wl_shop_add_to_cart_txt'] );
        }
        
        return $label;
    }
}

// Product Details Page - Add to Cart Button Label
if ( ! function_exists( 'shopxpert_custom_add_cart_button_single_product' ) ) {
    add_filter( 'woocommerce_product_single_add_to_cart_text', 'shopxpert_custom_add_cart_button_single_product', 99, 2 );
    function shopxpert_custom_add_cart_button_single_product( $label, $product = null ) {
        // Only apply if feature is enabled
        if ( ! shopxpert_is_rename_label_enabled() ) {
            return $label;
        }
        
        $options = get_option( 'shopxpert_others_tabs' );
        
        if ( isset( $options['wl_add_to_cart_txt'] ) && ! empty( trim( $options['wl_add_to_cart_txt'] ) ) ) {
            return esc_html( $options['wl_add_to_cart_txt'] );
        }
        
        return $label;
    }
}

// Description tab
if ( ! function_exists('shopxpert_rename_description_product_tab_label') ) {
    add_filter( 'woocommerce_product_description_tab_title', 'shopxpert_rename_description_product_tab_label' );
    function shopxpert_rename_description_product_tab_label() {
        if ( ! shopxpert_is_rename_label_enabled() ) {
            return 'Description';
        }
        $options = get_option( 'shopxpert_others_tabs' );
        if ( isset( $options['wl_description_tab_menu_title'] ) && ! empty( trim( $options['wl_description_tab_menu_title'] ) ) ) {
            return esc_html( $options['wl_description_tab_menu_title'] );
        }
        return 'Description';
    }
} 

// Description heading
if ( ! function_exists('shopxpert_rename_description_tab_heading') ) {
    add_filter( 'woocommerce_product_description_heading', 'shopxpert_rename_description_tab_heading' );
    function shopxpert_rename_description_tab_heading() {
        $heading_text = shopxpert_get_option_label_text( 'wl_description_tab_menu_title', 'shopxpert_others_tabs', 'Description' );
        return esc_html( $heading_text );
    }
}

// Additional Info tab
if ( ! function_exists('shopxpert_rename_additional_information_product_tab_label') ) {
    add_filter( 'woocommerce_product_additional_information_tab_title', 'shopxpert_rename_additional_information_product_tab_label' );
    function shopxpert_rename_additional_information_product_tab_label() {
        if ( ! shopxpert_is_rename_label_enabled() ) {
            return 'Additional Information';
        }
        $options = get_option( 'shopxpert_others_tabs' );
        if ( isset( $options['wl_additional_information_tab_menu_title'] ) && ! empty( trim( $options['wl_additional_information_tab_menu_title'] ) ) ) {
            return esc_html( $options['wl_additional_information_tab_menu_title'] );
        }
        return 'Additional Information';
    }
} 

// Additional Information heading
if ( ! function_exists('shopxpert_rename_additional_information_tab_heading') ) {
    add_filter( 'woocommerce_product_additional_information_heading', 'shopxpert_rename_additional_information_tab_heading' );
    function shopxpert_rename_additional_information_tab_heading() {
        $heading_text = shopxpert_get_option_label_text( 'wl_additional_information_tab_menu_title', 'shopxpert_others_tabs', 'Additional Information' );
        return esc_html( $heading_text );
    }
}

// Reviews tab
if ( ! function_exists('shopxpert_rename_reviews_product_tab_label') ) {
    add_filter( 'woocommerce_product_reviews_tab_title', 'shopxpert_rename_reviews_product_tab_label' );
    function shopxpert_rename_reviews_product_tab_label() {
        if ( ! shopxpert_is_rename_label_enabled() ) {
            return 'Reviews';
        }
        $options = get_option( 'shopxpert_others_tabs' );
        if ( isset( $options['wl_reviews_tab_menu_title'] ) && ! empty( trim( $options['wl_reviews_tab_menu_title'] ) ) ) {
            return esc_html( $options['wl_reviews_tab_menu_title'] );
        }
        return 'Reviews';
    }
} 

// Checkout Page - Place Order Button Text
if ( ! function_exists('shopxpert_rename_place_order_button') ) {
    add_filter( 'woocommerce_order_button_text', 'shopxpert_rename_place_order_button', 99 );
    function shopxpert_rename_place_order_button() {
        if ( ! shopxpert_is_rename_label_enabled() ) {
            return 'Place order';
        }
        $options = get_option( 'shopxpert_others_tabs' );
        if ( isset( $options['wl_checkout_placeorder_btn_txt'] ) && ! empty( trim( $options['wl_checkout_placeorder_btn_txt'] ) ) ) {
            return esc_html( $options['wl_checkout_placeorder_btn_txt'] );
        }
        return 'Place order';
    }
}

// Fallback: if themes/plugins build the order button HTML directly
if ( ! function_exists( 'shopxpert_replace_order_button_html' ) ) {
    add_filter( 'woocommerce_order_button_html', 'shopxpert_replace_order_button_html', 99, 2 );
    function shopxpert_replace_order_button_html( $html, $order ) {
        if ( ! shopxpert_is_rename_label_enabled() ) {
            return $html;
        }
        $options = get_option( 'shopxpert_others_tabs' );
        if ( isset( $options['wl_checkout_placeorder_btn_txt'] ) && ! empty( trim( $options['wl_checkout_placeorder_btn_txt'] ) ) ) {
            $replacement = esc_html( $options['wl_checkout_placeorder_btn_txt'] );
            $new_html = preg_replace( '/>\s*Place order\s*</i', '> ' . $replacement . ' <', $html );
            if ( $new_html === null ) {
                $new_html = str_replace( 'Place order', $replacement, $html );
            }
            return $new_html;
        }
        return $html;
    }
}

// Ensure themes that filter or construct loop HTML directly are supported
if ( ! function_exists( 'shopxpert_replace_loop_add_to_cart_html' ) ) {
    add_filter( 'woocommerce_loop_add_to_cart_link', 'shopxpert_replace_loop_add_to_cart_html', 99, 2 );
    function shopxpert_replace_loop_add_to_cart_html( $html, $product ) {
        if ( ! shopxpert_is_rename_label_enabled() ) {
            return $html;
        }
        $options = get_option( 'shopxpert_others_tabs' );
        $replacement = '';
        
        if ( isset( $options['wl_shop_add_to_cart_txt'] ) && ! empty( trim( $options['wl_shop_add_to_cart_txt'] ) ) ) {
            $replacement = esc_html( $options['wl_shop_add_to_cart_txt'] );
        }
        
        if ( empty( $replacement ) ) {
            return $html;
        }
        
        $patterns = array( '/>\s*Add to cart\s*</i', '/>\s*Select options\s*</i' );
        $replacements = array( '> ' . $replacement . ' <', '> ' . $replacement . ' <' );
        $new_html = preg_replace( $patterns, $replacements, $html );
        
        if ( $new_html === null ) {
            $new_html = str_replace( array( 'Add to cart', 'Select options' ), $replacement, $html );
        }
        
        return $new_html;
    }
}
