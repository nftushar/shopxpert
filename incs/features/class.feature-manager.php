<?php

use function Shopxpert\incs\shopxpert_get_option;



if (! defined('ABSPATH')) exit; // Exit if accessed directly


class Shopxpert_Feature_Manager
{

    private static $_instance = null;

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
     * Constructor
     */
    public function __construct()
    {
        // Ensure the plugin.php file is loaded
        if (! function_exists('is_plugin_active')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        if (is_admin()) {
            $this->include_under_admin();
        }

        $this->include_file();
    }

    /**
     * [include_under_admin] Necessary files required if admin page.
     * @return [void]
     */
    public function include_under_admin()
    {

        // Post Duplicator
        if (!is_plugin_active('ht-mega-for-elementor/htmega_addons_elementor.php')) {
            if (shopxpert_get_option('postduplicator', 'shopxpert_others_tabs', 'off') === 'on') {
                require_once(SHOPXPERT_ADDONS_PL_PATH . 'incs/features/post-duplicator/class.post-duplicator.php');
            }
        }
    }
    /**
     * [include_file] Necessary files required
     * @return [void]
     */
    public function include_file()
    {
        // Check if WooCommerce is active
        if (! is_plugin_active('woocommerce/woocommerce.php')) {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('[ShopXpert Feature Manager] WooCommerce is not active. Skipping WooCommerce-dependent features.');
            }
            if (is_admin()) {
                add_action('admin_notices', function () {
                    echo '<div class="error"><p><strong>ShopXpert</strong> requires WooCommerce to be active. Please activate WooCommerce to enable all features.</p></div>';
                });
            }
            return; // Exit early if WooCommerce is not active
        }

        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('[ShopXpert Feature Manager] WooCommerce is active. Checking features...');
            error_log('[ShopXpert Feature Manager] is_admin(): ' . (is_admin() ? 'YES' : 'NO'));
        }

        // Change Label Feature
        // Prefer the feature card key ('rename_label_settings') but fallback to legacy 'enablerenamelabel' for compatibility.
        $rename_label_enabled = shopxpert_get_option('rename_label_settings', 'shopxpert_others_tabs', 'off');
        if ( $rename_label_enabled === 'off' ) {
            $rename_label_enabled = shopxpert_get_option('enablerenamelabel', 'shopxpert_others_tabs', 'off');
        }
        $is_admin_page = is_admin();
        
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('[ShopXpert Feature Manager] Change Label Feature Check:');
            error_log('[ShopXpert Feature Manager] - Resolved option value: ' . $rename_label_enabled);
            error_log('[ShopXpert Feature Manager] - Is admin: ' . ($is_admin_page ? 'YES' : 'NO'));
            error_log('[ShopXpert Feature Manager] - Should load: ' . (!$is_admin_page && $rename_label_enabled == 'on' ? 'YES' : 'NO'));
            
            // Check what options exist
            $all_options = get_option('shopxpert_others_tabs');
            error_log('[ShopXpert Feature Manager] - All shopxpert_others_tabs options: ' . print_r($all_options, true));
        }
        
        if (!is_admin() && $rename_label_enabled == 'on') {
            $rename_label_file = SHOPXPERT_ADDONS_PL_PATH . 'incs/features/rename-label/rename_label.php';
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('[ShopXpert Feature Manager] Loading Change Label file: ' . $rename_label_file);
                error_log('[ShopXpert Feature Manager] File exists: ' . (file_exists($rename_label_file) ? 'YES' : 'NO'));
            }
            
            if (file_exists($rename_label_file)) {
                require($rename_label_file);
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    error_log('[ShopXpert Feature Manager] Change Label file loaded successfully');
                }
            } else {
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    error_log('[ShopXpert Feature Manager] ERROR: Change Label file not found at: ' . $rename_label_file);
                }
            }
        } else {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                if ($is_admin_page) {
                    error_log('[ShopXpert Feature Manager] Change Label feature skipped: Admin page (only loads on frontend)');
                } else {
                    error_log('[ShopXpert Feature Manager] Change Label feature skipped: Feature not enabled (value: ' . $rename_label_enabled . ')');
                }
            }
        }

        // pre-orders
        $pre_order_enabled = shopxpert_get_option('enable', 'shopxpert_pre_order_settings', 'off');
        if ($pre_order_enabled === 'off') {
            // Backwards-compatibility: older versions used 'enablerpreorder' as the option key
            $pre_order_enabled = shopxpert_get_option('enablerpreorder', 'shopxpert_pre_order_settings', 'off');
        }
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('[ShopXpert Feature Manager] Pre-orders enabled check: ' . $pre_order_enabled);
        }
        if (!is_admin() && $pre_order_enabled == 'on') {
            require_once(SHOPXPERT_ADDONS_PL_PATH . 'incs/features/pre-orders/pre-orders.php');
        }


        // Stock on Hold
        if (shopxpert_get_option('enable', 'shopxpert_backorder_settings', 'off') == 'on') {
            require_once(SHOPXPERT_ADDONS_PL_PATH . 'incs/features/backorder/class.backorder.php');
        }


        $value = shopxpert_get_option('wishlist', 'shopxpert_others_tabs', 'off');

        if ($value == 'on') {
            // $this->deactivate( 'wishlist/wishlist.php' );
            if (! class_exists('WooWishList_Base')) {
                require_once(SHOPXPERT_ADDONS_PL_PATH . 'incs/features/wishlist/init.php');
            }
        }


        // Wishlist
        if (shopxpert_get_option('wishlist', 'shopxpert_others_tabs', 'off') == 'off') {
            // $this->deactivate( 'wishlist/wishlist.php' );
            if (! class_exists('WooWishList_Base')) {
                require_once(SHOPXPERT_ADDONS_PL_PATH . 'incs/features/wishlist/init.php');
            }
        }

        // partial-payment
        if (is_plugin_active('shopxpert-addons/shopxpert_addons.php') && defined("SHOPXPERT_ADDONS_PL_PATH")) {

            // Partial payment
            if ((shopxpert_get_option('enable', 'shopxpert_partial_payment_settings', 'off') == 'on')) {
                require_once(SHOPXPERT_ADDONS_PL_PATH . 'incs/features/partial-payment/partial-payment.php');
            }
        }

        // Fake Order Detection
        if (shopxpert_get_option('enable_fake_order_detection', 'shopxpert_fake_order_detection_settings', 'off') == 'on') {
            require_once(SHOPXPERT_ADDONS_PL_PATH . 'incs/features/fake-order-detection/class.fake-order-detection.php');
            new ShopXpert_Fake_Order_Detection();
        }

        // Product Comparison
        $comparison_enabled = shopxpert_get_option('product_comparison', 'shopxpert_others_tabs', 'off');
        // Backwards-compatibility: check dedicated product-comparison settings and alternate keys
        if ($comparison_enabled === 'off') {
            $comparison_enabled = shopxpert_get_option('enable_product_comparison', 'shopxpert_product_comparison_settings', 'off');
            error_log('Product Comparison Enabled? ' . $comparison_enabled);
            if ($comparison_enabled === 'off') {
                $comparison_enabled = shopxpert_get_option('enable_product_comparison', 'shopxpert_others_tabs', 'off');
                error_log('Product Comparison Enabled? ' . $comparison_enabled);
                }
        }
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('[ShopXpert Feature Manager] Product Comparison enabled check: ' . $comparison_enabled);
        }
        if ($comparison_enabled === 'on') {
            require_once(SHOPXPERT_ADDONS_PL_PATH . 'incs/features/product-comparison/class.product-comparison.php');
            if (function_exists('Shopxpert\\ProductComparison\\Shopxpert_Product_Comparison')) {
                \Shopxpert\ProductComparison\Shopxpert_Product_Comparison();
            }
        }
    }

    /**
     * [include_product_filter_Feature_file] Include product filter Feature file
     * @return [void]
     */
    public function include_product_filter_Feature_file()
    {
        if (file_exists(SHOPXPERT_ADDONS_PL_PATH . 'incs/features/product-filter/product-filter.php')) {
            require_once(SHOPXPERT_ADDONS_PL_PATH . 'incs/features/product-filter/product-filter.php');

            if (shopxpert_get_option('enable', 'shopxpert_product_filter_settings', 'off') == 'on') {
                shopxpert_product_filter(true);
            } else {
                shopxpert_product_filter(false);
            }
        }
    }

    /**
     * [deactivate] Deactivate a plugin
     * @return [void]
     */
    public function deactivate($slug)
    {
        if (is_plugin_active($slug)) {
            return deactivate_plugins($slug);
        }
    }
}

Shopxpert_Feature_Manager::instance();
