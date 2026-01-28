<?php
namespace ShopXpert\Features\Wishlist;

/**
 * Admin handlers class
 */
class Admin {

    /**
     * [$_instance]
     * @var null
     */
    private static $_instance = null;

    /**
     * [instance] Initializes a singleton instance
     * @return [Admin]
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    /**
     * Initialize the class
     */
    private function __construct() {
        // Dashboard is auto-loaded via Composer PSR-4
        Admin\Dashboard::instance();
    }

}