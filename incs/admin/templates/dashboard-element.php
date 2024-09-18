<?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    $element_fields = Smartshop_Admin_Fields::instance()->fields()['shopxpert_elements_tabs'];
    // $element_keys = array_column( $element_fields, 'name' );
    $element_keys   = Smartshop_Admin_Fields_Manager::instance()->get_field_key( $element_fields, 'name' );

?>
<div id="shopxpert_elements_tabs" class="shopxper-admin-main-tab-pane">
    <div class="shopxper-admin-main-tab-pane-inner">
        
        <!-- Header Start -->
        <div class="shopxpert-admin-header">
            <div class="shopxpert-admin-header-content">
                <h6 class="shopxpert-admin-header-title"><?php echo esc_html__('ShopXpert  Element','shopxper');?></h6>
                <p class="shopxpert-admin-header-text"><?php echo esc_html__('You can activate or deactivate all options with a single click','shopxper');?></p>
            </div>
            <div class="shopxpert-admin-header-actions">
                <button class="shopxpert-admin-btn enable" data-switch-toggle="enable" data-switch-target="element"><?php echo esc_html__('Enable all','shopxper'); ?></button>
                <button class="shopxpert-admin-btn disable" data-switch-toggle="disable" data-switch-target="element"><?php echo esc_html__('Disable all','shopxper'); ?></button>
            </div>
        </div>
        <!-- Header End -->

        <form class="shopxpert-dashboard" id="shopxpert-dashboard-element-form" action="#" method="post" data-section="shopxpert_elements_tabs" data-fields='<?php echo wp_json_encode( $element_keys ); ?>'>

            <!-- Elements Start -->
            <div class="shopxpert-admin-switch-blocks">
                <?php
                    foreach( $element_fields as $key => $field ){
                        Smartshop_Admin_Fields_Manager::instance()->add_field( $field, 'shopxpert_elements_tabs' );
                    }
                ?>
            </div>
            <!-- Elements End -->

            <!-- Footer Start -->
            <div class="shopxpert-admin-footer">
                <button class="shopxpert-admin-btn-save shopxpert-admin-btn shopxpert-admin-btn-primary hover-effect-1" disabled="disabled"><?php echo esc_html__('DE Save Changes','shopxper');?></button>
            </div>
            <!-- Footer End -->

        </form>

    </div>
</div>