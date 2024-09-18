<?php
    namespace ShopXpertBlocks;
    // Exit if accessed directly.
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    if( !class_exists('Customize_Button') ){
        class Customize_Button {

            /**
             * Manage WishList and Compare Button
             *
             * @param [type] $settings
             * @param [type] $porduct_type
             * @return void
             */
            public function wishlist_compare_button( $settings, $porduct_type ){
                $plus_icon = !empty( $settings['quantityPlusIcon'] ) ? '<i class="'.esc_attr( $settings['quantityPlusIcon'] ).'"></i>' : '<i class="ion-plus"></i>';
                $minus_icon = !empty( $settings['quantityMinusIcon'] ) ? '<i class="'.esc_attr( $settings['quantityMinusIcon'] ).'"></i>' : '<i class="ion-minus"></i>';
            
                if( 'grouped' != $porduct_type ){
                    add_action( 'woocommerce_before_add_to_cart_quantity', function() use ($settings, $minus_icon) {
                        echo '<div class="wl-quantity-wrap">';
                            if( !empty( $settings['quantityText'] ) ){
                                echo '<span class="label">'.esc_html( $settings['quantityText'] ).'</span>';
                            }
                            echo '<div class="wl-quantity-cal">';
                            echo '<span class="wl-quantity wl-qunatity-minus" >'.$minus_icon.'</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    });
            
                    add_action( 'woocommerce_after_add_to_cart_quantity', function() use ($settings, $plus_icon) {
                        echo '<span class="wl-quantity wl-qunatity-plus" >'.$plus_icon.'</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        echo '</div>';
                        echo '</div>';
                        echo '<div class="wl-cart-wrap '.esc_attr($settings['buttonsPosition']).'">';
                            if( '5' !== $settings['layoutStyle']){
                                if( true === shopxpert_has_wishlist_plugin() && true !== $settings['hideWishlistButton'] ){
                                    echo '<span class="wl-cart-icon wishlist">'.shopxpert_add_to_wishlist_button('<i class="sli sli-heart"></i>','<i class="sli sli-heart"></i>', 'no').'</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                }
                            }
                    } );
                }else{
                    add_action( 'woocommerce_before_add_to_cart_quantity', function() use ($settings, $minus_icon) {
                        echo '<div class="wl-quantity-grouped-cal">';
                            echo '<span class="wl-quantity wl-qunatity-minus" >'.$minus_icon.'</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    });
            
                    add_action( 'woocommerce_after_add_to_cart_quantity', function() use ($settings, $plus_icon) {
                            echo '<span class="wl-quantity wl-qunatity-plus" >'.$plus_icon.'</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        echo '</div>';
                    } );
                    add_action( 'woocommerce_before_add_to_cart_button', function() use ($settings) {
                        echo '<div class="wl-cart-wrap '.esc_attr($settings['buttonsPosition']).'">';
                        if( '5' !== $settings['layoutStyle']){
                            if( true === shopxpert_has_wishlist_plugin() && true !== $settings['hideWishlistButton'] ){
                                echo '<span class="wl-cart-icon wishlist">'.shopxpert_add_to_wishlist_button('<i class="sli sli-heart"></i>','<i class="sli sli-heart"></i>', 'no').'</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            }
                        }
                    });
                }
            
                add_action( 'woocommerce_after_add_to_cart_button', function() use ($settings, $porduct_type) {
                    if( 'simple' == $porduct_type || 'grouped' == $porduct_type || 'variable' == $porduct_type){
                        if( '5' === $settings['layoutStyle']){
                            if( true === shopxpert_has_wishlist_plugin() && true !== $settings['hideWishlistButton'] ){
                                echo '<span class="wl-cart-icon wishlist">'.shopxpert_add_to_wishlist_button('<i class="sli sli-heart"></i>','<i class="sli sli-heart"></i>', 'no').'</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            }
                        }
                        if( function_exists('shopxpert_compare_button') && true === shopxpert_exist_compare_plugin() && true !== $settings['hideCompareButton'] ){
                            echo '<span class="wl-cart-icon compare">';
                                shopxpert_compare_button(
                                    array(
                                        'style'=> 2,
                                        'btn_text'=> '<i class="sli sli-refresh"></i>',
                                        'btn_added_txt'=> '<i class="sli sli-check"></i>'
                                    )
                                );
                            echo '</span>';
                        }
                        echo "</div>";
                    }elseif ('external' == $porduct_type) {
                        if( true === shopxpert_has_wishlist_plugin() && true !== $settings['hideWishlistButton'] ){
                            echo '<span class="wl-cart-icon wishlist">'.shopxpert_add_to_wishlist_button('<i class="sli sli-heart"></i>','<i class="sli sli-heart"></i>', 'no').'</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        }
                        if( function_exists('shopxpert_compare_button') && true === shopxpert_exist_compare_plugin() && true !== $settings['hideCompareButton'] ){
                            echo '<span class="wl-cart-icon compare">';
                                shopxpert_compare_button(
                                    array(
                                        'style'=> 2,
                                        'btn_text'=> '<i class="sli sli-refresh"></i>',
                                        'btn_added_txt'=> '<i class="sli sli-check"></i>'
                                    )
                                );
                            echo '</span>';
                        }
                    }
                    ?>
                    <ul class="wl-wishlist-compare-txt">
                        <?php if( true === shopxpert_has_wishlist_plugin() ): ?>
                            <li>
                                <?php if( true !== $settings['hideWishlistButton'] ): ?>
                                    <?php echo shopxpert_add_to_wishlist_button('<i class="sli sli-heart"></i>','<i class="sli sli-heart"></i>', 'yes'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                <?php endif; ?>
                            </li>
                        <?php endif; ?>
                        <?php if( function_exists('shopxpert_compare_button') && true === shopxpert_exist_compare_plugin() ): ?>
                            <li>
                                <?php if( true !== $settings['hideCompareButton'] ): ?>
                                    <span><i class="sli sli-refresh"></i></span>
                                    <?php shopxpert_compare_button(
                                            array(
                                                'style'=> 2,
                                                'btn_text_type'=> 'text',
                                                'btn_text'=> esc_html__('Compare','shopxper'),
                                                'btn_added_txt'=> esc_html__('Already Compared','shopxper')
                                            )
                                        ); 
                                    ?>
                                <?php endif; ?>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <?php
                } );

            }

        }
    }

?>