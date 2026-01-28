<?php

namespace ShopXpert\Database;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

/**
 * Database Schema Manager
 * 
 * Defines custom tables, indexes, and meta keys used by ShopXpert.
 * Helps identify optimization opportunities and maintain database health.
 */
class Schema {

    /**
     * Get all custom meta keys used by ShopXpert
     * 
     * These should ideally be indexed in the database for performance
     * 
     * @return array Meta keys with descriptions
     */
    public static function get_custom_meta_keys() {
        return [
            // Pre-orders
            '_wc_pre_order' => [
                'type' => 'product',
                'description' => 'Marks product as pre-order',
                'indexed' => true,
            ],
            '_pre_order_date' => [
                'type' => 'product',
                'description' => 'Pre-order availability date',
                'indexed' => true,
            ],
            'shopxpert_pre_order_data' => [
                'type' => 'post',
                'description' => 'Pre-order data for orders',
                'indexed' => false,
            ],

            // Backorder/Stock
            '_is_backorder' => [
                'type' => 'product',
                'description' => 'Marks product as backordered',
                'indexed' => true,
            ],
            '_backorder_quantity' => [
                'type' => 'product',
                'description' => 'Quantity on backorder',
                'indexed' => false,
            ],

            // Product Comparison
            'shopxpert_comparison_data' => [
                'type' => 'post',
                'description' => 'Comparison data for products',
                'indexed' => true,
            ],

            // Wishlist
            'shopxpert_wishlist_item' => [
                'type' => 'post',
                'description' => 'Wishlist item data',
                'indexed' => true,
            ],

            // Fake Order Detection
            'shopxpert_order_risk_score' => [
                'type' => 'post',
                'description' => 'Risk score for fake order detection',
                'indexed' => true,
            ],
        ];
    }

    /**
     * Get recommended database indexes
     * 
     * These should be created for optimal performance
     * Run create_indexes() to implement
     * 
     * @return array Indexes to create
     */
    public static function get_recommended_indexes() {
        global $wpdb;

        return [
            // Post meta indexes
            'idx_shopxpert_pre_order' => [
                'table' => $wpdb->postmeta,
                'columns' => ['meta_key', 'meta_value(10)'],
                'where' => "meta_key = '_wc_pre_order'",
            ],
            'idx_shopxpert_backorder' => [
                'table' => $wpdb->postmeta,
                'columns' => ['meta_key', 'meta_value(10)'],
                'where' => "meta_key = '_is_backorder'",
            ],
            'idx_shopxpert_comparison' => [
                'table' => $wpdb->postmeta,
                'columns' => ['meta_key', 'post_id'],
                'where' => "meta_key = 'shopxpert_comparison_data'",
            ],
            'idx_shopxpert_wishlist' => [
                'table' => $wpdb->postmeta,
                'columns' => ['meta_key', 'post_id'],
                'where' => "meta_key = 'shopxpert_wishlist_item'",
            ],

            // Options table indexes
            'idx_shopxpert_options' => [
                'table' => $wpdb->options,
                'columns' => ['option_name'],
                'where' => "option_name LIKE '%shopxpert%'",
            ],
        ];
    }

    /**
     * Check if tables have recommended indexes
     * 
     * @return array Status of each index
     */
    public static function check_indexes() {
        global $wpdb;

        $indexes = self::get_recommended_indexes();
        $status = [];

        foreach ( $indexes as $name => $index_config ) {
            // Query to check if index exists
            $existing = $wpdb->get_results( "SHOW INDEXES FROM {$index_config['table']} WHERE Key_name = '{$name}'" );
            
            $status[ $name ] = [
                'exists' => !empty( $existing ),
                'columns' => $index_config['columns'],
            ];
        }

        return $status;
    }

    /**
     * Create recommended indexes
     * 
     * Should be called during plugin activation/update
     * 
     * @return void
     */
    public static function create_indexes() {
        global $wpdb;

        $indexes = self::get_recommended_indexes();

        foreach ( $indexes as $name => $config ) {
            $columns = implode( ', ', $config['columns'] );
            $sql = "ALTER TABLE {$config['table']} ADD INDEX {$name} ({$columns})";
            
            try {
                $wpdb->query( $sql );
                if ( defined('WP_DEBUG') && WP_DEBUG ) {
                    error_log( "[ShopXpert Schema] Index created: {$name}" );
                }
            } catch ( \Exception $e ) {
                if ( defined('WP_DEBUG') && WP_DEBUG ) {
                    error_log( "[ShopXpert Schema] Failed to create index {$name}: " . $e->getMessage() );
                }
            }
        }
    }

    /**
     * Get database optimization report
     * 
     * @return array
     */
    public static function get_optimization_report() {
        global $wpdb;

        $report = [
            'meta_keys' => count( self::get_custom_meta_keys() ),
            'indexes' => self::check_indexes(),
            'table_stats' => [
                'postmeta_rows' => $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->postmeta}" ),
                'options_rows' => $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->options}" ),
            ],
        ];

        // Check for fragmentation
        $post_meta_fragmentation = $wpdb->get_var(
            "SELECT ROUND(((data_length + index_length) - data_free) / (data_length + index_length) * 100, 2) FROM information_schema.TABLES WHERE table_schema = DATABASE() AND table_name = '{$wpdb->postmeta}'"
        );

        if ( $post_meta_fragmentation ) {
            $report['postmeta_fragmentation'] = $post_meta_fragmentation . '%';
        }

        return $report;
    }

    /**
     * Get slow queries to optimize
     * 
     * Requires slow_query_log to be enabled
     * 
     * @return array
     */
    public static function get_slow_queries() {
        
        return [
            'recommendation' => 'Enable MySQL slow query log to identify bottlenecks',
            'threshold' => '0.5 seconds',
            'enable_sql' => 'SET GLOBAL slow_query_log = ON;',
        ];
    }
}
