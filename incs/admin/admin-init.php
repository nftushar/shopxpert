<?php

namespace ShopXpert\Admin;

use ShopXpert\Cache\Manager as CacheManager;

use function ShopXpert\shopxpert_clean;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class ShopXpert_Admin_Init
{

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
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct()
    {
        $this->remove_all_notices();
        $this->include();
        $this->init();
    }

    /**
     * [init] Assets Initializes
     * @return [void]
     */
    public function init()
    {
        add_action('admin_menu', [$this, 'add_menu'], 10);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);

        // Use admin_footer to correctly get the screen object for popup loading
        add_action('admin_footer', [$this, 'print_Feature_setting_popup']);

        add_action('wp_ajax_shopxpert_save_opt_data', [$this, 'save_data']);
        add_action('wp_ajax_shopxpert_Feature_data', [$this, 'handle_shopxpert_Feature_data']);

        // Redirect main menu to the Settings submenu page
        add_action('admin_menu', [$this, 'redirect_to_settings'], 11);
        add_action('admin_init', [$this, 'redirect_to_settings']);

        add_action('admin_init', [$this, 'shopxpert_log_features_status']);

    }

    /**
     * [include] Load Necessary file
     * @return [void]
     */
    public function include()
    {
        // Classes are auto-loaded via composer PSR-4 mapping
        // No require_once needed
    }




        public function shopxpert_log_features_status() {
            $screen = get_current_screen();
            if ( ! $screen || strpos($screen->id, 'shopxpert') === false ) return;

            $features = [ 
                'Pre Order'              => shopxpert_get_option('enable', 'shopxpert_pre_order_settings', 'off'),
                'Wishlist'               => shopxpert_get_option('wishlist', 'shopxpert_others_tabs', 'off'),
                'Fake Order Detection'    => shopxpert_get_option('enable_fake_order_detection', 'shopxpert_fake_order_detection_settings', 'off'),
                'Product Comparison'      => shopxpert_get_option('enable_product_comparison', 'shopxpert_product_comparison_settings', 'off'),
            ];

            foreach ($features as $feature => $status) {
                error_log("ShopXpert Feature: {$feature} Status: {$status}");
            }
        }


 



    /**
     * [add_menu] Add admin menu and submenu pages
     */
    public function add_menu()
    {
        // Add the main menu page
        self::$parent_menu_hook = add_menu_page(
            esc_html__('ShopXpert ', 'shopxpert'),
            esc_html__('ShopXpert ', 'shopxpert'),
            self::MENU_CAPABILITY,
            self::MENU_PAGE_SLUG,
            [$this, 'plugin_page'], // Show the same settings UI on the parent page
            SHOPXPERT_ADDONS_PL_URL . 'incs/admin/assets/images/icons/menu-bar_20x20.png',
            57
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
     * [redirect_to_settings] Redirect the main menu page to the Settings submenu page
     */
    public function redirect_to_settings()
    {
        global $pagenow;

        // If already on the target page, do nothing.
        if ($pagenow === 'admin.php' && isset($_GET['page']) && $_GET['page'] === 'shopxpert') {
            return;
        }

        if ($pagenow === 'admin.php' && isset($_GET['page']) && $_GET['page'] === self::MENU_PAGE_SLUG) {
            wp_redirect(admin_url('admin.php?page=shopxpert'));
            exit;
        }
    }


    /**
     * [load_template] Template load
     * @param  [string] $template template suffix
     * @return [void]
     */
    private static function load_template($template)
    {
        $tmp_file = SHOPXPERT_ADDONS_PL_PATH . 'incs/admin/templates/dashboard-' . $template . '.php';
        if (file_exists($tmp_file)) {
            include_once($tmp_file);
        }
    }


    public function main_menu_page_content()
    {
?>
        <div class="wrap shopxpert-admin-wrapper">
            <div class="shopxpert-admin-main-content">
                <div class="shopxpert-admin-main-body">
                    <?php self::load_template('welcome'); ?>
                </div>
            </div>
        </div>
    <?php
    }


    public function plugin_page()
    {
    ?>
        <div class="wrap shopxpert-admin-wrapper">
            <div class="shopxpert-admin-main-content">
                <?php self::load_template('navs'); ?>
                <div class="shopxpert-admin-main-body">
                    <?php self::load_template('welcome'); ?>
                    <?php self::load_template('feature'); ?>
                </div>
            </div>
        </div>
<?php
    }

  
            public function print_Feature_setting_popup() {
                $screen = get_current_screen();
                if (!$screen) return;

                error_log("Current Screen: " . $screen->base);

                $allowed_screens = ['shopxpert_page_shopxpert', 'shopxpert_page_wishlist']; // add more if needed
                if (in_array($screen->base, $allowed_screens)) {
                    include_once SHOPXPERT_ADDONS_PL_PATH . 'incs/admin/templates/dashboard-feature-setting-popup.php';
                } else {
                    error_log("Screen does not match for popup: " . $screen->base);
                }
            }


    /**
     * [remove_all_notices] remove admin notices
     * @return [void] 
     */
    public function xxremove_all_notices()
    {
        add_action('admin_notices', function () {
            $screen = get_current_screen();
            if ('shopxpert_page_shopxpert' === $screen->base) {
                remove_all_actions('admin_notices');
                remove_all_actions('all_admin_notices');
            }
        }, 0);
    }

    public function remove_all_notices()
    {
        add_action('admin_notices', function () {
            $screen = get_current_screen();

            $current_url = $_SERVER['REQUEST_URI'];
            if ('shopxpert_page_shopxpert' === $screen->base || strpos($current_url, 'admin.php?page=shopxpert_page') !== false) {
                remove_all_actions('admin_notices');
                remove_all_actions('all_admin_notices');
            }
        }, 0); // Ensure this runs early
    }




    /**
     * [shopxpert_save_opt_data] WP Ajax Callback
     * @return [JSON|Null]
     */

    public function save_data()
    {
        if (! current_user_can(self::MENU_CAPABILITY)) {
            error_log('User does not have the required capability.');
            return;
        }

        check_ajax_referer('shopxper_nonce_action', 'nonce');

        // Fetch and clean the input data
        $data     = isset($_POST['data']) ? shopxpert_clean($_POST['data']) : [];
        $section  = isset($_POST['section']) ? sanitize_text_field($_POST['section']) : '';
        $fields = isset($_POST['fields']) ? $_POST['fields'] : [];

        // Ensure $fields is an array and process it accordingly
        if (!is_array($fields)) {
            $fields = json_decode(stripslashes($fields), true);
        }

        error_log('[shopxpert_save_opt_data] section=' . $section . ' fields=' . wp_json_encode($fields) . ' data=' . wp_json_encode($data));

        if (empty($section) || empty($fields)) {
            return;
        }

        if (empty($data)) {
            $data = [];
        }

        // Update the options
        foreach ($fields as $field_entry) {
            $target_section = $section;
            $option_key = $field_entry;

            // New structure: ['key' => option_key, 'section' => target_section]
            if (is_array($field_entry)) {
                $option_key = isset($field_entry['key']) ? $field_entry['key'] : '';
                $target_section = isset($field_entry['section']) && !empty($field_entry['section']) ? $field_entry['section'] : $section;
            }

            if (empty($option_key)) {
                continue;
            }

            // Ensure the section option exists in the database
            if (false === get_option($target_section)) {
                add_option($target_section);
            }

            // Resolve value: support both flattened data (option => value)
            // and nested section data (section => [ option => value ])
            $value = null;
            if ( is_array( $data ) ) {
                if ( isset( $data[ $option_key ] ) ) {
                    $value = $data[ $option_key ];
                } elseif ( isset( $data[ $target_section ] ) && is_array( $data[ $target_section ] ) && isset( $data[ $target_section ][ $option_key ] ) ) {
                    $value = $data[ $target_section ][ $option_key ];
                }
            }

            $this->update_option($target_section, $option_key, $value);
        }

        wp_send_json_success([
            'message' => esc_html__('Data saved successfully!', 'shopxpert'),
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
    public function update_option($section, $option_key, $new_value)
    {
        if ($new_value === null) {
            $new_value = '';
        }

        // Fetch the current options for the section
        $options_data = is_array(get_option($section)) ? get_option($section) : [];

        // Update the specific option key with the new value
        $options_data[$option_key] = $new_value;

        // Save the updated options back to the database 
        update_option($section, $options_data);

        error_log('[shopxpert_save_opt_data] saved section=' . $section . ' key=' . $option_key . ' value=' . wp_json_encode($new_value));
    }


    /**
     * [Feature_data] Wp Ajax Callback
     * @return [JSON|Null]
     */
    public function handle_shopxpert_Feature_data()
    {
        if (!current_user_can(self::MENU_CAPABILITY)) {
            error_log("AJAX request received for shopxpert_Feature_data");
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
        $Feature_fields = \ShopXpert\Admin\Inc\Shopxpert_Admin_Fields::instance()->fields()['shopxpert_others_tabs']['features'];
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
                \ShopXpert\Admin\Inc\Shopxpert_Admin_Fields_Manager::instance()->add_field($field, $section);
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
    public function enqueue_scripts($hook)
    {

        if ($hook === 'shopxpert_page_shopxpert' || $hook === 'shopxpert_page_shopxpert_templates' || $hook === 'shopxpert_page_shopxpert_extension') {
            wp_enqueue_style('shopxpert-sweetalert');


            wp_enqueue_script(
                'select2',
                SHOPXPERT_ADDONS_PL_URL . 'incs/admin/assets/lib/js/select2.min.js',
                array('jquery'),
                SHOPXPERT_VERSION,
                TRUE
            ); 
        }
    }
}

// Initialize the admin class
ShopXpert_Admin_Init::instance();
