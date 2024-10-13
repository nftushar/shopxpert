<?php
namespace Shopxpert\Incs\Admin;

use Shopxpert\Incs;
use Shopxpert\Incs\Admin\Inc\Shopxpert_Admin_Fields_Manager;
use Shopxpert\Incs\Admin\Inc\Shopxpert_Admin_Fields;

use function  Shopxpert\incs\shopxpert_clean;

 
if (!defined('ABSPATH')) exit; // Exit if accessed directly


class ShopXpert_Admin_Init {
 
    /**
     * Parent Menu Page Slug
     */
    const MENU_PAGE_SLUG = 'shopxpert_page';

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
     * @return ShopXpert_Admin_Init
     */
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        $this->remove_all_notices();
        $this->include();
        $this->init(); 

    } 

    /**
     * [init] Assets Initializes
     * @return [void]
     */
    public function init(){
             // Add menu with priority 10 to ensure it appears in the correct order
             add_action('admin_menu', [$this, 'add_menu'], 10);
             add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
      
            add_action('admin_footer', [ $this, 'print_Feature_setting_popup' ], 99);

 
            add_action('wp_ajax_shopxpert_save_opt_data', array($this, 'save_data'));

             add_action('wp_ajax_shopxpert_Feature_data', array($this, 'Feature_data'));
    }

 
    /**
     * [include] Load Necessary file
     * @return [void]
     */
    public function include(){
        // require_once( SHOPXPERT_ADDONS_PL_PATH .'incs/api.php');
        // require_once('inc/diagnostic-data.php');
        require_once('inc/Shopxpert_Admin_Fields_Manager.php');
        require_once('inc/Shopxpert_Admin_Fields.php');
        require_once('inc/template-library.php'); 
    }



    /**
     * [add_menu] Add admin menu and submenu pages
     */
    public function add_menu() {
        // Add the main menu page
        self::$parent_menu_hook = add_menu_page(
            esc_html__('ShopXpert ', 'shopxpert'), // Page title
            esc_html__('ShopXpert ', 'shopxpert'), // Menu title
            self::MENU_CAPABILITY,                // Capability
            self::MENU_PAGE_SLUG,                // Menu slug
            [$this, 'main_menu_page_content'],    // Callback function for the menu page
            SHOPXPERT_ADDONS_PL_URL . 'incs/admin/assets/images/icons/menu-bar_20x20.png', // Icon URL
            57                                  // Position (right after Appearance)
        );

        // Add submenu page
        add_submenu_page(
            self::MENU_PAGE_SLUG,                // Parent slug
            esc_html__('Settings', 'shopxpert'),  // Page title
            esc_html__('Settings', 'shopxpert'),  // Menu title
            self::MENU_CAPABILITY,                // Capability
            'shopxpert',                          // Menu slug
            [$this, 'plugin_page']                // Callback function
        );
    }

    /**
     * [main_menu_page_content] Callback for the main menu page
     */
    public function main_menu_page_content() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Welcome to ShopXpert ', 'shopxpert'); ?></h1>
            <p><?php esc_html_e('This is the main page for the ShopXpert  plugin. You can customize settings and manage your shop here.', 'shopxpert'); ?></p>

            
            <?php 
                // Retrieve and display the option
                $section = 'shopxpert_others_tabs'; // Replace with your section key
                $options = get_option($section);

                echo '<div class="wrap">';
                echo '<h1>' . esc_html__('Options Display', 'shopxpert') . '</h1>';

                if ( ! empty( $options ) && is_array( $options ) ) {
                    echo '<pre>' . esc_html( print_r( $options, true ) ) . '</pre>';
                } else {
                    echo '<p>' . esc_html__('No options found.', 'shopxpert') . '</p>';
                }

                echo '</div>';
                ?>
             <hr>
             
        </div>
        <div class="wrap">
            <h1><?php esc_html_e('Welcome to ShopXpert ', 'shopxpert'); ?></h1>
            <p><?php esc_html_e('This is the main page for the ShopXpert  plugin. You can customize settings and manage your shop here.', 'shopxpert'); ?></p>

            
            <?php 
                // Retrieve and display the option
                $section = 'shopxpert_backorder_settings'; // Replace with your section key
                $options = get_option($section);

                echo '<div class="wrap">';
                echo '<h1>ZZ Options Display</h1>';

                // Use esc_html and var_export to safely output the options array without debugging functions.
                echo '<pre>' . esc_html(var_export($options, true)) . '</pre>';
                echo '</div>';
                ?>
             <hr>
             
        </div>
        <?php
    }

    /**
     * [load_template] Template load
     * @param  [string] $template template suffix
     * @return [void]
     */
    private static function load_template( $template ) {
        $tmp_file = SHOPXPERT_ADDONS_PL_PATH . 'incs/admin/templates/dashboard-' . $template . '.php';
  
        if ( file_exists( $tmp_file ) ) {
            include_once( $tmp_file );
        }
    }


    /** 
     * [plugin_page] Callback for the submenu page
     */
    public function plugin_page() {
        ?>
        <div class="wrap shopxpert-admin-wrapper">
            <div class="shopxpert-admin-main-content">
                <?php self::load_template('navs'); ?>
                <div class="shopxpert-admin-main-body"> 
                <?php self::load_template('gutenberg'); ?>

                <?php self::load_template('welcome'); ?>
                <?php self::load_template('Feature'); ?>
                </div>
            </div> 
        </div>
        <?php
    }


        /**
     * [print_Feature_setting_popup] addmin_footer Callback
     * @return [void]
     */
    public function print_Feature_setting_popup() {
        $screen = get_current_screen();
        if ( 'shopxpert_page_shopxpert' == $screen->base ) {
            // // error_log("shopxpert print_Feature_setting_popup 2");
            self::load_template('Feature-setting-popup');
        }
    }


/**
 * [remove_all_notices] remove admin notices
 * @return [void]
 */
public function remove_all_notices() {
add_action('in_admin_header', function (){
    $screen = get_current_screen();
    if ( 'shopxpert_page_shopxpert' == $screen->base ) {
        remove_all_actions('admin_notices'); 
        remove_all_actions('all_admin_notices');
    }
}, 1000); }
 

 
/**
 * [shopxpert_save_opt_data] WP Ajax Callback
 * @return [JSON|Null]
 */

 public function save_data() {
    if ( ! current_user_can( self::MENU_CAPABILITY ) ) {
        error_log('User does not have the required capability.');
        return;
    }

    check_ajax_referer( 'shopxper_nonce_action', 'nonce' );

  
    error_log("Shopxpert hello  save data");


    // Fetch and clean the input data
    $data     = isset($_POST['data']) ? shopxpert_clean($_POST['data']) : [];
    $section  = isset($_POST['section']) ? sanitize_text_field($_POST['section']) : '';
    $fields = isset($_POST['fields']) ? $_POST['fields'] : [];

    // Ensure $fields is an array and process it accordingly
    if (!is_array($fields)) {
        $fields = json_decode(stripslashes($fields), true);
    }
    
    // Error log for debugging
    error_log("sssFields after processing: " . print_r($data, true));
    

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
        error_log("zzzUpdating Option: $field with Value: " . print_r($value, true));
        $this->update_option($section, $field, $value);
    }

    wp_send_json_success([
        'message' => esc_html__('Data saved successfully!', 'shopxper'),
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
    // // error_log("Saving Option Data: " . print_r($options_data, true));
    update_option($section, $options_data);
}
 

    /**
     * [Feature_data] Wp Ajax Callback
     * @return [JSON|Null]
     */
    public function Feature_data() {
        if (!current_user_can(self::MENU_CAPABILITY)) {
            // error_log('User does not have the required capability.');
            return;
        }
        
        check_ajax_referer('shopxper_nonce_action', 'nonce');
    
        // Retrieve and sanitize POST data
        $subaction = isset($_POST['subaction']) ? sanitize_text_field(wp_unslash($_POST['subaction'])) : '';
        $section = isset($_POST['section']) ? sanitize_text_field(wp_unslash($_POST['section'])) : '';
        $fields = isset($_POST['fields']) ? (is_array($_POST['fields']) ? $_POST['fields'] : json_decode(wp_unslash($_POST['fields']), true)) : [];
        $fieldname = isset($_POST['fieldname']) ? sanitize_text_field(wp_unslash($_POST['fieldname'])) : '';
    
        // // error_log(print_r($fields, true)); // Log the fields array
    
        // Handle Feature data reset
        if ($subaction === 'reset_data' && !empty($section)) {
            delete_option($section);
            wp_send_json_success(['message' => 'Data reset successfully']);
        }
    
        // Get Feature data only if section and fields are provided
        if (empty($section) || empty($fields)) {
            wp_send_json_error(['message' => 'Feature_data Section or fields data is missing.']);
            return; // Ensure no further processing is done if validation fails
        }
    
        // Fetch Feature fields based on section or fieldname
        $Feature_fields = Shopxpert_Admin_Fields::instance()->fields()['shopxpert_others_tabs']['features'];
        $section_fields = [];
        foreach ($Feature_fields as $Feature) {
            if (isset($Feature['section']) && $Feature['section'] === $section) {
                $section_fields = $Feature['setting_fields'];
                break;
            } elseif (isset($Feature['name']) && $Feature['name'] === $fieldname) {
                $section_fields = $Feature['setting_fields'];
                break;
            }
        }
    
        $response_content = $message = $field_html = '';
        if ($subaction === 'get_data') {
            foreach ($section_fields as $field) {
                ob_start();
                Shopxpert_Admin_Fields_Manager::instance()->add_field($field, $section);
                $field_html .= ob_get_clean();
            }
            $message = esc_html__('Data Fetch successfully!', 'shopxpert');
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
            
            if( $hook === 'shopxpert_page_shopxpert' || $hook === 'shopxpert_page_shopxpert_templates' || $hook === 'shopxpert_page_shopxpert_extension'){
                  wp_enqueue_style('shopxpert-sweetalert');
            }
        }
}

// Initialize the admin class
ShopXpert_Admin_Init::instance();
 