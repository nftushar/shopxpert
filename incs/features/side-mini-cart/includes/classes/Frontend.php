<?php
namespace shopxpert\Features\SideMiniCart;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Frontend handlers class
 */
class Frontend {

    /**
     * [$_instance]
     * @var null
     */
    private static $_instance = null;

    /**
     * [instance] Initializes a singleton instance
     * @return [Frontend]
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    /**
     * Initialize the class
     */
    private function __construct() {
        $this->includes();
        $this->init();
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
    }

    /**
     * Load Required files
     *
     * @return void
     */
    private function includes(){
        require_once( __DIR__. '/Frontend/Manage_Mini_Cart.php' );
    }

    /**
     * Initialize
     *
     * @return void
     */
    public function init(){
        Frontend\Manage_Mini_Cart::instance();
    }

    /**
     * Enqueue Scripts
     *
     * @return void
     */
    public function enqueue_scripts(){
        wp_enqueue_style('shopxpert-mini-cart', MODULE_ASSETS . '/css/frontend.css', [], SHOPXPERT_VERSION );
        wp_enqueue_script('shopxpert-mini-cart', MODULE_ASSETS . '/js/frontend.js', ['jquery'], SHOPXPERT_VERSION, true );

        if( isset( $_POST['add-to-cart'] ) ){ $added_to_cart = true; }else{ $added_to_cart = false;}
        $hide_mini_cart = shopxpert_get_option_pro( 'empty_mini_cart_hide', 'shopxpert_others_tabs', 'off' );

        $localize_data = [
            'addedToCart'  => $added_to_cart,
            'hideMiniCart' => $hide_mini_cart == 'on' ? true : false,
        ];
        wp_localize_script( 'shopxpert-mini-cart', 'shopxpertMiniCart', $localize_data );


        // Inline CSS
        wp_add_inline_style( 'shopxpert-mini-cart', $this->inline_style() );

    }

    /**
     * [inline_style]
     * @return [string]
     */
    public function inline_style(){

        $icon_color     = shopxpert_generate_css_pro('mini_cart_icon_color','shopxpert_others_tabs','color');
        $icon_bg        = shopxpert_generate_css_pro('mini_cart_icon_bg_color','shopxpert_others_tabs','background-color');
        $icon_border    = shopxpert_generate_css_pro('mini_cart_icon_border_color','shopxpert_others_tabs','border-color');

        $counter_color      = shopxpert_generate_css_pro('mini_cart_counter_color','shopxpert_others_tabs','color');
        $counter_bg_color   = shopxpert_generate_css_pro('mini_cart_counter_bg_color','shopxpert_others_tabs','background-color');

        $button_color      = shopxpert_generate_css_pro('mini_cart_buttons_color','shopxpert_others_tabs','color');
        $button_bg_color   = shopxpert_generate_css_pro('mini_cart_buttons_bg_color','shopxpert_others_tabs','background-color');

        $button_hover_color     = shopxpert_generate_css_pro('mini_cart_buttons_hover_color','shopxpert_others_tabs','color');
        $button_hover_bg_color  = shopxpert_generate_css_pro('mini_cart_buttons_hover_bg_color','shopxpert_others_tabs','background-color');

        $custom_css = "
            .shopxpert_mini_cart_icon_area{
                {$icon_color}
                {$icon_bg}
                {$icon_border}
            }
            .shopxpert_mini_cart_counter{
                {$counter_color}
                {$counter_bg_color}
            }
            .shopxpert_button_area a.button{
                {$button_color}
                {$button_bg_color}
            }
            .shopxpert_button_area a.button:hover{
                {$button_hover_color}
            }
            .shopxpert_button_area a::before{
                {$button_hover_bg_color}
            }
        ";

        return $custom_css;

    }


}