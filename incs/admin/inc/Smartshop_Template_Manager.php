<?php  
namespace Smartshop\Incs\Admin\Inc;

use Smartshop\Incs\Admin\Inc\Smartshop_Template_CPT;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// require( SMARTSHOP_ADDONS_PL_PATH. 'incs/admin/inc/class.template_cpt.php' );
// require( SMARTSHOP_ADDONS_PL_PATH. 'incs/admin/inc/template-library/manager.php' );

class Smartshop_Template_Manager{

    const CPTTYPE = 'smartshop-template';
	const CPT_META = 'smartshop_template_meta';
	public static $language_code = '';

    private static $_instance = null;
    public static function instance(){
        if( is_null( self::$_instance ) ){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    function __construct(){
		Smartshop_Template_CPT::instance();

		add_action('wp_loaded', function(){
			self::$language_code = apply_filters('smartshop_current_language_code', 'en');
		} );

        //Add Menu
        add_action( 'admin_menu', [ $this, 'admin_menu' ], 225 );

        // Load Scripts
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

        // Print template edit popup.
		add_action( 'admin_footer', [ $this, 'print_popup' ] );

		// Template type column.
		add_action( 'manage_' . self::CPTTYPE . '_posts_columns', [ $this, 'manage_columns' ] );
		add_action( 'manage_' . self::CPTTYPE . '_posts_custom_column', [ $this, 'columns_content' ], 10, 2 );

        // Print template tabs.
		add_filter( 'views_edit-' . self::CPTTYPE, [ $this, 'print_tabs' ] );

		// query filter
		add_filter( 'parse_query', [ $this, 'query_filter' ] );
		
		// Post Row Action
		add_filter( 'post_row_actions', [ $this, 'row_actions' ], 10, 2 );

		add_filter('theme_'.self::CPTTYPE.'_templates', [ $this, 'add_page_templates' ], 999, 4 );

        // Template store ajax action
		add_action( 'wp_ajax_smartshop_template_store', [ $this, 'template_store_request' ] );

		// Get template data Ajax action
		add_action( 'wp_ajax_smartshop_get_template', [ $this, 'get_post_By_id' ] );

		// Manage Template Default Status
		add_action( 'wp_ajax_smartshop_manage_default_template', [ $this, 'manage_template_status' ] );

		// Template Import
		add_action( 'wp_ajax_smartshop_import_template', [ $this, 'template_import' ] );
		
    }

    /**
	 * [admin_menu] Add Post type Submenu
	 *
	 * @return void
	 */
    public function admin_menu(){
        $link_custom_post = 'edit.php?post_type=' . self::CPTTYPE;
		add_submenu_page(
			'smartshop_page',
			esc_html__('Template Builder', 'smartshop'),
			esc_html__('Template Builder', 'smartshop'),
			'manage_options',
			$link_custom_post,
            NULL
		);
    }

	/**
	 * Manage Post Table columns
	 *
	 * @param [array] $columns
	 * @return array
	 */
	public function manage_columns( $columns ) {

		$column_author  = $columns['author'];
		$column_date 	= $columns['date'];

		unset( $columns['date'] );
		unset( $columns['author'] );

		$columns['type'] 		= esc_html__('Type', 'smartshop');
		$columns['setdefault'] 	= esc_html__('Default', 'smartshop');
		$columns['author'] 		= esc_html( $column_author );
		$columns['date'] 		= esc_html( $column_date );

		return $columns;
	}

	/**
	 * Manage Custom column content
	 *
	 * @param [string] $column_name
	 * @param [int] $post_id
	 * @return void
	 */
	public function columns_content( $column_name, $post_id ) {
		$tmpType = get_post_meta( $post_id, 'smartshop_template_meta_type', true );

		if( !array_key_exists( $tmpType, self::get_template_type() ) ){
			return;
		}

		// Tabs Group
		if( strpos( $tmpType, 'cart' ) !== false ){
			$tmpTypeGroup = 'cart';
		}else if( strpos( $tmpType, 'myaccount' ) !== false ){
			$tmpTypeGroup = 'myaccount';
		}else if( strpos( $tmpType, 'checkout' ) !== false ){
			$tmpTypeGroup = 'checkout';
		}else{
			$tmpTypeGroup = $tmpType;
		}

		if( $column_name === 'type' ){
			$tabs = '';
			echo isset( self::get_template_type()[$tmpType] ) ? '<a class="column-tmptype" href="edit.php?post_type='.self::CPTTYPE.'&template_type='.$tmpType.'&tabs='.$tmpTypeGroup.'">'.self::get_template_type()[$tmpType]['label'].'</a>' : '-'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}elseif( $column_name === 'setdefault' ){

			$value = $this->get_template_id( self::get_template_type()[$tmpType]['optionkey'] );
			$checked = checked( $value, $post_id, false );

			echo '<label class="smartshop-default-tmp-status-switch" id="smartshop-default-tmp-status-'.esc_attr( $tmpType ).'-'.esc_attr( $post_id ).'"><input class="smartshop-status-'.esc_attr( $tmpType ).'" id="smartshop-default-tmp-status-'.esc_attr( $tmpType ).'-'.esc_attr( $post_id ).'" type="checkbox" value="'.esc_attr( $post_id ).'" '.$checked.'/><span><span>'.esc_html__('NO','smartshop').'</span><span>'.esc_html__('YES','smartshop').'</span></span><a>&nbsp;</a></label>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		}

	}

	/**
	 * Check SMARTSHOP template screen
	 *
	 * @return boolean
	 */
	private function is_current_screen() {
		global $pagenow, $typenow;
		return 'edit.php' === $pagenow && self::CPTTYPE === $typenow;
	}

	/**
	 * Manage Template filter by template type
	 *
	 * @param \WP_Query $query
	 * @return void
	 */
	public function query_filter( \WP_Query $query ) {
		if ( ! is_admin() || ! $this->is_current_screen() || ! empty( $query->query_vars['meta_key'] ) ) {
			return;
		}

		if( isset( $_GET['template_type'] ) && $_GET['template_type'] != '' && $_GET['template_type'] != 'all') {
			$type = isset( $_GET['template_type'] ) ? sanitize_key( $_GET['template_type'] ) : '';

			if ( ( 'emails' === $type ) && ( 'on' == smartshop_get_option( 'enable', 'smartshop_email_customizer_settings', 'off' ) ) ) {
				$emails = smartshop_wc_get_emails( 'id' );
				$emails = array_map( function ( $id ) { return 'email_' . $id; }, $emails );

				$meta_query = [
					'relation' => 'OR'
				];

				foreach ( $emails as $email ) {
					$meta_query[] = [
			            'key'     => self::CPT_META . '_type',
			            'value'   => $email,
			            'compare' => '=',
			        ];
				}
				$query->query_vars['meta_query'] = $meta_query;

			} else {
				$query->query_vars['meta_key']     = self::CPT_META . '_type';
				$query->query_vars['meta_value']   = $type;
				$query->query_vars['meta_compare'] = '=';
			}
		} else {
			// Meta query to fetch only the posts that doest not containt the meta key '_smartshop_order_bump'
			$query->query_vars['meta_query'] = [
				[
					'key'     => '_smartshop_order_bump',
					'compare' => 'NOT EXISTS',	
				],
			];
		}
	}

	/**
	 * Manage Row Action
	 *
	 * @param [array] $actions
	 * @param [object] $post
	 * @return array
	 */
	public function row_actions( $actions, $post ) {
        if ( $post->post_type == self::CPTTYPE ) {

			if( $this->edit_with_gutenberg( $post->ID ) ) {
				$actions['smartshop_edit_with_gutenberg'] = '<a href="'.get_edit_post_link($post->ID).'">'.esc_html__('Edit With Gutenberg', 'smartshop').'</a>';
			}

        }
        return $actions;
    }

	/**
	 * Add page templates.
	 *
	 * Fired by `theme_self::CPTTYPE_templates` filter.
	 *
	 * @param array $page_templates Array of page templates. Keys are filenames,
	 *
	 * @param \WP_Theme $wp_theme
	 * @param \WP_Post $post
	 *
	 * @return array Page templates.
	 */
	public function add_page_templates( $page_templates, $wp_theme, $post ){
		unset( $page_templates['elementor_theme'] );

		$page_templates['smartshop_canvas']    = esc_html__('SmartShop Canvas', 'smartshop');
		$page_templates['smartshop_fullwidth'] = esc_html__('SmartShop Full width', 'smartshop');

		return $page_templates;
	}

	/**
	 * Check edit with gutenberg
	 *
	 * @param [ind] $id
	 * @return boolean
	 */
	public function edit_with_gutenberg( $id ){
		$editor = get_post_meta( $id, 'smartshop_template_meta_editor', true );
		if( ! empty( $editor ) && $editor === 'gutenberg' ){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * Get Template Menu Tabs
	 *
	 * @return array
	 */
	public static function get_tabs(){

		$tabs = [
			'shop' => [
				'label' =>__('Shop','smartshop')
			],
			'archive' => [
				'label' =>__('Archive','smartshop')
			],
			'single' => [
				'label' => __('Single','smartshop')
			],
		];
		return apply_filters( 'smartshop_template_menu_tabs', $tabs );

	}

    /**
	 * Get Template Type
	 *
	 * @return array
	 */
	public static function get_template_type(){

		$template_type = [
			'shop' 	=> [
				'label'		=>__('Shop','smartshop'),
				'optionkey'	=> 'productarchivepage'
			],
			'archive' => [
				'label'		=>__('Archive','smartshop'),
				'optionkey'	=>'productallarchivepage'
			],
			'single' => [
				'label' 	=> __('Single','smartshop'),
				'optionkey' => 'singleproductpage'
			],
		];
		return apply_filters( 'smartshop_template_types', $template_type );

	}

	/**
	 * Get sample design from library
	 *
	 * @return array
	 */
	public function get_template_library(){

		// Delete transient data
		if ( get_option( 'smartshop_do_activation_library_cache', FALSE ) ) {
            delete_transient( 'smartshop_template_info' );
			delete_option('smartshop_do_activation_library_cache');
        }

		$elementor_template = Smartshop_Template_Library_Manager::get_templates_info();
		$gutenberg_template	= Smartshop_Template_Library_Manager::get_gutenberg_templates_info();
		$get_data = (!empty( $elementor_template['templates'] ) && !empty( $gutenberg_template['templates'] )) ?  array_merge($elementor_template['templates'],$gutenberg_template['templates']) : [];
		$data = [];

		if( !empty( $get_data ) ){
			foreach( $get_data as $template ){

				if( $template['post_type'] === 'smartshop-template' ){
					$data[$template['type']][] = $template;
					if( $template['shareId'] == 'Shop' ){
						$data['archive'][] = $template;
					}
				}else{
					if( $template['shareId'] == 'Shop'){
						$data['shop'][] = $template;
						$data['archive'][] = $template;
					}else if($template['shareId'] == 'Product Details'){
						$data['single'][] = $template;
					}else if($template['shareId'] == 'Cart'){
						$data['cart'][] = $template;
					}else if( $template['shareId'] == 'Checkout Page' ){
						$data['checkout'][] = $template;
					}
					else if( $template['shareId'] == 'My Account' ){
						$data['myaccount'][] = $template;
					}
				}
			}
		}
		return $data;
	}

    /**
	 * Print Template edit popup
	 *
	 * @return void
	 */
	public function print_popup() {
		if( isset( $_GET['post_type'] ) && $_GET['post_type'] == 'smartshop-template' ){
			include_once( SMARTSHOP_ADDONS_PL_PATH. 'incs/admin/templates/template_edit_popup.php' );
		}
    }

    /**
	 * Print Admin Tab
	 *
	 * @param [array] $views
	 * @return array
	 */
    public function print_tabs( $views ) {
		$active_class = 'nav-tab-active';
		$current_type = '';
		if( isset( $_GET['tabs'] ) ){
			$active_class = '';
			$current_type = sanitize_key( $_GET['tabs'] );
		}
        ?>
            <div id="smartshop-template-tabs-wrapper" class="nav-tab-wrapper">
				<div class="smartshop-menu-area">
					<a class="nav-tab <?php echo esc_attr($active_class); ?>" href="edit.php?post_type=<?php echo esc_attr(self::CPTTYPE); ?>"><?php echo esc_html__('All','smartshop');?></a>
					<?php
						foreach( self::get_tabs() as $tabkey => $tab ){
							$active_class = ( $current_type == $tabkey ? 'nav-tab-active' : '' );
							echo '<a class="nav-tab '.esc_attr($active_class).'" href="edit.php?post_type='.esc_attr(self::CPTTYPE).'&template_type='.esc_attr($tabkey).'&tabs='.esc_attr($tabkey).'">'.esc_html($tab['label']).'</a>';
						}
					?>
				</div>
				<div class="smartshop-template-importer">
					<button type="button" class="button button-primary">
						<span class="dashicons dashicons-download"></span>
						<span class="smartshop-template-importer-btn-text"><?php esc_html_e('Import Previously Assigned Templates','smartshop');?></span>
					</button>
				</div>
            </div>
			<?php 
				if( !empty( $current_type ) && isset( self::get_tabs()[$current_type]['submenu'] ) ){

					$sub_tab_active_class = 'smartshop-sub-tab-active'; 
					$current_sub_tab = '';
					if( isset( $_GET['tab'] ) ){
						$sub_tab_active_class = '';
						$current_sub_tab = sanitize_key( $_GET['tab'] );
					}

					echo '<div class="smartshop-template-subtabs"><ul>';
						echo '<li><a class="smartshop-sub-tab '.esc_attr($sub_tab_active_class).'" href="edit.php?post_type='.esc_attr(self::CPTTYPE).'&template_type='.esc_attr($current_type).'&tabs='.esc_attr($current_type).'">'.esc_html(self::get_tabs()[$current_type]['label']).'</a></li>';

						foreach( self::get_tabs()[$current_type]['submenu'] as $subtabkey => $subtab ){
							$sub_tab_active_class = ( $current_sub_tab == $subtabkey ? 'smartshop-sub-tab-active' : '' );
							echo '<li><a class="smartshop-sub-tab '.esc_attr($sub_tab_active_class).'" href="edit.php?post_type='.esc_attr(self::CPTTYPE).'&template_type='.esc_attr($subtabkey).'&tabs='.esc_attr($current_type).'&tab='.esc_attr($subtabkey).'">'.esc_html($subtab['label']).'</a></li>';
						}

					echo '</ul></div>';

				}
			?>
        <?php
		return $views;
    }

    /**
	 * Manage Scripts
	 *
	 * @param [string] $hook
	 * @return void
	 */
    public function enqueue_scripts( $hook ){

        if( isset( $_GET['post_type'] ) && $_GET['post_type'] == 'smartshop-template' ){

			// CSS
            wp_enqueue_style( 'smartshop-template-edit-manager', SMARTSHOP_ADDONS_PL_URL . 'incs/admin/assets/css/template_edit_manager.css', [], SHOPXPERT_VERSION );
			wp_enqueue_style('smartshop-sweetalert');
			wp_enqueue_style('slick', SMARTSHOP_ADDONS_PL_URL . 'assets/css/slick.css', [], SHOPXPERT_VERSION );
			
			// JS
			wp_enqueue_script('smartshop-sweetalert');
			wp_enqueue_script('slick', SMARTSHOP_ADDONS_PL_URL . 'assets/js/slick.min.js', array('jquery'), SHOPXPERT_VERSION, true );
            wp_enqueue_script( 'smartshop-template-edit-manager', SMARTSHOP_ADDONS_PL_URL . 'incs/admin/assets/js/template_edit_manager.js', array('jquery', 'wp-util'), SHOPXPERT_VERSION, true );

			$localize_data = [
                'ajaxurl' 	=> admin_url( 'admin-ajax.php' ),
				'prostatus'	=> is_admin() ? is_plugin_active('smartshop-addons-pro/smartshop_addons_pro.php') : false,
				'nonce' 	=> wp_create_nonce('smartshop_tmp_nonce'),
				'templatetype' => self::get_template_type(),
				'haselementor' => smartshop_is_elementor_editor() ? 'yes' : 'no',
				'editor' => [
					'elementor' => __('Elementor','smartshop'),
					'gutenberg' => __('Gutenberg','smartshop')
				],
				'templatelist' => $this->get_template_library(),
				'adminURL'	=> admin_url(),
				'labels' => [
					'fields'=>[
						'name'	=> [
							'title' 	  => __('Name','smartshop'),
							'placeholder' => __('Enter a template name','smartshop')
						],
						'type'		 => __('Type','smartshop'),
						'editor' 	 => __('Select Editor','smartshop'),
						'setdefault' => __('Set Default','smartshop'),
					],
					'head' => __('Template Settings','smartshop'),
					'buttons' => [
						'elementor' => [
							'label' => __('Edit With Elementor','smartshop'),
							'link' 	=> '#'
						],
						'gutenberg' => [
							'label' => __('Edit With Gutenberg','smartshop'),
							'link' 	=> '#'
						],
						'save' => [
							'label'  => __('Save Settings','smartshop'),
							'saving' => __('Saving...','smartshop'),
							'saved'  => __('All Data Saved','smartshop'),
							'link' 	 => '#'
						]
					],
					'sampledata' => [
						'visibility' => __('Sample Design','smartshop'),
						'elementor'  => __('Elementor','smartshop'),
						'gutenberg'  => __('Gutenberg','smartshop'),
						'pro' 		 => __('Pro','smartshop'),
					],
					'importer' =>[
						'button' => [
							'importing' => __('Assigned Template Importing..','smartshop'),
							'imported'  => __('All Assigned Template has been imported','smartshop'),
						],
						'message' =>[
							'title' 	=> __( 'Are you sure?','smartshop' ),
							'message' 	=> __( 'It will import those templates that were created from the "Templates" menu of Elementor and assigned to corresponding WooCommerce pages.','smartshop' ) ,
							'yesbtn' 	=> __('Yes','smartshop'),
							'cancelbtn' => __('Cancel','smartshop') 
						]
					]
				]
            ];
			wp_localize_script( 'smartshop-template-edit-manager', 'WLTMCPT', $localize_data );

        }

    }

    /**
	 * Store Template
	 *
	 * @return void
	 */
	public function template_store_request(){
		if ( isset( $_POST ) ) {

			if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'smartshop_tmp_nonce' ) ){
				$errormessage = array(
					'message'  => __('Nonce Varification Faild !','smartshop')
				);
				wp_send_json_error( $errormessage );
			}

			if( !(current_user_can('manage_options') || current_user_can('edit_others_posts')) ){
				$errormessage = array(
					'message'  => __('You are unauthorize to adding template!','smartshop')
				);
				wp_send_json_error( $errormessage );
			}

			$title 		= !empty( $_POST['title'] ) ? sanitize_text_field( $_POST['title'] ) : esc_html__( 'SMARTSHOP template '.time(), 'smartshop' );
			$tmpid 		= !empty( $_POST['tmpId'] ) ? sanitize_text_field( $_POST['tmpId'] ) : '';
			$tmpType 	= !empty( $_POST['tmpType'] ) ? sanitize_text_field( $_POST['tmpType'] ) : 'single';
			$tmpEditor 	= !empty( $_POST['tmpEditor'] ) ? sanitize_text_field( $_POST['tmpEditor'] ) : 'gutenberg';
			$setDefault = !empty( $_POST['setDefault'] ) ? sanitize_text_field( $_POST['setDefault'] ) : 'no';
			$sampleTmpID = !empty( $_POST['sampleTmpID'] ) ? sanitize_text_field( $_POST['sampleTmpID'] ) : '';
			$sampleTmpBuilder = !empty( $_POST['sampleTmpBuilder'] ) ? sanitize_text_field( $_POST['sampleTmpBuilder'] ) : '';

			$data = [
				'title' 		=> $title,
				'id' 			=> $tmpid,
				'tmptype' 		=> $tmpType,
				'setdefaullt'	=> $setDefault,
				'sampletmpid' 	=> $sampleTmpID,
				'sampletmpbuilder' => $sampleTmpBuilder
			];

			if( empty( $tmpid ) ){
				$data['tmpeditor'] = $tmpEditor;
			}

			if( $tmpid ){
				$this->update( $data );
			}else{
				$this->insert( $data );
			}

		}else{
			$errormessage = array(
				'message'  => __('Post request dose not found','smartshop')
			);
			wp_send_json_error( $errormessage );
		}

	}

    /**
	 * Template Insert
	 *
	 * @param [array] $data
	 * @return void
	 */
	public function insert( $data ){

		$args = [
			'post_type'    => self::CPTTYPE,
			'post_status'  => $data['tmptype'] == 'popup' ? 'draft' : 'publish',
			'post_title'   => $data['title'],
		];
		$new_post_id = wp_insert_post( $args );

		if( $new_post_id ){
			$return = array(
				'message'  => __('Template has been inserted','smartshop'),
				'id'       => $new_post_id,
			);

			// Meta data
			$template_slug 			= ( $data['tmpeditor'] === 'elementor' ) ? 'elementor_header_footer' : 'smartshop_fullwidth';
			$default_page_template 	= apply_filters( 'smartshop_default_page_template', $template_slug, $data['tmptype'] );

			update_post_meta( $new_post_id, self::CPT_META . '_type', $data['tmptype'] );
			update_post_meta( $new_post_id, self::CPT_META . '_editor', $data['tmpeditor'] );
			update_post_meta( $new_post_id, '_wp_page_template', $default_page_template );

			if( $data['tmpeditor'] === 'elementor' ){
				update_post_meta( $new_post_id, '_elementor_edit_mode', 'builder');
			}

			// Sample data import
			$this->sampleTemplateImport($data, $new_post_id);

			// Update Default template if user is set default
			if( $data['setdefaullt'] == 'yes' ) {
				$data['id'] = $new_post_id;
				$this->update_option( 'smartshop_woo_template_tabs', self::get_template_type()[$data['tmptype']]['optionkey'], $new_post_id, $data );
			}

			wp_send_json_success( $return );

		}else{
			$errormessage = array(
				'message'  => __('Some thing is worng !','smartshop')
			);
			wp_send_json_error( $errormessage );
		}

	}

    /**
	 * Template Update
	 *
	 * @param [array] $data
	 * @return void
	 */
	public function update( $data ){

		$update_post_args = array(
			'ID'         => $data['id'],
			'post_title' => $data['title'],
		);
		wp_update_post( $update_post_args );

		// Update Meta data
		update_post_meta( $data['id'], self::CPT_META . '_type', $data['tmptype'] );

		// Sample data import
		$this->sampleTemplateImport( $data, $data['id'] );

		// Update Default template if user is set default
		if( $data['setdefaullt'] == 'yes' ) {
			$this->update_option( 'smartshop_woo_template_tabs', self::get_template_type()[$data['tmptype']]['optionkey'], $data['id'], $data );
		}else{
			$this->update_option( 'smartshop_woo_template_tabs', self::get_template_type()[$data['tmptype']]['optionkey'], '0', $data );
		}

		$return = array(
			'message'  => __('Template has been updated','smartshop'),
			'id'       => $data['id']
		);
		wp_send_json_success( $return );

	}

	/**
	 * Sample Desing Importer
	 *
	 * @return void
	 */
	public function sampleTemplateImport($data, $post_id){

		if( !empty( $data['sampletmpid'] ) ){

			$response_data = ($data['sampletmpbuilder'] == 'elementor') ? Smartshop_Template_Library_Manager::get_template_data('template', $data['sampletmpid']) : Smartshop_Template_Library_Manager::get_template_data('gutenberg', $data['sampletmpid']);

			$this->popBuilderSettings( $response_data, $post_id );

			if( $data['sampletmpbuilder'] == 'elementor' ){
				$json_value 	= wp_slash( wp_json_encode( $response_data['content']['content'] ) );
				update_post_meta( $post_id, '_elementor_data', $json_value );
			}else{
				wp_update_post( ['ID' => $post_id, 'post_content'=> $response_data['content']] );
			}
		}
		
	}

	/**
	 * Add PopupBuilder Additional Settings
	 *
	 * @param [type] $data
	 * @param [type] $post_id
	 * @return void
	 */
	public function popBuilderSettings( $data, $post_id ){
		if( !empty( $data['type'] ) && $data['type'] == 'popup' ){
			update_post_meta( $post_id, '_wlpb_popup_seetings', $data['popup_settings']);
		}
	}

    /**
	 * Get Template data by id
	 *
	 * @return void
	 */
	public function get_post_By_id(){
		if ( isset( $_POST ) ) {

			if( !(current_user_can('manage_options') || current_user_can('edit_others_posts')) ){
				$errormessage = array(
					'message'  => __('You are unauthorize to adding template!','smartshop')
				);
				wp_send_json_error( $errormessage );
			}

			$nonce = $_POST['nonce'];
			if ( ! wp_verify_nonce( $nonce, 'smartshop_tmp_nonce' ) ) {
				$errormessage = array(
					'message'  => __('Nonce Varification Faild !','smartshop')
				);
				wp_send_json_error( $errormessage );
			}

			$tmpid = !empty( $_POST['tmpId'] ) ? sanitize_text_field( $_POST['tmpId'] ) : '';
			$postdata = get_post( $tmpid );
			$tmpType = !empty( get_post_meta( $tmpid, self::CPT_META . '_type', true ) ) ? get_post_meta( $tmpid, self::CPT_META . '_type', true ) : 'single';
			$tmpEditor = !empty( get_post_meta( $tmpid, self::CPT_META . '_editor', true ) ) ? get_post_meta( $tmpid, self::CPT_META . '_editor', true ) : 'elementor';
			$data = [
				'tmpTitle' 	 => $postdata->post_title,
				'tmpType' 	 => $tmpType,
				'tmpEditor'	 => $tmpEditor,
				'setDefault' => isset( self::get_template_type()[$tmpType]['optionkey'] ) ? $this->get_template_id(self::get_template_type()[$tmpType]['optionkey']) : '0',
			];
            wp_send_json_success( $data );

		}else{
			$errormessage = array(
				'message'  => __('Some thing is worng !','smartshop')
			);
			wp_send_json_error( $errormessage );
		}

	}

	/**
	 * set_default_template_type function
	 *
	 * @return void
	 */
	public function manage_template_status(){

		if ( isset( $_POST ) ) {

			if( !(current_user_can('manage_options') || current_user_can('edit_others_posts')) ){
				$errormessage = array(
					'message'  => __('You are unauthorize to adding template!','smartshop')
				);
				wp_send_json_error( $errormessage );
			}

			$nonce = $_POST['nonce'];
			if ( ! wp_verify_nonce( $nonce, 'smartshop_tmp_nonce' ) ) {
				$errormessage = array(
					'message'  => __('Nonce Varification Faild !','smartshop')
				);
				wp_send_json_error( $errormessage );
			}

			$tmpid 		= !empty( $_POST['tmpId'] ) ? sanitize_text_field( $_POST['tmpId'] ) : '0';
			$tmpType 	= !empty( $_POST['tmpType'] ) ? sanitize_text_field( $_POST['tmpType'] ) : 'single';

			$data = [
				'id' 			=> $tmpid,
				'tmptype' 		=> $tmpType,
				'setdefaullt'	=> ( $tmpid == '0' ) ? 'no' : 'yes',
			];

			$this->update_option( 'smartshop_woo_template_tabs', self::get_template_type()[$tmpType]['optionkey'], $tmpid, $data );

			$return = array(
				'message'  => __('Template has been updated','smartshop'),
				'id'       => $tmpid
			);

			wp_send_json_success( $return );

		}else{
			$errormessage = array(
				'message'  => __('Some thing is worng !','smartshop')
			);
			wp_send_json_error( $errormessage );
		}

	}

	/**
	 * option value Manage
	 *
	 * @return string
	 */

	 public function template_id_manage( $option_value, $template_data ){
		$option_value = !empty( $option_value ) ? maybe_unserialize( $option_value ) : [];

		$data = [ 'lang' => [] ];

		if( is_array( $option_value ) && isset( $option_value['lang'] ) && is_array( $option_value['lang'] ) && array_key_exists( self::$language_code, $option_value['lang'] ) ){
			$option_value['lang'][self::$language_code]['template_id'] = $template_data['id'];
			$option_value['lang'][self::$language_code]['default'] = $template_data['setdefaullt'];
			$data = $option_value;
		}else{
			if( is_array( $option_value ) && isset( $option_value['lang'] ) ){
				$data['lang'] = $option_value['lang'];
			}
			$data['lang'][self::$language_code] = [
				'template_id' => $template_data['id'],
				'default' => $template_data['setdefaullt'],
			];
		}
		return serialize( $data );
		
	}

	/**
	 * get_template_id function
	 *
	 * @return [int]
	 */
	public function get_template_id( $template_key, $callback = false ){
		$option_value = ( $callback && is_callable( $callback ) ) ? $callback( $template_key, 'smartshop_woo_template_tabs', '0' ) : smartshop_get_option( $template_key, 'smartshop_woo_template_tabs', '0' );
		$option_value = maybe_unserialize( $option_value );
		$template_id = 0;
		if( is_array( $option_value ) && array_key_exists( self::$language_code, $option_value['lang'] ) ){
			$template_id = ( $option_value['lang'][self::$language_code]['template_id'] != '0' ) ? $option_value['lang'][self::$language_code]['template_id'] : $option_value['lang']['en']['template_id'];
		}else{
			if( is_array( $option_value ) ){
				$template_id = isset( $option_value['lang']['en']['template_id'] ) ? $option_value['lang']['en']['template_id'] : '0';
			}else{
				$template_id = $option_value;
			}
		}
		return $template_id;
	}

	/**
	 * update_option
	 *
	 * @return void
	 */
	public function update_option( $section, $option_key, $new_value, $template_data ){
        if( $new_value === Null ){ $new_value = ''; }
        $options_datad = is_array( get_option( $section ) ) ? get_option( $section ) : array();
		if( defined( 'SHOPXPERT_VERSION_PRO' ) && ( '2.0.6' >= SHOPXPERT_VERSION_PRO ) ){
			$options_datad[$option_key] = $new_value;
		}else{
			$options_data_value = isset( $options_datad[$option_key] ) ? $options_datad[$option_key] : '';
			$options_datad[$option_key] = $this->template_id_manage( $options_data_value, $template_data );
		}
        update_option( $section, $options_datad );
    }

	/**
	 * Template Importer
	 *
	 * @return void
	 */
	public function template_import(){
		if ( isset( $_POST ) ) {
			
			if( !(current_user_can('manage_options') || current_user_can('edit_others_posts')) ){
				$errormessage = array(
					'message'  => __('You are unauthorize to adding template!','smartshop')
				);
				wp_send_json_error( $errormessage );
			}
			
			$nonce = $_POST['nonce'];
			if ( ! wp_verify_nonce( $nonce, 'smartshop_tmp_nonce' ) ) {
				$errormessage = array(
					'message'  => __('Nonce Varification Faild !','smartshop')
				);
				wp_send_json_error( $errormessage );
			}

			foreach( self::get_template_type() as $key => $template_type ){

				$tmp_id = $this->get_template_id( $template_type['optionkey'] );

				$get_args = array( 
					'p' 		=> $tmp_id, 
					'post_type' => 'elementor_library'
				);
				$templates_query = new \WP_Query( $get_args );
				wp_reset_query();

				if ( $templates_query->have_posts() ) {

					$args = array(
						'ID'        => $tmp_id,
						'post_type' => self::CPTTYPE,
					);
					$update_id = wp_update_post( $args );

					if( ! is_wp_error( $update_id ) ){
						update_post_meta( $update_id, self::CPT_META . '_type', $key );
					}

				}

			}

			$return = array(
				'message'  => __('Template has been imported','smartshop'),
			);

			wp_send_json_success( $return );

		}else{
			$errormessage = array(
				'message'  => __('Some thing is worng !','smartshop')
			);
			wp_send_json_error( $errormessage );
		}

	}


}

Smartshop_Template_Manager::instance();