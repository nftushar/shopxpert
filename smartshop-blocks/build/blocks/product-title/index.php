<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
		
$uniqClass 	 = 'smartshopblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'product_title' );
!empty( $settings['className'] ) ? $areaClasses[] = esc_attr( $settings['className'] ) : '';

$title_html_tag = smartshop_validate_html_tag( $settings['titleTag'] );
// $title = $block['is_editor'] ? get_the_title( smartshopBlocks_get_last_product_id() ) : get_the_title();
$title = get_the_title();

echo sprintf( "<%s class='%s'>%s</%s>", $title_html_tag, esc_attr( implode(' ', $areaClasses ) ), $title, $title_html_tag  ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped