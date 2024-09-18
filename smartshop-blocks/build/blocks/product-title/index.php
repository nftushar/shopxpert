<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
		
$uniqClass 	 = 'shopxpertblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'product_title' );
!empty( $settings['className'] ) ? $areaClasses[] = esc_attr( $settings['className'] ) : '';

$title_html_tag = shopxpert_validate_html_tag( $settings['titleTag'] );
// $title = $block['is_editor'] ? get_the_title( shopxpertBlocks_get_last_product_id() ) : get_the_title();
$title = get_the_title();

echo sprintf( "<%s class='%s'>%s</%s>", $title_html_tag, esc_attr( implode(' ', $areaClasses ) ), $title, $title_html_tag  ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped