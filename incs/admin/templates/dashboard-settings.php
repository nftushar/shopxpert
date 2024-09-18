<?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    $element_fields = Shopxpert_Admin_Fields::instance()->fields()['shopxpert_woo_template_tabs'];
    $element_keys   = Shopxpert_Admin_Fields_Manager::instance()->get_field_key( $element_fields, 'name' );

?>
<div id="shopxpert_woo_template_tabs" class="shopxper-admin-main-tab-pane">
    <div class="shopxper-admin-main-tab-pane-inner">
        <form class="shopxpert-dashboard" id="shopxpert-dashboard-settings-form" action="#" method="post" data-section="shopxpert_woo_template_tabs" data-fields='<?php echo wp_json_encode( $element_keys ); ?>'>
            <div class="shopxpert-admin-options">
                <?php
                    foreach( $element_fields as $key => $field ){
                        Shopxpert_Admin_Fields_Manager::instance()->add_field( $field, 'shopxpert_woo_template_tabs' );
                    }
                ?>
                <div class="shopxpert-admin-option shopxpert-sticky-condition">
                    <button class="shopxpert-admin-btn-save shopxpert-admin-btn shopxpert-admin-btn-primary hover-effect-1" style="margin-left:auto;" disabled="disabled"><?php echo esc_html__('DS Save Changes','shopxper');?></button>
                </div>
            </div>
        </form>
    </div>
</div>