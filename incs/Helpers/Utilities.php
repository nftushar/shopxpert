<?php

namespace ShopXpert\Helpers;

if (!defined('ABSPATH')) exit;

/**
 * Utilities Helper
 * 
 * General utility functions
 */
class Utilities {

    /**
     * Get cookie name with multisite support
     * 
     * @param string $name Base name
     * @return string
     */
    public static function get_cookie_name($name) {
        $name = 'shopxpert_' . $name;
        if (is_multisite()) {
            $name .= '_' . get_current_blog_id();
        }
        return $name;
    }

    /**
     * Sanitize variable recursively
     * 
     * @param mixed $var Variable to sanitize
     * @return mixed
     */
    public static function clean($var) {
        if (is_array($var)) {
            return array_map(function($item) {
                return is_scalar($item) ? sanitize_text_field($item) : $item;
            }, $var);
        }
        return is_scalar($var) ? sanitize_text_field($var) : $var;
    }

    /**
     * Get HTML allowed tags
     * 
     * @param string $tag_type Tag type (title or desc)
     * @return array
     */
    public static function get_html_allowed_tags($tag_type = 'title') {
        $accept_html_tags = [
            'span' => [
                'class' => [], 'id' => [], 'line-height' => [],
                'letter-spacing' => [], 'font-weight' => [], 'font-size' => [],
                'font-family' => [], 'word-spacing' => [], 'color' => [],
                'background-color' => [], 'padding' => [], 'margin' => [],
                'border' => [], 'border-radius' => [], 'width' => [],
                'height' => [], 'display' => [], 'position' => [],
                'top' => [], 'left' => [], 'right' => [], 'bottom' => [],
                'background' => [], 'style' => [],
            ],
            'strong' => ['class' => [], 'id' => [], 'style' => []],
            'br' => ['class' => [], 'id' => [], 'style' => []],
            'b' => ['class' => [], 'id' => [], 'style' => []],
            'sub' => ['class' => [], 'id' => [], 'style' => []],
            'sup' => ['class' => [], 'id' => [], 'style' => []],
            'i' => ['class' => [], 'id' => [], 'style' => []],
            'u' => ['class' => [], 'id' => [], 'style' => []],
            's' => ['class' => [], 'id' => [], 'style' => []],
            'em' => ['class' => [], 'id' => [], 'style' => []],
            'del' => ['class' => [], 'id' => [], 'style' => []],
            'ins' => ['class' => [], 'id' => [], 'style' => []],
            'code' => ['class' => [], 'id' => [], 'style' => []],
            'mark' => ['class' => [], 'id' => [], 'style' => []],
            'small' => ['class' => [], 'id' => [], 'style' => []],
            'strike' => ['class' => [], 'id' => [], 'style' => []],
            'abbr' => ['title' => [], 'class' => [], 'id' => [], 'style' => []],
        ];

        if ($tag_type === 'desc') {
            $desc_tags = [
                'h1' => ['class' => [], 'id' => [], 'style' => []],
                'h2' => ['class' => [], 'id' => [], 'style' => []],
                'h3' => ['class' => [], 'id' => [], 'style' => []],
                'h4' => ['class' => [], 'id' => [], 'style' => []],
                'h5' => ['class' => [], 'id' => [], 'style' => []],
                'h6' => ['class' => [], 'id' => [], 'style' => []],
                'p' => ['class' => [], 'id' => [], 'style' => []],
                'a' => ['href' => [], 'title' => [], 'class' => [], 'id' => [], 'style' => []],
                'q' => ['cite' => [], 'class' => [], 'id' => [], 'style' => []],
                'img' => ['src' => [], 'alt' => [], 'height' => [], 'width' => [], 'class' => [], 'id' => [], 'title' => [], 'style' => []],
                'dfn' => ['title' => [], 'class' => [], 'id' => [], 'style' => []],
                'time' => ['datetime' => [], 'class' => [], 'id' => [], 'style' => []],
                'cite' => ['title' => [], 'class' => [], 'id' => [], 'style' => []],
                'acronym' => ['title' => [], 'class' => [], 'id' => [], 'style' => []],
                'hr' => ['class' => [], 'id' => [], 'style' => []],
                'div' => ['class' => [], 'id' => [], 'style' => []],
                'button' => ['class' => [], 'id' => [], 'style' => []],
            ];
            $accept_html_tags = array_merge($accept_html_tags, $desc_tags);
        }

        return $accept_html_tags;
    }

    /**
     * Validate HTML tag
     * 
     * @param string $tag HTML tag
     * @return string
     */
    public static function validate_html_tag($tag = 'div') {
        $allowed_html_tags = [
            'article', 'aside', 'footer', 'header', 'section',
            'nav', 'main', 'div', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span'
        ];
        return in_array(strtolower($tag), $allowed_html_tags) ? $tag : 'div';
    }

    /**
     * Get HTML tag list
     * 
     * @return array
     */
    public static function get_html_tag_lists() {
        return [
            'h1' => __('H1', 'shopxpert'),
            'h2' => __('H2', 'shopxpert'),
            'h3' => __('H3', 'shopxpert'),
            'h4' => __('H4', 'shopxpert'),
            'h5' => __('H5', 'shopxpert'),
            'h6' => __('H6', 'shopxpert'),
            'p' => __('p', 'shopxpert'),
            'div' => __('div', 'shopxpert'),
            'span' => __('span', 'shopxpert'),
        ];
    }

    /**
     * Get orderby options
     * 
     * @return array
     */
    public static function get_order_by_options() {
        return [
            'none' => esc_html__('None', 'shopxpert'),
            'ID' => esc_html__('ID', 'shopxpert'),
            'date' => esc_html__('Date', 'shopxpert'),
            'name' => esc_html__('Name', 'shopxpert'),
            'title' => esc_html__('Title', 'shopxpert'),
            'comment_count' => esc_html__('Comment count', 'shopxpert'),
            'rand' => esc_html__('Random', 'shopxpert'),
            'featured' => esc_html__('Featured', 'shopxpert'),
            '_price' => esc_html__('Product Price', 'shopxpert'),
            'total_sales' => esc_html__('Top Seller', 'shopxpert'),
            '_wc_average_rating' => esc_html__('Top Rated', 'shopxpert'),
        ];
    }

    /**
     * Get countries
     * 
     * @return array
     */
    public static function get_countries() {
        $output = [];
        if (class_exists('WC_Countries')) {
            $countries = new WC_Countries();
            if (is_object($countries) && !empty($countries)) {
                $countries = $countries->get_countries();
                if (is_array($countries) && !empty($countries)) {
                    $output = $countries;
                }
            }
        }
        return $output;
    }

    /**
     * Get users
     * 
     * @return array
     */
    public static function get_users() {
        $options = [];
        $query = new \WP_User_Query(['fields' => ['display_name', 'ID']]);
        if (!is_wp_error($query) && !empty($query->get_results())) {
            foreach ($query->get_results() as $item) {
                $options[$item->ID] = $item->display_name;
            }
        }
        return $options;
    }

    /**
     * Get user roles
     * 
     * @return array
     */
    public static function get_user_roles() {
        global $wp_roles;
        $options = [];

        if (!empty($wp_roles) && !empty($wp_roles->roles)) {
            foreach ($wp_roles->roles as $role_key => $role_value) {
                $options[$role_key] = $role_value['name'];
            }
        }
        return $options;
    }

    /**
     * Get menus
     * 
     * @return array
     */
    public static function get_menus() {
        $raw_menus = wp_get_nav_menus();
        $menus = wp_list_pluck($raw_menus, 'name', 'term_id');
        $parent = isset($_GET['parent_menu']) ? absint($_GET['parent_menu']) : 0;
        if (0 < $parent && isset($menus[$parent])) {
            unset($menus[$parent]);
        }
        return $menus;
    }

    /**
     * Get taxonomies
     * 
     * @param string $taxonomy Taxonomy name
     * @param string $option_value Option value key
     * @return array
     */
    public static function get_taxonomy_list($taxonomy = 'product_cat', $option_value = 'slug') {
        $terms = get_terms([
            'taxonomy' => $taxonomy,
            'hide_empty' => true,
        ]);
        $options = [];
        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                $options[$term->$option_value] = $term->name;
            }
        }
        return $options;
    }

    /**
     * Get post types
     * 
     * @param array $args Arguments
     * @return array
     */
    public static function get_post_types($args = []) {
        $post_type_args = ['show_in_nav_menus' => true];
        if (!empty($args['post_type'])) {
            $post_type_args['name'] = $args['post_type'];
        }
        $_post_types = get_post_types($post_type_args, 'objects');

        $post_types = [];
        if (!empty($args['defaultadd'])) {
            $post_types[strtolower($args['defaultadd'])] = ucfirst($args['defaultadd']);
        }
        foreach ($_post_types as $post_type => $object) {
            $post_types[$post_type] = $object->label;
        }
        return $post_types;
    }

    /**
     * Get image sizes
     * 
     * @return array
     */
    public static function get_image_sizes() {
        $sizes = get_intermediate_image_sizes();
        $filter = ['full' => 'Full'];
        foreach ($sizes as $value) {
            $filter[$value] = ucwords(str_replace(['_', '-'], [' ', ' '], $value));
        }
        return $filter;
    }

    /**
     * Get theme name
     * 
     * @param string $name Theme name
     * @return bool
     */
    public static function get_theme_by_name($name) {
        $theme = wp_get_theme($name);
        return $theme->exists();
    }

    /**
     * Get current theme directory
     * 
     * @return string
     */
    public static function get_current_theme_directory() {
        $current_theme_dir = '';
        $current_theme = wp_get_theme();
        
        if ($current_theme->exists() && $current_theme->parent()) {
            $parent_theme = $current_theme->parent();
            if ($parent_theme->exists()) {
                $current_theme_dir = $parent_theme->get_stylesheet();
            }
        } elseif ($current_theme->exists()) {
            $current_theme_dir = $current_theme->get_stylesheet();
        }
        return $current_theme_dir;
    }
}
