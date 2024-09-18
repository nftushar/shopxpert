<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'shopxpertblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass );

!empty( $settings['className'] ) ? $areaClasses[] = esc_attr( $settings['className'] ) : '';

$id = $settings['blockUniqId'];
$currency_symbol = get_woocommerce_currency_symbol();

$filter_list = $settings['filterList'];


global $wp;
if ( '' == get_option('permalink_structure' ) ) {
	$current_url = remove_query_arg(array('page', 'paged'), add_query_arg($wp->query_string, '', home_url($wp->request)));
} else {
	$current_url = preg_replace('%\/page/[0-9]+%', '', home_url(trailingslashit($wp->request)));
}

$submit_btton_icon = !empty( $settings['formSubmitButtonIcon'] ) ? '<i class="'.esc_attr( $settings['formSubmitButtonIcon'] ).'"></i>' : '<i class="fa fa-search"></i>';
$filter_btton_icon = !empty( $settings['filterButtonIcon'] ) ? '<i class="'.esc_attr( $settings['filterButtonIcon'] ).'"></i>' : '<i class="fa fa-filter"></i>';


echo '<div class="'.esc_attr(implode(' ', $areaClasses )).'">';
?>
	<div class="shopxpert-horizontal-filter-wrap">
		<!-- Heaer Box Area Start -->
		<div class="shopxpert-heaer-box-area">

			<div class="shopxpert-filter-header-top-area">
				<div class="shopxpert-header-left-side">
					<?php
						if( !empty( $settings['filterAreaTitle'] ) ){
							echo '<h2 class="wl_hoz_filter_title">'.esc_html($settings['filterAreaTitle']).'</h2>';
						}
					?>
				</div>
				<div class="shopxpert-header-right-side">
					<?php 
					if( $settings['showSearchForm'] === true ):

						if ( isset( $_GET['q'] ) || isset( $_GET['s'] ) ) {
							$s = !empty( $_GET['s'] ) ? $_GET['s'] : '';
							$q = !empty( $_GET['q'] ) ? $_GET['q'] : '';
							$search_value = !empty( $q ) ? $q : $s;
						}else{
							$search_value = '';
						}

						if( !empty( $settings['redirectFormUrl'] ) ){
							$form_action = $settings['redirectFormUrl'];
						}else{
							$form_action = $current_url;
						}

					?>
						<form class="shopxpert-header-search-form" role="search" method="get" action="<?php echo esc_url( $form_action ); ?>">
							<div class="shopxpert-search-input-box">
								<input class="input-box" type="search" placeholder="<?php echo esc_attr_x( $settings['searchFormPlaceholder'], 'placeholder', 'shopxper' ); ?>" value="<?php echo esc_attr( $search_value ); ?>" name="q" title="<?php echo esc_attr_x( 'Search for:', 'label', 'shopxper' ); ?>" />
								<button class="input-inner-btn" type="submit" aria-label="<?php echo esc_attr__( 'Search', 'shopxper' );?>"><?php echo $submit_btton_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
							</div>
						</form>
					<?php endif; ?>

					<?php if( $settings['showFilterButton'] == true ): ?>
						<div class="shopxpert-search-filter-custom">
							<a href="#" id="filter-toggle-<?php echo esc_attr($id); ?>" class="filter-icon"><?php echo $filter_btton_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
						</div>
					<?php endif; ?>
				</div>
			</div>

			<div id="filter-item-<?php echo esc_attr($id); ?>" class="filter-item">
				<div class="shopxpert-filter-field-area">
					<div class="shopxpert-filter-field-wrap">
						<?php 
							if( isset( $filter_list ) ){
								$styleIndividual_Item = '';

								foreach ( $filter_list as $filter_key => $filter_item ) {

									$itemUniqId = $id.$filter_key;

									$filter_label = '';
									if( true === $settings['showFilterLabel'] ){
										$filter_label = '<label for="shopxpert-field-for-'.esc_attr($itemUniqId).'">'.esc_html($filter_item['filterTitle']).'</label>';
									}

									if( 'sort_by' === $filter_item['filterType'] ){
										$wlsort = ( isset( $_GET['wlsort'] ) && !empty( $_GET['wlsort'] ) ) ? $_GET['wlsort'] : '';
										$sort_by_asc_lavel = isset( $filter_item['sortByAscLavel'] ) ? $filter_item['sortByAscLavel'] : 'ASC';
										$sort_by_desc_lavel = isset( $filter_item['sortByDescLavel'] ) ? $filter_item['sortByDescLavel'] : 'DESC';
									?>
										<div class="shopxpert-filter-single-item shopxpert-states-input-auto">
											<?php echo $filter_label; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
											<select name="wl_sort" id="shopxpert-field-for-<?php echo esc_attr($itemUniqId); ?>" class="shopxpert-onchange-single-item shopxpert-single-select-<?php echo esc_attr($id); ?>" data-minimum-results-for-search="Infinity" data-placeholder="<?php echo esc_attr($filter_item['filterPlaceholder']); ?>">
												<?php
													if( !empty( $filter_item['filterPlaceholder'] ) && !$block['is_editor'] ){echo '<option></option>';}
												?>
												<option value="&wlsort=ASC" <?php selected( 'ASC', $wlsort, true ); ?> ><?php echo esc_html__( $sort_by_asc_lavel, 'shopxper' ); ?></option>
												<option value="&wlsort=DESC" <?php selected( 'DESC', $wlsort, true ); ?> ><?php echo esc_html__( $sort_by_desc_lavel, 'shopxper' ); ?></option>
											</select>
										</div>
									<?php

									}elseif( 'order_by' === $filter_item['filterType'] ){
										$wlorder_by = ( isset( $_GET['wlorder_by'] ) && !empty( $_GET['wlorder_by'] ) ) ? $_GET['wlorder_by'] : '';
										?>
										<div class="shopxpert-filter-single-item shopxpert-states-input-auto shopxpert-repeater-item-<?php echo esc_attr($itemUniqId); ?>">
											<?php echo $filter_label; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
											<select name="wl_order_by_sort" id="shopxpert-field-for-<?php echo esc_attr($itemUniqId); ?>" class="shopxpert-onchange-single-item shopxpert-single-select-<?php echo esc_attr($id); ?>" data-minimum-results-for-search="Infinity" data-placeholder="<?php echo esc_attr($filter_item['filterPlaceholder']); ?>">
												<?php
													if( !empty( $filter_item['filterPlaceholder'] ) && !$block['is_editor'] ){echo '<option></option>';}
												
													foreach ( shopxpert_order_by_opts() as $key => $opt_data ) {
														echo '<option value="&wlorder_by='.esc_attr( $key ).'" '.selected( $key, $wlorder_by, false ).'>'.esc_html__( $opt_data, 'shopxper' ).'</option>';
													}
												?>
											</select>
										</div>
										<?php

									}elseif( 'price_by' === $filter_item['filterType'] ){

										$woocommerce_currency_pos = get_option( 'woocommerce_currency_pos' );
										$currency_pos_left = false;
										$currency_pos_space = false;
										if( $woocommerce_currency_pos == 'left' || $woocommerce_currency_pos == 'left_space' ){
											$currency_pos_left = true;
										}

										if( strstr( $woocommerce_currency_pos, 'space' ) ){
											$currency_pos_space = true;
										}
				
										if( $currency_pos_space == true && $currency_pos_left == true){
											// left space
											$final_currency_symbol = $currency_symbol.' ';
										}else if( $currency_pos_space == true && $currency_pos_left == false ){
											// right space
											$final_currency_symbol = ' '.$currency_symbol;
										}else{
											$final_currency_symbol = $currency_symbol;
										}

										$cmin_price = ( isset( $_GET['min_price'] ) && !empty( $_GET['min_price'] ) ) ? $_GET['min_price'] : '';
										$cmax_price = ( isset( $_GET['max_price'] ) && !empty( $_GET['max_price'] ) ) ? $_GET['max_price'] : '';

										$current_price = [ $cmin_price, $cmax_price ];

										$psl_placeholder = '';
										if( empty( $cmin_price ) ){
											$psl_placeholder = 'data-placeholder="'.esc_attr( !empty( $filter_item['filterPlaceholder'] ) ? $filter_item['filterPlaceholder'] : '' ).'"';
										}

										$price_range_list = $settings['priceRangeList'];
										
										if( isset( $price_range_list ) ):
											?>
											<div class="shopxpert-filter-single-item shopxpert-states-input-auto shopxpert-repeater-item-<?php echo esc_attr($itemUniqId); ?>">
												<?php echo $filter_label; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
												<select id="shopxpert-field-for-<?php echo esc_attr($itemUniqId); ?>" class="shopxpert-onchange-single-item shopxpert-price-filter shopxpert-single-select-<?php echo esc_attr($id); ?>" data-minimum-results-for-search="Infinity" <?php echo $psl_placeholder; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> >
													<?php
														if( !empty( $filter_item['filterPlaceholder'] ) && empty( $cmin_price ) && !$block['is_editor']){echo '<option></option>';}

														foreach ( $price_range_list as $key => $price_range ) {

															$individual = [$price_range['minPrice'], $price_range['maxPrice'] ];
															$diff = array_diff( $individual, $current_price );

															$pselected = 0;
															if( count( $diff ) == 0 ) {
																$pselected = 1;
															}

															if( $currency_pos_left ){
																$generate_price = sprintf('%s%s %s %s%s',$final_currency_symbol,$price_range['minPrice'], $price_range['priceSeparator'],$final_currency_symbol,$price_range['maxPrice'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
															}else{
																$generate_price = sprintf('%s%s %s %s%s',$price_range['minPrice'], $final_currency_symbol, $price_range['priceSeparator'],$price_range['maxPrice'], $final_currency_symbol ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
															}

															echo sprintf("<option value='%s' data-min_price='&min_price=%s' data-max_price='&max_price=%s' %s>%s</option>", $key, $price_range['minPrice'], $price_range['maxPrice'], selected( $pselected, 1, false ), $generate_price ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
														}

													?>
												</select>
											</div>
											<?php
										endif;

									}else{
										$terms = get_terms( $filter_item['filterType'] );
										
										if ( !empty( $terms ) && !is_wp_error( $terms )){

											$taxonomy_data = get_taxonomy( $filter_item['filterType'] );

											$filter_name = $filter_item['filterType'];
											$str = substr( $filter_item['filterType'], 0, 3 );
											if( 'pa_' === $str ){
												$filter_name = 'filter_' . wc_attribute_taxonomy_slug( $filter_item['filterType'] );
											}

											if( $filter_name === 'product_cat' || $filter_name === 'product_tag' ){
												$filter_name = 'shopxpert_'.$filter_name;
											}

											$selected_taxonomies = ( isset( $_GET[$filter_name] ) && !empty( $_GET[$filter_name] ) ) ? explode( ',', $_GET[$filter_name] ) : array();

											$sl_placeholder = '';
											if( count( $selected_taxonomies ) != 1 ){
												$sl_placeholder = 'data-placeholder="'.esc_attr( !empty( $filter_item['filterPlaceholder'] ) ? $filter_item['filterPlaceholder'] : $taxonomy_data->labels->singular_name ).'"';
											}

											$multiple_select = $block['is_editor'] ? '' : 'multiple="multiple"';

											echo '<div class="shopxpert-filter-single-item shopxpert-states-input-auto shopxpert-repeater-item-'.esc_attr($itemUniqId).'">';
											echo $filter_label; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											echo '<select name="wltaxonomies['.$filter_item['filterType'].'][]" class="shopxpert-onchange-multiple-item shopxpert-multiple-select-'.esc_attr($id).'" '.$sl_placeholder.' '.$multiple_select.'>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

												foreach ( $terms as $term ){
													$link = shopxpert_block_filter_generate_term_link( $filter_item['filterType'], $term, null );

													$selected = 0;
													if( in_array( $term->slug, $selected_taxonomies ) ) {
														$selected = 1;
													}

													echo sprintf('<option value="%1$s" %3$s>%2$s</option>', $link['link'], esc_html($term->name), selected( $selected, 1, false ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
												}

											echo '</select></div>';

										}

									}

									// Individual Style
									if (!empty($filter_item['filterMinWidth'])) {
										$styleIndividual_Item .= ".shopxpert-horizontal-filter-wrap .shopxpert-filter-field-wrap .shopxpert-states-input-auto.shopxpert-repeater-item-$itemUniqId .select2-container{
											min-width: {$filter_item['filterMinWidth']}px;
										}";
									}
									if (!empty($filter_item['filterWidth'])) {
										$styleIndividual_Item .= ".shopxpert-horizontal-filter-wrap .shopxpert-filter-field-wrap .shopxpert-states-input-auto.shopxpert-repeater-item-$itemUniqId .select2-container{
											max-width: {$filter_item['filterWidth']}px;
										}";
									}

								}
							}

						?>
					</div>
					<div class="shopxpert-select-drop shopxpert-single-select-drop-<?php echo esc_attr($id); ?>"></div>
					<div class="shopxpert-select-drop shopxpert-multiple-select-drop-<?php echo esc_attr($id); ?>"></div>
				</div>
			</div>
		</div>
		<!-- Heaer Box Area End -->
	</div>
	<?php 
		if( !empty( $styleIndividual_Item ) ){
			echo '<style type="text/css">'.$styleIndividual_Item.'</style>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	?>
	<script type="text/javascript">
		;jQuery(document).ready(function($) {
			'use strict';

			var id = '<?php echo esc_js($id); ?>',
				isEditorMode = '<?php echo esc_js($block['is_editor']); ?>';

			// Localize Text
			var selectTxt = '<?php echo esc_html__( 'select', 'shopxper' ); ?>',
				ofTxt = '<?php echo esc_html__( 'of', 'shopxper' ); ?>';

			// Filter Toggle
			$('#filter-toggle-'+id).on('click', function(e){
				e.preventDefault()
				$('#filter-item-'+id).slideToggle()
			})


			$('.shopxpert-single-select-'+id).select2({
				dropdownParent: $('.shopxpert-single-select-drop-'+id),
			});
			$('.shopxpert-multiple-select-'+id).select2({
				// closeOnSelect : false,
				allowHtml: true,
				allowClear: true,
				dropdownParent: $('.shopxpert-multiple-select-drop-'+id),
			});

			$('.shopxpert-filter-single-item select').on('change', function (e) {
				var output = $(this).siblings('span.select2').find('ul');
				var total = e.currentTarget.length;
				var count = output.find('li').length - 0;
				if(count >= 3) {
					output.html("<li>"+count+" "+ofTxt+" "+total+" "+selectTxt+"</li>")
				} 
			});

			// Filter product
			var current_url = '<?php echo esc_js($current_url).'?wlfilter=1'; ?>';
			$('.shopxpert-filter-single-item select.shopxpert-onchange-single-item').on('change', function () {
				var sort_key = $(this).val();
				if ( sort_key && ( isEditorMode != true ) ) {
					window.location = current_url + sort_key;
				}
				return false;
			});

			// Price Filter
			$('.shopxpert-filter-single-item select.shopxpert-price-filter').on( 'change', function(){
				var selected = $(this).find('option:selected'),
					min_price = selected.data('min_price'),
					max_price = selected.data('max_price'),
					location  = min_price + max_price;

				if ( location && ( isEditorMode != true ) ) {
					window.location = current_url + location;
				}

			});

			// Texanomies Filter
			var previouslySelected = [];
			$('.shopxpert-filter-single-item select.shopxpert-onchange-multiple-item').on('change', function () {
				// Get newly selected elements
				var currentlySelected = $(this).val();
				if( currentlySelected != null ){

					if( currentlySelected.length == 0 && ( isEditorMode != true ) ){
						window.location = current_url;
					}else{
						var newSelections = currentlySelected.filter(function (element) {
							return previouslySelected.indexOf(element) == -1;
						});
						previouslySelected = currentlySelected;
						if (newSelections.length) {
							// If there are multiple new selections, we'll take the last in the list
							let lastSelected = newSelections.reverse()[0];
							if ( lastSelected && ( isEditorMode != true ) ) {
								window.location = lastSelected;
							}
						}
					}
					
				}else{
					if(isEditorMode != true){
						window.location = current_url;
					}
				}
				return false;
			});


		});
	</script>
<?php
echo '</div>';