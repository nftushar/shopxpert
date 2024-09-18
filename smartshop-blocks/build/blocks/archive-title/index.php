<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'shopxpertblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'shopxpert-archive-data-area' );

!empty( $settings['className'] ) ? $areaClasses[] = esc_attr( $settings['className'] ) : '';

$data       = shopxpert_get_archive_data();
$title_tag  = shopxpert_validate_html_tag( $settings['titleTag'] );

$title          = ( $settings['showTitle'] == true && !empty( $data['title'] ) ) ? sprintf( "<%s class='shopxpert-archive-title'>%s</%s>", $title_tag, wp_kses( $data['title'], shopxpert_get_html_allowed_tags('title') ), $title_tag  ) : '';
$description    = ( $settings['showDescription'] == true && !empty( $data['desc'] ) ) ? sprintf( "<div class='shopxpert-archive-desc'>%s</div>", wp_kses( $data['desc'], shopxpert_get_html_allowed_tags('desc') )  ) : '';
$image          = ( $settings['showImage'] == 'yes' && !empty( $data['image_url'] ) ) ? sprintf( "<div class='shopxpert-archive-image'><img src='%s' alt='%s'></div>", esc_url( $data['image_url'] ), esc_attr( $data['title'] )  ) : '';

echo '<div class="'.esc_attr(implode(' ', $areaClasses )).'">';
	echo sprintf( '%s %s %s', $image, $title, $description ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo '</div>';