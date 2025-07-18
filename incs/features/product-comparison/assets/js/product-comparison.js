// Product Comparison JS
jQuery(document).ready(function($) {
    function updateTable(html) {
        $('.shopxpert-compare-table, .shopxpert-compare-table-empty').parent().html(html);
    }
    function showFeedback(msg) {
        var $feedback = $('.shopxpert-compare-feedback');
        if ($feedback.length) {
            $feedback.text(msg).fadeIn().delay(1500).fadeOut();
        }
    }
    $(document).on('click', '.shopxpert-compare-btn', function(e) {
        e.preventDefault();
        var $btn = $(this);
        var productId = $btn.data('product-id');
        var action = $btn.hasClass('add-to-compare') ? 'add' :
                     $btn.hasClass('remove-from-compare') ? 'remove' : '';
        if (!action) return;
        $btn.prop('disabled', true);
        $.ajax({
            url: ShopxpertComparison.ajax_url,
            type: 'POST',
            data: {
                action: 'shopxpert_comparison_' + action,
                product_id: productId,
                nonce: ShopxpertComparison.nonce
            },
            success: function(response) {
                if (response.success) {
                    if ($btn.closest('.shopxpert-compare-table').length) {
                        updateTable(response.data.table_html);
                        showFeedback(action === 'add' ? 'Added!' : 'Removed!');
                    } else {
                        $btn.toggleClass('add-to-compare remove-from-compare');
                        $btn.text(response.data.button_text);
                        showFeedback(action === 'add' ? 'Added!' : 'Removed!');
                    }
                }
                $btn.prop('disabled', false);
            },
            error: function() {
                $btn.prop('disabled', false);
            }
        });
    });
    $(document).on('click', '.remove-all-compare', function(e) {
        e.preventDefault();
        var $btn = $(this);
        $btn.prop('disabled', true);
        $.ajax({
            url: ShopxpertComparison.ajax_url,
            type: 'POST',
            data: {
                action: 'shopxpert_comparison_remove_all',
                nonce: ShopxpertComparison.nonce
            },
            success: function(response) {
                if (response.success) {
                    updateTable(response.data.table_html);
                    showFeedback('All removed!');
                }
                $btn.prop('disabled', false);
            },
            error: function() {
                $btn.prop('disabled', false);
            }
        });
    });
});
