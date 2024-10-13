<?php
namespace Shopxpert\Incs\Admin\Templates;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Shopxpert\Incs\Admin\Inc\Shopxpert_Admin_Fields;
use Shopxpert\Incs\Admin\Inc\Shopxpert_Admin_Fields_Manager;

// Get the fields using the singleton instance of Shopxpert_Admin_Fields
$Feature_fields = Shopxpert_Admin_Fields::instance()->fields()['shopxpert_others_tabs']['features'];
$other_fields = Shopxpert_Admin_Fields::instance()->fields()['shopxpert_others_tabs']['others'];

$all_fields = array_merge($Feature_fields, $other_fields);
$element_keys = Shopxpert_Admin_Fields_Manager::instance()->get_field_key($all_fields, 'name');

echo "<pre>";
print_r($element_keys);
echo "</pre>";


 
// print_r($Feature_fields);

if (!is_array($Feature_fields)) {
   echo "no Feature_fields";
}
if (!is_array($other_fields)) {
   echo "no other_fields";
}
?>
<div id="shopxpert_others_tabs" class="shopxpert-admin-main-tab-pane">
    <div class="shopxpert-admin-main-tab-pane-inner"> 
        <!-- Header Start -->
        <div class="shopxpert-admin-header">
            <div class="shopxpert-admin-header-content">
                <h6 class="shopxpert-admin-header-title"><?php echo esc_html__('ShopXpert  Feature','shopxpert'); ?></h6>
                <p class="shopxpert-admin-header-text"><?php echo esc_html__('You can activate or deactivate all options with a single click','shopxpert'); ?></p>
            </div>
            <div class="shopxpert-admin-header-actions">
                <button class="shopxpert-admin-btn enable" data-switch-toggle="enable" data-switch-target="element"><?php echo esc_html__('Enable all','shopxpert'); ?></button>
                <button class="shopxpert-admin-btn disable" data-switch-toggle="disable" data-switch-target="element"><?php echo esc_html__('Disable all','shopxpert'); ?></button>
            </div>
        </div>
        <!-- Header End -->

        <form class="shopxpert-dashboard" id="shopxpert-dashboard-Feature-form" action="#" method="post" data-section="shopxpert_others_tabs" data-fields='<?php echo wp_json_encode($element_keys); ?>'> 
            <!-- Features Start -->
            <div class="shopxpert-admin-switch-blocks">
                <?php 

                foreach($Feature_fields as $key => $field) {
                    Shopxpert_Admin_Fields_Manager::instance()->add_field($field, 'shopxpert_others_tabs');
                }
                ?>
            </div>
            <!-- Features End -->

            <div class="shopxpert-admin-others-options">
                <?php
                foreach($other_fields as $key => $field) {
                    Shopxpert_Admin_Fields_Manager::instance()->add_field($field, 'shopxpert_others_tabs');
                }
                ?>
            </div>

            <!-- Footer Start -->
            <div class="shopxpert-admin-footer">
                <button class="shopxpert-admin-btn-save shopxpert-admin-btn shopxpert-admin-btn-primary hover-effect-1" disabled="disabled"><?php echo esc_html__('1st Save Changes','shopxpert');?></button>
            </div>
            <!-- Footer End -->
        </form>
        
 </div>
</div>


