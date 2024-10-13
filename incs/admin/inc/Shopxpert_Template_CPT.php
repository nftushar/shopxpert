<?php  

namespace Shopxpert\Incs\Admin\Inc;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



class Shopxpert_Template_CPT{

    const CPTTYPE = 'shopxpert-template';

    private static $_instance = null;
    public static function instance(){
        if( is_null( self::$_instance ) ){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

	/**
	 * Class Constructor
	 */
    function __construct(){
        add_action( 'init', [ $this, 'init' ] );
    }

	/**
	 * Initial Function
	 *
	 * @return void
	 */
	public function init(){
		//Register Custom Post Type
		$this->register_custom_post_type();
		// Register Custom Meta Field
		$this->register_post_meta_field();
		// Flash rewrite rules
		$this->flush_rewrite_rules();
	}

	/**
	 * Register Builder Custom post
	 *
	 * @return void
	 */
    public function register_custom_post_type() {

		$labels = array(
			'name'                  => esc_html_x('Template Builder', 'Post Type General Name', 'shopxpert'),
			'singular_name'         => esc_html_x('Template Builder', 'Post Type Singular Name', 'shopxpert'),
			'menu_name'             => esc_html__('Template', 'shopxpert'),
			'name_admin_bar'        => esc_html__('Template', 'shopxpert'),
			'archives'              => esc_html__('Template Archives', 'shopxpert'),
			'attributes'            => esc_html__('Template Attributes', 'shopxpert'),
			'parent_item_colon'     => esc_html__('Parent Item:', 'shopxpert'),
			'all_items'             => esc_html__('Templates', 'shopxpert'),
			'add_new_item'          => esc_html__('Add New Template', 'shopxpert'),
			'add_new'               => esc_html__('Add New', 'shopxpert'),
			'new_item'              => esc_html__('New Template', 'shopxpert'),
			'edit_item'             => esc_html__('Edit Template', 'shopxpert'),
			'update_item'           => esc_html__('Update Template', 'shopxpert'),
			'view_item'             => esc_html__('View Template', 'shopxpert'),
			'view_items'            => esc_html__('View Templates', 'shopxpert'),
			'search_items'          => esc_html__('Search Templates', 'shopxpert'),
			'not_found'             => esc_html__('Not found', 'shopxpert'),
			'not_found_in_trash'    => esc_html__('Not found in Trash', 'shopxpert'),
			'featured_image'        => esc_html__('Featured Image', 'shopxpert'),
			'set_featured_image'    => esc_html__('Set featured image', 'shopxpert'),
			'remove_featured_image' => esc_html__('Remove featured image', 'shopxpert'),
			'use_featured_image'    => esc_html__('Use as featured image', 'shopxpert'),
			'insert_into_item'      => esc_html__('Insert into Template', 'shopxpert'),
			'uploaded_to_this_item' => esc_html__('Uploaded to this Template', 'shopxpert'),
			'items_list'            => esc_html__('Templates list', 'shopxpert'),
			'items_list_navigation' => esc_html__('Templates list navigation', 'shopxpert'),
			'filter_items_list'     => esc_html__('Filter from list', 'shopxpert'),
		);

		$args = array(
			'label'               => esc_html__('Template Builder', 'shopxpert'),
			'description'         => esc_html__('SHOPXPERT Template', 'shopxpert'),
			'labels'              => $labels,
			'supports'            => array('title', 'editor', 'elementor', 'author', 'permalink', 'custom-fields'),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => false,
			'rewrite'             => array(
				'slug'       => 'shopxpert-template',
				'pages'      => false,
				'with_front' => true,
				'feeds'      => false,
			),
			'query_var'           => true,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
			'show_in_rest'        => true,
			'rest_base'           => self::CPTTYPE,
		);

		register_post_type( self::CPTTYPE, $args );

	}

	/**
     * [flush_rewrite_rules] Flash rewrite rules
     * @return [void]
     */
    public function flush_rewrite_rules() {
        if( get_option('shopxpert_plugin_permalinks_flushed', TRUE ) !== 'yes' ) {
            flush_rewrite_rules();
            update_option( 'shopxpert_plugin_permalinks_flushed', 'yes' );
        }
    }

	/**
	 * Register Metaboxes
	 *
	 * @return void
	 */
	public function register_post_meta_field() {

		// Get Default Value from Global Option
		$default_width = function_exists( 'shopxpert_get_option' ) ? (int)shopxpert_get_option( 'container_width', 'shopxpert_gutenberg_tabs', 1140 ) : 1140;

		// Meta Field for Container Width
		register_post_meta( self::CPTTYPE, '_shopxpert_container_width',
			[
				'show_in_rest' 	=> true,
				'single' 		=> true,
				'type' 			=> 'number',
				'default' 		=> $default_width,
				'auth_callback' => function() {
					return current_user_can( 'edit_posts' );
				}
			] 
		);

	}

}