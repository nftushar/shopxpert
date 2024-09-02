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

$args = [
	'posts_per_page' => 4,
	'columns' => 4,
	'orderby' => $settings['orderBy'],
	'order'   => $settings['order'],
];
if ( ! empty( $settings['perPage'] ) ) {
	$args['posts_per_page'] = $settings['perPage'];
}
if ( ! empty( $settings['columns']['desktop'] ) ) {
	$args['columns'] = $settings['columns']['desktop'];
}

// Get related Product
$args['related_products'] = array_filter( array_map( 'wc_get_product', wc_get_related_products( $product->get_id(), 
	$args['posts_per_page'], $product->get_upsell_ids() ) ), 'wc_products_array_filter_visible' );
$args['related_products'] = wc_products_array_orderby( $args['related_products'], $args['orderby'], $args['order'] );

echo '<div class="'.esc_attr(implode(' ', $areaClasses )).'">';
	wc_get_template( 'single-product/related.php', $args );
echo '</div>';