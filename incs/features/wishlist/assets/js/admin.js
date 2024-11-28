;(function($){
"use strict";

    // Active settigns menu item
    if ( typeof WishList.is_settings != "undefined" && WishList.is_settings == 1 ){
        $('.toplevel_page_wishlist .wp-first-item').addClass('current');
    }

    // Save value
    wishlistConditionField( WishList.option_data['btn_icon_type'], 'custom', '.button_custom_icon' );
    wishlistConditionField( WishList.option_data['added_btn_icon_type'], 'custom', '.addedbutton_custom_icon' );
    wishlistConditionField( WishList.option_data['shop_btn_position'], 'use_shortcode', '.depend_shop_btn_position_use_shortcode' );
    wishlistConditionField( WishList.option_data['shop_btn_position'], 'custom_position', '.depend_shop_btn_position_custom_hook' );
    wishlistConditionField( WishList.option_data['product_btn_position'], 'use_shortcode', '.depend_product_btn_position_use_shortcode' );
    wishlistConditionField( WishList.option_data['product_btn_position'], 'custom_position', '.depend_product_btn_position_custom_hook' );
    wishlistConditionField( WishList.option_data['button_style'], 'custom', '.button_custom_style' );
    wishlistConditionField( WishList.option_data['table_style'], 'custom', '.table_custom_style' );
    wishlistConditionField( WishList.option_data['enable_social_share'], 'on', '.depend_social_share_enable' );
    wishlistConditionField( WishList.option_data['enable_login_limit'], 'on', '.depend_user_login_enable' );

    // After Select field change Condition Field
    wishlistChangeField( '.button_icon_type select', '.button_custom_icon', 'custom' );
    wishlistChangeField( '.addedbutton_icon_type select', '.addedbutton_custom_icon', 'custom' );
    wishlistChangeField( '.shop_btn_position select', '.depend_shop_btn_position_use_shortcode', 'use_shortcode' );
    wishlistChangeField( '.shop_btn_position select', '.depend_shop_btn_position_custom_hook', 'custom_position' );
    wishlistChangeField( '.product_btn_position select', '.depend_product_btn_position_use_shortcode', 'use_shortcode' );
    wishlistChangeField( '.product_btn_position select', '.depend_product_btn_position_custom_hook', 'custom_position' );
    wishlistChangeField( '.button_style select', '.button_custom_style', 'custom' );
    wishlistChangeField( '.table_style select', '.table_custom_style', 'custom' );
    wishlistChangeField( '.enable_social_share .checkbox', '.depend_social_share_enable', 'on', 'radio' );
    wishlistChangeField( '.enable_login_limit .checkbox', '.depend_user_login_enable', 'on', 'radio' );

    function wishlistChangeField( filedselector, selector, condition_value, fieldtype = 'select' ){
        $(filedselector).on('change',function(){
            var change_value = '';

            if( fieldtype === 'radio' ){
                if( $(this).is(":checked") ){
                    change_value = $(this).val();
                }
            }else{
                change_value = $(this).val();
            }

            wishlistConditionField( change_value, condition_value, selector );
        });
    }

    // Hide || Show
    function wishlistConditionField( value, condition_value, selector ){
        if( value === condition_value ){
            $(selector).show();
        }else{
            $(selector).hide();
        }
    }

})(jQuery);