<?php
/*
* Shop Page
*/
 
use function  Shopxpert\incs\shopxpert_get_option_label_text;

// Add to Cart Button Label
if ( ! function_exists('shopxpert_custom_add_cart_button_shop_page') ) {
    add_filter( 'woocommerce_product_add_to_cart_text', 'shopxpert_custom_add_cart_button_shop_page', 99, 2 );
    function shopxpert_custom_add_cart_button_shop_page( $label ) {
        // Get the label text
        $label_text = shopxpert_get_option_label_text( 'wl_shop_add_to_cart_txt', 'shopxpert_rename_label_tabs', 'Add to Cart' );
        return esc_html( $label_text ); // Ensure output is escaped
    }
}

// Product Details Page - Add to Cart Button Label
if ( ! function_exists( 'shopxpert_custom_add_cart_button_single_product' ) ) {
    add_filter( 'woocommerce_product_single_add_to_cart_text', 'shopxpert_custom_add_cart_button_single_product' );
    function shopxpert_custom_add_cart_button_single_product( $label ) {
        // Get the custom label text from the option
        $custom_label = shopxpert_get_option_label_text( 'wl_shop_add_to_cart_txt', 'shopxpert_rename_label_tabs', 'Add to Cart' );
        return esc_html( $custom_label ); // Ensure output is escaped
    }
}

// Description tab
if ( ! function_exists('shopxpert_rename_description_product_tab_label') ) {
    add_filter( 'woocommerce_product_description_tab_title', 'shopxpert_rename_description_product_tab_label' );
    function shopxpert_rename_description_product_tab_label() {
        // Retrieve the label
        $label_text = shopxpert_get_option_label_text( 'wl_description_tab_menu_title', 'shopxpert_rename_label_tabs', 'Description' );
        return esc_html( $label_text ); // Ensure output is escaped
    }
}

// Description heading
if ( ! function_exists('shopxpert_rename_description_tab_heading') ) {
    add_filter( 'woocommerce_product_description_heading', 'shopxpert_rename_description_tab_heading' );
    function shopxpert_rename_description_tab_heading() {
        // Retrieve the heading
        $heading_text = shopxpert_get_option_label_text( 'wl_description_tab_menu_title', 'shopxpert_rename_label_tabs', 'Description' );
        return esc_html( $heading_text ); // Ensure output is escaped
    }
}

// Additional Info tab
if ( ! function_exists('shopxpert_rename_additional_information_product_tab_label') ) {
    add_filter( 'woocommerce_product_additional_information_tab_title', 'shopxpert_rename_additional_information_product_tab_label' );
    function shopxpert_rename_additional_information_product_tab_label() {
        // Retrieve the label
        $label_text = shopxpert_get_option_label_text( 'wl_additional_information_tab_menu_title', 'shopxpert_rename_label_tabs','Additional Information' );
        return esc_html( $label_text ); // Ensure output is escaped
    }
}

// Additional Information heading
if ( ! function_exists('shopxpert_rename_additional_information_tab_heading') ) {
    add_filter( 'woocommerce_product_additional_information_heading', 'shopxpert_rename_additional_information_tab_heading' );
    function shopxpert_rename_additional_information_tab_heading() {
        // Retrieve the heading
        $heading_text = shopxpert_get_option_label_text( 'wl_additional_information_tab_menu_title', 'shopxpert_rename_label_tabs','Additional Information' );
        return esc_html( $heading_text ); // Ensure output is escaped
    }
}

// Reviews tab
if ( ! function_exists('shopxpert_rename_reviews_product_tab_label') ) {
    add_filter( 'woocommerce_product_reviews_tab_title', 'shopxpert_rename_reviews_product_tab_label' );
    function shopxpert_rename_reviews_product_tab_label() {
        // Retrieve the label
        $label_text = shopxpert_get_option_label_text( 'wl_reviews_tab_menu_title', 'shopxpert_rename_label_tabs','Reviews' );
        return esc_html( $label_text ); // Ensure output is escaped
    }
}

// Checkout Page - Place Order Button Text
if ( ! function_exists('shopxpert_rename_place_order_button') ) {
    add_filter( 'woocommerce_order_button_text', 'shopxpert_rename_place_order_button' );
    function shopxpert_rename_place_order_button() {
        // Retrieve the button text
        $button_text = shopxpert_get_option_label_text( 'wl_checkout_placeorder_btn_txt', 'shopxpert_rename_label_tabs','Place order' );
        return esc_html( $button_text ); // Ensure output is escaped
    }
}
