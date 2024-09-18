<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( $block['is_editor'] ){
	\ShopXpert_Default_Data::instance()->theme_hooks('shopxpert-product-archive-addons');
}

$uniqClass 	 = 'shopxpertblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'shopxpert_block_archive_default' );
!empty( $settings['className'] ) ? $areaClasses[] = esc_attr( $settings['className'] ) : '';
!empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';
!empty( $settings['contentAlignment'] ) ? $areaClasses[] = 'shopxpert-content-align-'.$settings['contentAlignment'] : '';

if( isset( $settings['saleTagShow'] ) && $settings['saleTagShow'] === false){
	$areaClasses[] = 'shopxpert-archive-sale-badge-hide';
}else{
	!empty( $settings['saleTagPosition'] ) ? $areaClasses[] = 'shopxpert-archive-sale-badge-'.$settings['saleTagPosition'] : '';
}
// Manage Column
!empty( $settings['columns']['desktop'] ) ? $areaClasses[] = 'shopxpert-products-columns-'.$settings['columns']['desktop'] : 'shopxpert-products-columns-4';
!empty( $settings['columns']['laptop'] ) ? $areaClasses[] = 'shopxpert-products-columns-laptop-'.$settings['columns']['laptop'] : 'shopxpert-products-columns-laptop-3';
!empty( $settings['columns']['tablet'] ) ? $areaClasses[] = 'shopxpert-products-columns-tablet-'.$settings['columns']['tablet'] : 'shopxpert-products-columns-tablet-2';
!empty( $settings['columns']['mobile'] ) ? $areaClasses[] = 'shopxpert-products-columns-mobile-'.$settings['columns']['mobile'] : 'shopxpert-products-columns-mobile-1';

//Product Filter Feature
$contentClasses = array();
$areaAttributes = array();
$filterable = ( isset( $settings['filterable'] ) ? rest_sanitize_boolean( $settings['filterable'] ) : true );
if ( true === $filterable ) {
	$areaClasses[] = 'wl-filterable-products-wrap';
	$contentClasses[] = 'wl-filterable-products-content';
	$areaAttributes[] = 'data-wl-widget-name="shopxpert-product-archive-addons"';
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
$not_found_content = shopxpert_products_not_found_content();

echo '<div class="'.esc_attr( implode(' ', $areaClasses ) ).'" '.implode(' ', $areaAttributes ).'>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo ( ( true === $filterable ) ? '<div class="'.esc_attr(implode(' ', $contentClasses )).'">' : '' );
		if ( wp_strip_all_tags( $content ) ) {
			echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else{
			echo $not_found_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	echo ( ( true === $filterable ) ? '</div>' : '' );
echo '</div>';