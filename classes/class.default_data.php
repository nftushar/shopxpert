<?php
/**
* ShopXpert_Default_Data
*/
class ShopXpert_Default_Data{

    /**
     * [$instance]
     * @var null
     */
    private static $instance   = null;

    /**
     * [$product_id]
     * @var null
     */
    private static $product_id = null;

    /**
     * [instance] Initializes a singleton instance
     * @return [Assets_Management]
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * [__construct] Class Constructor
     */
    function __construct(){
        add_action( 'init', [ $this, 'init'] );
        // Elementor hook removed
    }

    /**
     * [init] Initialize Function
     * @return [void]
     */
    public function init(){
        add_filter( 'body_class', [ $this, 'body_class' ] );
        add_filter( 'post_class', [ $this, 'post_class' ] );
    }

 

    /**
     * [body_class] Body Classes
     * @param  [array] $classes body class list
     * @return [void] 
     */
    public function body_class( $classes ){
        return $classes;
    }

    /**
     * [post_class] Post Classes
     * @param  [array] $classes post class list
     * @return [void]
     */
    public function post_class( $classes ){
        return $classes;
    }

    /**
     * [get_product] get product
     * @param  [string] $post_type post type
     * @return [object]
     */
    public function get_product( $post_type ) {

		global $product;

		if( 'product' == $post_type ) {
			return $product;
		}

		$product = wc_get_product( 0 );

		return empty( $product ) ? null : $product;

	}

    /**
     * [theme_hook_reactive]
     * @param  [object] $element
     * @param [int] $section_id
     */
    public function theme_hook_reactive( $element, $section_id ){
        // Elementor editor specific code removed
    }

    /**
     * [theme_hooks]
     * @return [void]
     */
    public function theme_hooks( $name = '' ){
        // Elementor specific theme hooks removed
    }

    /**
     * [default] Show Default data in Elementor Editor Mode
     * @param  string $addons   Addon Name
     * @param  array  $settings Addon Settings
     * @return [html] 
     */
    public function default( $addons = '', $settings = array() ){
        return '';
    }

    /**
     * [product_content]
     * @param  [string] $content
     * @return [string] 
     */
    public function product_content( $content ){
        return $content;
    }

    /**
     * Product review tab empty content for elementor editor mode
     *
     * @return void
     */
    public function product_review_tab(){
        return null;
    }

}
ShopXpert_Default_Data::instance();