<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

	$uniqClass = 'smartshopblock-'.$settings['blockUniqId'];
	$areaClasses = array( 'smartshop-marker-area' );
	$classes = array( $uniqClass, 'wlb-marker-wrapper' );
	!empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';
	!empty( $settings['className'] ) ? $classes[] = esc_attr( $settings['className'] ) : '';
	!empty( $settings['style'] ) ? $classes[] = 'wlb-marker-style-'.$settings['style'] : 'wlb-marker-style-1';

	$background_image = smartshopBlocks_Background_Control( $settings, 'bgProperty' );

?>
<div class="<?php echo esc_attr(implode(' ', $areaClasses )); ?>">
	<div class="<?php echo esc_attr(implode(' ', $classes )); ?>" style="<?php echo esc_attr($background_image); ?> position:relative;">

		<?php
			foreach ( $settings['markerList'] as $item ):
				
				$horizontalPos = !empty( $item['horizontal'] ) ? 'left:'.$item['horizontal'].'%;' : 'left:50%;';
				$verticlePos = !empty( $item['verticle'] ) ? 'top:'.$item['verticle'].'%;' : '15%;';

			?>
				<div class="wlb_image_pointer" style="<?php echo esc_attr($horizontalPos.$verticlePos); ?>">
					<div class="wlb_pointer_box">
						<?php
							if( !empty( $item['title'] ) ){
								echo '<h4>'.esc_html__( $item['title'], 'shopxper' ).'</h4>';
							}
							if( !empty( $item['content'] ) ){
								echo '<p>'.esc_html__( $item['content'], 'shopxper' ).'</p>';
							}
						?>
					</div>
				</div>
				
			<?php
			endforeach;
		?> 
	</div>
</div>