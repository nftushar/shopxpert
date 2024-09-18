<?php 

namespace shopxpert;
 

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

        // Startshop Template CPT Manager
        require SHOPXPERT_ADDONS_PL_PATH . 'incs/admin/inc/Smartshop_Template_Manager.php';
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
    
        require SHOPXPERT_ADDONS_PL_PATH . 'incs/helper-function.php';
        require SHOPXPERT_ADDONS_PL_PATH . 'classes/class.assest_management.php';
        // require SHOPXPERT_ADDONS_PL_PATH . 'classes/class.widgets_control.php';
        // require SHOPXPERT_ADDONS_PL_PATH . 'classes/class.default_data.php';
        // require SHOPXPERT_ADDONS_PL_PATH . 'classes/class.quickview_manage.php';
        // require SHOPXPERT_ADDONS_PL_PATH . 'classes/class.icon_list.php';
        // require SHOPXPERT_ADDONS_PL_PATH . 'classes/class.multi_language.php';
        //    require SHOPXPERT_ADDONS_PL_PATH . 'incs/admin/templates/dashboard-feature-setting-popups.php'; 

        // Admin Setting file
        if (is_admin()) {
            // require SHOPXPERT_ADDONS_PL_PATH . 'incs/custom-metabox.php';
            require SHOPXPERT_ADDONS_PL_PATH . 'incs/admin/admin-init.php';
        }

        // features Manager
        require( SHOPXPERT_ADDONS_PL_PATH. 'incs/features/class.feature-manager.php' );
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