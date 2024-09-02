<?php
namespace SmartShopBlocks;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Manage Blocks
 */
class Blocks_List {
    
    /**
     * Block List
     *
     * @return array
     */
    public static function get_block_list(){

        $blockList = [

            'brand_logo' => [
                'label'  => __('Brand Logo','smartshop'),
                'name'   => 'smartshop/brand-logo',
                'server_side_render' => true,
                'type'   => 'common',
                'active' => true,
            ],
            'category_grid' => [
                'label'  => __('Category Grid','smartshop'),
                'name'   => 'smartshop/category-grid',
                'server_side_render' => true,
                'type'   => 'common',
                'active' => true,
                'script' => 'slick'
            ],
            'image_marker' => [
                'label'  => __('Image Marker','smartshop'),
                'name'   => 'smartshop/image-marker',
                'server_side_render' => true,
                'type'   => 'common',
                'active' => true,
            ],
            'special_day_offer' => [
                'label'  => __('Special Day Offer','smartshop'),
                'name'   => 'smartshop/special-day-offer',
                'server_side_render' => true,
                'type'   => 'common',
                'active' => true,
            ],
            'store_feature' => [
                'label'  => __('Store Feature','smartshop'),
                'name'   => 'smartshop/store-feature',
                'server_side_render' => true,
                'type'   => 'common',
                'active' => true,
            ],
            'product_tab' => [
                'label'  => __('Product tab','smartshop'),
                'name'   => 'smartshop/product-tab',
                'server_side_render' => true,
                'type'   => 'common',
                'active' => true,
                'script' => 'slick',
            ],
            'promo_banner' => [
                'label'  => __('Promo Banner','smartshop'),
                'name'   => 'smartshop/promo-banner',
                'type'   => 'common',
                'active' => true,
            ],
            'faq' => [
                'label'  => __('FAQ','smartshop'),
                'name'   => 'smartshop/faq',
                'server_side_render' => true,
                'type'   => 'common',
                'active' => true,
                'script' => 'smartshop-accordion-min',
            ],
            'product_curvy' => [
                'label'  => __('Product Curvy','smartshop'),
                'name'   => 'smartshop/product-curvy',
                'server_side_render' => true,
                'type'   => 'common',
                'active' => true,
            ],
            'archive_title' => [
                'label'  => __('Archive Title','smartshop'),
                'name'   => 'smartshop/archive-title',
                'server_side_render' => true,
                'type'   => 'common',
                'active' => true,
            ],
            'breadcrumbs' => [
                'label'  => __('Breadcrumbs','smartshop'),
                'name'   => 'smartshop/breadcrumbs',
                'server_side_render' => true,
                'type'   => 'common',
                'active' => true,
            ],
            'recently_viewed_products' => [
                'label'  => __('Recently Viewed Products','smartshop'),
                'name'   => 'smartshop/recently-viewed-products',
                'server_side_render' => true,
                'type'   => 'common',
                'active' => true,
            ],
            'testimonial' => [
                'label'  => __('Testimonial','smartshop'),
                'name'   => 'smartshop/testimonial',
                'server_side_render' => true,
                'type'   => 'common',
                'active' => true,
                'enqueue_assets' => function(){
                    wp_enqueue_style('smartshop-testimonial');
                }
            ],

            'product_title' => [
                'label'  => __('Product Title','smartshop'),
                'name'   => 'smartshop/product-title',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_price' => [
                'label'  => __('Product Price','smartshop'),
                'name'   => 'smartshop/product-price',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_addtocart' => [
                'label'  => __('Product Add To Cart','smartshop'),
                'name'   => 'smartshop/product-addtocart',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
                // 'enqueue_assets' => function(){
                //     wp_enqueue_style('dashicons');
                // },
            ],
            'product_short_description' => [
                'label'  => __('Product Short Description','smartshop'),
                'name'   => 'smartshop/product-short-description',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_description' => [
                'label'  => __('Product Description','smartshop'),
                'name'   => 'smartshop/product-description',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_rating' => [
                'label'  => __('Product Rating','smartshop'),
                'name'   => 'smartshop/product-rating',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_image' => [
                'label'  => __('Product Image','smartshop'),
                'name'   => 'smartshop/product-image',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_video_gallery' => [
                'label'  => __('Product Video Gallery','smartshop'),
                'name'   => 'smartshop/product-video-gallery',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_meta' => [
                'label'  => __('Product Meta','smartshop'),
                'name'   => 'smartshop/product-meta',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_categories' => [
                'label'  => __('Product Categories','smartshop'),
                'name'   => 'smartshop/product-categories',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_tags' => [
                'label'  => __('Product Tags','smartshop'),
                'name'   => 'smartshop/product-tags',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_sku' => [
                'label'  => __('Product SKU','smartshop'),
                'name'   => 'smartshop/product-sku',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'call_for_price' => [
                'label'  => __('Call For Price','smartshop'),
                'name'   => 'smartshop/call-for-price',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'suggest_price' => [
                'label'  => __('Suggest Price','smartshop'),
                'name'   => 'smartshop/suggest-price',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_additional_info' => [
                'label'  => __('Product Additional Info','smartshop'),
                'name'   => 'smartshop/product-additional-info',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_tabs' => [
                'label'  => __('Product Tabs','smartshop'),
                'name'   => 'smartshop/product-tabs',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_reviews' => [
                'label'  => __('Product Reviews','smartshop'),
                'name'   => 'smartshop/product-reviews',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_stock' => [
                'label'  => __('Product Stock','smartshop'),
                'name'   => 'smartshop/product-stock',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_qrcode' => [
                'label'  => __('Product QR Code','smartshop'),
                'name'   => 'smartshop/product-qrcode',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_related' => [
                'label'  => __('Product Related','smartshop'),
                'name'   => 'smartshop/product-related',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_upsell' => [
                'label'  => __('Product Upsell','smartshop'),
                'name'   => 'smartshop/product-upsell',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],

            'shop_archive_product' => [
                'title'  => __('Archive Layout Default','smartshop'),
                'name'   => 'smartshop/shop-archive-default',
                'server_side_render' => true,
                'type'   => 'shop',
                'active' => true,
            ],
            'archive_result_count' => [
                'title'  => __('Archive Result Count','smartshop'),
                'name'   => 'smartshop/archive-result-count',
                'server_side_render' => true,
                'type'   => 'shop',
                'active' => true,
            ],
            'archive_catalog_ordering' => [
                'title'  => __('Archive Catalog Ordering','smartshop'),
                'name'   => 'smartshop/archive-catalog-ordering',
                'server_side_render' => true,
                'type'   => 'shop',
                'active' => true,
            ],
            'product_filter' => [
                'title'  => __('Product Filter','smartshop'),
                'name'   => 'smartshop/product-filter',
                'server_side_render' => true,
                'type'   => 'shop',
                'active' => true,
                'enqueue_assets' => function(){
                    wp_enqueue_script('jquery-ui-slider');
                }
            ],
            'product_horizontal_filter' => [
                'title'  => __('Product Horizintal Filter','smartshop'),
                'name'   => 'smartshop/product-horizontal-filter',
                'server_side_render' => true,
                'type'   => 'shop',
                'active' => true,
                'enqueue_assets' => function(){
                    wp_enqueue_style('smartshop-select2');
                    wp_enqueue_script('select2-min');
                }
            ]
            
        ];

        return apply_filters( 'smartshop_block_list', $blockList );
        
    }


}
