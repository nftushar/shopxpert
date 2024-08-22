jQuery(document).ready(function($) {
    $('#smartshop-form').on('submit', function(e) {
        e.preventDefault();
        
console.log("smartshop-form");

        // Serialize form data
        var formData = $(this).serialize();

        // Ensure checkbox is handled explicitly
        var checkbox = $('input[name="smartshop_checkbox"]');
        if (checkbox.length > 0 && !checkbox.is(':checked')) {
            formData += '&smartshop_checkbox=0';
        }

        $.ajax({
            url: smartshop_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'save_smartshop_settings',
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
