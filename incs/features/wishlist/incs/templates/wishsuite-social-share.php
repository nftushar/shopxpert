<?php 
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	$idsString = is_array( $products_ids ) ? implode( ',',$products_ids ) : '';

	$share_link = get_the_permalink() . '?wishsuitepids='.$idsString;
	$share_title = get_the_title();

	$thumb_id = get_post_thumbnail_id();
	$thumb_url = wp_get_attachment_image_src( $thumb_id, 'thumbnail-size', true );

	$social_button_list = [
		'facebook' => [
			'title' => esc_html__( 'Facebook', 'shopxpert' ),
			'url' 	=> 'https://www.facebook.com/sharer/sharer.php?u='.$share_link,
		],
		'twitter' => [
			'title' => esc_html__( 'Twitter', 'shopxpert' ),
			'url' 	=> 'https://twitter.com/share?url=' . $share_link.'&amp;text='.$share_title,
		],
		'pinterest' => [
			'title' => esc_html__( 'Pinterest', 'shopxpert' ),
			'url' 	=> 'https://pinterest.com/pin/create/button/?url='.$share_link.'&media='.$thumb_url[0],
		],
		'linkedin' => [
			'title' => esc_html__( 'Linkedin', 'shopxpert' ),
			'url' 	=> 'https://www.linkedin.com/shareArticle?mini=true&url='.$share_link.'&amp;title='.$share_title,
		],
		'email' => [
			'title' => esc_html__( 'Email', 'shopxpert' ),
			'url' 	=> 'mailto:?subject='.esc_html__('Whislist&body=My whislist:', 'shopxpert') . $share_link,
		],
		'reddit' => [
			'title' => esc_html__( 'Reddit', 'shopxpert' ),
			'url' 	=> 'http://reddit.com/submit?url='.$share_link.'&amp;title='.$share_title,
		],
		'telegram' => [
			'title' => esc_html__( 'Telegram', 'shopxpert' ),
			'url' 	=> 'https://telegram.me/share/url?url=' . $share_link,
		],
		'odnoklassniki' => [
			'title' => esc_html__( 'Odnoklassniki', 'shopxpert' ),
			'url' 	=> 'https://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st._surl=' . $share_link,
		],
		'whatsapp' => [
			'title' => esc_html__( 'WhatsApp', 'shopxpert' ),
			'url' 	=> 'https://wa.me/?text=' . $share_link,
		],
		'vk' => [
			'title' => esc_html__( 'VK', 'shopxpert' ),
			'url' 	=> 'https://vk.com/share.php?url=' . $share_link,
		],
	];


	$default_buttons = [
        'facebook'   => esc_html__( 'Facebook', 'shopxpert' ),
        'twitter'    => esc_html__( 'Twitter', 'shopxpert' ),
        'pinterest'  => esc_html__( 'Pinterest', 'shopxpert' ),
        'linkedin'   => esc_html__( 'Linkedin', 'shopxpert' ),
        'telegram'   => esc_html__( 'Telegram', 'shopxpert' ),
    ];
	$button_list = shopxpert_get_option( 'social_share_buttons','wishsuite_table_settings_tabs', $default_buttons );
	$button_text = shopxpert_get_option( 'social_share_button_title','wishsuite_table_settings_tabs', 'Share:' );

	if( is_array($button_list) ){

?>

<div class="sxwishlist-social-share"> 
	<span class="sxwishlist-social-title"><?php echo esc_html__( $button_text, 'shopxpert' ); ?></span>

	<ul>
		<?php
			foreach ( $button_list as $buttonkey => $button ) {
				?>
				<li>
					<a rel="nofollow" href="<?php echo esc_url( $social_button_list[$buttonkey]['url'] ); ?>" <?php echo ( $buttonkey === 'email' ? '' : 'target="_blank"' ) ?>>
						<span class="sxwishlist-social-icon">
							<?php echo woowishsuite_icon_list( $buttonkey ); ?>
						</span>
					</a>
				</li>
				<?php
			}
		?>
	</ul>
</div>
<?php } ?>