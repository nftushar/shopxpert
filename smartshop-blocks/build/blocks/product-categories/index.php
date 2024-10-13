<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'shopxpertblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass );

!empty( $settings['className'] ) ? $areaClasses[] = esc_attr( $settings['className'] ) : '';


echo '<div class="'.esc_attr(implode(' ', $areaClasses )).'">';
	global $product;
	$product = wc_get_product();

	if( $block['is_editor'] ){
		echo \ShopXpert_Default_Data::instance()->default( 'wl-single-product-categories' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	} else{
		if ( empty( $product ) ) { return; }

		if( has_term( '', 'product_cat', $product->get_id() ) ) {
			echo '<div class="shopxpert_product_categories_info">';
				?>
					<?php if( $settings['categoriesTitleHide'] !== true): ?>
						<span class="categories-title"><?php echo sprintf( esc_html__( _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'shopxpert' ) ) ); ?></span>
					<?php endif; ?>
					<?php echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">', '</span>' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php
			echo '</div>';
		}
	}
        
echo '</div>';