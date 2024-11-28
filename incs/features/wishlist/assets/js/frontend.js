;(function($){
"use strict";
    
    var $body = $('body');

    // Add product in wishlist table
    if( 'on' !== WishList.option_data['btn_limit_login_off'] ){
        $body.on('click', 'a.wishlist-btn', function (e) {
            var $this = $(this),
                id = $this.data('product_id'),
                addedText = $this.data('added-text');

            e.preventDefault();

            $this.addClass('loading');

            $.ajax({
                url: WishList.ajaxurl,
                data: {
                    action: 'wishlist_add_to_list',
                    id: id,
                    nonce: WishList.wsnonce
                },
                dataType: 'json',
                method: 'GET',
                success: function ( response ) {
                    if ( response ) {
                        $this.removeClass('wishlist-btn');
                        $this.removeClass('loading').addClass('added');
                        $this.html( addedText );
                        $body.find('.wishlist-counter').html( response.data.item_count );
                    } else {
                        console.log( 'Something wrong loading compare data' );
                    }
                },
                error: function ( response ) {
                    console.log('Something wrong with AJAX response.', response );
                },
                complete: function () {
                    $this.removeClass('wishlist-btn');
                    $this.removeClass('loading').addClass('added');
                    $this.html( addedText );
                },
            });

        });
    }

    /**
     * AJAX Request for Remove item
     * @param {*} $this 
     * @param {*} $table 
     * @param {*} productId 
     * @param {*} message 
     */
    const wishListItemRemove = ($this, $table, productId, message = '')=>{
        $table.addClass('loading');

        $.ajax({
            url: WishList.ajaxurl,
            data: {
                action: 'wishlist_remove_from_list',
                id: productId,
                nonce: WishList.wsnonce
            },
            dataType: 'json',
            method: 'GET',
            success: function (response) {
                if ( response ) {

                    let totalPage = Math.ceil(response.data.item_count / response.data.per_page);
                    let currentUrl = window.location.href;
                    let newUrl = wishListGetPageNumberFromUrl(currentUrl) >= totalPage ? currentUrl.replace(/(\/page\/)(\d+)/, '$1' + (totalPage == 0 ? 1 : totalPage)) : currentUrl;

                    if( wishListGetPageNumberFromUrl(currentUrl) == totalPage ){
                        var target_row = $this.closest('tr');
                        target_row.hide(400, function() {
                            $(this).remove();
                            var table_row = $('.wishlist-table-content table tbody tr').length;
                            if( table_row == 1 ){
                                $('.wishlist-table-content table tbody tr.wishlist-empty-tr').show();
                            }
                        });
                    }
                    $body.find('.wishlist-counter').html( response.data.item_count );

                    window.history.pushState('page', 'Title', newUrl);
                    wishListDataRegenarate(newUrl);

                } else {
                    console.log( 'Something wrong loading compare data' );
                }
            },
            error: function (data) {
                console.log('Something wrong with AJAX response.');
            },
            complete: function () {
                // $table.removeClass('loading');
                // $this.addClass('loading');
            },
        });
    }

    // Remove data from wishlist table
    $body.on('click', 'a.wishlist-remove', function (e) {
        var $table = $('.wishlist-table-content');

        e.preventDefault();
        var $this = $(this),
            id = $this.data('product_id');

        wishListItemRemove($this, $table, id);

    });

    /**
     * Ajax Pagination
     */
    $body.on("click",'.wishlist-table-content .wishlist-pagination ul li a',function(e){
        e.preventDefault();
        let $this = $(this);
        let requestUrl = $this.attr("href");

        window.history.pushState('page', 'Title', requestUrl);
        wishListDataRegenarate(requestUrl);

    });
    /**
     * Regenerate Wishlist table data from URL
     */
    const wishListDataRegenarate = (requestUrl)=>{
        $('body .wishlist-table-content').addClass('loading');
        $.ajax({
            url: requestUrl,
            context: document.body
        }).success(function(data) {
            const allHtml = document.createRange().createContextualFragment(data);
            const tableContent = allHtml.querySelector(".wishlist-table-content");
            $('body .wishlist-table-content').removeClass('loading');
            $('body .wishlist-table-content').html(tableContent);
        });
    }
    /**
     * Get Current page number from URL
     * @param {current url} url 
     * @returns Page Number
     */
    const wishListGetPageNumberFromUrl = (url)=> {
        // Extract page number using a regular expression
        let match = url.match(/\/page\/(\d+)/);
        return match ? match[1] : null;
    }

    // Quentity
    $("div.wishlist-table-content").on("change", "input.qty", function() {
        $(this).closest('tr').find( "[data-quantity]" ).attr( "data-quantity", this.value );
    });

    // Delete table row after added to cart
    $(document).on('added_to_cart',function( e, fragments, carthash, button ){
        if( 'on' === WishList.option_data['after_added_to_cart'] ){

            let $table = $('.wishlist-table-content');
            let product_id = button.data('product_id');
            wishListItemRemove(button, $table, product_id);

        }
    });

    /**
     * Variation Product Add to cart from wishlist page
     */
    $(document).on( 'click', '.wishlist_table .product_type_variable.add_to_cart_button', function (e) {
        e.preventDefault();

        var $this = $(this),
            $product = $this.parents('.wishlist-product-add_to_cart').first(),
            $content = $product.find('.wishlist-quick-cart-form'),
            id = $this.data('product_id'),
            btn_loading_class = 'loading';

        if ($this.hasClass(btn_loading_class)) return;

        // Show Form
        if ( $product.hasClass('quick-cart-loaded') ) {
            $product.addClass('quick-cart-open');
            return;
        }

        var data = {
            action: 'wishlist_quick_variation_form',
            id: id,
            nonce: WishList.wsnonce
        };
        $.ajax({
            type: 'post',
            url: WishList.ajaxurl,
            data: data,
            beforeSend: function (response) {
                $this.addClass(btn_loading_class);
                $product.addClass('loading-quick-cart');
            },
            success: function (response) {
                $content.append( response );
                wishlist_render_variation_data( $product );
                wishlist_inser_to_cart();
            },
            complete: function (response) {
                setTimeout(function () {
                    $this.removeClass(btn_loading_class);
                    $product.removeClass('loading-quick-cart');
                    $product.addClass('quick-cart-open quick-cart-loaded');
                }, 100);
            },
        });

        return false;

    });

    $(document).on('click', '.wishlist-quick-cart-close', function () {
        var $this = $(this),
            $product = $this.parents('.wishlist-product-add_to_cart');
        $product.removeClass('quick-cart-open');
    });

    $(document.body).on('added_to_cart', function ( e, fragments, carthash, button ) {

        var target_row = button.closest('tr');
        target_row.find('.wishlist-addtocart').addClass('added');
        $('.wishlist-product-add_to_cart').removeClass('quick-cart-open');

    });

    /**
     * [wishlist_render_variation_data] show variation data
     * @param  {[selector]} $product
     * @return {[void]} 
     */
    function wishlist_render_variation_data( $product ) {
        $product.find('.variations_form').wc_variation_form().find('.variations select:eq(0)').change();
        $product.find('.variations_form').trigger('wc_variation_form');
    }

    /**
     * [wishlist_inser_to_cart] Add to cart
     * @return {[void]}
     */
    function wishlist_inser_to_cart(){

        $(document).on( 'click', '.wishlist-quick-cart-form .single_add_to_cart_button:not(.disabled)', function (e) {
            e.preventDefault();

            var $this = $(this),
                $form           = $this.closest('form.cart'),
                product_qty     = $form.find('input[name=quantity]').val() || 1,
                product_id      = $form.find('input[name=product_id]').val() || $this.val(),
                variation_id    = $form.find('input[name=variation_id]').val() || 0;

            $this.addClass('loading');

            /* For Variation product */    
            var item = {},
                variations = $form.find( 'select[name^=attribute]' );
                if ( !variations.length) {
                    variations = $form.find( '[name^=attribute]:checked' );
                }
                if ( !variations.length) {
                    variations = $form.find( 'input[name^=attribute]' );
                }

                variations.each( function() {
                    var $thisitem = $( this ),
                        attributeName = $thisitem.attr( 'name' ),
                        attributevalue = $thisitem.val(),
                        index,
                        attributeTaxName;
                        $thisitem.removeClass( 'error' );
                    if ( attributevalue.length === 0 ) {
                        index = attributeName.lastIndexOf( '_' );
                        attributeTaxName = attributeName.substring( index + 1 );
                        $thisitem.addClass( 'required error' );
                    } else {
                        item[attributeName] = attributevalue;
                    }
                });

            var data = {
                action: 'wishlist_insert_to_cart',
                product_id: product_id,
                product_sku: '',
                quantity: product_qty,
                variation_id: variation_id,
                variations: item,
                nonce: WishList.wsnonce
            };

            $( document.body ).trigger('adding_to_cart', [$this, data]);

            $.ajax({
                type: 'post',
                url:  WishList.ajaxurl,
                data: data,

                beforeSend: function (response) {
                    $this.removeClass('added').addClass('loading');
                },

                complete: function (response) {
                    $this.addClass('added').removeClass('loading');
                },

                success: function (response) {
                    if ( response.error & response.product_url ) {
                        window.location = response.product_url;
                        return;
                    } else {
                        $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $this]);
                    }
                },

            });

            return false;
        });

    }

    
    var wishlist_default_data = {
        price_html:'',
        image_html:'',
    };
    $(document).on('show_variation', '.wishlist_table .variations_form', function ( alldata, attributes, status ) {

        var target_row = alldata.target.closest('tr');

        // Get First image data
        if( typeof wishlist_default_data.price_html !== 'undefined' && wishlist_default_data.price_html.length === 0 ){
            wishlist_default_data.price_html = $(target_row).find('.wishlist-product-price').html();
            wishlist_default_data.image_html = $(target_row).find('.wishlist-product-image').html();
        }

        // Set variation data
        $(target_row).find('.wishlist-product-price').html( attributes.price_html );
        wishlist_variation_image_set( target_row, attributes.image );

        // reset data
        wishlist_variation_data_reset( target_row, wishlist_default_data );

    });

    // Reset data
    function wishlist_variation_data_reset( target_row, default_data ){
        $( target_row ).find('.reset_variations').on('click', function(e){
            $(target_row).find('.wishlist-product-price').html( default_data.price_html );
            $(target_row).find('.wishlist-product-image').html( default_data.image_html );
        });
    }

    // variation image set
    function wishlist_variation_image_set( target_row, image ){
        $(target_row).find('.wishlist-product-image img').wc_set_variation_attr('src',image.full_src);
        $(target_row).find('.wishlist-product-image img').wc_set_variation_attr('srcset',image.srcset);
        $(target_row).find('.wishlist-product-image img').wc_set_variation_attr('sizes',image.sizes);
    }


})(jQuery);