<?php
namespace WishList;

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

        // Guttenberg Block
        add_filter('shopxpert_block_list', [ $this, 'block_list' ] );

    }

    /**
     * Block list.
     */
    public function block_list( $block_list = [] ){

        $block_list['wishlist_table'] = [
            'label'  => __('WishList Table','shopxpert'),
            'name'   => 'shopxpert/wishlist-table',
            'server_side_render' => true,
            'type'   => 'common',
            'active' => true,
            'is_pro' => false,
            'location' => WISHLIST_BLOCKS_PATH,
        ];

        $block_list['wishlist_counter'] = [
            'label'  => __('WishList Counter','shopxpert'),
            'name'   => 'shopxpert/wishlist-counter',
            'server_side_render' => true,
            'type'   => 'common',
            'active' => true,
            'is_pro' => false,
            'location' => WISHLIST_BLOCKS_PATH,
        ];

        return $block_list;
    }

}