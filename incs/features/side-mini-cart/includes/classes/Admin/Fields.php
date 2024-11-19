<?php
namespace Shopxpert\Features\SideMiniCart\Admin;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Fields {

    private static $_instance = null;

    /**
     * Get Instance
     */
    public static function instance(){
        if( is_null( self::$_instance ) ){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct(){
        add_filter( 'shopxpert_admin_fields', [ $this, 'admin_fields' ], 99, 1 );
    }

    public function admin_fields( $fields ){

        array_splice( $fields['shopxpert_others_tabs']['features'], 22, 0, $this->side_mini_cart_sitting_fields() );

        if( \Shopxpert\Features\SideMiniCart\ENABLED ){
            $fields['shopxpert_elements_tabs'][] = [
                'name'  => 'wl_mini_cart',
                'label' => esc_html__( 'Mini Cart', 'shopxpert' ),
                'type'  => 'element',
                'default' => 'on'
            ];

            // Block
            $fields['shopxpert_gutenberg_tabs']['blocks'][] = [
                'name'  => 'side_mini_cart',
                'label' => esc_html__( 'Side Mini Cart', 'shopxpert' ),
                'type'  => 'element',
                'default' => 'on',
            ];

        }

        return $fields;
    }

    /**
     * Side Mini Car Fields;
     */
    public function side_mini_cart_sitting_fields(){

        error_log('side mini_cart_sitting_fields');
        $fields = [
            [
                'name'   => 'mini_side_cart',
                'label'  => esc_html__( 'TTT Side Mini Cart', 'shopxpert' ),
                'type'   => 'element',
                'default'=> 'off',
                'class'  =>'side_mini_cart',
                'require_settings'  => true,
                'documentation' => esc_url('https://#/doc/side-mini-cart-for-woocommerce/'),
                'setting_fields' => [
                    
                    [
                        'name'    => 'mini_cart_position',
                        'label'   => esc_html__( 'Position', 'shopxpert' ),
                        'desc'    => esc_html__( 'Set the position of the Mini Cart .', 'shopxpert' ),
                        'type'    => 'select',
                        'default' => 'left',
                        'options' => [
                            'left'   => esc_html__( 'Left','shopxpert' ),
                            'right'  => esc_html__( 'Right','shopxpert' ),
                        ],
                        'class' => 'shopxpert-action-field-left',
                    ],
        
                    [
                        'name'    => 'mini_cart_icon',
                        'label'   => esc_html__( 'Icon', 'shopxpert' ),
                        'desc'    => esc_html__( 'You can manage the side mini cart toggler icon.', 'shopxpert' ),
                        'type'    => 'text',
                        'default' => 'sli sli-basket-loaded',
                        'class'   => 'shopxpert_icon_picker shopxpert-action-field-left'
                    ],

                    [
                        'name'  => 'empty_mini_cart_hide',
                        'label' => esc_html__( 'Hide mini cart when empty?', 'shopxpert' ),
                        'desc'  => esc_html__( 'Hide side mini cart when the cart is empty.', 'shopxpert' ),
                        'type'  => 'checkbox',
                        'default' => 'off',
                        'class' => 'shopxpert-action-field-left'
                    ],
        
                    [
                        'name'  => 'mini_cart_icon_color',
                        'label' => esc_html__( 'Icon color', 'shopxpert' ),
                        'desc'  => esc_html__( 'Side mini cart icon color', 'shopxpert' ),
                        'type'  => 'color',
                        'class' => 'shopxpert-action-field-left'
                    ],
        
                    [
                        'name'  => 'mini_cart_icon_bg_color',
                        'label' => esc_html__( 'Icon background color', 'shopxpert' ),
                        'desc'  => esc_html__( 'Side mini cart icon background color', 'shopxpert' ),
                        'type'  => 'color',
                        'class' => 'shopxpert-action-field-left'
                    ],
        
                    [
                        'name'  => 'mini_cart_icon_border_color',
                        'label' => esc_html__( 'Icon border color', 'shopxpert' ),
                        'desc'  => esc_html__( 'Side mini cart icon border color', 'shopxpert' ),
                        'type'  => 'color',
                        'class' => 'shopxpert-action-field-left'
                    ],
        
                    [
                        'name'  => 'mini_cart_counter_color',
                        'label' => esc_html__( 'Counter color', 'shopxpert' ),
                        'desc'  => esc_html__( 'Side mini cart counter color', 'shopxpert' ),
                        'type'  => 'color',
                        'class' => 'shopxpert-action-field-left'
                    ],
        
                    [
                        'name'  => 'mini_cart_counter_bg_color',
                        'label' => esc_html__( 'Counter background color', 'shopxpert' ),
                        'desc'  => esc_html__( 'Side mini cart counter background color', 'shopxpert' ),
                        'type'  => 'color',
                        'class' => 'shopxpert-action-field-left'
                    ],

                    [
                        'name'      => 'mini_cart_button_heading',
                        'headding'  => esc_html__( 'Buttons', 'shopxpert' ),
                        'type'      => 'title'
                    ],

                    [
                        'name'  => 'mini_cart_buttons_color',
                        'label' => esc_html__( 'Color', 'shopxpert' ),
                        'desc'  => esc_html__( 'Side mini cart buttons color', 'shopxpert' ),
                        'type'  => 'color',
                        'class' => 'shopxpert-action-field-left'
                    ],
                    [
                        'name'  => 'mini_cart_buttons_bg_color',
                        'label' => esc_html__( 'Background color', 'shopxpert' ),
                        'desc'  => esc_html__( 'Side mini cart buttons background color', 'shopxpert' ),
                        'type'  => 'color',
                        'class' => 'shopxpert-action-field-left'
                    ],

                    [
                        'name'  => 'mini_cart_buttons_hover_color',
                        'label' => esc_html__( 'Hover color', 'shopxpert' ),
                        'desc'  => esc_html__( 'Side mini cart buttons hover color', 'shopxpert' ),
                        'type'  => 'color',
                        'class' => 'shopxpert-action-field-left'
                    ],
                    [
                        'name'  => 'mini_cart_buttons_hover_bg_color',
                        'label' => esc_html__( 'Hover background color', 'shopxpert' ),
                        'desc'  => esc_html__( 'Side mini cart buttons hover background color', 'shopxpert' ),
                        'type'  => 'color',
                        'class' => 'shopxpert-action-field-left'
                    ]

                ]
            ]
        ];

        return $fields;

    }

}