<?php
if ( ! class_exists( 'SmartShopBlocks' ) ) :

	/**
	 * Main SmartShopBlocks Class
	 */
	final class SmartShopBlocks{

		/**
		 * [$pattern_info]
		 * @var array
		 */
		public static $pattern_info = [];

		/**
		 * [$_instance]
		 * @var null
		 */
		private static $_instance = null;

		/**
		 * [instance] Initializes a singleton instance
		 * @return [Actions]
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * The Constructor.
		 */
		public function __construct() {
			$this->define_constants();
			$this->includes();
			add_action( 'plugins_loaded', [ $this, 'init' ] );
		}

		/**
		 * Initialize
		 */
		public function init(){
			// Pattern Remote Info
			if(wntorBlocks_gutenberg_edit_screen() ){
				if( is_admin() && class_exists('\Smartshop_Template_Library_Manager') ){
					self::$pattern_info = \Smartshop_Template_Library_Manager::instance()->get_gutenberg_patterns_info();
				}
			}
			$this->dependency_class_instance();
		}

		/**
		 * Define the required plugin constants
		 *
		 * @return void
		 */
		public function define_constants() {
			$this->define( 'SMARTSHOP_BLOCK_FILE', __FILE__ );
			$this->define( 'SMARTSHOP_BLOCK_PATH', __DIR__ );
			$this->define( 'SMARTSHOP_BLOCK_URL', plugins_url( '', SMARTSHOP_BLOCK_FILE ) );
			$this->define( 'SMARTSHOP_BLOCK_DIR', plugin_dir_path( SMARTSHOP_BLOCK_FILE ) );
			$this->define( 'SMARTSHOP_BLOCK_ASSETS', SMARTSHOP_BLOCK_URL . '/assets' );
			$this->define( 'SMARTSHOP_BLOCK_TEMPLATE', trailingslashit( SMARTSHOP_BLOCK_DIR . 'includes/templates' ) );
		}

		/**
	     * Define constant if not already set
	     *
	     * @param  string $name
	     * @param  string|bool $value
	     */
	    private function define( $name, $value ) {
	        if ( ! defined( $name ) ) {
	            define( $name, $value );
	        }
	    }

		/**
		 * Load required file
		 *
		 * @return void
		 */
		private function includes() {
			include( SMARTSHOP_BLOCK_PATH . '/vendor/autoload.php' );
		}

		/**
		 * Load dependency class
		 *
		 * @return void
		 */
		private function dependency_class_instance() {
			SmartShopBlocks\Scripts::instance();
			SmartShopBlocks\Manage_Styles::instance();
			SmartShopBlocks\Actions::instance();
			SmartShopBlocks\Blocks_init::instance();
			if( class_exists('\SmartShopBlocks\Block_Patterns_init') ){
				\SmartShopBlocks\Block_Patterns_init::instance();
			}
		}


	}
	
endif;

/**
 * The main function for that returns smartshopblocks
 *
 */
function smartshopblocks() {
	if ( ! empty( $_REQUEST['action'] ) && 'elementor' === $_REQUEST['action'] ) {
		return;
	}elseif( class_exists( 'Classic_Editor' ) ){
		return;
	}else{
		return SmartShopBlocks::instance();
	}
}
smartshopblocks();
