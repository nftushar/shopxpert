<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'shopxpertblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'product_related' );
!empty( $settings['className'] ) ? $areaClasses[] = esc_attr( $settings['className'] ) : '';

!empty( $settings['columns']['desktop'] ) ? $areaClasses[] = 'shopxpert-products-columns-'.$settings['columns']['desktop'] : 'shopxpert-products-columns-4';
!empty( $settings['columns']['laptop'] ) ? $areaClasses[] = 'shopxpert-products-columns-laptop-'.$settings['columns']['laptop'] : 'shopxpert-products-columns-laptop-3';
!empty( $settings['columns']['tablet'] ) ? $areaClasses[] = 'shopxpert-products-columns-tablet-'.$settings['columns']['tablet'] : 'shopxpert-products-columns-tablet-2';
!empty( $settings['columns']['mobile'] ) ? $areaClasses[] = 'shopxpert-products-columns-mobile-'.$settings['columns']['mobile'] : 'shopxpert-products-columns-mobile-1';

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