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
        // Prefer the feature card key and fallback to legacy key for compatibility
        $enabled = shopxpert_get_option( 'rename_label_settings', 'shopxpert_others_tabs', 'off' ) === 'on';
        if ( ! $enabled ) {
            $enabled = shopxpert_get_option( 'enablerenamelabel', 'shopxpert_others_tabs', 'off' ) === 'on';
        }
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
        // TEMP DEBUG: always log when this filter runs to help investigate missing change-label behavior
        error_log( '[TEMP DEBUG Rename Label] Shop page filter called. Original label: ' . $label );
        error_log( '[TEMP DEBUG Rename Label] - Feature enabled: ' . ( shopxpert_is_rename_label_enabled() ? 'YES' : 'NO' ) );
        $opts = get_option( 'shopxpert_others_tabs' );
        error_log( '[TEMP DEBUG Rename Label] - Options snapshot: ' . print_r( $opts, true ) );

        // Only apply if feature is enabled
        if ( ! shopxpert_is_rename_label_enabled() ) {
            error_log( '[TEMP DEBUG Rename Label] Feature not enabled at filter time, returning original label' );
            return $label;
        }
        
        // Get the option value directly to check if it's set
        $options = get_option( 'shopxpert_others_tabs' );
        // Extra TEMP DEBUG checks
        error_log( '[TEMP DEBUG Rename Label] Shop page - array key exists: ' . ( is_array( $options ) && array_key_exists( 'wl_shop_add_to_cart_txt', $options ) ? 'YES' : 'NO' ) );
        $val = is_array( $options ) && array_key_exists( 'wl_shop_add_to_cart_txt', $options ) ? $options['wl_shop_add_to_cart_txt'] : null;
        error_log( '[TEMP DEBUG Rename Label] Shop page - raw value: ' . var_export( $val, true ) );
        error_log( '[TEMP DEBUG Rename Label] Shop page - empty(trim(value)): ' . ( $val !== null ? ( empty( trim( $val ) ) ? 'YES' : 'NO' ) : 'N/A' ) );
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            error_log( '[ShopXpert Rename Label] Shop page options retrieved: ' . print_r( $options, true ) );
        }
        
        if ( isset( $options['wl_shop_add_to_cart_txt'] ) && ! empty( trim( $options['wl_shop_add_to_cart_txt'] ) ) ) {
            $custom_label = esc_html( $options['wl_shop_add_to_cart_txt'] );
            if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                error_log( '[ShopXpert Rename Label] Shop page custom label found: ' . $custom_label );
            }
            // TEMP DEBUG: log what we are returning so we can see if another filter overrides it
            error_log( '[TEMP DEBUG Rename Label] Shop page returning label: ' . $custom_label );
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
        // TEMP DEBUG: always log when this filter runs to help investigate missing change-label behavior
        error_log( '[TEMP DEBUG Rename Label] Single product filter called. Original label: ' . $label );
        error_log( '[TEMP DEBUG Rename Label] Product ID: ' . ( $product ? $product->get_id() : 'null' ) );
        error_log( '[TEMP DEBUG Rename Label] - Feature enabled: ' . ( shopxpert_is_rename_label_enabled() ? 'YES' : 'NO' ) );
        $opts = get_option( 'shopxpert_others_tabs' );
        error_log( '[TEMP DEBUG Rename Label] - Options snapshot: ' . print_r( $opts, true ) );

        // Only apply if feature is enabled
        if ( ! shopxpert_is_rename_label_enabled() ) {
            error_log( '[TEMP DEBUG Rename Label] Feature not enabled at filter time, returning original label' );
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
            // TEMP DEBUG: log what we are returning so we can see if another filter overrides it
            error_log( '[TEMP DEBUG Rename Label] Single product returning label: ' . $custom_label );
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
        // Retrieve the label (stored with other features)
        $options = get_option( 'shopxpert_others_tabs' );
        if ( isset( $options['wl_description_tab_menu_title'] ) && ! empty( trim( $options['wl_description_tab_menu_title'] ) ) ) {
            $ret = esc_html( $options['wl_description_tab_menu_title'] );
            error_log( '[TEMP DEBUG Rename Label] Description tab returning label: ' . $ret );
            return $ret;
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
        // Retrieve the label (stored with other features)
        $options = get_option( 'shopxpert_others_tabs' );
        if ( isset( $options['wl_additional_information_tab_menu_title'] ) && ! empty( trim( $options['wl_additional_information_tab_menu_title'] ) ) ) {
            $ret = esc_html( $options['wl_additional_information_tab_menu_title'] );
            error_log( '[TEMP DEBUG Rename Label] Additional information tab returning label: ' . $ret );
            return $ret;
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
        // Retrieve the label (stored with other features)
        $options = get_option( 'shopxpert_others_tabs' );
        if ( isset( $options['wl_reviews_tab_menu_title'] ) && ! empty( trim( $options['wl_reviews_tab_menu_title'] ) ) ) {
            $ret = esc_html( $options['wl_reviews_tab_menu_title'] );
            error_log( '[TEMP DEBUG Rename Label] Reviews tab returning label: ' . $ret );
            return $ret;
        }
        return 'Reviews';
    }
} 

// Checkout Page - Place Order Button Text
if ( ! function_exists('shopxpert_rename_place_order_button') ) {
    // Use high priority so themes/plugins can be overridden
    add_filter( 'woocommerce_order_button_text', 'shopxpert_rename_place_order_button', 99 );
    function shopxpert_rename_place_order_button() {
        // Only apply if feature is enabled
        if ( ! shopxpert_is_rename_label_enabled() ) {
            return 'Place order';
        }
        // Get the option value directly to check if it's set
        $options = get_option( 'shopxpert_others_tabs' );
        if ( isset( $options['wl_checkout_placeorder_btn_txt'] ) && ! empty( trim( $options['wl_checkout_placeorder_btn_txt'] ) ) ) {
            $ret = esc_html( $options['wl_checkout_placeorder_btn_txt'] );
            error_log( '[TEMP DEBUG Rename Label] Place order returning label: ' . $ret );
            return $ret;
        }
        return 'Place order'; // Return default if custom label not set
    }
}

// Fallback: if themes/plugins build the order button HTML directly, replace the inner text in the rendered HTML
if ( ! function_exists( 'shopxpert_replace_order_button_html' ) ) {
    add_filter( 'woocommerce_order_button_html', 'shopxpert_replace_order_button_html', 99, 2 );
    function shopxpert_replace_order_button_html( $html, $order ) {
        // Only apply if feature is enabled
        if ( ! shopxpert_is_rename_label_enabled() ) {
            return $html;
        }
        $options = get_option( 'shopxpert_others_tabs' );
        if ( isset( $options['wl_checkout_placeorder_btn_txt'] ) && ! empty( trim( $options['wl_checkout_placeorder_btn_txt'] ) ) ) {
            $replacement = esc_html( $options['wl_checkout_placeorder_btn_txt'] );
            // Try a regex replace of the inner text of the button
            $new_html = preg_replace( '/>\s*Place order\s*</i', '> ' . $replacement . ' <', $html );
            if ( $new_html === null ) {
                // preg_replace error; fallback to simple replace
                $new_html = str_replace( 'Place order', $replacement, $html );
            }
            error_log( '[TEMP DEBUG Rename Label] Replaced order button HTML with: ' . $replacement );
            return $new_html;
        }
        return $html;
    }
}

// Ensure themes that filter or construct loop HTML directly are supported by replacing the anchor text inside the loop add-to-cart HTML
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
        // If no replacement configured, leave HTML as-is
        if ( empty( $replacement ) ) {
            return $html;
        }
        // Replace known default labels safely
        $patterns = array( '/>\s*Add to cart\s*</i', '/>\s*Select options\s*</i' );
        $replacements = array( '> ' . $replacement . ' <', '> ' . $replacement . ' <' );
        $new_html = preg_replace( $patterns, $replacements, $html );
        if ( $new_html === null ) {
            // preg_replace error; fallback to simple str_replace as safer option
            $new_html = str_replace( array( 'Add to cart', 'Select options' ), $replacement, $html );
        }
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            error_log( '[ShopXpert Rename Label] Replaced loop add-to-cart HTML. Product ID: ' . ( $product ? $product->get_id() : 'null' ) );
        }
        return $new_html;
    }
}
