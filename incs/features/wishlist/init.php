<?php

use function  Shopxpert\incs\shopxpert_clean;
use function  Shopxpert\incs\shopxpert_get_option;



if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
error_log('file xxxxx Shopxpert_WooWishSuite_Base');
/**
 * Plugin Main Class
 */
final class Shopxpert_WooWishSuite_Base{

    /**
     * [$_instance]
     * @var null
     */
    private static $_instance = null;

    /**
     * [instance] Initializes a singleton instance
     * @return [Base]
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    /**p
     * [__construct] Class Constructor
     */
    private function __construct(){
        $this->define_constants();
        $this->incs();
        if( get_option('shopxpert_wishsuite_status', 'no') === 'no' ){
            add_action( 'wp_loaded',[ $this, 'activate' ] );
            update_option( 'shopxpert_wishsuite_status','yes' );
        }
        $this->init_plugin();
    }

    /**
     * Define the required plugin constants
     *
     * @return void
     */
    public function define_constants() {
        define( 'WOOWISHSUITE_FILE', __FILE__ );
        define( 'WOOWISHSUITE_FEATURE_PATH', __DIR__ );
        define( 'WOOWISHSUITE_URL', plugins_url( '', WOOWISHSUITE_FILE ) );
        define( 'WOOWISHSUITE_DIR', plugin_dir_path( WOOWISHSUITE_FILE ) );
        define( 'WOOWISHSUITE_ASSETS', WOOWISHSUITE_URL . '/assets' );
        define( 'WOOWISHSUITE_BASE', plugin_basename( WOOWISHSUITE_FILE ) );
        define( 'WOOWISHSUITE_BLOCKS_PATH', WOOWISHSUITE_FEATURE_PATH. "/incs/blocks" );
    }

    /**
     * [incs] Load file
     * @return [void]
     */
    public function incs(){
        require_once(__DIR__ . '/incs/classes/Installer.php');
        require_once(__DIR__ . '/incs/helper-functions.php');
        require_once( __DIR__. '/incs/classes/Manage_Data.php' );
        require_once(__DIR__ . '/incs/classes/Assets.php');
        require_once(__DIR__ . '/incs/classes/Admin.php');
        require_once(__DIR__ . '/incs/classes/Frontend.php');
        require_once(__DIR__ . '/incs/classes/Ajax.php');
        require_once(__DIR__ . '/incs/classes/Widgets_And_Blocks.php');

    }

    /**
     * Initialize the plugin
     *
     * @return void
     */
    public function init_plugin() {

        WooWooWishSuite\Assets::instance();

        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            WooWooWishSuite\Ajax::instance();
        }

        if ( is_admin() ) {
            WooWooWishSuite\Admin::instance();
        }
        WooWooWishSuite\Frontend::instance();
        WooWooWishSuite\Widgets_And_Blocks::instance();

        // add image size
        $this->set_image_size();

        // let's filter the woocommerce image size
        add_filter( 'woocommerce_get_image_size_wishsuite-image', [ $this, 'wc_image_filter_size' ], 10, 1 );
        

    }

    /**
     * Do stuff upon plugin activation
     *
     * @return void
     */
    public function activate() {
        $installer = new WooWooWishSuite\Installer();
        $installer->run();
    }

    /**
     * [set_image_size] Set Image Size
     */
    public function set_image_size(){

        $image_dimention = shopxpert_get_option( 'image_size', 'wishsuite_table_settings_tabs', array( 'width'=>80,'height'=>80 ) );
        if( isset( $image_dimention ) && is_array( $image_dimention ) ){
            $hard_crop = !empty( shopxpert_get_option( 'hard_crop', 'wishsuite_table_settings_tabs' ) ) ? true : false;
            add_image_size( 'wishsuite-image', absint( $image_dimention['width'] ), absint( $image_dimention['height'] ), $hard_crop );
        }

    }

    /**
     * [wc_image_filter_size]
     * @return [array]
     */
    public function wc_image_filter_size(){

        $image_dimention = shopxpert_get_option( 'image_size', 'wishsuite_table_settings_tabs', array( 'width'=>80,'height'=>80 ) );
        $hard_crop = !empty( shopxpert_get_option( 'hard_crop', 'wishsuite_table_settings_tabs' ) ) ? true : false;

        if( isset( $image_dimention ) && is_array( $image_dimention ) ){
            return array(
                'width'  => isset( $image_dimention['width'] ) ? absint( $image_dimention['width'] ) : 80,
                'height' => isset( $image_dimention['height'] ) ? absint( $image_dimention['height'] ) : 80,
                'crop'   => isset( $hard_crop ) ? 1 : 0,
            );
        }
        
    }

}

/**
 * Initializes the main plugin
 *
 * @return Shopxpert_WooWishSuite_Base
 */
function Shopxpert_WooWishSuite() {
    return Shopxpert_WooWishSuite_Base::instance();
}
Shopxpert_WooWishSuite();