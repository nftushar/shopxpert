<?php 

namespace SmartShop;
 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
* Assest Management
*/
class Assets_Management{
    
    /**
     * [$instance]
     * @var null
     */
    private static $instance = null;

    /**
     * [instance] Initializes a singleton instance
     * @return [Assets_Management]
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * [__construct] Class Constructor
     */
    function __construct(){

        $this->init();
    }

    /**
     * [init] Init
     * @return [void]
     */
    public function init() {

        // Register Scripts
        add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'register_assets' ] );

        // Elementor Editor Scripts
        add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'enqueue_elementor_editor' ] );

        // Frontend Scripts
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend_scripts' ] );

        add_filter( 'body_class', [ $this, 'body_classes' ] );

    }

    /**
     * [body_classes]
     * @param  [array] $classes
     * @return [array] 
     */    
    public function body_classes( $classes ){

        $current_theme = wp_get_theme();
        $classes[] = 'shopxpert_current_theme_'.$current_theme->get( 'TextDomain' );

        return $classes;
    }
    /**
     * All available styles
     *
     * @return array
     */
    public function get_styles() {
 
        $style_list = [
           
            'shopxpert-admin' => [  

                'src'     => SHOPXPERT_ADDONS_PL_URL . 'incs/admin/assets/css/shopxpert-admin.css',
                'version' => SHOPXPERT_VERSION
            ],
        ];
        return $style_list;

    }

    /**
     * All available scripts
     *
     * @return array
     */
    public function get_scripts() {

        $script_list = [
          
            'shopxpert-condition' => [
                'src'     => SHOPXPERT_ADDONS_PL_URL . 'incs/admin/assets/js/shopxpert-condition.js', 
                'version' => SHOPXPERT_VERSION,
                'deps'    => [ 'jquery'],
            ],


            'shopxpert-admin-main' =>[
                'src'     => SHOPXPERT_ADDONS_PL_URL . 'incs/admin/assets/js/shopxpert-admin.js',
                'version' => SHOPXPERT_VERSION,
                'deps'    => [ 'jquery', 'wp-util', 'serializejson' ]
            ],
            
        ];

        return $script_list;

    }

    /**
     * Register scripts and styles
     *
     * @return void
     */
    function register_assets() {
        $scripts = $this->get_scripts();
        $styles  = $this->get_styles();
    
        // Register and enqueue Scripts
        foreach ($scripts as $handle => $script) {
            $deps = isset($script['deps']) ? $script['deps'] : [];
            wp_register_script($handle, $script['src'], $deps, $script['version'], true);
            wp_enqueue_script($handle);
        } 
    
        // Register and enqueue Styles
        foreach ($styles as $handle => $style) {
            $deps = isset($style['deps']) ? $style['deps'] : [];
            wp_register_style($handle, $style['src'], $deps, $style['version']);
            wp_enqueue_style($handle);
        }
        
        // Localize Scripts
        $localizeargs = array(
            'shopxpertajaxurl' => admin_url('admin-ajax.php'),
            'ajax_nonce'       => 
            ('shopxper_nonce_action'),
        );
        wp_localize_script('shopxper-widgets-scripts', 'shopxper_addons', $localizeargs);
    
        // For Admin
        if (is_admin()) {
            $datalocalize = array(
                'nonce' => wp_create_nonce('shopxper_nonce_action'),
                'ajaxurl' => admin_url('admin-ajax.php'),
                'message' => [
                    'btntxt'  => esc_html__('Save Changes', 'shopxper'),
                    'loading' => esc_html__('Saving...', 'shopxper'),
                    'success' => esc_html__('XX Saved success Data', 'shopxper'),
                    'yes'     => esc_html__('Yes', 'shopxper'),
                    'cancel'  => esc_html__('Cancel', 'shopxper'),
                    'sure'    => esc_html__('Are you sure?', 'shopxper'),
                    'reseting'=> esc_html__('Resetting...', 'shopxper'),
                    'reseted' => esc_html__('Reset All Settings', 'shopxper'),
                ],
                'option_data' => [],
            );
            wp_localize_script('shopxpert-admin-main', 'SMARTSHOP_ADMIN', $datalocalize);
    
            // Localize Scripts For template Library
            $current_user = wp_get_current_user();
            $localize_data = [
                'ajaxurl'          => admin_url('admin-ajax.php'),
                'nonce'            => wp_create_nonce('shopxper_nonce_action'),
                'adminURL'         => admin_url(),
                'elementorURL'     => admin_url('edit.php?post_type=elementor_library'),
                'version'          => SHOPXPERT_VERSION,
                'pluginURL'        => plugin_dir_url(__FILE__),
                'alldata'          => !empty(base::$template_info['templates']) ? base::$template_info['templates'] : array(),
                'prolink'          => 'https://smartshop.com/pricing/?utm_source=admin&utm_medium=library',
                'prolabel'         => esc_html__('Pro', 'shopxper'),
                'loadingimg'       => SHOPXPERT_ADDONS_PL_URL . 'incs/admin/assets/images/loading.gif',
                'message'          => [
                    'packagedesc'=> esc_html__('in this package', 'shopxper'),
                    'allload'    => esc_html__('All Items have been Loaded', 'shopxper'),
                    'notfound'   => esc_html__('Nothing Found', 'shopxper'),
                ],
                'buttontxt'      => [
                    'tmplibrary' => esc_html__('Import to Library', 'shopxper'),
                    'tmppage'    => esc_html__('Import to Page', 'shopxper'),
                    'tmpbuilder' => esc_html__('Import to Builder', 'shopxper'),
                    'import'     => esc_html__('Import', 'shopxper'),
                    'buynow'     => esc_html__('Buy Now', 'shopxper'),
                    'preview'    => esc_html__('Preview', 'shopxper'),
                    'installing' => esc_html__('Installing..', 'shopxper'),
                    'activating' => esc_html__('Activating..', 'shopxper'),
                    'active'     => esc_html__('Active', 'shopxper'),
                ],
                'user'           => [
                    'email' => $current_user->user_email,
                ],
            ];
            wp_localize_script('shopxper-templates', 'WLTM', $localize_data);
            wp_localize_script('shopxper-install-manager', 'WLIM', $localize_data);
        }
    }
    

    /**
     * [enqueue_frontend_scripts Load frontend scripts]
     * @return [void]
     */
    public function enqueue_frontend_scripts() {

        $current_theme = wp_get_theme( 'oceanwp' );
        // CSS File
        if ( $current_theme->exists() ){
            wp_enqueue_style( 'font-awesome-four' );
        }else{
            if( wp_style_is( 'font-awesome', 'registered' ) ){
                wp_enqueue_style( 'font-awesome' );
            }else{
                wp_enqueue_style( 'font-awesome-four' );
            }
        }
        wp_enqueue_style( 'simple-line-icons-wl' );
        wp_enqueue_style( 'htflexboxgrid' );
        wp_enqueue_style( 'slick' );
        wp_enqueue_style( 'shopxper-widgets' );
        
        // If RTL
        if ( is_rtl() ) {
            wp_enqueue_style(  'shopxper-widgets-rtl' );
        }
    }

    

}

Assets_Management::instance();