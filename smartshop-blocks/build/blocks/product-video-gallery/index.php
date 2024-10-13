<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$settings = $attributes;
$is_editor = ( isset( $_GET['is_editor_mode'] ) && $_GET['is_editor_mode'] == 'yes' ) ? true : false;
$uniqClass 	 = 'shopxpertblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'wlpro-product-videothumbnails' );

!empty( $settings['className'] ) ? $areaClasses[] = esc_attr( $settings['className'] ) : '';
!empty( $settings['tabThumbnailsPosition'] ) ? $areaClasses[] = 'thumbnails-tab-position-'.$settings['tabThumbnailsPosition'] : '';

global $post;
if( $is_editor ){
	$product = wc_get_product(shopxpert_get_last_product_id());
} else{
	$product = wc_get_product();
}
if ( empty( $product ) ) { return; }
if ( $product && !is_a( $product, 'WC_Product' ) ) {
	$product = wc_get_product( $post->ID );
}

$gallery_images_ids = $product->get_gallery_image_ids() ? $product->get_gallery_image_ids() : array();
if ( $product->get_image_id() ){
	array_unshift( $gallery_images_ids, $product->get_image_id() );
}

echo '<div class="'.esc_attr(implode(' ', $areaClasses )).'">';

?>
	<div class="wl-thumbnails-image-area">

		<?php if( $settings['tabThumbnailsPosition'] == 'left' || $settings['tabThumbnailsPosition'] == 'top' ): ?>
			<ul class="shopxpert-product-video-tabs">
				<?php
					$j=0;
					foreach ( $gallery_images_ids as $thkey => $gallery_attachment_id ) {
						$j++;
						if( $j == 1 ){ $tabactive = 'htactive'; }else{ $tabactive = ' '; }
						$video_url = get_post_meta( $gallery_attachment_id, 'shopxpert_video_url', true );
						?>
						<li class="<?php if( !empty( $video_url ) ){ echo 'wlvideothumb'; }?>">
							<a class="<?php echo esc_attr($tabactive); ?>" href="#wlvideo-<?php echo esc_attr($j); ?>">
								<?php
									if( !empty( $video_url ) ){
										echo '<span class="wlvideo-button"><i class="sli sli-control-play"></i></span>';
										echo wp_get_attachment_image( $gallery_attachment_id, 'woocommerce_gallery_thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									}else{
										echo wp_get_attachment_image( $gallery_attachment_id, 'woocommerce_gallery_thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									}
								?>
							</a>
						</li>
						<?php
					}
				?>
			</ul>
		<?php endif; ?>

		<div class="shopxpert-product-gallery-video">
			<?php
				if( $is_editor ){
					if ( $product->is_on_sale() ) { 
						echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( 'Sale!','shopxpert' ) . '</span>', $post, $product ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
				}else{
					shopxpert_show_product_sale_flash();
				}

				if(function_exists('shopxpert_custom_product_badge')){
					shopxpert_custom_product_badge();
				}

				$i = 0;
				foreach ( $gallery_images_ids as $thkey => $gallery_attachment_id ) {
					$i++;
					if( $i == 1 ){ $tabactive = 'htactive'; }else{ $tabactive = ' '; }
					$video_url = get_post_meta( $gallery_attachment_id, 'shopxpert_video_url', true );
					?>
					<div class="video-cus-tab-pane <?php echo esc_attr($tabactive); ?>" id="wlvideo-<?php echo esc_attr($i); ?>">
						<?php
							if( !empty( $video_url ) ){
								?>
									<div class="embed-responsive embed-responsive-16by9">
										<?php echo wp_oembed_get( $video_url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</div>
								<?php
							}else{
								echo wp_get_attachment_image( $gallery_attachment_id, 'woocommerce_single' );
							}
						?>
					</div>
					<?php
				}
			?>
		</div>

		<?php if( $settings['tabThumbnailsPosition'] == 'right' || $settings['tabThumbnailsPosition'] == 'bottom' ): ?>

			<ul class="shopxpert-product-video-tabs">
				<?php
					$j=0;
					foreach ( $gallery_images_ids as $thkey => $gallery_attachment_id ) {
						$j++;
						if( $j == 1 ){ $tabactive = 'htactive'; }else{ $tabactive = ' '; }
						$video_url = get_post_meta( $gallery_attachment_id, 'shopxpert_video_url', true );
						?>
						<li class="<?php if( !empty( $video_url ) ){ echo 'wlvideothumb'; }?>">
							<a class="<?php echo esc_attr($tabactive); ?>" href="#wlvideo-<?php echo esc_attr($j); ?>">
								<?php
									if( !empty( $video_url ) ){
										echo '<span class="wlvideo-button"><i class="sli sli-control-play"></i></span>';
										echo wp_get_attachment_image( $gallery_attachment_id, 'woocommerce_gallery_thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									}else{
										echo wp_get_attachment_image( $gallery_attachment_id, 'woocommerce_gallery_thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									}
								?>
							</a>
						</li>
						<?php
					}
				?>
			</ul>

		<?php endif; ?>

	</div>
	<script>
		;jQuery(document).ready(function($) {
			'use strict';

			var $default_data = {
				src:'',
				srcfull:'',
				srcset:'',
				sizes:'',
				width:'',
				height:'',
			};
			$( '.single_variation_wrap' ).on( 'show_variation', function ( event, variation ) {

				// Get First image data
				if( $default_data.src.length === 0 ){
					$default_data.srcfull = $('.shopxpert-product-gallery-video').find('.video-cus-tab-pane.htactive img').attr('src');
					$default_data.src = $('.shopxpert-product-gallery-video').find('.video-cus-tab-pane.htactive img').attr('src');
					$default_data.srcset = $('.shopxpert-product-gallery-video').find('.video-cus-tab-pane.htactive img').attr('srcset');
				}

				$('.shopxpert-product-gallery-video').find('.video-cus-tab-pane.htactive img').wc_set_variation_attr('src',variation.image.full_src);
				$('.shopxpert-product-gallery-video').find('.video-cus-tab-pane.htactive img').wc_set_variation_attr('srcset',variation.image.srcset);
				$('.shopxpert-product-gallery-video').find('.video-cus-tab-pane.htactive img').wc_set_variation_attr('src',variation.image.src);

				$('.variations').find('.reset_variations').on('click', function(e){
					$('.shopxpert-product-gallery-video').find('.video-cus-tab-pane.htactive img').wc_set_variation_attr('src', $default_data.srcfull );
					$('.shopxpert-product-gallery-video').find('.video-cus-tab-pane.htactive img').wc_set_variation_attr('srcset', $default_data.srcset );
				});

			});
		});
	</script>
<?php
        
echo '</div>';