# shopxpert

# ShopXpert Addon - Fake Order Detection

## Fake Order Detection Feature

This feature helps detect and reduce fake or spam orders placed on your WooCommerce store by performing the following checks:

- Checking suspicious email domains
- Checking phone number patterns
- Validating incomplete addresses
- Filtering known spam IPs
- Blocking repeated failed payments
- Flagging multiple orders from the same IP with different emails
- (Optional) Integrating with fraud scoring APIs

### How to Enable

1. Ensure the code for the feature is present in `incs/features/fake-order-detection/class.fake-order-detection.php`.
2. Enable the feature by setting the option `enable_fake_order_detection` to `on` in the `shopxpert_fake_order_detection_settings` option group (can be set via code or a settings page if available).

### How it Works

When enabled, the feature hooks into the WooCommerce checkout process and runs a series of checks. If a check fails, the order is blocked and an error message is shown to the customer.

### Extending

- You can expand the blacklist for email domains and IPs in the class file.
- For advanced fraud detection, integrate with a third-party API in the provided stub.
