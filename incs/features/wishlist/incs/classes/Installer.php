<?php
namespace ShopXpert\Features\Wishlist;

use function Shopxpert\incs\shopxpert_update_option;

/**
 * Installer class
 */
class Installer {

    /**
     * Run the installer
     *
     * @return void
     */
    public function run() {
        $this->create_tables();
        $this->create_page();
    }

    /**
     * [create_tables]
     * @return [void]
     */
    public function create_tables() {
        global $wpdb;
        
        $charset_collate = '';
        if ( $wpdb->has_cap( 'collation' ) ) {
            $charset_collate = $wpdb->get_charset_collate();
        }

        $schema = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}wishlist_list` (
          `id` bigint( 20 ) unsigned NOT NULL AUTO_INCREMENT,
          `user_id` bigint( 20 ) NULL DEFAULT NULL,
          `product_id` bigint(20) NULL DEFAULT NULL,
          `quantity` int(11) NULL DEFAULT NULL,
          `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`)
        ) $charset_collate";

        if ( ! function_exists( 'dbDelta' ) ) {
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        }

        dbDelta( $schema );
    }

    /**
     * [create_page] Create page
     * @return [void]
     */
    private function create_page() {
        if ( function_exists( 'WC' ) ) {
            if ( !function_exists( 'wc_create_page' ) ) { 
                require_once WC_ABSPATH . '/incs/admin/wc-admin-functions.php';
            }
            $create_page_id = wc_create_page(
                sanitize_title_with_dashes( _x( 'shopxpert', 'page_slug', 'shopxpert' ) ),
                '',
                __( 'WishList', 'shopxpert' ),
                '<!-- wp:shortcode -->[wishlist_table]<!-- /wp:shortcode -->'
            );
            if( $create_page_id ){
                shopxpert_update_option( 'wishlist_table_settings_tabs', 'wishlist_page', $create_page_id ); //  
            }
            
        }
    }

    /**
     * [drop_tables] Delete table
     * @return [void]
     */
    public static function drop_tables() {
        global $wpdb;
        $tables = [
            "{$wpdb->prefix}wishlist_list",
        ];
        foreach ( $tables as $table ) {
            $wpdb->query( $wpdb->prepare("DROP TABLE IF EXISTS %s", $table) );
        }
    }


}