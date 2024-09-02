<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'smartshopblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'smartshop-breadcrumb' );
!empty( $settings['className'] ) ? $areaClasses[] = esc_attr( $settings['className'] ) : '';

$args = [
	'delimiter'   => !empty( $settings['separator'] ) ? '<span class="breadcrumb-separator">'.$settings['separator'].'</span>' : '<span class="breadcrumb-separator">&nbsp;&#47;&nbsp;</span>',
	'wrap_before' => '<nav class="woocommerce-breadcrumb">',
	'wrap_after'  => '</nav>',
];

echo '<div class="'.esc_attr(implode(' ', $areaClasses )).'">';
	woocommerce_breadcrumb( $args );
echo '</div>';