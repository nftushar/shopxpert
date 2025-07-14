<?php
/*
Plugin Name: ShopXpert
Plugin URI: https://github.com/tushar/shopxpert
Description: An all-in-one WooCommerce solution for label customization, pre-orders, and more.
Version: 1.0.5
Author: NF Tushar
Author URI: https://github.com/tushar
License: GPLv2 or later
Text Domain: shopxpert
Domain Path: /languages/
Requires Plugins: woocommerce
*/

if (!defined('ABSPATH')) exit; // Exit if accessed directly

define('SHOPXPERT_VERSION', '1.0.5');
define('SHOPXPERT_ADDONS_PL_ROOT', __FILE__);
define('SHOPXPERT_ADDONS_PL_URL', plugins_url('/', SHOPXPERT_ADDONS_PL_ROOT));
define('SHOPXPERT_ADDONS_PL_PATH', plugin_dir_path(SHOPXPERT_ADDONS_PL_ROOT));

// Include the Composer autoloader (if using Composer)
// require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

// Include WooCommerce check
require_once plugin_dir_path(__FILE__) . 'incs/class-shopxpert-woocommerce-check.php';

// Required File
require_once SHOPXPERT_ADDONS_PL_PATH . 'incs/main.php';

// Load the plugin's base functionality
\ShopXpert\shopxpert();

if ( ! function_exists( 'add_filter' ) ) {
    require_once( ABSPATH . 'wp-includes/plugin.php' );
}
if ( ! function_exists( 'plugin_basename' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'shopxpert_action_links');
function shopxpert_action_links($links) {
    $settings_link = '<a href="admin.php?page=shopxpert">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}
 
require_once plugin_dir_path(__FILE__) . 'incs/features/product-comparison/class.product-comparison.php';
if (class_exists('\Shopxpert\ProductComparison\Shopxpert_Product_Comparison_Base')) {
    \Shopxpert\ProductComparison\Shopxpert_Product_Comparison_Base::instance();
}
require_once plugin_dir_path(__FILE__) . 'incs/features/product-comparison/Manage_Comparison.php';
require_once plugin_dir_path(__FILE__) . 'incs/features/product-comparison/Shortcode.php';
if (class_exists('\Shopxpert\ProductComparison\Shortcode')) {
    $shopxpert_comparison_shortcode = new \Shopxpert\ProductComparison\Shortcode();
}
 
require_once plugin_dir_path(__FILE__) . 'incs/features/product-comparison/Frontend.php';
if (class_exists('\Shopxpert\ProductComparison\Frontend')) {
    \Shopxpert\ProductComparison\Frontend::instance();
}
 