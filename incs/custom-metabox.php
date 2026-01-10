<?php  

namespace WishList\Admin;
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

        // Log product ID
        error_log( 'ShopXpert Product ID: ' . $post->ID );

        // Single product layout field
        echo '<div id="shopxpert_product_data" class="panel woocommerce_options_panel hidden">';

            // Product Layout Field
            echo '<div class="options_group">';
                $value = get_post_meta( $post->ID, '_selectproduct_layout', true );
                if( empty( $value ) ) $value = '0';

                // Log selected layout
                error_log( 'Selected Product Layout: ' . $value );
                
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

                // Log cart custom content
                $cart_content = get_post_meta( $post->ID, 'shopxpert_cart_custom_content', true );
                error_log( 'Cart Custom Content: ' . $cart_content );
            echo '</div>';

            // Partial Payment
            $partial_enabled = shopxpert_get_option( 'enable', 'shopxpert_partial_payment_settings', 'off' );
            error_log( 'Partial Payment Option: ' . $partial_enabled );

            if( $partial_enabled === 'on' ){
                $enable_status = get_post_meta( $post->ID, 'shopxpert_partial_payment_enable', true );
                error_log( 'Partial Payment Enabled (Post Meta): ' . $enable_status );

                $display_field = $enable_status === 'yes' ? 'shopxpert-hidden-field' : 'shopxpert-hidden-field hidden';

                echo '<div class="options_group">';
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
                            'fixedamount' => esc_html__('Fixed Amount','shopxpert'),
                            'percentage'  => esc_html__('Percentage','shopxpert'),
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
                echo '</div>';
            }

            // Pre Orders
            $pre_order_enabled = shopxpert_get_option( 'enable', 'shopxpert_pre_order_settings', 'off' );
            if ( $pre_order_enabled === 'off' ) {
                $pre_order_enabled = shopxpert_get_option( 'enablerpreorder', 'shopxpert_pre_order_settings', 'off' );
            }
            error_log( 'Pre Order Option: ' . $pre_order_enabled );

            if( $pre_order_enabled == 'on' ){
                $enable_pre_order = get_post_meta( $post->ID, 'shopxpert_pre_order_enable', true );
                error_log( 'Pre Order Enabled (Post Meta): ' . $enable_pre_order );
            }

        echo '</div>';
    }


    /**
     * save_shopxpert_product_meta custom tab data save
     *
     * @return void
     */
     public function save_shopxpert_product_meta( $post_id ){

        if ( isset($_POST['woocommerce_meta_nonce']) && wp_verify_nonce($_POST['woocommerce_meta_nonce'], 'woocommerce_save_data') ) {

            // Single Product Layout
            $selectproduct_layout = !empty( $_POST['_selectproduct_layout'] ) ? sanitize_text_field( $_POST['_selectproduct_layout'] ) : '';
            update_post_meta( $post_id, '_selectproduct_layout', $selectproduct_layout );
            error_log( 'Saved Product Layout: ' . $selectproduct_layout );

            // Cart Content
            $selectproduct_cart_content = !empty( $_POST['shopxpert_cart_custom_content'] ) ? wp_kses_post( $_POST['shopxpert_cart_custom_content'] ) : '';
            update_post_meta( $post_id, 'shopxpert_cart_custom_content', $selectproduct_cart_content );
            error_log( 'Saved Cart Content: ' . $selectproduct_cart_content );

            // Partial Payment
            $partial_enabled = shopxpert_get_option( 'enable', 'shopxpert_partial_payment_settings', 'off' );
            error_log( 'Partial Payment Option (Save): ' . $partial_enabled );

            if( $partial_enabled === 'on' ){
                $status = !empty( $_POST['shopxpert_partial_payment_enable'] ) ? sanitize_text_field( $_POST['shopxpert_partial_payment_enable'] ) : '';
                update_post_meta( $post_id, 'shopxpert_partial_payment_enable', $status );
                error_log( 'Saved Partial Payment Enable: ' . $status );

                $amount_type = !empty( $_POST['shopxpert_partial_payment_amount_type'] ) ? sanitize_text_field( $_POST['shopxpert_partial_payment_amount_type'] ) : '';
                update_post_meta( $post_id, 'shopxpert_partial_payment_amount_type', $amount_type );
                error_log( 'Saved Partial Payment Amount Type: ' . $amount_type );

                $amount = !empty( $_POST['shopxpert_partial_payment_amount'] ) ? sanitize_text_field( $_POST['shopxpert_partial_payment_amount'] ) : '';
                update_post_meta( $post_id, 'shopxpert_partial_payment_amount', $amount );
                error_log( 'Saved Partial Payment Amount: ' . $amount );
            }

            // Pre Order
            $pre_order_enabled = shopxpert_get_option( 'enable', 'shopxpert_pre_order_settings', 'off' );
            if ( $pre_order_enabled === 'off' ) {
                $pre_order_enabled = shopxpert_get_option( 'enablerpreorder', 'shopxpert_pre_order_settings', 'off' );
            }
            error_log( 'Pre Order Option (Save): ' . $pre_order_enabled );

            if( $pre_order_enabled == 'on' ){
                $pre_order_status = !empty( $_POST['shopxpert_pre_order_enable'] ) ? sanitize_text_field( $_POST['shopxpert_pre_order_enable'] ) : '';
                update_post_meta( $post_id, 'shopxpert_pre_order_enable', $pre_order_status );
                error_log( 'Saved Pre Order Enable: ' . $pre_order_status );
            }

        } else {
            error_log( 'ShopXpert: Nonce failed, data not saved.' );
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
            <label for="shopxpert_selectcategory_layout"><?php esc_html_e('Category Layout', 'shopxpert'); ?></label>
            <select class="postform" id="equipment-group" name="shopxpert_selectcategory_layout">

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
        $category_layout = get_term_meta( $term_id, 'shopxpert_selectcategory_layout', true);

        ?>
            <tr class="form-field">
                <th scope="row" valign="top"><label for="shopxpert_selectcategory_layout"><?php esc_html_e( 'Category Layout', 'shopxpert' ); ?></label></th>
                <td>
                    <select class="postform" id="shopxpert_selectcategory_layout" name="shopxpert_selectcategory_layout">
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
        $shopxpert_categorylayout = filter_input( INPUT_POST, 'shopxpert_selectcategory_layout' );
        update_term_meta( $term_id, 'shopxpert_selectcategory_layout', $shopxpert_categorylayout );
    }

}

Shopxpert_Custom_Meta_Fields::get_instance();