<?php
namespace Shopxpert\Incs\Admin\Templates;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Shopxpert\Incs\Admin\Inc\Shopxpert_Admin_Fields;
use Shopxpert\Incs\Admin\Inc\Shopxpert_Admin_Fields_Manager;



    $settings_fields = Shopxpert_Admin_Fields::instance()->fields()['shopxpert_gutenberg_tabs']['settings'];
    $blocks_fields  = Shopxpert_Admin_Fields::instance()->fields()['shopxpert_gutenberg_tabs']['blocks'];
 

    $all_fields   = array_merge( $settings_fields, $blocks_fields );
    $element_keys = Shopxpert_Admin_Fields_Manager::instance()->get_field_key( $all_fields, 'name' );
?>
<div id="shopxpert_gutenberg_tabs" class="shopxpert-admin-main-tab-pane">
    <div class="shopxpert-admin-main-tab-pane-inner">
        <div class="shopxpert-nested-tabs-area">
            <ul class="shopxpert-nested-tabs">
                <li><a href="#blocks-settings" class="wlactive"><?php echo esc_html__('Blocks','shopxpert');?></a></li>
                <li><a href="#general-settings"><?php echo esc_html__('Settings','shopxpert');?></a></li>
            </ul>
        </div>
        
        <form class="shopxpert-dashboard" id="shopxpert-dashboard-settings-form" action="#" method="post" data-section="shopxpert_gutenberg_tabs" data-fields='<?php echo wp_json_encode( $element_keys ); ?>'>
            <div class="shopxpert-admin-settings-area">

                <!-- Blocks Start -->
                <div id="blocks-settings" class="shopxpert-admin-nested-tab-pane wlactive">

                    <!-- Header Start -->
                    <div class="shopxpert-admin-header shopxpert-admin-header-two">
                        <div class="shopxpert-admin-header-content">
                            <h6 class="shopxpert-admin-header-title"><?php echo esc_html__('ShopXpert  Blocks','shopxpert');?></h6>
                            <p class="shopxpert-admin-header-text"><?php echo esc_html__('You can enable or disable all blocks by one click.','shopxpert');?></p>
                        </div>
                        <div class="shopxpert-admin-header-actions">
                            <button class="shopxpert-admin-btn enable" data-switch-toggle="enable" data-switch-target="element"><?php echo esc_html__('Enable all','shopxpert'); ?></button>
                            <button class="shopxpert-admin-btn disable" data-switch-toggle="disable" data-switch-target="element"><?php echo esc_html__('Disable all','shopxpert'); ?></button>
                        </div>
                    </div>
                    <!-- Header End -->

                    <div class="shopxpert-admin-switch-blocks">
                        <?php
                            foreach( $blocks_fields as $key => $field ){
                                Shopxpert_Admin_Fields_Manager::instance()->add_field( $field, 'shopxpert_gutenberg_tabs' );
                            }
                        ?>
                    </div>

                </div>
                <!-- Blocks End -->

                <!-- Settings Start -->
                <div id="general-settings" class="shopxpert-admin-nested-tab-pane">
                    <?php
                        foreach( $settings_fields as $key => $field ){
                            Shopxpert_Admin_Fields_Manager::instance()->add_field( $field, 'shopxpert_gutenberg_tabs' );
                        }
                    ?>
                </div>
                <!-- Settings End -->

                <div class="shopxpert-admin-footer">
                    <button class="shopxpert-admin-btn-save shopxpert-admin-btn shopxpert-admin-btn-primary hover-effect-1" style="margin-left:auto;" disabled="disabled"><?php echo esc_html__('DG Save Changes','shopxpert');?></button>
                </div>

            </div>
        </form>

    </div>
</div>