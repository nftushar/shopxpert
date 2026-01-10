<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Prepare product IDs string
$idsString = is_array( $products_ids ) ? implode( ',', $products_ids ) : '';

// Share link and title
$share_link  = get_the_permalink() . '?wishlistpids=' . $idsString;
$share_title = get_the_title();

// Get post thumbnail URL (fallback to empty string)
$thumb_id  = get_post_thumbnail_id();
$thumb_url = $thumb_id ? wp_get_attachment_image_src( $thumb_id, 'thumbnail-size', true ) : [''];

// Define social share URLs
$social_button_list = [
    'facebook' => [
        'title' => esc_html__( 'Facebook', 'shopxpert' ),
        'url'   => 'https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode( $share_link ),
    ],
    'twitter' => [
        'title' => esc_html__( 'Twitter', 'shopxpert' ),
        'url'   => 'https://twitter.com/share?url=' . rawurlencode( $share_link ) . '&text=' . rawurlencode( $share_title ),
    ],
    'pinterest' => [
        'title' => esc_html__( 'Pinterest', 'shopxpert' ),
        'url'   => 'https://pinterest.com/pin/create/button/?url=' . rawurlencode( $share_link ) . '&media=' . rawurlencode( $thumb_url[0] ),
    ],
    'linkedin' => [
        'title' => esc_html__( 'Linkedin', 'shopxpert' ),
        'url'   => 'https://www.linkedin.com/shareArticle?mini=true&url=' . rawurlencode( $share_link ) . '&title=' . rawurlencode( $share_title ),
    ],
    'email' => [
        'title' => esc_html__( 'Email', 'shopxpert' ),
        'url'   => 'mailto:?subject=' . rawurlencode( 'Wishlist' ) . '&body=' . rawurlencode( 'My wishlist: ' . $share_link ),
    ],
    'reddit' => [
        'title' => esc_html__( 'Reddit', 'shopxpert' ),
        'url'   => 'http://reddit.com/submit?url=' . rawurlencode( $share_link ) . '&title=' . rawurlencode( $share_title ),
    ],
    'telegram' => [
        'title' => esc_html__( 'Telegram', 'shopxpert' ),
        'url'   => 'https://telegram.me/share/url?url=' . rawurlencode( $share_link ),
    ],
    'odnoklassniki' => [
        'title' => esc_html__( 'Odnoklassniki', 'shopxpert' ),
        'url'   => 'https://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st._surl=' . rawurlencode( $share_link ),
    ],
    'whatsapp' => [
        'title' => esc_html__( 'WhatsApp', 'shopxpert' ),
        'url'   => 'https://wa.me/?text=' . rawurlencode( $share_link ),
    ],
    'vk' => [
        'title' => esc_html__( 'VK', 'shopxpert' ),
        'url'   => 'https://vk.com/share.php?url=' . rawurlencode( $share_link ),
    ],
];

// Default buttons to show
$default_buttons = [
    'facebook'  => esc_html__( 'Facebook', 'shopxpert' ),
    'twitter'   => esc_html__( 'Twitter', 'shopxpert' ),
    'pinterest' => esc_html__( 'Pinterest', 'shopxpert' ),
    'linkedin'  => esc_html__( 'Linkedin', 'shopxpert' ),
    'telegram'  => esc_html__( 'Telegram', 'shopxpert' ),
];

// Get user-selected buttons from settings
$button_list  = WishList_get_option( 'social_share_buttons', 'wishlist_table_settings_tabs', $default_buttons );
$button_text  = trim( WishList_get_option( 'social_share_button_title', 'wishlist_table_settings_tabs', 'Share:' ) );

// Render only if button list is valid
if ( ! empty( $button_list ) && is_array( $button_list ) ): ?>
    <div class="wishlist-social-share"> 
        <span class="wishlist-social-title"><?php echo esc_html( $button_text ); ?></span>
        <ul>
            <?php foreach ( $button_list as $buttonkey => $button ):
                if ( ! isset( $social_button_list[ $buttonkey ] ) ) continue;
                $target = ( $buttonkey === 'email' ) ? '' : 'target="_blank"';
            ?>
                <li>
                    <a rel="nofollow" href="<?php echo esc_url( $social_button_list[ $buttonkey ]['url'] ); ?>" <?php echo $target; ?>>
                        <span class="wishlist-social-icon">
                            <?php echo WishList_icon_list( $buttonkey ); ?>
                        </span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
