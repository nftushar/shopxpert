<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass     = 'shopxpertblock-'.$settings['blockUniqId'];
$areaClasses   = array( $uniqClass,'shopxpert', 'shopxpert-product-curvy' );

!empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';
!empty( $settings['className'] ) ? $areaClasses[] = esc_attr( $settings['className'] ) : '';

!empty( $settings['columns']['desktop'] ) ? $areaClasses[] = 'shopxpert-grid-columns-'.$settings['columns']['desktop'] : 'shopxpert-grid-columns-4';
!empty( $settings['columns']['laptop'] ) ? $areaClasses[] = 'shopxpert-grid-columns-laptop-'.$settings['columns']['laptop'] : 'shopxpert-grid-columns-laptop-3';
!empty( $settings['columns']['tablet'] ) ? $areaClasses[] = 'shopxpert-grid-columns-tablet-'.$settings['columns']['tablet'] : 'shopxpert-grid-columns-tablet-2';
!empty( $settings['columns']['mobile'] ) ? $areaClasses[] = 'shopxpert-grid-columns-mobile-'.$settings['columns']['mobile'] : 'shopxpert-grid-columns-mobile-1';

$queryArgs = [
	'perPage'	=> $settings['perPage'],
	'filterBy'	=> $settings['productFilterType']
];
if( $settings['customOrder'] ){
	$queryArgs['orderBy'] = $settings['orderBy'];
	$queryArgs['order'] = $settings['order'];
}
if( is_array( $settings['selectedCategories'] ) && count( $settings['selectedCategories'] ) > 0 ){
	$queryArgs['categories'] = $settings['selectedCategories'];
}
$products = new \WP_Query( shopxpertBlocks_Product_Query( $queryArgs ) );


$content_style = '';
if( isset( $settings['layout'] ) ){
	if ( $settings['layout'] == '2' ) {
		$content_style = 'wl_left-item';
	}elseif ( $settings['layout']=='3' ) {
		$content_style = 'wl_dark-item';
	}else{
		$content_style = '';
	}
}

?>
<div class="<?php echo esc_attr(implode(' ', $areaClasses )); ?>">

	<?php if( $products->have_posts() ): ?>

		<div class="shopxpert-grid <?php echo ( $settings['noGutter'] === true ? esc_attr('shopxpert-no-gutters') : '' ); ?>">
			<?php
				while( $products->have_posts() ) {
					$products->the_post();

					$product = wc_get_product( get_the_ID() );

					$btn_class = $product->is_purchasable() && $product->is_in_stock() ? ' add_to_cart_button' : '';

					$btn_class .= $product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? ' ajax_add_to_cart' : '';
					$description = wp_trim_words ( get_the_content(), $settings['contentLimit'], '' );

					?>
						<div class="shopxpert-grid-column">
							<div class="wl_single-product-item <?php echo esc_attr($content_style); ?>">

								<a href="<?php the_permalink(); ?>" class="product-thumbnail">
									<div class="images">
										<?php echo $product->get_image( 'full' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</div>
								</a>

								<div class="product-content">
									<div class="product-content-top">

										<?php if( $settings['showTitle'] === true ): ?>
											<h6 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
										<?php endif; ?>

										<?php if( $settings['showPrice'] === true ): ?>
											<div class="product-price">
												<span class="new-price"><?php woocommerce_template_loop_price();?></span>
											</div>
										<?php endif; ?>

										<?php
											if( $settings['showContent'] === true ){
												echo '<p>'.$description.'</p>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											}
										?>

										<?php if( $settings['showRating'] === true ): ?>
											<div class="reading">
												<?php woocommerce_template_loop_rating(); ?>
											</div>
										<?php endif; ?>

									</div>
									<ul class="action">
										<li class="wl_cart">
											<a href="<?php echo $product->add_to_cart_url(); ?>" data-quantity="1" class="action-item <?php echo esc_attr($btn_class); ?>" data-product_id="<?php echo $product->get_id(); ?>"><?php echo __( '<i class="fa fa-shopping-cart"></i>', 'shopxpert' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
										</li>
										<?php
											if( true === shopxpert_has_wishlist_plugin() ){
												echo '<li>'.shopxpert_add_to_wishlist_button('<i class="fa fa-heart-o"></i>','<i class="fa fa-heart"></i>').'</li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											}
										?>                                    
										<?php
											if( function_exists('shopxpert_compare_button') && true === shopxpert_exist_compare_plugin() ){
												echo '<li>';
													shopxpert_compare_button(
														array(
															'style' => 2,
															'btn_text' => '<i class="fa fa-exchange"></i>',
															'btn_added_txt' => '<i class="fa fa-exchange"></i>' 
														)
													);
												echo '</li>';
											}
										?>
									</ul>
								</div>

							</div>
						</div>
					<?php
				}
			?>
		</div>

	<?php wp_reset_postdata(); endif; ?>
	
</div>