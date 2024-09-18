<?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div id="shopxpert_extension_tabs" class="shopxper-admin-main-tab-pane">
    <div class="shopxper-admin-main-tab-pane-inner">
        <?php Smartshop_Extension_Manager::instance()->render_html(); ?>
    </div>
</div>