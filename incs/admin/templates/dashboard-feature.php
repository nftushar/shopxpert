<?php

namespace ShopXpert\Admin\Templates;



if (!defined('ABSPATH')) exit; // Exit if accessed directly

use ShopXpert\Admin\Inc\Shopxpert_Admin_Fields;
use ShopXpert\Admin\Inc\Shopxpert_Admin_Fields_Manager;

// Get the fields using the singleton instance of Shopxpert_Admin_Fields
$Feature_fields = Shopxpert_Admin_Fields::instance()->fields()['shopxpert_others_tabs']['features'];

$all_fields = array_merge($Feature_fields);

// Build key/section map so each toggle saves to its correct option section.
$element_keys = [];
foreach ($all_fields as $field) {
    // Prefer option_id, then name
    $key = isset($field['option_id']) && !empty($field['option_id']) ? $field['option_id'] : (isset($field['name']) ? $field['name'] : '');
    if (empty($key)) {
        continue;
    }
    // Use field-specific section when provided, otherwise fall back to the form section
    $element_keys[] = [
        'key'     => $key,
        'section' => isset($field['section']) && !empty($field['section']) ? $field['section'] : 'shopxpert_others_tabs',
    ];
}
?>

<div id="shopxpert_others_tabs" class="shopxpert-admin-main-tab-pane">
    <div class="shopxpert-admin-main-tab-pane-inner">


        <!-- Header Start -->
        <div class="shopxpert-admin-header">
            <div class="shopxpert-admin-header-content">
                <h6 class="shopxpert-admin-header-title"><?php echo esc_html__('ShopXpert  Feature', 'shopxpert'); ?></h6>
                <p class="shopxpert-admin-header-text"><?php echo esc_html__('You can activate or deactivate all options in One click', 'shopxpert'); ?></p>
            </div>
            <div class="shopxpert-admin-header-actions">
                <button class="shopxpert-admin-btn enable" data-switch-toggle="enable" data-switch-target="element"><?php echo esc_html__('Enable all', 'shopxpert'); ?></button>
                <button class="shopxpert-admin-btn disable" data-switch-toggle="disable" data-switch-target="element"><?php echo esc_html__('Disable all', 'shopxpert'); ?></button>
            </div>
        </div>
        <!-- Header End -->

        <form class="shopxpert-dashboard" id="shopxpert-dashboard-Feature-form" action="#" method="post" data-section="shopxpert_others_tabs" data-fields='<?php echo wp_json_encode($element_keys); ?>'>
            <!-- Features Start -->
            <div class="shopxpert-admin-switch-blocks">
                <?php

                foreach ($Feature_fields as $key => $field) {
                    Shopxpert_Admin_Fields_Manager::instance()->add_field($field, 'shopxpert_others_tabs');
                }
                ?>
            </div>
            <!-- Features End -->

            <!-- Footer Start -->
            <div class="shopxpert-admin-footer">
                <button class="shopxpert-admin-btn-save shopxpert-admin-btn shopxpert-admin-btn-primary hover-effect-1" disabled="disabled"><?php echo esc_html__('Save Changes', 'shopxpert'); ?></button>
            </div>
            <!-- Footer End -->
        </form>

    </div>
</div>