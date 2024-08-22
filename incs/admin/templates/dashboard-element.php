<?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    $element_fields = Woolentor_Admin_Fields::instance()->fields()['smartshop_elements_tabs'];
    // $element_keys = array_column( $element_fields, 'name' );
    $element_keys   = Woolentor_Admin_Fields_Manager::instance()->get_field_key( $element_fields, 'name' );

?>
<div id="smartshop_elements_tabs" class="smartshop-admin-main-tab-pane">
    <div class="smartshop-admin-main-tab-pane-inner">
        
        <!-- Header Start -->
        <div class="smartshop-admin-header">
            <div class="smartshop-admin-header-content">
                <h6 class="smartshop-admin-header-title"><?php echo esc_html__('SmartShop Element','smartshop');?></h6>
                <p class="smartshop-admin-header-text"><?php echo esc_html__('You can enable or disable all options by one click.','smartshop');?></p>
            </div>
            <div class="smartshop-admin-header-actions">
                <button class="smartshop-admin-btn enable" data-switch-toggle="enable" data-switch-target="element"><?php echo esc_html__('Enable all','smartshop'); ?></button>
                <button class="smartshop-admin-btn disable" data-switch-toggle="disable" data-switch-target="element"><?php echo esc_html__('Disable all','smartshop'); ?></button>
            </div>
        </div>
        <!-- Header End -->

        <form class="smartshop-dashboard" id="smartshop-dashboard-element-form" action="#" method="post" data-section="smartshop_elements_tabs" data-fields='<?php echo wp_json_encode( $element_keys ); ?>'>

            <!-- Elements Start -->
            <div class="smartshop-admin-switch-blocks">
                <?php
                    foreach( $element_fields as $key => $field ){
                        Woolentor_Admin_Fields_Manager::instance()->add_field( $field, 'smartshop_elements_tabs' );
                    }
                ?>
            </div>
            <!-- Elements End -->

            <!-- Footer Start -->
            <div class="smartshop-admin-footer">
                <button class="smartshop-admin-btn-save smartshop-admin-btn smartshop-admin-btn-primary hover-effect-1" disabled="disabled"><?php echo esc_html__('DE Save Changes','smartshop');?></button>
            </div>
            <!-- Footer End -->

        </form>

    </div>
</div>