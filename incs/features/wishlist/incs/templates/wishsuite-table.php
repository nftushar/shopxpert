<div class="sxwishlist-table-content">
    <table class="wishsuite_table">
        <thead>
            <?php
                $cell_count = 1;
                if( !empty( $fields ) ){
                    $cell_count = count( $fields );
                    echo '<tr>';
                        foreach ( $fields as $field_id => $field ){
                            $name = $sxwishlist->field_name( $field_id );
                            if( array_key_exists( $field_id, $heading_txt ) && !empty( $heading_txt[$field_id] ) ){
                                $name = $sxwishlist->field_name( $heading_txt[$field_id], true );
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
                            $data_label = $sxwishlist->field_name( $field_id );
                            if( array_key_exists( $field_id, $heading_txt ) && !empty( $heading_txt[$field_id] ) ){
                                $data_label = $sxwishlist->field_name( $heading_txt[$field_id], true );
                            }
                        ?>
                            <td class="sxwishlist-product-<?php echo esc_attr( $field_id ); ?>" data-label="<?php echo esc_attr( $data_label ); ?>">
                                <?php $sxwishlist->display_field( $field_id, $product ); ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>

            <?php endforeach; ?>
                <tr class="sxwishlist-empty-tr" style="display: none;">
                    <td class="sxwishlist-emplty-text" colspan="<?php echo esc_attr( $cell_count ); ?>">
                        <?php if( !empty( $empty_text ) ){ echo wp_kses_post( $empty_text ); } ?>
                    </td>
                </tr>
            <?php else: ?>
                <tr>
                    <td class="sxwishlist-emplty-text" colspan="<?php echo esc_attr( $cell_count ); ?>">
                        <?php if( !empty( $empty_text ) ){ echo wp_kses_post( $empty_text ); } ?>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php $sxwishlist->pagination(); ?>
    <?php $sxwishlist->social_share(); ?>

    <div class="sxwishlist-table-content-loader"></div>
</div>