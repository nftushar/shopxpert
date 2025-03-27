<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class ShopXpert_WooCommerce_Check {
    public function __construct() {
        add_action( 'admin_notices', array( $this, 'check_woocommerce_active' ) );
    }

    public function check_woocommerce_active() {
        // Check if WooCommerce is installed and active
        if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
            // Check if WooCommerce is installed
            $plugin_path = WP_PLUGIN_DIR . '/woocommerce/woocommerce.php';
            if ( file_exists( $plugin_path ) ) {
                // WooCommerce is installed but not active
                $activate_url = wp_nonce_url(
                    admin_url( 'plugins.php?action=activate&plugin=woocommerce/woocommerce.php' ),
                    'activate-plugin_woocommerce/woocommerce.php'
                );
                echo '<div class="error"><p><strong>ShopXpert</strong> requires WooCommerce to be active. <a href="' . esc_url( $activate_url ) . '">Click here to activate WooCommerce</a>.</p></div>';
            } else {
                // WooCommerce is not installed
                $install_url = esc_url( 'https://wordpress.org/plugins/woocommerce/' );
                echo '<div class="error"><p><strong>ShopXpert</strong> requires WooCommerce to be installed and active. <a href="' . $install_url . '" target="_blank">Click here to download WooCommerce</a>.</p></div>';
            }
        }
    }
}

new ShopXpert_WooCommerce_Check();
?>
