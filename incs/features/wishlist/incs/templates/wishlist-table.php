<div class="wishlist-table-content">
    <table class="wishlist_table">
        <thead>
            <?php
                // Backwards-compatible: ensure $wishlist instance exists in template
                if ( ! isset( $wishlist ) || ! is_object( $wishlist ) ) {
                    $wishlist = \WishList\Frontend\Manage_Wishlist::instance();
                }

                $fields = ! empty( $fields ) && is_array( $fields ) ? $fields : array();

                $cell_count = 1;
                if( !empty( $fields ) ){
                    $cell_count = count( $fields );
                    echo '<tr>';
                        foreach ( $fields as $field_id => $field ){
                            $name = $wishlist->field_name( $field_id );
                            if( array_key_exists( $field_id, $heading_txt ) && !empty( $heading_txt[$field_id] ) ){
                                $name = $wishlist->field_name( $heading_txt[$field_id], true );
                            }
                            echo '<th>'.esc_html($name).'</th>';
                        }
                    echo '</tr>';
                }
            ?>
        </thead>
        <tbody>
            <?php 
                if( !empty( $products ) ):
                    foreach ( $products as $product_id => $product ):
            ?>
                    <tr>
                        <?php foreach ( $fields as $field_id => $field ) : 
                            $data_label = $wishlist->field_name( $field_id );
                            if( array_key_exists( $field_id, $heading_txt ) && !empty( $heading_txt[$field_id] ) ){
                                $data_label = $wishlist->field_name( $heading_txt[$field_id], true );
                            }
                        ?>
                            <td class="wishlist-product-<?php echo esc_attr( $field_id ); ?>" data-label="<?php echo esc_attr( $data_label ); ?>">
                                <?php $wishlist->display_field( $field_id, $product ); ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>

            <?php endforeach; ?>
                <tr class="wishlist-empty-tr" style="display: none;">
                    <td class="wishlist-emplty-text" colspan="<?php echo esc_attr( $cell_count ); ?>">
                        <?php if( !empty( $empty_text ) ){ echo wp_kses_post( $empty_text ); } ?>
                    </td>
                </tr>
            <?php else: ?>
                <tr>
                    <td class="wishlist-emplty-text" colspan="<?php echo esc_attr( $cell_count ); ?>">
                        <?php if( !empty( $empty_text ) ){ echo wp_kses_post( $empty_text ); } ?>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php $wishlist->pagination(); ?>
    <?php $wishlist->social_share(); ?>

    <div class="wishlist-table-content-loader"></div>
</div>