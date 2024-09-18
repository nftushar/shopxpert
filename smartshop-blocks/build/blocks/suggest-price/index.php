<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$is_editor = ( isset( $_GET['is_editor_mode'] ) && $_GET['is_editor_mode'] == 'yes' ) ? true : false;

$uniqClass 	 = 'smartshopblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass );

!empty( $settings['className'] ) ? $areaClasses[] = esc_attr( $settings['className'] ) : '';


global $post;
if( $is_editor ){
	$product = wc_get_product(smartshop_get_last_product_id());
} else{
	$product = wc_get_product();
}
if ( empty( $product ) ) { return; }
if ( $product && !is_a( $product, 'WC_Product' ) ) {
	$product = wc_get_product( $post->ID );
}


$id = $settings['blockUniqId'];

echo '<div class="'.esc_attr(implode(' ', $areaClasses )).'">';
	?>
	<div class="wl-suggest-price">
		<?php
			if( isset( $_REQUEST['wlsubmit-'.$id] ) ){

				if ( ! isset( $_POST['smartshop_suggest_price_nonce_field'] ) || ! wp_verify_nonce( $_POST['smartshop_suggest_price_nonce_field'], 'smartshop_suggest_price_action' ) ){
					echo '<p class="wlsendmessage">'.esc_html__('Sorry, your nonce verification fail.','shopxper').'</p>';
				}else{
					$name     = $_POST['wlname'];
					$email    = $_POST['wlemail'];
					$message  = $_POST['wlmessage'];

					//php mailer variables
					$sentto  = $settings['sendToMail'];
					$subject = esc_html__("Suggest Price For - ".$product->get_title(), 'shopxper');
					$headers = esc_html__('From: ','shopxper'). esc_html( $email ) . "\r\n" . esc_html__('Reply-To: ', 'shopxper') . esc_html( $email ) . "\r\n";

					//Here put your Validation and send mail
					$sent = wp_mail( $sentto, $subject, wp_strip_all_tags($message), $headers );

					if( $sent ) {
						echo '<p class="wlsendmessage">'.esc_html( $settings['messageSuccess'] ).'</p>';
					}
					else{
						echo '<p class="wlsendmessage">'.esc_html($settings['messageError']).'</p>';
					}
				}
			}
		?>
		<button id="wlopenform-<?php echo esc_attr( $id ); ?>" class="wlsugget-button wlopen"><?php echo esc_html__( $settings['openButtonText'], 'shopxper' ); ?></button>
		<button id="wlcloseform-<?php echo esc_attr( $id ); ?>" class="wlsugget-button wlclose" style="display: none;"><?php echo esc_html__( $settings['closeButtonText'], 'shopxper' ); ?></button>
		<form id="wlsuggestform-<?php echo esc_attr( $id ); ?>" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>" method="post">
			<div class="wl-suggest-form-input">
				<input type="text" name="wlname" id="<?php echo esc_attr( 'wlname-' .$id );?>" placeholder="<?php echo esc_attr($settings['namePlaceholderText']);?>" />
			</div>
			<div class="wl-suggest-form-input">
				<input type="email" name="wlemail" id="<?php echo esc_attr( 'wlemail-' .$id );?>" placeholder="<?php echo esc_attr($settings['emailPlaceholderText']);?>" />
			</div>
			<div class="wl-suggest-form-input">
				<textarea name="wlmessage" id="<?php echo esc_attr('wlmessage-'.$id);?>" rows="4" cols="50" placeholder="<?php echo esc_attr($settings['messagePlaceholderText']);?>"></textarea>
			</div>
			<div class="wl-suggest-form-input">
				<input type="submit" name="<?php echo esc_attr( 'wlsubmit-' .$id );?>" id="<?php echo esc_attr( 'wlsubmit-' .$id );?>" value="<?php echo esc_attr($settings['submitButtonText']);?>" />
			</div>
			<?php wp_nonce_field( 'smartshop_suggest_price_action', 'smartshop_suggest_price_nonce_field' ); ?>
		</form>

	</div>

	<script type="text/javascript">
		;jQuery(document).ready(function($) {
		"use strict";

			var open_formbtn = '#wlopenform-<?php echo esc_attr($id); ?>';
			var close_formbtn = '#wlcloseform-<?php echo esc_attr($id); ?>';
			var terget_form = 'form#wlsuggestform-<?php echo esc_attr($id); ?>';
			$( open_formbtn ).on('click', function(){
				$(this).hide();
				$(this).siblings( close_formbtn ).show();
				$(this).siblings( terget_form ).slideDown('slow');
			});

			// Close Button
			$( close_formbtn ).on('click', function(){
				$(this).hide();
				$(this).siblings( open_formbtn ).show();
				$(this).siblings( terget_form ).slideUp('slow');
			});

		});
	</script>

	<?php
echo '</div>';