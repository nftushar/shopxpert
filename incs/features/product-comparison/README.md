# ShopXpert Product Comparison Feature

## Overview
The Product Comparison feature allows customers to compare multiple products side by side with detailed information including images, ratings, prices, descriptions, availability, SKU, and more.

## Key Features Implemented

### ✅ Core Features
- **Compare Button/Link with Text** - Customizable button with text, icons, or both
- **Product Counter** - Shows number of products in comparison
- **Comprehensive Product Fields** - Image, Title, Rating, Price, Add to Cart, Description, Availability, SKU
- **Multiple Display Locations** - Single product page, shop page, archive pages
- **Product Search** - Search and add products directly from comparison table
- **Easy Product Removal** - Simple remove buttons for each product
- **Responsive Design** - Works on all devices and screen sizes
- **WordPress Theme Compatibility** - Works with any WordPress theme
- **User-Friendly Admin Panel** - Comprehensive settings and customization options

### ✅ Advanced Features
- **Multiple Button Styles** - Default, Icon-only, Text-only
- **Flexible Button Positioning** - Before/after add to cart, title, or custom
- **Popup Display** - Modal popup for comparison table
- **Footer Bar** - Fixed footer showing compared products
- **Widget Support** - WordPress widget for sidebar display
- **Multiple Layouts** - Table, Grid, and List layouts
- **Custom CSS Support** - Add custom styling
- **Animation Effects** - Multiple popup animations
- **AJAX Functionality** - Smooth, non-refreshing interactions

## File Structure

```
incs/features/product-comparison/
├── assets/
│   ├── css/
│   │   └── product-comparison.css    # Main stylesheet
│   └── js/
│       └── product-comparison.js     # JavaScript functionality
├── templates/
│   └── comparison-table.php          # Template for different layouts
├── class.product-comparison.php      # Main class with AJAX handlers
├── Frontend.php                      # Frontend initialization
├── Product_Display.php               # Button display on pages
├── Manage_Comparison.php             # Comparison data management
├── Shortcode.php                     # Shortcode handlers
├── Admin_Fields.php                  # Admin settings
├── Widget.php                        # WordPress widget
└── README.md                         # This documentation
```

## Usage

### Shortcodes

#### Compare Button
```
[product_comparison_button product_id="123" button_style="default" show_counter="true" show_icon="true"]
```

**Parameters:**
- `product_id` - Product ID (default: current product)
- `button_style` - Button style: default, icon-only, text-only
- `show_counter` - Show product counter: true/false
- `show_icon` - Show icon: true/false

#### Comparison Table
```
[product_comparison_table show_fields="image,title,rating,price,description,availability,sku,add_to_cart" show_search="true"]
```

**Parameters:**
- `show_fields` - Comma-separated list of fields to display
- `show_search` - Enable search functionality: true/false
- `display_mode` - Display mode: table, popup

### Widget
The Product Comparison widget can be added to any widget area (sidebar, footer, etc.) with customizable options.

### PHP Functions

#### Get Compare Button HTML
```php
$button_html = \Shopxpert\ProductComparison\Product_Display::instance()->get_compare_button_html($product_id, $args);
```

#### Get Comparison Table HTML
```php
$table_html = \Shopxpert\ProductComparison\Product_Display::instance()->get_comparison_table_html($args);
```

## Admin Settings

### General Settings
- **Enable Product Comparison** - Turn feature on/off
- **Button Text** - Customize button text
- **Maximum Products** - Set limit for comparison
- **Table Title** - Customize comparison table title

### Display Settings
- **Show on Shop Page** - Display buttons on shop/archive pages
- **Show on Single Product** - Display buttons on single product pages
- **Button Position** - Choose button placement
- **Button Style** - Select button appearance
- **Show Counter** - Display product count on button

### Advanced Settings
- **Enable Search** - Allow product search in comparison
- **Show Footer Bar** - Display fixed footer bar
- **Table Fields** - Choose which fields to display
- **Popup Animation** - Select animation effect
- **Custom CSS** - Add custom styling

## CSS Classes

### Button Classes
- `.shopxpert-compare-btn` - Main button class
- `.add-to-compare` - Add to comparison state
- `.remove-from-compare` - Remove from comparison state
- `.shopxpert-compare-counter` - Product counter badge

### Table Classes
- `.shopxpert-compare-table-container` - Main container
- `.shopxpert-compare-table` - Comparison table
- `.shopxpert-compare-table-title` - Table title
- `.shopxpert-compare-feedback` - Success/error messages

### Layout Classes
- `.shopxpert-compare-layout-table` - Table layout
- `.shopxpert-compare-layout-grid` - Grid layout
- `.shopxpert-compare-layout-list` - List layout

## JavaScript API

### Global Object
```javascript
window.ShopxpertProductComparison
```

### Methods
- `addProduct(productId)` - Add product to comparison
- `removeProduct(productId)` - Remove product from comparison
- `clearAll()` - Clear all products
- `showPopup()` - Show comparison popup
- `closePopup()` - Close comparison popup
- `updateCounter()` - Update product counter

## AJAX Endpoints

- `shopxpert_comparison_add` - Add product to comparison
- `shopxpert_comparison_remove` - Remove product from comparison
- `shopxpert_comparison_remove_all` - Clear all products
- `shopxpert_comparison_count` - Get product count
- `shopxpert_comparison_search` - Search products
- `shopxpert_comparison_footer_data` - Get footer bar data

## Hooks and Filters

### Actions
- `shopxpert_comparison_product_added` - After product added
- `shopxpert_comparison_product_removed` - After product removed
- `shopxpert_comparison_cleared` - After comparison cleared

### Filters
- `shopxpert_comparison_fields` - Modify available fields
- `shopxpert_comparison_button_html` - Modify button HTML
- `shopxpert_comparison_table_html` - Modify table HTML

## Browser Support
- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+
- Internet Explorer 11+

## Dependencies
- WordPress 5.0+
- WooCommerce 3.0+
- jQuery 1.12+

## Performance Notes
- Uses WordPress transients for caching
- AJAX requests are optimized
- CSS and JS are minified
- Images are lazy-loaded
- Responsive images are used

## Troubleshooting

### Common Issues
1. **Buttons not showing** - Check if feature is enabled in settings
2. **AJAX not working** - Verify nonce and AJAX URL
3. **Styling issues** - Check for theme conflicts
4. **JavaScript errors** - Check browser console for errors

### Debug Mode
Enable WordPress debug mode to see detailed error messages:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

## Support
For support and feature requests, please contact the plugin developer or create an issue in the repository.
