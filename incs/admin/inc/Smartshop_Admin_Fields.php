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
            'smartshop_gutenberg_tabs' => array(

                'settings' => array(

                    array(
                        'name'    => 'css_add_via',
                        'label'   => esc_html__( 'zzzz Add CSS through', 'smartshop' ),
                        'desc'    => esc_html__( 'Choose how you want to add the newly generated CSS.', 'smartshop' ),
                        'type'    => 'select',
                        'default' => 'internal',
                        'options' => array(
                            'internal' => esc_html__('Internal','smartshop'),
                            'external' => esc_html__('External','smartshop'),
                        )
                    ),

                    array(
                        'name'  => 'container_width',
                        'label' => esc_html__( 'xxxx Container Width', 'smartshop' ),
                        'desc'  => esc_html__( 'You can set the container width from here.', 'smartshop' ),
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
                        'headding'  => esc_html__( 'zz General', 'smartshop' ),
                        'type'      => 'title',
                        'class'     => 'smartshop_heading_style_two'
                    ),

                    array(
                        'name'    => 'product_tab',
                        'label'   => esc_html__( 'xxxxProduct Tab', 'smartshop' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'promo_banner',
                        'label'   => esc_html__( 'Promo Banner', 'smartshop' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'special_day_offer',
                        'label'   => esc_html__( 'Special Day Offer', 'smartshop' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'image_marker',
                        'label'   => esc_html__( 'Image Marker', 'smartshop' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'store_feature',
                        'label'   => esc_html__( 'Store Feature', 'smartshop' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'brand_logo',
                        'label'   => esc_html__( 'Brand Logo', 'smartshop' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'category_grid',
                        'label'   => esc_html__( 'Category Grid', 'smartshop' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'faq',
                        'label'   => esc_html__( 'FAQ', 'smartshop' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_curvy',
                        'label'   => esc_html__( 'Product Curvy', 'smartshop' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'archive_title',
                        'label'   => esc_html__( 'Archive Title', 'smartshop' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'breadcrumbs',
                        'label'   => esc_html__( 'Breadcrumbs', 'smartshop' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),
                    array(
                        'name'    => 'recently_viewed_products',
                        'label'   => esc_html__( 'Recently Viewed Products', 'smartshop' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_grid',
                        'label'   => esc_html__( 'Product Grid', 'smartshop' ),
                        'type'    => 'element',
                        'default' => 'off',
                        'is_pro'  => true,
                    ),
    
                    array(
                        'name'    => 'customer_review',
                        'label'   => esc_html__( 'Customer Review', 'smartshop' ),
                        'type'    => 'element',
                        'default' => 'off',
                        'is_pro'  => true,
                    ),

                    array(
                        'name'      => 'shop_blocks_heading',
                        'headding'  => esc_html__( 'Shop / Archive', 'smartshop' ),
                        'type'      => 'title',
                        'class'     => 'smartshop_heading_style_two'
                    ),

                    array(
                        'name'    => 'shop_archive_product',
                        'label'   => esc_html__( 'Product Archive (Default)', 'smartshop' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),
                    array(
                        'name'    => 'product_filter',
                        'label'   => esc_html__( 'Product Filter', 'smartshop' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),
                    array(
                        'name'    => 'product_horizontal_filter',
                        'label'   => esc_html__( 'Product Horizontal Filter', 'smartshop' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),
                    array(
                        'name'    => 'archive_result_count',
                        'label'   => esc_html__( 'Archive Result Count', 'smartshop' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),
                    array(
                        'name'    => 'archive_catalog_ordering',
                        'label'   => esc_html__( 'Archive Catalog Ordering', 'smartshop' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'      => 'single_blocks_heading',
                        'headding'  => esc_html__( 'Single Product', 'smartshop' ),
                        'type'      => 'title',
                        'class'     => 'smartshop_heading_style_two'
                    ),

                    array(
                        'name'   => 'product_title',
                        'label'  => esc_html__('Product Title','smartshop'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_price',
                        'label'   => esc_html__('Product Price','smartshop'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'   => 'product_addtocart',
                        'label'  => esc_html__('Product Add To Cart','smartshop'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_short_description',
                        'label'   => esc_html__('Product Short Description','smartshop'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_description',
                        'label'   => esc_html__('Product Description','smartshop'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_rating',
                        'label'   => esc_html__('Product Rating','smartshop'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_image',
                        'label'   => esc_html__('Product Image','smartshop'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),
                    array(
                        'name'    => 'product_video_gallery',
                        'label'   => esc_html__('Product Video Gallery','smartshop'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_meta',
                        'label'   => esc_html__('Product Meta','smartshop'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_additional_info',
                        'label'   => esc_html__('Product Additional Info','smartshop'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_tabs',
                        'label'   => esc_html__('Product Tabs','smartshop'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_stock',
                        'label'   => esc_html__('Product Stock','smartshop'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_qrcode',
                        'label'   => esc_html__('Product QR Code','smartshop'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_related',
                        'label'   => esc_html__('Product Related','smartshop'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_upsell',
                        'label'   => esc_html__('Product Upsell','smartshop'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_reviews',
                        'label'   => esc_html__('Product Reviews','smartshop'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),
                    array(
                        'name'    => 'product_categories',
                        'label'   => esc_html__('Product Categories','smartshop'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),
                    array(
                        'name'    => 'product_tags',
                        'label'   => esc_html__('Product Tags','smartshop'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),
                    array(
                        'name'    => 'product_sku',
                        'label'   => esc_html__('Product SKU','smartshop'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),
                    array(
                        'name'    => 'call_for_price',
                        'label'   => esc_html__('Call for Price','smartshop'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),
                    array(
                        'name'    => 'suggest_price',
                        'label'   => esc_html__('Suggest Price','smartshop'),
                        'type'    => 'element',
                        'default' => 'on',
                    ),
                    array(
                        'name'    => 'product_social_share',
                        'label'   => esc_html__('Product Social Share','smartshop'),
                        'type'    => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'    => 'product_stock_progressbar',
                        'label'   => esc_html__('Stock Progressbar','smartshop'),
                        'type'    => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'    => 'product_sale_schedule',
                        'label'   => esc_html__('Product Sale Schedule','smartshop'),
                        'type'    => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'    => 'product_navigation',
                        'label'   => esc_html__('Product Navigation','smartshop'),
                        'type'    => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),

                    array(
                        'name'    => 'product_advance_image',
                        'label'   => esc_html__('Advance Product Image','smartshop'),
                        'type'    => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'    => 'product_thumbnails_zoom_image',
                        'label'   => esc_html__('Product Image With Zoom','smartshop'),
                        'type'    => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),

                    array(
                        'name'      => 'cart_blocks_heading',
                        'headding'  => esc_html__( 'Cart', 'smartshop' ),
                        'type'      => 'title',
                        'class'     => 'smartshop_heading_style_two'
                    ),
                    array(
                        'name'  => 'cart_table',
                        'label' => esc_html__( 'Product Cart Table', 'smartshop' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'cart_table_list',
                        'label' => esc_html__( 'Product Cart Table (List Style)', 'smartshop' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'cart_total',
                        'label' => esc_html__( 'Product Cart Total', 'smartshop' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'corss_sell',
                        'label' => esc_html__( 'Product Cross Sell', 'smartshop' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'return_to_shop',
                        'label' => esc_html__( 'Return To Shop Button', 'smartshop' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'cart_empty_message',
                        'label' => esc_html__( 'Empty Cart Message', 'smartshop' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),

                    array(
                        'name'      => 'checkout_blocks_heading',
                        'headding'  => esc_html__( 'Checkout', 'smartshop' ),
                        'type'      => 'title',
                        'class'     => 'smartshop_heading_style_two'
                    ),
                    array(
                        'name'  => 'checkout_billing_form',
                        'label' => esc_html__( 'Checkout Billing Form', 'smartshop' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'checkout_shipping_form',
                        'label' => esc_html__( 'Checkout Shipping Form', 'smartshop' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'checkout_additional_form',
                        'label' => esc_html__( 'Checkout Additional..', 'smartshop' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'checkout_coupon_form',
                        'label' => esc_html__( 'Checkout Coupon Form', 'smartshop' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'checkout_payment',
                        'label' => esc_html__( 'Checkout Payment Method', 'smartshop' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'checkout_shipping_method',
                        'label' => esc_html__( 'Checkout Shipping Method', 'smartshop' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'checkout_order_review',
                        'label' => esc_html__( 'Checkout Order Review', 'smartshop' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'checkout_login_form',
                        'label' => esc_html__( 'Checkout Login Form', 'smartshop' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),

                    array(
                        'name'      => 'myaccount_blocks_heading',
                        'headding'  => esc_html__( 'My Account', 'smartshop' ),
                        'type'      => 'title',
                        'class'     => 'smartshop_heading_style_two'
                    ),
                    array(
                        'name'  => 'my_account',
                        'label' => esc_html__( 'My Account', 'smartshop' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_navigation',
                        'label' => esc_html__( 'My Account Navigation', 'smartshop' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_dashboard',
                        'label' => esc_html__( 'My Account Dashboard', 'smartshop' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_download',
                        'label' => esc_html__( 'My Account Download', 'smartshop' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_edit',
                        'label' => esc_html__( 'My Account Edit', 'smartshop' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_address',
                        'label' => esc_html__( 'My Account Address', 'smartshop' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_order',
                        'label' => esc_html__( 'My Account Order', 'smartshop' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_logout',
                        'label' => esc_html__( 'My Account Logout', 'smartshop' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_login_form',
                        'label' => esc_html__( 'Login Form', 'smartshop' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_registration_form',
                        'label' => esc_html__( 'Registration Form', 'smartshop' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_lost_password',
                        'label' => esc_html__( 'Lost Password Form', 'smartshop' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_reset_password',
                        'label' => esc_html__( 'Reset Password Form', 'smartshop' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),

                    array(
                        'name'      => 'thankyou_blocks_heading',
                        'headding'  => esc_html__( 'Thank You', 'smartshop' ),
                        'type'      => 'title',
                        'class'     => 'smartshop_heading_style_two'
                    ),
                    array(
                        'name'  => 'thankyou_order',
                        'label' => esc_html__( 'Thank You Order', 'smartshop' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'thankyou_address_details',
                        'label' => esc_html__( 'Thank You Address', 'smartshop' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'thankyou_order_details',
                        'label' => esc_html__( 'Thank You Order Details', 'smartshop' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),

                )

            ),

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
                        'name'     => 'smartshop_backorder_settings',
                        'label'    => esc_html__( 'xx Backorder', 'smartshop' ),
                        'type'     => 'module',
                        'default'  => 'off',
                        'section'  => 'smartshop_backorder_settings',
                        'option_id'=> 'enable',
                        'require_settings'  => true,
                        'documentation' => esc_url('https://smartshop.com/doc/how-to-enable-woocommerce-backorder/'),
                        'setting_fields' => array(
                        
                            array(
                                'name'  => 'enable',
                                'label' => esc_html__( 'Enable / Disable', 'smartshop' ),
                                'desc'  => esc_html__( 'You can enable / disable backorder module from here.', 'smartshop' ),
                                'type'  => 'checkbox',
                                'default' => 'off',
                                'class' => 'smartshop-action-field-left'
                            ),

                            array(
                                'name'    => 'backorder_limit',
                                'label'   => esc_html__( 'Backorder Limit', 'smartshop' ),
                                'desc'    => esc_html__( 'Set "Backorder Limit" on all "Backorder" products across the entire website. You can also set limits for each product individually from the "Inventory" tab.', 'smartshop' ),
                                'type'    => 'number',
                                'class'   => 'smartshop-action-field-left'
                            ),

                            array(
                                'name'    => 'backorder_availability_date',
                                'label'   => esc_html__( 'Availability Date', 'smartshop' ),
                                'type'    => 'date',
                                'class'   => 'smartshop-action-field-left'
                            ),
                        
                            array(
                                'name'        => 'backorder_availability_message',
                                'label'       => esc_html__( 'Availability Message', 'smartshop' ),
                                'desc'        => esc_html__( 'Manage how you want the "Message" to appear. Use this {availability_date} placeholder to display the date you set. ', 'smartshop' ),
                                'type'        => 'text',
                                'default'     => esc_html__( 'On Backorder: Will be available on {availability_date}', 'smartshop' ),
                                'class'       => 'smartshop-action-field-left',
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