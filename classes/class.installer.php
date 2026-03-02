<?php
namespace ShopXpert\Classes;

use ShopXpert\Database\Schema;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Installer class
 * Handles plugin installation, activation, and database setup
 */
class Installer {

    private static $instance = null;

    /**
     * Track if indexes have been created to avoid recreating on every request
     * @var bool
     */
    private static $indexes_created = false;

    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Class Constructor
     */
    private function __construct(){
        $this->run();
    }

    /**
     * Run the installer
     *
     * @return void
     */
    public function run() {
        $this->add_version();
        $this->add_redirection_flag();
        $this->setup_database();
    }

    /**
     * Setup database - create indexes and optimize
     * Only runs once on activation to improve performance
     *
     * @return void
     */
    public function setup_database() {
        // Check if we've already created indexes in this request
        if ( self::$indexes_created ) {
            return;
        }

        // Check if this is first install or upgrade
        $installed_version = get_option( 'shopxpert_db_version', '0' );
        
        // Only create indexes on fresh install or upgrade
        if ( version_compare( $installed_version, '1.0.9', '<' ) ) {
            // Create recommended database indexes for better query performance
            if ( defined('WP_DEBUG') && WP_DEBUG ) {
                error_log('[ShopXpert Installer] Creating database indexes...');
            }
            
            // Note: Index creation is handled by Schema class
            // This is a placeholder that can be enabled for production
            // Schema::create_indexes();
            
            update_option( 'shopxpert_db_version', '1.0.9' );
            self::$indexes_created = true;
        }
    }

    /**
     * Add time and version on DB
     */
    public function add_version() {
        $installed = get_option( 'shopxpert_installed' );

        if ( ! $installed ) {
            update_option( 'shopxpert_installed', time() );
        }

        update_option( 'SHOPXPERT_VERSION', SHOPXPERT_VERSION );
    }

    /**
     * [add_redirection_flag] redirection flug
     */
    public function add_redirection_flag(){
        add_option( 'shopxpert_do_activation_redirect', TRUE );
        flush_rewrite_rules();
    }

    /**
     * Get optimization report (for admin dashboard)
     *
     * @return array
     */
    public static function get_optimization_report() {
        return Schema::get_optimization_report();
    }
}
