<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'shopxpertblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'shopxpert-product-additional-info' );
!empty( $settings['className'] ) ? $areaClasses[] = esc_attr( $settings['className'] ) : '';

$product = wc_get_product();
if ( empty( $product ) ) {
	return;
}
echo '<div class="'.esc_attr(implode(' ', $areaClasses )).'">';
	wc_get_template( 'single-product/tabs/additional-information.php' );
echo '</div>';