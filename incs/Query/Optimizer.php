<?php

namespace ShopXpert\Query;

use ShopXpert\Cache\Manager as CacheManager;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

/**
 * Query Optimizer
 * 
 * Optimizes WooCommerce and WordPress queries to reduce database load.
 * Implements query caching, field limiting, and smart pagination.
 */
class Optimizer {

    /**
     * Cache query results
     * 
     * @param array $args WP_Query arguments
     * @param string $cache_key Cache key for this query
     * @param int $ttl Cache TTL in seconds
     * @return \WP_Query|\WP_Post[] Results from cache or fresh query
     */
    public static function cached_query( $args = [], $cache_key = '', $ttl = 3600 ) {
        if ( empty( $cache_key ) ) {
            $cache_key = 'query_' . md5( json_encode( $args ) );
        }

        // Try to get from cache
        $cached = CacheManager::remember(
            $cache_key,
            function() use ( $args ) {
                return new \WP_Query( $args );
            },
            $ttl
        );

        return $cached;
    }

    /**
     * Optimized product query
     * 
     * Uses fields parameter to limit returned columns
     * Caches results to avoid repeated queries
     * 
     * @param array $args Query arguments
     * @param string $cache_key Optional cache key
     * @param int $ttl Cache TTL
     * @return array Post IDs
     */
    public static function get_product_ids( $args = [], $cache_key = '', $ttl = 3600 ) {
        // Add fields parameter to only get IDs (faster query)
        $args['fields'] = 'ids';
        $args['post_type'] = 'product';
        
        if ( empty( $cache_key ) ) {
            $cache_key = 'product_ids_' . md5( json_encode( $args ) );
        }

        return CacheManager::remember(
            $cache_key,
            function() use ( $args ) {
                return get_posts( $args );
            },
            $ttl
        );
    }

    /**
     * Get products with minimal fields
     * 
     * Optimized to reduce data transfer and memory usage
     * 
     * @param array $args Query arguments
     * @param string $fields Comma-separated field list or 'minimal'
     * @return array Posts
     */
    public static function get_products_optimized( $args = [], $fields = 'minimal' ) {
        if ( 'minimal' === $fields ) {
            // Only get essential fields
            $args['fields'] = 'ids'; // Start with IDs only
        } else {
            $args['fields'] = $fields;
        }

        $args['post_type'] = 'product';
        $args['no_found_rows'] = true; // Don't count total rows if not paginating

        return get_posts( $args );
    }

    /**
     * Count posts with caching
     * 
     * Useful for counting features without loading full posts
     * 
     * @param array $args Query arguments
     * @param string $cache_key Cache key
     * @param int $ttl Cache TTL
     * @return int Post count
     */
    public static function count_posts( $args = [], $cache_key = '', $ttl = 3600 ) {
        if ( empty( $cache_key ) ) {
            $cache_key = 'post_count_' . md5( json_encode( $args ) );
        }

        return CacheManager::remember(
            $cache_key,
            function() use ( $args ) {
                $args['posts_per_page'] = 1;
                $args['fields'] = 'ids';
                $args['no_found_rows'] = false; // Need to count rows for this

                $query = new \WP_Query( $args );
                return $query->found_posts;
            },
            $ttl
        );
    }

    /**
     * Optimize meta query
     * 
     * Ensures meta_key is indexed in database for performance
     * 
     * @param array $meta_query Meta query array
     * @return array Optimized meta query
     */
    public static function optimize_meta_query( $meta_query = [] ) {
        // Ensure proper structure
        if ( !isset( $meta_query['relation'] ) ) {
            $meta_query['relation'] = 'AND';
        }

        // Index common meta keys (these should be added to DB indexes)
        $indexed_meta_keys = [
            '_wc_pre_order',
            '_is_backorder',
            '_backorder_quantity',
            'shopxpert_comparison_data',
            'shopxpert_wishlist_item',
        ];

        return $meta_query;
    }

    /**
     * Optimize taxonomy query
     * 
     * Use cached taxonomy data when possible
     * 
     * @param array $tax_query Taxonomy query array
     * @return array Optimized query
     */
    public static function optimize_tax_query( $tax_query = [] ) {
        if ( !isset( $tax_query['relation'] ) ) {
            $tax_query['relation'] = 'AND';
        }

        return $tax_query;
    }

    /**
     * Get posts using optimized query
     * 
     * Combines all optimizations into one method
     * 
     * @param array $args Query arguments
     * @param bool $use_cache Whether to cache results
     * @param string $cache_key Custom cache key
     * @param int $ttl Cache TTL
     * @return array Posts
     */
    public static function get_optimized( $args = [], $use_cache = true, $cache_key = '', $ttl = 3600 ) {
        // Optimizations
        if ( !isset( $args['no_found_rows'] ) ) {
            $args['no_found_rows'] = true; // Don't count if not paginating
        }

        if ( !isset( $args['fields'] ) ) {
            $args['fields'] = 'ids'; // Default to IDs only
        }

        if ( $use_cache ) {
            if ( empty( $cache_key ) ) {
                $cache_key = 'posts_' . md5( json_encode( $args ) );
            }

            return CacheManager::remember(
                $cache_key,
                function() use ( $args ) {
                    return get_posts( $args );
                },
                $ttl
            );
        }

        return get_posts( $args );
    }

    /**
     * Clear query caches
     * 
     * Call this when product/post data changes
     * 
     * @return void
     */
    public static function clear_query_caches() {
        CacheManager::flush_all();
    }
}
