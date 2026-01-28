<?php 

namespace ShopXpert\Classes;
 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
* Asset Management Data
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
           
            'shopxpert -admin' => [  

                'src'     => SHOPXPERT_ADDONS_PL_URL . 'incs/admin/assets/css/shopxpert -admin.css',
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
            'ajax_nonce'       => wp_create_nonce('shopxper_nonce_action'),
        );
        wp_localize_script('shopxpert-widgets-scripts', 'shopxper_addons', $localizeargs);
    
// For Admin
if (is_admin()) {
    // Create the nonce
    $nonce = wp_create_nonce('shopxper_save_opt_nonce');

    // Localize the script with nonce and other data
    $datalocalize = array(
        'nonce' => $nonce,
        'ajaxurl' => admin_url('admin-ajax.php'),
        'message' => [
            'btntxt'  => esc_html__('Save Changes', 'shopxpert'),
            'loading' => esc_html__('Saving...', 'shopxpert'),
            'success' => esc_html__('successfully Saved', 'shopxpert'),
            'yes'     => esc_html__('Yes', 'shopxpert'),
            'cancel'  => esc_html__('Cancel', 'shopxpert'),
            'sure'    => esc_html__('Are you sure?', 'shopxpert'),
            'reseting'=> esc_html__('Resetting...', 'shopxpert'),
            'reseted' => esc_html__('Reset All Settings', 'shopxpert'),
        ],
        'option_data' => [],
    );
    wp_localize_script('shopxpert-admin-main', 'SHOPXPERT_ADMIN', $datalocalize);
    
    // Localize additional scripts as needed
    $current_user = wp_get_current_user();
    $localize_data = [
        'ajaxurl'          => admin_url('admin-ajax.php'),
        'nonce'            => $nonce,
        'adminURL'         => admin_url(),
        'version'          => SHOPXPERT_VERSION,
        'pluginURL'        => plugin_dir_url(__FILE__),
        'alldata'          => !empty(base::$template_info['templates']) ? base::$template_info['templates'] : array(),
        'prolink'          => 'https://#/pricing/?utm_source=admin&utm_medium=library',
        'prolabel'         => esc_html__('Pro', 'shopxpert'),
        'loadingimg'       => SHOPXPERT_ADDONS_PL_URL . 'incs/admin/assets/images/loading.gif',
        'message'          => [
            'packagedesc'=> esc_html__('in this package', 'shopxpert'),
            'allload'    => esc_html__('All Items have been Loaded', 'shopxpert'),
            'notfound'   => esc_html__('Nothing Found', 'shopxpert'),
        ],
        'buttontxt'      => [
            'tmplibrary' => esc_html__('Import to Library', 'shopxpert'),
            'tmppage'    => esc_html__('Import to Page', 'shopxpert'),
            'tmpbuilder' => esc_html__('Import to Builder', 'shopxpert'),
            'import'     => esc_html__('Import', 'shopxpert'),
            'buynow'     => esc_html__('Buy Now', 'shopxpert'),
            'preview'    => esc_html__('Preview', 'shopxpert'),
            'installing' => esc_html__('Installing..', 'shopxpert'),
            'activating' => esc_html__('Activating..', 'shopxpert'),
            'active'     => esc_html__('Active', 'shopxpert'),
        ],
        'user'           => [
            'email' => $current_user->user_email,
        ],
    ];
    // Elementor template/install manager removed
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
        wp_enqueue_style( 'shopxpert-widgets' );
        
        // If RTL
        if ( is_rtl() ) {
            wp_enqueue_style(  'shopxpert-widgets-rtl' );
        }
    }

    /**
     * Elementor editor assets removed
     */
    

}

Assets_Management::instance();