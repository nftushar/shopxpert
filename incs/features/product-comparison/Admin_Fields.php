<?php

namespace Shopxpert\ProductComparison;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Admin_Fields {
    private $settings_api;
    private static $_instance = null;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        require_once( SHOPXPERT_ADDONS_PL_PATH . 'incs/admin/inc/settings_field_manager_default.php' );
        $this->settings_api = new \WishList\Admin\ShopXpert_Settings_Field_Manager_Default();
        \add_action( 'admin_init', [ $this, 'admin_init' ] );
    }

    public function admin_init() {
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->fields_settings() );
        $this->settings_api->admin_init();
    }

    public function get_settings_sections() {
        return [
            [
                'id'    => 'product_comparison_settings_tabs',
                'title' => esc_html__( 'Product Comparison Settings', 'shopxpert' )
            ]
        ];
    }

    protected function fields_settings() {
        return [
            'product_comparison_settings_tabs' => [
                [
                    'name'    => 'enable',
                    'label'   => __( 'Enable Product Comparison', 'shopxpert' ),
                    'type'    => 'checkbox',
                    'default' => 'on',
                    'desc'    => esc_html__( 'Enable or disable the Product Comparison feature.', 'shopxpert' ),
                ],
                [
                    'name'    => 'button_text',
                    'label'   => __( 'Add to Compare Button Text', 'shopxpert' ),
                    'type'    => 'text',
                    'default' => __( 'Add to Compare', 'shopxpert' ),
                ],
                [
                    'name'    => 'remove_button_text',
                    'label'   => __( 'Remove from Compare Button Text', 'shopxpert' ),
                    'type'    => 'text',
                    'default' => __( 'Remove from Compare', 'shopxpert' ),
                ],
                [
                    'name'    => 'max_products',
                    'label'   => __( 'Maximum Products to Compare', 'shopxpert' ),
                    'type'    => 'number',
                    'default' => 4,
                    'desc'    => esc_html__( 'Set the maximum number of products a user can compare at once.', 'shopxpert' ),
                ],
                [
                    'name'    => 'table_title',
                    'label'   => __( 'Comparison Table Title', 'shopxpert' ),
                    'type'    => 'text',
                    'default' => __( 'Product Comparison', 'shopxpert' ),
                ],
            ]
        ];
    }

    public function plugin_page() {
        echo '<div class="wrap">';
        echo '<h2>' . esc_html__( 'Product Comparison Settings', 'shopxpert' ) . '</h2>';
        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();
        echo '</div>';
    }
}