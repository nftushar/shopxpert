<?php
/**
 * Product Comparison Table Template
 * 
 * This template can be used to display the comparison table in different layouts
 * 
 * Available variables:
 * @var array $products - Array of product objects
 * @var array $fields - Array of fields to display
 * @var string $layout - Layout type (table, grid, list)
 * @var array $settings - Comparison settings
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$layout = $layout ?? 'table';
$fields = $fields ?? ['image', 'title', 'rating', 'price', 'description', 'availability', 'sku', 'add_to_cart'];
$products = $products ?? [];
$settings = $settings ?? [];

if (empty($products)) {
    echo '<div class="shopxpert-compare-table-empty">' . __('No products in comparison.', 'shopxpert') . '</div>';
    return;
}
?>

<div class="shopxpert-compare-table-container shopxpert-compare-layout-<?php echo esc_attr($layout); ?>">
    <div class="shopxpert-compare-table-title">
        <?php echo esc_html($settings['table_title'] ?? __('Product Comparison', 'shopxpert')); ?>
    </div>
    
    <div class="shopxpert-compare-feedback" style="display:none;"></div>
    
    <?php if ($settings['show_search'] ?? true): ?>
    <div class="shopxpert-compare-search-container">
        <input type="text" class="shopxpert-compare-search" placeholder="<?php esc_attr_e('Search products to add...', 'shopxpert'); ?>">
        <div class="shopxpert-compare-search-results"></div>
    </div>
    <?php endif; ?>
    
    <div class="shopxpert-compare-controls">
        <button class="shopxpert-compare-btn remove-all-compare">
            <?php _e('Remove All', 'shopxpert'); ?>
        </button>
        <span class="shopxpert-compare-counter">
            <?php echo count($products); ?> <?php _e('products', 'shopxpert'); ?>
        </span>
    </div>

    <?php if ($layout === 'table'): ?>
        <?php $this->render_table_layout($products, $fields); ?>
    <?php elseif ($layout === 'grid'): ?>
        <?php $this->render_grid_layout($products, $fields); ?>
    <?php elseif ($layout === 'list'): ?>
        <?php $this->render_list_layout($products, $fields); ?>
    <?php endif; ?>
</div>

<?php
/**
 * Render table layout
 */
function render_table_layout($products, $fields) {
    ?>
    <div class="shopxpert-compare-table-wrapper">
        <table class="shopxpert-compare-table">
            <thead>
                <tr>
                    <?php foreach ($fields as $field): ?>
                        <th><?php echo esc_html($this->get_field_label($field)); ?></th>
                    <?php endforeach; ?>
                    <th><?php _e('Action', 'shopxpert'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <?php foreach ($fields as $field): ?>
                            <td><?php echo $this->render_field($product, $field); ?></td>
                        <?php endforeach; ?>
                        <td>
                            <button class="shopxpert-compare-btn remove-from-compare" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                                <?php _e('Remove', 'shopxpert'); ?>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}

/**
 * Render grid layout
 */
function render_grid_layout($products, $fields) {
    ?>
    <div class="shopxpert-compare-grid">
        <?php foreach ($products as $product): ?>
            <div class="shopxpert-compare-product-card">
                <div class="product-card-header">
                    <?php if (in_array('image', $fields)): ?>
                        <div class="product-image">
                            <?php echo $product->get_image('medium'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="product-actions">
                        <button class="shopxpert-compare-btn remove-from-compare" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                            <?php _e('Remove', 'shopxpert'); ?>
                        </button>
                    </div>
                </div>
                
                <div class="product-card-content">
                    <?php foreach ($fields as $field): ?>
                        <?php if ($field !== 'image'): ?>
                            <div class="product-field product-field-<?php echo esc_attr($field); ?>">
                                <label><?php echo esc_html($this->get_field_label($field)); ?>:</label>
                                <div class="field-value"><?php echo $this->render_field($product, $field); ?></div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
}

/**
 * Render list layout
 */
function render_list_layout($products, $fields) {
    ?>
    <div class="shopxpert-compare-list">
        <?php foreach ($products as $product): ?>
            <div class="shopxpert-compare-list-item">
                <div class="list-item-content">
                    <?php foreach ($fields as $field): ?>
                        <div class="product-field product-field-<?php echo esc_attr($field); ?>">
                            <span class="field-label"><?php echo esc_html($this->get_field_label($field)); ?>:</span>
                            <span class="field-value"><?php echo $this->render_field($product, $field); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="list-item-actions">
                    <button class="shopxpert-compare-btn remove-from-compare" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                        <?php _e('Remove', 'shopxpert'); ?>
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
}

/**
 * Get field label
 */
function get_field_label($field) {
    $labels = [
        'image' => __('Image', 'shopxpert'),
        'title' => __('Product', 'shopxpert'),
        'rating' => __('Rating', 'shopxpert'),
        'price' => __('Price', 'shopxpert'),
        'description' => __('Description', 'shopxpert'),
        'availability' => __('Availability', 'shopxpert'),
        'sku' => __('SKU', 'shopxpert'),
        'add_to_cart' => __('Add to Cart', 'shopxpert'),
    ];
    
    return $labels[$field] ?? ucfirst($field);
}

/**
 * Render field value
 */
function render_field($product, $field) {
    switch ($field) {
        case 'image':
            return $product->get_image('medium');
            
        case 'title':
            return '<a href="' . esc_url(get_permalink($product->get_id())) . '" class="product-title">' . esc_html($product->get_name()) . '</a>';
            
        case 'rating':
            $rating = $product->get_average_rating();
            if ($rating > 0) {
                $html = '<div class="shopxpert-compare-rating">';
                for ($i = 1; $i <= 5; $i++) {
                    $class = $i <= $rating ? 'star filled' : 'star';
                    $html .= '<span class="' . $class . '">★</span>';
                }
                $html .= '<span class="rating-text">(' . $rating . ')</span>';
                $html .= '</div>';
                return $html;
            }
            return '<span class="no-rating">' . __('No rating', 'shopxpert') . '</span>';
            
        case 'price':
            return '<div class="shopxpert-compare-price">' . $product->get_price_html() . '</div>';
            
        case 'description':
            $description = $product->get_short_description() ?: $product->get_description();
            return '<div class="shopxpert-compare-description">' . wp_trim_words($description, 20) . '</div>';
            
        case 'availability':
            $availability = $product->get_availability();
            $class = 'in-stock';
            if ($availability['class'] === 'out-of-stock') $class = 'out-of-stock';
            if ($availability['class'] === 'available-on-backorder') $class = 'on-backorder';
            return '<span class="shopxpert-compare-availability ' . $class . '">' . esc_html($availability['availability']) . '</span>';
            
        case 'sku':
            $sku = $product->get_sku();
            if ($sku) {
                return '<span class="shopxpert-compare-sku">' . esc_html($sku) . '</span>';
            }
            return '<span class="no-sku">' . __('N/A', 'shopxpert') . '</span>';
            
        case 'add_to_cart':
            if ($product->is_purchasable() && $product->is_in_stock()) {
                return '<button class="shopxpert-compare-add-to-cart" data-product-id="' . esc_attr($product->get_id()) . '">' . __('Add to Cart', 'shopxpert') . '</button>';
            }
            return '<button class="shopxpert-compare-add-to-cart" disabled>' . __('Not Available', 'shopxpert') . '</button>';
            
        default:
            return '';
    }
}
?>
