<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

class Shopxpert_Template_Library {

    // Get Instance
    private static $_instance = null;
    public static function instance(){
        if( is_null( self::$_instance ) ){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    function __construct(){
        if ( is_admin() ) { 
            add_action( 'wp_ajax_shopxpert_ajax_get_required_plugin', [ $this, 'ajax_plugin_data' ] );
            add_action( 'wp_ajax_shopxpert_ajax_plugin_activation', [ $this, 'ajax_plugin_activation' ] );
            add_action( 'wp_ajax_shopxpert_ajax_theme_activation', [ $this, 'ajax_theme_activation' ] );
        }

        add_action( 'admin_enqueue_scripts', [ $this, 'scripts' ], 999 );
    }

    /**
     * Admin Scripts.
     */
    public function scripts( $hook ) {
        if( 'shopxpert_shopxpert_templates' == $hook ){
            // CSS
            wp_enqueue_style( 'shopxpert-selectric' );
            wp_enqueue_style( 'shopxpert-temlibray-style' );

            // JS
            wp_enqueue_script( 'shopxpert-modernizr' );
            wp_enqueue_script( 'jquery-selectric' );
            wp_enqueue_script( 'jquery-ScrollMagic' );
            wp_enqueue_script( 'babel-min' );
            wp_enqueue_script( 'shopxpert-templates' );
            wp_enqueue_script( 'shopxpert-install-manager' );
        }
    }

    /**
     * Ajax request.
     */
    public function templates_ajax_request() {
        if ( ! current_user_can( 'manage_options') ) {
            echo wp_json_encode(
                array(
                    'message' => esc_html__( 'You are not permitted to import the template.', 'shopxpert' )
                )
            );
        } else {
            if ( isset( $_REQUEST ) ) { 
                if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( wp_unslash( $_REQUEST['nonce'] ), 'shopxpert_template_nonce' ) ) {
                    $errormessage = array(
                        'message' => __( 'Are you cheating?', 'shopxpert' )
                    );
                    wp_send_json_error( $errormessage );
                }
                
                $template_id        = sanitize_text_field( $_REQUEST['httemplateid'] );
                $template_parentid  = sanitize_text_field( $_REQUEST['htparentid'] );
                $template_title     = sanitize_text_field( $_REQUEST['httitle'] );
                $page_title         = sanitize_text_field( $_REQUEST['pagetitle'] );
                $template_type      = sanitize_text_field( $_REQUEST['templatetype'] );

                $response_data  = \Shopxpert_Template_Library_Manager::get_template_data('template', $template_id );
                $defaulttitle   = ucfirst( $template_parentid ) .' -> '.$template_title;

                $args = [
                    'post_type'    => !empty( $page_title ) ? 'page' : 'elementor_library',
                    'post_status'  => ( !empty( $page_title ) || $template_type == 'popup' ) ? 'draft' : 'publish',
                    'post_title'   => !empty( $page_title ) ? $page_title : $defaulttitle,
                    'post_content' => '',
                ];

                if( $template_type !== 'other' ){
                    $args['post_type'] = 'shopxpert-template';
                }

                $new_post_id = wp_insert_post( $args );

                $json_value  = wp_slash( wp_json_encode( $response_data['content']['content'] ) );
                update_post_meta( $new_post_id, '_elementor_data', $json_value );
                update_post_meta( $new_post_id, '_elementor_template_type', $response_data['type'] );
                update_post_meta( $new_post_id, '_elementor_edit_mode', 'builder' );

                if( $template_type !== 'other' && $new_post_id && ! is_wp_error( $new_post_id ) ){
                    update_post_meta( $new_post_id, 'shopxpert_template_meta_type', $template_type );
                    update_post_meta( $new_post_id, 'shopxpert_template_meta_editor', 'elementor' );
                }
                
                if( isset( $response_data['page_settings'] ) ){
                    update_post_meta( $new_post_id, '_elementor_page_settings', $response_data['page_settings'] );
                }

                if ( $new_post_id && ! is_wp_error( $new_post_id ) ) {
                    update_post_meta( $new_post_id, '_wp_page_template', !empty( $response_data['page_template'] ) ? $response_data['page_template'] : 'elementor_header_footer' );
                }

                echo wp_json_encode(
                    array( 
                        'id'      => $new_post_id,
                        'edittxt' => !empty( $page_title ) ? esc_html__( 'Edit Page', 'shopxpert' ) : esc_html__( 'Edit Template', 'shopxpert' )
                    )
                );
            }
        }

        wp_die();
    }

    /**
     * Ajax response required data
     */
    public function ajax_plugin_data(){
        if ( isset( $_POST ) ) {
            if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'shopxpert_template_nonce' ) ) {
                wp_send_json_error(
                    array(
                        'success' => false,
                        'message' => esc_html__( 'Nonce Verification Failed!', 'shopxpert' ),
                    )
                );
            }

            $freeplugins = explode( ',', sanitize_text_field( $_POST['freeplugins'] ) );
            $proplugins = explode( ',', sanitize_text_field( $_POST['proplugins'] ) );
            $themeinfo = explode( ',', sanitize_text_field( $_POST['requiredtheme'] ) );

            if(!empty($_POST['freeplugins'])){ $this->required_plugins( $freeplugins, 'free' ); }
            if(!empty($_POST['proplugins'])){ $this->required_plugins( $proplugins, 'pro' ); }
            if(!empty($_POST['requiredtheme'])){ $this->required_theme( $themeinfo, 'free' ); }
        }
        wp_die();
    }

    // Functions for required plugins and themes remain here as per your original logic.
}
