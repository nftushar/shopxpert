<?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<script type="text/template" id="tmpl-smartshopmodule">

    <div class="smartshop-module-setting-popup">
        <div id="smartshop-admin-pro-popup" class="smartshop-admin-popup open">
            <div class="smartshop-module-setting-popup-content smartshop-admin-popup-inner">
                <button class="smartshop-admin-popup-close">
                    <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.08366 1.73916L8.26116 0.916656L5.00033 4.17749L1.73949 0.916656L0.916992 1.73916L4.17783 4.99999L0.916992 8.26082L1.73949 9.08332L5.00033 5.82249L8.26116 9.08332L9.08366 8.26082L5.82283 4.99999L9.08366 1.73916Z" fill="currentColor"></path>
                    </svg>
                </button>
                <form class="smartshop-module-setting-data" id="smartshop-module-setting-form" action="#" method="post" data-section="{{data.section}}" data-fields="{{data.fileds}}">
                    {{{ data.content }}}
                    <div class="smartshop-admin-footer {{ data.section !== 'smartshop_others_tabs' ? 'has-reset' : '' }}">
                        <# if( data.section != 'smartshop_others_tabs' ){ #>
                            <button class="smartshop-admin-module-reset smartshop-admin-btn smartshop-admin-btn-primary hover-effect-1"><?php echo esc_html__('Reset To Default','smartshop');?></button>
                        <# } #>
                        <button class="smartshop-admin-module-save smartshop-admin-btn smartshop-admin-btn-primary hover-effect-1" disabled="disabled"><?php echo esc_html__('Save Changes','smartshop');?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</script>
