<?php
namespace ShopXpertBlocks;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Blocks Assets Manage
 */
class Scripts {

	/**
     * [$_instance]
     * @var null
     */
    private static $_instance = null;

    /**
     * [instance] Initializes a singleton instance
     * @return [Scripts]
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
		add_action( 'enqueue_block_assets', [ $this, 'block_assets' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'block_editor_assets' ] );
	}

	/**
	 * Block assets.
	 */
	public function block_assets() {

		wp_enqueue_script(
		    'shopxpert-block-main',
		    SMARTSHOP_BLOCK_URL . '/assets/js/script.js',
		    array('jquery'),
		    SHOPXPERT_VERSION,
		    true
		);

		wp_enqueue_style(
		    'shopxpert-block-common',
		    SMARTSHOP_BLOCK_URL . '/assets/css/common-style.css',
		    array(),
		    SHOPXPERT_VERSION
		);

		wp_enqueue_style(
		    'shopxpert-block-default',
		    SMARTSHOP_BLOCK_URL . '/assets/css/style-index.css',
		    array(),
		    SHOPXPERT_VERSION
		);

		if ( shopxpertBlocks_Has_Blocks( shopxpertBlocks_get_ID() ) || shopxpertBlocks_is_gutenberg_page() || (is_front_page() || is_home()) ){
			$this->load_css();
		}

	}

	/**
	 * Load CSS File
	 */
	public function load_css(){
		wp_enqueue_style( 'shopxpert-block-style', SMARTSHOP_BLOCK_URL . '/assets/css/blocks.style.build.css', array(), SHOPXPERT_VERSION );
	}

	/**
	 * Block editor assets.
	 */
	public function block_editor_assets() {

		global $pagenow;

		if ( $pagenow !== 'widgets.php' ) {

			wp_enqueue_style( 'font-awesome-four' );
			wp_enqueue_style( 'htflexboxgrid' );
			wp_enqueue_style( 'simple-line-icons-wl' );
			wp_enqueue_style( 'slick' );
			wp_enqueue_style( 'shopxper-widgets' );

			// Third-Party Scripts
			$this->load_extra_scripts();

			wp_enqueue_style( 'shopxpert-block-template-library', SMARTSHOP_BLOCK_URL . '/assets/css/template-library.css', [], SHOPXPERT_VERSION, 'all' );
			wp_enqueue_style( 'shopxpert-block-editor-style', SMARTSHOP_BLOCK_URL . '/assets/css/editor-style.css', [], SHOPXPERT_VERSION, 'all' );

			$dependencies = require_once( SMARTSHOP_BLOCK_PATH . '/build/blocks-shopxpert.asset.php' );
			wp_enqueue_script(
				'shopxpert-blocks',
				SMARTSHOP_BLOCK_URL . '/build/blocks-shopxpert.js',
				$dependencies['dependencies'],
				SHOPXPERT_VERSION,
				true
			);

			/**
			 * Localize data
			 */
			$editor_localize_data = array(
				'url' 		=> SMARTSHOP_BLOCK_URL,
				'ajax' 		=> admin_url('admin-ajax.php'),
				'security' 	=> wp_create_nonce('shopxpertblock-nonce'),
				'locale' 	=> get_locale(),
				'options'	=> $this->get_block_list()['block_list'],
				'templateType'	=> $this->get_block_list()['template_type'],
				'sampledata'	=> is_admin() ? Sample_Data::instance()->get_sample_data( false, 'sampledata/product' ) : array(),
				'prostatus'		=> is_admin() ? is_plugin_active('shopxpert-addons-pro/shopxpert_addons_pro.php') : false,
				'templatelist'	=> is_admin() ? \Smartshop_Template_Library_Manager::get_gutenberg_templates_info() : array(),
				'prolink'		=> 'https://shopxpert.com/pricing/?utm_source=admin&utm_medium=gtlibrary',
			);

			// My Account MenuList
			if( (get_post_type() === 'shopxpert-template') || (basename( $_SERVER['PHP_SELF'] ) === 'site-editor.php') ){
				$editor_localize_data['myaccountmenu'] = function_exists('wc_get_account_menu_items') ? ( wc_get_account_menu_items() + ['customadd' => esc_html__( 'Custom', 'shopxper' )] ) : [];
			}

			wp_localize_script( 'shopxpert-blocks', 'shopxpertData', $editor_localize_data );
		}

	}

	/**
	 * Load Third Party Scripts
	 *
	 * @return void
	 */
	public function load_extra_scripts(){
		if( function_exists('WC') ){
			wp_enqueue_style('woocommerce-layout', \WC()->plugin_url() . '/assets/css/woocommerce-layout.css', false, \Automattic\Jetpack\Constants::get_constant('WC_VERSION'), 'all' );
			if ( ! wp_script_is( 'wc-add-to-cart-variation', 'enqueued' ) ) {
				wp_enqueue_script('wc-add-to-cart-variation', \WC()->plugin_url() . '/assets/js/frontend/add-to-cart-variation.js', array( 'jquery', 'wp-util', 'jquery-blockui' ), \Automattic\Jetpack\Constants::get_constant('WC_VERSION'), 'all' );
			}
		}
		wp_enqueue_style('wishsuite-frontend');
		wp_enqueue_style('evercompare-frontend');
		if( defined('\Shopxpert\Features\CurrencySwitcher\FEATURE_ASSETS') ){
			wp_enqueue_style('shopxpert-currency-switcher', \Shopxpert\Features\CurrencySwitcher\FEATURE_ASSETS . '/css/frontend.css', [], SHOPXPERT_VERSION );
		}

	}

	/**
	 * Manage block based on template type
	 */
	public function get_block_list(){

		$blocks_list 	= Blocks_init::$blocksList;
		$generate_list 	= [];

		// If FSE Screen
		if( basename( $_SERVER['PHP_SELF'] ) === 'site-editor.php' ){
			foreach ( $blocks_list as $key => $block ) {
				$generate_list = array_merge( $generate_list, $blocks_list[$key] );
			}
			return array(
				'block_list' 	=> $generate_list,
				'template_type' => ''
			);
		}
		
		if( get_post_type() === 'shopxpert-template' ){
            $tmpType = Blocks_init::instance()->get_template_type( get_post_meta( get_the_ID(), 'shopxpert_template_meta_type', true ) );
        }else{
            $tmpType = '';
        }

		$is_builder = true;

		$common_block  	= array_key_exists( 'common', $blocks_list ) ? $blocks_list['common'] : [];
        $builder_common = ( $is_builder == true && array_key_exists( 'builder_common', $blocks_list ) ) ? $blocks_list['builder_common'] : [];
        $template_wise  = ( $is_builder == true && $tmpType !== '' && array_key_exists( $tmpType, $blocks_list ) ) ? $blocks_list[$tmpType] : [];

		if( $tmpType === '' ){
			$generate_list = $common_block;
        }else{
            $generate_list = array_merge( $template_wise, $common_block, $builder_common );
        }

		return array(
			'block_list' 	=> $generate_list,
			'template_type' => $tmpType
		);

	}
	
	
}