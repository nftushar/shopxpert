<?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    $element_fields = Smartshop_Admin_Fields::instance()->fields()['smartshop_elements_tabs'];
    // $element_keys = array_column( $element_fields, 'name' );
    $element_keys   = Smartshop_Admin_Fields_Manager::instance()->get_field_key( $element_fields, 'name' );

?>
<div id="smartshop_elements_tabs" class="shopxper-admin-main-tab-pane">
    <div class="shopxper-admin-main-tab-pane-inner">
        
        <!-- Header Start -->
        <div class="smartshop-admin-header">
            <div class="smartshop-admin-header-content">
                <h6 class="smartshop-admin-header-title"><?php echo esc_html__('SmartShop Element','shopxper');?></h6>
                <p class="smartshop-admin-header-text"><?php echo esc_html__('You can activate or deactivate all options with a single click','shopxper');?></p>
            </div>
            <div class="smartshop-admin-header-actions">
                <button class="smartshop-admin-btn enable" data-switch-toggle="enable" data-switch-target="element"><?php echo esc_html__('Enable all','shopxper'); ?></button>
                <button class="smartshop-admin-btn disable" data-switch-toggle="disable" data-switch-target="element"><?php echo esc_html__('Disable all','shopxper'); ?></button>
            </div>
        </div>
        <!-- Header End -->

        <form class="smartshop-dashboard" id="smartshop-dashboard-element-form" action="#" method="post" data-section="smartshop_elements_tabs" data-fields='<?php echo wp_json_encode( $element_keys ); ?>'>

            <!-- Elements Start -->
            <div class="smartshop-admin-switch-blocks">
                <?php
                    foreach( $element_fields as $key => $field ){
                        Smartshop_Admin_Fields_Manager::instance()->add_field( $field, 'smartshop_elements_tabs' );
                    }
                ?>
            </div>
            <!-- Elements End -->

            <!-- Footer Start -->
            <div class="smartshop-admin-footer">
                <button class="smartshop-admin-btn-save smartshop-admin-btn smartshop-admin-btn-primary hover-effect-1" disabled="disabled"><?php echo esc_html__('DE Save Changes','shopxper');?></button>
            </div>
            <!-- Footer End -->

        </form>

    </div>
</div>