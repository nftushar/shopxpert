<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'smartshopblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass );
$classes 	 = array( 'wlspcial-banner' );

!empty( $settings['className'] ) ? $areaClasses[] = esc_attr( $settings['className'] ) : '';
!empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';

!empty( $settings['contentPosition'] ) ? $classes[] = 'smartshop-banner-content-pos-'.$settings['contentPosition'] : '';

$default_img_url = SMARTSHOP_BLOCK_URL . '/assets/images/banner-image.svg';
$banner_url 	= !empty( $settings['bannerLink'] ) ? $settings['bannerLink'] : '#';
$banner_image 	= !empty( $settings['bannerImage']['id'] ) ? wp_get_attachment_image( $settings['bannerImage']['id'], 'full' ) : '<img src="'.esc_url($default_img_url).'" alt="'.esc_attr__("Banner Default image",'smartshop').'" />';
$badge_image 	= !empty( $settings['badgeImage']['id'] ) ? wp_get_attachment_image( $settings['badgeImage']['id'], 'full' ) : '';

?>
<div class="<?php echo esc_attr(implode(' ', $areaClasses )); ?>">
	<div class="<?php echo esc_attr(implode(' ', $classes )); ?>">
		
		<div class="banner-thumb">
			<a href="<?php echo esc_url( $banner_url ); ?>">
				<?php echo $banner_image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</a>
		</div>

		<?php
			if( !empty( $badge_image ) ){
				echo '<div class="wlbanner-badgeimage">'.$badge_image.'</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		?>

		<div class="banner-content">
			<?php
				if( !empty( $settings['title'] ) ){
					echo '<h2>'.esc_html($settings['title']).'</h2>';
				}
				if( !empty( $settings['subTitle'] ) ){
					echo '<h6>'.esc_html( $settings['subTitle'] ).'</h6>';
				}
				if( !empty( $settings['offerAmount'] ) ){
					echo '<h5>'.esc_html($settings['offerAmount']).'<span>'.esc_html( $settings['offerTagLine'] ).'</span></h5>';
				}
				if( !empty( $settings['bannerDescription'] ) ){
					echo '<p>'.esc_html( $settings['bannerDescription'] ).'</p>';
				}

				if( !empty( $settings['buttonText'] ) ){
					echo '<a href="'.esc_url( $banner_url ).'">'.esc_html__( $settings['buttonText'],'smartshop' ).'</a>';
				}
			?>
		</div>

	</div>
</div>