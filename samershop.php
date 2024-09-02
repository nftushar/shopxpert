<?php
/*
Plugin Name: working on SmartShop
Plugin URI:
Description: Demo of Plugin Options Page
Version: 1.0.2
Author: NF Tushar
Author URI: https://tushar.me
License: GPLv2 or later
Text Domain: smartshop
Domain Path: /languages/
*/


if (!defined('ABSPATH')) exit; // Exit if accessed directly

define('SMARTSHOP_VERSION', '1.0.2');
define('SMARTSHOP_ADDONS_PL_ROOT', __FILE__);
define('SMARTSHOP_ADDONS_PL_URL', plugins_url('/', SMARTSHOP_ADDONS_PL_ROOT));
define('SMARTSHOP_ADDONS_PL_PATH', plugin_dir_path(SMARTSHOP_ADDONS_PL_ROOT));

// Include the Composer autoloader (if using Composer)
// require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
        require_once __DIR__ . '/vendor/autoload.php';
    }
    
 

    
// Required File
require_once SMARTSHOP_ADDONS_PL_PATH . 'incs/main.php';
// Load the plugin's base functionality
\SmartShop\smartshop();