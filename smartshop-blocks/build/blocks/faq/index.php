<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass = 'shopxpertblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'shopxpertfaq-block-area' );
$classes = array( 'htshopxpert-faq' );

!empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';
!empty( $settings['className'] ) ? $areaClasses[] = esc_attr( $settings['className'] ) : '';
!empty( $settings['iconPosition'] ) ? $classes[] = 'shopxpertfaq-icon-pos-'.$settings['iconPosition'] : '';

$icon = '<span class="htshopxpert-faq-head-indicator"></span>';

$accordion_settings = [
	'showitem' => ( $settings['showFirstItem'] === true ),
];
$dataOptions = 'data-settings='.wp_json_encode( $accordion_settings );

?>
<div class="<?php echo esc_attr(implode(' ', $areaClasses )); ?>">
	<div class="<?php echo esc_attr(implode(' ', $classes )); ?>" id="<?php echo esc_attr('htshopxpert-faq-'.$settings['blockUniqId']); ?>" <?php echo esc_attr($dataOptions); ?>>

		<?php
			foreach ( $settings['faqList'] as $item ):
				
				$title = ( !empty( $item['title'] ) ? '<span class="htshopxpert-faq-head-text">'.esc_html($item['title']).'</span>' : '' );

			?>
				<div class="htshopxpert-faq-card">
					<?php
						if( $settings['iconPosition'] == 'right'){
							echo sprintf( '<div class="htshopxpert-faq-head">%2$s %1$s</div>',$icon, $title ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}else{
							echo sprintf( '<div class="htshopxpert-faq-head">%1$s %2$s</div>',$icon, $title ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
					?>
					<div class="htshopxpert-faq-body">
						<div class="htshopxpert-faq-content">
							<?php 
								if ( !empty( $item['content'] ) ) {
									echo wp_kses_post( $item['content'] );
								}
							?>
						</div>
					</div>
				</div>
				
			<?php
			endforeach;
		?>

	</div>
</div>