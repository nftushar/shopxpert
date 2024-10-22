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
                'title' => __( 'ShopXpert Addons', 'shopxpert' ),
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
        error_log('init_widgets called');
    
        // Include widget files here
        require_once plugin_dir_path( __FILE__ ) . '../incs/addons/wb_wishsuite_table.php';
    
        foreach ( $this->widget_list()['common'] as $element_key => $element ) {
            $widget_class = '\ShopXpert\Widgets\\' . self::generate_classname( $element_key ) . '_Widget';
    
            // Log for debugging
            error_log('Attempting to load widget class: ' . $widget_class);
    
            if ( class_exists( $widget_class ) ) {
                \Elementor\Plugin::instance()->widgets_manager->register( new $widget_class() );
                error_log('Registered widget: ' . $element_key);
            } else {
                error_log('Widget class does not exist: ' . $widget_class);
            }
        }
    }
    
    
    public static function generate_classname( $element_key ) {
		$class_name = explode( '_', $element_key );
		$class_name = array_map( 'ucfirst', $class_name );
		$class_name = implode( '_', $class_name );

		return $class_name;
	}
    

    public function widget_list() {
        $is_pro = is_plugin_active('shopxpert-addons-pro/shopxpert_addons_pro.php');

        $widget_list = [
            'common' => [
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
