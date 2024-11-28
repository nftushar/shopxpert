<?php
/*
Plugin Name:wwe ShopXpert
Plugin URI:https://github.com/tushar/shopxpert
Description: An all-in-one WooCommerce solution for label customization, pre-orders, and more.
Version: 1.0.3
Author: NF Tushar
Author URI: https://github.com/tushar
License: GPLv2 or later
Text Domain: shopxpert
Domain Path: /languages/
*/


if (!defined('ABSPATH')) exit; // Exit if accessed directly

define('SHOPXPERT_VERSION', '1.0.3');
define('SHOPXPERT_ADDONS_PL_ROOT', __FILE__);
define('SHOPXPERT_ADDONS_PL_URL', plugins_url('/', SHOPXPERT_ADDONS_PL_ROOT));
define('SHOPXPERT_ADDONS_PL_PATH', plugin_dir_path(SHOPXPERT_ADDONS_PL_ROOT));


// Include the Composer autoloader (if using Composer)
// require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
        require_once __DIR__ . '/vendor/autoload.php';
    }
    
    
// Required File
require_once SHOPXPERT_ADDONS_PL_PATH . 'incs/main.php';
// Load the plugin's base functionality
\ShopXpert\shopxpert();