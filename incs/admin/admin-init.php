<?php
namespace Smartshop\Incs\Admin;

use Smartshop\Incs;
use Smartshop\Incs\Admin\Inc\Smartshop_Admin_Fields_Manager;
use Smartshop\Incs\Admin\Inc\Smartshop_Admin_Fields;

use function Smartshop\Incs\smartshop_clean;

if (!defined('ABSPATH')) exit;  // Exit if accessed directly

class SmartShop_Admin_Init {

    /**
     * Parent Menu Page Slug
     */
    const MENU_PAGE_SLUG = 'smartshop_page';

    /**
     * Menu capability
     */
    const MENU_CAPABILITY = 'manage_options';

    /**
     * [$parent_menu_hook] Parent Menu Hook
     * @var string
     */
    static $parent_menu_hook = '';

    /**
     * [$_instance]
     * @var null
     */
    private static $_instance = null;

    /**
     * Initializes a singleton instance
     * @return SmartShop_Admin_Init
     */
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
             // Add menu with priority 10 to ensure it appears in the correct order
             add_action('admin_menu', [$this, 'add_menu'], 10);
             add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
     
     
             add_action('wp_ajax_smartshop_module_data', array($this, 'save_data'));

             add_action('wp_ajax_nopriv_smartshop_module_data', array($this, 'module_data'));
    }

 
    /**
     * [include] Load Necessary file
     * @return [void]
     */
    public function include(){
        // require_once( SMARTSHOP_ADDONS_PL_PATH .'incs/api.php');
        // require_once('inc/diagnostic-data.php');
        require_once('inc/Smartshop_Admin_Fields_Manager.php');
        require_once('inc/Smartshop_Admin_Fields.php');
        require_once('inc/template-library.php'); 
    }



    /**
     * [add_menu] Add admin menu and submenu pages
     */
    public function add_menu() {
        // Add the main menu page
        self::$parent_menu_hook = add_menu_page(
            esc_html__('SmartShop', 'samrtshop'), // Page title
            esc_html__('SmartShop', 'samrtshop'), // Menu title
            self::MENU_CAPABILITY,                // Capability
            self::MENU_PAGE_SLUG,                // Menu slug
            [$this, 'main_menu_page_content'],    // Callback function for the menu page
            SMARTSHOP_ADDONS_PL_URL . 'incs/admin/assets/images/icons/menu-bar_20x20.png', // Icon URL
            57                                  // Position (right after Appearance)
        );

        // Add submenu page
        add_submenu_page(
            self::MENU_PAGE_SLUG,                // Parent slug
            esc_html__('Settings', 'samrtshop'),  // Page title
            esc_html__('Settings', 'samrtshop'),  // Menu title
            self::MENU_CAPABILITY,                // Capability
            'samrtshop',                          // Menu slug
            [$this, 'plugin_page']                // Callback function
        );
    }

    /**
     * [main_menu_page_content] Callback for the main menu page
     */
    public function main_menu_page_content() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Welcome to SmartShop', 'samrtshop'); ?></h1>
            <p><?php esc_html_e('This is the main page for the SmartShop plugin. You can customize settings and manage your shop here.', 'samrtshop'); ?></p>

            
            <?php 
              // Retrieve and display the option
              $section = 'smartshop_others_tabs'; // Replace with your section key
              $options = get_option($section);
              
              echo '<div class="wrap">';
              echo '<h1>Options Display</h1>';
              echo '<pre>' . print_r($options, true) . '</pre>';
              echo '</div>';
            ?> 
             
        </div>
        <?php
    }

    /**
     * [load_template] Template load
     * @param  [string] $template template suffix
     * @return [void]
     */
    private static function load_template( $template ) {
        $tmp_file = SMARTSHOP_ADDONS_PL_PATH . 'incs/admin/templates/dashboard-' . $template . '.php';
  
        if ( file_exists( $tmp_file ) ) {
            include_once( $tmp_file );
        }
    }


    /** 
     * [plugin_page] Callback for the submenu page
     */
    public function plugin_page() {
        ?>
        <div class="wrap smartshop-admin-wrapper">
            <div class="smartshop-admin-main-content">
                <?php self::load_template('navs'); ?>
                <div class="smartshop-admin-main-body"> 
                <?php self::load_template('welcome'); ?>
                <?php self::load_template('module'); ?>
                </div>
            </div> 
        </div>
        <?php
    }


 
/**
 * [smartshop_save_opt_data] WP Ajax Callback
 * @return [JSON|Null]
 */

 public function save_data() {
    if ( ! current_user_can( self::MENU_CAPABILITY ) ) {
        error_log('User does not have the required capability.');
        return;
    }

    check_ajax_referer( 'smartshop_save_opt_nonce', 'nonce' );

    // Fetch and clean the input data
    $data     = isset($_POST['data']) ? smartshop_clean($_POST['data']) : [];
    $section  = isset($_POST['section']) ? sanitize_text_field($_POST['section']) : '';
    $fields   = isset($_POST['fields']) ? json_decode(stripslashes($_POST['fields']), true) : [];

    // Debugging: Log the received data
    error_log("Data: " . print_r($data, true));
    error_log("Section: " . print_r($section, true));
    error_log("Fields: " . print_r($fields, true));

    if (empty($section) || empty($fields)) {
        error_log('Section or fields data is missing.');
        return;
    }

    if (empty($data)) {
        $data = [];
    }

    // Ensure the section option exists in the database
    if (false === get_option($section)) {
        add_option($section);
    }

    // Update the options
    foreach ($fields as $field) {
        $value = isset($data[$field]) ? $data[$field] : null;
        error_log("Updating Option: $field with Value: " . print_r($value, true));
        $this->update_option($section, $field, $value);
    }

    wp_send_json_success([
        'message' => esc_html__('Data saved successfully!', 'smartshop'),
        'data'    => $data
    ]);
}

/**
 * Updates a specific option within a section.
 *
 * @param string $section The section of the options.
 * @param string $option_key The specific key within the section.
 * @param mixed  $new_value The value to be saved.
 */
public function update_option($section, $option_key, $new_value) {
    if ($new_value === null) { 
        $new_value = ''; 
    }

    // Fetch the current options for the section
    $options_data = is_array(get_option($section)) ? get_option($section) : [];

    // Update the specific option key with the new value
    $options_data[$option_key] = $new_value;

    // Save the updated options back to the database
    // error_log("Saving Option Data: " . print_r($options_data, true));
    update_option($section, $options_data);
}


    /**
     * [module_data] Wp Ajax Callback
     * @return [JSON|Null]
     */
    public function module_data(){
        // Verify nonce for security
        check_ajax_referer('smartshop_save_opt_nonce', 'nonce');
    
        // Retrieve and sanitize POST data
        $subaction  = !empty($_POST['subaction']) ? sanitize_text_field($_POST['subaction']) : '';
        $section    = !empty($_POST['section']) ? sanitize_text_field($_POST['section']) : '';
        $fields     = !empty($_POST['fields']) ? array_map('sanitize_text_field', (array) $_POST['fields']) : [];
        $fieldname  = !empty($_POST['fieldname']) ? sanitize_text_field($_POST['fieldname']) : '';
    
        // Debug output to PHP error log
        error_log('subaction: ' . $subaction);
        error_log('section: ' . $section);
        error_log('fields: ' . print_r($fields, true));
        error_log('fieldname: ' . $fieldname);
    
        // Handle module data reset
        if ($subaction === 'reset_data') {
            if (!empty($section)) {
                delete_option($section);
            }
        }
    
        // Get module data only if section and fields are provided
        if (empty($section) || empty($fields)) {
            return;
        }
    
        // Fetch module fields based on section or fieldname
        $module_fields = Smartshop_Admin_Fields::instance()->fields()['smartshop_others_tabs']['modules'];


        $section_fields = [];
        foreach ($module_fields as $module) {
    //         echo'<pre>';
    //         echo"hello modal";
    // var_dump($module['section']);
    // echo'<pre>';
            if (isset($module['section']) && $module['section'] === $section) {
                $section_fields = $module['setting_fields'];
                break;
            } else {
                if (isset($module['name']) && $module['name'] === $fieldname) {
                    $section_fields = $module['setting_fields'];
                    break;
                }
            }
        }
    
        $response_content = $message = $field_html = '';
        if ($subaction === 'get_data') {
            foreach ($section_fields as $field) {
                ob_start();
                Smartshop_Admin_Fields_Manager::instance()->add_field($field, $section);
                $field_html .= ob_get_clean();
            }
            $message = esc_html__('Data fetched successfully!', 'smartshop');
            $response_content = $field_html;
        }
    
        wp_send_json_success([
            'message' => $message,
            'content' => $response_content,
            'fields'  => wp_json_encode($fields)
        ]);
    }
    
 
    /**
     * [enqueue_scripts] Add Scripts Base Menu Slug
     * @param  [string] $hook
     * @return [void]
     */
        public function enqueue_scripts( $hook  ) {
            
            if( $hook === 'SmartShop_page_smartshop' || $hook === 'SmartShop_page_smartshop_templates' || $hook ===  'SmartShop_page_smartshop_extension'){
                // wp_enqueue_style('smartshop-sweetalert');
            }
        }
}

// Initialize the admin class
SmartShop_Admin_Init::instance();
 