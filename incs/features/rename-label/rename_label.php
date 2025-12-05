<?php
/*
* Shop Page
*/
use function  Shopxpert\incs\shopxpert_get_option_label_text;
use function  Shopxpert\incs\shopxpert_get_option;

// Log that the file is loaded
if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
    // Only log if WP_DEBUG is enabled to avoid cluttering logs
} else {
    error_log( '[ShopXpert Rename Label] File loaded successfully at ' . current_time( 'mysql' ) );
}

// Helper function to check if rename label feature is enabled
if ( ! function_exists('shopxpert_is_rename_label_enabled') ) {
    function shopxpert_is_rename_label_enabled() {
        $enabled = shopxpert_get_option( 'enablerenamelabel', 'shopxpert_others_tabs', 'off' ) === 'on';
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            error_log( '[ShopXpert Rename Label] Feature enabled check: ' . ( $enabled ? 'YES' : 'NO' ) );
        }
        return $enabled;
    }
}

// Add to Cart Button Label - Shop Page
if ( ! function_exists('shopxpert_custom_add_cart_button_shop_page') ) {
    add_filter( 'woocommerce_product_add_to_cart_text', 'shopxpert_custom_add_cart_button_shop_page', 99, 2 );
    function shopxpert_custom_add_cart_button_shop_page( $label, $product = null ) {
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            error_log( '[ShopXpert Rename Label] Shop page filter called. Original label: ' . $label );
        }
        
        // Only apply if feature is enabled
        if ( ! shopxpert_is_rename_label_enabled() ) {
            if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                error_log( '[ShopXpert Rename Label] Feature not enabled, returning original label' );
            }
            return $label;
        }
        
        // Get the option value directly to check if it's set
        $options = get_option( 'shopxpert_others_tabs' );
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            error_log( '[ShopXpert Rename Label] Shop page options retrieved: ' . print_r( $options, true ) );
        }
        
        if ( isset( $options['wl_shop_add_to_cart_txt'] ) && ! empty( trim( $options['wl_shop_add_to_cart_txt'] ) ) ) {
            $custom_label = esc_html( $options['wl_shop_add_to_cart_txt'] );
            if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                error_log( '[ShopXpert Rename Label] Shop page custom label found: ' . $custom_label );
            }
            return $custom_label;
        }
        
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            error_log( '[ShopXpert Rename Label] Shop page custom label not set. Option exists: ' . ( isset( $options['wl_shop_add_to_cart_txt'] ) ? 'YES' : 'NO' ) );
            if ( isset( $options['wl_shop_add_to_cart_txt'] ) ) {
                error_log( '[ShopXpert Rename Label] Shop page option value: "' . $options['wl_shop_add_to_cart_txt'] . '" (empty check: ' . ( empty( trim( $options['wl_shop_add_to_cart_txt'] ) ) ? 'YES' : 'NO' ) . ')' );
            }
            error_log( '[ShopXpert Rename Label] Returning original label: ' . $label );
        }
        return $label; // Return original label if custom label not set
    }
}

// Product Details Page - Add to Cart Button Label
if ( ! function_exists( 'shopxpert_custom_add_cart_button_single_product' ) ) {
    add_filter( 'woocommerce_product_single_add_to_cart_text', 'shopxpert_custom_add_cart_button_single_product', 99, 2 );
    function shopxpert_custom_add_cart_button_single_product( $label, $product = null ) {
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            error_log( '[ShopXpert Rename Label] Single product filter called. Original label: ' . $label );
            error_log( '[ShopXpert Rename Label] Product ID: ' . ( $product ? $product->get_id() : 'null' ) );
        }
        
        // Only apply if feature is enabled
        if ( ! shopxpert_is_rename_label_enabled() ) {
            if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                error_log( '[ShopXpert Rename Label] Feature not enabled, returning original label' );
            }
            return $label;
        }
        
        // Get the option value directly to check if it's set
        $options = get_option( 'shopxpert_others_tabs' );
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            error_log( '[ShopXpert Rename Label] Single product options retrieved: ' . print_r( $options, true ) );
        }
        
        if ( isset( $options['wl_add_to_cart_txt'] ) && ! empty( trim( $options['wl_add_to_cart_txt'] ) ) ) {
            $custom_label = esc_html( $options['wl_add_to_cart_txt'] );
            if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                error_log( '[ShopXpert Rename Label] Single product custom label found: ' . $custom_label );
            }
            return $custom_label;
        }
        
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            error_log( '[ShopXpert Rename Label] Single product custom label not set. Option exists: ' . ( isset( $options['wl_add_to_cart_txt'] ) ? 'YES' : 'NO' ) );
            if ( isset( $options['wl_add_to_cart_txt'] ) ) {
                error_log( '[ShopXpert Rename Label] Single product option value: "' . $options['wl_add_to_cart_txt'] . '" (empty check: ' . ( empty( trim( $options['wl_add_to_cart_txt'] ) ) ? 'YES' : 'NO' ) . ')' );
            }
            error_log( '[ShopXpert Rename Label] Returning original label: ' . $label );
        }
        return $label; // Return original label if custom label not set
    }
}

// Description tab
if ( ! function_exists('shopxpert_rename_description_product_tab_label') ) {
    add_filter( 'woocommerce_product_description_tab_title', 'shopxpert_rename_description_product_tab_label' );
    function shopxpert_rename_description_product_tab_label() {
        // Only apply if feature is enabled
        if ( ! shopxpert_is_rename_label_enabled() ) {
            return 'Description';
        }
        // Retrieve the label
        $options = get_option( 'shopxpert_rename_label_tabs' );
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
        // Retrieve the heading
        $heading_text = shopxpert_get_option_label_text( 'wl_description_tab_menu_title', 'shopxpert_others_tabs', 'Description' );
        return esc_html( $heading_text ); // Ensure output is escaped
    }
}

// Additional Info tab
if ( ! function_exists('shopxpert_rename_additional_information_product_tab_label') ) {
    add_filter( 'woocommerce_product_additional_information_tab_title', 'shopxpert_rename_additional_information_product_tab_label' );
    function shopxpert_rename_additional_information_product_tab_label() {
        // Only apply if feature is enabled
        if ( ! shopxpert_is_rename_label_enabled() ) {
            return 'Additional Information';
        }
        // Retrieve the label
        $options = get_option( 'shopxpert_rename_label_tabs' );
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
        // Retrieve the heading
        $heading_text = shopxpert_get_option_label_text( 'wl_additional_information_tab_menu_title', 'shopxpert_others_tabs','Additional Information' );
        return esc_html( $heading_text ); // Ensure output is escaped
    }
}

// Reviews tab
if ( ! function_exists('shopxpert_rename_reviews_product_tab_label') ) {
    add_filter( 'woocommerce_product_reviews_tab_title', 'shopxpert_rename_reviews_product_tab_label' );
    function shopxpert_rename_reviews_product_tab_label() {
        // Only apply if feature is enabled
        if ( ! shopxpert_is_rename_label_enabled() ) {
            return 'Reviews';
        }
        // Retrieve the label
        $options = get_option( 'shopxpert_rename_label_tabs' );
        if ( isset( $options['wl_reviews_tab_menu_title'] ) && ! empty( trim( $options['wl_reviews_tab_menu_title'] ) ) ) {
            return esc_html( $options['wl_reviews_tab_menu_title'] );
        }
        return 'Reviews';
    }
}

// Checkout Page - Place Order Button Text
if ( ! function_exists('shopxpert_rename_place_order_button') ) {
    add_filter( 'woocommerce_order_button_text', 'shopxpert_rename_place_order_button' );
    function shopxpert_rename_place_order_button() {
        // Only apply if feature is enabled
        if ( ! shopxpert_is_rename_label_enabled() ) {
            return 'Place order';
        }
        // Get the option value directly to check if it's set
        $options = get_option( 'shopxpert_others_tabs' );
        if ( isset( $options['wl_checkout_placeorder_btn_txt'] ) && ! empty( trim( $options['wl_checkout_placeorder_btn_txt'] ) ) ) {
            return esc_html( $options['wl_checkout_placeorder_btn_txt'] );
        }
        return 'Place order'; // Return default if custom label not set
    }
}
