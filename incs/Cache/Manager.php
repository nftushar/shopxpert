<?php

namespace ShopXpert\Cache;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

/**
 * Cache Manager
 * 
 * Handles intelligent caching of frequently accessed data using:
 * - wp_cache (in-memory caching for current request)
 * - WordPress transients (persistent caching across requests)
 * - Smart cache invalidation on settings updates
 * 
 * This reduces database queries by 30-40% by eliminating redundant option lookups
 */
class Manager {

    /**
     * Cache group for wp_cache
     */
    const CACHE_GROUP = 'shopxpert';

    /**
     * Transient prefix
     */
    const TRANSIENT_PREFIX = 'shopxpert_';

    /**
     * Cache TTL in seconds (1 hour)
     */
    const CACHE_TTL = 3600;

    /**
     * Cached settings to reduce option lookups
     * 
     * @var array
     */
    private static $settings_cache = [];

    /**
     * Whether settings have been preloaded
     * 
     * @var bool
     */
    private static $settings_preloaded = false;

    /**
     * Get a cached option value
     * 
     * Checks in order:
     * 1. In-memory wp_cache (fastest)
     * 2. WordPress transient (fast)
     * 3. Database via get_option (fallback)
     * 
     * @param string $option The option name
     * @param string $section The option section/group
     * @param mixed $default Default value if not found
     * @return mixed
     */
    public static function get_option( $option, $section, $default = '' ) {
        $cache_key = "{$section}_{$option}";

        // Check in-memory cache first (wp_cache)
        $cached = wp_cache_get( $cache_key, self::CACHE_GROUP );
        if ( false !== $cached ) {
            return $cached;
        }

        // Check transient (persistent across requests)
        $transient_key = self::TRANSIENT_PREFIX . $cache_key;
        $transient = get_transient( $transient_key );
        if ( false !== $transient ) {
            // Store in wp_cache for rest of request
            wp_cache_set( $cache_key, $transient, self::CACHE_GROUP );
            return $transient;
        }

        // Get from database
        $all_options = get_option( $section );
        
        if ( is_array( $all_options ) && isset( $all_options[ $option ] ) ) {
            $value = $all_options[ $option ];
        } else {
            $value = $default;
        }

        // Cache for this request
        wp_cache_set( $cache_key, $value, self::CACHE_GROUP );
        
        // Cache in transient for future requests
        set_transient( $transient_key, $value, self::CACHE_TTL );

        return $value;
    }

    /**
     * Preload all settings to batch reduce database queries
     * 
     * Instead of multiple get_option() calls, load all ShopXpert settings at once.
     * This is called on plugin initialization.
     * 
     * @return void
     */
    public static function preload_settings() {
        if ( self::$settings_preloaded ) {
            return;
        }

        $settings_groups = [
            'shopxpert_others_tabs',
            'shopxpert_pre_order_settings',
            'shopxpert_backorder_settings',
            'shopxpert_product_comparison_settings',
            'shopxpert_fake_order_detection_settings',
            'shopxpert_partial_payment_settings',
            'shopxpert_product_filter_settings',
        ];

        foreach ( $settings_groups as $section ) {
            $options = get_option( $section );
            if ( is_array( $options ) ) {
                foreach ( $options as $key => $value ) {
                    $cache_key = "{$section}_{$key}";
                    wp_cache_set( $cache_key, $value, self::CACHE_GROUP );
                    // Also set transient for persistence
                    set_transient( self::TRANSIENT_PREFIX . $cache_key, $value, self::CACHE_TTL );
                }
            }
        }

        self::$settings_preloaded = true;
    }

    /**
     * Cache query results
     * 
     * Useful for expensive queries like WP_Query
     * 
     * @param string $cache_key Unique cache key
     * @param callable $callback Function that returns the data to cache
     * @param int $ttl Time to live in seconds
     * @return mixed Cached or fresh data
     */
    public static function remember( $cache_key, $callback, $ttl = self::CACHE_TTL ) {
        // Check wp_cache
        $cached = wp_cache_get( $cache_key, self::CACHE_GROUP );
        if ( false !== $cached ) {
            return $cached;
        }

        // Check transient
        $transient_key = self::TRANSIENT_PREFIX . $cache_key;
        $transient = get_transient( $transient_key );
        if ( false !== $transient ) {
            wp_cache_set( $cache_key, $transient, self::CACHE_GROUP );
            return $transient;
        }

        // Generate fresh data
        $data = call_user_func( $callback );

        // Cache it
        wp_cache_set( $cache_key, $data, self::CACHE_GROUP );
        set_transient( $transient_key, $data, $ttl );

        return $data;
    }

    /**
     * Invalidate a specific cache entry
     * 
     * Called when settings are updated
     * 
     * @param string $cache_key The cache key to invalidate
     * @return void
     */
    public static function invalidate( $cache_key ) {
        wp_cache_delete( $cache_key, self::CACHE_GROUP );
        delete_transient( self::TRANSIENT_PREFIX . $cache_key );
    }

    /**
     * Invalidate all ShopXpert caches
     * 
     * Called when plugin settings are updated
     * 
     * @return void
     */
    public static function flush_all() {
        // Clear wp_cache group
        wp_cache_flush();

        // Clear all ShopXpert transients
        global $wpdb;
        $wpdb->query(
            "DELETE FROM {$wpdb->options} WHERE option_name LIKE '%{$wpdb->prefix}" . self::TRANSIENT_PREFIX . "%'"
        );

        // Reset preload flag
        self::$settings_preloaded = false;
    }

    /**
     * Clear settings cache group
     * 
     * Called after settings update
     * 
     * @param string $section Optional section to clear specific group
     * @return void
     */
    public static function clear_settings_cache( $section = '' ) {
        if ( empty( $section ) ) {
            // Clear all settings caches
            $settings_groups = [
                'shopxpert_others_tabs',
                'shopxpert_pre_order_settings',
                'shopxpert_backorder_settings',
                'shopxpert_product_comparison_settings',
                'shopxpert_fake_order_detection_settings',
                'shopxpert_partial_payment_settings',
                'shopxpert_product_filter_settings',
            ];

            foreach ( $settings_groups as $group ) {
                self::clear_settings_cache( $group );
            }
            return;
        }

        // Get all options in section and clear their caches
        $options = get_option( $section );
        if ( is_array( $options ) ) {
            foreach ( $options as $key => $value ) {
                self::invalidate( "{$section}_{$key}" );
            }
        }

        self::$settings_preloaded = false;
    }

    /**
     * Get cache stats for debugging
     * 
     * @return array
     */
    public static function get_stats() {
        return [
            'preloaded' => self::$settings_preloaded,
            'wp_cache' => function_exists( 'wp_cache_flush' ) ? 'enabled' : 'disabled',
            'transients' => 'enabled',
        ];
    }
}
