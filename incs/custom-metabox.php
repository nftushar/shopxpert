<?php  

use function Shopxpert\incs\shopxpert_get_option;



if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Shopxpert_Custom_Meta_Fields{

    private static $_instance = null;

    /**
     * Get Instance
     */
    public static function get_instance(){
        if( is_null( self::$_instance ) ){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    public function __construct(){

        // Custom Product tab
        add_filter( 'woocommerce_product_data_tabs', [ $this, 'product_shopxpert_tab' ], 10, 1 );
		add_action( 'woocommerce_product_data_panels', [ $this, 'product_shopxpert_data_panel' ], 99 );
		add_action( 'woocommerce_process_product_meta', [ $this, 'save_shopxpert_product_meta' ] );

        // Product category custom field
        add_action('product_cat_add_form_fields', [ $this, 'taxonomy_add_new_meta_field' ], 15, 1 );
        add_action('product_cat_edit_form_fields', [ $this, 'taxonomy_edit_meta_field' ], 15, 1 );
        add_action('edited_product_cat', [ $this, 'save_taxonomy_custom_meta' ], 15, 1 );
        add_action('create_product_cat', [ $this, 'save_taxonomy_custom_meta' ], 15, 1 );

    }

    /**
     * product_shopxpert_tab product custom tab for shopxpert
     *
     * @param [type] $tabs
     * @return void
     */
    public function product_shopxpert_tab( $tabs ){
        ?>
            <style>
                #woocommerce-product-data ul.wc-tabs li.shopxpert_product_data_tab_tab a::before { content: ''; background-image: url(<?php echo SHOPXPERT_ADDONS_PL_URL . 'incs/admin/assets/images/logo.png'; ?>); width: 18px; height: 18px; background-size: 18px 18px; display: inline-block; top: 3px; position: relative; }
            </style>
		<?php
        
        $tabs['shopxpert_product_data_tab'] = array(
            'label'    => __( 'ShopXpert', 'shopxpert' ),
            'target'   => 'shopxpert_product_data',
            'class'    => 'wl_product_layout_opt',
            'priority' => 85,
        );

        return $tabs;

    }

    /**
     * product_shopxpert_data_panel shopxpert product tab data
     *
     * @return void
     */
    public function product_shopxpert_data_panel(){
        global $post;

        // Single product layout field
        echo '<div id="shopxpert_product_data" class="panel woocommerce_options_panel hidden">';

            // Product Layout Field
            echo '<div class="options_group">';
                $value = get_post_meta( $post->ID, '_selectproduct_layout', true );
                if( empty( $value ) ) $value = '0';
                
                echo '<p class=" form-field _selectproduct_layout_field">';
                    echo '<label for="_selectproduct_layout">'.esc_html__( 'Select Product layout', 'shopxpert' ).'</label>';
                    echo '<select class="select short" id="_selectproduct_layout" name="_selectproduct_layout">';
                        $shopxpert_templates = [];
                        if( function_exists( 'shopxpert_wltemplate_list' ) ){
                            $shopxpert_templates = shopxpert_wltemplate_list( array('single') );
                        }

                        if( !empty( $shopxpert_templates ) ){
                            echo '<optgroup label="'.esc_attr('ShopXpert').'">';
                            foreach ( $shopxpert_templates as $template_key => $template ) {
                                echo '<option value="'.esc_attr( $template_key ).'" '.selected( $value, $template_key, false ).'>'.esc_html__( $template, 'shopxpert' ).'</option>';
                            }
                            echo '</optgroup>';
                        }
                    echo '</select></p>';

            echo '</div>';

            // Custom Cart Content
            echo '<div class="options_group">';
                woocommerce_wp_textarea_input(
                    array(
                        'id'          => 'shopxpert_cart_custom_content',
                        'label'       => __( 'Custom Content for cart page', 'shopxpert' ),
                        'desc_tip'    => true,
                        'description' => __( 'If you want to show cart page custom content', 'shopxpert' ),
                    )
                );
            echo '</div>';

            // Partial Payment
            if( ( shopxpert_get_option( 'enable', 'shopxpert_partial_payment_settings', 'off' ) == 'on' ) ){
                ?>
                    <div class="options_group">
                        <h4 class="shopxpert-group-heading" style="margin-bottom: 0;margin-left: 12px;"><?php echo esc_html__('Partial Payment','shopxpert'); ?></h4>
                        <?php

                            $enable_status = get_post_meta( $post->ID, 'shopxpert_partial_payment_enable', true );
                            $display_field = $enable_status === 'yes' ? 'shopxpert-hidden-field' : 'shopxpert-hidden-field hidden';

                            woocommerce_wp_checkbox(
                                array(
                                    'id'          => 'shopxpert_partial_payment_enable',
                                    'label'       => esc_html__('Enable Partial Payment', 'shopxpert'),
                                    'description' => esc_html__('Enable this to require a partial payment for this product.', 'shopxpert'),
                                    'desc_tip'    => true
                                )
                            );

                            woocommerce_wp_select( [
                                'id'      => 'shopxpert_partial_payment_amount_type',
                                'label'   => esc_html__( 'Partial Amount Type', 'shopxpert' ),
                                'options' => [
                                    'fixedamount'   => esc_html__('Fixed Amount','shopxpert'),
                                    'percentage'    => esc_html__('Percentage','shopxpert'),
                                ],
                                'value'         => $this->get_saved_data( $post->ID, 'shopxpert_partial_payment_amount_type', 'amount_type', 'shopxpert_partial_payment_settings', 'percentage' ),
                                'wrapper_class' => $display_field,
                            ] );
            
                            woocommerce_wp_text_input( [
                                'id'          => 'shopxpert_partial_payment_amount',
                                'label'       => esc_html__( 'Partial Payment Amount', 'shopxpert' ),
                                'placeholder' => esc_html__( 'Amount', 'shopxpert' ),
                                'value'       => $this->get_saved_data( $post->ID, 'shopxpert_partial_payment_amount', 'amount', 'shopxpert_partial_payment_settings', '50' ),
                                'wrapper_class' => $display_field,
                            ] );

                        ?>
                    </div>
                <?php
            }

            // Pre Orders
            $pre_order_enabled = shopxpert_get_option( 'enable', 'shopxpert_pre_order_settings', 'off' );
            if ( $pre_order_enabled === 'off' ) {
                // Backwards-compatibility: older installations used 'enablerpreorder'
                $pre_order_enabled = shopxpert_get_option( 'enablerpreorder', 'shopxpert_pre_order_settings', 'off' );
            }
            if( $pre_order_enabled == 'on' ){
                ?>
                    <div class="options_group">
                        <h4 class="shopxpert-group-heading" style="margin-bottom: 0;margin-left: 12px;"><?php echo esc_html__('Pre Order','shopxpert'); ?></h4>
                        <?php
                            $enable_pre_order = get_post_meta( $post->ID, 'shopxpert_pre_order_enable', true );
                            $wrapper_class = $enable_pre_order === 'yes' ? 'shopxpert-hidden-field' : 'shopxpert-hidden-field hidden';

                            woocommerce_wp_checkbox(
                                [
                                    'id'          => 'shopxpert_pre_order_enable',
                                    'label'       => esc_html__('Enable Pre Order', 'shopxpert'),
                                    'description' => esc_html__('Enable this to require a pre order for this product.', 'shopxpert'),
                                    'desc_tip'    => true
                                ]
                            );

                            echo '<div class="shopxpert-pre-order-fields '.$wrapper_class.'">';

                                $manage_price = get_post_meta( $post->ID, 'shopxpert_pre_order_manage_price', true );
                                $price_field_class = $manage_price !== 'product_price' ? '' : 'hidden';

                                echo '<p class="form-field shopxpert_pre_order_available_date_field">';

                                    echo '<label for="shopxpert_pre_order_available_date">'.esc_html__( 'Available Date', 'shopxpert' ).'</label>';
                                    echo sprintf( '<input type="date" class="short" id="%1$s" name="%1$s" value="%2$s"/>', 'shopxpert_pre_order_available_date', $this->get_saved_data( $post->ID, 'shopxpert_pre_order_available_date', 'shopxpert_pre_order_available_date', 'shopxpert_pre_order_settings', '' ) );

                                    echo sprintf( '<input type="time" class="shopxpert_pre_order_available_time" id="%1$s" name="%1$s" value="%2$s"/>', 'shopxpert_pre_order_available_time', $this->get_saved_data( $post->ID, 'shopxpert_pre_order_available_time', 'shopxpert_pre_order_available_time', 'shopxpert_pre_order_settings', '' ) );

                                echo '</p>';

                                woocommerce_wp_select( 
                                    [
                                        'id'      => 'shopxpert_pre_order_manage_price',
                                        'label'   => esc_html__( 'Manage Price', 'shopxpert' ),
                                        'options' =>  [
                                            'product_price' => esc_html__( 'Product Price', 'shopxpert' ),
                                            'increase_price' => esc_html__( 'Increase Price', 'shopxpert' ),
                                            'decrease_price' => esc_html__( 'Decrease Price', 'shopxpert' ),
                                            'fixed_price'   => esc_html__( 'Fixed Price', 'shopxpert' ),
                                        ],
                                        'value' => $this->get_saved_data( $post->ID, 'shopxpert_pre_order_manage_price', 'shopxpert_pre_order_manage_price', 'shopxpert_pre_order_settings', 'product_price' ),
                                    ] 
                                );
                                woocommerce_wp_select( 
                                    [
                                        'id'      => 'shopxpert_pre_order_amount_type',
                                        'label'   => esc_html__( 'Amount Type', 'shopxpert' ),
                                        'options' =>  [
                                            'fixed_amount' => esc_html__( 'Fixed Amount', 'shopxpert' ),
                                            'percentage'   => esc_html__( 'Percentage', 'shopxpert' ),
                                        ],
                                        'value' => $this->get_saved_data( $post->ID, 'shopxpert_pre_order_amount_type', 'shopxpert_pre_order_amount_type', 'shopxpert_pre_order_settings', 'percentage' ),
                                        'wrapper_class' => ( get_post_meta( $post->ID, 'shopxpert_pre_order_manage_price', true ) == 'fixed_price' || get_post_meta( $post->ID, 'shopxpert_pre_order_manage_price', true ) == 'product_price' ) ? 'hidden' : '',
                                    ] 
                                );

                                woocommerce_wp_text_input( [
                                    'id'          => 'shopxpert_pre_order_amount',
                                    'label'       => esc_html__( 'Amount', 'shopxpert' ),
                                    'placeholder' => esc_html__( 'Amount', 'shopxpert' ),
                                    'value'       => $this->get_saved_data( $post->ID, 'shopxpert_pre_order_amount', 'shopxpert_pre_order_amount', 'shopxpert_pre_order_settings', '50' ),
                                    'wrapper_class' => 'shopxpert-mange-price '.$price_field_class,
                                ] );

                            echo '</div>';
                        ?>
                    </div>
                <?php
            }

        echo '</div>';
    }

    /**
     * save_shopxpert_product_meta custom tab data save
     *
     * @return void
     */
    public function save_shopxpert_product_meta( $post_id ){

        if( wp_verify_nonce( sanitize_key( $_POST['woocommerce_meta_nonce'] ), 'woocommerce_save_data' ) ){

            // Single Product Layout
            $selectproduct_layout = !empty( $_POST['_selectproduct_layout'] ) ? sanitize_text_field( $_POST['_selectproduct_layout'] ) : '';
            update_post_meta( $post_id, '_selectproduct_layout', $selectproduct_layout );

            // Cat Page Custom Content
            $selectproduct_cart_content = !empty( $_POST['shopxpert_cart_custom_content'] ) ? sanitize_text_field( $_POST['shopxpert_cart_custom_content'] ) : '';
            update_post_meta( $post_id, 'shopxpert_cart_custom_content', $selectproduct_cart_content );

            // Manage Partial Payment data
            if( ( shopxpert_get_option( 'enable', 'shopxpert_partial_payment_settings', 'off' ) == 'on' ) ){

                $status = !empty( $_POST['shopxpert_partial_payment_enable'] ) ? sanitize_text_field( $_POST['shopxpert_partial_payment_enable'] ) : '';
                update_post_meta( $post_id, 'shopxpert_partial_payment_enable', $status );

                $amount_type = !empty( $_POST['shopxpert_partial_payment_amount_type'] ) ? sanitize_text_field( $_POST['shopxpert_partial_payment_amount_type'] ) : '';
                update_post_meta( $post_id, 'shopxpert_partial_payment_amount_type', $amount_type );

                $amount = !empty( $_POST['shopxpert_partial_payment_amount'] ) ? sanitize_text_field( $_POST['shopxpert_partial_payment_amount'] ) : '';
                update_post_meta( $post_id, 'shopxpert_partial_payment_amount', $amount );

            }

            // Manage Pre Order data
            $pre_order_enabled = shopxpert_get_option( 'enable', 'shopxpert_pre_order_settings', 'off' );
            if ( $pre_order_enabled === 'off' ) {
                // Backwards-compatibility: older installations used 'enablerpreorder'
                $pre_order_enabled = shopxpert_get_option( 'enablerpreorder', 'shopxpert_pre_order_settings', 'off' );
            }
            if( $pre_order_enabled == 'on' ){

                $pre_order_status = !empty( $_POST['shopxpert_pre_order_enable'] ) ? sanitize_text_field( $_POST['shopxpert_pre_order_enable'] ) : '';
                update_post_meta( $post_id, 'shopxpert_pre_order_enable', $pre_order_status );

                $pre_order_date = !empty( $_POST['shopxpert_pre_order_available_date'] ) ? sanitize_text_field( $_POST['shopxpert_pre_order_available_date'] ) : '';
                update_post_meta( $post_id, 'shopxpert_pre_order_available_date', $pre_order_date );

                $pre_order_time = !empty( $_POST['shopxpert_pre_order_available_time'] ) ? sanitize_text_field( $_POST['shopxpert_pre_order_available_time'] ) : '00:00';
                update_post_meta( $post_id, 'shopxpert_pre_order_available_time', $pre_order_time );

                $pre_order_manage_price = !empty( $_POST['shopxpert_pre_order_manage_price'] ) ? sanitize_text_field( $_POST['shopxpert_pre_order_manage_price'] ) : '';
                update_post_meta( $post_id, 'shopxpert_pre_order_manage_price', $pre_order_manage_price );

                $pre_order_amount_type = !empty( $_POST['shopxpert_pre_order_amount_type'] ) ? sanitize_text_field( $_POST['shopxpert_pre_order_amount_type'] ) : '';
                update_post_meta( $post_id, 'shopxpert_pre_order_amount_type', $pre_order_amount_type );

                $pre_order_amount = !empty( $_POST['shopxpert_pre_order_amount'] ) ? sanitize_text_field( $_POST['shopxpert_pre_order_amount'] ) : '';
                update_post_meta( $post_id, 'shopxpert_pre_order_amount', $pre_order_amount );

                $get_field_date  = strtotime( $pre_order_date );
                $get_field_time  = self::time_to_second( $pre_order_time );
                if ( $get_field_date ) {
                    $get_field_date += $get_field_time - get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;
                }
                $next_date = wp_next_scheduled( '_shopxpert_pre_order_schedule_date_cron', array( $post_id ) );
                if ( $next_date ) {
                    if ( $next_date !== $get_field_date ) {
                        wp_unschedule_event( $next_date, '_shopxpert_pre_order_schedule_date_cron', array( $post_id ) );
                        if ( $get_field_date ) {
                            wp_schedule_single_event( $get_field_date, '_shopxpert_pre_order_schedule_date_cron', array( $post_id ) );
                        } else {
                            wp_schedule_single_event( $next_date, '_shopxpert_pre_order_schedule_date_cron', array( $post_id ) );
                        }
                    } elseif ( $get_field_date == '' ) {
                        wp_unschedule_event( $next_date, '_shopxpert_pre_order_schedule_date_cron', array( $post_id ) );
                    }
                } else {
                    if ( $get_field_date ) {
                        wp_schedule_single_event( $get_field_date, '_shopxpert_pre_order_schedule_date_cron', array( $post_id ) );
                    }
                }


            }

        }else{

            delete_post_meta( $post_id, '_selectproduct_layout' );
			delete_post_meta( $post_id, 'shopxpert_cart_custom_content' );

            // Partial Payment
            delete_post_meta( $post_id, 'shopxpert_partial_payment_enable' );
            delete_post_meta( $post_id, 'shopxpert_partial_payment_amount_type' );
            delete_post_meta( $post_id, 'shopxpert_partial_payment_amount' );

            // Pre Orders
            delete_post_meta ( $post_id, 'shopxpert_pre_order_enable' );
            delete_post_meta ( $post_id, 'shopxpert_pre_order_available_date' );
            delete_post_meta ( $post_id, 'shopxpert_pre_order_manage_price' );
            delete_post_meta ( $post_id, 'shopxpert_pre_order_amount_type' );
            delete_post_meta ( $post_id, 'shopxpert_pre_order_amount' );

			return false;
        }

    }

    /**
     * Time calculate
     *
     * @param [string] $time
     * @return void
     */
    public static function time_to_second( $time ) {
		if ( ! $time ) {
			return 0;
		}
		$temp = explode( ":", $time );
		if ( count( $temp ) == 2 ) {
			return ( absint( $temp[0] ) * 3600 + absint( $temp[1] ) * 60 );
		} else {
			return 0;
		}
	}

    /**
     * get save data
     *
     * @param [int] $post_id
     * @param [string] $meta_key
     * @param [string] $option_key
     * @param string $default
     * @return void
     */
    public function get_saved_data( $post_id, $meta_key, $option_key, $option_section, $default = '' ) {
		$amount_type = get_post_meta( $post_id, $meta_key, true );

		if ( ! $amount_type ) {
			$amount_type = shopxpert_get_option( $option_key, $option_section, $default );
		}

		return $amount_type;
	}

    /**
     * Add field in new category add screen
     *
     * @return void
     */
    public function taxonomy_add_new_meta_field(){
        ?>
        <div class="form-field term-group">
            <label for="wooletor_selectcategory_layout"><?php esc_html_e('Category Layout', 'shopxpert'); ?></label>
            <select class="postform" id="equipment-group" name="wooletor_selectcategory_layout">

                <?php
                    $shopxpert_templates = [];
                    if( function_exists( 'shopxpert_wltemplate_list' ) ){
                        $shopxpert_templates = shopxpert_wltemplate_list( array('shop','archive') );
                    }

                    if( !empty( $shopxpert_templates ) ){
                        echo '<optgroup label="'.esc_attr('ShopXpert').'">';
                        foreach ( $shopxpert_templates as $template_key => $template ) {
                            echo '<option value="'.esc_attr( $template_key ).'">'.esc_html__( $template, 'shopxpert' ).'</option>';
                        }
                        echo '</optgroup>';
                    }
                ?>

            </select>
        </div>
        <?php
    }

    /**
     * Add field in category edit screen
     *
     * @return void
     */
    public function taxonomy_edit_meta_field( $term ){
        //getting term ID
        $term_id = $term->term_id;

        // retrieve the existing value(s) for this meta field.
        $category_layout = get_term_meta( $term_id, 'wooletor_selectcategory_layout', true);

        ?>
            <tr class="form-field">
                <th scope="row" valign="top"><label for="wooletor_selectcategory_layout"><?php esc_html_e( 'Category Layout', 'shopxpert' ); ?></label></th>
                <td>
                    <select class="postform" id="wooletor_selectcategory_layout" name="wooletor_selectcategory_layout">
                        <?php
                            $shopxpert_templates = [];
                            if( function_exists( 'shopxpert_wltemplate_list' ) ){
                                $shopxpert_templates = shopxpert_wltemplate_list( array('shop','archive') );
                            }

                            if( !empty( $shopxpert_templates ) ){
                                echo '<optgroup label="'.esc_attr('ShopXpert').'">';
                                foreach ( $shopxpert_templates as $template_key => $template ) {
                                    echo '<option value="'.esc_attr( $template_key ).'" '.selected( $category_layout, $template_key, false ).'>'.esc_html__( $template, 'shopxpert' ).'</option>';
                                }
                                echo '</optgroup>';
                            }
                        ?>
                    </select>
                </td>
            </tr>
        <?php
    }

    /**
     * Data extra taxonomy field data
     *
     * @return void
     */
    public function save_taxonomy_custom_meta( $term_id ) {
        $shopxpert_categorylayout = filter_input( INPUT_POST, 'wooletor_selectcategory_layout' );
        update_term_meta( $term_id, 'wooletor_selectcategory_layout', $shopxpert_categorylayout );
    }

}

Shopxpert_Custom_Meta_Fields::get_instance();