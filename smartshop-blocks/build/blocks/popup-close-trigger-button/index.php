<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'smartshopblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'smartshop-popup-close-button-area' );

!empty( $settings['className'] ) ? $areaClasses[] = esc_attr( $settings['className'] ) : '';

$button_icon = '';
if( !empty( $settings['buttonIcon'] ) ){
	$button_icon = '<i class="'.$settings['buttonIcon'].'"></i>';
}

$short_code_attributes = [
	'button_text' 	=> !empty( $settings['buttonText'] ) ? $settings['buttonText'] : __( 'Close', 'smartshop' ),
	'button_icon' 	=> htmlentities( $button_icon ),
	'icon_position' => !empty( $settings['iconPosition'] ) ? $settings['iconPosition'] : 'left',
	'button_align' 	=> !empty( $settings['alignment'] ) ? $settings['alignment'] : 'center',
	'action' 		=> !empty( $settings['action'] ) ? $settings['action'] : 'close_popup',
	'redirect_url' 	=> !empty( $settings['redirectUrl'] ) ? $settings['redirectUrl'] : '',
];

echo '<div class="'.esc_attr(implode(' ', $areaClasses )).'">';
	echo smartshop_do_shortcode( 'wlpb_trigger_button', $short_code_attributes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo '</div>';