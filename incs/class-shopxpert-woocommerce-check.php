<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class ShopXpert_WooCommerce_Check {
    public function __construct() {
        add_action( 'admin_notices', array( $this, 'check_woocommerce_active' ) );
        add_action( 'admin_init', array( $this, 'deactivate_plugin_if_woocommerce_inactive' ) );
    }

    public function check_woocommerce_active() {
        if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
            echo '<div class="error"><p><strong>ShopXpert</strong> requires <a href="https://wordpress.org/plugins/woocommerce/" target="_blank">WooCommerce</a> to be installed and active.</p></div>';
        }
    }

    public function deactivate_plugin_if_woocommerce_inactive() {
        if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
            deactivate_plugins( plugin_basename( __FILE__ ) );
        }
    }
}

new ShopXpert_WooCommerce_Check();