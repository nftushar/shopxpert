<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$settings = $attributes;

$is_editor = ( isset( $_GET['is_editor_mode'] ) && $_GET['is_editor_mode'] == 'yes' ) ? true : false;

if( $is_editor ){
    \WC()->frontend_includes();
    if ( is_null( \WC()->cart ) ) {
        \WC()->session = new \WC_Session_Handler();
        \WC()->session->init();   
        \WC()->cart     = new \WC_Cart();
        \WC()->customer = new \WC_Customer(get_current_user_id(), true);
    }
    \ShopXpertBlocks\Sample_Data::instance()->add_product_for_empty_cart();
}

$uniqClass 	 = 'shopxpertblock-'.$settings['blockUniqId'];
$areaClasses = [ $uniqClass, 'shopxpert_block_side_mini_cart' ];
!empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';
!empty( $settings['className'] ) ? $areaClasses[] = esc_attr( $settings['className'] ) : '';


echo '<div class="'.esc_attr( implode(' ', $areaClasses ) ).'">';

    if( 'header' === $settings['content_type'] ){
        $title = !empty( $settings['header_title'] ) ? sprintf( "<h2>%s</h2>", $settings['header_title'] ) : '';
        $icon = !empty( $settings['cross_icon'] ) ? '<i class="'.$settings['cross_icon'].'"></i>' : '&#10006;';
        $close_icon = sprintf( "<span class='shopxpert_mini_cart_close'>%s</span>", $icon );
        echo sprintf('<div class="shopxpert_mini_cart_header">%1$s %2$s</div>',$title, $close_icon );
    }elseif( 'footer' === $settings['content_type'] ){

        $subtotal_txt = !empty( $settings['footer_sub_total'] ) ? $settings['footer_sub_total'] : '';
        $viewcart_btn = !empty( $settings['cart_btn_txt'] ) ? $settings['cart_btn_txt'] : '';
        $checkout_btn = !empty( $settings['checkout_btn_txt'] ) ? $settings['checkout_btn_txt'] : '';

        ?>
            <div class="shopxpert_mini_cart_footer">
                <?php if ( !empty( \WC()->cart->cart_contents ) ):?>
                    <span class="shopxpert_sub_total">
                        <span><?php esc_html_e( $subtotal_txt, 'shopxpert' ); ?></span>
                        <?php echo \WC()->cart->get_cart_subtotal(); ?>
                    </span>
                <?php endif; ?>
                <div class="shopxpert_button_area">
                    <a href="<?php echo wc_get_cart_url(); ?>" class="button btn shopxpert_cart">
                        <?php esc_html_e( $viewcart_btn, 'shopxpert' ); ?>
                    </a>
                    <a  href="<?php echo wc_get_checkout_url(); ?>" class="button btn shopxpert_checkout">
                        <?php esc_html_e( $checkout_btn, 'shopxpert' ); ?>
                    </a>
                </div>
            </div>
        <?php
    }else{
        $empty_cart_body = !empty( $settings['footer_empty_text'] ) ? $settings['footer_empty_text'] : '';
        ?>
        <div class="shopxpert_mini_cart_content">
            <?php if( empty( \WC()->cart->cart_contents ) ): ?>
                <p class="shopxpert_empty_cart_body"><?php esc_html_e( $empty_cart_body, 'shopxpert' ); ?></p>
            <?php else:?>
                <ul>
                <?php
                    foreach ( \WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                    $_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

                    $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                    if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {

                        $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );

                        $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

                        $product_name =  apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key );
                        
                        $product_price = apply_filters( 'woocommerce_cart_item_price', \WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );

                        $product_subtotal = apply_filters( 'woocommerce_cart_item_subtotal', \WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
                ?>
                    <li class="woocommerce-mini-cart-item <?php echo esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key ) ); ?> shopxpert_mini_cart_product-wrap">

                        <?php if ( ! $_product->is_visible() ) : ?>
                        <div class="shopxpert_mini_cart_img">
                            <?php echo str_replace( array( 'http:', 'https:' ), '', $thumbnail ); ?>
                            <?php
                                echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
                                    '<a href="%s" class="shopxpert_del remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&#10005;</a>',
                                    esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                    __( 'Remove this item', 'shopxpert' ),
                                    esc_attr( $product_id ),
                                    esc_attr( $cart_item_key ),
                                    esc_attr( $_product->get_sku() )
                                ), $cart_item_key );
                            ?>
                        </div>
                        <?php else : ?>
                            <div class="shopxpert_mini_cart_img">
                                <a href="<?php echo esc_url( $product_permalink ); ?>">
                                    <?php echo str_replace( array( 'http:', 'https:' ), '', $thumbnail ); ?>
                                </a>
                                <?php
                                    echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
                                        '<a href="%s" class="shopxpert_del remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&#10005;</a>',
                                        esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                        __( 'Remove this item', 'shopxpert' ),
                                        esc_attr( $product_id ),
                                        esc_attr( $cart_item_key ),
                                        esc_attr( $_product->get_sku() )
                                    ), $cart_item_key );
                                ?>
                            </div>
                        <?php endif; ?>

                        <div class="shopxpert_cart_single_content">
                            <div class="shopxpert_mini_title">
                                <h3><?php echo wp_kses_post($product_name);?></h3>
                                <span><?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); ?></span>
                            </div>
                        </div>
                        
                    </li>
                    <?php } } ?>

                </ul>
            <?php endif;?>
        </div>
        <?php
    }

echo '</div>';