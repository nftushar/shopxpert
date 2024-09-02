<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

error_log('gutenburg fiel');

$uniqClass 	 = 'smartshopblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'smartshop-archive-catalog-ordering-area', 'smartshop_archive_catalog_ordering' );

!empty( $settings['className'] ) ? $areaClasses[] = esc_attr( $settings['className'] ) : '';

if( $block['is_editor'] ){
	echo '<div class="'.esc_attr(implode(' ', $areaClasses )).'">';
		smartshop_product_shorting('menu_order');
	echo '</div>';
} else{
	echo '<div class="'.esc_attr(implode(' ', $areaClasses )).'">';
		woocommerce_catalog_ordering();
	echo '</div>';
}