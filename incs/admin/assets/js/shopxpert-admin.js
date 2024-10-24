// alert("hello");


jQuery(document).ready(function($) {
    $('.datepicker').datepicker({
        dateFormat: 'yy-mm-dd' // Adjust the format as needed
    });
});


; (function ($) {
    "use strict";

    // Tab Menu
    function shopxpert_admin_tabs( $tabmenus, $tabpane ){
        $tabmenus.on('click', 'a', function(e){
            e.preventDefault();
            var $this = $(this),
                $target = $this.attr('href');
            $this.addClass('wlactive').parent().addClass('wlactive').siblings().removeClass('wlactive').children('a').removeClass('wlactive');
            $( $tabpane + $target ).addClass('wlactive').siblings().removeClass('wlactive');
        });
    }

    // // Navigation tabs Nested tabs
      shopxpert_admin_tabs( $(".shopxpert-nested-tabs"), '.shopxpert-admin-nested-tab-pane' );

    // // Extension Tabs
      shopxpert_admin_tabs( $(".shopxpert-admin-tabs"), '.shopxpert-admin-tab-pane' );

    // Navigation Tabs
    $('.shopxpert-admin-main-nav').on('click', '.shopxpert-admin-main-nav-btn', function (e) {
        e.preventDefault()
        const $this = $(this),
            $siblingsBtn = $this.closest('li').siblings().find('.shopxpert-admin-main-nav-btn'),
            $target = $this.attr('href')
        localStorage.setItem("wlActiveTab", $target);
        if (!$this.hasClass('wlactive')) {
            $this.addClass('wlactive')
            $siblingsBtn.removeClass('wlactive')
            $($target).addClass('wlactive').show().siblings().removeClass('wlactive').hide()
        }
    })
    if (localStorage.wlActiveTab !== undefined && localStorage.wlActiveTab !== null) {
        const $wlActiveTab = localStorage.getItem('wlActiveTab');
        let hasActiveElement = false;
        $('.shopxpert-admin-main-nav-btn').each(function () {
            const $this = $(this),
                $siblingsBtn = $this.closest('li').siblings().find('.shopxpert-admin-main-nav-btn')
            if ($this.attr('href') === $wlActiveTab) {
                $this.addClass('wlactive');
                $siblingsBtn.removeClass('wlactive');
                hasActiveElement = true;
                return false;
            }
        });
        if (hasActiveElement) {
            $($wlActiveTab).addClass('wlactive').show().siblings().removeClass('wlactive').hide()
        } else {
            var $defaultIndex = $('.shopxpert-admin-main-nav-btn').length;
            if ($defaultIndex > 0) {
                const $firstTab = $('.shopxpert-admin-main-nav-btn')[$defaultIndex - 1],
                    $target = $firstTab.hash;
                $firstTab.classList.add('wlactive');
                $($target).addClass('wlactive').show().siblings().removeClass('wlactive').hide();
            }
        }
    } else {
        var $defaultIndex = $('.shopxpert-admin-main-nav-btn').length;
        if ($defaultIndex > 0) {
            const $firstTab = $('.shopxpert-admin-main-nav-btn')[$defaultIndex - 1],
                $target = $firstTab.hash
            $firstTab.classList.add('wlactive')
            $($target).addClass('wlactive').show().siblings().removeClass('wlactive').hide()
        }
    }

    /* Number Input */
    $('.shopxpert-admin-number-btn').on('click', function(e){
        console.log("hello");
        
        e.preventDefault()
        const $this = $(this),
            $input = $this.parent('.shopxpert-admin-number').find('input[type="number"]')[0]
        if($this.hasClass('increase')) {
            $input.value = Number($input.value) + 1
        } else if($this.hasClass('decrease') && Number($input.value) > 1) {
            $input.value = Number($input.value) - 1
        }
    });

    // Footer Sticky Save Button
    var $adminHeaderArea = $('.shopxpert-admin-main-nav'),
        $stickyFooterArea = $('.shopxpert-admin-footer,.shopxpert-sticky-condition');

    if ($stickyFooterArea.length <= 0 || $adminHeaderArea.length <= 0) return;

    var totalOffset = $adminHeaderArea.offset().top + $adminHeaderArea.outerHeight();
    var footerSaveStickyToggler = function () {
        var windowScroll = $(window).scrollTop(),
            windowHeight = $(window).height(),
            documentHeight = $(document).height();

        if (totalOffset < windowScroll && windowScroll + windowHeight != documentHeight) {
            $stickyFooterArea.addClass('shopxpert-admin-sticky');
        } else if (windowScroll + windowHeight == documentHeight || totalOffset > windowScroll) {
            $stickyFooterArea.removeClass('shopxpert-admin-sticky');
        }
    };
    footerSaveStickyToggler();
    $(window).scroll(footerSaveStickyToggler);

    /* Pro Popup */
    /* Open */
    $('[data-shopxpert-pro="disabled"]').on('click', function (e) {
        e.preventDefault()
        const $popup = $('#shopxpert-admin-pro-popup')
        $popup.addClass('open')
    });
    /* Close */
    $('.shopxpert-admin-popup-close').on('click', function () {
        const $this = $(this),
            $popup = $this.closest('.shopxpert-admin-popup')
        $popup.removeClass('open')
    });
    /* Close on outside clicl */
    $(document).on('click', function (e) {
        if (e.target.classList.contains('shopxpert-admin-popup')) {
            e.target.classList.remove('open')
        }
    });

    /* Switch Enable/Disable Function */
    $('[data-switch-toggle]').on('click', function (e) {
        e.preventDefault();
console.log("JS Switch Enable/Disable Function");

        const $this = $(this),
            $type = $this.data('switch-toggle'),
            $target = $this.data('switch-target'),
            $switches = $(`[data-switch-id="${$target}"`)

        $switches.each(function () {
            const $switch = $(this)
            if ($switch.data('shopxpert') !== 'disabled') {
                const $input = $switch.find('input[type="checkbox"');
                var actionBtn = $switch.closest('.shopxpert-admin-switch-block-actions').find('.shopxpert-admin-switch-block-setting');
                if ($type === 'enable' && $input.is(":visible")) {
                    $input[0].setAttribute("checked", "checked");
                    $input[0].checked = true;
                    if (actionBtn.hasClass('shopxpert-visibility-none')) {
                        actionBtn.removeClass('shopxpert-visibility-none');
                    }
                }
                if ($type === 'disable' && $input.is(":visible")) {
                    $input[0].removeAttribute("checked");
                    $input[0].checked = false;
                    actionBtn.addClass('shopxpert-visibility-none');
                }

            }
        });

    });

    /* Select 2 */
    $('.shopxpert-admin-select select[multiple="multiple"]').each(function () {
        const $this = $(this),
            $parent = $this.parent();
        $this.select2({
            dropdownParent: $parent,
            placeholder: "Select template"
        });
    })

    /**
     * Admin Feature additional setting button
     */
    $('.shopxpert-admin-switch .checkbox').on('click', function (e) {

        var actionBtn = $(this).closest('.shopxpert-admin-switch-block-actions').find('.shopxpert-admin-switch-block-setting');
        if (actionBtn.hasClass('shopxpert-visibility-none')) {
            actionBtn.removeClass('shopxpert-visibility-none');
        } else {
            actionBtn.addClass('shopxpert-visibility-none');
        }
    });
 
    // Option data save
    $('.shopxpert-admin-btn-save').on('click', function (event) {
        event.preventDefault();

        var $option_form = $(this).closest('.shopxpert-admin-main-tab-pane').find('form.shopxpert-dashboard'),
            $savebtn = $(this),
            $section = $option_form.data('section'),
            $field_keys = $option_form.data('fields');

        $.ajax({
            url: SHOPXPERT_ADMIN.ajaxurl,
            type: 'POST',
            data: {
                nonce: SHOPXPERT_ADMIN.nonce,
                section: $section,
                fields: JSON.stringify($field_keys),
                action: 'shopxpert_save_opt_data',
                data: $option_form.serializeJSON() // Ensure this is formatted correctly
            },
            beforeSend: function () {
                $savebtn.text(SHOPXPERT_ADMIN.message.loading).addClass('updating-message');
            },
            success: function (response) {
                console.log("Response:", response);
                if (response.success) {
                    $savebtn.removeClass('updating-message').addClass('disabled').attr('disabled', true).text(SHOPXPERT_ADMIN.message.success);
                } else {
                    console.error('Error:', response.data.message);
                }
            },
            complete: function () {
                $savebtn.removeClass('updating-message').addClass('disabled').attr('disabled', true).text(SHOPXPERT_ADMIN.message.success);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error('AJAX Error:', textStatus, errorThrown);
                console.log('Response:', jqXHR.responseText);
            }
        });
    });

 

    // Save Button Enable
    $('.shopxpert-admin-main-tab-pane .shopxpert-dashboard').on('click', 'input,select,textarea,.shopxpert-admin-number-btn', function () { $(this).closest('.shopxpert-admin-main-tab-pane').find('.shopxpert-admin-btn-save').removeClass('disabled').attr('disabled', false).text(SHOPXPERT_ADMIN.message.btntxt);
    });

    $('.shopxpert-admin-main-tab-pane .shopxpert-dashboard').on('keyup', 'input', function () {
        $(this).closest('.shopxpert-admin-main-tab-pane').find('.shopxpert-admin-btn-save').removeClass('disabled').attr('disabled', false).text(SHOPXPERT_ADMIN.message.btntxt);
    });

    $('.shopxpert-admin-header-actions .shopxpert-admin-btn').on('click', function () {
        $(this).closest('.shopxpert-admin-main-tab-pane').find('.shopxpert-admin-btn-save').removeClass('disabled').attr('disabled', false).text(SHOPXPERT_ADMIN.message.btntxt);
    });

    $('.shopxpert-admin-main-tab-pane .shopxpert-dashboard').on('change', 'select.shopxpert-admin-select', function () {
        $(this).closest('.shopxpert-admin-main-tab-pane').find('.shopxpert-admin-btn-save').removeClass('disabled').attr('disabled', false).text(SHOPXPERT_ADMIN.message.btntxt);
    });


// Feature additional settings
$('.shopxpert-admin-switch-block-setting').on('click', function(event) {
    event.preventDefault(); 

    var $this = $(this),
        $section = $this.data('section'),
        $fields = $this.data('fields'),
        $fieldname = $this.data('fieldname') ? $this.data('fieldname') : '',
        content = null,
        Featurewrapper = wp.template('shopxpertFeature');

console.log("shopxpert Feature_data");


    $.ajax({
        url: SHOPXPERT_ADMIN.ajaxurl,
        type: 'POST',
        data: {
            nonce: SHOPXPERT_ADMIN.nonce,
            section: $section,
            fields: $fields,
            fieldname: $fieldname,
            action: 'shopxpert_Feature_data',
            subaction: 'get_data',
        },
        beforeSend: function(){
            $this.addClass('Feature-setting-loading');
        },
        success: function( response ) { 
            content = Featurewrapper( {
                section : $section,
                fileds  : response.data.fields,
                content : response.data.content
            } );
            $( 'body' ).append( content );

            shopxpert_Feature_ajax_reactive();
            $( document ).trigger('Feature_setting_loaded');
            $this.removeClass('Feature-setting-loading');
            
        },
        complete: function( response ) {
            $this.removeClass('Feature-setting-loading');
        },
        error: function(errorThrown){
            console.log(errorThrown);
        }
    });
});


 
    // PopUp reactive JS
    function shopxpert_Feature_ajax_reactive() { 
        // Select 2 Multiple selection
        $('.shopxpert-Feature-setting-popup').find('.shopxpert-admin-option:not(.shopxpert-repeater-field) .shopxpert-admin-select select[multiple="multiple"]').each(function () {
            const $this = $(this),
                $parent = $this.parent();
            $this.select2({
                dropdownParent: $parent,
                placeholder: "Select Item"
            });
        });

        //Initiate Color Picker
        // $('.shopxpert-Feature-setting-popup').find('.shopxpert-admin-option:not(.shopxpert-repeater-field) .wp-color-picker-field').wpColorPicker({
        //     change: function (event, ui) {
        //         $(this).closest('.shopxpert-Feature-setting-popup-content').find('.shopxpert-admin-Feature-save').removeClass('disabled').attr('disabled', false).text(SHOPXPERT_ADMIN.message.btntxt);
        //     },
        //     clear: function (event) {
        //         $(this).closest('.shopxpert-Feature-setting-popup-content').find('.shopxpert-admin-Feature-save').removeClass('disabled').attr('disabled', false).text(SHOPXPERT_ADMIN.message.btntxt);
        //     }
        // });

        // WPColor Picker Button disable.
        $('div[data-shopxpert-pro="disabled"] .wp-picker-container button').each(function () {
            $(this).attr("disabled", true);
        });

    //     /* Number Input */
        $('.shopxpert-admin-number-btn').on('click', function (e) {
            e.preventDefault()
            const $this = $(this),
                $input = $this.parent('.shopxpert-admin-number').find('input[type="number"]')[0]
            if ($this.hasClass('increase')) {
                $input.value = Number($input.value) + 1
            } else if ($this.hasClass('decrease') && Number($input.value) > 1) {
                $input.value = Number($input.value) - 1
            }
        });

        // Icon Picker
 

        // Media Uploader
        $('.shopxpert-browse').on('click', function (event) {
            event.preventDefault();

            var self = $(this);

            // Create the media frame.
            var file_frame = wp.media.frames.file_frame = wp.media({
                title: self.data('uploader_title'),
                button: {
                    text: self.data('uploader_button_text'),
                },
                multiple: false
            });

            file_frame.on('select', function () {
                var attachment = file_frame.state().get('selection').first().toJSON();
                self.prev('.shopxpert-url').val(attachment.url).change();
                self.siblings('.shopxpert_display').html('<img src="' + attachment.url + '" alt="' + attachment.title + '" />');
            });

            // Finally, open the modal
            file_frame.open();

        });

        // Remove Media Button
        $('.shopxpert-remove').on('click', function (event) {
            event.preventDefault();
            var self = $(this);
            self.siblings('.shopxpert-url').val('').change();
            self.siblings('.shopxpert_display').html('');
        });

        // Feature additional setting save
        $('.shopxpert-admin-Feature-save').on('click', function (event) {
            event.preventDefault();

            console.log('Feature additional setting save clicked');

            // Getting relevant form and button elements
            var $option_form = $(this).closest('.shopxpert-Feature-setting-popup-content').find('form.shopxpert-Feature-setting-data'),
                $savebtn = $(this),
                $section = $option_form.data('section'),
                $field_keys = $option_form.data('fields');

            // Debugging variables
            // console.log('xxSection:', $section);
            // console.log('xxFields:', $field_keys);
            // console.log('xxSerialized Data:', $(this).closest('.shopxpert-Feature-setting-popup-content').find('form.shopxpert-Feature-setting-data :input').not('.shopxpert-repeater-hidden :input').serializeJSON());

            // Ensure SHOPXPERT_ADMIN is defined
            if (typeof SHOPXPERT_ADMIN === 'undefined') {
                console.error('SHOPXPERT_ADMIN is not defined');
                return;
            }

            
            $.ajax({
                url: SHOPXPERT_ADMIN.ajaxurl, // URL to send the request to
                type: 'POST', // Method of request
                data: {
                    nonce: SHOPXPERT_ADMIN.nonce, // Nonce for security
                    section: $section, // Section data
                    fields: $field_keys, // Field keys data
                    action: 'shopxpert_save_opt_data', // Action to be handled in PHP
                    data: $(this).closest('.shopxpert-Feature-setting-popup-content').find('form.shopxpert-Feature-setting-data :input').not('.shopxpert-repeater-hidden :input').serializeJSON() // Serialized form data
                },
                beforeSend: function () {
                    // Update the button text and add a loading class
                    $savebtn.text(SHOPXPERT_ADMIN.message.loading).addClass('updating-message');
                },
                success: function (response) {
                    $savebtn.removeClass('updating-message').addClass('disabled').attr('disabled', true).text(SHOPXPERT_ADMIN.message.success); // Update the button state 
                },
                complete: function (response) {
                    console.log('AJAX Complete Response:', response); // Log the response
                    $savebtn.removeClass('updating-message').addClass('disabled').attr('disabled', true).text(SHOPXPERT_ADMIN.message.success); // Finalize the button state
                },

                error: function (errorThrown) {
                    console.error('AJAX Error:', errorThrown); // Log any errors
                }
            });
        });

        // Feature Setting Reset
        $('.shopxpert-admin-Feature-reset').on('click', function (event) {
            event.preventDefault();

console.log('Feature Setting Reset ajax');

            var $option_form = $(this).closest('.shopxpert-Feature-setting-popup-content').find('form.shopxpert-Feature-setting-data'),
                $resetbtn = $(this),
                $section = $option_form.data('section');

            Swal.fire({
                title: SHOPXPERT_ADMIN.message.sure,
                text: 'It will reset all the settings to default, and all the changes you made will be deleted.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: SHOPXPERT_ADMIN.message.yes,
                cancelButtonText: SHOPXPERT_ADMIN.message.cancel,
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: SHOPXPERT_ADMIN.ajaxurl,
                        type: 'POST',
                        data: {
                            nonce: SHOPXPERT_ADMIN.nonce,
                            section: $section,
                            action: 'shopxpert_Feature_data',
                            subaction: 'reset_data',
                        },

                        beforeSend: function () {
                            $resetbtn.removeClass('disabled').addClass('updating-message').text(SHOPXPERT_ADMIN.message.reseting);
                        },

                        success: function (response) { 
                            $resetbtn.removeClass('updating-message').addClass('disabled').attr('disabled', true).text(SHOPXPERT_ADMIN.message.reseted);
                        },

                        complete: function (response) {
                            $resetbtn.removeClass('updating-message').addClass('disabled').attr('disabled', true).text(SHOPXPERT_ADMIN.message.reseted);
                            window.location.reload();
                        },

                        error: function (errorThrown) {
                            console.log(errorThrown);
                        }

                    });


                }
            })

        });

        // Save button active
        $('.shopxpert-Feature-setting-popup-content .shopxpert-Feature-setting-data').on('click', 'input,select,textarea,.shopxpert-admin-number-btn', function () {
            $(this).closest('.shopxpert-Feature-setting-popup-content').find('.shopxpert-admin-Feature-save').removeClass('disabled').attr('disabled', false).text(SHOPXPERT_ADMIN.message.btntxt);
        });

        $('.shopxpert-Feature-setting-popup-content .shopxpert-Feature-setting-data').on('keyup', 'input', function () {
            $(this).closest('.shopxpert-Feature-setting-popup-content').find('.shopxpert-admin-Feature-save').removeClass('disabled').attr('disabled', false).text(SHOPXPERT_ADMIN.message.btntxt);
        });

        $('.shopxpert-Feature-setting-popup-content .shopxpert-Feature-setting-data').on('change', 'select', function () {
            $(this).closest('.shopxpert-Feature-setting-popup-content').find('.shopxpert-admin-Feature-save').removeClass('disabled').attr('disabled', false).text(SHOPXPERT_ADMIN.message.btntxt);
        });

        /* Close PopUp */
        $('.shopxpert-admin-popup-close').on('click', function () {
            const $this = $(this),
                $popup = $this.closest('.shopxpert-admin-popup')
            $popup.removeClass('open')
        });

        // Repeater Field
        shopxpert_repeater_field();

        // Field Dependency
        $(document).ready(function () {
            $('.shopxpert-Feature-setting-data').shopxpert_conditions();
        });

    }

    /* Repeater Item control */
    $(document).on('repeater_field_added', function (e, hidden_repeater_elem) {

        $(hidden_repeater_elem).find('.shopxpert-admin-select select[multiple="multiple"]').each(function () {
            const $this = $(this),
                $parent = $this.parent();
            $this.select2({
                dropdownParent: $parent,
                placeholder: "Select template"
            });
        });

        // $(hidden_repeater_elem).find('.wp-color-picker-field').each(function () {
        //     $(this).wpColorPicker({
        //         change: function (event, ui) {
        //             $(this).closest('.shopxpert-Feature-setting-popup-content').find('.shopxpert-admin-Feature-save').removeClass('disabled').attr('disabled', false).text(SHOPXPERT_ADMIN.message.btntxt);
        //         },
        //         clear: function (event) {
        //             $(this).closest('.shopxpert-Feature-setting-popup-content').find('.shopxpert-admin-Feature-save').removeClass('disabled').attr('disabled', false).text(SHOPXPERT_ADMIN.message.btntxt);
        //         }
        //     });
        // });

    });

    function shopxpert_repeater_field() {

        /* Add field */
        $('.shopxpert-repeater-item-add').on('click', function (e) {
            e.preventDefault();

            var $this = $(this),
                $hidden = $this.prev('.shopxpert-repeater-hidden').clone(true),
                $insert_location = $this.closest('.shopxpert-reapeater-fields-area').find('div.shopxpert-option-repeater-item:not(.shopxpert-repeater-hidden):last'),
                $itemCount = $this.closest('.shopxpert-reapeater-fields-area').find('.shopxpert-option-repeater-item:not(.shopxpert-repeater-hidden)').length,
                $addLimit = typeof $this.attr('data-limit') !== 'undefined' ? parseInt($this.attr('data-limit')) : '';

            // If already reach adding limit
            if ($addLimit != '' && $addLimit <= $itemCount) {

                Swal.fire({
                    title: 'Upgrade to Premium version',
                    text: 'With the free version, you can add 2 currencies. To unlock more currencies and advanced features, please upgrade to the pro version.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#ddd',
                    confirmButtonText: 'Upgrade Now',
                    cancelButtonText: 'Not Now',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.open('https://shopxpert.com/pricing/?utm_source=admin&utm_medium=lockfeatures&utm_campaign=free', '_blank');
                    }
                })

                return false;
            }

            $hidden.attr('data-id', $itemCount);
            $('.shopxpert-option-repeater-item-area .shopxpert-option-repeater-item').removeClass('shopxpert_active_repeater');
            $hidden.removeClass('shopxpert-repeater-hidden').addClass('shopxpert_active_repeater');
            $hidden.insertAfter($insert_location);

            if ($insert_location.length == 0) {
                $this.closest('.shopxpert-reapeater-fields-area').find('.shopxpert-option-repeater-item-area').html($hidden);
            }

            $(document).trigger('repeater_field_added', [$('.shopxpert-Feature-setting-data .shopxpert-option-repeater-item.shopxpert_active_repeater')]);
            $(document).trigger('repeater_field_item_added', [$('.shopxpert-Feature-setting-data .shopxpert-option-repeater-item.shopxpert_active_repeater')]);

            // Title Value update after add.
            $('.shopxpert-Feature-setting-data .shopxpert-option-repeater-item.shopxpert_active_repeater').find('.shopxpert-repeater-title-field :input').trigger('change');

            // Field Dependency
            $('.shopxpert-option-repeater-item-area').children('.shopxpert-option-repeater-item').children('.shopxpert-option-repeater-fields').shopxpert_conditions();

            // Enable Button
            $('.shopxpert-admin-Feature-save').removeClass('disabled').attr('disabled', false).text(SHOPXPERT_ADMIN.message.btntxt);

            return false;

        });

        // Change Heading using title field value
        $('.shopxpert-repeater-title-field :input').on('keyup change', function (event) {
            let titleValue = event.currentTarget.tagName == 'SELECT' ? event.currentTarget.options[event.currentTarget.selectedIndex].text : $(this).val();
            $(this).closest('.shopxpert-option-repeater-fields').siblings('.shopxpert-option-repeater-tools').find('.shopxpert-option-repeater-item-title').html(titleValue);
        });

        // Hide Show Manage
        $('.shopxpert-option-repeater-item').on('click', '.shopxpert-option-repeater-tools', function () {
            const $this = $(this),
                $parentItem = $this.parent();
            if ($parentItem.hasClass('shopxpert_active_repeater')) {
                $parentItem.removeClass('shopxpert_active_repeater');
            } else {
                $parentItem.addClass('shopxpert_active_repeater').siblings().removeClass('shopxpert_active_repeater');
                $(document).trigger('repeater_field_added', [$('.shopxpert-Feature-setting-data .shopxpert-option-repeater-item.shopxpert_active_repeater')]);
                $(document).trigger('repeater_field_item_active', [$parentItem]);
            }
            $('.shopxpert-option-repeater-item-area').children('.shopxpert-option-repeater-item').children('.shopxpert-option-repeater-fields').shopxpert_conditions();
        });

        // Remove Element
        $('.shopxpert-option-repeater-item-remove').on('click', function (event) {

            const $this = $(this),
                $parentItem = $this.parents('.shopxpert-option-repeater-item'),
                $fieldsArea = $parentItem.parents('.shopxpert-reapeater-fields-area');

            $parentItem.remove();

            // ID Re-Order
            $('.shopxpert-option-repeater-item:not(.shopxpert-repeater-hidden)').each(function (index) {
                $(this).attr('data-id', index);
            });

            $(document).trigger('repeater_field_item_removed', [$parentItem, $fieldsArea]);

            // Enable Button
            $('.shopxpert-admin-Feature-save').removeClass('disabled').attr('disabled', false).text(SHOPXPERT_ADMIN.message.btntxt);

            return false;
        });

        // Initiate sortable Field
        if ($(".shopxpert-option-repeater-item-area").length > 0) {
            $(".shopxpert-option-repeater-item-area").sortable({
                axis: 'y',
                connectWith: ".shopxpert-option-repeater-item",
                handle: ".shopxpert-option-repeater-tools",
                placeholder: "widget-placeholder",
                update: function (event, ui) {
                    $('.shopxpert-admin-Feature-save').removeClass('disabled').attr('disabled', false).text(SHOPXPERT_ADMIN.message.btntxt);
                    $('.shopxpert-option-repeater-item-area').children('.shopxpert-option-repeater-item').children('.shopxpert-option-repeater-fields').shopxpert_conditions();
                }
            });
        }

        /**
         * Repeater Custom Button
         */
        $('.shopxpert-repeater-custom-action').on('click', function () {

// console.log("epeater Custom Button ajax");

            let $this = $(this),
                $fieldsArea = $this.siblings('.shopxpert-option-repeater-item-area'),
                $data = typeof $this.attr('data-customaction') !== 'undefined' ? JSON.parse($this.attr('data-customaction')) : '',
                $fieldValue = $(document).find($data['option_selector']),
                $FeatureSaveButton = $('.shopxpert-admin-Feature-save');

            if (typeof $fieldValue !== 'undefined') {
                $data = { ...$data, value: $fieldValue.val() }
            }

            $.ajax({
                url: SHOPXPERT_ADMIN.ajaxurl,
                type: 'POST',
                data: {
                    nonce: SHOPXPERT_ADMIN.nonce,
                    action: 'shopxpert_repeater_custom_action',
                    data: $data
                },

                beforeSend: function () {
                    $this.removeClass('disabled').addClass('updating-message');
                    // Enable Feature Data save button
                    $FeatureSaveButton.removeClass('disabled').attr('disabled', false).text(SHOPXPERT_ADMIN.message.btntxt);
                },

                success: function (response) { 
                    $this.removeClass('updating-message');
                    $(document).trigger('repeater_custom_action_start', [$data, $fieldsArea, response.data]);
                    // Save Feature Data
                    $FeatureSaveButton.trigger('click');
                },

                complete: function (response) {
                    $this.removeClass('updating-message');
                    // Save Feature Data
                    $FeatureSaveButton.trigger('click');
                },

                error: function (errorThrown) {
                    console.log(errorThrown);
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Something went wrong! Try again later.",
                    });
                }

            });

        });



        /**
         * For Currency Switcher Feature
         */
        $(document).on('change', '.wlcs-currency-selection .shopxpert-admin-select select', function (e) {
            let thisField = $(this),
                item = thisField.closest('.shopxpert-option-repeater-item'),
                fieldsArea = item.closest('.shopxpert-reapeater-fields-area'),
                uniqueIdWrap = item.find('.wlcs-currency-selection');

            if ((true === uniqueIdWrap.hasClass('wlcs-currency-selection-field')) && ('undefined' !== typeof fieldsArea)) {
                $(document).trigger('country_default_select_refresh', [fieldsArea]);
            }
        });
        $(document).on('repeater_field_item_removed', function (e, item, fieldsArea) {
            let uniqueIdWrap = item.find('.wlcs-currency-selection');
            if ((true === uniqueIdWrap.hasClass('wlcs-currency-selection-field')) && ('undefined' !== typeof fieldsArea)) {
                $(document).trigger('country_default_select_refresh', [fieldsArea]);
            }
        });

        /**
         * Change Default Currency Switcher value select refresh.
         */
        $(document).on('country_default_select_refresh', function (e, itemsArea) {
            if (0 < itemsArea.length) {
                let items = itemsArea.find('.shopxpert-option-repeater-item:not(.shopxpert-repeater-hidden)'),
                    selects = $(document).find('.wlcs-default-selection .shopxpert-admin-select select'),
                    options = {};

                $.each(items, function () {
                    let thisItem = $(this),
                        selectItem = thisItem.find('.wlcs-currency-selection .shopxpert-admin-select select option:selected'),
                        label = selectItem.text(),
                        currencyCode = selectItem.val(),
                        title = '';

                    if (('undefined' !== typeof label)) {
                        label = label.trim();
                        if (0 < label.length) {
                            title = label;
                        }
                        options[currencyCode] = title;
                    }
                });

                $.each(selects, function () {
                    let thisSelect = $(this),
                        optionsHTML = '',
                        selectValue = thisSelect.val();

                    $.each(options, function (optionId, optionTitle) {
                        optionsHTML += '<option value="' + optionId + '">' + optionTitle + '</option>';
                    });

                    thisSelect.html(optionsHTML).val(selectValue).change();

                });

            }
        });

        // Currency Exchange Rate Field update
        $(document).on('repeater_custom_action_start', function (e, buttonData, itemsArea, response) {
            let repeaterFields = itemsArea.children();
            repeaterFields.map((index, child) => {
                let currencyCode = $(child).find('.wlcs-currency-selection .shopxpert-admin-select select').val();
                if (response[currencyCode]) {
                    const exchangeRate = parseFloat(response[currencyCode]).toFixed(2);
                    $(child).find('.wlcs-currency-dynamic-exchange-rate .shopxpert-admin-number input').val(exchangeRate);
                }
            });

            Swal.fire({
                title: "Success!",
                text: "The exchange rates for every added currency have been updated based on the selected default currency.",
                icon: "success"
            });

        });
    }
    shopxpert_repeater_field();

    // Extension Tabs
    // shopxpert_admin_tabs( $(".shopxpert-admin-tabs"), '.shopxpert-admin-tab-pane' );

    // Field Dependency
    $(document).ready(function () {
        $('.shopxpert-dashboard').children('.shopxpert-admin-options').shopxpert_conditions();
    });

})(jQuery);