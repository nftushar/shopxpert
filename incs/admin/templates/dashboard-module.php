<?php
namespace Smartshop\Incs\Admin\Templates;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Smartshop\Incs\Admin\Inc\Smartshop_Admin_Fields;
use Smartshop\Incs\Admin\Inc\Smartshop_Admin_Fields_Manager;

// Get the fields using the singleton instance of Smartshop_Admin_Fields
$module_fields = Smartshop_Admin_Fields::instance()->fields()['smartshop_others_tabs']['modules'];
$other_fields = Smartshop_Admin_Fields::instance()->fields()['smartshop_others_tabs']['others'];

$all_fields = array_merge($module_fields, $other_fields);
$element_keys = Smartshop_Admin_Fields_Manager::instance()->get_field_key($all_fields, 'name');

echo "<pre>";
print_r($element_keys);
echo "</pre>";


 
// print_r($module_fields);

if (!is_array($module_fields)) {
   echo "no module_fields";
}
if (!is_array($other_fields)) {
   echo "no other_fields";
}
?>
<div id="smartshop_others_tabs" class="smartshop-admin-main-tab-pane">
    <div class="smartshop-admin-main-tab-pane-inner"> 
        <!-- Header Start -->
        <div class="smartshop-admin-header">
            <div class="smartshop-admin-header-content">
                <h6 class="smartshop-admin-header-title"><?php echo esc_html__('SmartShop Module','smartshop'); ?></h6>
                <p class="smartshop-admin-header-text"><?php echo esc_html__('You can enable or disable all options by one click.','smartshop'); ?></p>
            </div>
            <div class="smartshop-admin-header-actions">
                <button class="smartshop-admin-btn enable" data-switch-toggle="enable" data-switch-target="element"><?php echo esc_html__('Enable all','smartshop'); ?></button>
                <button class="smartshop-admin-btn disable" data-switch-toggle="disable" data-switch-target="element"><?php echo esc_html__('Disable all','smartshop'); ?></button>
            </div>
        </div>
        <!-- Header End -->

        <form class="smartshop-dashboard" id="smartshop-dashboard-module-form" action="#" method="post" data-section="smartshop_others_tabs" data-fields='<?php echo wp_json_encode($element_keys); ?>'> 
            <!-- Modules Start -->
            <div class="smartshop-admin-switch-blocks">
                <?php 

                foreach($module_fields as $key => $field) {
                    Smartshop_Admin_Fields_Manager::instance()->add_field($field, 'smartshop_others_tabs');
                }
                ?>
            </div>
            <!-- Modules End -->

            <div class="smartshop-admin-others-options">
                <?php
                foreach($other_fields as $key => $field) {
                    Smartshop_Admin_Fields_Manager::instance()->add_field($field, 'smartshop_others_tabs');
                }
                ?>
            </div>

            <!-- Footer Start -->
            <div class="smartshop-admin-footer">
                <button class="smartshop-admin-btn-save smartshop-admin-btn smartshop-admin-btn-primary hover-effect-1" disabled="disabled"><?php echo esc_html__('1st Save Changes','smartshop');?></button>
            </div>
            <!-- Footer End -->
        </form>
        
 </div>
</div>


