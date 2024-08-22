<?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    $element_fields = Woolentor_Admin_Fields::instance()->fields()['smartshop_style_tabs'];
    $element_keys   = Woolentor_Admin_Fields_Manager::instance()->get_field_key( $element_fields, 'name' );

?>
<div id="smartshop_style_tabs" class="smartshop-admin-main-tab-pane">
    <div class="smartshop-admin-main-tab-pane-inner">
        <form class="smartshop-dashboard" id="smartshop-dashboard-style-form" action="#" method="post" data-section="smartshop_style_tabs" data-fields='<?php echo wp_json_encode( $element_keys ); ?>'>
            <div class="smartshop-admin-options">

                <?php
                    foreach( $element_fields as $key => $field ){
                        Woolentor_Admin_Fields_Manager::instance()->add_field( $field, 'smartshop_style_tabs' );
                    }
                ?>

                <div class="smartshop-admin-option-heading">
                    <h4 class="smartshop-admin-option-heading-title"><?php echo esc_html__('Helping Screenshot','smartshop');?></h4>
                </div>
                <div class="smartshop-admin-option">
                    <img src="<?php echo esc_url(SMARTSHOP_ADDONS_PL_URL.'incs/admin/assets/images/helping-screenshot.png'); ?>" alt="<?php echo esc_attr__('Helping Screenshot','smartshop'); ?>">
                </div>
                <div class="smartshop-admin-option smartshop-sticky-condition">
                    <button class="smartshop-admin-btn-save smartshop-admin-btn smartshop-admin-btn-primary hover-effect-1" style="margin-left:auto;" disabled="disabled"><?php echo esc_html__('D Save Changes','smartshop');?></button>
                </div>
            </div>
        </form>
    </div>
</div>