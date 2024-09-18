<?php
/*
 * Plugisn Options value
 * return on/off
 */
function smartshopBlocks_get_option( $option, $section, $default = '' ){
    $options = get_option( $section );
    if ( isset( $options[$option] ) ) {
        return $options[$option];
    }
    return $default;
}

/**
* Woocommerce Product last product id return
*/
function smartshopBlocks_get_last_product_id(){
    global $wpdb;

    $cache_key  = 'smartshop_last_product_id';
    $results    = wp_cache_get( $cache_key );

    // Getting last Product ID (max value)
    if ( false === $results ) {
        $results = $wpdb->get_col($wpdb->prepare("SELECT MAX(ID) FROM {$wpdb->prefix}posts WHERE post_type = %s AND post_status = %s", 'product', 'publish'));
        wp_cache_set( $cache_key, $results );
    }

    return reset($results);
    
}

/**
* Woocommerce Product last order id return
*/
function smartshopBlocks_get_last_order_id(){
    if( function_exists('wc_get_orders') ){
        $orders = wc_get_orders( array(
            'limit' => 1,  // Limit the query to one order
            'orderby' => 'date',  // Order by date
            'order' => 'DESC',    // Sort in descending order (latest first)
        ));

        // Check if there are any orders
        if ( !empty( $orders ) ) {
            $latest_order = reset( $orders ); // Get the first (latest) order in the array
            return $latest_order->get_id(); // Get the order ID
        }
    }

    return 0;
}

/**
* Template Editor Mode
*/
function smartshopBlocks_edit_mode(){
    if( !empty( $_GET['post'] ) && $_GET['action'] === 'edit' ){
        $post_obj = get_post( $_GET['post'] );
        if( $post_obj->post_type === 'smartshop-template' ) {
            return true;
        }else{
            return false;
        }
    }else{
        return false;
    }
}

/**
* Checked Current theme is FSE
*/
function smartshopBlocks_current_theme_is_fse() {
	if ( function_exists( 'wp_is_block_theme' ) ) {
		return (bool) wp_is_block_theme();
	}
	if ( function_exists( 'gutenberg_is_fse_theme' ) ) {
		return (bool) gutenberg_is_fse_theme();
	}

	return false;
}

/**
 * Generate CSS
 *
 * @param [array] $settings
 * @param [string] $attribute
 * @param [string] $css_attr
 * @param string $unit
 * @param string $important
 */
function smartshopBlocks_generate_css( $settings, $attribute, $css_attr, $unit = '', $important = '' ){

    $value = !empty( $settings[$attribute] ) ? $settings[$attribute] : '';

    if( !empty( $value ) && 'NaN' !== $value ){
        $css_attr .= ":{$value}{$unit}";
        return $css_attr."{$important};";
    }else{
        return false;
    }

}

/**
 * Manage Dimension
 *
 * @param [array] $settings
 * @param [string] $attribute
 * @param [string] $css_attr
 * @param string $important
 */
function smartshopBlocks_Dimention_Control( $settings, $attribute, $css_attr, $important = '' ){
    $dimensions = !empty( $settings[$attribute] ) ? $settings[$attribute] : array();

    if( array_key_exists( 'device', $dimensions ) ){
        $generate_dimension = [ 'desktop'=>false, 'laptop'=>false, 'tablet'=>false,'mobile'=>false ];
        foreach( $dimensions as $key => $dimension ){
            if( $key === 'device' ){
                continue;
            }
            $generate_dimension[$key] = smartshopBlocks_Dimention_Value( $dimension, $css_attr, $important );
        }
        return $generate_dimension;
    }else{
        return smartshopBlocks_Dimention_Value( $dimensions, $css_attr, $important );
    }

}

/**
 * Generate Dimension value
 *
 * @param [array] $dimensions
 * @param [string] $css_attr
 * @param [string] $important
 */
function smartshopBlocks_Dimention_Value( $dimensions, $css_attr, $important ){
    if( isset( $dimensions['top'] ) || isset( $dimensions['right'] ) || isset( $dimensions['bottom'] ) || isset( $dimensions['left'] ) ){
        $unit = empty( $dimensions['unit'] ) ? 'px' : $dimensions['unit'];

        $top = ( $dimensions['top'] !== '' ) ? $dimensions['top'].$unit : null;
        $right = ( $dimensions['right'] !== '' ) ? $dimensions['right'].$unit : null;
        $bottom = ( $dimensions['bottom'] !== '' ) ? $dimensions['bottom'].$unit : null;
        $left = ( $dimensions['left'] !== '' ) ? $dimensions['left'].$unit : null;
        $css_dimension = ( ($top != null) || ($right !=null) || ($bottom != null) || ($left != '') ) ? ( $css_attr.":{$top} {$right} {$bottom} {$left} {$important};" ) : '';
        return $css_dimension;
    }else{
        return false;
    }
}

/**
 * Background Image control
 *
 * @param [array] $settings
 * @param [string] $attribute
 */
function smartshopBlocks_Background_Control( $settings, $attribute ){
    $background_property = !empty( $settings[$attribute] ) ? $settings[$attribute] : array();
    
    if( !empty( $background_property['imageId'] ) ){
        $image_url = wp_get_attachment_image_src( $background_property['imageId'], 'full' );
        $background_css = "background-image:url({$image_url[0]});";

        if( !empty( $background_property['position'] ) ){
            $background_css .= "background-position:{$background_property['position']};";
        }
        if( !empty( $background_property['attachment'] ) ){
            $background_css .= "background-attachment:{$background_property['attachment']};";
        }
        if( !empty( $background_property['repeat'] ) ){
            $background_css .= "background-repeat:{$background_property['repeat']};";
        }
        if( !empty( $background_property['size'] ) ){
            $background_css .= "background-size:{$background_property['size']};";
        }

        return  $background_css;

    }else{
        return false;
    }
    
}

/**
 * Check Gutenberg editor page
 */
function smartshopBlocks_is_gutenberg_page() {

    if ( !function_exists( 'get_current_screen' ) ) {
        require_once( ABSPATH . 'wp-admin/includes/screen.php' );
    }
    
	// Gutenberg plugin is enable.
    if ( function_exists( 'is_gutenberg_page' ) && is_gutenberg_page() ) { 
        return true;
    }
	
	// Gutenberg editor page
	$current_screen = get_current_screen();
	if ( $current_screen !== NULL && method_exists( $current_screen, 'is_block_editor' ) && $current_screen->is_block_editor() ) {
        return true;
	}
	
    return false;

}

/**
 * Check Gutenberg Edit Screen if dose not work 'smartshopBlocks_is_gutenberg_page' this function then we will use it.
 */
function smartshopBlocks_gutenberg_edit_screen(){
    if( isset( $_SERVER['QUERY_STRING'] ) ){
        parse_str( $_SERVER['QUERY_STRING'], $query_arr );
    } else {
        $query_arr = [];
    }

    $basename = basename( $_SERVER['PHP_SELF'] );
    $post_add_new_screen = ( $basename === 'post-new.php' || $basename === 'site-editor.php' ) ? true : false;
    if( $post_add_new_screen === false ){
        if( is_admin() && empty( $query_arr['action'] ) ){
            return false;
        }else{
            return true;
        }
    }else{
        return true;
    }
}

/**
 * current page blocks
 */
function smartshopBlocks_check_inner_blocks( $block ) {
    static $currentBlocks = [];
    
    $current = $block;

    if( $block['blockName'] == 'core/block' ) { //reusable block
        $current = parse_blocks( get_post_field( 'post_content', $block['attrs']['ref'] ) ) ? parse_blocks( get_post_field( 'post_content', $block['attrs']['ref'] ) )[0] : '';
    }

    if( isset( $current['blockName'] ) && $current['blockName'] != '' ) {
        array_push( $currentBlocks, $current );
        if( count( $current['innerBlocks'] ) > 0 ){
            foreach( $current['innerBlocks'] as $innerBlock ) {
                smartshopBlocks_check_inner_blocks( $innerBlock );
            }
        }
    }
    return $currentBlocks;
}

/**
 * Get All Current page blocks
 */
function smartshopBlocks_get_blocks( $id = '' ){
    $get_blocks = [];

    $posts_array = !empty($id) ? get_post($id) : get_post();
    if( $posts_array ){
        foreach( parse_blocks( $posts_array->post_content ) as $block){
            $get_blocks = smartshopBlocks_check_inner_blocks( $block );
        }
    }

    return $get_blocks;

}

/**
 * Get Block Setting by block name.
 */
function smartshopBlocks_get_settings_by_blockName( $id, $block_name ){
    $blocks_data = smartshopBlocks_get_blocks( $id );

    $blocks_data = array_filter( $blocks_data, function( $block ) use ( $block_name ){
        return $block_name === $block['blockName'];
    } );

    return !empty( $blocks_data[0]['attrs'] ) ? $blocks_data[0]['attrs'] : false;
}

/**
 * Get Post ID
 */
function smartshopBlocks_get_ID(){
    if( class_exists('\Smartshop_Manage_WC_Template') ){
        $post_id = \Smartshop_Manage_WC_Template::instance()->get_builder_template_id();
    }else{
        $post_id = '';
    }
    return $post_id != '' ? $post_id : smartshopBlocks_Current_Template_Id();
}

/**
 * Get Current FSE Template ID OR Post id.
 */
function smartshopBlocks_Current_Template_Id(){
    global $_wp_current_template_id, $_wp_current_template_content, $wp_embed, $wp_query;
    if( empty( $_wp_current_template_id ) ){
        return get_the_ID();
    }
    $template_slug = explode( '//', $_wp_current_template_id );
    return (smartshopBlocks_current_theme_is_fse() && isset($template_slug[1]) && $template_slug[1] != 'page') ? $template_slug[1] : get_the_ID();
}

/**
 * Check has SmartShop blocks
 *
 * @return bool
 */
function smartshopBlocks_Has_Blocks( $id ){
    $content = get_the_content( null, false, $id );
    $blocks  = parse_blocks( $content );
    return smartshopBlocks_Search_Wl_block( $blocks );
}

/**
 * Check has SmartShop block
 *
 * @return bool
 */
function smartshopBlocks_Search_Wl_block( $blocks ) {
    $has_block  = false;
    foreach ( $blocks as $value ) {
        if( isset( $value['blockName'] ) ) {
            $block_name = explode( '/', $value['blockName'] );
            if( $block_name[0] === 'shopxper' ){
                $has_block = true;
                break;
            }
        }
        // Check InnerBlocks
        if( !empty( $value['innerBlocks'] ) ){
            $has_block = smartshopBlocks_Search_Wl_block( $value['innerBlocks'] );
        }
    }
    return $has_block;
}

/**
 * Get Reusable ID
 *
 * @return array
 */
function smartshopBlocks_reusable_id( $post_id ){
    global $_wp_current_template_content;
    $reusable_id = [];
    if( $post_id ){
        $post = get_post( $post_id );
        $post_content = $post !== null ? $post->post_content : $_wp_current_template_content;
        if ( has_blocks( $post_content ) ) {
            $blocks = parse_blocks( $post_content );
            foreach ($blocks as $key => $value) {
                if( isset( $value['attrs']['ref'] ) ) {
                    $reusable_id[] = $value['attrs']['ref'];
                }
            }
        }
    }
    return $reusable_id;
}

/**
 * Get Image Sizes
 */
function smartshopBlocks_get_image_size() {
    $sizes = get_intermediate_image_sizes();
    $filter = [ 'full' => __( 'Full','shopxper') ];
    foreach ( $sizes as $value ) {
        $filter[$value] = ucwords( str_replace( array('_', '-'), array(' ', ' '), $value ) );
    }
    return $filter;
}

/**
 * Get Category data
 *
 * @param string $taxnomySlug
 * @param integer $number
 * @param string $order
 * @param string $type
 */
function smartshopBlocks_taxnomy_data( $taxnomySlug = '', $number = 20, $order = 'asc', $type = '' ){
    
    $data = array();

    $queryArg = array(
        'taxonomy'   => 'product_cat',
        'orderby'    => 'name',
        'order'      => $order,
        'number'     => $number,
        'hide_empty' => true,
    );

    if( !empty( $taxnomySlug ) ){
        $queryArg['slug'] = $taxnomySlug;
    }

    $term_data = get_terms( $queryArg );

    if( !empty( $term_data ) && !is_wp_error( $term_data ) ){

        foreach ( $term_data as $terms ) {
            $tempData = array();
            $thumbnail_id   = get_term_meta( $terms->term_id, 'thumbnail_id', true ) ? get_term_meta( $terms->term_id, 'thumbnail_id', true ) : ''; 
            $tempData['link']   = get_term_link( $terms );
            $tempData['name']   = $terms->name;
            $tempData['slug']   = $terms->slug;
            $tempData['desc']   = $terms->description;
            $tempData['count']  = $terms->count;
            $tempData['thumbnail_id']  = $thumbnail_id ? $thumbnail_id : '';
            $tempData['placeholderImg']  = wc_placeholder_img_src( 'woocommerce_single' );
            
            // Images
            if( $thumbnail_id ){
                $image_sizes    = smartshopBlocks_get_image_size();
                $image_src      = array();
                foreach ( $image_sizes as $key => $size ) {
                    $image_src[$key] = [
                        'src' => wp_get_attachment_image_src( $thumbnail_id, $key, false )[0],
                        'html' => wp_get_attachment_image( $thumbnail_id, $key )
                    ];
                }
                $tempData['image'] = $image_src;
            }

            $data[] = $tempData;
        }
        
    }

    return $data;

}

/**
 * Product Type
 *
 * @param string $type
 */
function smartshopBlocks_Product_type( $type ) {
    switch ( $type ) {

        case 'recent':
            $product_type = 'recent_products';
            break;

        case 'sale':
            $product_type = 'sale_products';
            break;

        case 'best_selling':
            $product_type = 'best_selling_products';
            break;

        case 'top_rated':
            $product_type = 'top_rated_products';
            break;

        case 'featured':
            $product_type = 'featured';
            break;

        default:
            $product_type = 'products';
            break;
    }
    return $product_type;
}

/**
 * Product Query
 *
 * @param array $params
 */
function smartshopBlocks_Product_Query( $params ){
    
    $meta_query = $tax_query = array();
    
    $query_args = array(
        'post_type'         => 'product',
        'post_status'       => 'publish',
        'posts_per_page'    => isset( $params['perPage'] ) ? $params['perPage'] : 4,
        'order'             => isset( $params['order'] ) ? $params['order'] : 'DESC',
        'orderby'           => isset( $params['orderBy'] ) ? $params['orderBy'] : 'date',
        'paged'             => isset( $params['paged'] ) ? $params['paged'] : 1,
    );

    // Categories wise
    if( isset( $params['categories'] ) ){
        $field_name = 'slug';
        $tax_query[] = array(
            'taxonomy' => 'product_cat',
            'terms' => $params['categories'],
            'field' => $field_name,
            'include_children' => false
        );
    }

    // Tag wise
    if( isset( $params['tags'] ) ){
        $field_name = 'slug';
        $tax_query[] = array(
            'taxonomy' => 'product_tag',
            'terms' => $params['tags'],
            'field' => $field_name,
            'include_children' => false
        );
    }
    $query_args['tax_query'] = $tax_query;

    if( isset( $params['offset'] ) && $params['offset'] && !( $query_args['paged'] > 1 ) ){
        $query_args['offset'] = isset( $params['offset'] ) ? $params['offset'] : 0;
    }

    if( isset( $params['include'] ) && $params['include'] ){
        $query_args['post__in'] = explode( ',', $params['include'] );
    }

    if( isset( $params['exclude'] ) && $params['exclude'] ){
        $query_args['post__not_in'] = explode( ',', $params['exclude'] );
    }

    if( isset( $params['filterBy'] ) ){

        switch ( $params['filterBy'] ) {
            
            case 'featured':
                $query_args['post__in'] = wc_get_featured_product_ids();
            break;
    
            case 'best_selling':
                $query_args['meta_key']   = 'total_sales';
                $query_args['orderby']    = 'meta_value_num';
                $query_args['order']      = 'desc';
            break;

            case 'sale':
                $query_args['post__in'] = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
            break;
    
            case 'top_rated': 
                $query_args['meta_key']   = '_wc_average_rating';
                $query_args['orderby']    = 'meta_value_num';
                $query_args['order']      = 'desc';          
            break;
    
            case 'mixed_order':
                $query_args['orderby']    = 'rand';
            break;
    
            default: /* Recent */
                $query_args['orderby']    = 'date';
                $query_args['order']      = 'desc';
            break;
            
        }

    }

    /**
     * Custom Order
     */
    if( isset( $params['orderBy'] ) && 'none' != $params['orderBy'] ){
        $query_args['orderby'] = $params['orderBy'];
    }
    if( isset( $params['order'] ) ){
        $query_args['order'] = $params['order'];
    }

    $query_args['wpnonce'] = wp_create_nonce( 'smartshopblock-nonce' );

    return $query_args;
}

/**
 * Compare Button
 *
 * @param array $button_arg
 */
function smartshopBlocks_compare_button( $button_arg = array() ){

    global $product;
    $product_id = $product->get_id();

    $output = '';

    $button_style       = !empty( $button_arg['style'] ) ? $button_arg['style'] : 1;

    if( class_exists('Ever_Compare') || class_exists('Smartshop_Ever_Compare') ){

        $button_title       = !empty( $button_arg['title'] ) ? $button_arg['title'] : esc_html__('Add to Compare','shopxper');
        $button_text        = !empty( $button_arg['btn_text'] ) ? $button_arg['btn_text'] : esc_html__('Add to Compare','shopxper');
        $button_added_text  = !empty( $button_arg['btn_added_txt'] ) ? $button_arg['btn_added_txt'] : esc_html__( 'Product Added','shopxper' );

        $comp_link = \EverCompare\Frontend\Manage_Compare::instance()->get_compare_page_url();
        $output = '<a title="'.esc_attr( $button_title ).'" href="'.esc_url( $comp_link ).'" class="htcompare-btn smartshop-compare" data-added-text="'.esc_attr( $button_added_text ).'" data-product_id="'.esc_attr( $product_id ).'">'.$button_text.'</a>';
        return $output;

    }elseif( class_exists('YITH_Woocompare') ){
        $comp_link = home_url() . '?action=yith-woocompare-add-product';
        $comp_link = add_query_arg('id', $product_id, $comp_link);

        if( $button_style == 1 ){
            if( class_exists('YITH_Woocompare_Frontend') ){
                $output = do_shortcode('[yith_compare_button]');
            }
        }else{
            $output = '<a title="'. esc_attr__('Add to Compare', 'shopxper') .'" href="'. esc_url( $comp_link ) .'" class="smartshop-compare compare" data-product_id="'. esc_attr( $product_id ) .'" rel="nofollow">'.esc_html__( 'Compare', 'shopxper' ).'</a>';
        }
        return $output;
    }else{
        return 0;
    }

}

/**
 * Ratting Generate
 *
 * @param int $ratting_num
 */
function smartshopBlocks_ratting( $ratting_num ){
    if( !empty( $ratting_num ) ){
        $rating = $ratting_num;
        $rating_whole = floor( $ratting_num );
        $rating_fraction = $rating - $rating_whole;
        echo '<ul class="rating">';
            for($i = 1; $i <= 5; $i++){
                if( $i <= $rating_whole ){
                    echo '<li><i class="fa fa-star"></i></li>';
                } else {
                    if( $rating_fraction != 0 ){
                        echo '<li><i class="fa fa-star-half-o"></i></li>';
                        $rating_fraction = 0;
                    } else {
                        echo '<li><i class="fa fa-star-o"></i></li>';
                    }
                }
            }
        echo '</ul>';
    }
}