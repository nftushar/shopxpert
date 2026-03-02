<?php

namespace ShopXpert\Helpers;

use ShopXpert\Cache\Manager as CacheManager;

if (!defined('ABSPATH')) exit;

/**
 * Products Helper
 * 
 * Handles product-related functions
 */
class Products {

    /**
     * Get the last product ID
     * 
     * @return int
     */
    public static function get_last_id() {
        global $wpdb;
        
        $value = CacheManager::remember('last_product_id', function() use ($wpdb) {
            return (int) $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT MAX(ID) FROM {$wpdb->prefix}posts WHERE post_type = %s AND post_status = %s",
                    'product',
                    'publish'
                )
            );
        }, HOUR_IN_SECONDS);

        return $value;
    }

    /**
     * Generate product query arguments
     * 
     * @param array $query_args Query arguments
     * @return array
     */
    public static function generate_query($query_args = []) {
        $meta_query = $tax_query = [];
        $per_page = !empty($query_args['per_page']) ? $query_args['per_page'] : 3;

        // Categories query
        if (isset($query_args['categories'])) {
            $tax_query[] = [
                'taxonomy' => 'product_cat',
                'terms' => $query_args['categories'],
                'field' => 'slug',
                'include_children' => false
            ];
        }

        // Tags query
        if (isset($query_args['tags'])) {
            $tax_query[] = [
                'taxonomy' => 'product_tag',
                'terms' => $query_args['tags'],
                'field' => 'slug',
                'include_children' => false
            ];
        }

        // Featured products
        if ($query_args['product_type'] === 'featured') {
            $tax_query[] = [
                'taxonomy' => 'product_visibility',
                'field' => 'name',
                'terms' => 'featured',
                'operator' => 'IN',
            ];
        }

        // Hide hidden items
        if (isset($query_args['hidden']) && $query_args['hidden'] === true) {
            $tax_query[] = [
                'taxonomy' => 'product_visibility',
                'field' => 'name',
                'terms' => ['exclude-from-search', 'exclude-from-catalog'],
                'operator' => 'NOT IN',
                'include_children' => false,
            ];
        }

        // Hide out of stock
        $hide_out_of_stock = isset($query_args['hide_out_of_stock']) && $query_args['hide_out_of_stock'] === true 
            ? 'yes' 
            : get_option('woocommerce_hide_out_of_stock_items', 'no');
            
        if ($hide_out_of_stock === 'yes') {
            $meta_query[] = [
                'key' => '_stock_status',
                'value' => 'instock',
                'compare' => '==',
            ];
        }

        $args = [
            'post_type' => 'product',
            'post_status' => 'publish',
            'ignore_sticky_posts' => 1,
            'posts_per_page' => $per_page,
            'meta_query' => $meta_query,
            'tax_query' => $tax_query,
        ];

        // Product type specific query modifications
        switch ($query_args['product_type']) {
            case 'sale':
                $args['post__in'] = array_merge([0], wc_get_product_ids_on_sale());
                break;

            case 'best_selling':
                $args['meta_key'] = 'total_sales';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'desc';
                break;

            case 'top_rated':
                $args['meta_key'] = '_wc_average_rating';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'desc';
                break;

            case 'mixed_order':
                $args['orderby'] = 'rand';
                break;

            case 'show_byid':
            case 'show_byid_manually':
                $args['post__in'] = $query_args['product_ids'];
                $args['orderby'] = $query_args['product_ids'];
                break;

            default: // Recent
                $args['orderby'] = 'date';
                $args['order'] = 'desc';
                break;
        }

        // Custom order
        if (isset($query_args['custom_order'])) {
            $args['orderby'] = $query_args['custom_order']['orderby'];
            $args['order'] = $query_args['custom_order']['order'];
        }

        return $args;
    }

    /**
     * Get min/max price limits
     * 
     * @return array
     */
    public static function get_price_limits() {
        global $wpdb;

        return CacheManager::remember('minmax_price_limit', function() use ($wpdb) {
            $min = (int) $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT MIN(CAST(meta_value AS UNSIGNED)) FROM {$wpdb->postmeta} WHERE meta_key = %s",
                    '_price'
                )
            );
            $max = (int) $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT MAX(CAST(meta_value AS UNSIGNED)) FROM {$wpdb->postmeta} WHERE meta_key = %s",
                    '_price'
                )
            );
            return ['min' => $min, 'max' => $max];
        }, HOUR_IN_SECONDS);
    }

    /**
     * Get product taxonomies
     * 
     * @param string $object Post type
     * @param bool $skip_terms Whether to skip term-based taxonomies
     * @return array
     */
    public static function get_taxonomies($object = 'product', $skip_terms = false) {
        $all_taxonomies = get_object_taxonomies($object);
        $taxonomies_list = [];

        foreach ($all_taxonomies as $taxonomy_data) {
            $taxonomy = get_taxonomy($taxonomy_data);
            if ($skip_terms === true) {
                if ($taxonomy->show_ui && 'pa_' !== substr($taxonomy_data, 0, 3)) {
                    $taxonomies_list[$taxonomy_data] = $taxonomy->label;
                }
            } else {
                if ($taxonomy->show_ui) {
                    $taxonomies_list[$taxonomy_data] = $taxonomy->label;
                }
            }
        }

        return $taxonomies_list;
    }
}
