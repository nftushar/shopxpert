<?php

namespace Smartshop\Incs\Admin\Inc;
use Smartshop\Incs;
// Ensure the correct function is available
use function Smartshop\smartshop_taxonomy_list;
use function Smartshop\smartshop_post_name;
 

if (!defined('ABSPATH')) exit; 


class Smartshop_Admin_Fields {
    
    private static $_instance = null;

    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    } 



    /**
     * [fields] Admin Fields
     * @return [array] fields 
     */
    public function fields(){

        $settings_fields = array(
 
            'smartshop_others_tabs' => array(

       'modules' => array(
                 
                                array(
                        'name'     => 'wishlist',
                        'label'    => esc_html__( 's Wishlist', 'woolentor' ),
                        'type'     => 'element',
                        'default'  => 'off',
                        'documentation' => esc_url('https://woolentor.com/doc/wishlist-for-woocommerce/')
                    ),

                    array(
                        'name'     => 'compare',
                        'label'    => esc_html__( 'Compare', 'smartshop' ),
                        'type'     => 'element',
                        'default'  => 'off',
                        'documentation' => esc_url('https://smartshop.com/doc/woocommerce-product-compare/')
                    ),
                    
                    array(
                        'name'    => 'ajaxsearch',
                        'label'   => esc_html__( 'Ajax Search Widget', 'smartshop' ),
                        'desc'    => esc_html__( 'AJAX Search Widget', 'smartshop' ),
                        'type'    => 'element',
                        'default' => 'off',
                        'documentation' => esc_url('https://smartshop.com/doc/how-to-use-woocommerce-ajax-search/')
                    ),
    
                    array(
                        'name'     => 'ajaxcart_singleproduct',
                        'label'    => esc_html__( 'Single Product Ajax Add To Cart', 'smartshop' ),
                        'desc'     => esc_html__( 'AJAX Add to Cart on Single Product page', 'smartshop' ),
                        'type'     => 'element',
                        'default'  => 'off',
                        'documentation' => esc_url('https://smartshop.com/doc/single-product-ajax-add-to-cart/')
                    ),

                    array(
                        'name'   => 'smartshop_checkout_field_settingsp',
                        'label'  => esc_html__( 'Checkout Fields Manager', 'smartshop' ),
                        'desc'   => esc_html__( 'Checkout Fields Manager Module', 'smartshop' ),
                        'type'   => 'module',
                        'default'=> 'off',
                        'require_settings' => true,
                        'is_pro' => true
                    ),

                    array(
                        'name'   => 'partial_paymentp',
                        'label'  => esc_html__( 'Partial Payment', 'smartshop' ),
                        'desc'   => esc_html__( 'Partial Payment Module', 'smartshop' ),
                        'type'   => 'module',
                        'default'=> 'off',
                        'require_settings' => true,
                        'is_pro' => true
                    ),

                    array(
                        'name'   => 'pre_ordersp',
                        'label'  => esc_html__( 'Pre Orders', 'smartshop' ),
                        'desc'   => esc_html__( 'Pre Orders Module', 'smartshop' ),
                        'type'   => 'module',
                        'default'=> 'off',
                        'require_settings' => true,
                        'is_pro' => true
                    ),

                    array(
                        'name'   => 'size_chartp',
                        'label'  => esc_html__( 'Size Chart', 'smartshop' ),
                        'desc'   => esc_html__( 'Size Chart Module', 'smartshop' ),
                        'type'   => 'module',
                        'default'=> 'off',
                        'require_settings' => true,
                        'is_pro' => true
                    ),

                    array(
                        'name'    => 'order_bump',
                        'label'   => esc_html__( 'Order Bump', 'smartshop' ),
                        'type'    => 'module',
                        'default' => 'off',
                        'require_settings' => true,
                        'is_pro'  => true
                    ),

                    array(
                        'name'    => 'product_filterp',
                        'label'   => esc_html__( 'Product Filter', 'smartshop' ),
                        'type'    => 'module',
                        'default' => 'off',
                        'require_settings' => true,
                        'is_pro'  => true
                    ),

                    array(
                        'name'     => 'email_customizerp',
                        'label'    => esc_html__( 'Email Customizer', 'smartshop' ),
                        'type'     => 'module',
                        'default'=> 'off',
                        'require_settings' => true,
                        'is_pro' => true
                    ),

                    array(
                        'name'     => 'email_automationp',
                        'label'    => esc_html__( 'Email Automation', 'smartshop' ),
                        'type'     => 'module',
                        'default'=> 'off',
                        'require_settings' => true,
                        'is_pro' => true
                    ),

                    array(
                        'name'   => 'gtm_conversion_trackingp',
                        'label'  => esc_html__( 'GTM Conversion Tracking', 'smartshop' ),
                        'desc'   => esc_html__( 'GTM Conversion Tracking Module', 'smartshop' ),
                        'type'   => 'module',
                        'default'=> 'off',
                        'require_settings' => true,
                        'is_pro' => true
                    ),
                    
                    array(
                        'name'   => 'single_product_sticky_add_to_cartp',
                        'label'  => esc_html__( 'Product sticky Add to cart', 'smartshop' ),
                        'desc'   => esc_html__( 'Sticky Add to Cart on Single Product page', 'smartshop' ),
                        'type'   => 'element',
                        'default'=> 'off',
                        'is_pro' => true
                    ),
    
                    array(
                        'name'   => 'mini_side_cartp',
                        'label'  => esc_html__( 'Side Mini Cart', 'smartshop' ),
                        'type'   => 'element',
                        'default'=> 'off',
                        'is_pro' => true
                    ),

                    array(
                        'name'   => 'redirect_add_to_cartp',
                        'label'  => esc_html__( 'Redirect to Checkout', 'smartshop' ),
                        'type'   => 'element',
                        'default'=> 'off',
                        'is_pro' => true
                    ),
    
                    array(
                        'name'   => 'multi_step_checkoutp',
                        'label'  => esc_html__( 'Multi Step Checkout', 'smartshop' ),
                        'type'   => 'element',
                        'default'=> 'off',
                        'is_pro' => true
                    )

                ),


                'others' => array(

                    array(
                        'name'  => 'loadproductlimit',
                        'label' => esc_html__( 'Load Products in Elementor Addons', 'smartshop' ),
                        'desc'  => esc_html__( 'Set the number of products to load in Elementor Addons', 'smartshop' ),
                        'min'               => 1,
                        'max'               => 100,
                        'step'              => '1',
                        'type'              => 'number',
                        'default'           => '20',
                        'sanitize_callback' => 'floatval'
                    )

                ),

            ),
 
        );
 
    
        return apply_filters( 'smartshop_admin_fields', $settings_fields );

    }

        /**
     * [field_sections] Admin Fields section
     * @return [array] section 
     */

    public function field_sections(){

        $sections = array(

            array(
                'id'    => 'smartshop_general_tabs',
                'title' => esc_html__( 'x General', 'smartshop' ),
                'icon'  => 'dashicons-admin-home'
            ),

            array(
                'id'    => 'smartshop_woo_template_tabs',
                'title' => esc_html__( 'WooCommerce Template', 'smartshop' ),
                'icon'  => 'wli-store'
            ),

            array(
                'id'    => 'smartshop_gutenberg_tabs',
                'title' => esc_html__( 'Gutenberg', 'smartshop' ),
                'icon'  => 'wli-cog'
            ),

            array(
                'id'    => 'smartshop_elements_tabs',
                'title' => esc_html__( 'Elements', 'smartshop' ),
                'icon'  => 'wli-images'
            ),

            array(
                'id'    => 'smartshop_others_tabs',
                'title' => esc_html__( 'Modules', 'smartshop' ),
                'icon'  => 'wli-grid'
            ),

            array(
                'id'    => 'smartshop_style_tabs',
                'title' => esc_html__( 'Style', 'smartshop' ),
                'icon'  => 'wli-tag'
            ),

            array(
                'id'    => 'smartshop_extension_tabs',
                'title' => esc_html__( 'Extensions', 'smartshop' ),
                'icon'  => 'wli-masonry'
            ),

            array(
                'id'    => 'smartshop_freevspro_tabs',
                'title' => esc_html__( 'Free VS Pro', 'smartshop' ),
                'class' => 'freevspro'
            ),

        );
        return apply_filters( 'smartshop_admin_fields_sections', $sections );

    }
}