<?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    $element_fields = Shopxpert_Admin_Fields::instance()->fields()['shopxpert_style_tabs'];
    $element_keys   = Shopxpert_Admin_Fields_Manager::instance()->get_field_key( $element_fields, 'name' );

?>
<div id="shopxpert_style_tabs" class="shopxpert-admin-main-tab-pane">
    <div class="shopxpert-admin-main-tab-pane-inner">
        <form class="shopxpert-dashboard" id="shopxpert-dashboard-style-form" action="#" method="post" data-section="shopxpert_style_tabs" data-fields='<?php echo wp_json_encode( $element_keys ); ?>'>
            <div class="shopxpert-admin-options">

                <?php
                    foreach( $element_fields as $key => $field ){
                        Shopxpert_Admin_Fields_Manager::instance()->add_field( $field, 'shopxpert_style_tabs' );
                    }
                ?>

                <div class="shopxpert-admin-option-heading">
                    <h4 class="shopxpert-admin-option-heading-title"><?php echo esc_html__('Helping Screenshot','shopxpert');?></h4>
                </div>
                <div class="shopxpert-admin-option">
                    <img src="<?php echo esc_url(SHOPXPERT_ADDONS_PL_URL.'incs/admin/assets/images/helping-screenshot.png'); ?>" alt="<?php echo esc_attr__('Helping Screenshot','shopxpert'); ?>">
                </div>
                <div class="shopxpert-admin-option shopxpert-sticky-condition">
                    <button class="shopxpert-admin-btn-save shopxpert-admin-btn shopxpert-admin-btn-primary hover-effect-1" style="margin-left:auto;" disabled="disabled"><?php echo esc_html__('D Save Changes','shopxpert');?></button>
                </div>
            </div>
        </form>
    </div>
</div>