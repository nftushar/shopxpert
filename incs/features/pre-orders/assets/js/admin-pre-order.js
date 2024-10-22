;(function($){
    "use strict";
    
    $('input#shopxpert_pre_order_enable').on('change',function( event ){
        var status = $(this).prop('checked'),
            parent = $(this).parents('p.form-field');
        if( status ){
            parent.siblings('div.shopxpert-pre-order-fields').removeClass('hidden');
        }else{
            parent.siblings('div.shopxpert-pre-order-fields').addClass('hidden');
        }
    });

    $('select#shopxpert_pre_order_manage_price').on('change',function( event ){
        var value = $(this).val();

        if( value != 'product_price' ){
            $('.shopxpert-mange-price').removeClass('hidden');
            if( value == 'fixed_price' ){
                $('.shopxpert_pre_order_amount_type_field').addClass('hidden');
            }else{
                $('.shopxpert_pre_order_amount_type_field').removeClass('hidden');
            }
        }else{
            $('.shopxpert-mange-price').addClass('hidden');
            $('.shopxpert_pre_order_amount_type_field').addClass('hidden');
        }

    });
    
})(jQuery);