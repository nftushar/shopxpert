<?php

namespace ShopXpert\Widgets;

use Elementor\Widget_Base;

class Wb_Wishsuite_Table_Widget extends Widget_Base {
    public function get_name() {
        return 'wb_wishsuite_table'; // This should match the key in widget_list
    }

    public function get_title() {
        return __( 'WishSuite Table', 'shopxpert' );
    }

    public function get_categories() {
        return [ 'shopxpert-addons' ]; // This should match the category you added
    }

    protected function _register_controls() {
        // Define controls for your widget here
        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Content', 'shopxpert' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __( 'Title', 'shopxpert' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Default Title', 'shopxpert' ),
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        // Render the widget output
        $settings = $this->get_settings_for_display();
        echo '<h2>' . esc_html( $settings['title'] ) . '</h2>';
        // Add your table rendering logic here
    }
}
