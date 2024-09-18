<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

    $rows = $settings['rows'];
?>
<div class="product-item <?php if ( $rows > 1 && ( $loopitem % $rows != 0 ) ){ echo 'mb-30 ';} if( $settings['style'] == 3){ echo 'product_style_three'; }?> ">

    <div class="product-inner">
        <div class="image-wrap">
            <a href="<?php the_permalink();?>" class="image">
                <?php 
                    woocommerce_show_product_loop_sale_flash();
                    woocommerce_template_loop_product_thumbnail();
                ?>
            </a>
            <?php
                if( $settings['style'] == 1){
                    if( true === shopxpert_has_wishlist_plugin() ){
                        echo shopxpert_add_to_wishlist_button(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    }
                }
            ?>
            <?php if( $settings['style'] == 3):?>
                <div class="product_information_area">

                    <?php
                        global $product; 
                        $attributes = $product->get_attributes();
                        if($attributes):
                            echo '<div class="product_attribute">';
                            foreach ( $attributes as $attribute ) :
                                $name = $attribute->get_name();
                            ?>
                            <ul>
                                <?php
                                    echo '<li class="attribute_label">'.wc_attribute_label( $attribute->get_name() ).esc_html__(':','shopxper').'</li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                    if ( $attribute->is_taxonomy() ) {
                                        global $wc_product_attributes;
                                        $product_terms = wc_get_product_terms( $product->get_id(), $name, array( 'fields' => 'all' ) );
                                        foreach ( $product_terms as $product_term ) {
                                            $product_term_name = $product_term->name;
                                            $link = get_term_link( $product_term->term_id, $name );
                                            $color = get_term_meta( $product_term->term_id, 'color', true );
                                            if ( ! empty ( $wc_product_attributes[ $name ]->attribute_public ) ) {
                                                echo '<li><a href="' . esc_url( $link  ) . '" rel="tag">' . esc_html($product_term_name) . '</a></li>';
                                            } else {
                                                if(!empty($color)){
                                                    echo '<li class="color_attribute" style="background-color: '.esc_attr($color).';">&nbsp;</li>';
                                                }else{
                                                    echo '<li>' . esc_html($product_term_name) . '</li>';
                                                }
                                                
                                            }
                                        }
                                    }
                                ?>
                            </ul>
                    <?php endforeach; echo '</div>'; endif;?>

                    <div class="actions style_two">
                        <?php
                            woocommerce_template_loop_add_to_cart();
                            if( true === shopxpert_has_wishlist_plugin() ){
                                echo shopxpert_add_to_wishlist_button(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            }
                        ?>
                    </div>

                    <div class="content">
                        <h4 class="title"><a href="<?php the_permalink();?>"><?php echo get_the_title(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a></h4>
                        <?php woocommerce_template_loop_price();?>
                    </div>

                </div>

            <?php else:?>
                <div class="actions <?php if( $settings['style'] == 2){ echo 'style_two'; }?>">
                    <?php
                        if( $settings['style'] == 2){
                            woocommerce_template_loop_add_to_cart();
                            if( true === shopxpert_has_wishlist_plugin() ){
                                echo shopxpert_add_to_wishlist_button(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            }
                        }else{
                            woocommerce_template_loop_add_to_cart(); 

                            if( function_exists('shopxpert_compare_button') && true === shopxpert_exist_compare_plugin() ){
                                shopxpert_compare_button();
                            }

                        }
                    ?>
                </div>
            <?php endif;?>

            
        </div>
        
        <div class="content">
            <h4 class="title"><a href="<?php the_permalink();?>"><?php echo get_the_title(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a></h4>
            <?php woocommerce_template_loop_price();?>
        </div>
    </div>

</div>