<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Shopxpert_Wb_Wishlist_Counter_Widget extends Widget_Base {

    public function get_name() {
        return 'wb-wishlist-counter';
    }

    public function get_title() {
        return __( 'WL: WishList Counter', 'shopxpert' );
    }

    public function get_icon() {
        return 'eicon-counter-circle';
    }

    public function get_categories() {
        return array( 'shopxpert-addons' );
    }

    public function get_help_url() {
        return 'https://#/documentation/';
    }

    public function get_keywords(){
        return ['wishlist counter','product counter','wishlist counter'];
    }

    protected function register_controls() {

        // Content
        $this->start_controls_section(
            'wishlist_content',
            [
                'label' => __( 'WishList Counter', 'shopxpert' ),
            ]
        );

            $this->add_control(
                'counter_after_text',
                [
                    'label' => __( 'Text after "Wishlist" icon', 'shopxpert' ),
                    'type' => Controls_Manager::TEXT,
                    'label_block'=>true,
                ]
            );

        $this->end_controls_section();

        // Counter Style
        $this->start_controls_section(
            'counter_style_section',
            [
                'label' => __( 'Styles', 'shopxpert' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_responsive_control(
                'shopxpert-tab-menu-align',
                [
                    'label' => __( 'Alignment', 'shopxpert' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => __( 'Left', 'shopxpert' ),
                            'icon' => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'shopxpert' ),
                            'icon' => 'eicon-text-align-center',
                        ],
                        'right' => [
                            'title' => __( 'Right', 'shopxpert' ),
                            'icon' => 'eicon-text-align-right',
                        ]
                    ],
                    'selectors' => [
                        '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                    ],
                    'prefix_class'=> 'wishlist-align-%s',
                    'default' => 'left',
                ]
            );

            $this->add_control(
                'counter_style_hedding',
                [
                    'label' => esc_html__( 'Counter', 'shopxpert' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );

            $this->add_control(
                'counter_color',
                [
                    'label' => __( 'Counter Color', 'shopxpert' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wishlist-counter-area span.wishlist-counter' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'counter_background',
                    'label' => __( 'Counter Background', 'shopxpert' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .wishlist-counter-area span.wishlist-counter',
                    'exclude' =>['image'],
                ]
            );

            $this->add_control(
                'counter_icon_style_hedding',
                [
                    'label' => esc_html__( 'Counter Icon', 'shopxpert' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'counter_icon_color',
                [
                    'label' => __( 'Counter Icon Color', 'shopxpert' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wishlist-counter-area span.wishlist-counter-icon' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'counter_text_style_hedding',
                [
                    'label' => esc_html__( 'Text', 'shopxpert' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition'=>[
                        'counter_after_text!'=>''
                    ]
                ]
            );
            $this->add_control(
                'counter_text_color',
                [
                    'label' => __( 'Counter Text Color', 'shopxpert' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wishlist-counter-area.wishlist-has-text' => 'color: {{VALUE}}',
                    ],
                    'condition'=>[
                        'counter_after_text!'=>''
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'counter_text_typography',
                    'label' => __( 'Typography', 'shopxpert' ),
                    'selector' => '{{WRAPPER}} .wishlist-counter-area.wishlist-has-text',
                    'condition'=>[
                        'counter_after_text!'=>''
                    ]
                ]
            );
            
        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {
        $settings   = $this->get_settings_for_display();

        $short_code_attributes = [
            'text' => $settings['counter_after_text'],
        ];
        echo shopxpert_do_shortcode( 'wishlist_counter', $short_code_attributes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

    }

}