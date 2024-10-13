<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'shopxpertblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass );

!empty( $settings['className'] ) ? $areaClasses[] = esc_attr( $settings['className'] ) : '';


echo '<div class="'.esc_attr(implode(' ', $areaClasses )).'">';
	?>
		<div class="wl-call-forprice">
			<a href="tel:<?php echo esc_attr($settings['buttonPhoneNumber']); ?>" ><?php echo esc_html__( $settings['buttonText'], 'shopxpert' ); ?></a>
		</div>
	<?php
echo '</div>';