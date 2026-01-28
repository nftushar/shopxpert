<?php 

namespace ShopXpert;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

/**
 * Base
 */
final class Base {

    const MINIMUM_PHP_VERSION = '5.4';
    const MINIMUM_ELEMENTOR_VERSION = '3.0.0';

    private static $instance = null;

    public static function instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Initialize your class here
        add_action('plugins_loaded', [$this, 'init']);
        
        // Initialize caching and preload settings on plugins_loaded
        add_action('plugins_loaded', [$this, 'init_cache'], 5);
    }

    /**
     * Initialize caching system
     * @return void
     */
    public function init_cache() {
        // Preload all settings to reduce database queries
        \ShopXpert\Cache\Manager::preload_settings();

        // Register cache invalidation hooks for when settings are updated
        add_action('update_option_shopxpert_others_tabs', [$this, 'on_settings_update']);
        add_action('update_option_shopxpert_pre_order_settings', [$this, 'on_settings_update']);
        add_action('update_option_shopxpert_backorder_settings', [$this, 'on_settings_update']);
        add_action('update_option_shopxpert_product_comparison_settings', [$this, 'on_settings_update']);
        add_action('update_option_shopxpert_fake_order_detection_settings', [$this, 'on_settings_update']);
        add_action('update_option_shopxpert_partial_payment_settings', [$this, 'on_settings_update']);
        add_action('update_option_shopxpert_product_filter_settings', [$this, 'on_settings_update']);
    }

    /**
     * Handle settings update to invalidate cache
     * @return void
     */
    public function on_settings_update() {
        \ShopXpert\Cache\Manager::flush_all();
    }

    /**
     * [init] Plugins Loaded Init Hook
     * @return [void]
     */
    public function init() {
         // Add this line to check
        $this->included_files();
    }
    

    public function included_files() {
        // Initialize Assets Management to register all hooks
        // Must be before feature manager for assets to load properly
        \ShopXpert\Classes\Assets_Management::instance();
        
        // Initialize Feature Manager for all features
        \ShopXpert\Features\Shopxpert_Feature_Manager::instance();
        
        // Initialize Admin interface if in admin
        if (is_admin()) {
            \ShopXpert\Admin\ShopXpert_Admin_Init::instance();
        }
    } 
}

/**
 * Initializes the main plugin
 *
 * @return Base
 */
function shopxpert() {
    return Base::instance();
}