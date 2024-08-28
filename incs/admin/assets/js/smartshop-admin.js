// alert("hello");

; (function ($) {
    "use strict";

    // Tab Menu
    function smartshop_admin_tabs( $tabmenus, $tabpane ){
        $tabmenus.on('click', 'a', function(e){
            e.preventDefault();
            var $this = $(this),
                $target = $this.attr('href');
            $this.addClass('wlactive').parent().addClass('wlactive').siblings().removeClass('wlactive').children('a').removeClass('wlactive');
            $( $tabpane + $target ).addClass('wlactive').siblings().removeClass('wlactive');
        });
    }

    // // Navigation tabs Nested tabs
      smartshop_admin_tabs( $(".smartshop-nested-tabs"), '.smartshop-admin-nested-tab-pane' );

    // // Extension Tabs
      smartshop_admin_tabs( $(".smartshop-admin-tabs"), '.smartshop-admin-tab-pane' );

    // Navigation Tabs
    $('.smartshop-admin-main-nav').on('click', '.smartshop-admin-main-nav-btn', function (e) {
        e.preventDefault()
        const $this = $(this),
            $siblingsBtn = $this.closest('li').siblings().find('.smartshop-admin-main-nav-btn'),
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
        $('.smartshop-admin-main-nav-btn').each(function () {
            const $this = $(this),
                $siblingsBtn = $this.closest('li').siblings().find('.smartshop-admin-main-nav-btn')
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
            var $defaultIndex = $('.smartshop-admin-main-nav-btn').length;
            if ($defaultIndex > 0) {
                const $firstTab = $('.smartshop-admin-main-nav-btn')[$defaultIndex - 1],
                    $target = $firstTab.hash;
                $firstTab.classList.add('wlactive');
                $($target).addClass('wlactive').show().siblings().removeClass('wlactive').hide();
            }
        }
    } else {
        var $defaultIndex = $('.smartshop-admin-main-nav-btn').length;
        if ($defaultIndex > 0) {
            const $firstTab = $('.smartshop-admin-main-nav-btn')[$defaultIndex - 1],
                $target = $firstTab.hash
            $firstTab.classList.add('wlactive')
            $($target).addClass('wlactive').show().siblings().removeClass('wlactive').hide()
        }
    }

    /* Number Input */
    $('.smartshop-admin-number-btn').on('click', function (e) {
        e.preventDefault()
        const $this = $(this),
            $input = $this.parent('.smartshop-admin-number').find('input[type="number"]')[0]
        if ($this.hasClass('increase')) {
            $input.value = Number($input.value) + 1
        } else if ($this.hasClass('decrease') && Number($input.value) > 1) {
            $input.value = Number($input.value) - 1
        }
    });

    // Footer Sticky Save Button
    var $adminHeaderArea = $('.smartshop-admin-main-nav'),
        $stickyFooterArea = $('.smartshop-admin-footer,.smartshop-sticky-condition');

    if ($stickyFooterArea.length <= 0 || $adminHeaderArea.length <= 0) return;

    var totalOffset = $adminHeaderArea.offset().top + $adminHeaderArea.outerHeight();
    var footerSaveStickyToggler = function () {
        var windowScroll = $(window).scrollTop(),
            windowHeight = $(window).height(),
            documentHeight = $(document).height();

        if (totalOffset < windowScroll && windowScroll + windowHeight != documentHeight) {
            $stickyFooterArea.addClass('smartshop-admin-sticky');
        } else if (windowScroll + windowHeight == documentHeight || totalOffset > windowScroll) {
            $stickyFooterArea.removeClass('smartshop-admin-sticky');
        }
    };
    footerSaveStickyToggler();
    $(window).scroll(footerSaveStickyToggler);

    /* Pro Popup */
    /* Open */
    $('[data-smartshop-pro="disabled"]').on('click', function (e) {
        e.preventDefault()
        const $popup = $('#smartshop-admin-pro-popup')
        $popup.addClass('open')
    });
    /* Close */
    $('.smartshop-admin-popup-close').on('click', function () {
        const $this = $(this),
            $popup = $this.closest('.smartshop-admin-popup')
        $popup.removeClass('open')
    });
    /* Close on outside clicl */
    $(document).on('click', function (e) {
        if (e.target.classList.contains('smartshop-admin-popup')) {
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
            if ($switch.data('smartshop-pro') !== 'disabled') {
                const $input = $switch.find('input[type="checkbox"');
                var actionBtn = $switch.closest('.smartshop-admin-switch-block-actions').find('.smartshop-admin-switch-block-setting');
                if ($type === 'enable' && $input.is(":visible")) {
                    $input[0].setAttribute("checked", "checked");
                    $input[0].checked = true;
                    if (actionBtn.hasClass('smartshop-visibility-none')) {
                        actionBtn.removeClass('smartshop-visibility-none');
                    }
                }
                if ($type === 'disable' && $input.is(":visible")) {
                    $input[0].removeAttribute("checked");
                    $input[0].checked = false;
                    actionBtn.addClass('smartshop-visibility-none');
                }

            }
        });

    });

    /* Select 2 */
    $('.smartshop-admin-select select[multiple="multiple"]').each(function () {
        const $this = $(this),
            $parent = $this.parent();
        $this.select2({
            dropdownParent: $parent,
            placeholder: "Select template"
        });
    })

    /**
     * Admin Module additional setting button
     */
    $('.smartshop-admin-switch .checkbox').on('click', function (e) {

        var actionBtn = $(this).closest('.smartshop-admin-switch-block-actions').find('.smartshop-admin-switch-block-setting');
        if (actionBtn.hasClass('smartshop-visibility-none')) {
            actionBtn.removeClass('smartshop-visibility-none');
        } else {
            actionBtn.addClass('smartshop-visibility-none');
        }
    });
 
    // Option data save
    $('.smartshop-admin-btn-save').on('click', function (event) {
        event.preventDefault();

        var $option_form = $(this).closest('.smartshop-admin-main-tab-pane').find('form.smartshop-dashboard'),
            $savebtn = $(this),
            $section = $option_form.data('section'),
            $field_keys = $option_form.data('fields');

        $.ajax({
            url: SMARTSHOP_ADMIN.ajaxurl,
            type: 'POST',
            data: {
                nonce: SMARTSHOP_ADMIN.nonce,
                section: $section,
                fields: JSON.stringify($field_keys),
                action: 'smartshop_save_opt_data',
                data: $option_form.serializeJSON() // Ensure this is formatted correctly
            },
            beforeSend: function () {
                $savebtn.text(SMARTSHOP_ADMIN.message.loading).addClass('updating-message');
            },
            success: function (response) {
                console.log("Response:", response);
                if (response.success) {
                    $savebtn.removeClass('updating-message').addClass('disabled').attr('disabled', true).text(SMARTSHOP_ADMIN.message.success);
                } else {
                    console.error('Error:', response.data.message);
                }
            },
            complete: function () {
                $savebtn.removeClass('updating-message').addClass('disabled').attr('disabled', true).text(SMARTSHOP_ADMIN.message.success);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error('AJAX Error:', textStatus, errorThrown);
                console.log('Response:', jqXHR.responseText);
            }
        });
    });

 

    // Save Button Enable
    $('.smartshop-admin-main-tab-pane .smartshop-dashboard').on('click', 'input,select,textarea,.smartshop-admin-number-btn', function () { $(this).closest('.smartshop-admin-main-tab-pane').find('.smartshop-admin-btn-save').removeClass('disabled').attr('disabled', false).text(SMARTSHOP_ADMIN.message.btntxt);
    });

    $('.smartshop-admin-main-tab-pane .smartshop-dashboard').on('keyup', 'input', function () {
        $(this).closest('.smartshop-admin-main-tab-pane').find('.smartshop-admin-btn-save').removeClass('disabled').attr('disabled', false).text(SMARTSHOP_ADMIN.message.btntxt);
    });

    $('.smartshop-admin-header-actions .smartshop-admin-btn').on('click', function () {
        $(this).closest('.smartshop-admin-main-tab-pane').find('.smartshop-admin-btn-save').removeClass('disabled').attr('disabled', false).text(SMARTSHOP_ADMIN.message.btntxt);
    });

    $('.smartshop-admin-main-tab-pane .smartshop-dashboard').on('change', 'select.smartshop-admin-select', function () {
        $(this).closest('.smartshop-admin-main-tab-pane').find('.smartshop-admin-btn-save').removeClass('disabled').attr('disabled', false).text(SMARTSHOP_ADMIN.message.btntxt);
    });


// Module additional settings
$('.smartshop-admin-switch-block-setting').on('click', function(event) {
    event.preventDefault(); 

    var $this = $(this),
        $section = $this.data('section'),
        $fields = $this.data('fields'),
        $fieldname = $this.data('fieldname') ? $this.data('fieldname') : '',
        content = null,
        modulewrapper = wp.template('smartshopmodule');

    $.ajax({
        url: SMARTSHOP_ADMIN.ajaxurl,
        type: 'POST',
        data: {
            nonce: SMARTSHOP_ADMIN.nonce,
            section: $section,
            fields: $fields,
            fieldname: $fieldname,
            action: 'smartshop_module_data',
            subaction: 'get_data',
        },
        beforeSend: function(){
            $this.addClass('module-setting-loading');
        },
        success: function( response ) { 
            content = modulewrapper( {
                section : $section,
                fileds  : response.data.fields,
                content : response.data.content
            } );
            $( 'body' ).append( content );

            smartshop_module_ajax_reactive();
            $( document ).trigger('module_setting_loaded');
            $this.removeClass('module-setting-loading');
            
        },
        complete: function( response ) {
            $this.removeClass('module-setting-loading');
        },
        error: function(errorThrown){
            console.log(errorThrown);
        }
    });
});


 
    // PopUp reactive JS
    function smartshop_module_ajax_reactive() {

        // Select 2 Multiple selection
        $('.smartshop-module-setting-popup').find('.smartshop-admin-option:not(.smartshop-repeater-field) .smartshop-admin-select select[multiple="multiple"]').each(function () {
            const $this = $(this),
                $parent = $this.parent();
            $this.select2({
                dropdownParent: $parent,
                placeholder: "Select Item"
            });
        });

        //Initiate Color Picker
        $('.smartshop-module-setting-popup').find('.smartshop-admin-option:not(.smartshop-repeater-field) .wp-color-picker-field').wpColorPicker({
            change: function (event, ui) {
                $(this).closest('.smartshop-module-setting-popup-content').find('.smartshop-admin-module-save').removeClass('disabled').attr('disabled', false).text(SMARTSHOP_ADMIN.message.btntxt);
            },
            clear: function (event) {
                $(this).closest('.smartshop-module-setting-popup-content').find('.smartshop-admin-module-save').removeClass('disabled').attr('disabled', false).text(SMARTSHOP_ADMIN.message.btntxt);
            }
        });

        // WPColor Picker Button disable.
        $('div[data-smartshop-pro="disabled"] .wp-picker-container button').each(function () {
            $(this).attr("disabled", true);
        });

    //     /* Number Input */
        $('.smartshop-admin-number-btn').on('click', function (e) {
            e.preventDefault()
            const $this = $(this),
                $input = $this.parent('.smartshop-admin-number').find('input[type="number"]')[0]
            if ($this.hasClass('increase')) {
                $input.value = Number($input.value) + 1
            } else if ($this.hasClass('decrease') && Number($input.value) > 1) {
                $input.value = Number($input.value) - 1
            }
        });

        // Icon Picker
 

        // Media Uploader
        $('.smartshop-browse').on('click', function (event) {
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
                self.prev('.smartshop-url').val(attachment.url).change();
                self.siblings('.smartshop_display').html('<img src="' + attachment.url + '" alt="' + attachment.title + '" />');
            });

            // Finally, open the modal
            file_frame.open();

        });

        // Remove Media Button
        $('.smartshop-remove').on('click', function (event) {
            event.preventDefault();
            var self = $(this);
            self.siblings('.smartshop-url').val('').change();
            self.siblings('.smartshop_display').html('');
        });

        // Module additional setting save
        $('.smartshop-admin-module-save').on('click', function (event) {
            event.preventDefault();

            console.log('Module additional setting save clicked');

            // Getting relevant form and button elements
            var $option_form = $(this).closest('.smartshop-module-setting-popup-content').find('form.smartshop-module-setting-data'),
                $savebtn = $(this),
                $section = $option_form.data('section'),
                $field_keys = $option_form.data('fields');

            // Debugging variables
            // console.log('xxSection:', $section);
            // console.log('xxFields:', $field_keys);
            // console.log('xxSerialized Data:', $(this).closest('.smartshop-module-setting-popup-content').find('form.smartshop-module-setting-data :input').not('.smartshop-repeater-hidden :input').serializeJSON());

            // Ensure SMARTSHOP_ADMIN is defined
            if (typeof SMARTSHOP_ADMIN === 'undefined') {
                console.error('SMARTSHOP_ADMIN is not defined');
                return;
            }

            console.log('SMARTSHOP_ADMIN:', SMARTSHOP_ADMIN);

            // AJAX request
            $.ajax({
                url: SMARTSHOP_ADMIN.ajaxurl, // URL to send the request to
                type: 'POST', // Method of request
                data: {
                    nonce: SMARTSHOP_ADMIN.nonce, // Nonce for security
                    section: $section, // Section data
                    fields: $field_keys, // Field keys data
                    action: 'smartshop_save_opt_data', // Action to be handled in PHP
                    data: $(this).closest('.smartshop-module-setting-popup-content').find('form.smartshop-module-setting-data :input').not('.smartshop-repeater-hidden :input').serializeJSON() // Serialized form data
                },
                beforeSend: function () {
                    // Update the button text and add a loading class
                    $savebtn.text(SMARTSHOP_ADMIN.message.loading).addClass('updating-message');
                },
                success: function (response) {
                    console.log('xxx AJAX Success Response:', response); // Log the response
                    alert('Module additional setting saved'); // Alert success message
                    $savebtn.removeClass('updating-message').addClass('disabled').attr('disabled', true).text(SMARTSHOP_ADMIN.message.success); // Update the button state
                },
                complete: function (response) {
                    console.log('AJAX Complete Response:', response); // Log the response
                    $savebtn.removeClass('updating-message').addClass('disabled').attr('disabled', true).text(SMARTSHOP_ADMIN.message.success); // Finalize the button state
                },
                error: function (errorThrown) {
                    console.error('AJAX Error:', errorThrown); // Log any errors
                }
            });
        });

        // Module Setting Reset
        $('.smartshop-admin-module-reset').on('click', function (event) {
            event.preventDefault();

console.log('Module Setting Reset ajax');

            var $option_form = $(this).closest('.smartshop-module-setting-popup-content').find('form.smartshop-module-setting-data'),
                $resetbtn = $(this),
                $section = $option_form.data('section');

            Swal.fire({
                title: SMARTSHOP_ADMIN.message.sure,
                text: 'It will reset all the settings to default, and all the changes you made will be deleted.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: SMARTSHOP_ADMIN.message.yes,
                cancelButtonText: SMARTSHOP_ADMIN.message.cancel,
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: SMARTSHOP_ADMIN.ajaxurl,
                        type: 'POST',
                        data: {
                            nonce: SMARTSHOP_ADMIN.nonce,
                            section: $section,
                            action: 'smartshop_module_data',
                            subaction: 'reset_data',
                        },

                        beforeSend: function () {
                            $resetbtn.removeClass('disabled').addClass('updating-message').text(SMARTSHOP_ADMIN.message.reseting);
                        },

                        success: function (response) {
                            alert('Module Setting Reset ajax saved');
                            $resetbtn.removeClass('updating-message').addClass('disabled').attr('disabled', true).text(SMARTSHOP_ADMIN.message.reseted);
                        },

                        complete: function (response) {
                            $resetbtn.removeClass('updating-message').addClass('disabled').attr('disabled', true).text(SMARTSHOP_ADMIN.message.reseted);
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
        $('.smartshop-module-setting-popup-content .smartshop-module-setting-data').on('click', 'input,select,textarea,.smartshop-admin-number-btn', function () {
            $(this).closest('.smartshop-module-setting-popup-content').find('.smartshop-admin-module-save').removeClass('disabled').attr('disabled', false).text(SMARTSHOP_ADMIN.message.btntxt);
        });

        $('.smartshop-module-setting-popup-content .smartshop-module-setting-data').on('keyup', 'input', function () {
            $(this).closest('.smartshop-module-setting-popup-content').find('.smartshop-admin-module-save').removeClass('disabled').attr('disabled', false).text(SMARTSHOP_ADMIN.message.btntxt);
        });

        $('.smartshop-module-setting-popup-content .smartshop-module-setting-data').on('change', 'select', function () {
            $(this).closest('.smartshop-module-setting-popup-content').find('.smartshop-admin-module-save').removeClass('disabled').attr('disabled', false).text(SMARTSHOP_ADMIN.message.btntxt);
        });

        /* Close PopUp */
        $('.smartshop-admin-popup-close').on('click', function () {
            const $this = $(this),
                $popup = $this.closest('.smartshop-admin-popup')
            $popup.removeClass('open')
        });

        // Repeater Field
        smartshop_repeater_field();

        // Field Dependency
        $(document).ready(function () {
            $('.smartshop-module-setting-data').smartshop_conditions();
        });

    }

    /* Repeater Item control */
    $(document).on('repeater_field_added', function (e, hidden_repeater_elem) {

        $(hidden_repeater_elem).find('.smartshop-admin-select select[multiple="multiple"]').each(function () {
            const $this = $(this),
                $parent = $this.parent();
            $this.select2({
                dropdownParent: $parent,
                placeholder: "Select template"
            });
        });

        $(hidden_repeater_elem).find('.wp-color-picker-field').each(function () {
            $(this).wpColorPicker({
                change: function (event, ui) {
                    $(this).closest('.smartshop-module-setting-popup-content').find('.smartshop-admin-module-save').removeClass('disabled').attr('disabled', false).text(SMARTSHOP_ADMIN.message.btntxt);
                },
                clear: function (event) {
                    $(this).closest('.smartshop-module-setting-popup-content').find('.smartshop-admin-module-save').removeClass('disabled').attr('disabled', false).text(SMARTSHOP_ADMIN.message.btntxt);
                }
            });
        });

    });

    function smartshop_repeater_field() {

        /* Add field */
        $('.smartshop-repeater-item-add').on('click', function (e) {
            e.preventDefault();

            var $this = $(this),
                $hidden = $this.prev('.smartshop-repeater-hidden').clone(true),
                $insert_location = $this.closest('.woolenor-reapeater-fields-area').find('div.smartshop-option-repeater-item:not(.smartshop-repeater-hidden):last'),
                $itemCount = $this.closest('.woolenor-reapeater-fields-area').find('.smartshop-option-repeater-item:not(.smartshop-repeater-hidden)').length,
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
                        window.open('https://smartshop.com/pricing/?utm_source=admin&utm_medium=lockfeatures&utm_campaign=free', '_blank');
                    }
                })

                return false;
            }

            $hidden.attr('data-id', $itemCount);
            $('.smartshop-option-repeater-item-area .smartshop-option-repeater-item').removeClass('smartshop_active_repeater');
            $hidden.removeClass('smartshop-repeater-hidden').addClass('smartshop_active_repeater');
            $hidden.insertAfter($insert_location);

            if ($insert_location.length == 0) {
                $this.closest('.woolenor-reapeater-fields-area').find('.smartshop-option-repeater-item-area').html($hidden);
            }

            $(document).trigger('repeater_field_added', [$('.smartshop-module-setting-data .smartshop-option-repeater-item.smartshop_active_repeater')]);
            $(document).trigger('repeater_field_item_added', [$('.smartshop-module-setting-data .smartshop-option-repeater-item.smartshop_active_repeater')]);

            // Title Value update after add.
            $('.smartshop-module-setting-data .smartshop-option-repeater-item.smartshop_active_repeater').find('.smartshop-repeater-title-field :input').trigger('change');

            // Field Dependency
            $('.smartshop-option-repeater-item-area').children('.smartshop-option-repeater-item').children('.smartshop-option-repeater-fields').smartshop_conditions();

            // Enable Button
            $('.smartshop-admin-module-save').removeClass('disabled').attr('disabled', false).text(SMARTSHOP_ADMIN.message.btntxt);

            return false;

        });

        // Change Heading using title field value
        $('.smartshop-repeater-title-field :input').on('keyup change', function (event) {
            let titleValue = event.currentTarget.tagName == 'SELECT' ? event.currentTarget.options[event.currentTarget.selectedIndex].text : $(this).val();
            $(this).closest('.smartshop-option-repeater-fields').siblings('.smartshop-option-repeater-tools').find('.smartshop-option-repeater-item-title').html(titleValue);
        });

        // Hide Show Manage
        $('.smartshop-option-repeater-item').on('click', '.smartshop-option-repeater-tools', function () {
            const $this = $(this),
                $parentItem = $this.parent();
            if ($parentItem.hasClass('smartshop_active_repeater')) {
                $parentItem.removeClass('smartshop_active_repeater');
            } else {
                $parentItem.addClass('smartshop_active_repeater').siblings().removeClass('smartshop_active_repeater');
                $(document).trigger('repeater_field_added', [$('.smartshop-module-setting-data .smartshop-option-repeater-item.smartshop_active_repeater')]);
                $(document).trigger('repeater_field_item_active', [$parentItem]);
            }
            $('.smartshop-option-repeater-item-area').children('.smartshop-option-repeater-item').children('.smartshop-option-repeater-fields').smartshop_conditions();
        });

        // Remove Element
        $('.smartshop-option-repeater-item-remove').on('click', function (event) {

            const $this = $(this),
                $parentItem = $this.parents('.smartshop-option-repeater-item'),
                $fieldsArea = $parentItem.parents('.woolenor-reapeater-fields-area');

            $parentItem.remove();

            // ID Re-Order
            $('.smartshop-option-repeater-item:not(.smartshop-repeater-hidden)').each(function (index) {
                $(this).attr('data-id', index);
            });

            $(document).trigger('repeater_field_item_removed', [$parentItem, $fieldsArea]);

            // Enable Button
            $('.smartshop-admin-module-save').removeClass('disabled').attr('disabled', false).text(SMARTSHOP_ADMIN.message.btntxt);

            return false;
        });

        // Initiate sortable Field
        if ($(".smartshop-option-repeater-item-area").length > 0) {
            $(".smartshop-option-repeater-item-area").sortable({
                axis: 'y',
                connectWith: ".smartshop-option-repeater-item",
                handle: ".smartshop-option-repeater-tools",
                placeholder: "widget-placeholder",
                update: function (event, ui) {
                    $('.smartshop-admin-module-save').removeClass('disabled').attr('disabled', false).text(SMARTSHOP_ADMIN.message.btntxt);
                    $('.smartshop-option-repeater-item-area').children('.smartshop-option-repeater-item').children('.smartshop-option-repeater-fields').smartshop_conditions();
                }
            });
        }

        /**
         * Repeater Custom Button
         */
        $('.smartshop-repeater-custom-action').on('click', function () {

// console.log("epeater Custom Button ajax");

            let $this = $(this),
                $fieldsArea = $this.siblings('.smartshop-option-repeater-item-area'),
                $data = typeof $this.attr('data-customaction') !== 'undefined' ? JSON.parse($this.attr('data-customaction')) : '',
                $fieldValue = $(document).find($data['option_selector']),
                $moduleSaveButton = $('.smartshop-admin-module-save');

            if (typeof $fieldValue !== 'undefined') {
                $data = { ...$data, value: $fieldValue.val() }
            }

            $.ajax({
                url: SMARTSHOP_ADMIN.ajaxurl,
                type: 'POST',
                data: {
                    nonce: SMARTSHOP_ADMIN.nonce,
                    action: 'smartshop_repeater_custom_action',
                    data: $data
                },

                beforeSend: function () {
                    $this.removeClass('disabled').addClass('updating-message');
                    // Enable Module Data save button
                    $moduleSaveButton.removeClass('disabled').attr('disabled', false).text(SMARTSHOP_ADMIN.message.btntxt);
                },

                success: function (response) {
                     alert('Repeater Custom Button saved');
                    $this.removeClass('updating-message');
                    $(document).trigger('repeater_custom_action_start', [$data, $fieldsArea, response.data]);
                    // Save Module Data
                    $moduleSaveButton.trigger('click');
                },

                complete: function (response) {
                    $this.removeClass('updating-message');
                    // Save Module Data
                    $moduleSaveButton.trigger('click');
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
         * For Currency Switcher Module
         */
        $(document).on('change', '.wlcs-currency-selection .smartshop-admin-select select', function (e) {
            let thisField = $(this),
                item = thisField.closest('.smartshop-option-repeater-item'),
                fieldsArea = item.closest('.woolenor-reapeater-fields-area'),
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
                let items = itemsArea.find('.smartshop-option-repeater-item:not(.smartshop-repeater-hidden)'),
                    selects = $(document).find('.wlcs-default-selection .smartshop-admin-select select'),
                    options = {};

                $.each(items, function () {
                    let thisItem = $(this),
                        selectItem = thisItem.find('.wlcs-currency-selection .smartshop-admin-select select option:selected'),
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
                let currencyCode = $(child).find('.wlcs-currency-selection .smartshop-admin-select select').val();
                if (response[currencyCode]) {
                    const exchangeRate = parseFloat(response[currencyCode]).toFixed(2);
                    $(child).find('.wlcs-currency-dynamic-exchange-rate .smartshop-admin-number input').val(exchangeRate);
                }
            });

            Swal.fire({
                title: "Success!",
                text: "The exchange rates for every added currency have been updated based on the selected default currency.",
                icon: "success"
            });

        });
    }
    smartshop_repeater_field();

    // Extension Tabs
    // smartshop_admin_tabs( $(".smartshop-admin-tabs"), '.smartshop-admin-tab-pane' );

    // Field Dependency
    $(document).ready(function () {
        $('.smartshop-dashboard').children('.smartshop-admin-options').smartshop_conditions();
    });

})(jQuery);