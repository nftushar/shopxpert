<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'smartshopblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass );

!empty( $settings['className'] ) ? $areaClasses[] = esc_attr( $settings['className'] ) : '';

$id = $settings['blockUniqId'];
$currency_symbol = get_woocommerce_currency_symbol();

$filter_type = $settings['filterType'];

$list_icon = !empty( $settings['listIcon'] ) ? '<i class="'.$settings['listIcon'].'"></i>' : '';


global $wp;
if ( '' == get_option('permalink_structure' ) ) {
	$current_url = remove_query_arg(array('page', 'paged'), add_query_arg($wp->query_string, '', home_url($wp->request)));
} else {
	$current_url = preg_replace('%\/page/[0-9]+%', '', home_url(trailingslashit($wp->request)));
}

echo '<div class="'.esc_attr(implode(' ', $areaClasses )).'">';
	?>
	<div class="smartshop-filter-wrap" style="<?php if( 'price_by' === $filter_type ){ echo 'overflow: visible;'; } ?>">

		<?php

		if( !empty( $filter_type ) ):

			echo !empty( $settings['filterAreaTitle'] ) ? '<h2 class="wl_filter_title">'.esc_html($settings['filterAreaTitle']).'</h2>' : '';

			if( 'search_form' === $filter_type ):

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
				<form class="wl_product_search_form" role="search" method="get" action="<?php echo esc_url( $form_action ); ?>">
					<input type="search" placeholder="<?php echo esc_attr_x( 'Search Products&hellip;', 'placeholder', 'smartshop' ); ?>" value="<?php echo esc_attr( $search_value ); ?>" name="q" title="<?php echo esc_attr_x( 'Search for:', 'label', 'smartshop' ); ?>" />
					<button type="submit" aria-label="<?php echo esc_attr__( 'Search', 'smartshop' );?>"><i class="fa fa-search"></i></button>
				</form>

			<?php elseif( 'price_by' === $filter_type ):

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

				$step = 1;
				// Find min and max price in current result set.
				$prices    = function_exists('smartshop_minmax_price_limit') ? smartshop_minmax_price_limit() : array('min' => 10,'max' => 20);

				$min_price = $prices['min'];
				$max_price = $prices['max'];

				// Check to see if we should add taxes to the prices if store are excl tax but display incl.
				$tax_display_mode = get_option( 'woocommerce_tax_display_shop' );

				if ( wc_tax_enabled() && ! wc_prices_include_tax() && 'incl' === $tax_display_mode ) {
					$tax_class = apply_filters( 'smartshop_price_filter_tax_class', '' ); // Uses standard tax class.
					$tax_rates = \WC_Tax::get_rates( $tax_class );

					if ( $tax_rates ) {
						$min_price += \WC_Tax::get_tax_total( \WC_Tax::calc_exclusive_tax( $min_price, $tax_rates ) );
						$max_price += \WC_Tax::get_tax_total( \WC_Tax::calc_exclusive_tax( $max_price, $tax_rates ) );
					}
				}

				if ( $min_price === $max_price ){
					$max_price = 100;
				}

				$min_price = apply_filters( 'smartshop_price_filter_min_amount', floor( $min_price / $step ) * $step );
				$max_price = apply_filters( 'smartshop_price_filter_max_amount', ceil( $max_price / $step ) * $step );

				$current_min_price = isset( $_GET['min_price'] ) ? floor( floatval( wp_unslash( $_GET['min_price'] ) ) / $step ) * $step : $min_price; // WPCS: input var ok, CSRF ok.
				$current_max_price = isset( $_GET['max_price'] ) ? ceil( floatval( wp_unslash( $_GET['max_price'] ) ) / $step ) * $step : $max_price; // WPCS: input var ok, CSRF ok.

			?>

			<div class="wl_price_filter">
				<form method="get" action="<?php echo esc_url( $current_url ); ?>">
					<div class="smartshop_slider_range" style="display: none;"></div>
					<input type="hidden" name="wlfilter" value="1">
					<input type="text" id="min_price-<?php echo esc_attr($id); ?>" name="min_price" value="<?php echo esc_attr( $current_min_price ); ?>" data-min="<?php echo esc_attr( $min_price ); ?>" placeholder="<?php echo esc_attr__( 'Min price', 'smartshop' ); ?>" />
					<input type="text" id="max_price-<?php echo esc_attr($id); ?>" name="max_price" value="<?php echo esc_attr( $current_max_price ); ?>" data-max="<?php echo esc_attr( $max_price ); ?>" placeholder="<?php echo esc_attr__( 'Max price', 'smartshop' ); ?>" />
					<div class="wl_button_price">
						<button type="submit" aria-label="<?php echo esc_attr__( 'Filter','smartshop' );?>"><?php echo esc_html__( 'Filter', 'smartshop' ); ?></button>
						<div class="smartshop_price_label" style="display: none;">
							<?php echo esc_html__( 'Price:', 'smartshop' ); ?>
							<span id="from-<?php echo esc_attr($id); ?>"></span> &mdash; <span id="to-<?php echo esc_attr($id); ?>"></span>
						</div>
					</div>
					<?php echo wc_query_string_form_fields( null, array( 'min_price', 'max_price', 'paged' ), '', true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</form>
			</div>
			<script type="text/javascript">
				;jQuery(document).ready(function($) {
					'use strict';

					var id = '<?php echo esc_js($id); ?>';

					$( 'input#min_price-'+id+', input#max_price-'+id ).hide();
					$( '.smartshop_slider_range, .smartshop_price_label' ).show();

					var min_price = parseInt( '<?php echo esc_js($min_price); ?>' ),
						max_price = parseInt( '<?php echo esc_js($max_price); ?>' ),
						current_min_price = parseInt( '<?php echo esc_js($current_min_price); ?>' ),
						current_max_price = parseInt( '<?php echo esc_js($current_max_price); ?>' ),
						currency_pos_left = '<?php echo esc_js($currency_pos_left); ?>',
						currency_symbol = '<?php echo esc_js($final_currency_symbol); ?>';

					$( ".smartshop_slider_range" ).slider({
						range: true,
						min: min_price,
						max: max_price,
						values: [ current_min_price, current_max_price ],
						slide: function( event, ui ) {
							$( 'input#min_price-'+id ).val( ui.values[0] );
							$( 'input#max_price-'+id ).val( ui.values[1] );
							( currency_pos_left ) ? $( ".smartshop_price_label span#from-"+id ).html( currency_symbol + ui.values[0] ) : $( ".smartshop_price_label span#from-"+id ).html(  ui.values[0] + currency_symbol );
							( currency_pos_left ) ? $( ".smartshop_price_label span#to-"+id ).html( currency_symbol + ui.values[1] ) : $( ".smartshop_price_label span#to-"+id ).html( ui.values[1] + currency_symbol );
						},

					});

					$( "#min_price-"+id ).val(  $( ".smartshop_slider_range" ).slider( "values", 0 ) );
					$( "#max_price-"+id ).val(  $( ".smartshop_slider_range" ).slider( "values", 1 ) );

					if( currency_pos_left ){
						$( ".smartshop_price_label span#from-"+id ).html(  currency_symbol + $( ".smartshop_slider_range" ).slider( "values", 0 ) );
						$( ".smartshop_price_label span#to-"+id ).html(  currency_symbol + $( ".smartshop_slider_range" ).slider( "values", 1 ) );
					}else{
						$( ".smartshop_price_label span#from-"+id ).html( $( ".smartshop_slider_range" ).slider( "values", 0 ) + currency_symbol );
						$( ".smartshop_price_label span#to-"+id ).html( $( ".smartshop_slider_range" ).slider( "values", 1 ) + currency_symbol );
					}

				});
			</script>

			<?php elseif( 'sort_by' === $filter_type ): 
				$wlsort = ( isset( $_GET['wlsort'] ) && !empty( $_GET['wlsort'] ) ) ? $_GET['wlsort'] : '';
				$sort_by_none_lavel = isset( $settings['sortByNoneLavel'] ) ? $settings['sortByNoneLavel'] : 'None';
				$sort_by_asc_lavel = isset( $settings['sortByAscLavel'] ) ? $settings['sortByAscLavel'] : 'ASC';
				$sort_by_desc_lavel = isset( $settings['sortByDescLavel'] ) ? $settings['sortByDescLavel'] : 'DESC';
			?>
				<div class="wl_sort_by_filter">
					<select name="wl_sort">
						<option value="&wlsort=none"><?php echo esc_html__( $sort_by_none_lavel, 'smartshop' ); ?></option>
						<option value="&wlsort=ASC" <?php selected( 'ASC', $wlsort, true ); ?> ><?php echo esc_html__( $sort_by_asc_lavel, 'smartshop' ); ?></option>
						<option value="&wlsort=DESC" <?php selected( 'DESC', $wlsort, true ); ?> ><?php echo esc_html__( $sort_by_desc_lavel, 'smartshop' ); ?></option>
					</select>
				</div>
			<?php elseif( 'order_by' === $filter_type ):
				$wlorder_by = ( isset( $_GET['wlorder_by'] ) && !empty( $_GET['wlorder_by'] ) ) ? $_GET['wlorder_by'] : '';
			?>
				<div class="wl_order_by_filter">
					<select name="wl_order_by_sort">
						<?php
							foreach ( smartshop_order_by_opts() as $key => $opt_data ) {
								echo '<option value="&wlorder_by='.esc_attr( $key ).'" '.selected( $key, $wlorder_by, false ).'>'.esc_html__( $opt_data, 'smartshop' ).'</option>';
							}
						?>
					</select>
				</div>

			<?php else:

				if( true === $settings['showHierarchical'] ){
					$terms = get_terms( ['taxonomy' => $filter_type, 'parent' => 0, 'child_of' => 0] );

					if ( !empty( $terms ) && !is_wp_error( $terms )){
						echo '<ul>';
							foreach ( $terms as $term ){
								$link = smartshop_block_filter_generate_term_link( $filter_type, $term, $current_url );
								echo '<li class="'.esc_attr($link['class']).'">';
									echo sprintf('%1$s<a href="%2$s">%3$s <span>(%4$s)</span></a>', $list_icon, esc_url($link['link']), $term->name, $term->count ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

									$loterms = get_terms( [ 'taxonomy' => $filter_type, 'parent' => $term->term_id ] );
									if( !empty( $loterms ) && !is_wp_error( $loterms ) ){
										echo '<ul class="wlchildren">';
											foreach( $loterms as $key => $loterm ){
												$clink = smartshop_block_filter_generate_term_link( $filter_type, $loterm, $current_url );
												echo sprintf('<li class="%5$s">%1$s<a href="%2$s">%3$s <span>(%4$s)</span></a></li>', $list_icon, $clink['link'], $loterm->name, $loterm->count, $clink['class'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											}
										echo '</ul>';
									}
								echo '</li>';
							}
						echo '</ul>';
					}
					
				}else{
					$terms = get_terms( ['taxonomy' => $filter_type ] );
					if ( !empty( $terms ) && !is_wp_error( $terms ) ){
						echo '<ul>';
							foreach ( $terms as $term ){
								$link = smartshop_block_filter_generate_term_link( $filter_type, $term, $current_url );
								echo sprintf('<li class="%5$s">%4$s<a href="%1$s">%2$s <span>(%3$s)</span></a></li>', esc_url($link['link']), $term->name, $term->count, $list_icon, esc_attr($link['class']) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							}
						echo '</ul>';
					}
				}

			?>
			<?php endif;?>

		<?php else: echo '<p>'.esc_html__( 'Please Select Filter Type', 'smartshop' ).'</p>'; ?>
			
		<?php endif; ?>

		<?php if( 'sort_by' === $filter_type || 'order_by' === $filter_type ):?>
			<script type="text/javascript">
				;jQuery(document).ready(function($) {
					'use strict';
					var current_url = '<?php echo esc_js($current_url).'?wlfilter=1'; ?>',
						isEditorMode = '<?php echo esc_js($block['is_editor']); ?>';
					$('.wl_order_by_filter select,.wl_sort_by_filter select').on('change', function () {
						var sort_key = $(this).val();
						if ( sort_key && ( isEditorMode != true ) ) {
							window.location = current_url + sort_key;
						}
						return false;
					});
				});
			</script>
		<?php endif; ?>

	</div>
	<?php
echo '</div>';