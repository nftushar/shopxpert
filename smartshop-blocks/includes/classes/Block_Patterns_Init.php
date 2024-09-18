<?php
namespace SmartShopBlocks;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Manage Block Patterns
 */
class Block_Patterns_init {

    /**
     * Reguler Expresion For COMMA
     */
    const REGEX_COMMA_FINDER = '/[\s,]+/';

     /**
     * Reguler Expresion For DOT
     */
    const REGEX_DOT_FINDER = '/\(.+\)/';
    
	/**
     * [$_instance]
     * @var null
     */
    private static $_instance = null;

    /**
     * [instance] Initializes a singleton instance
     * @return [Blocks_init]
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
        $this->register_patterns_category();
        $this->register_patterns();
	}

    /**
     * Register Block Category
     *
     * @return void
     */
    public function register_patterns_category(){
        register_block_pattern_category( 'shoplentor', [ 'label' => __( 'ShopLentor', 'shopxper' ) ] );
    }

    /**
     * Register Pattern
     *
     * @param [type] $pattern
     * @return void
     */
    public function register_pattern( $pattern ) {
        $this->prepare_pattern_data( $pattern );
    }

    /**
     * Register Patterns
     *
     * @return void
     */
    public function register_patterns(){

        if ( ! class_exists( 'WP_Block_Patterns_Registry' ) || ! is_admin() ) {
			return;
		}

        if( !is_array( \SmartShopBlocks::$pattern_info ) && empty( \SmartShopBlocks::$pattern_info ) ){
            return;
        }

        if( !isset( \SmartShopBlocks::$pattern_info['patterns']) ){
            return;
        }

        $pattern_list = \SmartShopBlocks::$pattern_info['patterns'];

        foreach ( $pattern_list as $pattern ){
            $this->register_pattern( $pattern );
        }
    }

    /**
     * Prepare Pattern data for register
     *
     * @param [type] $pattern
     * @return void
     */
    public function prepare_pattern_data( $pattern ){

        $pattern_slug  = str_replace(' ', '-', trim(preg_replace(self::REGEX_DOT_FINDER, '', strtolower( $pattern['title'] ) ) ) );
        $pattern_slug = "smartshop-blocks/" . $pattern_slug.$pattern['id'];

        $pattern_info = [
            'slug'          => $pattern_slug,
            'title'         => $pattern['title'],
            'categories'    => 'shoplentor',
            'keywords'      => $pattern['keywords'],
            'content'       => !empty( $pattern['content'] ) ? $pattern['content'] : '',
            'description'   => '',
            'viewportWidth' => '',
            'blockTypes'    => '',
            'inserter'      => '',
        ];

        // Slug is required.
        if ( empty( $pattern_info['slug'] ) ) {
            _doing_it_wrong(
                'register_block_patterns',
                esc_html( sprintf( __( 'The block pattern fails to register because the "Slug" field is missing in this pattern "%s"', 'shopxper' ), $pattern_info['title'] ) ),
                '6.0.0'
            );
            return;
        }

        // Check already register pattern
        if ( \WP_Block_Patterns_Registry::get_instance()->is_registered( $pattern_info['slug'] ) ) {
            return;
        }

        // Title is required.
        if ( empty( $pattern_info['title'] ) ) {
            _doing_it_wrong(
                'register_block_patterns',
                esc_html( sprintf( __( 'The block pattern fails to register because the "Title" field is missing in this pattern "%s"', 'shopxper' ), $pattern_info['title']) ),
                '6.0.0'
            );
            return;
        }

        // Properties of the array type, parse the data as comma-separated.
        foreach ( ['categories', 'keywords', 'blockTypes'] as $property ) {
            if ( ! empty( $pattern_info[ $property ] ) ) {
                if( !is_array( $pattern_info[ $property ] ) ){
                    $pattern_info[ $property ] = array_filter(
                        preg_split( self::REGEX_COMMA_FINDER, (string) $pattern_info[ $property ] )
                    );
                }
            } else {
                unset( $pattern_info[ $property ] );
            }
        }

        // Parse properties with the data type "int"
        foreach ( ['viewportWidth'] as $property ) {
            if ( ! empty( $pattern_info[ $property ] ) ) {
                $pattern_info[ $property ] = (int) $pattern_info[ $property ];
            } else {
                unset( $pattern_info[ $property ] );
            }
        }

        // Parse properties with the data type "bool"
        foreach ( ['inserter'] as $property ) {
            if ( ! empty( $pattern_info[ $property ] ) ) {
                $pattern_info[ $property ] = in_array( strtolower( $pattern_info[ $property ] ),  ['yes', 'true'], true );
            } else {
                unset( $pattern_info[ $property ] );
            }
        }

        if ( ! $pattern_info['content'] ) {
            return;
        }

        // Register Pattern
        register_block_pattern( $pattern_info['slug'], $pattern_info );

    }


}