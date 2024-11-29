<?php
namespace WishList\Admin;
/**
 * Admin Page Fields handlers class
 */
class Admin_Fields {

    private $settings_api;

    /**
     * [$_instance]
     * @var null
     */
    private static $_instance = null;

    /**
     * [instance] Initializes a singleton instance
     * @return [Admin]
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    function __construct() {
       
        require_once SHOPXPERT_ADDONS_PL_PATH . 'incs/admin/inc/settings_field_manager_default.php';
        
        if (class_exists('ShopXpert_Settings_Field_Manager_Default')) {
            error_log('Class ShopXpert_Settings_Field_Manager_Default not found after require.');
            error_log(SHOPXPERT_ADDONS_PL_PATH .'incs/admin/inc/settings_field_manager_default.php'); 
        }

        $this->settings_api = new \WishList\Admin\ShopXpert_Settings_Field_Manager_Default();
        add_action( 'admin_init', [ $this, 'admin_init' ] );
    }

    public function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->fields_settings() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    // Options page Section register
    public function get_settings_sections() {
        $sections = array(

            array(
                'id'    => 'wishlist_general_tabs',
                'title' => esc_html__( 'General Settings', 'shopxpert' )
            ),

            array(
                'id'    => 'wishlist_settings_tabs',
                'title' => esc_html__( 'Button Settings', 'shopxpert' )
            ),
            
            array(
                'id'    => 'wishlist_table_settings_tabs',
                'title' => esc_html__( 'Table Settings', 'shopxpert' )
            ),
            
            array(
                'id'    => 'wishlist_style_settings_tabs',
                'title' => esc_html__( 'Style Settings', 'shopxpert' )
            ),

        );
        return $sections;
    }

    // Options page field register
    protected function fields_settings() {

        $settings_fields = array(

            'wishlist_general_tabs' => array(
                array(
                    'name'      => 'enable_login_limit',
                    'label'     => __( 'Limit Wishlist Use', 'shopxpert' ),
                    'type'      => 'checkbox',
                    'default'   => 'off',
                    'desc'      => esc_html__( 'Enable this option to allow only the logged-in users to use the Wishlist feature.', 'shopxpert' ),
                ),

                array(
                    'name'      => 'logout_button',
                    'label'     => __( 'Wishlist Icon Tooltip Text', 'shopxpert' ),
                    'desc'      => __( 'Enter a text for the tooltip that will be shown when someone hover over the Wishlist icon.', 'shopxpert' ),
                    'type'      => 'text',
                    'default'   => __( 'Please login', 'shopxpert' ),
                     'class'    => 'depend_user_login_enable'
                ),

            ),

            'wishlist_settings_tabs' => array(

                array(
                    'name'  => 'btn_show_shoppage',
                    'label'  => __( 'Show button in product list', 'shopxpert' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                ),

                array(
                    'name'  => 'btn_show_productpage',
                    'label'  => __( 'Show button in single product page', 'shopxpert' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                ),

                array(
                    'name'    => 'shop_btn_position',
                    'label'   => __( 'Shop page button position', 'shopxpert' ),
                    'desc'    => __( 'You can manage wishlist button position in product list page.', 'shopxpert' ),
                    'type'    => 'select',
                    'default' => 'after_cart_btn',
                    'options' => [
                        'before_cart_btn' => __( 'Before Add To Cart', 'shopxpert' ),
                        'after_cart_btn'  => __( 'After Add To Cart', 'shopxpert' ),
                        'top_thumbnail'   => __( 'Top On Image', 'shopxpert' ),
                        'use_shortcode'   => __( 'Use Shortcode', 'shopxpert' ),
                        'custom_position' => __( 'Custom Position', 'shopxpert' ),
                    ],
                ),

                array(
                    'name'    => 'shop_use_shortcode_message',
                    'headding'=> wp_kses_post('<code>[wishlist_button]</code> Use this shortcode into your theme/child theme to place the wishlist button.'),
                    'type'    => 'title',
                    'size'    => 'margin_0 regular',
                    'class' => 'depend_shop_btn_position_use_shortcode element_section_title_area message-info',
                ),

                array(
                    'name'    => 'shop_custom_hook_message',
                    'headding'=> esc_html__( 'Some themes remove the above positions. In that case, custom position is useful. Here you can place the custom/default hook name & priority to inject & adjust the wishlist button for the product loop.', 'shopxpert' ),
                    'type'    => 'title',
                    'size'    => 'margin_0 regular',
                    'class' => 'depend_shop_btn_position_custom_hook element_section_title_area message-info',
                ),

                array(
                    'name'        => 'shop_custom_hook_name',
                    'label'       => __( 'Hook name', 'shopxpert' ),
                    'desc'        => __( 'e.g: woocommerce_after_shop_loop_item_title', 'shopxpert' ),
                    'type'        => 'text',
                    'class'       => 'depend_shop_btn_position_custom_hook'
                ),

                array(
                    'name'        => 'shop_custom_hook_priority',
                    'label'       => __( 'Hook priority', 'shopxpert' ),
                    'desc'        => __( 'Default: 10', 'shopxpert' ),
                    'type'        => 'text',
                    'class'       => 'depend_shop_btn_position_custom_hook'
                ),

                array(
                    'name'    => 'product_btn_position',
                    'label'   => __( 'Product page button position', 'shopxpert' ),
                    'desc'    => __( 'You can manage wishlist button position in single product page.', 'shopxpert' ),
                    'type'    => 'select',
                    'default' => 'after_cart_btn',
                    'options' => [
                        'before_cart_btn' => __( 'Before Add To Cart', 'shopxpert' ),
                        'after_cart_btn'  => __( 'After Add To Cart', 'shopxpert' ),
                        'after_thumbnail' => __( 'After Image', 'shopxpert' ),
                        'after_summary'   => __( 'After Summary', 'shopxpert' ),
                        'use_shortcode'   => __( 'Use Shortcode', 'shopxpert' ),
                        'custom_position' => __( 'Custom Position', 'shopxpert' ),
                    ],
                ),

                array(
                    'name'    => 'product_use_shortcode_message',
                    'headding'=> wp_kses_post('<code>[wishlist_button]</code> Use this shortcode into your theme/child theme to place the wishlist button.'),
                    'type'    => 'title',
                    'size'    => 'margin_0 regular',
                    'class' => 'depend_product_btn_position_use_shortcode element_section_title_area message-info',
                ),

                array(
                    'name'    => 'product_custom_hook_message',
                    'headding'=> esc_html__( 'Some themes remove the above positions. In that case, custom position is useful. Here you can place the custom/default hook name & priority to inject & adjust the wishlist button for the single product page.', 'shopxpert' ),
                    'type'    => 'title',
                    'size'    => 'margin_0 regular',
                    'class' => 'depend_product_btn_position_custom_hook element_section_title_area message-info',
                ),

                array(
                    'name'        => 'product_custom_hook_name',
                    'label'       => __( 'Hook name', 'shopxpert' ),
                    'desc'        => __( 'e.g: woocommerce_after_single_product_summary', 'shopxpert' ),
                    'type'        => 'text',
                    'class'       => 'depend_product_btn_position_custom_hook'
                ),

                array(
                    'name'        => 'product_custom_hook_priority',
                    'label'       => __( 'Hook priority', 'shopxpert' ),
                    'desc'        => __( 'Default: 10', 'shopxpert' ),
                    'type'        => 'text',
                    'class'       => 'depend_product_btn_position_custom_hook'
                ),

                array(
                    'name'        => 'button_text',
                    'label'       => __( 'Button Text', 'shopxpert' ),
                    'desc'        => __( 'Enter your wishlist button text.', 'shopxpert' ),
                    'type'        => 'text',
                    'default'     => __( 'x Wishlist', 'shopxpert' ),
                    'placeholder' => __( 'y Wishlist', 'shopxpert' ),
                ),

                array(
                    'name'        => 'added_button_text',
                    'label'       => __( 'Product added text', 'shopxpert' ),
                    'desc'        => __( 'Enter the product added text.', 'shopxpert' ),
                    'type'        => 'text',
                    'default'     => __( 'Product Added', 'shopxpert' ),
                    'placeholder' => __( 'Product Added', 'shopxpert' ),
                ),

                array(
                    'name'        => 'exist_button_text',
                    'label'       => __( 'Already exists in the wishlist text', 'shopxpert' ),
                    'desc'        => wp_kses_post( 'Enter the message for "<strong>already exists in the wishlist</strong>" text.' ),
                    'type'        => 'text',
                    'default'     => __( 'Product already added', 'shopxpert' ),
                    'placeholder' => __( 'Product already added', 'shopxpert' ),
                ),

            ),

            'wishlist_table_settings_tabs' => array(

                array(
                    'name'    => 'wishlist_page',
                    'label'   => __( 'Wishlist page', 'shopxpert' ),
                    'type'    => 'select',
                    'default' => '0',
                    'options' => WishList_get_post_list(),
                    'desc'    => wp_kses_post('Select a wishlist page for wishlist table. It should contain the shortcode <code>[wishlist_table]</code>'),
                ),

                array(
                    'name'    => 'wishlist_product_per_page',
                    'label'   => __( 'Products per page', 'shopxpert' ),
                    'type'    => 'number',
                    'default' => '20',
                    'desc'    => __('You can choose the number of wishlist products to display per page. The default value is 20 products.', 'shopxpert'),
                ),

                array(
                    'name'  => 'after_added_to_cart',
                    'label'  => __( 'Remove from the "Wishlist" after adding to the cart.', 'shopxpert' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                ),

                array(
                    'name' => 'show_fields',
                    'label' => __('Show fields in table', 'shopxpert'),
                    'desc' => __('Choose which fields should be presented on the product compare page with table.', 'shopxpert'),
                    'type' => 'multicheckshort',
                    'options' => WishList_get_available_attributes(),
                    'default' => [
                        'remove'        => esc_html__( 'Remove', 'shopxpert' ),
                        'image'         => esc_html__( 'Image', 'shopxpert' ),
                        'title'         => esc_html__( 'Title', 'shopxpert' ),
                        'price'         => esc_html__( 'Price', 'shopxpert' ),
                        'quantity'      => esc_html__( 'Quantity', 'shopxpert' ),
                        'add_to_cart'   => esc_html__( 'Add To Cart', 'shopxpert' ),
                    ],
                ),

                array(
                    'name'    => 'table_heading',
                    'label'   => __( 'Table heading text', 'shopxpert' ),
                    'desc'    => __( 'You can change table heading text from here.', 'shopxpert' ),
                    'type'    => 'multitext',
                    'options' => WishList_table_heading()
                ),

                array(
                    'name' => 'empty_table_text',
                    'label' => __('Empty table text', 'shopxpert'),
                    'desc' => __('Text will be displayed if the user doesn\'t add any product to  the wishlist.', 'shopxpert'),
                    'type' => 'textarea'
                ),

                array(
                    'name'        => 'image_size',
                    'label'       => __( 'Image size', 'shopxpert' ),
                    'desc'        => __( 'Enter your required image size.', 'shopxpert' ),
                    'type'        => 'multitext',
                    'options'     =>[
                        'width'  => esc_html__( 'Width', 'shopxpert' ),
                        'height' => esc_html__( 'Height', 'shopxpert' ),
                    ],
                    'default' => [
                        'width'   => 80,
                        'height'  => 80,
                    ],
                ),

                array(
                    'name'  => 'hard_crop',
                    'label'  => __( 'Image Hard Crop', 'shopxpert' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                ),

                array(
                    'name'    => 'social_share_button_area_title',
                    'headding'=> esc_html__( 'Social share button', 'shopxpert' ),
                    'type'    => 'title',
                    'size'    => 'margin_0 regular',
                    'class' => 'element_section_title_area',
                ),

                array(
                    'name'  => 'enable_social_share',
                    'label'  => esc_html__( 'Enable social share button', 'shopxpert' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'desc'    => esc_html__( 'Enable social share button.', 'shopxpert' ),
                ),

                array(
                    'name'        => 'social_share_button_title',
                    'label'       => esc_html__( 'Social share button title', 'shopxpert' ),
                    'desc'        => esc_html__( 'Enter your social share button title.', 'shopxpert' ),
                    'type'        => 'text',
                    'default'     => esc_html__( 'Share:', 'shopxpert' ),
                    'placeholder' => esc_html__( 'Share', 'shopxpert' ),
                    'class' => 'depend_social_share_enable'
                ),

                array(
                    'name' => 'social_share_buttons',
                    'label' => esc_html__('Enable share buttons', 'shopxpert'),
                    'desc'    => esc_html__( 'You can manage your social share buttons.', 'shopxpert' ),
                    'type' => 'multicheckshort',
                    'options' => [
                        'facebook'      => esc_html__( 'Facebook', 'shopxpert' ),
                        'twitter'       => esc_html__( 'Twitter', 'shopxpert' ),
                        'pinterest'     => esc_html__( 'Pinterest', 'shopxpert' ),
                        'linkedin'      => esc_html__( 'Linkedin', 'shopxpert' ),
                        'email'         => esc_html__( 'Email', 'shopxpert' ),
                        'reddit'        => esc_html__( 'Reddit', 'shopxpert' ),
                        'telegram'      => esc_html__( 'Telegram', 'shopxpert' ),
                        'odnoklassniki' => esc_html__( 'Odnoklassniki', 'shopxpert' ),
                        'whatsapp'      => esc_html__( 'WhatsApp', 'shopxpert' ),
                        'vk'            => esc_html__( 'VK', 'shopxpert' ),
                    ],
                    'default' => [
                        'facebook'   => esc_html__( 'Facebook', 'shopxpert' ),
                        'twitter'    => esc_html__( 'Twitter', 'shopxpert' ),
                        'pinterest'  => esc_html__( 'Pinterest', 'shopxpert' ),
                        'linkedin'   => esc_html__( 'Linkedin', 'shopxpert' ),
                        'telegram'   => esc_html__( 'Telegram', 'shopxpert' ),
                    ],
                    'class' => 'depend_social_share_enable'
                ),

            ),

            'wishlist_style_settings_tabs' => array(

                array(
                    'name'    => 'button_style',
                    'label'   => __( 'Button style', 'shopxpert' ),
                    'desc'    => __( 'Choose a style for the wishlist button from here.', 'shopxpert' ),
                    'type'    => 'select',
                    'default' => 'default',
                    'options' => [
                        'default'     => esc_html__( 'Default style', 'shopxpert' ),
                        'themestyle'  => esc_html__( 'Theme style', 'shopxpert' ),
                        'custom'      => esc_html__( 'Custom style', 'shopxpert' ),
                    ]
                ),

                array(
                    'name'    => 'button_icon_type',
                    'label'   => __( 'Button icon type', 'shopxpert' ),
                    'desc'    => __( 'Choose an icon for the wishlist button from here.', 'shopxpert' ),
                    'type'    => 'select',
                    'default' => 'default',
                    'options' => [
                        'none'     => esc_html__( 'None', 'shopxpert' ),
                        'default'  => esc_html__( 'Default icon', 'shopxpert' ),
                        'custom'   => esc_html__( 'Custom icon', 'shopxpert' ),
                    ]
                ),

                array(
                    'name'    => 'button_custom_icon',
                    'label'   => __( 'Button custom icon', 'shopxpert' ),
                    'type'    => 'image_upload',
                    'options' => [
                        'button_label' => esc_html__( 'Upload', 'shopxpert' ),   
                        'button_remove_label' => esc_html__( 'Remove', 'shopxpert' ),   
                    ],
                ),

                array(
                    'name'    => 'addedbutton_icon_type',
                    'label'   => __( 'Added Button icon type', 'shopxpert' ),
                    'desc'    => __( 'Choose an icon for the wishlist button from here.', 'shopxpert' ),
                    'type'    => 'select',
                    'default' => 'default',
                    'options' => [
                        'none'     => esc_html__( 'None', 'shopxpert' ),
                        'default'  => esc_html__( 'Default icon', 'shopxpert' ),
                        'custom'   => esc_html__( 'Custom icon', 'shopxpert' ),
                    ]
                ),

                array(
                    'name'    => 'addedbutton_custom_icon',
                    'label'   => __( 'Added Button custom icon', 'shopxpert' ),
                    'type'    => 'image_upload',
                    'options' => [
                        'button_label' => esc_html__( 'Upload', 'shopxpert' ),   
                        'button_remove_label' => esc_html__( 'Remove', 'shopxpert' ),   
                    ],
                ),

                array(
                    'name'    => 'table_style',
                    'label'   => __( 'Table style', 'shopxpert' ),
                    'desc'    => __( 'Choose a style for the wishlist table here.', 'shopxpert' ),
                    'type'    => 'select',
                    'default' => 'default',
                    'options' => [
                        'default' => esc_html__( 'Default style', 'shopxpert' ),
                        'custom'  => esc_html__( 'Custom style', 'shopxpert' ),
                    ]
                ),

                array(
                    'name'    => 'button_custom_style_title',
                    'headding'=> __( 'Button custom style', 'shopxpert' ),
                    'type'    => 'title',
                    'size'    => 'margin_0 regular',
                    'class' => 'button_custom_style element_section_title_area',
                ),

                array(
                    'name'  => 'button_color',
                    'label' => esc_html__( 'Color', 'shopxpert' ),
                    'desc'  => wp_kses_post( 'Set the color of the button.', 'shopxpert' ),
                    'type'  => 'color',
                    'class' => 'button_custom_style',
                ),

                array(
                    'name'  => 'button_hover_color',
                    'label' => esc_html__( 'Hover Color', 'shopxpert' ),
                    'desc'  => wp_kses_post( 'Set the hover color of the button.', 'shopxpert' ),
                    'type'  => 'color',
                    'class' => 'button_custom_style',
                ),

                array(
                    'name'  => 'background_color',
                    'label' => esc_html__( 'Background Color', 'shopxpert' ),
                    'desc'  => wp_kses_post( 'Set the background color of the button.', 'shopxpert' ),
                    'type'  => 'color',
                    'class' => 'button_custom_style',
                ),

                array(
                    'name'  => 'hover_background_color',
                    'label' => esc_html__( 'Hover Background Color', 'shopxpert' ),
                    'desc'  => wp_kses_post( 'Set the hover background color of the button.', 'shopxpert' ),
                    'type'  => 'color',
                    'class' => 'button_custom_style',
                ),

                array(
                    'name'    => 'button_custom_padding',
                    'label'   => __( 'Padding', 'shopxpert' ),
                    'type'    => 'dimensions',
                    'options' => [
                        'top'   => esc_html__( 'Top', 'shopxpert' ),   
                        'right' => esc_html__( 'Right', 'shopxpert' ),   
                        'bottom'=> esc_html__( 'Bottom', 'shopxpert' ),   
                        'left'  => esc_html__( 'Left', 'shopxpert' ),
                        'unit'  => esc_html__( 'Unit', 'shopxpert' ),
                    ],
                    'class' => 'button_custom_style',
                ),

                array(
                    'name'    => 'button_custom_margin',
                    'label'   => __( 'Margin', 'shopxpert' ),
                    'type'    => 'dimensions',
                    'options' => [
                        'top'   => esc_html__( 'Top', 'shopxpert' ),   
                        'right' => esc_html__( 'Right', 'shopxpert' ),   
                        'bottom'=> esc_html__( 'Bottom', 'shopxpert' ),   
                        'left'  => esc_html__( 'Left', 'shopxpert' ),
                        'unit'  => esc_html__( 'Unit', 'shopxpert' ),
                    ],
                    'class' => 'button_custom_style',
                ),

                array(
                    'name'    => 'button_custom_border_radius',
                    'label'   => __( 'Border Radius', 'shopxpert' ),
                    'type'    => 'dimensions',
                    'options' => [
                        'top'   => esc_html__( 'Top', 'shopxpert' ),   
                        'right' => esc_html__( 'Right', 'shopxpert' ),   
                        'bottom'=> esc_html__( 'Bottom', 'shopxpert' ),   
                        'left'  => esc_html__( 'Left', 'shopxpert' ),
                        'unit'  => esc_html__( 'Unit', 'shopxpert' ),
                    ],
                    'class' => 'button_custom_style',
                ),

                array(
                    'name'    => 'table_custom_style_title',
                    'headding'=> __( 'Table custom style', 'shopxpert' ),
                    'type'    => 'title',
                    'size'    => 'margin_0 regular',
                    'class' => 'table_custom_style element_section_title_area',
                ),

                array(
                    'name'  => 'table_heading_color',
                    'label' => esc_html__( 'Heading Color', 'shopxpert' ),
                    'desc'  => wp_kses_post( 'Set the heading color of the wishlist table.', 'shopxpert' ),
                    'type'  => 'color',
                    'class' => 'table_custom_style',
                ),

                array(
                    'name'  => 'table_heading_bg_color',
                    'label' => esc_html__( 'Heading Background Color', 'shopxpert' ),
                    'desc'  => wp_kses_post( 'Set the heading background color of the wishlist table.', 'shopxpert' ),
                    'type'  => 'color',
                    'class' => 'table_custom_style',
                ),
                array(
                    'name'  => 'table_heading_border_color',
                    'label' => esc_html__( 'Heading Border Color', 'shopxpert' ),
                    'desc'  => wp_kses_post( 'Set the heading border color of the wishlist table.', 'shopxpert' ),
                    'type'  => 'color',
                    'class' => 'table_custom_style',
                ),

                array(
                    'name'  => 'table_border_color',
                    'label' => esc_html__( 'Border Color', 'shopxpert' ),
                    'desc'  => wp_kses_post( 'Set the border color of the wishlist table.', 'shopxpert' ),
                    'type'  => 'color',
                    'class' => 'table_custom_style',
                ),

                array(
                    'name'    => 'table_custom_style_add_to_cart',
                    'headding'=> __( 'Add To Cart Button style', 'shopxpert' ),
                    'type'    => 'title',
                    'size'    => 'margin_0 regular',
                    'class' => 'table_custom_style element_section_title_area',
                ),

                array(
                    'name'  => 'table_cart_button_color',
                    'label' => esc_html__( 'Color', 'shopxpert' ),
                    'desc'  => wp_kses_post( 'Set the add to cart button color of the wishlist table.', 'shopxpert' ),
                    'type'  => 'color',
                    'class' => 'table_custom_style',
                ),
                array(
                    'name'  => 'table_cart_button_bg_color',
                    'label' => esc_html__( 'Background Color', 'shopxpert' ),
                    'desc'  => wp_kses_post( 'Set the add to cart button background color of the wishlist table.', 'shopxpert' ),
                    'type'  => 'color',
                    'class' => 'table_custom_style',
                ),
                array(
                    'name'  => 'table_cart_button_hover_color',
                    'label' => esc_html__( 'Hover Color', 'shopxpert' ),
                    'desc'  => wp_kses_post( 'Set the add to cart button hover color of the wishlist table.', 'shopxpert' ),
                    'type'  => 'color',
                    'class' => 'table_custom_style',
                ),
                array(
                    'name'  => 'table_cart_button_hover_bg_color',
                    'label' => esc_html__( 'Hover Background Color', 'shopxpert' ),
                    'desc'  => wp_kses_post( 'Set the add to cart button hover background color of the wishlist table.', 'shopxpert' ),
                    'type'  => 'color',
                    'class' => 'table_custom_style',
                ),

            ),

        );
        
        return $settings_fields;
    }

    public function plugin_page() {
        echo '<div class="wrap">';
            echo '<h2>'.esc_html__( 'Wishlist Settings','shopxpert' ).'</h2>';
            $this->save_message();
            $this->settings_api->show_navigation();
            $this->settings_api->show_forms();
        echo '</div>';
    }

    public function save_message() {
        if( isset( $_GET['settings-updated'] ) ) {
            ?>
                <div class="updated notice is-dismissible"> 
                    <p><strong><?php esc_html_e('Successfully Settings Saved.', 'shopxpert') ?></strong></p>
                </div>
            <?php
        }
    }

}