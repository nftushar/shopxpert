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
                        'name'     => 'rename_label_settings',
                        'label'    => esc_html__( 'Rename Label', 'smartshop' ),
                        'type'     => 'module',
                        'default'  => 'off',
                        'section'  => 'smartshop_rename_label_tabs',
                        'option_id'=> 'enablerenamelabel',
                        'require_settings'=> true,
                        'documentation' => esc_url('https://smartshop.com/doc/change-woocommerce-text/'),
                        'setting_fields' => array(
                            
                            array(
                                'name'  => 'enablerenamelabel',
                                'label' => esc_html__( 'Enable / Disable', 'smartshop' ),
                                'desc'  => esc_html__( 'You can enable / disable rename label from here.', 'smartshop' ),
                                'type'  => 'checkbox',
                                'default' => 'off',
                                'class'   =>'enablerenamelabel smartshop-action-field-left',
                            ),
            
                            array(
                                'name'      => 'shop_page_heading',
                                'headding'  => esc_html__( 'Shop Page', 'smartshop' ),
                                'type'      => 'title',
                                'class'     => 'depend_enable_rename_label',
                            ),
                            
                            array(
                                'name'        => 'wl_shop_add_to_cart_txt',
                                'label'       => esc_html__( 'Add to Cart Button Text', 'smartshop' ),
                                'desc'        => esc_html__( 'Change the Add to Cart button text for the Shop page.', 'smartshop' ),
                                'type'        => 'text',
                                'placeholder' => esc_html__( 'Add to Cart', 'smartshop' ),
                                'class'       => 'depend_enable_rename_label smartshop-action-field-left',
                            ),
            
                            array(
                                'name'      => 'product_details_page_heading',
                                'headding'  => esc_html__( 'Product Details Page', 'smartshop' ),
                                'type'      => 'title',
                                'class'     => 'depend_enable_rename_label',
                            ),
            
                            array(
                                'name'        => 'wl_add_to_cart_txt',
                                'label'       => esc_html__( 'Add to Cart Button Text', 'smartshop' ),
                                'desc'        => esc_html__( 'Change the Add to Cart button text for the Product details page.', 'smartshop' ),
                                'type'        => 'text',
                                'placeholder' => esc_html__( 'Add to Cart', 'smartshop' ),
                                'class'       => 'depend_enable_rename_label smartshop-action-field-left',
                            ),
            
                            array(
                                'name'        => 'wl_description_tab_menu_title',
                                'label'       => esc_html__( 'Description', 'smartshop' ),
                                'desc'        => esc_html__( 'Change the tab title for the product description.', 'smartshop' ),
                                'type'        => 'text',
                                'placeholder' => esc_html__( 'Description', 'smartshop' ),
                                'class'       => 'depend_enable_rename_label smartshop-action-field-left',
                            ),
                            
                            array(
                                'name'        => 'wl_additional_information_tab_menu_title',
                                'label'       => esc_html__( 'Additional Information', 'smartshop' ),
                                'desc'        => esc_html__( 'Change the tab title for the product additional information', 'smartshop' ),
                                'type'        => 'text',
                                'placeholder' => esc_html__( 'Additional information', 'smartshop' ),
                                'class'       => 'depend_enable_rename_label smartshop-action-field-left',
                            ),
                            
                            array(
                                'name'        => 'wl_reviews_tab_menu_title',
                                'label'       => esc_html__( 'Reviews', 'smartshop' ),
                                'desc'        => esc_html__( 'Change the tab title for the product review', 'smartshop' ),
                                'type'        => 'text',
                                'placeholder' => __( 'Reviews', 'smartshop' ),
                                'class'       =>'depend_enable_rename_label smartshop-action-field-left',
                            ),
            
                            array(
                                'name'      => 'checkout_page_heading',
                                'headding'  => esc_html__( 'Checkout Page', 'smartshop' ),
                                'type'      => 'title',
                                'class'     => 'depend_enable_rename_label',
                            ),
            
                            array(
                                'name'        => 'wl_checkout_placeorder_btn_txt',
                                'label'       => esc_html__( 'Place order', 'smartshop' ),
                                'desc'        => esc_html__( 'Change the label for the Place order field.', 'smartshop' ),
                                'type'        => 'text',
                                'placeholder' => esc_html__( 'Place order', 'smartshop' ),
                                'class'       => 'depend_enable_rename_label smartshop-action-field-left',
                            ),

                        )
                    ),
                    array(
                        'name'     => 'wishlist',
                        'label'    => esc_html__( 's Wishlist', 'smartshop' ),
                        'type'     => 'element',
                        'default'  => 'off',
                        'documentation' => esc_url('https://smartshop.com/doc/wishlist-for-woocommerce/')
                    ), 
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