<?php
namespace ShopXpert\Classes;
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
/**
 * Installer class
 */
class Installer {

    private static $instance = null;
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


}