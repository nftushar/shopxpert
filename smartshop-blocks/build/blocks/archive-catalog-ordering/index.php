<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// error_log('gutenburg fiel');

$uniqClass 	 = 'shopxpertblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'shopxpert-archive-catalog-ordering-area', 'shopxpert_archive_catalog_ordering' );

!empty( $settings['className'] ) ? $areaClasses[] = esc_attr( $settings['className'] ) : '';

if( $block['is_editor'] ){
	echo '<div class="'.esc_attr(implode(' ', $areaClasses )).'">';
		shopxpert_product_shorting('menu_order');
	echo '</div>';
} else{
	echo '<div class="'.esc_attr(implode(' ', $areaClasses )).'">';
		woocommerce_catalog_ordering();
	echo '</div>';
}