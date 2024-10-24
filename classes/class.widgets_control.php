<?php

namespace ShopXpert;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widgets_Control {
    
    private static $instance = null;
    
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    function __construct() {
        $this->init();
    }

    public function init() {
        // Check if Elementor is loaded
        if ( ! did_action( 'elementor/loaded' ) ) {
            return;
        }

        // Register actions
        add_action( 'elementor/elements/categories_registered', [ $this, 'add_category' ] );
        add_action( 'elementor/widgets/register', [ $this, 'init_widgets' ] );
    }

    public function add_category( $elements_manager ) {
        error_log('add_category called');

        $elements_manager->add_category(
            'shopxpert-addons',
            [
                'title' => __( 'NF ShopXpert', 'shopxpert' ),
                'icon'  => 'fa fa-plug',
            ]
        );

        $elements_manager->add_category(
            'shopxpert-addons-pro',
            [
                'title' => __( 'ShopXpert Addons Pro', 'shopxpert-pro' ),
                'icon'  => 'fa fa-plug',
            ]
        );
    }

    public function init_widgets() {
        // Check if post type is 'shopxpert-template' and retrieve template type
        if ( get_post_type() === 'shopxpert-template' ) {
            $tmpType = $this->get_template_type( get_post_meta( get_the_ID(), 'shopxpert_template_meta_type', true ) );
        } else {
            $tmpType = '';
        }
    
        // Ensure 'wp_strip_all_tags' function is available
        if ( !function_exists( 'wp_strip_all_tags' ) ) {
            require_once( ABSPATH . 'wp-includes/formatting.php' );
        }
    
        // Iterate over the widget list based on the template type
        foreach ( $this->widget_list_manager( $tmpType ) as $element_key => $element ) {
    
            // Determine widget file path based on location or default paths
            if ( isset( $element['location'] ) ) {
                $widget_file = trailingslashit( $element['location'] ) . $element_key . '.php';
            } else {
                $widget_path = ( $element['is_pro'] == true ) ? SHOPXPERT_ADDONS_PL_PATH_PRO : SHOPXPERT_ADDONS_PL_PATH;
                $widget_file = $widget_path . 'includes/addons/' . $element_key . '.php';
            }
    
            // Check if the widget is enabled and if the file exists before including it
            if ( ( shopxpert_get_option( $element_key, 'shopxpert_elements_tabs', 'on' ) === 'on' ) && file_exists( $widget_file ) ) {
                require_once( $widget_file );
    
                // Build widget class name dynamically
                $widget_class = '\Elementor\Shopxpert_' . self::generate_classname( $element_key ) . '_Widget';
                
                // Check if the class exists and register it with Elementor
                if ( class_exists( $widget_class ) ) {
                    if ( shopxpert_is_elementor_version( '>=', '3.5.0' ) ) {
                        shopxpert_elementor()->widgets_manager->register( new $widget_class() );
                    } else {
                        shopxpert_elementor()->widgets_manager->register_widget_type( new $widget_class() );
                    }
                } else {
                    error_log( 'Widget class does not exist: ' . $widget_class );
                }
            } else {
                error_log( 'Widget file not found or disabled: ' . $widget_file );
            }
        }
    }
     
    
    public static function generate_classname( $element_key ) {
		$class_name = explode( '_', $element_key );
		$class_name = array_map( 'ucfirst', $class_name );
		$class_name = implode( '_', $class_name );

		return $class_name;
        error_log($class_name);
	}  
        /* Widget list generate */
        public function widget_list_manager( $tmpType ){

            // $is_builder = ( shopxpert_get_option( 'enablecustomlayout', 'shopxpert_woo_template_tabs', 'on' ) == 'on' ) ? true : false;
    
            $common_widget  = $this->widget_list()['common'];
            $builder_common = ( $is_builder == true ) ? $this->widget_list()['builder_common'] : [];
            $template_wise  = ( $is_builder == true && $tmpType !== '' && array_key_exists( $tmpType, $this->widget_list() ) ) ? $this->widget_list()[$tmpType] : [];
    
            $generate_list = [];
    
            if( $tmpType === '' ){
                foreach( $this->widget_list() as $widget_list_key => $widget_list ){
    
                    if( $is_builder == false ){
                        $generate_list = $common_widget;
                    }else{
                        $generate_list += $widget_list;
                    }
                    
                }
            }else{
                $generate_list = array_merge( $template_wise, $common_widget, $builder_common );
            }
    
            return apply_filters( 'shopxpert_load_widget_list', $generate_list, $this->widget_list(), $tmpType );
    
        }

            /* Manage Template type */
    public function get_template_type( $type ){

        switch ( $type ) {

            case 'single':
            case 'quickview':
                $template_type = 'single';
                break;

            case 'shop':
            case 'archive':
                $template_type = 'shop';
                break;

            case 'cart':
                $template_type = 'cart';
                break;

            case 'emptycart':
                $template_type = 'emptycart';
                break;

            case 'minicart':
                $template_type = 'minicart';
                break;

            case 'checkout':
            case 'checkouttop':
                $template_type = 'checkout';
                break;

            case 'myaccount':
            case 'myaccountlogin':
            case 'dashboard':
            case 'orders':
            case 'downloads':
            case 'edit-address':
            case 'edit-account':
                $template_type = 'myaccount';
                break;
            
            case 'lost-password':
            case 'reset-password':
                $template_type = 'lostpassword';
                break;

            case 'thankyou':
                $template_type = 'thankyou';
                break;

            default:
                $template_type = '';

        }

        if ( 0 === strpos($type, 'email') ) {
            $template_type = 'emails';
        }

        return $template_type;

    }

    

    public function widget_list() {
        $is_pro = is_plugin_active('shopxpert-addons-pro/shopxpert_addons_pro.php');

        $widget_list = [
            'common' => [
                'wb_wishsuite_counter' => [
                    'title' => esc_html__('Wishsuite Counter', 'shopxpert'),
                    'is_pro' => false,
                ],
                'wb_wishsuite_table' => [
                    'title' => esc_html__('WishSuite Table', 'shopxpert'),
                    'is_pro' => false,
                ],
            ],
        ];

        return apply_filters( 'shopxpert_widget_list', $widget_list );
    }
}

// Initialize the class
Widgets_Control::instance();
