<?php

namespace ShopXpert\Helpers;

if (!defined('ABSPATH')) exit;

/**
 * Helpers Loader
 * 
 * Loads all helper modules and provides backward-compatible functions
 */
class Loader {

    /**
     * Initialize helpers
     * 
     * @return void
     */
    public static function init() {
        // Load helper classes
        require_once __DIR__ . '/Options.php';
        require_once __DIR__ . '/Products.php';
        require_once __DIR__ . '/Utilities.php';
        
        // Register backward-compatible functions
        self::register_functions();
    }

    /**
     * Register backward-compatible functions
     * 
     * @return void
     */
    private static function register_functions() {
        // These functions provide backward compatibility
        // New code should use the helper classes directly
    }
}

/**
 * Backward-compatible option function
 */
function shopxpert_get_option($option, $section, $default = '') {
    return Options::get($option, $section, $default);
}

/**
 * Backward-compatible option label text function
 */
function shopxpert_get_option_label_text($option, $section, $default = '') {
    return Options::get_label_text($option, $section, $default);
}

/**
 * Backward-compatible option update function
 */
function shopxpert_update_option($section, $option_key, $new_value) {
    return Options::update($section, $option_key, $new_value);
}

/**
 * Backward-compatible product query function
 */
function shopxpert_product_query($query_args = []) {
    return Products::generate_query($query_args);
}

/**
 * Backward-compatible last product ID function
 */
function shopxpert_get_last_product_id() {
    return Products::get_last_id();
}

/**
 * Backward-compatible min/max price limit function
 */
function shopxpert_minmax_price_limit() {
    return Products::get_price_limits();
}

/**
 * Backward-compatible get taxonomies function
 */
function shopxpert_get_taxonomies($object = 'product', $skip_terms = false) {
    return Products::get_taxonomies($object, $skip_terms);
}

/**
 * Backward-compatible cookie name function
 */
function shopxpert_get_cookie_name($name) {
    return Utilities::get_cookie_name($name);
}

/**
 * Backward-compatible clean function
 */
function shopxpert_clean($var) {
    return Utilities::clean($var);
}

/**
 * Backward-compatible HTML allowed tags function
 */
function shopxpert_get_html_allowed_tags($tag_type = 'title') {
    return Utilities::get_html_allowed_tags($tag_type);
}

/**
 * Backward-compatible validate HTML tag function
 */
function shopxpert_validate_html_tag($tag = 'div') {
    return Utilities::validate_html_tag($tag);
}

/**
 * Backward-compatible HTML tag lists function
 */
function shopxpert_html_tag_lists() {
    return Utilities::get_html_tag_lists();
}

/**
 * Backward-compatible order by options function
 */
function shopxpert_order_by_opts() {
    return Utilities::get_order_by_options();
}

/**
 * Backward-compatible get countries function
 */
function shopxpert_get_countries() {
    return Utilities::get_countries();
}

/**
 * Backward-compatible get users function
 */
function shopxpert_get_users() {
    return Utilities::get_users();
}

/**
 * Backward-compatible get user roles function
 */
function shopxpert_get_user_roles() {
    return Utilities::get_user_roles();
}

/**
 * Backward-compatible get menus function
 */
function shopxpert_get_all_create_menus() {
    return Utilities::get_menus();
}

/**
 * Backward-compatible taxonomy list function
 */
function shopxpert_taxonomy_list($taxonomy = 'product_cat', $option_value = 'slug') {
    return Utilities::get_taxonomy_list($taxonomy, $option_value);
}

/**
 * Backward-compatible get post types function
 */
function shopxpert_get_post_types($args = []) {
    return Utilities::get_post_types($args);
}

/**
 * Backward-compatible get image size function
 */
function shopxpert_get_image_size() {
    return Utilities::get_image_sizes();
}

/**
 * Backward-compatible get theme by name function
 */
function shopxpert_get_theme_byname($name) {
    return Utilities::get_theme_by_name($name);
}

/**
 * Backward-compatible get current theme directory function
 */
function shopxpert_get_current_theme_directory() {
    return Utilities::get_current_theme_directory();
}

// Initialize the loader
Loader::init();
