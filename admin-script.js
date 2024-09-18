jQuery(document).ready(function($) {
    $('#shopxpert-form').on('submit', function(e) {
        e.preventDefault();
        
console.log("shopxpert-form");

        // Serialize form data
        var formData = $(this).serialize();

        // Ensure checkbox is handled explicitly
        var checkbox = $('input[name="shopxpert_checkbox"]');
        if (checkbox.length > 0 && !checkbox.is(':checked')) {
            formData += '&shopxpert_checkbox=0';
        }

        $.ajax({
            url: smartshop_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'save_shopxpert_settings',
                nonce: $('#nonce').val(),
                settings: formData,
            },
            success: function(response) {
                if (response.success) {
                    alert('Settings saved');
                } else {
                    alert('An error occurred');
                }
            },
            error: function() {
                alert('An error occurred');
            }
        });
    });
});
