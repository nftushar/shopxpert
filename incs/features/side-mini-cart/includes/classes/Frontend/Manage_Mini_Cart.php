<?php
namespace shopxpert\Features\SideMiniCart\Frontend;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Manage_Mini_Cart {
    private static $_instance = null;

    /**
     * Get Instance
     */
    public static function instance(){
        if( is_null( self::$_instance ) ){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct(){
        add_action( 'shopxpert_footer_render_content', [ $this, 'mini_cart' ] );
        add_action( 'shopxpert_cart_content', [ $this, 'get_cart_item' ] );
        add_filter( 'woocommerce_add_to_cart_fragments', [ $this,'wc_add_to_cart_fragment' ], 10, 1 );
    }

    /**
     * Mini Cart Template
     * @return void
     */
    public function mini_cart(){
        require( SHOPXPERT_TEMPLATE_PRO .'tmp-mini_cart.php' );
    }

    /**
     * [get_cart_item] Render fragment cart item
     * @return [html]
     */
    public function get_cart_item(){

        $args = [];
        ob_start();
        $mini_cart_tmp_id = method_exists( 'shopxpert_Template_Manager', 'get_template_id' ) ? \shopxpert_Template_Manager::instance()->get_template_id( 'mini_cart_layout', 'shopxpert_get_option_pro' ) : '0';
        if( !empty( $mini_cart_tmp_id ) ){
            echo method_exists('shopxpert_Manage_WC_Template','render_build_content') ? \shopxpert_Manage_WC_Template::render_build_content( $mini_cart_tmp_id, true ) : '';
        }else{
            wc_get_template( 'tmp-mini_cart_content.php', $args, '', SHOPXPERT_TEMPLATE_PRO );
        }
        echo ob_get_clean();

    }

    /**
     * Cart Item HTML Return For fragment.
     */
    public function cart_item_html(){
        ob_start();
        $this->get_cart_item();
        return ob_get_clean();
    }

    /**
     * [wc_add_to_cart_fragment] add to cart freagment callable
     * @param  [type] $fragments
     * @return [type] $fragments
     */
    public function wc_add_to_cart_fragment( $fragments ){

        $item_count = WC()->cart->get_cart_contents_count();
        $cart_item = $this->cart_item_html();

        // Cart Item
        $fragments['div.shopxpert_cart_content_container'] = '<div class="shopxpert_cart_content_container">'.$cart_item.'</div>';

        //Cart Counter
        $fragments['span.shopxpert_mini_cart_counter'] = '<span class="shopxpert_mini_cart_counter">'.$item_count.'</span>';

        return $fragments;
    }
    

}