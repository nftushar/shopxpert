<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
		
$uniqClass 	 = 'smartshopblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'smartshop-product-description' );
!empty( $settings['className'] ) ? $areaClasses[] = esc_attr( $settings['className'] ) : '';

$product = wc_get_product();
if ( empty( $product ) ) { return; }
echo '<div class="'.esc_attr(implode(' ', $areaClasses )).'">';
	echo '<div class="woocommerce_product_description">';
		the_content();
	echo '</div>';
echo '</div>';