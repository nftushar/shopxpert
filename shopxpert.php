<?php
/*
Plugin Name: working on SHOPXPERT
Plugin URI:
Description: Demo of Plugin Options Page
Version: 1.0.2
Author: NF Tushar
Author URI: https://tushar.me
License: GPLv2 or later
Text Domain: shopxpert
Domain Path: /languages/
*/


if (!defined('ABSPATH')) exit; // Exit if accessed directly

define('SHOPXPERT_VERSION', '1.0.2');
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