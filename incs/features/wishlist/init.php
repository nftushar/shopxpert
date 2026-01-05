<?php
 
 use function Shopxpert\incs\shopxpert_get_option;
 
 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
 
 
/**
 * Plugin Main Class
 */
final class Shopxpert_WishList_Base{

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
    
    /**
     * [__construct] Class Constructor
     */
    private function __construct(){
        $this->define_constants();
        $this->incs();
        if( get_option('shopxpert_wishList_status', 'no') === 'no' ){
            add_action( 'wp_loaded',[ $this, 'activate' ] );
            update_option( 'shopxpert_wishList_status','yes' );
        }
        $this->init_plugin();
    }

    /**
     * Define the required plugin constants
     *
     * @return void
     */
    public function define_constants() {
        define( 'WISHLIST_FILE', __FILE__ );
        define( 'WISHLIST_MODULE_PATH', __DIR__ );
        define( 'WISHLIST_URL', plugins_url( '', WISHLIST_FILE ) );
        define( 'WISHLIST_DIR', plugin_dir_path( WISHLIST_FILE ) );
        define( 'WISHLIST_ASSETS', WISHLIST_URL . '/assets' );
        define( 'WISHLIST_BASE', plugin_basename( WISHLIST_FILE ) );
        define( 'WISHLIST_BLOCKS_PATH', WISHLIST_MODULE_PATH. "/incs/blocks" );
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
        WishList\Assets::instance();

        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            WishList\Ajax::instance();
        }

        if ( is_admin() ) {
            WishList\Admin::instance();
        }
        WishList\Frontend::instance();
        WishList\Widgets_And_Blocks::instance();

        // add image size
        $this->set_image_size();

        // let's filter the woocommerce image size
        add_filter( 'woocommerce_get_image_size_wishlist-image', [ $this, 'wc_image_filter_size' ], 10, 1 );
        

    }

    /**
     * Do stuff upon plugin activation
     *
     * @return void
     */
    public function activate() {
        $installer = new WishList\Installer();
        $installer->run();
    }

    /**
     * [set_image_size] Set Image Size
     */
    public function set_image_size(){

        $image_dimention = shopxpert_get_option( 'image_size', 'wishlist_table_settings_tabs', array( 'width'=>80,'height'=>80 ) );
        if( isset( $image_dimention ) && is_array( $image_dimention ) ){
            $hard_crop = !empty( shopxpert_get_option( 'hard_crop', 'wishlist_table_settings_tabs' ) ) ? true : false;
            add_image_size( 'wishlist-image', absint( $image_dimention['width'] ), absint( $image_dimention['height'] ), $hard_crop );
        }

    }

    /**
     * [wc_image_filter_size]
     * @return [array]
     */
    public function wc_image_filter_size(){

$image_dimention = WishList_get_option( 'image_size', 'wishlist_table_settings_tabs', array( 'width'=>80,'height'=>80 ) );
            $hard_crop = !empty( WishList_get_option( 'hard_crop', 'wishlist_table_settings_tabs' ) ) ? true : false;

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
 * @return Shopxpert_WishList_Base
 */
function Shopxpert_WishList() {
    return Shopxpert_WishList_Base::instance();
}
Shopxpert_WishList();