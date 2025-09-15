<?php

namespace Shopxpert\ProductComparison;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Product Comparison Widget
 */
class Widget extends \WP_Widget {
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'shopxpert_product_comparison',
            __('ShopXpert Product Comparison', 'shopxpert'),
            [
                'description' => __('Display product comparison table and controls.', 'shopxpert'),
                'classname' => 'shopxpert-comparison-widget'
            ]
        );
    }

    /**
     * Widget output
     */
    public function widget($args, $instance) {
        // Check if comparison is enabled
        if (!\Shopxpert\incs\shopxpert_get_option('enable', 'product_comparison_settings_tabs', 'on')) {
            return;
        }

        $title = !empty($instance['title']) ? $instance['title'] : __('Product Comparison', 'shopxpert');
        $show_search = !empty($instance['show_search']) ? $instance['show_search'] : 'on';
        $show_fields = !empty($instance['show_fields']) ? $instance['show_fields'] : 'image,title,rating,price,description,availability,sku,add_to_cart';
        $max_height = !empty($instance['max_height']) ? $instance['max_height'] : '400px';

        echo $args['before_widget'];
        echo $args['before_title'] . esc_html($title) . $args['after_title'];
        
        // Add custom styling
        echo '<style>
            .shopxpert-comparison-widget .shopxpert-compare-table-container {
                max-height: ' . esc_attr($max_height) . ';
                overflow-y: auto;
            }
        </style>';
        
        // Display comparison table
        echo do_shortcode('[product_comparison_table show_fields="' . esc_attr($show_fields) . '" show_search="' . esc_attr($show_search) . '"]');
        
        echo $args['after_widget'];
    }

    /**
     * Widget form
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Product Comparison', 'shopxpert');
        $show_search = !empty($instance['show_search']) ? $instance['show_search'] : 'on';
        $show_fields = !empty($instance['show_fields']) ? $instance['show_fields'] : 'image,title,rating,price,description,availability,sku,add_to_cart';
        $max_height = !empty($instance['max_height']) ? $instance['max_height'] : '400px';
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:', 'shopxpert'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('show_search')); ?>">
                <input type="checkbox" id="<?php echo esc_attr($this->get_field_id('show_search')); ?>" name="<?php echo esc_attr($this->get_field_name('show_search')); ?>" value="on" <?php checked($show_search, 'on'); ?>>
                <?php _e('Show Search', 'shopxpert'); ?>
            </label>
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('show_fields')); ?>"><?php _e('Fields to Display:', 'shopxpert'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('show_fields')); ?>" name="<?php echo esc_attr($this->get_field_name('show_fields')); ?>" type="text" value="<?php echo esc_attr($show_fields); ?>" placeholder="image,title,rating,price,description,availability,sku,add_to_cart">
            <small><?php _e('Comma-separated list of fields to display', 'shopxpert'); ?></small>
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('max_height')); ?>"><?php _e('Max Height:', 'shopxpert'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('max_height')); ?>" name="<?php echo esc_attr($this->get_field_name('max_height')); ?>" type="text" value="<?php echo esc_attr($max_height); ?>" placeholder="400px">
        </p>
        <?php
    }

    /**
     * Update widget settings
     */
    public function update($new_instance, $old_instance) {
        $instance = [];
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['show_search'] = (!empty($new_instance['show_search'])) ? 'on' : 'off';
        $instance['show_fields'] = (!empty($new_instance['show_fields'])) ? sanitize_text_field($new_instance['show_fields']) : 'image,title,rating,price,description,availability,sku,add_to_cart';
        $instance['max_height'] = (!empty($new_instance['max_height'])) ? sanitize_text_field($new_instance['max_height']) : '400px';
        
        return $instance;
    }
}
