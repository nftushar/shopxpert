<?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div id="smartshop_extension_tabs" class="smartshop-admin-main-tab-pane">
    <div class="smartshop-admin-main-tab-pane-inner">
        <?php Smartshop_Extension_Manager::instance()->render_html(); ?>
    </div>
</div>