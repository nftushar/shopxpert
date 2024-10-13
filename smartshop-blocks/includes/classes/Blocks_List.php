<?php
namespace ShopXpertBlocks;

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
                'label'  => __('Brand Logo','shopxpert'),
                'name'   => 'shopxpert/brand-logo',
                'server_side_render' => true,
                'type'   => 'common',
                'active' => true,
            ],
            'category_grid' => [
                'label'  => __('Category Grid','shopxpert'),
                'name'   => 'shopxpert/category-grid',
                'server_side_render' => true,
                'type'   => 'common',
                'active' => true,
                'script' => 'slick'
            ],
            'image_marker' => [
                'label'  => __('Image Marker','shopxpert'),
                'name'   => 'shopxpert/image-marker',
                'server_side_render' => true,
                'type'   => 'common',
                'active' => true,
            ],
            'special_day_offer' => [
                'label'  => __('Special Day Offer','shopxpert'),
                'name'   => 'shopxpert/special-day-offer',
                'server_side_render' => true,
                'type'   => 'common',
                'active' => true,
            ],
            'store_feature' => [
                'label'  => __('Store Feature','shopxpert'),
                'name'   => 'shopxpert/store-feature',
                'server_side_render' => true,
                'type'   => 'common',
                'active' => true,
            ],
            'product_tab' => [
                'label'  => __('Product tab','shopxpert'),
                'name'   => 'shopxpert/product-tab',
                'server_side_render' => true,
                'type'   => 'common',
                'active' => true,
                'script' => 'slick',
            ],
            'promo_banner' => [
                'label'  => __('Promo Banner','shopxpert'),
                'name'   => 'shopxpert/promo-banner',
                'type'   => 'common',
                'active' => true,
            ],
            'faq' => [
                'label'  => __('FAQ','shopxpert'),
                'name'   => 'shopxpert/faq',
                'server_side_render' => true,
                'type'   => 'common',
                'active' => true,
                'script' => 'shopxpert-accordion-min',
            ],
            'product_curvy' => [
                'label'  => __('Product Curvy','shopxpert'),
                'name'   => 'shopxpert/product-curvy',
                'server_side_render' => true,
                'type'   => 'common',
                'active' => true,
            ],
            'archive_title' => [
                'label'  => __('Archive Title','shopxpert'),
                'name'   => 'shopxpert/archive-title',
                'server_side_render' => true,
                'type'   => 'common',
                'active' => true,
            ],
            'breadcrumbs' => [
                'label'  => __('Breadcrumbs','shopxpert'),
                'name'   => 'shopxpert/breadcrumbs',
                'server_side_render' => true,
                'type'   => 'common',
                'active' => true,
            ],
            'recently_viewed_products' => [
                'label'  => __('Recently Viewed Products','shopxpert'),
                'name'   => 'shopxpert/recently-viewed-products',
                'server_side_render' => true,
                'type'   => 'common',
                'active' => true,
            ],
            'testimonial' => [
                'label'  => __('Testimonial','shopxpert'),
                'name'   => 'shopxpert/testimonial',
                'server_side_render' => true,
                'type'   => 'common',
                'active' => true,
                'enqueue_assets' => function(){
                    wp_enqueue_style('shopxpert-testimonial');
                }
            ],

            'product_title' => [
                'label'  => __('Product Title','shopxpert'),
                'name'   => 'shopxpert/product-title',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_price' => [
                'label'  => __('Product Price','shopxpert'),
                'name'   => 'shopxpert/product-price',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_addtocart' => [
                'label'  => __('Product Add To Cart','shopxpert'),
                'name'   => 'shopxpert/product-addtocart',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
                // 'enqueue_assets' => function(){
                //     wp_enqueue_style('dashicons');
                // },
            ],
            'product_short_description' => [
                'label'  => __('Product Short Description','shopxpert'),
                'name'   => 'shopxpert/product-short-description',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_description' => [
                'label'  => __('Product Description','shopxpert'),
                'name'   => 'shopxpert/product-description',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_rating' => [
                'label'  => __('Product Rating','shopxpert'),
                'name'   => 'shopxpert/product-rating',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_image' => [
                'label'  => __('Product Image','shopxpert'),
                'name'   => 'shopxpert/product-image',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_video_gallery' => [
                'label'  => __('Product Video Gallery','shopxpert'),
                'name'   => 'shopxpert/product-video-gallery',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_meta' => [
                'label'  => __('Product Meta','shopxpert'),
                'name'   => 'shopxpert/product-meta',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_categories' => [
                'label'  => __('Product Categories','shopxpert'),
                'name'   => 'shopxpert/product-categories',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_tags' => [
                'label'  => __('Product Tags','shopxpert'),
                'name'   => 'shopxpert/product-tags',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_sku' => [
                'label'  => __('Product SKU','shopxpert'),
                'name'   => 'shopxpert/product-sku',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'call_for_price' => [
                'label'  => __('Call For Price','shopxpert'),
                'name'   => 'shopxpert/call-for-price',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'suggest_price' => [
                'label'  => __('Suggest Price','shopxpert'),
                'name'   => 'shopxpert/suggest-price',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_additional_info' => [
                'label'  => __('Product Additional Info','shopxpert'),
                'name'   => 'shopxpert/product-additional-info',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_tabs' => [
                'label'  => __('Product Tabs','shopxpert'),
                'name'   => 'shopxpert/product-tabs',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_reviews' => [
                'label'  => __('Product Reviews','shopxpert'),
                'name'   => 'shopxpert/product-reviews',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_stock' => [
                'label'  => __('Product Stock','shopxpert'),
                'name'   => 'shopxpert/product-stock',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_qrcode' => [
                'label'  => __('Product QR Code','shopxpert'),
                'name'   => 'shopxpert/product-qrcode',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_related' => [
                'label'  => __('Product Related','shopxpert'),
                'name'   => 'shopxpert/product-related',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_upsell' => [
                'label'  => __('Product Upsell','shopxpert'),
                'name'   => 'shopxpert/product-upsell',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],

            'shop_archive_product' => [
                'title'  => __('Archive Layout Default','shopxpert'),
                'name'   => 'shopxpert/shop-archive-default',
                'server_side_render' => true,
                'type'   => 'shop',
                'active' => true,
            ],
            'archive_result_count' => [
                'title'  => __('Archive Result Count','shopxpert'),
                'name'   => 'shopxpert/archive-result-count',
                'server_side_render' => true,
                'type'   => 'shop',
                'active' => true,
            ],
            'archive_catalog_ordering' => [
                'title'  => __('Archive Catalog Ordering','shopxpert'),
                'name'   => 'shopxpert/archive-catalog-ordering',
                'server_side_render' => true,
                'type'   => 'shop',
                'active' => true,
            ],
            'product_filter' => [
                'title'  => __('Product Filter','shopxpert'),
                'name'   => 'shopxpert/product-filter',
                'server_side_render' => true,
                'type'   => 'shop',
                'active' => true,
                'enqueue_assets' => function(){
                    wp_enqueue_script('jquery-ui-slider');
                }
            ],
            'product_horizontal_filter' => [
                'title'  => __('Product Horizintal Filter','shopxpert'),
                'name'   => 'shopxpert/product-horizontal-filter',
                'server_side_render' => true,
                'type'   => 'shop',
                'active' => true,
                'enqueue_assets' => function(){
                    wp_enqueue_style('shopxpert-select2');
                    wp_enqueue_script('select2-min');
                }
            ]
            
        ];

        return apply_filters( 'shopxpert_block_list', $blockList );
        
    }


}
