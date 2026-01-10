<?php
namespace WishList;
/**
 * Ajax handlers class
 */
class Ajax {

    /**
     * [$_instance]
     * @var null
     */
    private static $_instance = null;

    /**
     * [instance] Initializes a singleton instance
     * @return [Ajax]
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    /**
     * Initialize the class
     */
    private function __construct() {

        // Add Ajax Callback
        add_action( 'wp_ajax_wishlist_add_to_list', [ $this, 'add_to_wishlist' ] );
        add_action( 'wp_ajax_nopriv_wishlist_add_to_list', [ $this, 'add_to_wishlist' ] );

        // Remove Ajax Callback
        add_action( 'wp_ajax_wishlist_remove_from_list', [ $this, 'remove_wishlist' ] );
        add_action( 'wp_ajax_nopriv_wishlist_remove_from_list', [ $this, 'remove_wishlist' ] );

        // Variation Quick cart Form Ajax Callback
        add_action( 'wp_ajax_wishlist_quick_variation_form', [ $this, 'variation_form_html' ] );
        add_action( 'wp_ajax_nopriv_wishlist_quick_variation_form', [ $this, 'variation_form_html' ] );

        // For Add to cart
        add_action( 'wp_ajax_wishlist_insert_to_cart', [ $this, 'insert_to_cart' ] );
        add_action( 'wp_ajax_nopriv_wishlist_insert_to_cart', [ $this, 'insert_to_cart' ] );

    }

    /**
     * [add_to_wishlist] Product add ajax callback
     */
    public function add_to_wishlist(){
            if ( ! isset( $_GET['nonce'] ) || ! wp_verify_nonce( wp_unslash( $_GET['nonce'] ), 'wishList_nonce' ) ) {
            $errormessage = array(
                'message'  => __('Nonce Varification Faild !','shopxpert')
            );
            wp_send_json_error( $errormessage );
        }
        $id = sanitize_text_field( $_GET['id'] );
        $inserted = \WishList\Frontend\Manage_Wishlist::instance()->add_product( $id );
        if ( ! $inserted ) {
            wp_send_json_success([
                'message' => __( 'The product does not add!', 'shopxpert' )
            ]);
        }else{
            wp_send_json_success([
                'item_count' => count( \WishList\Frontend\Manage_Wishlist::instance()->get_products_data() ),
                'message' => __( 'Product successfully added!', 'shopxpert' )
            ]);
        }

    }

    /**
     * [remove_wishlist] Product delete ajax callback
     * @return [void]
     */
    public function remove_wishlist(){
        if ( ! isset( $_GET['nonce'] ) || ! wp_verify_nonce( wp_unslash( $_GET['nonce'] ),  'wishList_nonce' ) ){
            $errormessage = array(
                'message'  => __('Nonce Varification Faild !','shopxpert')
            );
            wp_send_json_error( $errormessage );
        }
        $id = sanitize_text_field( $_GET['id'] );
        $deleted = \WishList\Frontend\Manage_Wishlist::instance()->remove_product( $id );
        if ( ! $deleted ) {
            wp_send_json_success([
                'message' => __( 'The product does not delete!', 'shopxpert' )
            ]);
        }else{
            wp_send_json_success([
                'item_count' => count( \WishList\Frontend\Manage_Wishlist::instance()->get_products_data() ),
                'per_page' => (int)WishList_get_option( 'wishlist_product_per_page', 'wishlist_table_settings_tabs', 20 ),
                'message' => __( 'Product successfully deleted!', 'shopxpert' )
            ]);
        }

    }

    /**
     * [variation_form_html]
     * @param  boolean $id product id
     * @return [void]
     */
    public function variation_form_html( $id = 0 ) {

        // Nonce check
        if (
            empty( $_POST['nonce'] ) ||
            ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'wishList_nonce' )
        ) {
            wp_send_json_error(
                [
                    'message' => __( 'Nonce verification failed!', 'shopxpert' ),
                ]
            );
        }

        // Product ID
        if ( ! empty( $_POST['id'] ) ) {
            $id = absint( $_POST['id'] );
        }

        if ( ! $id || ! class_exists( 'WooCommerce' ) ) {
            wp_die();
        }

        global $post;

        $post = get_post( $id );

        if ( ! $post || 'product' !== $post->post_type ) {
            wp_die();
        }

        setup_postdata( $post );

        woocommerce_template_single_add_to_cart();

        wp_reset_postdata();

        wp_die();
    }


    /**
     * [insert_to_cart] Insert add to cart
     * @return [JSON]
     */
    public function insert_to_cart(){

        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( wp_unslash( $_GET['nonce'] ),  'wishList_nonce' ) ){
            $errormessage = array(
                'message'  => __('Nonce Varification Faild !','shopxpert')
            );
            wp_send_json_error( $errormessage );
        }

        // phpcs:disable WordPress.Security.NonceVerification.Missing
        if ( ! isset( $_POST['product_id'] ) ) {
            return;
        }

        $product_id         = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
        $quantity           = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( wp_unslash( $_POST['quantity'] ) );
        $variation_id       = !empty( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0;
        $variations         = !empty( $_POST['variations'] ) ? array_map( 'sanitize_text_field', $_POST['variations'] ) : array();
        $passed_validation  = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity, $variation_id, $variations );
        $product_status     = get_post_status( $product_id );

        if ( $passed_validation && \WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variations ) && 'publish' === $product_status ) {
            do_action( 'woocommerce_ajax_added_to_cart', $product_id );
            if ( 'yes' === get_option('woocommerce_cart_redirect_after_add') ) {
                wc_add_to_cart_message( array( $product_id => $quantity ), true );
            }
            \WC_AJAX::get_refreshed_fragments();
        } else {
            $data = array(
                'error' => true,
                'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id ),
            );
            wp_send_json_error( $data );
        }
        wp_send_json_success();
        
    }


}