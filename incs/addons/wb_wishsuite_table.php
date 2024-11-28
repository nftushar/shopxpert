<?php

namespace ShopXpert\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;

class Wb_Wishlist_Table_Widget extends Widget_Base {

    public function get_name() {
        return 'wb_wishlist_table'; // This should match the key in widget_list
    }

    public function get_title() {
        return __( 'WishList Table', 'shopxpert' );
    }

    public function get_categories() {
        return [ 'shopxpert-addons' ]; // This should match the category you added
    }

    protected function register_controls() {

        // Content
        $this->start_controls_section(
            'wishlist_content',
            [
                'label' => __( 'WishList', 'shopxpert' ),
            ]
        );

            $this->add_control(
                'empty_table_text',
                [
                    'label' => __( 'Empty table text', 'shopxpert' ),
                    'type' => Controls_Manager::TEXT,
                    'label_block'=>true,
                ]
            );

        $this->end_controls_section();

        // Table Heading Style
        $this->start_controls_section(
            'table_heading_style_section',
            [
                'label' => __( 'Table Heading', 'shopxpert' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'heading_color',
                [
                    'label' => __( 'Heading Color', 'shopxpert' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wishlist-table-content table thead > tr th' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'heading_background',
                    'label' => __( 'Heading Background', 'shopxpert' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .wishlist-table-content table thead > tr th',
                    'exclude' =>['image'],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'heading_border',
                    'label' => __( 'Border', 'shopxpert' ),
                    'selector' => '{{WRAPPER}} .wishlist-table-content table thead > tr',
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'heading_typography',
                    'label' => __( 'Typography', 'shopxpert' ),
                    'selector' => '{{WRAPPER}} .wishlist-table-content table thead > tr th',
                ]
            );
            
        $this->end_controls_section();

        // Table Content Style
        $this->start_controls_section(
            'table_content_style_section',
            [
                'label' => __( 'Table Body', 'shopxpert' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'table_body_border',
                    'label' => __( 'Border', 'shopxpert' ),
                    'selector' => '{{WRAPPER}} .wishlist-table-content table,.wishlist-table-content table tbody > tr',
                ]
            );

            $this->add_control(
                'table_body_title',
                [
                    'label' => __( 'Title', 'shopxpert' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'table_body_title_typography',
                    'label' => __( 'Typography', 'shopxpert' ),
                    'selector' => '{{WRAPPER}} .wishlist-table-content table tbody > tr td.wishlist-product-title',
                ]
            );

            $this->add_control(
                'table_body_title_color',
                [
                    'label' => __( 'Color', 'shopxpert' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wishlist-table-content table tbody > tr td.wishlist-product-title a' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'table_body_title_hover_color',
                [
                    'label' => __( 'Hover Color', 'shopxpert' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wishlist-table-content table tbody > tr td.wishlist-product-title a:hover' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'table_body_price',
                [
                    'label' => __( 'Price', 'shopxpert' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'table_body_price_typography',
                    'label' => __( 'Typography', 'shopxpert' ),
                    'selector' => '{{WRAPPER}} .wishlist-table-content table tbody > tr td.wishlist-product-price',
                ]
            );

            $this->add_control(
                'table_body_price_color',
                [
                    'label' => __( 'Color', 'shopxpert' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wishlist-table-content table tbody > tr td.wishlist-product-price' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .wishlist-table-content table tbody > tr td.wishlist-product-price .woocommerce-Price-amount' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'table_body_quantity_field',
                [
                    'label' => __( 'Quantity', 'shopxpert' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );
            $this->add_control(
                'table_body_quantity_field_color',
                [
                    'label' => __( 'Color', 'shopxpert' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wishlist-table-content .quantity .qty' => 'color: {{VALUE}}',
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'table_body_quantity_field_typography',
                    'label' => __( 'Typography', 'shopxpert' ),
                    'selector' => '{{WRAPPER}} .wishlist-table-content .quantity .qty',
                ]
            );
            $this->add_control(
                'table_body_quantity_field_background_color',
                [
                    'label' => __( 'Background Color', 'shopxpert' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wishlist-table-content .quantity .qty' => 'background-color: {{VALUE}}',
                    ],
                ]
            );
            $this->add_control(
                'table_body_quantity_field_border_color',
                [
                    'label' => __( 'Border Color', 'shopxpert' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wishlist-table-content .quantity .qty' => 'border-color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'table_body_remove_icon',
                [
                    'label' => __( 'Remove Icon', 'shopxpert' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );

            $this->add_control(
                'table_body_remove_icon_color',
                [
                    'label' => __( 'Color', 'shopxpert' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wishlist-remove::before' => 'background-color: {{VALUE}}',
                        '{{WRAPPER}} .wishlist-remove::after' => 'background-color: {{VALUE}}',
                    ],
                ]
            );
            $this->add_control(
                'table_body_remove_icon_hover_color',
                [
                    'label' => __( 'Hover Color', 'shopxpert' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wishlist-remove:hover::before' => 'background-color: {{VALUE}}',
                        '{{WRAPPER}} .wishlist-remove:hover::after' => 'background-color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'table_body_social_share',
                [
                    'label' => __( 'Social Share', 'shopxpert' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );
            $this->add_control(
                'table_body_social_share_title_color',
                [
                    'label' => __( 'Title Color', 'shopxpert' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wishlist-social-share .wishlist-social-title' => 'color: {{VALUE}}',
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'table_body_social_share_title_typography',
                    'label' => __( 'Typography', 'shopxpert' ),
                    'selector' => '{{WRAPPER}} .wishlist-social-share .wishlist-social-title',
                ]
            );

            $this->add_control(
                'table_body_social_share_color',
                [
                    'label' => __( 'Icon Color', 'shopxpert' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wishlist-social-share ul li a' => 'color: {{VALUE}}',
                    ],
                ]
            );
            $this->add_control(
                'table_body_social_share_hover_color',
                [
                    'label' => __( 'Icon Hover Color', 'shopxpert' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wishlist-social-share ul li a:hover' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'table_body_social_share_size',
                [
                    'label' => esc_html__( 'Size', 'shopxpert' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .wishlist-social-share ul li a .wishlist-social-icon svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

         // Table Add to cart button Style
         $this->start_controls_section(
            'table_content_add_to_style_section',
            [
                'label' => __( 'Add To Cart', 'shopxpert' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'table_add_to_cart_button_typography',
                    'label' => __( 'Typography', 'shopxpert' ),
                    'selector' => '{{WRAPPER}} .wishlist-table-content table .wishlist-addtocart',
                ]
            );

            $this->add_responsive_control(
                'table_add_to_cart_button_padding',
                [
                    'label' => __( 'Padding', 'shopxpert' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wishlist-table-content table .wishlist-addtocart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->start_controls_tabs('table_add_to_cart_button_style_tabs');

                // Normal
                $this->start_controls_tab(
                    'table_add_to_cart_button_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'shopxpert' ),
                    ]
                );
                    
                    $this->add_control(
                        'table_cart_button_color',
                        [
                            'label' => __( 'Color', 'shopxpert' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wishlist-table-content table .wishlist-addtocart' => 'color: {{VALUE}}',
                            ],
                        ]
                    );
        
                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'table_cart_button_bg_color',
                            'label' => __( 'Background', 'shopxpert' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .wishlist-table-content table .wishlist-addtocart',
                            'exclude' =>['image'],
                        ]
                    );

                $this->end_controls_tab();

                // Hover
                $this->start_controls_tab(
                    'table_add_to_cart_button_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'shopxpert' ),
                    ]
                );
                    
                    $this->add_control(
                        'table_cart_button_hover_color',
                        [
                            'label' => __( 'Color', 'shopxpert' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wishlist-table-content table .wishlist-addtocart:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );
        
                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'table_cart_button_hover_bg_color',
                            'label' => __( 'Background', 'shopxpert' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .wishlist-table-content table .wishlist-addtocart:hover',
                            'exclude' =>['image'],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

    }

    protected function render() {
        // Render the widget output
        $settings = $this->get_settings_for_display();
        echo '<h2>' . esc_html( $settings['empty_table_text'] ) . '</h2>';
        // Add your table rendering logic here
    }
}
