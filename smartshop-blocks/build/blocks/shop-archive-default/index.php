<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( $block['is_editor'] ){
	\SmartShop_Default_Data::instance()->theme_hooks('smartshop-product-archive-addons');
}

$uniqClass 	 = 'smartshopblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'smartshop_block_archive_default' );
!empty( $settings['className'] ) ? $areaClasses[] = esc_attr( $settings['className'] ) : '';
!empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';
!empty( $settings['contentAlignment'] ) ? $areaClasses[] = 'smartshop-content-align-'.$settings['contentAlignment'] : '';

if( isset( $settings['saleTagShow'] ) && $settings['saleTagShow'] === false){
	$areaClasses[] = 'smartshop-archive-sale-badge-hide';
}else{
	!empty( $settings['saleTagPosition'] ) ? $areaClasses[] = 'smartshop-archive-sale-badge-'.$settings['saleTagPosition'] : '';
}
// Manage Column
!empty( $settings['columns']['desktop'] ) ? $areaClasses[] = 'smartshop-products-columns-'.$settings['columns']['desktop'] : 'smartshop-products-columns-4';
!empty( $settings['columns']['laptop'] ) ? $areaClasses[] = 'smartshop-products-columns-laptop-'.$settings['columns']['laptop'] : 'smartshop-products-columns-laptop-3';
!empty( $settings['columns']['tablet'] ) ? $areaClasses[] = 'smartshop-products-columns-tablet-'.$settings['columns']['tablet'] : 'smartshop-products-columns-tablet-2';
!empty( $settings['columns']['mobile'] ) ? $areaClasses[] = 'smartshop-products-columns-mobile-'.$settings['columns']['mobile'] : 'smartshop-products-columns-mobile-1';

//Product Filter Module
$contentClasses = array();
$areaAttributes = array();
$filterable = ( isset( $settings['filterable'] ) ? rest_sanitize_boolean( $settings['filterable'] ) : true );
if ( true === $filterable ) {
	$areaClasses[] = 'wl-filterable-products-wrap';
	$contentClasses[] = 'wl-filterable-products-content';
	$areaAttributes[] = 'data-wl-widget-name="smartshop-product-archive-addons"';
	$areaAttributes[] = 'data-wl-widget-settings="' . esc_attr( htmlspecialchars( wp_json_encode( $settings ) ) ) . '"';
}

if ( WC()->session && function_exists( 'wc_print_notices' ) ) {
	wc_print_notices();
}

if ( ! isset( $GLOBALS['post'] ) ) {
	$GLOBALS['post'] = null;
}

$options = [
	'query_post_type'	=> ! empty( $settings['paginate'] ) ? 'current_query' : '',
	'columns' 			=> $settings['columns']['desktop'],
	'rows' 				=> $settings['rows'],
	'paginate' 			=> !empty( $settings['paginate'] ) ? 'yes' : 'no',
	'editor_mode' 		=> $block['is_editor'],
];

if( !empty( $settings['paginate'] ) ){
	$options['paginate'] = 'yes';
	$options['allow_order'] = !empty( $settings['allowOrder'] ) ? 'yes' : 'no';
	$options['show_result_count'] = !empty( $settings['showResultCount'] ) ? 'yes' : 'no';
}else{
	$options['order'] 	= !empty( $settings['order'] ) ? $settings['order'] : 'desc';
	$options['orderby'] = !empty( $settings['orderBy'] ) ? $settings['orderBy'] : 'date';
}

$shortcode 	= new \Archive_Products_Render( $options, 'products', $filterable );
$content 	= $shortcode->get_content();
$not_found_content = smartshop_products_not_found_content();

echo '<div class="'.esc_attr( implode(' ', $areaClasses ) ).'" '.implode(' ', $areaAttributes ).'>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo ( ( true === $filterable ) ? '<div class="'.esc_attr(implode(' ', $contentClasses )).'">' : '' );
		if ( wp_strip_all_tags( $content ) ) {
			echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else{
			echo $not_found_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	echo ( ( true === $filterable ) ? '</div>' : '' );
echo '</div>';