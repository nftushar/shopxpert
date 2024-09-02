<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'smartshopblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass );

!empty( $settings['className'] ) ? $areaClasses[] = esc_attr( $settings['className'] ) : '';

if( $block['is_editor'] ){
	$product = wc_get_product(smartshop_get_last_product_id());
} else{
	$product = wc_get_product();
}
if ( empty( $product ) ) { return; }

echo '<div class="'.esc_attr(implode(' ', $areaClasses )).'">';

	if( has_term( '', 'product_tag', $product->get_id() ) ) {
		echo '<div class="smartshop_product_tags_info">';
			?>
				<?php if($settings['showTitle'] === true ): ?><span class="tags-title"><?php echo sprintf( esc_html( _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'smartshop' ) ) ); ?></span> <?php endif; ?>
				<?php echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tagged_as">', '</span>' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php
			do_action( 'woocommerce_product_meta_end' );
		echo '</div>';
	}

echo '</div>';