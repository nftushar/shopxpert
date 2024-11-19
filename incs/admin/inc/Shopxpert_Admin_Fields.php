<?php

namespace Shopxpert\Incs\Admin\Inc;


use Shopxpert\Incs;
// Ensure the correct function is available
use function Shopxpert\shopxpert_taxonomy_list;
use function Shopxpert\shopxpert_post_name;
use function Shopxpert\Incs\shopxpert_get_post_types;
 

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
                        'documentation' => esc_url('https://#/doc/change-woocommerce-text/'),
                        'setting_fields' => array(
                            
                            array(
                                'name'  => 'enablerenamelabel',
                                'label' => esc_html__( 'Enable / Disable', 'shopxpert' ),
                                'desc'  => esc_html__( 'You can enable or disable the label change feature from this section.', 'shopxpert' ),
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
                                'label'       => esc_html__( 'Add to Cart Button Label', 'shopxpert' ),
                                'desc'        => esc_html__( 'Change the Add to Cart Button Label for the Shop page.', 'shopxpert' ),
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
                                'label'       => esc_html__( 'Add to Cart Button Label', 'shopxpert' ),
                                'desc'        => esc_html__( 'Change the Add to Cart Button Label for the Product details page.', 'shopxpert' ),
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
                                'desc'        => esc_html__( 'Easily change the title of the "Additional Information" tab for products.', 'shopxpert' ),
                                'type'        => 'text',
                                'placeholder' => esc_html__( 'Additional information', 'shopxpert' ),
                                'class'       => 'depend_enable_rename_label shopxpert-action-field-left',
                            ),
                            
                            array(
                                'name'        => 'wl_reviews_tab_menu_title',
                                'label'       => esc_html__( 'Reviews', 'shopxpert' ),
                                'desc'        => esc_html__( 'Update the product review tab title.', 'shopxpert' ),
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
                                'desc'        => esc_html__( 'Update or customize the text for the "Place Order" button.', 'shopxpert' ),
                                'type'        => 'text',
                                'placeholder' => esc_html__( 'Place order', 'shopxpert' ),
                                'class'       => 'depend_enable_rename_label shopxpert-action-field-left',
                            ),

                        )
                    ), 
                    array(
                        'name'     => 'pre_orders',
                        'label'    => esc_html__( 'Pre Orders', 'shopxpert' ),
                        'type'     => 'Feature',
                        'default'  => 'off',
                        'section'  => 'shopxpert_pre_order_settings',
                        'option_id'=> 'enablerpreorder',
                        'require_settings'=> true,
                        'documentation' => esc_url('https://#/doc/change-woocommerce-text/'),
                        'setting_fields' => array(
    
                            array(
                                'name'  => 'enable',
                                'label' => esc_html__( 'Enable / Disable', 'shopxpert' ),
                                'desc'  => esc_html__( 'You can enable / disable pre orders from here.', 'shopxpert' ),
                                'type'  => 'checkbox',
                                'default' => 'off',
                                'class' => 'shopxpert-action-field-left'
                            ),
    
                            array(
                                'name'        => 'add_to_cart_btn_text',
                                'label'       => esc_html__( 'Add to Cart Button Label', 'shopxpert' ),
                                'desc'        => esc_html__( 'Easily customize the "Add to Cart" button text for products that allow pre-orders.', 'shopxpert' ),
                                'type'        => 'text',
                                'default'     => esc_html__('Pre Order','shopxpert'),
                                'placeholder' => esc_html__( 'Pre Order', 'shopxpert' ),
                                'class'       => 'shopxpert-action-field-left',
                            ),
    
                            array(
                                'name'        => 'manage_price_lavel',
                                'label'       => esc_html__( 'Price Label Management', 'shopxpert' ),
                                'desc'        => esc_html__( 'Control how price labels are displayed. Leave it blank if you want to show only the pre-order price without any label. You can use the following placeholders: {original_price}, {preorder_price}.', 'shopxpert' ),
                                'default'     => esc_html__( '{original_price} Pre order price: {preorder_price}', 'shopxpert' ),
                                'type'        => 'text',
                                'class'       => 'shopxpert-action-field-left',
                            ),
    
                            array(
                                'name'        => 'availability_date',
                                'label'       => esc_html__( 'Availability date label', 'shopxpert' ),
                                'desc'        => esc_html__( 'Customize how the availability date appears. The following placeholders are available: {availability_date}, {availability_time}.', 'shopxpert' ),
                                'type'        => 'text',
                                'default'     => esc_html__( 'Available on: {availability_date} at {availability_time}', 'shopxpert' ),
                                'class'       => 'shopxpert-action-field-left',
                            ),
    
                            array(
                                'name'  => 'show_countdown',
                                'label' => esc_html__( 'Display Countdown', 'shopxpert' ),
                                'desc'  => esc_html__( 'Enable or disable the countdown for pre-orders using this option.', 'shopxpert' ),
                                'type'  => 'checkbox',
                                'default' => 'on',
                                'class' => 'shopxpert-action-field-left'
                            ),
    
                            array(
                                'name'    => 'countdown_heading_title',
                                'headding'=> esc_html__( 'Countdown Custom Label', 'shopxpert' ),
                                'type'    => 'title',
                                'size'    => 'margin_0 regular',
                                'class'   => 'element_section_title_area',
                                'condition' => array( 'show_countdown', '==', 'true' ),
                            ),
    
                            array(
                                'name'        => 'countdown_end_date',
                                'label'       => esc_html__( 'Countdown End Date', 'shopxpert' ),
                                'type'        => 'date',  // Change this to 'date' or 'datepicker'
                                'default'     => '',
                                'condition'   => array( 'show_countdown', '==', 'true' ),
                                'class'       => 'shopxpert-action-field-left datepicker',
                            ),
                            
    
                        ),
                    ),
       
                    array(
                        'name'     => 'shopxpert_backorder_settings',
                        'label'    => esc_html__( 'Stock on Hold', 'shopxpert' ),
                        'type'     => 'Feature',
                        'default'  => 'off',
                        'section'  => 'shopxpert_backorder_settings',
                        'option_id'=> 'enable',
                        'require_settings'  => true,
                        'documentation' => esc_url('https://#/doc/how-to-enable-woocommerce-backorder/'),
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
                                'label'   => esc_html__( 'Stock on Hold Limit', 'shopxpert' ),
                                'desc'    => esc_html__( 'You can set a "Stock on Hold Limit" for all products marked as "Stock on Hold" across your website. If needed, you can also set a unique limit for each product in the "Inventory" tab.', 'shopxpert' ),
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
                                'desc'        => esc_html__( 'Customize how you want the message to appear. Use {availability_date} to display your selected date. ', 'shopxpert' ),
                                'type'        => 'text',
                                'default'     => esc_html__( 'On Stock on Hold: Will be available on {availability_date}', 'shopxpert' ),
                                'class'       => 'shopxpert-action-field-left',
                            ),
                            
                        )
                    ),

                    array(
                        'name'     => 'wishlist',
                        'label'    => esc_html__( 'Wishlist', 'shopxpert' ),
                        'type'     => 'element',
                        'default'  => 'off',
                        'documentation' => esc_url('https://#/doc/wishlist-for-woocommerce/')
                    ), 
                    
                    array(
                        'name'    => 'ajaxsearch',
                        'label'   => esc_html__( 'Search Widget', 'shopxpert' ),
                        'desc'    => esc_html__( 'Search Widget', 'shopxpert' ),
                        'type'    => 'element',
                        'default' => 'off',
                        'documentation' => esc_url('https://#/doc/how-to-use-woocommerce-ajax-search/')
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

         // Post Duplicator Condition
         if( !is_plugin_active('ht-mega-for-elementor/htmega_addons_elementor.php') ){

            $post_types = shopxpert_get_post_types( array( 'defaultadd' => 'all' ) );
            if ( did_action( 'elementor/loaded' ) && defined( 'ELEMENTOR_VERSION' ) ) {
                $post_types['elementor_library'] = esc_html__( 'Templates', 'shopxpert' );
            }

            $settings_fields['shopxpert_others_tabs']['features'][] = [
                'name'     => 'postduplicator',
                'label'    => esc_html__( 'Post Duplicator', 'shopxpert' ),
                'type'     => 'element',
                'default'  => 'off',
                'require_settings'  => true,
                'documentation' => esc_url('https://#/doc/duplicate-woocommerce-product/'),
                'setting_fields' => array(
                    
                    array(
                        'name'    => 'postduplicate_condition',
                        'label'   => esc_html__( 'Post Duplicator Condition', 'shopxpert' ),
                        'desc'    => esc_html__( 'You can enable duplicator for individual post.', 'shopxpert' ),
                        'type'    => 'multiselect',
                        'default' => '',
                        'options' => $post_types
                    )

                )
            ];

        }

   
    
        return apply_filters( 'shopxpert_admin_fields', $settings_fields );

    }

        /**
     * [field_sections] Admin Fields section
     * @return [array] section 
     */

    public function field_sections(){

        $sections = array( 
            array(
                'id'    => 'shopxpert_others_tabs',
                'title' => esc_html__( 'Features', 'shopxpert' ),
                'icon'  => 'wli-grid'
            ), 
        );
        return apply_filters( 'shopxpert_admin_fields_sections', $sections );

    }
}