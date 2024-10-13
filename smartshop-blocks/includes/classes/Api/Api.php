<?php
namespace ShopXpertBlocks\Api;

use Exception;
use WP_REST_Server;

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Load general WP action hook
 */
class Api
{

    public $namespace = '';

    /**
     * The Constructor.
     */
    public function __construct()
    {
        $this->namespace = 'shopxpert/v1';
    }

    /**
     * Resgister routes
     */
    public function register_routes()
    {

        register_rest_route(
            $this->namespace,
            'category',
            [
                'methods' => WP_REST_Server::READABLE,
                'args' => [
                    'querySlug' => [],
                    'queryLimit' => [],
                    'queryOrder' => [],
                    'queryType' => [],
                    'wpnonce' => []
                ],
                'callback' => [$this, 'get_category_data'],
                'permission_callback' => [$this, 'permission_check'],
            ]
        );

        register_rest_route(
            $this->namespace,
            'products',
            [
                'methods' => WP_REST_Server::READABLE,
                'args' => [
                    'perPage' => [],
                    'categories' => [],
                    'orderBy' => [],
                    'order' => [],
                    'filterBy' => [],
                    'offset' => [],
                    'include' => [],
                    'exclude' => [],
                    'wpnonce' => []
                ],
                'callback' => [$this, 'get_post_data'],
                'permission_callback' => '__return_true'
            ]
        );

        register_rest_route(
            $this->namespace,
            'sampledata/product',
            [
                'methods' => WP_REST_Server::READABLE,
                'args' => [
                    'wpnonce' => []
                ],
                'callback' => [$this, 'get_last_product_data'],
                'permission_callback' => '__return_true'
            ]
        );

        // register_rest_route( $this->namespace, 'products/(?P<id>[\d]+)', 
        //     [
        //         'methods' => WP_REST_Server::READABLE,
        //         'args' => [
        //             'wpnonce'  => []
        //         ],
        //         'callback' => [ $this, 'get_product_data' ],
        //         'permission_callback' => '__return_true'
        //     ]
        // );

        register_rest_route(
            $this->namespace,
            'imagesizes',
            [
                'methods' => WP_REST_Server::READABLE,
                'args' => [
                    'wpnonce' => []
                ],
                'callback' => [$this, 'get_image_sizes'],
                'permission_callback' => '__return_true'
            ]
        );

        // Get Post List
        register_rest_route(
            $this->namespace,
            'get-poslist',
            [
                'methods' => WP_REST_Server::READABLE,
                'args' => [
                    'postType' => [],
                    'perPage' => [],
                    'metaQuery' => [],
                    'wpnonce' => []
                ],
                'callback' => [$this, 'get_post_list_data'],
                'permission_callback' => [$this, 'permission_check'],
            ]
        );

        // Get Taxonomies List
        register_rest_route(
            $this->namespace,
            'get-taxonomies',
            [
                'methods' => WP_REST_Server::READABLE,
                'args' => [
                    'object' => [],
                    'skipTerms' => [],
                    'wpnonce' => []
                ],
                'callback' => [$this, 'get_taxonomies_list_data'],
                'permission_callback' => [$this, 'permission_check'],
            ]
        );

        // Get Option Data
        register_rest_route(
            $this->namespace,
            'get-wloptions',
            [
                'methods' => WP_REST_Server::READABLE,
                'args' => [
                    'optionSection' => [],
                    'optionKey' => [],
                ],
                'callback' => [$this, 'get_options_data'],
                'permission_callback' => [$this, 'permission_check'],
            ]
        );

        // Template Library
        \ShopXpertBlocks\Template_Library::instance()->register_routes($this->namespace);

        // CSS
        \ShopXpertBlocks\Manage_Styles::instance()->register_routes($this->namespace);

    }

    /**
     * Api permission check
     */
    public function permission_check()
    {
        if (current_user_can('edit_posts')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get category data
     */
    public function get_category_data($request)
    {

        if (!isset($_REQUEST['wpnonce']) || !wp_verify_nonce($_REQUEST['wpnonce'], 'shopxpertblock-nonce')) {
            return rest_ensure_response([]);
        }

        $data = shopxpertBlocks_taxnomy_data($request['querySlug'], $request['queryLimit'], $request['queryOrder'], $request['queryType']);
        return rest_ensure_response($data);

    }

    /**
     * Get Image sizes data
     */
    public function get_image_sizes($request)
    {

        if (!isset($_REQUEST['wpnonce']) || !wp_verify_nonce($_REQUEST['wpnonce'], 'shopxpertblock-nonce')) {
            return rest_ensure_response([]);
        }

        $data = shopxpertBlocks_get_image_size();
        return rest_ensure_response($data);

    }

    /**
     * Get Post data
     */
    public function get_post_data($request)
    {

        if (!isset($_REQUEST['wpnonce']) || !wp_verify_nonce($_REQUEST['wpnonce'], 'shopxpertblock-nonce')) {
            return rest_ensure_response([]);
        }

        if ( !function_exists('wp_strip_all_tags') ) {
            require_once( ABSPATH . 'wp-includes/formatting.php' );
        }

        $data = [];
        $loop = new \WP_Query(shopxpertBlocks_Product_Query($request));

        if ($loop->have_posts()) {
            while ($loop->have_posts()) {
                $loop->the_post();

                $item = array();
                $product_id = get_the_ID();
                $product = wc_get_product($product_id);
                $user_id = get_the_author_meta('ID');
                $item['id'] = $product_id;
                $item['time'] = get_the_date();
                $item['title'] = get_the_title();
                $item['permalink'] = get_permalink();
                $item['excerpt'] = wp_strip_all_tags(get_the_excerpt());
                $item['content'] = wp_strip_all_tags(get_the_content());
                $item['price_sale'] = $product->get_sale_price();
                $item['price_regular'] = $product->get_regular_price();
                $item['on_sale'] = $product->is_on_sale();
                $item['badge'] = [
                    'sale_badge' => shopxpert_sale_flash('default', false),
                ];
                $item['discount'] = ($item['price_sale'] && $item['price_regular']) ? round(($item['price_regular'] - $item['price_sale']) / $item['price_regular'] * 100) . '%' : '';
                $item['price_html'] = $product->get_price_html();
                $item['stock'] = $product->get_stock_status();
                $item['featured'] = $product->is_featured();
                $item['rating'] = [
                    'count' => $product->get_rating_count(),
                    'average' => $product->get_average_rating(),
                    'html' => wc_get_rating_html($product->get_average_rating(), $product->get_rating_count()),
                    'html2' => shopxpert_wc_get_rating_html('yes'),
                ];
                $cart_btn_class = $product->is_purchasable() && $product->is_in_stock() ? ' add_to_cart_button' : '';
                $cart_btn_class .= $product->supports('ajax_add_to_cart') && $product->is_purchasable() && $product->is_in_stock() ? ' ajax_add_to_cart' : '';
                $item['addtocart'] = [
                    'link' => $product->add_to_cart_url(),
                    'text' => __('Add To Cart', 'shopxpert'),
                    'class' => $cart_btn_class,
                ];
                $item['wishlist'] = [
                    'status' => shopxpert_has_wishlist_plugin(),
                    'html' => shopxpert_add_to_wishlist_button()
                ];
                $item['compare'] = [
                    'status' => shopxpert_exist_compare_plugin(),
                    'html' => shopxpertBlocks_compare_button(array('style' => 2)),
                    'html2' => shopxpertBlocks_compare_button(array('style' => 2, 'btn_text' => '<i class="fa fa-exchange"></i>', 'btn_added_txt' => '<i class="fa fa-exchange"></i>')),
                ];

                $time = current_time('timestamp');
                $time_to = strtotime($product->get_date_on_sale_to());
                $item['deal'] = ($item['price_sale'] && $time_to > $time) ? gmdate('Y/m/d', $time_to) : '';

                // Images
                if (has_post_thumbnail()) {
                    $thumb_id = get_post_thumbnail_id($product_id);
                    $image_sizes = shopxpertBlocks_get_image_size();
                    $image_src = array();
                    foreach ($image_sizes as $key => $size) {
                        $image_src[$key] = [
                            'src' => wp_get_attachment_image_src($thumb_id, $key, false)[0],
                            'html' => $product->get_image($key)
                        ];
                    }
                    $item['image'] = $image_src;
                } else {
                    $item['image'] = array(
                        'full' => [
                            'src' => wc_placeholder_img_src('full'),
                            'html' => '<img src="' . wc_placeholder_img_src('full') . '" alt="' . get_the_title() . '">',
                        ]
                    );
                }

                // Tags
                $tags = get_the_terms($product_id, (isset($request['tag']) ? esc_attr($request['tag']) : 'product_tag'));
                if (!empty($tag)) {
                    $tag_list = array();
                    foreach ($tags as $tag) {
                        $tag_list[] = [
                            'slug' => $tag->slug,
                            'name' => $tag->name,
                            'url' => get_term_link($tag->term_id)
                        ];
                    }
                    $item['tags'] = $tag_list;
                }

                // Categories
                $categories = get_the_terms($product_id, (isset($request['cat']) ? esc_attr($request['cat']) : 'product_cat'));
                if (!empty($categories)) {
                    $category_list = array();
                    foreach ($categories as $category) {
                        $category_list[] = [
                            'slug' => $category->slug,
                            'name' => $category->name,
                            'url' => get_term_link($category->term_id)
                        ];
                    }
                    $item['categories'] = $category_list;
                }
                $data[] = $item;
            }
            wp_reset_postdata();
        }
        return rest_ensure_response($data);

    }

    /**
     * Get Last Product data
     */
    public function get_last_product_data($request)
    {

        // if ( !isset( $_REQUEST['wpnonce'] ) || !wp_verify_nonce( $_REQUEST['wpnonce'], 'shopxpertblock-nonced') ){
        //     return rest_ensure_response([]);
        // }

        // Load WooCommerce frontend files
        if (function_exists('WC')) {
            \WC()->frontend_includes();
        }

        if (!class_exists('\ShopXpert_Default_Data')) {
            return [];
        }

        $product = \ShopXpert_Default_Data::instance()->get_product('');

        $data = $item = [];

        $item['id'] = $product->get_id();
        $item['title'] = $product->get_title();

        $cart_btn_class = $product->is_purchasable() && $product->is_in_stock() ? ' add_to_cart_button' : '';
        $cart_btn_class .= $product->supports('ajax_add_to_cart') && $product->is_purchasable() && $product->is_in_stock() ? ' ajax_add_to_cart' : '';
        $item['addtocart'] = [
            'link' => $product->add_to_cart_url(),
            'class' => $cart_btn_class,
            'text' => $product->single_add_to_cart_text(),
            'html' => \ShopXpert_Default_Data::instance()->default('wl-product-add-to-cart')
        ];

        $item['price_html'] = \ShopXpert_Default_Data::instance()->default('wl-single-product-price');
        $item['short_description'] = \ShopXpert_Default_Data::instance()->default('wl-single-product-short-description');
        $item['description'] = \ShopXpert_Default_Data::instance()->default('wl-single-product-description');
        $item['rating'] = \ShopXpert_Default_Data::instance()->default('wl-single-product-rating');
        $item['image'] = \ShopXpert_Default_Data::instance()->default('wl-single-product-image');
        $item['meta_info'] = \ShopXpert_Default_Data::instance()->default('wl-single-product-meta');
        $item['additional_info'] = \ShopXpert_Default_Data::instance()->default('wl-product-additional-information');
        $item['product_tabs'] = \ShopXpert_Default_Data::instance()->default('wl-product-data-tabs2');
        $item['product_reviews'] = \ShopXpert_Default_Data::instance()->default('wl-single-product-reviews');
        $item['product_stock'] = \ShopXpert_Default_Data::instance()->default('wl-single-product-stock');
        $item['product_upsell'] = \ShopXpert_Default_Data::instance()->default('wl-single-product-upsell');
        $item['product_related'] = \ShopXpert_Default_Data::instance()->default('wl-product-related', array('orderby' => 'date', 'order' => 'desc'));

        $data = $item;

        return rest_ensure_response($data);


    }

    /**
     * Post List Callable Function
     *
     * @param [type] $request
     */
    public function get_post_list_data($request)
    {
        if (!isset($_REQUEST['wpnonce']) || !wp_verify_nonce($_REQUEST['wpnonce'], 'shopxpertblock-nonce')) {
            return rest_ensure_response([]);
        }

        $post_type = isset($request['postType']) ? $request['postType'] : 'shopxpert-template';
        $per_page = isset($request['perPage']) ? $request['perPage'] : -1;
        $meta_query = isset($request['metaQuery']) ? $request['metaQuery'] : [];

        $data = [];
        $offers = get_posts(
            [
                'post_type' => $post_type,
                'post_status' => 'publish',
                'posts_per_page' => $per_page,
                'meta_query' => [$meta_query]
            ]
        );

        if (!empty($offers)) {
            foreach ($offers as $offer) {
                $item = [];
                $item['id'] = $offer->ID;
                $item['title'] = $offer->post_title;
                $data[] = $item;
            }
        }

        return rest_ensure_response($data);
    }

    /**
     * Taxonomies List Callable Function
     *
     * @param [type] $request
     */
    public function get_taxonomies_list_data($request)
    {
        if (!isset($_REQUEST['wpnonce']) || !wp_verify_nonce($_REQUEST['wpnonce'], 'shopxpertblock-nonce')) {
            return rest_ensure_response([]);
        }

        $object = isset($request['object']) ? $request['object'] : 'product';
        $skip_terms = isset($request['skipTerms']) ? $request['skipTerms'] : [];

        $data = [];

        $all_taxonomies = get_object_taxonomies( $object );
        foreach ( $all_taxonomies as $taxonomy_data ) {
            $taxonomy = get_taxonomy( $taxonomy_data );
            if( $skip_terms === true ){
                if( ( $taxonomy->show_ui ) && ( 'pa_' !== substr( $taxonomy_data, 0, 3 ) ) ) {
                    $item = [];
                    $item['key'] = $taxonomy_data;
                    $item['title'] = $taxonomy->label;
                    $data[] = $item;
                }
            }else{
                if( $taxonomy->show_ui ) {
                    $item = [];
                    $item['key'] = $taxonomy_data;
                    $item['title'] = $taxonomy->label;
                    $data[] = $item;
                }
            }
        }
        

        return rest_ensure_response($data);
    }

    /**
     * Get Option data
     * @param mixed $request
     * @return \WP_Error|\WP_REST_Response
     */
    public function get_options_data($request){
        if (!isset($_REQUEST['wpnonce']) || !wp_verify_nonce($_REQUEST['wpnonce'], 'shopxpertblock-nonce')) {
            return rest_ensure_response([]);
        }

        $option_section = isset($request['optionSection']) ? $request['optionSection'] : '';
        $option_key     = isset($request['optionKey']) ? $request['optionKey'] : '';

        $getData = shopxpertBlocks_get_option( $option_key, $option_section );
        $data = [];
        
        if (!empty($getData)) {
            $i = 0;
            foreach ($getData as $dataItem) {
                $i++;
                $item = [];
                $item['id'] = $i;
                $item['title'] = !empty( $dataItem['title'] ) ? $dataItem['title'] : __( 'Unnamed Deal', 'shopxpert' );
                $data[] = $item;
            }
        }

        return rest_ensure_response($data);
    }


}
