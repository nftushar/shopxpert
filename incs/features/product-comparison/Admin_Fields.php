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
                    'default' => __( 's Product Comparison', 'shopxpert' ),
                ],
                [
                    'name'    => 'show_on_shop',
                    'label'   => __( 'Show Compare Button on Shop Page', 'shopxpert' ),
                    'type'    => 'checkbox',
                    'default' => 'on',
                    'desc'    => esc_html__( 'Display compare button on shop and archive pages.', 'shopxpert' ),
                ],
                [
                    'name'    => 'show_on_single',
                    'label'   => __( 'Show Compare Button on Single Product Page', 'shopxpert' ),
                    'type'    => 'checkbox',
                    'default' => 'on',
                    'desc'    => esc_html__( 'Display compare button on single product pages.', 'shopxpert' ),
                ],
                [
                    'name'    => 'button_position',
                    'label'   => __( 'Button Position', 'shopxpert' ),
                    'type'    => 'select',
                    'default' => 'after_add_to_cart',
                    'options' => [
                        'before_add_to_cart' => __( 'Before Add to Cart Button', 'shopxpert' ),
                        'after_add_to_cart'  => __( 'After Add to Cart Button', 'shopxpert' ),
                        'before_title'       => __( 'Before Product Title', 'shopxpert' ),
                        'after_title'        => __( 'After Product Title', 'shopxpert' ),
                        'custom'             => __( 'Custom (use shortcode)', 'shopxpert' ),
                    ],
                    'desc'    => esc_html__( 'Choose where to display the compare button.', 'shopxpert' ),
                ],
                [
                    'name'    => 'button_style',
                    'label'   => __( 'Button Style', 'shopxpert' ),
                    'type'    => 'select',
                    'default' => 'default',
                    'options' => [
                        'default'    => __( 'Default (Icon + Text)', 'shopxpert' ),
                        'icon-only'  => __( 'Icon Only', 'shopxpert' ),
                        'text-only'  => __( 'Text Only', 'shopxpert' ),
                    ],
                    'desc'    => esc_html__( 'Choose the button display style.', 'shopxpert' ),
                ],
                [
                    'name'    => 'show_counter',
                    'label'   => __( 'Show Product Counter', 'shopxpert' ),
                    'type'    => 'checkbox',
                    'default' => 'on',
                    'desc'    => esc_html__( 'Display the number of products in comparison on the button.', 'shopxpert' ),
                ],
                [
                    'name'    => 'enable_search',
                    'label'   => __( 'Enable Product Search', 'shopxpert' ),
                    'type'    => 'checkbox',
                    'default' => 'on',
                    'desc'    => esc_html__( 'Allow users to search and add products from the comparison table.', 'shopxpert' ),
                ],
                [
                    'name'    => 'show_footer_bar',
                    'label'   => __( 'Show Footer Comparison Bar', 'shopxpert' ),
                    'type'    => 'checkbox',
                    'default' => 'on',
                    'desc'    => esc_html__( 'Display a fixed footer bar showing compared products.', 'shopxpert' ),
                ],
                [
                    'name'    => 'table_fields',
                    'label'   => __( 'Table Fields to Display', 'shopxpert' ),
                    'type'    => 'multicheck',
                    'default' => ['image', 'title', 'rating', 'price', 'description', 'availability', 'sku', 'add_to_cart'],
                    'options' => [
                        'image'        => __( 'Product Image', 'shopxpert' ),
                        'title'        => __( 'Product Title', 'shopxpert' ),
                        'rating'       => __( 'Product Rating', 'shopxpert' ),
                        'price'        => __( 'Product Price', 'shopxpert' ),
                        'description'  => __( 'Product Description', 'shopxpert' ),
                        'availability' => __( 'Availability', 'shopxpert' ),
                        'sku'          => __( 'SKU', 'shopxpert' ),
                        'add_to_cart'  => __( 'Add to Cart Button', 'shopxpert' ),
                    ],
                    'desc'    => esc_html__( 'Select which fields to display in the comparison table.', 'shopxpert' ),
                ],
                [
                    'name'    => 'popup_animation',
                    'label'   => __( 'Popup Animation', 'shopxpert' ),
                    'type'    => 'select',
                    'default' => 'slide',
                    'options' => [
                        'slide'    => __( 'Slide In', 'shopxpert' ),
                        'fade'     => __( 'Fade In', 'shopxpert' ),
                        'zoom'     => __( 'Zoom In', 'shopxpert' ),
                        'bounce'   => __( 'Bounce', 'shopxpert' ),
                    ],
                    'desc'    => esc_html__( 'Choose the animation effect for the comparison popup.', 'shopxpert' ),
                ],
                [
                    'name'    => 'custom_css',
                    'label'   => __( 'Custom CSS', 'shopxpert' ),
                    'type'    => 'textarea',
                    'default' => '',
                    'desc'    => esc_html__( 'Add custom CSS to style the comparison elements.', 'shopxpert' ),
                ],
            ]
        ];
    }

    public function plugin_page() {
        echo '<div class="wrap">';
        echo '<h2>' . esc_html__( '3 Product Comparison Settings', 'shopxpert' ) . '</h2>';
        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();
        echo '</div>';
    }
}