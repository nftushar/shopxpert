<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

    $uniqClass   = 'shopxpertblock-'.$settings['blockUniqId'];
    $areaClasses = array( $uniqClass, );
    $rowClasses  = $settings['sliderOn'] === true ? array( 'shopxpert-grid-slider' ) : array( 'shopxpert-grid' );

    !empty( $settings['className'] ) ? $areaClasses[] = esc_attr( $settings['className'] ) : '';
    !empty( $settings['columns']['desktop'] ) ? $areaClasses[] = 'shopxpert-grid-columns-'.$settings['columns']['desktop'] : 'shopxpert-grid-columns-4';
    !empty( $settings['columns']['laptop'] ) ? $areaClasses[] = 'shopxpert-grid-columns-laptop-'.$settings['columns']['laptop'] : 'shopxpert-grid-columns-laptop-3';
    !empty( $settings['columns']['tablet'] ) ? $areaClasses[] = 'shopxpert-grid-columns-tablet-'.$settings['columns']['tablet'] : 'shopxpert-grid-columns-tablet-2';
    !empty( $settings['columns']['mobile'] ) ? $areaClasses[] = 'shopxpert-grid-columns-mobile-'.$settings['columns']['mobile'] : 'shopxpert-grid-columns-mobile-1';

    !empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';

    $settings['noGutter'] === true ? $rowClasses[] = 'shopxpert-no-gutters' : '';

    $display_type = $settings['displayType'];
    $order = ! empty( $settings['order'] ) ? $settings['order'] : '';

    $layout  = $settings['style'];
    $column = !empty( $settings['columns']['desktop'] ) ? $settings['columns']['desktop'] : '4';

    $collumval = 'shopxpert-grid-column';

    $catargs = array(
        'orderby'    => 'name',
        'order'      => $order,
        'hide_empty' => true,
    );

    if( $display_type == 'singleCat' ){
        $product_category = $settings['productCategory'];
        $catargs['slug'] = $product_category;
    }
    elseif( $display_type == 'multipleCat' ){
        $product_categories = !empty( $settings['productCategories'] ) ? $settings['productCategories'] : array();
        if( is_array( $product_categories ) && count( $product_categories ) > 0 ){
            $catargs['slug'] = $product_categories;
        }
    }else{
        $catargs['slug'] = '';
    }

    if( $display_type == 'allCat' ){
        $catargs['number'] = $settings['displayLimit'];
    }else{
        $catargs['number'] = false;
    }

    $prod_categories = shopxpertBlocks_taxnomy_data( $catargs['slug'], $catargs['number'], $catargs['order'] );

    $image_size = $settings['imageSize'] ? $settings['imageSize'] : 'full';

    // Slider Options
    $slider_settings = array();
    $sliderOptions = $direction = '';
    if( $settings['sliderOn'] === true ){

        $rowClasses[] = 'product-slider';

        $direction = $settings['slIsrtl'] ? 'dir=rtl' : 'dir=ltr';
        $slider_settings = [
            'arrows' => (true === $settings['slarrows']),
            'dots' => (true === $settings['sldots']),
            'autoplay' => (true === $settings['slautolay']),
            'autoplay_speed' => absint($settings['slautoplaySpeed']),
            'animation_speed' => absint($settings['slanimationSpeed']),
            'pause_on_hover' => ('yes' === $settings['slpauseOnHover']),
            'rtl' => ( true === $settings['slIsrtl'] ),
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
        $sliderOptions = 'data-settings='.wp_json_encode( $slider_settings );
    }else{
        $sliderOptions = '';
        $direction = '';
    }

    $counter = $bgc = 0;

?>
<div class="<?php echo esc_attr(implode(' ', $areaClasses )); ?>">
    <div class="<?php echo esc_attr(implode(' ', $rowClasses )); ?>" <?php echo $sliderOptions; ?> <?php echo esc_attr( $direction ); ?>>
        <?php
            $topSpace = '';
            foreach ( $prod_categories as $key => $prod_cat ):
                $bgc++;
                $counter++;

                $cat_thumb_id = $prod_cat['thumbnail_id'];
                $thumbnails = $cat_thumb_id ? wp_get_attachment_image( $cat_thumb_id, $image_size ) : '';

                ?>
                <div class="<?php echo esc_attr( $collumval ); echo esc_attr( $topSpace ); ?>">

                    <?php if( '1' === $layout ): ?>
                        <div class="ht-category-wrap">
                            <?php if( !empty( $thumbnails ) ):?>
                            <div class="ht-category-image ht-category-image-zoom">
                                <a class="ht-category-border" href="<?php echo esc_url( $prod_cat['link'] ); ?>">
                                    <?php echo $thumbnails; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                </a>
                            </div>
                            <?php endif; ?>

                            <div class="ht-category-content">
                                <h3><a href="<?php echo esc_url( $prod_cat['link'] ); ?>"><?php echo esc_html__( $prod_cat['name'], 'shopxper' ); ?></a></h3>
                                <?php 
                                    if( $settings['showCount'] === true ){
                                        echo '<span>'.esc_html__( $prod_cat['count'], 'shopxper' ).'</span>';
                                    }
                                ?>
                            </div>
                        </div>

                    <?php elseif( '2' === $layout ):?>
                        <div class="ht-category-wrap-2">
                            <div class="ht-category-content-2 <?php echo ( $settings['titleAfterBorder'] === true ? "" : esc_attr("hide-title-after") ); ?>">
                                <h3><a href="<?php echo esc_url( $prod_cat['link'] ); ?>"><?php echo esc_html__( $prod_cat['name'], 'shopxper' ); ?></a></h3>
                            </div>
                            <?php if( !empty( $thumbnails ) ):?>
                            <div class="ht-category-image-2">
                                <a href="<?php echo esc_url( $prod_cat['link'] ); ?>">
                                    <?php echo $thumbnails; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>

                    <?php elseif( '3' === $layout ):?>
                        <div class="ht-category-wrap">
                            <?php if( !empty( $thumbnails ) ): ?>
                            <div class="ht-category-image ht-category-image-zoom">
                                <a class="ht-category-border-2" href="<?php echo esc_url( $prod_cat['link'] ); ?>">
                                    <?php echo $thumbnails; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                </a>
                            </div>
                            <?php else: ?>
                                <div class="ht-category-image ht-category-image-zoom">
                                    <a class="ht-category-border-2" href="<?php echo esc_url( $prod_cat['link'] ); ?>">
                                        <img src="<?php echo esc_url( $prod_cat['placeholderImg'] ) ?>" alt="<?php echo esc_attr( $prod_cat['name'] );?>" />
                                    </a>
                                </div>
                            <?php endif; ?>
                            <div class="ht-category-content-3 ht-category-content-3-bg<?php echo esc_attr($bgc); ?>">
                                <h3><a href="<?php echo esc_url( $prod_cat['link'] ); ?>"><?php echo esc_html__( $prod_cat['name'], 'shopxper' ); ?></a></h3>
                            </div>
                        </div>

                    <?php elseif( '4' === $layout ):?>
                        <div class="ht-category-wrap">
                            <?php if( !empty( $thumbnails ) ):?>
                            <div class="ht-category-image ht-category-image-zoom">
                                <a href="<?php echo esc_url( $prod_cat['link'] ); ?>">
                                    <?php echo $thumbnails; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                </a>
                            </div>
                            <?php endif; ?>
                            <div class="ht-category-content-4">
                                <h3>
                                    <a href="<?php echo esc_url( $prod_cat['link'] ); ?>"><?php echo esc_html__( $prod_cat['name'], 'shopxper' ); ?></a>
                                    <?php 
                                        if( $settings['showCount'] === true ){
                                            echo '<span>('.esc_html__( $prod_cat['count'], 'shopxper' ).')</span>';
                                        }
                                    ?>
                                </h3>
                            </div>
                        </div>

                    <?php else:?>
                        <div class="ht-category-wrap">
                            <?php if( !empty( $thumbnails ) ):?>
                            <div class="ht-category-image-3 ht-category-image-zoom">
                                <a href="<?php echo esc_url( $prod_cat['link'] ); ?>">
                                    <?php echo $thumbnails; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                </a>
                            </div>
                            <?php endif; ?>
                            <div class="ht-category-content-5">
                                <h3><a href="<?php echo esc_url( $prod_cat['link'] ); ?>"><?php echo esc_html__( $prod_cat['name'], 'shopxper' ); ?></a></h3>
                            </div>
                        </div>

                    <?php endif; ?>

                </div>
                <?php
                if( $bgc == 4 ){ $bgc = 0; }
                if( $counter >= $column ){
                    $topSpace = ' shopxpert_margin_top';
                }
            endforeach;
        ?>
    </div>
</div>