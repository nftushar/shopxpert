<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'smartshopblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'product_related' );
!empty( $settings['className'] ) ? $areaClasses[] = esc_attr( $settings['className'] ) : '';

!empty( $settings['columns']['desktop'] ) ? $areaClasses[] = 'smartshop-products-columns-'.$settings['columns']['desktop'] : 'smartshop-products-columns-4';
!empty( $settings['columns']['laptop'] ) ? $areaClasses[] = 'smartshop-products-columns-laptop-'.$settings['columns']['laptop'] : 'smartshop-products-columns-laptop-3';
!empty( $settings['columns']['tablet'] ) ? $areaClasses[] = 'smartshop-products-columns-tablet-'.$settings['columns']['tablet'] : 'smartshop-products-columns-tablet-2';
!empty( $settings['columns']['mobile'] ) ? $areaClasses[] = 'smartshop-products-columns-mobile-'.$settings['columns']['mobile'] : 'smartshop-products-columns-mobile-1';

$product = wc_get_product();
if ( empty( $product ) ) { return; }

// Get upsell Product
$product_per_page   = '-1';
$columns            = 4;
$orderby            = 'rand';
$order              = 'desc';
if ( ! empty( $settings['columns']['desktop'] ) ) {
	$columns = $settings['columns']['desktop'];
}
if ( ! empty( $settings['orderby'] ) ) {
	$orderby = $settings['orderby'];
}
if ( ! empty( $settings['order'] ) ) {
	$order = $settings['order'];
}
if ( ! empty( $settings['perPage'] ) ) {
	$product_per_page = $settings['perPage'];
}

echo '<div class="'.esc_attr(implode(' ', $areaClasses )).'">';
	woocommerce_upsell_display( $product_per_page, $columns, $orderby, $order );
echo '</div>';