<?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    $element_fields = \ShopXpert\Admin\Inc\Shopxpert_Admin_Fields::instance()->fields()['shopxpert_elements_tabs'];
    // $element_keys = array_column( $element_fields, 'name' );
    $element_keys   = \ShopXpert\Admin\Inc\Shopxpert_Admin_Fields_Manager::instance()->get_field_key( $element_fields, 'name' );

?>
<div id="shopxpert_elements_tabs" class="shopxpert-admin-main-tab-pane">
    <div class="shopxpert-admin-main-tab-pane-inner">
        
        <!-- Header Start -->
        <div class="shopxpert-header-admin">
            <div class="shopxpert-header-admin-content">
                <h6 class="shopxpert-header-admin-title"><?php echo esc_html__('ShopXpert  Element','shopxpert');?></h6>
                <p class="shopxpert-header-admin-text"><?php echo esc_html__('You can activate or deactivate all options in One click','shopxpert');?></p>
            </div>
            <div class="shopxpert-header-admin-actions">
                <button class="shopxpert-admin-btn enable" data-switch-toggle="enable" data-switch-target="element"><?php echo esc_html__('xx Enable all','shopxpert'); ?></button>
                <button class="shopxpert-admin-btn disable" data-switch-toggle="disable" data-switch-target="element"><?php echo esc_html__('Disable all','shopxpert'); ?></button>
            </div>
        </div>
        <!-- Header End -->

        <form class="shopxpert-dashboard" id="shopxpert-dashboard-element-form" action="#" method="post" data-section="shopxpert_elements_tabs" data-fields='<?php echo wp_json_encode( $element_keys ); ?>'>

            <!-- Elements Start -->
            <div class="shopxpert-admin-switch-blocks">
                <?php
                    foreach( $element_fields as $key => $field ){
                        Shopxpert_Admin_Fields_Manager::instance()->add_field( $field, 'shopxpert_elements_tabs' );
                    }
                ?>
            </div>
            <!-- Elements End -->

            <!-- Footer Start -->
            <div class="shopxpert-admin-footer">
                <button class="shopxpert-admin-btn-save shopxpert-admin-btn shopxpert-admin-btn-primary hover-effect-1" disabled="disabled"><?php echo esc_html__('DE Save Changes','shopxpert');?></button>
            </div>
            <!-- Footer End -->

        </form>

    </div>
</div>