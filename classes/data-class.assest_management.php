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
        $classes[] = 'smartshop_current_theme_'.$current_theme->get( 'TextDomain' );

        return $classes;
    }
    /**
     * All available styles
     *
     * @return array
     */
    public function get_styles() {
 
        $style_list = [
           
            'smartshop-admin' => [  

                'src'     => SMARTSHOP_ADDONS_PL_URL . 'incs/admin/assets/css/smartshop-admin.css',
                'version' => SMARTSHOP_VERSION
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
            // 'slick' => [
            //     'src'     => SMARTSHOP_ADDONS_PL_URL . 'assets/js/slick.min.js',
            //     'version' => SMARTSHOP_VERSION,
            //     'deps'    => [ 'jquery' ]
            // ],
            // 'countdown-min' => [
            //     'src'     => SMARTSHOP_ADDONS_PL_URL . 'assets/js/jquery.countdown.min.js',
            //     'version' => SMARTSHOP_VERSION,
            //     'deps'    => [ 'jquery' ]
            // ],
            // 'smartshop-accordion-min' => [
            //     'src'     => SMARTSHOP_ADDONS_PL_URL . 'assets/js/accordion.min.js',
            //     'version' => SMARTSHOP_VERSION,
            //     'deps'    => [ 'jquery' ]
            // ],
            // 'select2-min' => [
            //     'src'     => SMARTSHOP_ADDONS_PL_URL . 'assets/js/select2.min.js',
            //     'version' => SMARTSHOP_VERSION,
            //     'deps'    => [ 'jquery' ]
            // ],
            // 'wow' => [
            //     'src'     => SMARTSHOP_ADDONS_PL_URL . 'assets/lib/js/wow.js',
            //     'version' => SMARTSHOP_VERSION,
            //     'deps'    => [ 'jquery' ]
            // ],
            // 'jarallax' => [
            //     'src'     => SMARTSHOP_ADDONS_PL_URL . 'assets/lib/js/jarallax.js',
            //     'version' => SMARTSHOP_VERSION,
            //     'deps'    => [ 'jquery' ]
            // ],
            // 'magnific-popup' => [
            //     'src'     => SMARTSHOP_ADDONS_PL_URL . 'assets/lib/js/magnific-popup.js',
            //     'version' => SMARTSHOP_VERSION,
            //     'deps'    => [ 'jquery' ]
            // ],
            // 'one-page-nav' => [
            //     'src'     => SMARTSHOP_ADDONS_PL_URL . 'assets/lib/js/one-page-nav.js',
            //     'version' => SMARTSHOP_VERSION,
            //     'deps'    => [ 'jarallax','magnific-popup','wow','jquery' ]
            // ],
            // 'smartshop-widgets-scripts' => [
            //     'src'     => SMARTSHOP_ADDONS_PL_URL . 'assets/js/smartshop-widgets-active.js',
            //     'version' => SMARTSHOP_VERSION,
            //     'deps'    => [ 'jquery','slick','wc-add-to-cart-variation' ]
            // ],
            // 'smartshop-ajax-search' => [
            //     'src'     => SMARTSHOP_ADDONS_PL_URL . 'assets/addons/ajax-search/js/ajax-search.js',
            //     'version' => SMARTSHOP_VERSION,
            //     'deps'    => [ 'smartshop-widgets-scripts' ]
            // ],
            // 'jquery-single-product-ajax-cart' =>[
            //     'src'     => SMARTSHOP_ADDONS_PL_URL . 'assets/js/single_product_ajax_add_to_cart.js',
            //     'version' => SMARTSHOP_VERSION,
            //     'deps'    => [ 'jquery' ]
            // ],
            // 'smartshop-flash-sale-module' => [
            //     'src'     => SMARTSHOP_ADDONS_PL_URL . 'incs/modules/flash-sale/assets/js/flash-sale.js',
            //     'version' => SMARTSHOP_VERSION,
            //     'deps'    => [ 'jquery', 'countdown-min' ]
            // ],

            // 'smartshop-jquery-interdependencies' => [
            //     'src'     => SMARTSHOP_ADDONS_PL_URL . 'incs/admin/assets/lib/js/jquery-interdependencies.min.js', 
            //     'version' => SMARTSHOP_VERSION,
            //     'deps'    => [ 'jquery' ],
            // ],
            'smartshop-condition' => [
                'src'     => SMARTSHOP_ADDONS_PL_URL . 'incs/admin/assets/js/smartshop-condition.js', 
                'version' => SMARTSHOP_VERSION,
                'deps'    => [ 'jquery'],
            ],


            'smartshop-admin-main' =>[
                'src'     => SMARTSHOP_ADDONS_PL_URL . 'incs/admin/assets/js/smartshop-admin.js',
                'version' => SMARTSHOP_VERSION,
                'deps'    => [ 'jquery', 'wp-util', 'serializejson' ]
            ],


            // 'smartshop-sweetalert' => [
            //     'src'     => SMARTSHOP_ADDONS_PL_URL . 'incs/admin/assets/lib/js/sweetalert2.min.js',
            //     'version' => SMARTSHOP_VERSION
            // ],
            // 'smartshop-modernizr' => [
            //     'src'     => SMARTSHOP_ADDONS_PL_URL . 'incs/admin/assets/lib/js/modernizr.custom.63321.js',
            //     'version' => SMARTSHOP_VERSION,
            //     'deps'    => [ 'jquery' ]
            // ],
            // 'jquery-selectric' => [
            //     'src'     => SMARTSHOP_ADDONS_PL_URL . 'incs/admin/assets/lib/js/jquery.selectric.min.js',
            //     'version' => SMARTSHOP_VERSION,
            //     'deps'    => [ 'jquery' ]
            // ],
            // 'jquery-ScrollMagic' => [
            //     'src'     => SMARTSHOP_ADDONS_PL_URL . 'incs/admin/assets/lib/js/ScrollMagic.min.js',
            //     'version' => SMARTSHOP_VERSION,
            //     'deps'    => [ 'jquery' ]
            // ],
            // 'babel-min' => [
            //     'src'     => SMARTSHOP_ADDONS_PL_URL . 'incs/admin/assets/lib/js/babel.min.js',
            //     'version' => SMARTSHOP_VERSION,
            //     'deps'    => [ 'jquery' ]
            // ],
            // 'smartshop-templates' => [
            //     'src'     => SMARTSHOP_ADDONS_PL_URL . 'incs/admin/assets/js/template_library_manager.js',
            //     'version' => SMARTSHOP_VERSION,
            //     'deps'    => [ 'jquery', 'wp-util' ]
            // ],
            // 'smartshop-install-manager' => [
            //     'src'     => SMARTSHOP_ADDONS_PL_URL . 'incs/admin/assets/js/install_manager.js',
            //     'version' => SMARTSHOP_VERSION,
            //     'deps'    => [ 'wp-util', 'updates' ]
            // ],
            
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
            'smartshopajaxurl' => admin_url('admin-ajax.php'),
            'ajax_nonce'       => wp_create_nonce('smartshop_nonce_action'),
        );
        wp_localize_script('smartshop-widgets-scripts', 'smartshop_addons', $localizeargs);
    
// For Admin
if (is_admin()) {
    // Create the nonce
    $nonce = wp_create_nonce('smartshop_save_opt_nonce');

    // Localize the script with nonce and other data
    $datalocalize = array(
        'nonce' => $nonce,
        'ajaxurl' => admin_url('admin-ajax.php'),
        'message' => [
            'btntxt'  => esc_html__('Save Changes', 'smartshop'),
            'loading' => esc_html__('Saving...', 'smartshop'),
            'success' => esc_html__('ZZ Saved success Data', 'smartshop'),
            'yes'     => esc_html__('Yes', 'smartshop'),
            'cancel'  => esc_html__('Cancel', 'smartshop'),
            'sure'    => esc_html__('Are you sure?', 'smartshop'),
            'reseting'=> esc_html__('Resetting...', 'smartshop'),
            'reseted' => esc_html__('Reset All Settings', 'smartshop'),
        ],
        'option_data' => [],
    );
    wp_localize_script('smartshop-admin-main', 'SMARTSHOP_ADMIN', $datalocalize);
    
    // Localize additional scripts as needed
    $current_user = wp_get_current_user();
    $localize_data = [
        'ajaxurl'          => admin_url('admin-ajax.php'),
        'nonce'            => $nonce,
        'adminURL'         => admin_url(),
        'elementorURL'     => admin_url('edit.php?post_type=elementor_library'),
        'version'          => SMARTSHOP_VERSION,
        'pluginURL'        => plugin_dir_url(__FILE__),
        'alldata'          => !empty(base::$template_info['templates']) ? base::$template_info['templates'] : array(),
        'prolink'          => 'https://smartshop.com/pricing/?utm_source=admin&utm_medium=library',
        'prolabel'         => esc_html__('Pro', 'smartshop'),
        'loadingimg'       => SMARTSHOP_ADDONS_PL_URL . 'includes/admin/assets/images/loading.gif',
        'message'          => [
            'packagedesc'=> esc_html__('in this package', 'smartshop'),
            'allload'    => esc_html__('All Items have been Loaded', 'smartshop'),
            'notfound'   => esc_html__('Nothing Found', 'smartshop'),
        ],
        'buttontxt'      => [
            'tmplibrary' => esc_html__('Import to Library', 'smartshop'),
            'tmppage'    => esc_html__('Import to Page', 'smartshop'),
            'tmpbuilder' => esc_html__('Import to Builder', 'smartshop'),
            'import'     => esc_html__('Import', 'smartshop'),
            'buynow'     => esc_html__('Buy Now', 'smartshop'),
            'preview'    => esc_html__('Preview', 'smartshop'),
            'installing' => esc_html__('Installing..', 'smartshop'),
            'activating' => esc_html__('Activating..', 'smartshop'),
            'active'     => esc_html__('Active', 'smartshop'),
        ],
        'user'           => [
            'email' => $current_user->user_email,
        ],
    ];
    wp_localize_script('smartshop-templates', 'WLTM', $localize_data);
    wp_localize_script('smartshop-install-manager', 'WLIM', $localize_data);
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
        wp_enqueue_style( 'smartshop-widgets' );
        
        // If RTL
        if ( is_rtl() ) {
            wp_enqueue_style(  'smartshop-widgets-rtl' );
        }
    }

    

}

Assets_Management::instance();