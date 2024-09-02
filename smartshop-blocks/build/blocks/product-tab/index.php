<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

	$product_style 	= $settings['style'];
	$columns 		= $settings['columns']['desktop'];
	$rows 			= $settings['rows'];
	$customClass 	= !empty( $settings['className'] ) ? esc_attr( $settings['className'] ) : '';
	$proslider 		= $settings['slider'] ? 'yes' : 'no';
	$producttab 	= $settings['productTab'] ? 'yes' : 'no';

	$product_type 	= $settings['productFilterType'];
	$per_page 		= $settings['perPage'];
	$custom_order 	= $settings['customOrder'];
	$query_args = array(
		'per_page' => $per_page,
		'product_type' => $product_type
	);

	// Category Wise
	$product_cats = !empty( $settings['selectedCategories'] ) ? $settings['selectedCategories'] : array();
	if( is_array( $product_cats ) && count( $product_cats ) > 0 ){
		$query_args['categories'] = $product_cats;
	}

	// Custom Order
	if( $custom_order == true ){
		$orderby = $settings['orderBy'];
		$order 	 = $settings['order'];
		$query_args['custom_order'] = array (
			'orderby' => $orderby,
			'order' => $order,
		);
	}

	$args = smartshop_product_query( $query_args );

	$products = new \WP_Query( $args );

	// Slider Options
	$slider_settings = array();
	if( $proslider == 'yes' ){
		$is_rtl = is_rtl();
		$direction = $is_rtl ? 'rtl' : 'ltr';
		$slider_settings = [
			'arrows' => (true === $settings['slarrows']),
			'dots' => (true === $settings['sldots']),
			'autoplay' => (true === $settings['slautolay']),
			'autoplay_speed' => absint($settings['slautoplaySpeed']),
			'animation_speed' => absint($settings['slanimationSpeed']),
			'pause_on_hover' => ('yes' === $settings['slpauseOnHover']),
			'rtl' => $is_rtl,
		];

		$slider_responsive_settings = [
			'product_items' => absint($settings['slitems']),
			'scroll_columns' => absint($settings['slscrollItem']),
			'tablet_width' => absint($settings['sltabletWidth']),
			'tablet_display_columns' => absint($settings['sltabletDisplayColumns']),
			'tablet_scroll_columns' => absint($settings['sltabletScrollColumns']),
			'mobile_width' => absint($settings['slMobileWidth']),
			'mobile_display_columns' => absint($settings['slMobileDisplayColumns']),
			'mobile_scroll_columns' => absint($settings['slMobileScrollColumns']),

		];
		$slider_settings = array_merge( $slider_settings, $slider_responsive_settings );
	}

	$collumval = 'slide-item smartshop-grid-column';
	

	$tabuniqid = $settings['blockUniqId'];
	$uniqClass = 'smartshopblock-'.$settings['blockUniqId'];
	$customClass .= ' '.$uniqClass;
	!empty( $settings['className'] ) ? $customClass .= ' '.$settings['className'] : '';

	$areaClasses   = array( 'smartshop-product-tab-area' );
	!empty( $settings['columns']['desktop'] ) ? $areaClasses[] = 'smartshop-grid-columns-'.$settings['columns']['desktop'] : 'smartshop-grid-columns-4';
	!empty( $settings['columns']['laptop'] ) ? $areaClasses[] = 'smartshop-grid-columns-laptop-'.$settings['columns']['laptop'] : 'smartshop-grid-columns-laptop-3';
	!empty( $settings['columns']['tablet'] ) ? $areaClasses[] = 'smartshop-grid-columns-tablet-'.$settings['columns']['tablet'] : 'smartshop-grid-columns-tablet-2';
	!empty( $settings['columns']['mobile'] ) ? $areaClasses[] = 'smartshop-grid-columns-mobile-'.$settings['columns']['mobile'] : 'smartshop-grid-columns-mobile-1';

?>
<div class="<?php echo esc_attr($customClass); ?>">
<div class="<?php echo esc_attr(implode(' ', $areaClasses )); ?>">

<?php if ( $producttab == 'yes' ) { ?>
	<div class="product-tab-list ht-text-center">
		<ul class="ht-tab-menus">
			<?php
				$m=0;
				if( is_array( $product_cats ) && count( $product_cats ) > 0 ){

					// Category retrive
					$catargs = array(
						'taxonomy'   => 'product_cat',
						'orderby'    => 'name',
						'order'      => 'ASC',
						'hide_empty' => true,
						'slug'       => $product_cats,
					);
					$prod_categories = get_terms($catargs);

					foreach( $prod_categories as $prod_cats ){
						$m++;
						$field_name = is_numeric( $product_cats[0] ) ? 'term_id' : 'slug';
						$args['tax_query'] = array(
							array(
								'taxonomy' => 'product_cat',
								'terms' => $prod_cats,
								'field' => $field_name,
								'include_children' => false
							),
						);
						if( 'featured' == $product_type ){
							$args['tax_query'][] = array(
								'taxonomy' => 'product_visibility',
								'field'    => 'name',
								'terms'    => 'featured',
								'operator' => 'IN',
							);
						}
						$fetchproduct = new \WP_Query( $args );

						if( $fetchproduct->have_posts() ){
							?>
								<li><a class="<?php if($m==1){ echo 'htactive';}?>" href="#smartshoptab<?php echo esc_attr($tabuniqid.$m);?>">
									<?php echo esc_attr( $prod_cats->name );?>
								</a></li>
							<?php
						}
					}
				}
			?>
		</ul>
	</div>
<?php }; ?>

	<?php if( is_array( $product_cats ) && (count( $product_cats ) > 0) && ( $producttab == 'yes' ) ): ?>

		<?php
			$j=0;
			$tabcatargs = array(
				'taxonomy'   => 'product_cat',
				'orderby'    => 'name',
				'order'      => 'ASC',
				'hide_empty' => true,
				'slug'       => $product_cats,
			);
			$tabcat_fach = get_terms( $tabcatargs );
			foreach( $tabcat_fach as $cats ):
				$j++;
				$field_name = is_numeric($product_cats[0])?'term_id':'slug';
				$args['tax_query'] = array(
					array(
						'taxonomy' => 'product_cat',
						'terms' => $cats,
						'field' => $field_name,
						'include_children' => false
					)
				);
				if( 'featured' == $product_type ){
					$args['tax_query'][] = array(
						'taxonomy' => 'product_visibility',
						'field'    => 'name',
						'terms'    => 'featured',
						'operator' => 'IN',
					);
				}
				$products = new \WP_Query( $args );

				if( $products->have_posts() ):
		?>
			<div class="ht-tab-pane <?php if( $j==1 ){ echo 'htactive'; } ?>" id="<?php echo esc_attr('smartshoptab'.$tabuniqid.$j);?>">
				<div class="smartshop-grid">

					<!-- product item start -->
					<div class="<?php echo esc_attr( $collumval );?>">
					<?php
						$loopitem = 1;
						while( $products->have_posts() ): $products->the_post();

							wc_get_template( '/loop-item.php', ['settings' => $settings, 'loopitem' => $loopitem ], __DIR__, __DIR__ );

						if( $loopitem % $rows == 0 && ($products->post_count != $loopitem ) ){
							echo '</div><div class="'.esc_attr( $collumval ).'">';
						}
						$loopitem++; endwhile; wp_reset_query(); wp_reset_postdata();
						echo '</div>';
					?>
					<!-- product item end -->

				</div>
			</div>
		<?php endif; endforeach;?>

	<?php else:?>
		<div class="smartshop-grid <?php echo ( $proslider == 'yes' ) ? 'smartshop-grid-slider' : ''; ?>">

			<?php if( $proslider == 'yes' ){ echo '<div id="product-slider-' . esc_attr($settings['blockUniqId']) . '" dir="'.esc_attr($direction).'" class="product-slider" data-settings=\'' . wp_json_encode($slider_settings) . '\' style="display:none">';}?>
				
				<!-- product item start -->
				<div class="<?php echo esc_attr( $collumval );?>">
				<?php
					$loopitem = 1;
					while( $products->have_posts() ): $products->the_post();

						wc_get_template( '/loop-item.php', ['settings' => $settings, 'loopitem' => $loopitem ], __DIR__, __DIR__ );

					if( $loopitem % $rows == 0 && ($products->post_count != $loopitem ) ){
						echo '</div><div class="'.esc_attr( $collumval ).'">';
					}
					$loopitem++; endwhile; wp_reset_query(); wp_reset_postdata();
					echo '</div>';
				?>
				<!-- product item end -->

			<?php if( $proslider == 'yes' ){ echo '</div>';} ?>

		</div>
	<?php endif;?>

	</div>

</div>