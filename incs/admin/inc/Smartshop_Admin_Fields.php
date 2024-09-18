<?php

namespace Shopxpert\Incs\Admin\Inc;


use Shopxpert\Incs;
// Ensure the correct function is available
use function Shopxpert\shopxpert_taxonomy_list;
use function Shopxpert\shopxpert_post_name;
 

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
            'shopxpert_gutenberg_tabs' => array(

                'settings' => array(

                    array(
                        'name'    => 'css_add_via',
                        'label'   => esc_html__( 'zzzz Add CSS through', 'shopxper' ),
                        'desc'    => esc_html__( 'Choose how you want to add the newly generated CSS.', 'shopxper' ),
                        'type'    => 'select',
                        'default' => 'internal',
                        'options' => array(
                            'internal' => esc_html__('Internal','shopxper'),
                            'external' => esc_html__('External','shopxper'),
                        )
                    ),

                    array(
                        'name'  => 'container_width',
                        'label' => esc_html__( 'xxxx Container Width', 'shopxper' ),
                        'desc'  => esc_html__( 'You can set the container width from here.', 'shopxper' ),
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
                        'headding'  => esc_html__( 'zz General', 'shopxper' ),
                        'type'      => 'title',
                        'class'     => 'shopxpert_heading_style_two'
                    ),

                    array(
                        'name'    => 'product_tab',
                        'label'   => esc_html__( 'xxxxProduct Tab', 'shopxper' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'promo_banner',
                        'label'   => esc_html__( 'Promo Banner', 'shopxper' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'special_day_offer',
                        'label'   => esc_html__( 'Special Day Offer', 'shopxper' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'image_marker',
                        'label'   => esc_html__( 'Image Marker', 'shopxper' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'store_feature',
                        'label'   => esc_html__( 'Store Feature', 'shopxper' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'brand_logo',
                        'label'   => esc_html__( 'Brand Logo', 'shopxper' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'category_grid',
                        'label'   => esc_html__( 'Category Grid', 'shopxper' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'faq',
                        'label'   => esc_html__( 'FAQ', 'shopxper' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_curvy',
                        'label'   => esc_html__( 'Product Curvy', 'shopxper' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'archive_title',
                        'label'   => esc_html__( 'Archive Title', 'shopxper' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'breadcrumbs',
                        'label'   => esc_html__( 'Breadcrumbs', 'shopxper' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),
                    array(
                        'name'    => 'recently_viewed_products',
                        'label'   => esc_html__( 'Recently Viewed Products', 'shopxper' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_grid',
                        'label'   => esc_html__( 'Product Grid', 'shopxper' ),
                        'type'    => 'element',
                        'default' => 'off',
                        'is_pro'  => true,
                    ),
    
                    array(
                        'name'    => 'customer_review',
                        'label'   => esc_html__( 'Customer Review', 'shopxper' ),
                        'type'    => 'element',
                        'default' => 'off',
                        'is_pro'  => true,
                    ),

                    array(
                        'name'      => 'shop_blocks_heading',
                        'headding'  => esc_html__( 'Shop / Archive', 'shopxper' ),
                        'type'      => 'title',
                        'class'     => 'shopxpert_heading_style_two'
                    ),

                    array(
                        'name'    => 'shop_archive_product',
                        'label'   => esc_html__( 'Product Archive (Default)', 'shopxper' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),
                    array(
                        'name'    => 'product_filter',
                        'label'   => esc_html__( 'Product Filter', 'shopxper' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),
                    array(
                        'name'    => 'product_horizontal_filter',
                        'label'   => esc_html__( 'Product Horizontal Filter', 'shopxper' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),
                    array(
                        'name'    => 'archive_result_count',
                        'label'   => esc_html__( 'Archive Result Count', 'shopxper' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),
                    array(
                        'name'    => 'archive_catalog_ordering',
                        'label'   => esc_html__( 'Archive Catalog Ordering', 'shopxper' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'      => 'single_blocks_heading',
                        'headding'  => esc_html__( 'Single Product', 'shopxper' ),
                        'type'      => 'title',
                        'class'     => 'shopxpert_heading_style_two'
                    ),

                    array(
                        'name'   => 'product_title',
                        'label'  => esc_html__('Product Title','shopxper'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_price',
                        'label'   => esc_html__('Product Price','shopxper'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'   => 'product_addtocart',
                        'label'  => esc_html__('Product Add To Cart','shopxper'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_short_description',
                        'label'   => esc_html__('Product Short Description','shopxper'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_description',
                        'label'   => esc_html__('Product Description','shopxper'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_rating',
                        'label'   => esc_html__('Product Rating','shopxper'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_image',
                        'label'   => esc_html__('Product Image','shopxper'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),
                    array(
                        'name'    => 'product_video_gallery',
                        'label'   => esc_html__('Product Video Gallery','shopxper'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_meta',
                        'label'   => esc_html__('Product Meta','shopxper'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_additional_info',
                        'label'   => esc_html__('Product Additional Info','shopxper'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_tabs',
                        'label'   => esc_html__('Product Tabs','shopxper'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_stock',
                        'label'   => esc_html__('Product Stock','shopxper'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_qrcode',
                        'label'   => esc_html__('Product QR Code','shopxper'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_related',
                        'label'   => esc_html__('Product Related','shopxper'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_upsell',
                        'label'   => esc_html__('Product Upsell','shopxper'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_reviews',
                        'label'   => esc_html__('Product Reviews','shopxper'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),
                    array(
                        'name'    => 'product_categories',
                        'label'   => esc_html__('Product Categories','shopxper'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),
                    array(
                        'name'    => 'product_tags',
                        'label'   => esc_html__('Product Tags','shopxper'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),
                    array(
                        'name'    => 'product_sku',
                        'label'   => esc_html__('Product SKU','shopxper'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),
                    array(
                        'name'    => 'call_for_price',
                        'label'   => esc_html__('Call for Price','shopxper'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),
                    array(
                        'name'    => 'suggest_price',
                        'label'   => esc_html__('Suggest Price','shopxper'),
                        'type'    => 'element',
                        'default' => 'on',
                    ),
                    array(
                        'name'    => 'product_social_share',
                        'label'   => esc_html__('Product Social Share','shopxper'),
                        'type'    => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'    => 'product_stock_progressbar',
                        'label'   => esc_html__('Stock Progressbar','shopxper'),
                        'type'    => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'    => 'product_sale_schedule',
                        'label'   => esc_html__('Product Sale Schedule','shopxper'),
                        'type'    => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'    => 'product_navigation',
                        'label'   => esc_html__('Product Navigation','shopxper'),
                        'type'    => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),

                    array(
                        'name'    => 'product_advance_image',
                        'label'   => esc_html__('Advance Product Image','shopxper'),
                        'type'    => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'    => 'product_thumbnails_zoom_image',
                        'label'   => esc_html__('Product Image With Zoom','shopxper'),
                        'type'    => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),

                    array(
                        'name'      => 'cart_blocks_heading',
                        'headding'  => esc_html__( 'Cart', 'shopxper' ),
                        'type'      => 'title',
                        'class'     => 'shopxpert_heading_style_two'
                    ),
                    array(
                        'name'  => 'cart_table',
                        'label' => esc_html__( 'Product Cart Table', 'shopxper' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'cart_table_list',
                        'label' => esc_html__( 'Product Cart Table (List Style)', 'shopxper' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'cart_total',
                        'label' => esc_html__( 'Product Cart Total', 'shopxper' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'corss_sell',
                        'label' => esc_html__( 'Product Cross Sell', 'shopxper' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'return_to_shop',
                        'label' => esc_html__( 'Return To Shop Button', 'shopxper' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'cart_empty_message',
                        'label' => esc_html__( 'Empty Cart Message', 'shopxper' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),

                    array(
                        'name'      => 'checkout_blocks_heading',
                        'headding'  => esc_html__( 'Checkout', 'shopxper' ),
                        'type'      => 'title',
                        'class'     => 'shopxpert_heading_style_two'
                    ),
                    array(
                        'name'  => 'checkout_billing_form',
                        'label' => esc_html__( 'Checkout Billing Form', 'shopxper' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'checkout_shipping_form',
                        'label' => esc_html__( 'Checkout Shipping Form', 'shopxper' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'checkout_additional_form',
                        'label' => esc_html__( 'Checkout Additional..', 'shopxper' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'checkout_coupon_form',
                        'label' => esc_html__( 'Checkout Coupon Form', 'shopxper' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'checkout_payment',
                        'label' => esc_html__( 'Checkout Payment Method', 'shopxper' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'checkout_shipping_method',
                        'label' => esc_html__( 'Checkout Shipping Method', 'shopxper' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'checkout_order_review',
                        'label' => esc_html__( 'Checkout Order Review', 'shopxper' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'checkout_login_form',
                        'label' => esc_html__( 'Checkout Login Form', 'shopxper' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),

                    array(
                        'name'      => 'myaccount_blocks_heading',
                        'headding'  => esc_html__( 'My Account', 'shopxper' ),
                        'type'      => 'title',
                        'class'     => 'shopxpert_heading_style_two'
                    ),
                    array(
                        'name'  => 'my_account',
                        'label' => esc_html__( 'My Account', 'shopxper' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_navigation',
                        'label' => esc_html__( 'My Account Navigation', 'shopxper' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_dashboard',
                        'label' => esc_html__( 'My Account Dashboard', 'shopxper' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_download',
                        'label' => esc_html__( 'My Account Download', 'shopxper' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_edit',
                        'label' => esc_html__( 'My Account Edit', 'shopxper' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_address',
                        'label' => esc_html__( 'My Account Address', 'shopxper' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_order',
                        'label' => esc_html__( 'My Account Order', 'shopxper' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_logout',
                        'label' => esc_html__( 'My Account Logout', 'shopxper' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_login_form',
                        'label' => esc_html__( 'Login Form', 'shopxper' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_registration_form',
                        'label' => esc_html__( 'Registration Form', 'shopxper' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_lost_password',
                        'label' => esc_html__( 'Lost Password Form', 'shopxper' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_reset_password',
                        'label' => esc_html__( 'Reset Password Form', 'shopxper' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),

                    array(
                        'name'      => 'thankyou_blocks_heading',
                        'headding'  => esc_html__( 'Thank You', 'shopxper' ),
                        'type'      => 'title',
                        'class'     => 'shopxpert_heading_style_two'
                    ),
                    array(
                        'name'  => 'thankyou_order',
                        'label' => esc_html__( 'Thank You Order', 'shopxper' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'thankyou_address_details',
                        'label' => esc_html__( 'Thank You Address', 'shopxper' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'thankyou_order_details',
                        'label' => esc_html__( 'Thank You Order Details', 'shopxper' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),

                )

            ),

            'shopxpert_others_tabs' => array(

                'features' => array( 
                    array(
                        'name'     => 'rename_label_settings',
                        'label'    => esc_html__( 'Change Label', 'shopxper' ),
                        'type'     => 'Feature',
                        'default'  => 'off',
                        'section'  => 'shopxpert_rename_label_tabs',
                        'option_id'=> 'enablerenamelabel',
                        'require_settings'=> true,
                        'documentation' => esc_url('https://shopxpert.com/doc/change-woocommerce-text/'),
                        'setting_fields' => array(
                            
                            array(
                                'name'  => 'enablerenamelabel',
                                'label' => esc_html__( 'Enable / Disable', 'shopxper' ),
                                'desc'  => esc_html__( 'You can enable / disable change Label from here.', 'shopxper' ),
                                'type'  => 'checkbox',
                                'default' => 'off',
                                'class'   =>'enablerenamelabel shopxpert-action-field-left',
                            ),
            
                            array(
                                'name'      => 'shop_page_heading',
                                'headding'  => esc_html__( 'Shop Page', 'shopxper' ),
                                'type'      => 'title',
                                'class'     => 'depend_enable_rename_label',
                            ),
                            
                            array(
                                'name'        => 'wl_shop_add_to_cart_txt',
                                'label'       => esc_html__( 'Add to Cart Button Text', 'shopxper' ),
                                'desc'        => esc_html__( 'Change the Add to Cart button text for the Shop page.', 'shopxper' ),
                                'type'        => 'text',
                                'placeholder' => esc_html__( 'Add to Cart', 'shopxper' ),
                                'class'       => 'depend_enable_rename_label shopxpert-action-field-left',
                            ),
            
                            array(
                                'name'      => 'product_details_page_heading',
                                'headding'  => esc_html__( 'Product Details Page', 'shopxper' ),
                                'type'      => 'title',
                                'class'     => 'depend_enable_rename_label',
                            ),
            
                            array(
                                'name'        => 'wl_add_to_cart_txt',
                                'label'       => esc_html__( 'Add to Cart Button Text', 'shopxper' ),
                                'desc'        => esc_html__( 'Change the Add to Cart button text for the Product details page.', 'shopxper' ),
                                'type'        => 'text',
                                'placeholder' => esc_html__( 'Add to Cart', 'shopxper' ),
                                'class'       => 'depend_enable_rename_label shopxpert-action-field-left',
                            ),
            
                            array(
                                'name'        => 'wl_description_tab_menu_title',
                                'label'       => esc_html__( 'Description', 'shopxper' ),
                                'desc'        => esc_html__( 'Change the tab title for the product description.', 'shopxper' ),
                                'type'        => 'text',
                                'placeholder' => esc_html__( 'Description', 'shopxper' ),
                                'class'       => 'depend_enable_rename_label shopxpert-action-field-left',
                            ),
                            
                            array(
                                'name'        => 'wl_additional_information_tab_menu_title',
                                'label'       => esc_html__( 'Additional Information', 'shopxper' ),
                                'desc'        => esc_html__( 'Change the tab title for the product additional information', 'shopxper' ),
                                'type'        => 'text',
                                'placeholder' => esc_html__( 'Additional information', 'shopxper' ),
                                'class'       => 'depend_enable_rename_label shopxpert-action-field-left',
                            ),
                            
                            array(
                                'name'        => 'wl_reviews_tab_menu_title',
                                'label'       => esc_html__( 'Reviews', 'shopxper' ),
                                'desc'        => esc_html__( 'Change the tab title for the product review', 'shopxper' ),
                                'type'        => 'text',
                                'placeholder' => __( 'Reviews', 'shopxper' ),
                                'class'       =>'depend_enable_rename_label shopxpert-action-field-left',
                            ),
            
                            array(
                                'name'      => 'checkout_page_heading',
                                'headding'  => esc_html__( 'Checkout Page', 'shopxper' ),
                                'type'      => 'title',
                                'class'     => 'depend_enable_rename_label',
                            ),
            
                            array(
                                'name'        => 'wl_checkout_placeorder_btn_txt',
                                'label'       => esc_html__( 'Place order', 'shopxper' ),
                                'desc'        => esc_html__( 'Change the label for the Place order field.', 'shopxper' ),
                                'type'        => 'text',
                                'placeholder' => esc_html__( 'Place order', 'shopxper' ),
                                'class'       => 'depend_enable_rename_label shopxpert-action-field-left',
                            ),

                        )
                    ),
                    array(
                        'name'     => 'shopxpert_backorder_settings',
                        'label'    => esc_html__( 'xx Pending Stock', 'shopxper' ),
                        'type'     => 'Feature',
                        'default'  => 'off',
                        'section'  => 'shopxpert_backorder_settings',
                        'option_id'=> 'enable',
                        'require_settings'  => true,
                        'documentation' => esc_url('https://shopxpert.com/doc/how-to-enable-woocommerce-backorder/'),
                        'setting_fields' => array(
                        
                            array(
                                'name'  => 'enable',
                                'label' => esc_html__( 'Enable / Disable', 'shopxper' ),
                                'desc'  => esc_html__( 'You can enable / disable backorder Feature from here.', 'shopxper' ),
                                'type'  => 'checkbox',
                                'default' => 'off',
                                'class' => 'shopxpert-action-field-left'
                            ),

                            array(
                                'name'    => 'backorder_limit',
                                'label'   => esc_html__( 'Pending Stock Limit', 'shopxper' ),
                                'desc'    => esc_html__( 'Set "Pending Stock Limit" on all "Pending Stock" products across the entire website. You can also set limits for each product individually from the "Inventory" tab.', 'shopxper' ),
                                'type'    => 'number',
                                'class'   => 'shopxpert-action-field-left'
                            ),

                            array(
                                'name'    => 'backorder_availability_date',
                                'label'   => esc_html__( 'Availability Date', 'shopxper' ),
                                'type'    => 'date',
                                'class'   => 'shopxpert-action-field-left'
                            ),
                        
                            array(
                                'name'        => 'backorder_availability_message',
                                'label'       => esc_html__( 'Availability Message', 'shopxper' ),
                                'desc'        => esc_html__( 'Manage how you want the "Message" to appear. Use this {availability_date} placeholder to display the date you set. ', 'shopxper' ),
                                'type'        => 'text',
                                'default'     => esc_html__( 'On Pending Stock: Will be available on {availability_date}', 'shopxper' ),
                                'class'       => 'shopxpert-action-field-left',
                            ),
                            
                        )
                        
                    ),
                    array(
                        'name'     => 'wishlist',
                        'label'    => esc_html__( 's Wishlist', 'shopxper' ),
                        'type'     => 'element',
                        'default'  => 'off',
                        'documentation' => esc_url('https://shopxpert.com/doc/wishlist-for-woocommerce/')
                    ), 
                    array(
                        'name'    => 'ajaxsearch',
                        'label'   => esc_html__( 'Dynamic Search Widget', 'shopxper' ),
                        'desc'    => esc_html__( 'Dynamic Search Widget', 'shopxper' ),
                        'type'    => 'element',
                        'default' => 'off',
                        'documentation' => esc_url('https://shopxpert.com/doc/how-to-use-woocommerce-ajax-search/')
                    ),
                ),

                'others' => array(

                    array(
                        'name'  => 'loadproductlimit',
                        'label' => esc_html__( 'Load Products in Elementor Addons', 'shopxper' ),
                        'desc'  => esc_html__( 'Set the number of products to load in Elementor Addons', 'shopxper' ),
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
                'title' => esc_html__( 'x General', 'shopxper' ),
                'icon'  => 'dashicons-admin-home'
            ),

            array(
                'id'    => 'shopxpert_woo_template_tabs',
                'title' => esc_html__( 'WooCommerce Template', 'shopxper' ),
                'icon'  => 'wli-store'
            ),

            array(
                'id'    => 'shopxpert_gutenberg_tabs',
                'title' => esc_html__( 'Gutenberg', 'shopxper' ),
                'icon'  => 'wli-cog'
            ),

            array(
                'id'    => 'shopxpert_elements_tabs',
                'title' => esc_html__( 'Elements', 'shopxper' ),
                'icon'  => 'wli-images'
            ),

            array(
                'id'    => 'shopxpert_others_tabs',
                'title' => esc_html__( 'Features', 'shopxper' ),
                'icon'  => 'wli-grid'
            ),

            array(
                'id'    => 'shopxpert_style_tabs',
                'title' => esc_html__( 'Style', 'shopxper' ),
                'icon'  => 'wli-tag'
            ),

            array(
                'id'    => 'shopxpert_extension_tabs',
                'title' => esc_html__( 'Extensions', 'shopxper' ),
                'icon'  => 'wli-masonry'
            ),

            array(
                'id'    => 'shopxpert_freevspro_tabs',
                'title' => esc_html__( 'Free VS Pro', 'shopxper' ),
                'class' => 'freevspro'
            ),

        );
        return apply_filters( 'shopxpert_admin_fields_sections', $sections );

    }
}