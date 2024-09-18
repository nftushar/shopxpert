<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'shopxpertblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'shopxpert-product-tabs', 'product' );
!empty( $settings['className'] ) ? $areaClasses[] = esc_attr( $settings['className'] ) : '';

$product = wc_get_product();
if ( empty( $product ) ) {
	return;
}
if( $block['is_editor'] ){
	echo '<div class="woocommerce woocommerce-page single-product woocommerce-js">';
}
echo '<div class="'.esc_attr(implode(' ', $areaClasses )).'">';
	$post = get_post( $product->get_id() );
	wc_get_template( 'single-product/tabs/tabs.php' );
echo '</div>';
if( $block['is_editor'] ){
	echo '</div>';
}