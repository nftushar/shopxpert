<?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    $settings_fields = Smartshop_Admin_Fields::instance()->fields()['smartshop_gutenberg_tabs']['settings'];
    $blocks_fields  = Smartshop_Admin_Fields::instance()->fields()['smartshop_gutenberg_tabs']['blocks'];

    $all_fields   = array_merge( $settings_fields, $blocks_fields );
    $element_keys = Smartshop_Admin_Fields_Manager::instance()->get_field_key( $all_fields, 'name' );
?>
<div id="smartshop_gutenberg_tabs" class="smartshop-admin-main-tab-pane">
    <div class="smartshop-admin-main-tab-pane-inner">
        <div class="smartshop-nested-tabs-area">
            <ul class="smartshop-nested-tabs">
                <li><a href="#blocks-settings" class="wlactive"><?php echo esc_html__('Blocks','smartshop');?></a></li>
                <li><a href="#general-settings"><?php echo esc_html__('Settings','smartshop');?></a></li>
            </ul>
        </div>
        
        <form class="smartshop-dashboard" id="smartshop-dashboard-settings-form" action="#" method="post" data-section="smartshop_gutenberg_tabs" data-fields='<?php echo wp_json_encode( $element_keys ); ?>'>
            <div class="smartshop-admin-settings-area">

                <!-- Blocks Start -->
                <div id="blocks-settings" class="smartshop-admin-nested-tab-pane wlactive">

                    <!-- Header Start -->
                    <div class="smartshop-admin-header smartshop-admin-header-two">
                        <div class="smartshop-admin-header-content">
                            <h6 class="smartshop-admin-header-title"><?php echo esc_html__('SmartShop Blocks','smartshop');?></h6>
                            <p class="smartshop-admin-header-text"><?php echo esc_html__('You can enable or disable all blocks by one click.','smartshop');?></p>
                        </div>
                        <div class="smartshop-admin-header-actions">
                            <button class="smartshop-admin-btn enable" data-switch-toggle="enable" data-switch-target="element"><?php echo esc_html__('Enable all','smartshop'); ?></button>
                            <button class="smartshop-admin-btn disable" data-switch-toggle="disable" data-switch-target="element"><?php echo esc_html__('Disable all','smartshop'); ?></button>
                        </div>
                    </div>
                    <!-- Header End -->

                    <div class="smartshop-admin-switch-blocks">
                        <?php
                            foreach( $blocks_fields as $key => $field ){
                                Smartshop_Admin_Fields_Manager::instance()->add_field( $field, 'smartshop_gutenberg_tabs' );
                            }
                        ?>
                    </div>

                </div>
                <!-- Blocks End -->

                <!-- Settings Start -->
                <div id="general-settings" class="smartshop-admin-nested-tab-pane">
                    <?php
                        foreach( $settings_fields as $key => $field ){
                            Smartshop_Admin_Fields_Manager::instance()->add_field( $field, 'smartshop_gutenberg_tabs' );
                        }
                    ?>
                </div>
                <!-- Settings End -->

                <div class="smartshop-admin-footer">
                    <button class="smartshop-admin-btn-save smartshop-admin-btn smartshop-admin-btn-primary hover-effect-1" style="margin-left:auto;" disabled="disabled"><?php echo esc_html__('DG Save Changes','smartshop');?></button>
                </div>

            </div>
        </form>

    </div>
</div>