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

                    array(
                        'name'    => 'promo_banner',
                        'label'   => esc_html__( 'Promo Banner', 'shopxpert' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'special_day_offer',
                        'label'   => esc_html__( 'Special Day Offer', 'shopxpert' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'image_marker',
                        'label'   => esc_html__( 'Image Marker', 'shopxpert' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'store_feature',
                        'label'   => esc_html__( 'Store Feature', 'shopxpert' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'brand_logo',
                        'label'   => esc_html__( 'Brand Logo', 'shopxpert' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'category_grid',
                        'label'   => esc_html__( 'Category Grid', 'shopxpert' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'faq',
                        'label'   => esc_html__( 'FAQ', 'shopxpert' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_curvy',
                        'label'   => esc_html__( 'Product Curvy', 'shopxpert' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'archive_title',
                        'label'   => esc_html__( 'Archive Title', 'shopxpert' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'breadcrumbs',
                        'label'   => esc_html__( 'Breadcrumbs', 'shopxpert' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),
                    array(
                        'name'    => 'recently_viewed_products',
                        'label'   => esc_html__( 'Recently Viewed Products', 'shopxpert' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_grid',
                        'label'   => esc_html__( 'Product Grid', 'shopxpert' ),
                        'type'    => 'element',
                        'default' => 'off',
                        'is_pro'  => true,
                    ),
    
                    array(
                        'name'    => 'customer_review',
                        'label'   => esc_html__( 'Customer Review', 'shopxpert' ),
                        'type'    => 'element',
                        'default' => 'off',
                        'is_pro'  => true,
                    ),

                    array(
                        'name'      => 'shop_blocks_heading',
                        'headding'  => esc_html__( 'Shop / Archive', 'shopxpert' ),
                        'type'      => 'title',
                        'class'     => 'shopxpert_heading_style_two'
                    ),

                    array(
                        'name'    => 'shop_archive_product',
                        'label'   => esc_html__( 'Product Archive (Default)', 'shopxpert' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),
                    array(
                        'name'    => 'product_filter',
                        'label'   => esc_html__( 'Product Filter', 'shopxpert' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),
                    array(
                        'name'    => 'product_horizontal_filter',
                        'label'   => esc_html__( 'Product Horizontal Filter', 'shopxpert' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),
                    array(
                        'name'    => 'archive_result_count',
                        'label'   => esc_html__( 'Archive Result Count', 'shopxpert' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),
                    array(
                        'name'    => 'archive_catalog_ordering',
                        'label'   => esc_html__( 'Archive Catalog Ordering', 'shopxpert' ),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'      => 'single_blocks_heading',
                        'headding'  => esc_html__( 'Single Product', 'shopxpert' ),
                        'type'      => 'title',
                        'class'     => 'shopxpert_heading_style_two'
                    ),

                    array(
                        'name'   => 'product_title',
                        'label'  => esc_html__('Product Title','shopxpert'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_price',
                        'label'   => esc_html__('Product Price','shopxpert'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'   => 'product_addtocart',
                        'label'  => esc_html__('Product Add To Cart','shopxpert'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_short_description',
                        'label'   => esc_html__('Product Short Description','shopxpert'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_description',
                        'label'   => esc_html__('Product Description','shopxpert'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_rating',
                        'label'   => esc_html__('Product Rating','shopxpert'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_image',
                        'label'   => esc_html__('Product Image','shopxpert'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),
                    array(
                        'name'    => 'product_video_gallery',
                        'label'   => esc_html__('Product Video Gallery','shopxpert'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_meta',
                        'label'   => esc_html__('Product Meta','shopxpert'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_additional_info',
                        'label'   => esc_html__('Product Additional Info','shopxpert'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_tabs',
                        'label'   => esc_html__('Product Tabs','shopxpert'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_stock',
                        'label'   => esc_html__('Product Stock','shopxpert'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_qrcode',
                        'label'   => esc_html__('Product QR Code','shopxpert'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_related',
                        'label'   => esc_html__('Product Related','shopxpert'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_upsell',
                        'label'   => esc_html__('Product Upsell','shopxpert'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),

                    array(
                        'name'    => 'product_reviews',
                        'label'   => esc_html__('Product Reviews','shopxpert'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),
                    array(
                        'name'    => 'product_categories',
                        'label'   => esc_html__('Product Categories','shopxpert'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),
                    array(
                        'name'    => 'product_tags',
                        'label'   => esc_html__('Product Tags','shopxpert'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),
                    array(
                        'name'    => 'product_sku',
                        'label'   => esc_html__('Product SKU','shopxpert'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),
                    array(
                        'name'    => 'call_for_price',
                        'label'   => esc_html__('Call for Price','shopxpert'),
                        'type'    => 'element',
                        'default' => 'on'
                    ),
                    array(
                        'name'    => 'suggest_price',
                        'label'   => esc_html__('Suggest Price','shopxpert'),
                        'type'    => 'element',
                        'default' => 'on',
                    ),
                    array(
                        'name'    => 'product_social_share',
                        'label'   => esc_html__('Product Social Share','shopxpert'),
                        'type'    => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'    => 'product_stock_progressbar',
                        'label'   => esc_html__('Stock Progressbar','shopxpert'),
                        'type'    => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'    => 'product_sale_schedule',
                        'label'   => esc_html__('Product Sale Schedule','shopxpert'),
                        'type'    => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'    => 'product_navigation',
                        'label'   => esc_html__('Product Navigation','shopxpert'),
                        'type'    => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),

                    array(
                        'name'    => 'product_advance_image',
                        'label'   => esc_html__('Advance Product Image','shopxpert'),
                        'type'    => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'    => 'product_thumbnails_zoom_image',
                        'label'   => esc_html__('Product Image With Zoom','shopxpert'),
                        'type'    => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),

                    array(
                        'name'      => 'cart_blocks_heading',
                        'headding'  => esc_html__( 'Cart', 'shopxpert' ),
                        'type'      => 'title',
                        'class'     => 'shopxpert_heading_style_two'
                    ),
                    array(
                        'name'  => 'cart_table',
                        'label' => esc_html__( 'Product Cart Table', 'shopxpert' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'cart_table_list',
                        'label' => esc_html__( 'Product Cart Table (List Style)', 'shopxpert' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'cart_total',
                        'label' => esc_html__( 'Product Cart Total', 'shopxpert' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'corss_sell',
                        'label' => esc_html__( 'Product Cross Sell', 'shopxpert' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'return_to_shop',
                        'label' => esc_html__( 'Return To Shop Button', 'shopxpert' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'cart_empty_message',
                        'label' => esc_html__( 'Empty Cart Message', 'shopxpert' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),

                    array(
                        'name'      => 'checkout_blocks_heading',
                        'headding'  => esc_html__( 'Checkout', 'shopxpert' ),
                        'type'      => 'title',
                        'class'     => 'shopxpert_heading_style_two'
                    ),
                    array(
                        'name'  => 'checkout_billing_form',
                        'label' => esc_html__( 'Checkout Billing Form', 'shopxpert' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'checkout_shipping_form',
                        'label' => esc_html__( 'Checkout Shipping Form', 'shopxpert' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'checkout_additional_form',
                        'label' => esc_html__( 'Checkout Additional..', 'shopxpert' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'checkout_coupon_form',
                        'label' => esc_html__( 'Checkout Coupon Form', 'shopxpert' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'checkout_payment',
                        'label' => esc_html__( 'Checkout Payment Method', 'shopxpert' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'checkout_shipping_method',
                        'label' => esc_html__( 'Checkout Shipping Method', 'shopxpert' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'checkout_order_review',
                        'label' => esc_html__( 'Checkout Order Review', 'shopxpert' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'checkout_login_form',
                        'label' => esc_html__( 'Checkout Login Form', 'shopxpert' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),

                    array(
                        'name'      => 'myaccount_blocks_heading',
                        'headding'  => esc_html__( 'My Account', 'shopxpert' ),
                        'type'      => 'title',
                        'class'     => 'shopxpert_heading_style_two'
                    ),
                    array(
                        'name'  => 'my_account',
                        'label' => esc_html__( 'My Account', 'shopxpert' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_navigation',
                        'label' => esc_html__( 'My Account Navigation', 'shopxpert' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_dashboard',
                        'label' => esc_html__( 'My Account Dashboard', 'shopxpert' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_download',
                        'label' => esc_html__( 'My Account Download', 'shopxpert' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_edit',
                        'label' => esc_html__( 'My Account Edit', 'shopxpert' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_address',
                        'label' => esc_html__( 'My Account Address', 'shopxpert' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_order',
                        'label' => esc_html__( 'My Account Order', 'shopxpert' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_logout',
                        'label' => esc_html__( 'My Account Logout', 'shopxpert' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_login_form',
                        'label' => esc_html__( 'Login Form', 'shopxpert' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_registration_form',
                        'label' => esc_html__( 'Registration Form', 'shopxpert' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_lost_password',
                        'label' => esc_html__( 'Lost Password Form', 'shopxpert' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_reset_password',
                        'label' => esc_html__( 'Reset Password Form', 'shopxpert' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),

                    array(
                        'name'      => 'thankyou_blocks_heading',
                        'headding'  => esc_html__( 'Thank You', 'shopxpert' ),
                        'type'      => 'title',
                        'class'     => 'shopxpert_heading_style_two'
                    ),
                    array(
                        'name'  => 'thankyou_order',
                        'label' => esc_html__( 'Thank You Order', 'shopxpert' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'thankyou_address_details',
                        'label' => esc_html__( 'Thank You Address', 'shopxpert' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'thankyou_order_details',
                        'label' => esc_html__( 'Thank You Order Details', 'shopxpert' ),
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
                'id'    => 'shopxpert_woo_template_tabs',
                'title' => esc_html__( 'WooCommerce Template', 'shopxpert' ),
                'icon'  => 'wli-store'
            ),

            array(
                'id'    => 'shopxpert_gutenberg_tabs',
                'title' => esc_html__( 'Gutenberg', 'shopxpert' ),
                'icon'  => 'wli-cog'
            ),

            array(
                'id'    => 'shopxpert_elements_tabs',
                'title' => esc_html__( 'Elements', 'shopxpert' ),
                'icon'  => 'wli-images'
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
                'id'    => 'shopxpert_extension_tabs',
                'title' => esc_html__( 'Extensions', 'shopxpert' ),
                'icon'  => 'wli-masonry'
            ),

            array(
                'id'    => 'shopxpert_freevspro_tabs',
                'title' => esc_html__( 'Free VS Pro', 'shopxpert' ),
                'class' => 'freevspro'
            ),

        );
        return apply_filters( 'shopxpert_admin_fields_sections', $sections );

    }
}