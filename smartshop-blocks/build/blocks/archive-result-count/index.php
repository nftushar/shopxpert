<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'smartshopblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'smartshop-archive-result-count-area', 'smartshop_archive_result_count' );

!empty( $settings['className'] ) ? $areaClasses[] = esc_attr( $settings['className'] ) : '';

if( $block['is_editor'] ){
	echo '<div class="'.esc_attr(implode(' ', $areaClasses )).'">';
		$args = array(
			'total'    => wp_count_posts( 'product' )->publish,
			'per_page' => $settings['productPerPage'],
			'current'  => 1,
		);
		wc_get_template( 'loop/result-count.php', $args );
	echo '</div>';
} else{
	$total    = wc_get_loop_prop( 'total' );
	$par_page = !empty( $settings['productPerPage'] ) ? $settings['productPerPage'] : wc_get_loop_prop('per_page');
	echo '<div class="'.esc_attr(implode(' ', $areaClasses )).'">';
		$page = absint( empty( $_GET['product-page'] ) ? 1 : $_GET['product-page'] );
		smartshop_product_result_count( $total, $par_page, $page );
	echo '</div>';
}