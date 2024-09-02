<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'smartshopblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'smartshop-archive-data-area' );

!empty( $settings['className'] ) ? $areaClasses[] = esc_attr( $settings['className'] ) : '';

$data       = smartshop_get_archive_data();
$title_tag  = smartshop_validate_html_tag( $settings['titleTag'] );

$title          = ( $settings['showTitle'] == true && !empty( $data['title'] ) ) ? sprintf( "<%s class='smartshop-archive-title'>%s</%s>", $title_tag, wp_kses( $data['title'], smartshop_get_html_allowed_tags('title') ), $title_tag  ) : '';
$description    = ( $settings['showDescription'] == true && !empty( $data['desc'] ) ) ? sprintf( "<div class='smartshop-archive-desc'>%s</div>", wp_kses( $data['desc'], smartshop_get_html_allowed_tags('desc') )  ) : '';
$image          = ( $settings['showImage'] == 'yes' && !empty( $data['image_url'] ) ) ? sprintf( "<div class='smartshop-archive-image'><img src='%s' alt='%s'></div>", esc_url( $data['image_url'] ), esc_attr( $data['title'] )  ) : '';

echo '<div class="'.esc_attr(implode(' ', $areaClasses )).'">';
	echo sprintf( '%s %s %s', $image, $title, $description ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo '</div>';