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