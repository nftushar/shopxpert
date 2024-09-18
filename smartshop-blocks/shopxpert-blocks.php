<?php
if ( ! class_exists( 'ShopXpertBlocks' ) ) :

	/**
	 * Main ShopXpertBlocks Class
	 */
	final class ShopXpertBlocks{

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
				if( is_admin() && class_exists('\Shopxpert_Template_Library_Manager') ){
					self::$pattern_info = \Shopxpert_Template_Library_Manager::instance()->get_gutenberg_patterns_info();
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
			$this->define( 'SHOPXPERT_BLOCK_FILE', __FILE__ );
			$this->define( 'SHOPXPERT_BLOCK_PATH', __DIR__ );
			$this->define( 'SHOPXPERT_BLOCK_URL', plugins_url( '', SHOPXPERT_BLOCK_FILE ) );
			$this->define( 'SHOPXPERT_BLOCK_DIR', plugin_dir_path( SHOPXPERT_BLOCK_FILE ) );
			$this->define( 'SHOPXPERT_BLOCK_ASSETS', SHOPXPERT_BLOCK_URL . '/assets' );
			$this->define( 'SHOPXPERT_BLOCK_TEMPLATE', trailingslashit( SHOPXPERT_BLOCK_DIR . 'includes/templates' ) );
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
			include( SHOPXPERT_BLOCK_PATH . '/vendor/autoload.php' );
		}

		/**
		 * Load dependency class
		 *
		 * @return void
		 */
		private function dependency_class_instance() {
			ShopXpertBlocks\Scripts::instance();
			ShopXpertBlocks\Manage_Styles::instance();
			ShopXpertBlocks\Actions::instance();
			ShopXpertBlocks\Blocks_init::instance();
			if( class_exists('\ShopXpertBlocks\Block_Patterns_init') ){
				\ShopXpertBlocks\Block_Patterns_init::instance();
			}
		}


	}
	
endif;

/**
 * The main function for that returns shopxpertblocks
 *
 */
function shopxpertblocks() {
	if ( ! empty( $_REQUEST['action'] ) && 'elementor' === $_REQUEST['action'] ) {
		return;
	}elseif( class_exists( 'Classic_Editor' ) ){
		return;
	}else{
		return ShopXpertBlocks::instance();
	}
}
shopxpertblocks();
