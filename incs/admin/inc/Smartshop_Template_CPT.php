<?php  

namespace Smartshop\Incs\Admin\Inc;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



class Smartshop_Template_CPT{

    const CPTTYPE = 'smartshop-template';

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
			'name'                  => esc_html_x('Template Builder', 'Post Type General Name', 'shopxper'),
			'singular_name'         => esc_html_x('Template Builder', 'Post Type Singular Name', 'shopxper'),
			'menu_name'             => esc_html__('Template', 'shopxper'),
			'name_admin_bar'        => esc_html__('Template', 'shopxper'),
			'archives'              => esc_html__('Template Archives', 'shopxper'),
			'attributes'            => esc_html__('Template Attributes', 'shopxper'),
			'parent_item_colon'     => esc_html__('Parent Item:', 'shopxper'),
			'all_items'             => esc_html__('Templates', 'shopxper'),
			'add_new_item'          => esc_html__('Add New Template', 'shopxper'),
			'add_new'               => esc_html__('Add New', 'shopxper'),
			'new_item'              => esc_html__('New Template', 'shopxper'),
			'edit_item'             => esc_html__('Edit Template', 'shopxper'),
			'update_item'           => esc_html__('Update Template', 'shopxper'),
			'view_item'             => esc_html__('View Template', 'shopxper'),
			'view_items'            => esc_html__('View Templates', 'shopxper'),
			'search_items'          => esc_html__('Search Templates', 'shopxper'),
			'not_found'             => esc_html__('Not found', 'shopxper'),
			'not_found_in_trash'    => esc_html__('Not found in Trash', 'shopxper'),
			'featured_image'        => esc_html__('Featured Image', 'shopxper'),
			'set_featured_image'    => esc_html__('Set featured image', 'shopxper'),
			'remove_featured_image' => esc_html__('Remove featured image', 'shopxper'),
			'use_featured_image'    => esc_html__('Use as featured image', 'shopxper'),
			'insert_into_item'      => esc_html__('Insert into Template', 'shopxper'),
			'uploaded_to_this_item' => esc_html__('Uploaded to this Template', 'shopxper'),
			'items_list'            => esc_html__('Templates list', 'shopxper'),
			'items_list_navigation' => esc_html__('Templates list navigation', 'shopxper'),
			'filter_items_list'     => esc_html__('Filter from list', 'shopxper'),
		);

		$args = array(
			'label'               => esc_html__('Template Builder', 'shopxper'),
			'description'         => esc_html__('SMARTSHOP Template', 'shopxper'),
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
				'slug'       => 'smartshop-template',
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
        if( get_option('smartshop_plugin_permalinks_flushed', TRUE ) !== 'yes' ) {
            flush_rewrite_rules();
            update_option( 'smartshop_plugin_permalinks_flushed', 'yes' );
        }
    }

	/**
	 * Register Metaboxes
	 *
	 * @return void
	 */
	public function register_post_meta_field() {

		// Get Default Value from Global Option
		$default_width = function_exists( 'smartshop_get_option' ) ? (int)smartshop_get_option( 'container_width', 'smartshop_gutenberg_tabs', 1140 ) : 1140;

		// Meta Field for Container Width
		register_post_meta( self::CPTTYPE, '_smartshop_container_width',
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