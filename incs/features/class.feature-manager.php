<?php

namespace ShopXpert\Features;

use function ShopXpert\shopxpert_get_option;

if (! defined('ABSPATH')) exit; // Exit if accessed directly

/**
 * Feature Manager - LAZY LOADING VERSION
 * Handles on-demand loading of plugin features based on settings.
 * Features are only loaded when their hooks are triggered (lazy loading).
 * This significantly improves initial page load time and reduces memory usage.
 */
class Shopxpert_Feature_Manager
{

    private static $_instance = null;
    
    /**
     * Track which features have been loaded to avoid re-loading
     * @var array
     */
    private static $loaded_features = [];

    /**
     * Instance
     */
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor - Sets up lazy loading hooks
     */
    public function __construct()
    {
        // Ensure the plugin.php file is loaded
        if (! function_exists('is_plugin_active')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        // Setup lazy loading hooks on wp_loaded
        add_action('wp_loaded', [$this, 'register_lazy_hooks'], 999);
        
        // Load admin-only features
        if (is_admin()) {
            $this->include_under_admin();
        }

        // Lightweight check - just verify WooCommerce status
        if (! is_plugin_active('woocommerce/woocommerce.php')) {
            if (is_admin()) {
                add_action('admin_notices', function () {
                    echo '<div class="error"><p><strong>ShopXpert</strong> requires WooCommerce to be active.</p></div>';
                });
            }
        }
    }

    /**
     * Register lazy hooks for features - called on wp_loaded
     * Features will only be loaded when their specific hooks are triggered
     * This is the key to lazy loading - features are NOT loaded until needed
     */
    public function register_lazy_hooks() {
        // Check WooCommerce is active
        if (! is_plugin_active('woocommerce/woocommerce.php')) {
            return;
        }
        
        // Pre-orders - lazy load on WooCommerce hooks
        $pre_order_enabled = shopxpert_get_option('enable', 'shopxpert_pre_order_settings', 'off');
        if ($pre_order_enabled === 'off') {
            $pre_order_enabled = shopxpert_get_option('enablerpreorder', 'shopxpert_pre_order_settings', 'off');
        }
        if ($pre_order_enabled == 'on') {
            add_action('woocommerce_before_shop_loop', [$this, 'load_pre_orders'], 5);
            add_action('woocommerce_single_product_summary', [$this, 'load_pre_orders'], 5);
        }
        
        // Backorder - lazy load on stock-related hooks
        if (shopxpert_get_option('enable', 'shopxpert_backorder_settings', 'off') == 'on') {
            add_action('woocommerce_get_availability', [$this, 'load_backorder'], 5);
        }
        
        // Wishlist - lazy load on appropriate hooks
        $wishlist_enabled = shopxpert_get_option('wishlist', 'shopxpert_others_tabs', 'off');
        if ($wishlist_enabled == 'on' || $wishlist_enabled == 'off') {
            add_action('woocommerce_after_shop_loop_item', [$this, 'load_wishlist'], 15);
            add_action('woocommerce_single_product_summary', [$this, 'load_wishlist'], 25);
            add_shortcode('wishlist', [$this, 'load_wishlist']);
        }
        
        // Product Comparison - lazy load
        $comparison_enabled = shopxpert_get_option('product_comparison', 'shopxpert_others_tabs', 'off');
        if ($comparison_enabled === 'off') {
            $comparison_enabled = shopxpert_get_option('enable_product_comparison', 'shopxpert_product_comparison_settings', 'off');
        }
        if ($comparison_enabled === 'on') {
            add_action('woocommerce_after_shop_loop_item', [$this, 'load_comparison'], 20);
            add_shortcode('compare_button', [$this, 'load_comparison']);
        }
        
        // Fake Order Detection - lazy load on order creation
        if (shopxpert_get_option('enable_fake_order_detection', 'shopxpert_fake_order_detection_settings', 'off') == 'on') {
            add_action('woocommerce_checkout_create_order', [$this, 'load_fake_order_detection'], 5);
        }
        
        // Change Label - lazy load for frontend
        $rename_label_enabled = shopxpert_get_option('rename_label_settings', 'shopxpert_others_tabs', 'off');
        if ($rename_label_enabled === 'off') {
            $rename_label_enabled = shopxpert_get_option('enablerenamelabel', 'shopxpert_others_tabs', 'off');
        }
        if ($rename_label_enabled == 'on') {
            add_action('woocommerce_before_shop_loop', [$this, 'load_rename_label'], 1);
            add_action('woocommerce_single_product_summary', [$this, 'load_rename_label'], 1);
        }
    }

    // Lazy loader methods - each loads the feature only when called
    
    /**
     * Lazy load Pre-Orders feature
     */
    public function load_pre_orders() {
        if (isset(self::$loaded_features['pre_orders'])) return;
        if (class_exists('ShopXpert\\Features\\PreOrders\\Shopxpert_Pre_Orders')) {
            \ShopXpert\Features\PreOrders\Shopxpert_Pre_Orders::get_instance();
            self::$loaded_features['pre_orders'] = true;
        }
    }
    
    /**
     * Lazy load Backorder feature
     */
    public function load_backorder() {
        if (isset(self::$loaded_features['backorder'])) return;
        if (class_exists('ShopXpert\\Features\\Backorder\\Shopxpert_Backorder')) {
            new \ShopXpert\Features\Backorder\Shopxpert_Backorder();
            self::$loaded_features['backorder'] = true;
        }
    }
    
    /**
     * Lazy load Wishlist feature
     */
    public function load_wishlist() {
        if (isset(self::$loaded_features['wishlist'])) return;
        if (! class_exists('WooWishList_Base') && class_exists('ShopXpert\\Features\\Wishlist\\WooWishList_Base')) {
            new \ShopXpert\Features\Wishlist\WooWishList_Base();
            self::$loaded_features['wishlist'] = true;
        }
    }
    
    /**
     * Lazy load Product Comparison feature
     */
    public function load_comparison() {
        if (isset(self::$loaded_features['comparison'])) return;
        if (class_exists('ShopXpert\\Features\\ProductComparison\\Shopxpert_Product_Comparison_Base')) {
            \ShopXpert\Features\ProductComparison\Shopxpert_Product_Comparison_Base::instance();
        }
        if (class_exists('ShopXpert\\Features\\ProductComparison\\Frontend')) {
            \ShopXpert\Features\ProductComparison\Frontend::instance();
        }
        self::$loaded_features['comparison'] = true;
    }
    
    /**
     * Lazy load Fake Order Detection feature
     */
    public function load_fake_order_detection() {
        if (isset(self::$loaded_features['fake_order_detection'])) return;
        if (class_exists('ShopXpert\\Features\\FakeOrderDetection\\ShopXpert_Fake_Order_Detection')) {
            new \ShopXpert\Features\FakeOrderDetection\ShopXpert_Fake_Order_Detection();
            self::$loaded_features['fake_order_detection'] = true;
        }
    }
    
    /**
     * Lazy load Rename Label feature
     */
    public function load_rename_label() {
        if (isset(self::$loaded_features['rename_label'])) return;
        $rename_label_file = SHOPXPERT_ADDONS_PL_PATH . 'incs/features/rename-label/rename_label.php';
        if (file_exists($rename_label_file)) {
            require($rename_label_file);
            self::$loaded_features['rename_label'] = true;
        }
    }

    /**
     * [include_under_admin] Admin features
     */
    public function include_under_admin()
    {
        // Post Duplicator
        if (!is_plugin_active('ht-mega-for-elementor/htmega_addons_elementor.php')) {
            if (shopxpert_get_option('postduplicator', 'shopxpert_others_tabs', 'off') === 'on') {
                if (class_exists('ShopXpert\\Features\\PostDuplicator\\Shopxpert_Post_Duplicator')) {
                    new \ShopXpert\Features\PostDuplicator\Shopxpert_Post_Duplicator();
                }
            }
        }
    }

    /**
     * Legacy method for backward compatibility
     * @deprecated Use lazy loading instead
     */
    public function include_file()
    {
        // Legacy - kept for backward compatibility
        $this->load_pre_orders();
        $this->load_backorder();
        $this->load_wishlist();
        $this->load_comparison();
        $this->load_fake_order_detection();
        $this->load_rename_label();
        
        // Partial Payment - still eager loads
        if (is_plugin_active('shopxpert-addons/shopxpert_addons.php')) {
            if ((shopxpert_get_option('enable', 'shopxpert_partial_payment_settings', 'off') == 'on')) {
                if (class_exists('ShopXpert\\Features\\PartialPayment\\PartialPayment')) {
                    new \ShopXpert\Features\PartialPayment\PartialPayment();
                }
            }
        }
    }

    /**
     * [include_product_filter_Feature_file] Include product filter Feature file
     * @return [void]
     */
    public function include_product_filter_Feature_file()
    {
        if (shopxpert_get_option('enable', 'shopxpert_product_filter_settings', 'off') == 'on') {
            if (class_exists('ShopXpert\\Features\\ProductFilter\\ProductFilter')) {
                $product_filter = new \ShopXpert\Features\ProductFilter\ProductFilter();
                $product_filter->init();
            }
        }
    }

    /**
     * Get loaded features status (for debugging)
     * @return array
     */
    public static function get_loaded_features() {
        return self::$loaded_features;
    }
}

Shopxpert_Feature_Manager::instance();
