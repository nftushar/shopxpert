<?php

namespace ShopXpert\Features\ProductComparison;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Manage Comparison logic class
 */
class Manage_Comparison {
    /**
     * [$_instance]
     * @var null
     */
    private static $_instance = null;

    /**
     * [instance] Initializes a singleton instance
     * @return [Manage_Comparison]
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct() {
        // Future: add hooks, AJAX, etc.
    }

    /**
     * Add a product to the comparison list
     */
    public function add_product($product_id) {
        $list = $this->get_comparison_list();
        error_log('Before add: ' . print_r($list, true));
        if (!in_array($product_id, $list)) {
            $list[] = $product_id;
            $this->save_comparison_list($list);
            error_log('Added product: ' . $product_id);
        }
        error_log('After add: ' . print_r($list, true));
    }

    /**
     * Remove a product from the comparison list
     */
    public function remove_product($product_id) {
        $list = $this->get_comparison_list();
        $list = array_diff($list, [$product_id]);
        $this->save_comparison_list($list);
    }

    /**
     * Get the current comparison list
     */
    public function get_comparison_list() {
        if (\is_user_logged_in()) {
            $user_id = \get_current_user_id();
            $list = \get_user_meta($user_id, '_shopxpert_comparison_list', true);
            if (!is_array($list)) $list = [];
        } else {
            $list = isset($_COOKIE['shopxpert_comparison_list']) ? json_decode(stripslashes($_COOKIE['shopxpert_comparison_list']), true) : [];
            if (!is_array($list)) $list = [];
        }
        error_log('get_comparison_list: ' . print_r($list, true));
        return $list;
    }

    /**
     * Save the comparison list
     */
    private function save_comparison_list($list) {
        if (\is_user_logged_in()) {
            $user_id = \get_current_user_id();
            \update_user_meta($user_id, '_shopxpert_comparison_list', $list);
        } else {
            \setcookie('shopxpert_comparison_list', json_encode($list), time() + 3600 * 24 * 30, '/');
            $_COOKIE['shopxpert_comparison_list'] = json_encode($list);
        }
    }

    /**
     * Clear the comparison list
     */
    public function clear_comparison() {
        $this->save_comparison_list([]);
    }
} 