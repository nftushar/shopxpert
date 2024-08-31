<?php
/*
* Shop Page
*/

// Add to Cart Button Text
if( !function_exists('smartshop_custom_add_cart_button_shop_page') ){
    add_filter( 'woocommerce_product_add_to_cart_text', 'smartshop_custom_add_cart_button_shop_page', 99, 2 );
    function smartshop_custom_add_cart_button_shop_page( $label ) {
        return __( smartshop_get_option_label_text( 'wl_shop_add_to_cart_txt', 'smartshop_rename_label_tabs', 'Add to Cart' ), 'smartshop-pro' );
    }
}

/*
* Product Details Page
*/

// Add to Cart Button Text
if( !function_exists('smartshop_custom_add_cart_button_single_product') ){
    add_filter( 'woocommerce_product_single_add_to_cart_text', 'smartshop_custom_add_cart_button_single_product' );
    function smartshop_custom_add_cart_button_single_product( $label ) {
        return __( smartshop_get_option_label_text( 'wl_add_to_cart_txt', 'smartshop_rename_label_tabs', 'Add to Cart' ), 'smartshop-pro' );
    }
}

//Description tab
if( !function_exists('smartshop_rename_description_product_tab_label') ){
    add_filter( 'woocommerce_product_description_tab_title', 'smartshop_rename_description_product_tab_label' );
    function smartshop_rename_description_product_tab_label() {
        return __( smartshop_get_option_label_text( 'wl_description_tab_menu_title', 'smartshop_rename_label_tabs', 'Description' ), 'smartshop-pro' );
    }
}

if( !function_exists('smartshop_rename_description_tab_heading') ){
    add_filter( 'woocommerce_product_description_heading', 'smartshop_rename_description_tab_heading' );
    function smartshop_rename_description_tab_heading() {
        return __( smartshop_get_option_label_text( 'wl_description_tab_menu_title', 'smartshop_rename_label_tabs', 'Description' ), 'smartshop-pro' );
    }
}

//Additional Info tab
if( !function_exists('smartshop_rename_additional_information_product_tab_label') ){
    add_filter( 'woocommerce_product_additional_information_tab_title', 'smartshop_rename_additional_information_product_tab_label' );
    function smartshop_rename_additional_information_product_tab_label() {
        return __( smartshop_get_option_label_text( 'wl_additional_information_tab_menu_title', 'smartshop_rename_label_tabs','Additional Information' ), 'smartshop-pro' );
    }
}

if( !function_exists('smartshop_rename_additional_information_tab_heading') ){
    add_filter( 'woocommerce_product_additional_information_heading', 'smartshop_rename_additional_information_tab_heading' );
    function smartshop_rename_additional_information_tab_heading() {
        return __( smartshop_get_option_label_text( 'wl_additional_information_tab_menu_title', 'smartshop_rename_label_tabs','Additional Information' ), 'smartshop-pro' );
    }
}

//Reviews Info tab
if( !function_exists('smartshop_rename_reviews_product_tab_label') ){
    add_filter( 'woocommerce_product_reviews_tab_title', 'smartshop_rename_reviews_product_tab_label' );
    function smartshop_rename_reviews_product_tab_label() {
        return __( smartshop_get_option_label_text( 'wl_reviews_tab_menu_title', 'smartshop_rename_label_tabs','Reviews' ), 'smartshop-pro');
    }
}


/*
* Checkout Page
*/
if( !function_exists('smartshop_rename_place_order_button') ){
    add_filter( 'woocommerce_order_button_text', 'smartshop_rename_place_order_button' );
    function smartshop_rename_place_order_button() {
        return __( smartshop_get_option_label_text( 'wl_checkout_placeorder_btn_txt', 'smartshop_rename_label_tabs','Place order' ), 'smartshop-pro');
    }
}