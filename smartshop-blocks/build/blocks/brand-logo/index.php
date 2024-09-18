<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

	$uniqClass 	 = 'shopxpertblock-'.$settings['blockUniqId'];
	$classes 	 = array( $uniqClass, 'ht-brand-wrap' );
	$areaClasses = array( 'shopxpert-brand-area' );

	!empty( $settings['className'] ) ? $areaClasses[] = esc_attr( $settings['className'] ) : '';
	!empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';

	!empty( $settings['columns']['desktop'] ) ? $areaClasses[] = 'shopxpert-grid-columns-'.$settings['columns']['desktop'] : 'shopxpert-grid-columns-4';
	!empty( $settings['columns']['laptop'] ) ? $areaClasses[] = 'shopxpert-grid-columns-laptop-'.$settings['columns']['laptop'] : 'shopxpert-grid-columns-laptop-3';
	!empty( $settings['columns']['tablet'] ) ? $areaClasses[] = 'shopxpert-grid-columns-tablet-'.$settings['columns']['tablet'] : 'shopxpert-grid-columns-tablet-2';
	!empty( $settings['columns']['mobile'] ) ? $areaClasses[] = 'shopxpert-grid-columns-mobile-'.$settings['columns']['mobile'] : 'shopxpert-grid-columns-mobile-1';

	$default_img = '<img src="'.SHOPXPERT_BLOCK_URL.'/assets/images/brand.png'.'" alt="'.esc_html__('Brand Logo','shopxper').'">';
	$brands = $settings['brandLogoList'];

	// Slider Options
	$slider_settings = [];
	if( $settings['slider'] === true ){
		$is_rtl = is_rtl();
		$direction = $is_rtl ? 'rtl' : 'ltr';
		$slider_settings = [
			'arrows' => ( true === $settings['arrows'] ),
			'dots' => ( true === $settings['dots'] ),
			'autoplay' => ( true === $settings['autoplay'] ),
			'autoplay_speed' => absint( $settings['autoplaySpeed'] ),
			'animation_speed' => absint( $settings['animationSpeed'] ),
			'pause_on_hover' => ( true === $settings['pauseOnHover'] ),
			'rtl' => $is_rtl,
		];

		$slider_responsive_settings = [
			'product_items' => absint($settings['sliderItems']),
            'scroll_columns' => absint($settings['scrollColumns']),
            'tablet_width' => absint($settings['tabletWidth']),
            'tablet_display_columns' => absint($settings['tabletDisplayColumns']),
            'tablet_scroll_columns' => absint($settings['tabletScrollColumns']),
            'mobile_width' => absint($settings['mobileWidth']),
            'mobile_display_columns' => absint($settings['mobileDisplayColumns']),
            'mobile_scroll_columns' => absint($settings['mobileScrollColumns']),
		];
		$slider_settings = array_merge( $slider_settings, $slider_responsive_settings );
	}
		
?>
<div class="<?php echo esc_attr(implode(' ', $areaClasses )); ?>">
	<div class="<?php echo esc_attr(implode(' ', $classes )); ?>">
		<?php
			if( is_array( $brands ) ){

				if( $settings['slider'] === true ){
					echo '<div id="'.esc_attr("product-slider-" . $settings['blockUniqId']).'" dir="'.esc_attr($direction).'" class="product-slider" data-settings=\'' . wp_json_encode( $slider_settings ) . '\' style="display:none">';
				}else{
					echo '<div class="shopxpert-grid '.( $settings['noGutter'] === true ? 'shopxpert-no-gutters' : '' ).'">';
				}
					foreach ( $brands as $key => $brand ) {
	
						$image = !empty( $brand['image']['id'] ) ? wp_get_attachment_image( $brand['image']['id'], 'full' ) : $default_img;
						$logo  = !empty( $brand['link'] ) ? sprintf('<a href="%s" target="_blank">%s</a>',esc_url( $brand['link'] ), $image ) : $image;
	
						?>
							<div class="shopxpert-grid-column">
								<div class="wl-single-brand">
									<?php echo wp_kses_post( $logo ); ?>
								</div>
							</div>
						<?php
					}
				echo '</div>';
			}
		?>
	</div>
</div>