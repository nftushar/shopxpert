<?php
/**
 * [WishList_get_post_list]
 * @param  string $post_type
 * @return [array]
 */


 use function  Shopxpert\incs\shopxpert_get_option;


function WishList_get_post_list( $post_type = 'page' ){
    $options = array();
    $options['0'] = __('Select','shopxpert');
    $perpage = -1;
    $all_post = array( 'posts_per_page' => $perpage, 'post_type'=> $post_type );
    $post_terms = get_posts( $all_post );
    if ( ! empty( $post_terms ) && ! is_wp_error( $post_terms ) ){
        foreach ( $post_terms as $term ) {
            $options[ $term->ID ] = $term->post_title;
        }
        return $options;
    }
}


function wishlist_locate_template( $tmp_name ) {
    $woo_tmp_base = WC()->template_path();
    $woo_tmp_path = $woo_tmp_base . $tmp_name; //active theme directory/woocommerce/
    $theme_tmp_path = '/' . $tmp_name; //active theme root directory
    $plugin_tmp_path = WISHLIST_DIR . 'incs/templates/' . $tmp_name;
 

    $located = locate_template([ $woo_tmp_path, $theme_tmp_path ]); 

    if ( empty( $located ) && file_exists( $plugin_tmp_path ) ) {
        $located = $plugin_tmp_path;  // Explicitly assign the plugin template path if not found in theme
    }

    return apply_filters( 'wishlist_locate_template', $located, $tmp_name );
}



function WishList_get_template( $tmp_name, $args = null, $echo = true ) {
    $located = wishlist_locate_template( $tmp_name ); 
    if ( $args && is_array( $args ) ) {
        extract( $args );
    }

    if ( $echo !== true ) { ob_start(); }

    // Check if the file exists before including
    if ( file_exists( $located ) ) {
        include( $located );
    } else {
        error_log('Template file not found: ' . $located);
    }

    if ( $echo !== true ) { return ob_get_clean(); }
}


/**
 * [WishList_get_page_url]
 * @return [URL]
 */
function WishList_get_page_url() {
    $page_id = shopxpert_get_option( 'wishlist_page', 'wishlist_table_settings_tabs' );
    return get_permalink( $page_id );
}

/**
 * [WishList_add_to_cart]
 * @param  [object] $product
 * @return [HTML]
 */
function WishList_add_to_cart( $product, $quentity ){
    return \WishList\Frontend\Manage_Wishlist::instance()->add_to_cart_html( $product, $quentity );
}

/**
 * Get default fields List
 * return array
 */
function WishList_get_default_fields(){
    $fields = array(
        'remove'      => esc_html__( 'Remove', 'shopxpert' ),
        'image'       => esc_html__( 'Image', 'shopxpert' ),
        'title'       => esc_html__( 'Title', 'shopxpert' ),
        'price'       => esc_html__( 'Price', 'shopxpert' ),
        'quantity'    => esc_html__( 'Quantity', 'shopxpert' ),
        'add_to_cart' => esc_html__( 'Add To Cart', 'shopxpert' ),
        'description' => esc_html__( 'Description', 'shopxpert' ),
        'availability'=> esc_html__( 'Availability', 'shopxpert' ),
        'sku'         => esc_html__( 'Sku', 'shopxpert' ),
        'weight'      => esc_html__( 'Weight', 'shopxpert' ),
        'dimensions'  => esc_html__( 'Dimensions', 'shopxpert' ),
    );
    return apply_filters( 'wishlist_default_fields', $fields );
}

/**
 * [WishList_table_active_heading]
 * @return [array]
 */
function WishList_table_active_heading(){
    $active_heading = !empty( shopxpert_get_option( 'show_fields', 'wishlist_table_settings_tabs' ) ) ? shopxpert_get_option( 'show_fields', 'wishlist_table_settings_tabs' ) : array();
    return $active_heading;
}

/**
 * [WishList_table_heading]
 * @return [array]
 */
function WishList_table_heading(){
    $new_list = array();

    $active_default_fields = array(
        'remove'      => esc_html__( 'Remove', 'shopxpert' ),
        'image'       => esc_html__( 'Image', 'shopxpert' ),
        'title'       => esc_html__( 'Title', 'shopxpert' ),
        'price'       => esc_html__( 'Price', 'shopxpert' ),
        'quantity'    => esc_html__( 'Quantity', 'shopxpert' ),
        'add_to_cart' => esc_html__( 'Add To Cart', 'shopxpert' ),
    );

    $field_list = count( WishList_table_active_heading() ) > 0 ? WishList_table_active_heading() : $active_default_fields;
    foreach ( $field_list as $key => $value ) {
        $new_list[$key] = \WishList\Frontend\Manage_Wishlist::instance()->field_name( $key );
    }
    return $new_list;
}

/**
 * Get Post List
 * return array
 */
function WishList_get_available_attributes() {
    $attribute_list = array();

    if( function_exists( 'wc_get_attribute_taxonomies' ) ) {
        $attribute_list = wc_get_attribute_taxonomies();
    }

    $fields = WishList_get_default_fields();

    if ( count( $attribute_list ) > 0 ) {
        foreach ( $attribute_list as $attribute ) {
            $fields[ 'pa_' . $attribute->attribute_name ] = $attribute->attribute_label;
        }
    }

    return $fields;
}


/**
 * [WishList_dimensions]
 * @param  [string] $key
 * @param  [string] $tab
 * @return [String | Bool]
 */
function WishList_dimensions( $key, $tab, $css_attr ){
    $dimensions = !empty( shopxpert_get_option( $key, $tab ) ) ? shopxpert_get_option( $key, $tab ) : array();
    if( !empty( $dimensions['top'] ) || !empty( $dimensions['right'] ) || !empty( $dimensions['bottom'] ) || !empty( $dimensions['left'] ) ){
        $unit = empty( $dimensions['unit'] ) ? 'px' : $dimensions['unit'];
        $css_attr .= ":{$dimensions['top']}{$unit} {$dimensions['right']}{$unit} {$dimensions['bottom']}{$unit} {$dimensions['left']}{$unit}";
        return $css_attr.';';
    }else{
        return false;
    }
}

/**
 * [WishList_generate_css]
 * @return [String | Bool]
 */
function WishList_generate_css( $key, $tab, $css_attr ){
    $field_value = !empty( shopxpert_get_option( $key, $tab ) ) ? shopxpert_get_option( $key, $tab ) : '';

    if( !empty( $field_value ) ){
        $css_attr .= ":{$field_value}";
        return $css_attr.';';
    }else{
        return false;
    } 
}

/**
 * [WishList_icon_list]
 * @return [svg]
 */










function WishList_icon_list( $key = '' ){
  $icon_list = [
        'default' => '<svg width="20px" height="20px" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" stroke-width="3" stroke="#000" fill="none"><path d="M9.06 25c-1.38-7.7 3.72-14.37 11.67-15 7-.55 10.47 7.93 11.17 9.55a.13.13 0 0 0 .25 0c3.25-8.91 9.17-9.29 11.25-9.5 5.6-.6 13.11 3.73 11.6 13.82-2.16 14-23.12 29.81-23.12 29.81S11.79 40.05 9.06 25Z"/></svg>',
        'loading' => '<svg height="15px" width="15px" viewBox="0 0 471.701 471.701">
            <g class="loading"><path d="M409.6,0c-9.426,0-17.067,7.641-17.067,17.067v62.344C304.667-5.656,164.478-3.386,79.411,84.479 c-40.09,41.409-62.455,96.818-62.344,154.454c0,9.426,7.641,17.067,17.067,17.067S51.2,248.359,51.2,238.933 c0.021-103.682,84.088-187.717,187.771-187.696c52.657,0.01,102.888,22.135,138.442,60.976l-75.605,25.207 c-8.954,2.979-13.799,12.652-10.82,21.606s12.652,13.799,21.606,10.82l102.4-34.133c6.99-2.328,11.697-8.88,11.674-16.247v-102.4 C426.667,7.641,419.026,0,409.6,0z"></path><path d="M443.733,221.867c-9.426,0-17.067,7.641-17.067,17.067c-0.021,103.682-84.088,187.717-187.771,187.696 c-52.657-0.01-102.888-22.135-138.442-60.976l75.605-25.207c8.954-2.979,13.799-12.652,10.82-21.606 c-2.979-8.954-12.652-13.799-21.606-10.82l-102.4,34.133c-6.99,2.328-11.697,8.88-11.674,16.247v102.4 c0,9.426,7.641,17.067,17.067,17.067s17.067-7.641,17.067-17.067v-62.345c87.866,85.067,228.056,82.798,313.122-5.068 c40.09-41.409,62.455-96.818,62.344-154.454C460.8,229.508,453.159,221.867,443.733,221.867z"></path></g></svg>',
        'email' => '<svg id="Capa_1" enable-background="new 0 0 479.058 479.058" height="512" viewBox="0 0 479.058 479.058" width="512" xmlns="http://www.w3.org/2000/svg">
            <path d="m434.146 59.882h-389.234c-24.766 0-44.912 20.146-44.912 44.912v269.47c0 24.766 20.146 44.912 44.912 44.912h389.234c24.766 0 44.912-20.146 44.912-44.912v-269.47c0-24.766-20.146-44.912-44.912-44.912zm0 29.941c2.034 0 3.969.422 5.738 1.159l-200.355 173.649-200.356-173.649c1.769-.736 3.704-1.159 5.738-1.159zm0 299.411h-389.234c-8.26 0-14.971-6.71-14.971-14.971v-251.648l199.778 173.141c2.822 2.441 6.316 3.655 9.81 3.655s6.988-1.213 9.81-3.655l199.778-173.141v251.649c-.001 8.26-6.711 14.97-14.971 14.97z"/></svg>',
        'facebook' => '<svg id="Bold" enable-background="new 0 0 24 24" height="512" viewBox="0 0 24 24" width="512" xmlns="http://www.w3.org/2000/svg">
            <path d="m15.997 3.985h2.191v-3.816c-.378-.052-1.678-.169-3.192-.169-3.159 0-5.323 1.987-5.323 5.639v3.361h-3.486v4.266h3.486v10.734h4.274v-10.733h3.345l.531-4.266h-3.877v-2.939c.001-1.233.333-2.077 2.051-2.077z"/></svg>',
        'linkedin' => '<svg height="682pt" viewBox="-21 -35 682.66669 682" width="682pt" xmlns="http://www.w3.org/2000/svg">
            <path d="m77.613281-.667969c-46.929687 0-77.613281 30.816407-77.613281 71.320313 0 39.609375 29.769531 71.304687 75.8125 71.304687h.890625c47.847656 0 77.625-31.695312 77.625-71.304687-.894531-40.503906-29.777344-71.320313-76.714844-71.320313zm0 0"/><path d="m8.109375 198.3125h137.195313v412.757812h-137.195313zm0 0"/><path d="m482.054688 188.625c-74.011719 0-123.640626 69.546875-123.640626 69.546875v-59.859375h-137.199218v412.757812h137.191406v-230.5c0-12.339843.894531-24.660156 4.519531-33.484374 9.917969-24.640626 32.488281-50.167969 70.390625-50.167969 49.644532 0 69.5 37.851562 69.5 93.339843v220.8125h137.183594v-236.667968c0-126.78125-67.6875-185.777344-157.945312-185.777344zm0 0"/></svg>',
        'odnoklassniki' => '<svg id="Bold" enable-background="new 0 0 24 24" height="512" viewBox="0 0 24 24" width="512" xmlns="http://www.w3.org/2000/svg">
            <path d="m4.721 12.881c-.613 1.205.083 1.781 1.671 2.765 1.35.834 3.215 1.139 4.413 1.261-.491.472 1.759-1.692-4.721 4.541-1.374 1.317.838 3.43 2.211 2.141l3.717-3.585c1.423 1.369 2.787 2.681 3.717 3.59 1.374 1.294 3.585-.801 2.226-2.141-.102-.097-5.037-4.831-4.736-4.541 1.213-.122 3.05-.445 4.384-1.261l-.001-.001c1.588-.989 2.284-1.564 1.68-2.769-.365-.684-1.349-1.256-2.659-.267 0 0-1.769 1.355-4.622 1.355-2.854 0-4.622-1.355-4.622-1.355-1.309-.994-2.297-.417-2.658.267z"/><path d="m11.999 12.142c3.478 0 6.318-2.718 6.318-6.064 0-3.36-2.84-6.078-6.318-6.078-3.479 0-6.319 2.718-6.319 6.078 0 3.346 2.84 6.064 6.319 6.064zm0-9.063c1.709 0 3.103 1.341 3.103 2.999 0 1.644-1.394 2.985-3.103 2.985s-3.103-1.341-3.103-2.985c-.001-1.659 1.393-2.999 3.103-2.999z"/></svg>',
        'pinterest' => '<svg id="Bold" enable-background="new 0 0 24 24" height="512" viewBox="0 0 24 24" width="512" xmlns="http://www.w3.org/2000/svg">
            <path d="m12.326 0c-6.579.001-10.076 4.216-10.076 8.812 0 2.131 1.191 4.79 3.098 5.633.544.245.472-.054.94-1.844.037-.149.018-.278-.102-.417-2.726-3.153-.532-9.635 5.751-9.635 9.093 0 7.394 12.582 1.582 12.582-1.498 0-2.614-1.176-2.261-2.631.428-1.733 1.266-3.596 1.266-4.845 0-3.148-4.69-2.681-4.69 1.49 0 1.289.456 2.159.456 2.159s-1.509 6.096-1.789 7.235c-.474 1.928.064 5.049.111 5.318.029.148.195.195.288.073.149-.195 1.973-2.797 2.484-4.678.186-.685.949-3.465.949-3.465.503.908 1.953 1.668 3.498 1.668 4.596 0 7.918-4.04 7.918-9.053-.016-4.806-4.129-8.402-9.423-8.402z"/></svg>',
        'reddit' => '<svg id="Bold" enable-background="new 0 0 24 24" height="512" viewBox="0 0 24 24" width="512" xmlns="http://www.w3.org/2000/svg">
            <path d="m21.325 9.308c-.758 0-1.425.319-1.916.816-1.805-1.268-4.239-2.084-6.936-2.171l1.401-6.406 4.461 1.016c0 1.108.89 2.013 1.982 2.013 1.113 0 2.008-.929 2.008-2.038s-.889-2.038-2.007-2.038c-.779 0-1.451.477-1.786 1.129l-4.927-1.108c-.248-.067-.491.113-.557.365l-1.538 7.062c-2.676.113-5.084.928-6.895 2.197-.491-.518-1.184-.837-1.942-.837-2.812 0-3.733 3.829-1.158 5.138-.091.405-.132.837-.132 1.268 0 4.301 4.775 7.786 10.638 7.786 5.888 0 10.663-3.485 10.663-7.786 0-.431-.045-.883-.156-1.289 2.523-1.314 1.594-5.115-1.203-5.117zm-15.724 5.41c0-1.129.89-2.038 2.008-2.038 1.092 0 1.983.903 1.983 2.038 0 1.109-.89 2.013-1.983 2.013-1.113.005-2.008-.904-2.008-2.013zm10.839 4.798c-1.841 1.868-7.036 1.868-8.878 0-.203-.18-.203-.498 0-.703.177-.18.491-.18.668 0 1.406 1.463 6.07 1.488 7.537 0 .177-.18.491-.18.668 0 .207.206.207.524.005.703zm-.041-2.781c-1.092 0-1.982-.903-1.982-2.011 0-1.129.89-2.038 1.982-2.038 1.113 0 2.008.903 2.008 2.038-.005 1.103-.895 2.011-2.008 2.011z"/></svg>',
        'telegram'=>'<svg id="Bold" enable-background="new 0 0 24 24" height="512" viewBox="0 0 24 24" width="512" xmlns="http://www.w3.org/2000/svg">
            <path d="m9.417 15.181-.397 5.584c.568 0 .814-.244 1.109-.537l2.663-2.545 5.518 4.041c1.012.564 1.725.267 1.998-.931l3.622-16.972.001-.001c.321-1.496-.541-2.081-1.527-1.714l-21.29 8.151c-1.453.564-1.431 1.374-.247 1.741l5.443 1.693 12.643-7.911c.595-.394 1.136-.176.691.218z"/></svg>',
        'twitter' => '<svg height="681pt" viewBox="-21 -81 681.33464 681" width="681pt" xmlns="http://www.w3.org/2000/svg">
            <path d="m200.964844 515.292969c241.050781 0 372.871094-199.703125 372.871094-372.871094 0-5.671875-.117188-11.320313-.371094-16.9375 25.585937-18.5 47.824218-41.585937 65.371094-67.863281-23.480469 10.441406-48.753907 17.460937-75.257813 20.636718 27.054687-16.230468 47.828125-41.894531 57.625-72.488281-25.320313 15.011719-53.363281 25.917969-83.214844 31.808594-23.914062-25.472656-57.964843-41.402344-95.664062-41.402344-72.367188 0-131.058594 58.6875-131.058594 131.03125 0 10.289063 1.152344 20.289063 3.398437 29.882813-108.917968-5.480469-205.503906-57.625-270.132812-136.921875-11.25 19.363281-17.742188 41.863281-17.742188 65.871093 0 45.460938 23.136719 85.605469 58.316407 109.082032-21.5-.660156-41.695313-6.5625-59.351563-16.386719-.019531.550781-.019531 1.085937-.019531 1.671875 0 63.46875 45.171875 116.460938 105.144531 128.46875-11.015625 2.996094-22.605468 4.609375-34.558594 4.609375-8.429687 0-16.648437-.828125-24.632812-2.363281 16.683594 52.070312 65.066406 89.960937 122.425781 91.023437-44.855469 35.15625-101.359375 56.097657-162.769531 56.097657-10.5625 0-21.003906-.605469-31.2617188-1.816407 57.9999998 37.175781 126.8710938 58.871094 200.8867188 58.871094"/></svg>',
        'vk'=>'<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 511.962 511.962"       
            style="enable-background:new 0 0 511.962 511.962;" xml:space="preserve">
            <g><path d="M507.399,370.471c-1.376-2.304-9.888-20.8-50.848-58.816c-42.88-39.808-37.12-33.344,14.528-102.176 c31.456-41.92,44.032-67.52,40.096-78.464c-3.744-10.432-26.88-7.68-26.88-7.68l-76.928,0.448c0,0-5.696-0.768-9.952,1.76 c-4.128,2.496-6.784,8.256-6.784,8.256s-12.192,32.448-28.448,60.032c-34.272,58.208-48,61.28-53.6,57.664 c-13.024-8.416-9.76-33.856-9.76-51.904c0-56.416,8.544-79.936-16.672-86.016c-8.384-2.016-14.528-3.36-35.936-3.584 c-27.456-0.288-50.72,0.096-63.872,6.528c-8.768,4.288-15.52,13.856-11.392,14.4c5.088,0.672,16.608,3.104,22.72,11.424 c7.904,10.72,7.616,34.848,7.616,34.848s4.544,66.4-10.592,74.656c-10.4,5.664-24.64-5.888-55.2-58.72 c-15.648-27.04-27.488-56.96-27.488-56.96s-2.272-5.568-6.336-8.544c-4.928-3.616-11.84-4.768-11.84-4.768l-73.152,0.448 c0,0-10.976,0.32-15.008,5.088c-3.584,4.256-0.288,13.024-0.288,13.024s57.28,133.984,122.112,201.536 c59.488,61.92,127.008,57.856,127.008,57.856h30.592c0,0,9.248-1.024,13.952-6.112c4.352-4.672,4.192-13.44,4.192-13.44 s-0.608-41.056,18.464-47.104c18.784-5.952,42.912,39.68,68.48,57.248c19.328,13.28,34.016,10.368,34.016,10.368l68.384-0.96 C488.583,400.807,524.359,398.599,507.399,370.471z"/></g></svg>',
        'whatsapp'=>'<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
            viewBox="0 0 52 52" style="enable-background:new 0 0 52 52;" xml:space="preserve">
            <g><path d="M26,0C11.663,0,0,11.663,0,26c0,4.891,1.359,9.639,3.937,13.762C2.91,43.36,1.055,50.166,1.035,50.237 c-0.096,0.352,0.007,0.728,0.27,0.981c0.263,0.253,0.643,0.343,0.989,0.237L12.6,48.285C16.637,50.717,21.26,52,26,52 c14.337,0,26-11.663,26-26S40.337,0,26,0z M26,50c-4.519,0-8.921-1.263-12.731-3.651c-0.161-0.101-0.346-0.152-0.531-0.152 c-0.099,0-0.198,0.015-0.294,0.044l-8.999,2.77c0.661-2.413,1.849-6.729,2.538-9.13c0.08-0.278,0.035-0.578-0.122-0.821 C3.335,35.173,2,30.657,2,26C2,12.767,12.767,2,26,2s24,10.767,24,24S39.233,50,26,50z"/><path d="M42.985,32.126c-1.846-1.025-3.418-2.053-4.565-2.803c-0.876-0.572-1.509-0.985-1.973-1.218 c-1.297-0.647-2.28-0.19-2.654,0.188c-0.047,0.047-0.089,0.098-0.125,0.152c-1.347,2.021-3.106,3.954-3.621,4.058 c-0.595-0.093-3.38-1.676-6.148-3.981c-2.826-2.355-4.604-4.61-4.865-6.146C20.847,20.51,21.5,19.336,21.5,18 c0-1.377-3.212-7.126-3.793-7.707c-0.583-0.582-1.896-0.673-3.903-0.273c-0.193,0.039-0.371,0.134-0.511,0.273 c-0.243,0.243-5.929,6.04-3.227,13.066c2.966,7.711,10.579,16.674,20.285,18.13c1.103,0.165,2.137,0.247,3.105,0.247 c5.71,0,9.08-2.873,10.029-8.572C43.556,32.747,43.355,32.331,42.985,32.126z M30.648,39.511 c-10.264-1.539-16.729-11.708-18.715-16.87c-1.97-5.12,1.663-9.685,2.575-10.717c0.742-0.126,1.523-0.179,1.849-0.128 c0.681,0.947,3.039,5.402,3.143,6.204c0,0.525-0.171,1.256-2.207,3.293C17.105,21.48,17,21.734,17,22c0,5.236,11.044,12.5,13,12.5 c1.701,0,3.919-2.859,5.182-4.722c0.073,0.003,0.196,0.028,0.371,0.116c0.36,0.181,0.984,0.588,1.773,1.104 c1.042,0.681,2.426,1.585,4.06,2.522C40.644,37.09,38.57,40.701,30.648,39.511z"/></g></svg>'
    ];
    return ( $key == '' ) ? $icon_list : $icon_list[$key];
}