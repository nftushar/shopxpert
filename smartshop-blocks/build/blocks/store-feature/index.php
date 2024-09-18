<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass = 'shopxpertblock-'.$settings['blockUniqId'];
$areaClasses = array('shopxpert-store-feature-area');
$classes = array( $uniqClass, 'shopxpert-blocks ht-feature-wrap' );
!empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';
!empty( $settings['className'] ) ? $classes[] = esc_attr( $settings['className'] ) : '';
!empty( $settings['layout'] ) ? $classes[] = 'ht-feature-style-'.$settings['layout'] : 'ht-feature-style-1';
!empty( $settings['textAlignment'] ) ? $classes[] = 'shopxpert-text-align-'.$settings['textAlignment'] : 'shopxpert-text-align-center';

$store_image = !empty( $settings['featureImage']['id'] ) ? wp_get_attachment_image( $settings['featureImage']['id'], 'full' ) : '';

?>
<div class="<?php echo esc_attr(implode(' ', $areaClasses )); ?>">
	<div class="<?php echo esc_attr(implode(' ', $classes )); ?>">
		<div class="ht-feature-inner">
			<?php
				if( !empty( $store_image ) ){
					echo '<div class="ht-feature-img">'.$store_image.'</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
			?>
			<div class="ht-feature-content">
				<?php
					if( !empty( $settings['title'] ) ){
						echo '<h4>'.esc_html($settings['title']).'</h4>';
					}
					if( !empty( $settings['subTitle'] ) ){
						echo '<p>'.esc_html($settings['subTitle']).'</p>';
					}
				?>
			</div>
		</div>
	</div>
</div>