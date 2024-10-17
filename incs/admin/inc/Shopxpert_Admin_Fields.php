<?php

namespace Shopxpert\Incs\Admin\Inc;


use Shopxpert\Incs;
// Ensure the correct function is available
use function Shopxpert\shopxpert_taxonomy_list;
use function Shopxpert\shopxpert_post_name;
 

if (!defined('ABSPATH')) exit; 


class Shopxpert_Admin_Fields {
    
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
            'shopxpert_gutenberg_tabs' => array(

                'settings' => array(

                    array(
                        'name'    => 'css_add_via',
                        'label'   => esc_html__( 'zzzz Add CSS through', 'shopxpert' ),
                        'desc'    => esc_html__( 'Choose how you want to add the newly generated CSS.', 'shopxpert' ),
                        'type'    => 'select',
                        'default' => 'internal',
                        'options' => array(
                            'internal' => esc_html__('Internal','shopxpert'),
                            'external' => esc_html__('External','shopxpert'),
                        )
                    ),

                    array(
                        'name'  => 'container_width',
                        'label' => esc_html__( 'xxxx Container Width', 'shopxpert' ),
                        'desc'  => esc_html__( 'You can set the container width from here.', 'shopxpert' ),
                        'min'               => 1,
                        'max'               => 10000,
                        'step'              => '1',
                        'type'              => 'number',
                        'default'           => '1140',
                        'sanitize_callback' => 'floatval'
                    ),

                ),

                'blocks' => array(

                    array(
                        'name'      => 'general_blocks_heading',
                        'headding'  => esc_html__( 'zz General', 'shopxpert' ),
                        'type'      => 'title',
                        'class'     => 'shopxpert_heading_style_two'
                    ),

                    array(
                        'name'    => 'product_tab',
                        'label'   => esc_html__( 'xxxxProduct Tab', 'shopxpert' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),
                )
            ),

            'shopxpert_others_tabs' => array(

                'features' => array( 
                    array(
                        'name'     => 'rename_label_settings',
                        'label'    => esc_html__( 'Change Label', 'shopxpert' ),
                        'type'     => 'Feature',
                        'default'  => 'off',
                        'section'  => 'shopxpert_rename_label_tabs',
                        'option_id'=> 'enablerenamelabel',
                        'require_settings'=> true,
                        'documentation' => esc_url('https://shopxpert.com/doc/change-woocommerce-text/'),
                        'setting_fields' => array(
                            
                            array(
                                'name'  => 'enablerenamelabel',
                                'label' => esc_html__( 'Enable / Disable', 'shopxpert' ),
                                'desc'  => esc_html__( 'You can enable / disable change Label from here.', 'shopxpert' ),
                                'type'  => 'checkbox',
                                'default' => 'off',
                                'class'   =>'enablerenamelabel shopxpert-action-field-left',
                            ),
            
                            array(
                                'name'      => 'shop_page_heading',
                                'headding'  => esc_html__( 'Shop Page', 'shopxpert' ),
                                'type'      => 'title',
                                'class'     => 'depend_enable_rename_label',
                            ),
                            
                            array(
                                'name'        => 'wl_shop_add_to_cart_txt',
                                'label'       => esc_html__( 'Add to Cart Button Text', 'shopxpert' ),
                                'desc'        => esc_html__( 'Change the Add to Cart button text for the Shop page.', 'shopxpert' ),
                                'type'        => 'text',
                                'placeholder' => esc_html__( 'Add to Cart', 'shopxpert' ),
                                'class'       => 'depend_enable_rename_label shopxpert-action-field-left',
                            ),
            
                            array(
                                'name'      => 'product_details_page_heading',
                                'headding'  => esc_html__( 'Product Details Page', 'shopxpert' ),
                                'type'      => 'title',
                                'class'     => 'depend_enable_rename_label',
                            ),
            
                            array(
                                'name'        => 'wl_add_to_cart_txt',
                                'label'       => esc_html__( 'Add to Cart Button Text', 'shopxpert' ),
                                'desc'        => esc_html__( 'Change the Add to Cart button text for the Product details page.', 'shopxpert' ),
                                'type'        => 'text',
                                'placeholder' => esc_html__( 'Add to Cart', 'shopxpert' ),
                                'class'       => 'depend_enable_rename_label shopxpert-action-field-left',
                            ),
            
                            array(
                                'name'        => 'wl_description_tab_menu_title',
                                'label'       => esc_html__( 'Description', 'shopxpert' ),
                                'desc'        => esc_html__( 'Change the tab title for the product description.', 'shopxpert' ),
                                'type'        => 'text',
                                'placeholder' => esc_html__( 'Description', 'shopxpert' ),
                                'class'       => 'depend_enable_rename_label shopxpert-action-field-left',
                            ),
                            
                            array(
                                'name'        => 'wl_additional_information_tab_menu_title',
                                'label'       => esc_html__( 'Additional Information', 'shopxpert' ),
                                'desc'        => esc_html__( 'Change the tab title for the product additional information', 'shopxpert' ),
                                'type'        => 'text',
                                'placeholder' => esc_html__( 'Additional information', 'shopxpert' ),
                                'class'       => 'depend_enable_rename_label shopxpert-action-field-left',
                            ),
                            
                            array(
                                'name'        => 'wl_reviews_tab_menu_title',
                                'label'       => esc_html__( 'Reviews', 'shopxpert' ),
                                'desc'        => esc_html__( 'Change the tab title for the product review', 'shopxpert' ),
                                'type'        => 'text',
                                'placeholder' => __( 'Reviews', 'shopxpert' ),
                                'class'       =>'depend_enable_rename_label shopxpert-action-field-left',
                            ),
            
                            array(
                                'name'      => 'checkout_page_heading',
                                'headding'  => esc_html__( 'Checkout Page', 'shopxpert' ),
                                'type'      => 'title',
                                'class'     => 'depend_enable_rename_label',
                            ),
            
                            array(
                                'name'        => 'wl_checkout_placeorder_btn_txt',
                                'label'       => esc_html__( 'Place order', 'shopxpert' ),
                                'desc'        => esc_html__( 'Change the label for the Place order field.', 'shopxpert' ),
                                'type'        => 'text',
                                'placeholder' => esc_html__( 'Place order', 'shopxpert' ),
                                'class'       => 'depend_enable_rename_label shopxpert-action-field-left',
                            ),

                        )
                    ),
                    array(
                        'name'     => 'shopxpert_backorder_settings',
                        'label'    => esc_html__( 'xx Pending Stock', 'shopxpert' ),
                        'type'     => 'Feature',
                        'default'  => 'off',
                        'section'  => 'shopxpert_backorder_settings',
                        'option_id'=> 'enable',
                        'require_settings'  => true,
                        'documentation' => esc_url('https://shopxpert.com/doc/how-to-enable-woocommerce-backorder/'),
                        'setting_fields' => array(
                        
                            array(
                                'name'  => 'enable',
                                'label' => esc_html__( 'Enable / Disable', 'shopxpert' ),
                                'desc'  => esc_html__( 'You can enable / disable backorder Feature from here.', 'shopxpert' ),
                                'type'  => 'checkbox',
                                'default' => 'off',
                                'class' => 'shopxpert-action-field-left'
                            ),

                            array(
                                'name'    => 'backorder_limit',
                                'label'   => esc_html__( 'Pending Stock Limit', 'shopxpert' ),
                                'desc'    => esc_html__( 'Set "Pending Stock Limit" on all "Pending Stock" products across the entire website. You can also set limits for each product individually from the "Inventory" tab.', 'shopxpert' ),
                                'type'    => 'number',
                                'class'   => 'shopxpert-action-field-left'
                            ),

                            array(
                                'name'    => 'backorder_availability_date',
                                'label'   => esc_html__( 'Availability Date', 'shopxpert' ),
                                'type'    => 'date',
                                'class'   => 'shopxpert-action-field-left'
                            ),
                        
                            array(
                                'name'        => 'backorder_availability_message',
                                'label'       => esc_html__( 'Availability Message', 'shopxpert' ),
                                'desc'        => esc_html__( 'Manage how you want the "Message" to appear. Use this {availability_date} placeholder to display the date you set. ', 'shopxpert' ),
                                'type'        => 'text',
                                'default'     => esc_html__( 'On Pending Stock: Will be available on {availability_date}', 'shopxpert' ),
                                'class'       => 'shopxpert-action-field-left',
                            ),
                            
                        )
                        
                    ),
                    array(
                        'name'     => 'wishlist',
                        'label'    => esc_html__( 's Wishlist', 'shopxpert' ),
                        'type'     => 'element',
                        'default'  => 'off',
                        'documentation' => esc_url('https://shopxpert.com/doc/wishlist-for-woocommerce/')
                    ), 
                    array(
                        'name'    => 'ajaxsearch',
                        'label'   => esc_html__( 'Dynamic Search Widget', 'shopxpert' ),
                        'desc'    => esc_html__( 'Dynamic Search Widget', 'shopxpert' ),
                        'type'    => 'element',
                        'default' => 'off',
                        'documentation' => esc_url('https://shopxpert.com/doc/how-to-use-woocommerce-ajax-search/')
                    ),
                ),

                'others' => array(

                    array(
                        'name'  => 'loadproductlimit',
                        'label' => esc_html__( 'Load Products in Elementor Addons', 'shopxpert' ),
                        'desc'  => esc_html__( 'Set the number of products to load in Elementor Addons', 'shopxpert' ),
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
 
    
        return apply_filters( 'shopxpert_admin_fields', $settings_fields );

    }

        /**
     * [field_sections] Admin Fields section
     * @return [array] section 
     */

    public function field_sections(){

        $sections = array(

            array(
                'id'    => 'shopxpert_general_tabs',
                'title' => esc_html__( 'x General', 'shopxpert' ),
                'icon'  => 'dashicons-admin-home'
            ), 
            array(
                'id'    => 'shopxpert_others_tabs',
                'title' => esc_html__( 'Features', 'shopxpert' ),
                'icon'  => 'wli-grid'
            ),
            array(
                'id'    => 'shopxpert_style_tabs',
                'title' => esc_html__( 'Style', 'shopxpert' ),
                'icon'  => 'wli-tag'
            ), 
            array(
                'id'    => 'shopxpert_gutenberg_tabs',
                'title' => esc_html__( 'Gutenberg', 'shopxpert' ),
                'icon'  => 'wli-cog'
            ),
        );
        return apply_filters( 'shopxpert_admin_fields_sections', $sections );

    }
}