<?php

namespace ShopXpert\Helpers;

use ShopXpert\Cache\Manager as CacheManager;

if (!defined('ABSPATH')) exit;

/**
 * Options Helper
 * 
 * Handles option-related functions with caching support
 */
class Options {

    /**
     * Get an option value with intelligent caching
     * 
     * @param string $option The option name
     * @param string $section The option section/group
     * @param mixed $default Default value if not found
     * @return mixed
     */
    public static function get($option, $section, $default = '') {
        return CacheManager::get_option($option, $section, $default);
    }

    /**
     * Get option label text
     * 
     * @param string $option The option name
     * @param string $section The option section
     * @param string $default Default value
     * @return string
     */
    public static function get_label_text($option, $section, $default = '') {
        $options = get_option($section);
        if (isset($options[$option])) {
            if (!empty($options[$option])) {
                return $options[$option];
            }
            return $default;
        }
        return $default;
    }

    /**
     * Update an option
     * 
     * @param string $section The section
     * @param string $option_key The option key
     * @param mixed $new_value The new value
     * @return void
     */
    public static function update($section, $option_key, $new_value) {
        $options_data = get_option($section);
        if (isset($options_data[$option_key])) {
            $options_data[$option_key] = $new_value;
        } else {
            $options_data = [$option_key => $new_value];
        }
        update_option($section, $options_data);
    }

    /**
     * Check if a feature is enabled
     * 
     * @param string $option The option name
     * @param string $section The option section
     * @return bool
     */
    public static function is_enabled($option, $section) {
        return self::get($option, $section, 'off') === 'on';
    }
}
