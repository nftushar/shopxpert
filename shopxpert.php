<?php
/*
Plugin Name: D ShopXpert
Plugin URI: https://github.com/tushar/shopxpert
Description: An all-in-one WooCommerce solution for label customization, pre-orders, and more.
Version: 1.0.4
Author: NF Tushar
Author URI: https://github.com/tushar
License: GPLv2 or later
Text Domain: shopxpert
Domain Path: /languages/
Requires Plugins: woocommerce
*/

if (!defined('ABSPATH')) exit; // Exit if accessed directly

define('SHOPXPERT_VERSION', '1.0.4');
define('SHOPXPERT_ADDONS_PL_ROOT', __FILE__);
define('SHOPXPERT_ADDONS_PL_URL', plugins_url('/', SHOPXPERT_ADDONS_PL_ROOT));
define('SHOPXPERT_ADDONS_PL_PATH', plugin_dir_path(SHOPXPERT_ADDONS_PL_ROOT));

// Include the Composer autoloader (if using Composer)
// require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

// Include WooCommerce check
require_once plugin_dir_path(__FILE__) . 'includes/class-shopxpert-woocommerce-check.php';

// Required File
require_once SHOPXPERT_ADDONS_PL_PATH . 'incs/main.php';

// Load the plugin's base functionality
\ShopXpert\shopxpert();


function shopxpert_enqueue_scripts() {
    wp_enqueue_script('shopxpert-react-app', plugins_url('assets/js/app.js', __FILE__), array(), SHOPXPERT_VERSION, true);
    wp_enqueue_style('shopxpert-react-app', plugins_url('assets/css/app.css', __FILE__), array(), SHOPXPERT_VERSION);
}
add_action('wp_enqueue_scripts', 'shopxpert_enqueue_scripts');

function shopxpert_render_react_component() {
    echo '<div id="shopxpert-react-app"></div>';
}
add_shortcode('shopxpert_react', 'shopxpert_render_react_component');