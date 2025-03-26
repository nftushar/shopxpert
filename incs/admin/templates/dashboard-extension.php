<?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div id="shopxpert_extension_tabs" class="shopxpert-admin-main-tab-pane">
    <div class="shopxpert-admin-main-tab-pane-inner">
        <?php Shopxpert_Extension_Manager::instance()->render_html(); ?>
    </div>
</div>