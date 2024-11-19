<?php
namespace shopxpert\Features\SideMiniCart;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Widgets class.
 */
class Widgets_And_Blocks {

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
     * Widgets constructor.
     */
    public function __construct() {

        // Elementor Widget
        add_filter( 'shopxpert_widget_list', [ $this, 'widget_list' ] );

        // Guttenberg Block
        add_filter('shopxpert_block_list', [ $this, 'block_list' ] );

    }

    /**
     * Widget list.
     */
    public function widget_list( $widget_list = [] ) {

        $widget_list['minicart']['wl_mini_cart'] = [
            'title'    => esc_html__('Mini Cart','shopxpert'),
            'is_pro'   => true,
            'location' => WIDGETS_PATH,
        ];

        return $widget_list;
    }

    /**
     * Block list.
     */
    public function block_list( $block_list = [] ){

        $block_list['side_mini_cart'] = [
            'label'  => __('xx Side Mini Cart','shopxpert'),
            'name'   => 'shopxpert/side-mini-cart',
            'server_side_render' => true,
            'type'   => 'common',
            'active' => true,
            'location' => BLOCKS_PATH,
        ];

        return $block_list;
    }

}