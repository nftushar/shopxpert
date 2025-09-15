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
                                'name'  => 'rename_label_section_title',
                                'headding' => 'Change Label',
                                'type'  => 'title',
                                'class' => 'shopxpert-feature-section',
                            ),
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
                                'name'  => 'pre_orders_section_title',
                                'headding' => 'Pre Orders',
                                'type'  => 'title',
                                'class' => 'shopxpert-feature-section',
                            ),
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
                                'name'  => 'stock_on_hold_section_title',
                                'headding' => 'Stock on Hold',
                                'type'  => 'title',
                                'class' => 'shopxpert-feature-section',
                            ),
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
                        'name'     => 'fake_order_detection',
                        'label'    => esc_html__( 'Fake Order Detection', 'shopxpert' ),
                        'type'     => 'Feature',
                        'default'  => 'off',
                        'section'  => 'shopxpert_fake_order_detection_settings',
                        'option_id'=> 'enable_fake_order_detection',
                        'require_settings'=> true,
                        'documentation' => esc_url('https://#/doc/fake-order-detection/'),
                        'setting_fields' => array(
                            array(
                                'name'  => 'fake_order_detection_section_title',
                                'headding' => 'Fake Order Detection',
                                'type'  => 'title',
                                'class' => 'shopxpert-fake-order-section',
                            ),
                            array(
                                'name'  => 'fake_order_detection_info',
                                'type'  => 'html',
                                'desc'  => '<div class="shopxpert-highlight"><span class="dashicons dashicons-info"></span> <strong>' . esc_html__('Tip:', 'shopxpert') . '</strong> ' . esc_html__('Use the blacklist fields to block known spam domains and IPs. Changes take effect immediately.', 'shopxpert') . '</div>',
                            ),
                            array(
                                'name'  => 'enable_fake_order_detection',
                                'label' => esc_html__( 'Enable / Disable', 'shopxpert' ),
                                'desc'  => esc_html__( 'Enable this option to detect and block fake or spam orders in WooCommerce.', 'shopxpert' ),
                                'type'  => 'checkbox',
                                'default' => 'off',
                                'class'   =>'shopxpert-action-field-left shopxpert-toggle',
                            ),
                            array(
                                'name'  => 'fake_order_email_blacklist',
                                'label' => esc_html__( 'Email Domain Blacklist', 'shopxpert' ),
                                'desc'  => esc_html__( 'Enter one suspicious email domain per line (e.g., mailinator.com).', 'shopxpert' ),
                                'type'  => 'textarea',
                                'default' => "mailinator.com\ntempmail.com\n10minutemail.com\nyopmail.com\nguerrillamail.com",
                                'class'   =>'shopxpert-action-field-left',
                            ),
                            array(
                                'name'  => 'fake_order_ip_blacklist',
                                'label' => esc_html__( 'Spam IP Blacklist', 'shopxpert' ),
                                'desc'  => esc_html__( 'Enter one suspicious IP address per line.', 'shopxpert' ),
                                'type'  => 'textarea',
                                'default' => "1.2.3.4\n5.6.7.8",
                                'class'   =>'shopxpert-action-field-left',
                            ),
                            array(
                                'name'  => 'enable_fraud_api',
                                'label' => esc_html__( 'Enable Fraud Scoring API', 'shopxpert' ),
                                'desc'  => esc_html__( 'Enable integration with a third-party fraud scoring API.', 'shopxpert' ),
                                'type'  => 'checkbox',
                                'default' => 'off',
                                'class'   =>'shopxpert-action-field-left shopxpert-toggle',
                            ),
                            array(
                                'name'  => 'fraud_api_key',
                                'label' => esc_html__( 'Fraud API Key', 'shopxpert' ),
                                'desc'  => esc_html__( 'Enter your fraud scoring API key here.', 'shopxpert' ),
                                'type'  => 'text',
                                'default' => '',
                                'class'   =>'shopxpert-action-field-left',
                            ),
                        )
                    ),

                    array(
                        'name'     => 'product_comparison',
                        'label'    => esc_html__( 'Product Comparison', 'shopxpert' ),
                        'type'     => 'Feature',
                        'default'  => 'off',
                        'section'  => 'shopxpert_product_comparison_settings',
                        'option_id'=> 'enable_product_comparison',
                        'require_settings'=> true,
                        'documentation' => esc_url('https://#/doc/product-comparison-for-woocommerce/'),
                        'setting_fields' => array(
                            array(
                                'name'  => 'product_comparison_section_title',
                                'headding' => 'Product Comparison',
                                'type'  => 'title',
                                'class' => 'shopxpert-feature-section',
                            ),
                            array(
                                'name'  => 'enable_product_comparison',
                                'label' => esc_html__( 'Enable / Disable', 'shopxpert' ),
                                'desc'  => esc_html__( 'Enable or disable the Product Comparison feature.', 'shopxpert' ),
                                'type'  => 'checkbox',
                                'default' => 'off',
                                'class' => 'shopxpert-action-field-left',
                            ),
                            array(
                                'name'  => 'show_btn_product_list',
                                'label' => esc_html__( 'Show button in product list page', 'shopxpert' ),
                                'desc'  => esc_html__( 'Show compare button in product list page.', 'shopxpert' ),
                                'type'  => 'checkbox',
                                'default' => 'on',
                                'class' => 'shopxpert-action-field-left',
                            ),
                            array(
                                'name'  => 'show_btn_single_product',
                                'label' => esc_html__( 'Show button in single product page', 'shopxpert' ),
                                'desc'  => esc_html__( 'Show compare button in single product page.', 'shopxpert' ),
                                'type'  => 'checkbox',
                                'default' => 'on',
                                'class' => 'shopxpert-action-field-left',
                            ),
                            array(
                                'name'    => 'shop_btn_position',
                                'label'   => esc_html__( 'Shop page button position', 'shopxpert' ),
                                'desc'    => esc_html__( 'You can manage compare button position in product list page.', 'shopxpert' ),
                                'type'    => 'select',
                                'default' => 'after_cart_btn',
                                'options' => array(
                                    'before_cart_btn' => esc_html__( 'Before Add To Cart', 'shopxpert' ),
                                    'after_cart_btn'  => esc_html__( 'After Add To Cart', 'shopxpert' ),
                                    'top_thumbnail'   => esc_html__( 'Top On Image', 'shopxpert' ),
                                    'use_shortcode'   => esc_html__( 'Use Shortcode', 'shopxpert' ),
                                    'custom_position' => esc_html__( 'Custom Position', 'shopxpert' ),
                                ),
                                'class' => 'shopxpert-action-field-left',
                            ),
                            array(
                                'name'    => 'product_btn_position',
                                'label'   => esc_html__( 'Product page button position', 'shopxpert' ),
                                'desc'    => esc_html__( 'You can manage compare button position in single product page.', 'shopxpert' ),
                                'type'    => 'select',
                                'default' => 'after_cart_btn',
                                'options' => array(
                                    'before_cart_btn' => esc_html__( 'Before Add To Cart', 'shopxpert' ),
                                    'after_cart_btn'  => esc_html__( 'After Add To Cart', 'shopxpert' ),
                                    'after_thumbnail' => esc_html__( 'After Image', 'shopxpert' ),
                                    'after_summary'   => esc_html__( 'After Summary', 'shopxpert' ),
                                    'use_shortcode'   => esc_html__( 'Use Shortcode', 'shopxpert' ),
                                    'custom_position' => esc_html__( 'Custom Position', 'shopxpert' ),
                                ),
                                'class' => 'shopxpert-action-field-left',
                            ),
                            array(
                                'name'  => 'open_popup',
                                'label' => esc_html__( 'Open popup', 'shopxpert' ),
                                'desc'  => esc_html__( 'You can manage the popup window from here.', 'shopxpert' ),
                                'type'  => 'checkbox',
                                'default' => 'on',
                                'class' => 'shopxpert-action-field-left',
                            ),
                            array(
                                'name'  => 'button_text',
                                'label' => esc_html__( 'Button text', 'shopxpert' ),
                                'desc'  => esc_html__( 'Enter your compare button text.', 'shopxpert' ),
                                'type'  => 'text',
                                'default' => esc_html__( 'Compare', 'shopxpert' ),
                                'placeholder' => esc_html__( 'Compare', 'shopxpert' ),
                                'class' => 'shopxpert-action-field-left',
                            ),
                            array(
                                'name'  => 'added_button_text',
                                'label' => esc_html__( 'Added button text', 'shopxpert' ),
                                'desc'  => esc_html__( 'Enter your compare added button text.', 'shopxpert' ),
                                'type'  => 'text',
                                'default' => esc_html__( 'Added', 'shopxpert' ),
                                'placeholder' => esc_html__( 'Added', 'shopxpert' ),
                                'class' => 'shopxpert-action-field-left',
                            ),
                            array(
                                'name'    => 'button_icon_type',
                                'label'   => esc_html__( 'Button icon type', 'shopxpert' ),
                                'desc'    => esc_html__( 'Choose an icon type for the compare button from here.', 'shopxpert' ),
                                'type'    => 'select',
                                'default' => 'default',
                                'options' => array(
                                    'none'     => esc_html__( 'None', 'shopxpert' ),
                                    'default'  => esc_html__( 'Default icon', 'shopxpert' ),
                                    'custom'   => esc_html__( 'Custom icon', 'shopxpert' ),
                                ),
                                'class' => 'shopxpert-action-field-left',
                            ),
                            array(
                                'name'    => 'added_button_icon_type',
                                'label'   => esc_html__( 'Added button icon type', 'shopxpert' ),
                                'desc'    => esc_html__( 'Choose an icon for the added state of the compare button.', 'shopxpert' ),
                                'type'    => 'select',
                                'default' => 'default',
                                'options' => array(
                                    'none'     => esc_html__( 'None', 'shopxpert' ),
                                    'default'  => esc_html__( 'Default icon', 'shopxpert' ),
                                    'custom'   => esc_html__( 'Custom icon', 'shopxpert' ),
                                ),
                                'class' => 'shopxpert-action-field-left',
                            ),
                            array(
                                'name'  => 'enable_shareable_link',
                                'label' => esc_html__( 'Enable shareable link', 'shopxpert' ),
                                'desc'  => esc_html__( 'If you enable this you can easily share your compare page link with specific products.', 'shopxpert' ),
                                'type'  => 'checkbox',
                                'default' => 'off',
                                'class' => 'shopxpert-action-field-left',
                            ),
                            array(
                                'name'  => 'compare_limit',
                                'label' => esc_html__( 'Limit', 'shopxpert' ),
                                'desc'  => esc_html__( 'You can manage your maximum compare quantity from here.', 'shopxpert' ),
                                'type'  => 'number',
                                'default' => 10,
                                'class' => 'shopxpert-action-field-left',
                            ),
                            array(
                                'name'  => 'show_fields_in_table',
                                'label' => esc_html__( 'Show fields in table', 'shopxpert' ),
                                'desc'  => esc_html__( 'Choose which fields should be presented on the product compare page with table.', 'shopxpert' ),
                                'type'  => 'multicheck',
                                'options' => array(
                                    'remove'      => esc_html__( 'Remove', 'shopxpert' ),
                                    'image'       => esc_html__( 'Image', 'shopxpert' ),
                                    'title'       => esc_html__( 'Title', 'shopxpert' ),
                                    'price'       => esc_html__( 'Price', 'shopxpert' ),
                                    'quantity'    => esc_html__( 'Quantity', 'shopxpert' ),
                                    'add_to_cart' => esc_html__( 'Add To Cart', 'shopxpert' ),
                                ),
                                'default' => array('remove','image','title','price','quantity','add_to_cart'),
                                'class' => 'shopxpert-action-field-left',
                            ),
                            array(
                                'name'    => 'custom_heading',
                                'label'   => esc_html__( 'Custom heading', 'shopxpert' ),
                                'desc'    => esc_html__( 'Fields heading text.', 'shopxpert' ),
                                'type'    => 'multitext',
                                'options' => array(
                                    'remove'      => esc_html__( 'Remove', 'shopxpert' ),
                                    'image'       => esc_html__( 'Image', 'shopxpert' ),
                                    'title'       => esc_html__( 'Title', 'shopxpert' ),
                                    'price'       => esc_html__( 'Price', 'shopxpert' ),
                                    'quantity'    => esc_html__( 'Quantity', 'shopxpert' ),
                                    'add_to_cart' => esc_html__( 'Add To Cart', 'shopxpert' ),
                                ),
                                'class' => 'shopxpert-action-field-left',
                            ),
                            array(
                                'name'  => 'max_limit_message',
                                'label' => esc_html__( 'Reached maximum limit message', 'shopxpert' ),
                                'desc'  => esc_html__( 'You can manage message for maximum product added in the compare table.', 'shopxpert' ),
                                'type'  => 'text',
                                'default' => esc_html__( 'You have reached the maximum number of products to compare.', 'shopxpert' ),
                                'class' => 'shopxpert-action-field-left',
                            ),
                            array(
                                'name'  => 'empty_compare_text',
                                'label' => esc_html__( 'Empty compare page text', 'shopxpert' ),
                                'desc'  => esc_html__( 'Text will be displayed if user don\'t add any products to compare.', 'shopxpert' ),
                                'type'  => 'textarea',
                                'default' => esc_html__( 'No products added to compare.', 'shopxpert' ),
                                'class' => 'shopxpert-action-field-left',
                            ),
                            array(
                                'name'  => 'return_to_shop_text',
                                'label' => esc_html__( 'Return to shop button text', 'shopxpert' ),
                                'desc'  => esc_html__( 'Enter your return to shop button text.', 'shopxpert' ),
                                'type'  => 'text',
                                'default' => esc_html__( 'Return to Shop', 'shopxpert' ),
                                'placeholder' => esc_html__( 'Return to Shop', 'shopxpert' ),
                                'class' => 'shopxpert-action-field-left',
                            ),
                            array(
                                'name'    => 'image_size',
                                'label'   => esc_html__( 'Image size', 'shopxpert' ),
                                'desc'    => esc_html__( 'Set the image size for compare table.', 'shopxpert' ),
                                'type'    => 'multitext',
                                'options' => array(
                                    'width'  => esc_html__( 'Width', 'shopxpert' ),
                                    'height' => esc_html__( 'Height', 'shopxpert' ),
                                ),
                                'default' => array('width'=>80,'height'=>80),
                                'class' => 'shopxpert-action-field-left',
                            ),
                            array(
                                'name'  => 'image_hard_crop',
                                'label' => esc_html__( 'Image Hard Crop', 'shopxpert' ),
                                'desc'  => esc_html__( 'Enable hard crop for images.', 'shopxpert' ),
                                'type'  => 'checkbox',
                                'default' => 'on',
                                'class' => 'shopxpert-action-field-left',
                            ),
                        ),
                    ),

                ),

                'others' => array(

                    

                ),

            ),
 
        );

         // Post Duplicator Condition
         if( !is_plugin_active('ht-mega-for-elementor/htmega_addons_elementor.php') ){

            $post_types = shopxpert_get_post_types( array( 'defaultadd' => 'all' ) );

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