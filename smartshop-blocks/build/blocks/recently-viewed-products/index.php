<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'smartshopblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'smartshop_block_recently_viewed_product' );
!empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';
!empty( $settings['className'] ) ? $areaClasses[] = esc_attr( $settings['className'] ) : '';

!empty( $settings['columns']['desktop'] ) ? $areaClasses[] = 'smartshop-grid-columns-'.$settings['columns']['desktop'] : 'smartshop-grid-columns-4';
!empty( $settings['columns']['laptop'] ) ? $areaClasses[] = 'smartshop-grid-columns-laptop-'.$settings['columns']['laptop'] : 'smartshop-grid-columns-laptop-3';
!empty( $settings['columns']['tablet'] ) ? $areaClasses[] = 'smartshop-grid-columns-tablet-'.$settings['columns']['tablet'] : 'smartshop-grid-columns-tablet-2';
!empty( $settings['columns']['mobile'] ) ? $areaClasses[] = 'smartshop-grid-columns-mobile-'.$settings['columns']['mobile'] : 'smartshop-grid-columns-mobile-1';


$title_html_tag = smartshop_validate_html_tag( $settings['titleTag'] );

$products_list = smartshop_get_track_user_data();

if( $block['is_editor'] && empty( $products_list ) ){
    echo '<div class="elementor-panel" style="margin-bottom:10px;"><div class="elementor-panel-alert elementor-panel-alert-warning">'. esc_html__( 'You haven\'t viewed at any of the products yet. Below are demo product for the editing mode.', 'smartshop' ) . '</div></div>';
}else{
    if ( empty( $products_list ) ) {
        if( $settings['showEmptyMessage'] ){
            echo '<div class="smartshop-no-view-product">'. esc_html__( trim( $settings['emptyMessage'] ) ) .'</div>';
        }
        return '';
    }
}

echo '<div class="'.esc_attr(implode(' ', $areaClasses )).'">';

    $products_list_value = array_values( $products_list );

    if( $settings['order'] == 'DESC' ){
        $products_list_value = array_reverse( $products_list_value );
    }

    $args = array(
        'post_type'            => 'product',
        'ignore_sticky_posts'  => 1,
        'no_found_rows'        => 1,
        'posts_per_page'       => $settings['perPage'],
        'orderby'              => 'post__in',
        'post__in'             => isset( $products_list_value ) ? $products_list_value : [],
    );
    $products = new \WP_Query( $args );

    if ( $products->have_posts() ) {
        echo '<div class="smartshop-grid '.( $settings['noGutter'] ? 'smartshop-no-gutters' : '' ).'">';

        while( $products->have_posts() ): $products->the_post();
            ?>
                <div class="smartshop-grid-column">
                    <div class="smartshop-recently-viewed-product">
                        <div class="smartshop-recently-view-image">
                            <?php
                                if( class_exists('WooCommerce') && $settings['showBadge'] == true ){ 
                                    smartshop_custom_product_badge(); 
                                    smartshop_sale_flash();
                                }
                            ?>
                            <a href="<?php the_permalink();?>"> 
                                <?php woocommerce_template_loop_product_thumbnail(); ?> 
                            </a>
                        </div>
                        
                        <?php if( $settings['showTitle'] == true || $settings['showPrice'] == true || $settings['showAddToCart'] == true ): ?>
                            <div class="smartshop-recently-view-content">
                                <?php
                                    if( $settings['showTitle'] == true ){
                                        echo sprintf( "<%s class='smartshop-recently-view-title'><a href='%s'>%s</a></%s>", $title_html_tag, esc_url(get_the_permalink()), get_the_title(), $title_html_tag ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                    }
                                    if( $settings['showPrice'] == true ){
                                        echo '<div class="smartshop-recently-view-price">';
                                            woocommerce_template_loop_price();
                                        echo '</div>';
                                    }
                                    if( $settings['showAddToCart'] == true ){
                                        woocommerce_template_loop_add_to_cart();
                                    }
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php
        endwhile;
        echo '</div>';
    }

echo '</div>';