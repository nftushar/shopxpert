<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'smartshopblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass );

!empty( $settings['className'] ) ? $areaClasses[] = esc_attr( $settings['className'] ) : '';


echo '<div class="'.esc_attr(implode(' ', $areaClasses )).'">';
	global $product;
	$product = wc_get_product();

	if( $block['is_editor'] ){
		echo \SmartShop_Default_Data::instance()->default( 'wl-single-product-sku' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	} else{
		if ( empty( $product ) ) { return; }

		echo '<div class="smartshop_product_sku_info">';
			do_action( 'woocommerce_product_meta_start' );
			if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>
				<?php if( $settings['skuTitleHide'] !== true ): ?>
					<span class="sku-title"><?php esc_html_e('SKU:', 'smartshop'); ?></span>
				<?php endif; ?>
				<span class="sku"><?php echo ( $sku = $product->get_sku() ) ? esc_html($sku) : esc_html__( 'N/A', 'smartshop' ); ?></span>
			<?php endif;
		echo '</div>';

	}
        
echo '</div>';