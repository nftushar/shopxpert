<?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    $element_fields = Smartshop_Admin_Fields::instance()->fields()['smartshop_woo_template_tabs'];
    $element_keys   = Smartshop_Admin_Fields_Manager::instance()->get_field_key( $element_fields, 'name' );

?>
<div id="smartshop_woo_template_tabs" class="smartshop-admin-main-tab-pane">
    <div class="smartshop-admin-main-tab-pane-inner">
        <form class="smartshop-dashboard" id="smartshop-dashboard-settings-form" action="#" method="post" data-section="smartshop_woo_template_tabs" data-fields='<?php echo wp_json_encode( $element_keys ); ?>'>
            <div class="smartshop-admin-options">
                <?php
                    foreach( $element_fields as $key => $field ){
                        Smartshop_Admin_Fields_Manager::instance()->add_field( $field, 'smartshop_woo_template_tabs' );
                    }
                ?>
                <div class="smartshop-admin-option smartshop-sticky-condition">
                    <button class="smartshop-admin-btn-save smartshop-admin-btn smartshop-admin-btn-primary hover-effect-1" style="margin-left:auto;" disabled="disabled"><?php echo esc_html__('DS Save Changes','smartshop');?></button>
                </div>
            </div>
        </form>
    </div>
</div>