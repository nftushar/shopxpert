<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$settings = $attributes;

$is_editor = ( isset( $_GET['is_editor_mode'] ) && $_GET['is_editor_mode'] == 'yes' ) ? true : false;

$uniqClass 	 = 'shopxpertblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'shopxpert_block_wishsuite_table' );
!empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';
!empty( $settings['className'] ) ? $areaClasses[] = esc_attr( $settings['className'] ) : '';


echo '<div class="'.esc_attr( implode(' ', $areaClasses ) ).'">';

    $short_code_attributes = [
        'empty_text' => $settings['emptyTableText'],
    ];
    echo shopxpert_do_shortcode( 'wishsuite_table', $short_code_attributes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

echo '</div>';