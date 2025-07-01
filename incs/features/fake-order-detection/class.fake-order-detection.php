<?php
// incs/features/fake-order-detection/class.fake-order-detection.php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

// The following functions are provided by WordPress/WooCommerce:
// add_action, wc_add_notice, __
// No need to import, but static analysis may complain.

class ShopXpert_Fake_Order_Detection {
    public function __construct() {
        // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
        add_action('woocommerce_checkout_process', [$this, 'run_fake_order_checks']);
    }

    public function run_fake_order_checks() {
        $email = $_POST['billing_email'] ?? '';
        $phone = $_POST['billing_phone'] ?? '';
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $suspicious = [];

        // 1. Check suspicious email domains
        if ($this->is_suspicious_email($email)) {
            wc_add_notice(__( 'Suspicious email domain detected.', 'shopxpert' ), 'error');
            $suspicious[] = 'email_domain';
        }

        // 2. Check phone number patterns
        if ($this->is_fake_phone($phone)) {
            wc_add_notice(__( 'Invalid phone number pattern.', 'shopxpert' ), 'error');
            $suspicious[] = 'phone_pattern';
        }

        // 3. Validate incomplete addresses
        if ($this->is_incomplete_address($_POST)) {
            wc_add_notice(__( 'Incomplete address.', 'shopxpert' ), 'error');
            $suspicious[] = 'incomplete_address';
        }

        // 4. Filter known spam IPs
        if ($this->is_spam_ip($ip)) {
            wc_add_notice(__( 'Order from known spam IP.', 'shopxpert' ), 'error');
            $suspicious[] = 'spam_ip';
        }

        // 5. Block repeated failed payments (pseudo-logic, needs persistent storage)
        if ($this->has_repeated_failed_payments($ip, $email)) {
            wc_add_notice(__( 'Too many failed payment attempts from this IP or email.', 'shopxpert' ), 'error');
            $suspicious[] = 'failed_payments';
        }

        // 6. Flag multiple orders from same IP with different emails
        if ($this->multiple_orders_same_ip_diff_email($ip, $email)) {
            wc_add_notice(__( 'Multiple orders from this IP with different emails detected.', 'shopxpert' ), 'error');
            $suspicious[] = 'multiple_orders_same_ip';
        }

        // 7. Optionally integrate with fraud scoring APIs
        $fraud_api_enabled = get_option('shopxpert_fake_order_detection_settings')['enable_fraud_api'] ?? 'off';
        if ($fraud_api_enabled === 'on') {
            $score = $this->get_fraud_score($email, $ip, $phone);
            if ($score > 80) {
                wc_add_notice(__('Order flagged as high risk by fraud scoring.', 'shopxpert'), 'error');
                $suspicious[] = 'fraud_score';
            }
        }

        // Log suspicious orders
        if (!empty($suspicious)) {
            $this->log_suspicious_order($email, $ip, $suspicious);
        }
    }

    private function is_suspicious_email($email) {
        $settings = get_option('shopxpert_fake_order_detection_settings');
        $blacklist = isset($settings['fake_order_email_blacklist']) ? explode("\n", $settings['fake_order_email_blacklist']) : [];
        $blacklist = array_map('trim', $blacklist);
        $domain = strtolower(substr(strrchr($email, "@"), 1));
        return in_array($domain, $blacklist);
    }

    private function is_fake_phone($phone) {
        // Block common fake patterns (all same digit, sequential, etc.)
        return preg_match('/^(\d)\1{9,}$/', $phone) || preg_match('/^(1234567890|0123456789)$/', $phone);
    }

    private function is_incomplete_address($data) {
        return empty($data['billing_address_1']) || empty($data['billing_city']) || empty($data['billing_postcode']);
    }

    private function is_spam_ip($ip) {
        $settings = get_option('shopxpert_fake_order_detection_settings');
        $spam_ips = isset($settings['fake_order_ip_blacklist']) ? explode("\n", $settings['fake_order_ip_blacklist']) : [];
        $spam_ips = array_map('trim', $spam_ips);
        return in_array($ip, $spam_ips);
    }

    private function has_repeated_failed_payments($ip, $email) {
        // This is a stub. In production, use transients or a custom table to track failed attempts.
        // Example: get_transient('shopxpert_failed_' . md5($ip . $email)) > 3
        return false;
    }

    private function multiple_orders_same_ip_diff_email($ip, $email) {
        global $wpdb;
        $recent_orders = $wpdb->get_results($wpdb->prepare(
            "SELECT pm.post_id FROM {$wpdb->postmeta} pm
            JOIN {$wpdb->posts} p ON pm.post_id = p.ID
            WHERE p.post_type = 'shop_order' AND pm.meta_key = '_customer_ip_address' AND pm.meta_value = %s
            AND p.post_date > NOW() - INTERVAL 1 DAY",
            $ip
        ));
        if (!$recent_orders) return false;
        foreach ($recent_orders as $order) {
            $order_id = $order->post_id;
            $order_email = get_post_meta($order_id, '_billing_email', true);
            if ($order_email && strtolower($order_email) !== strtolower($email)) {
                return true;
            }
        }
        return false;
    }

    private function log_suspicious_order($email, $ip, $reasons) {
        $log = get_option('shopxpert_fake_order_log', []);
        if (!is_array($log)) $log = [];
        $log[] = [
            'timestamp' => current_time('mysql'),
            'email' => $email,
            'ip' => $ip,
            'reasons' => $reasons,
            'post' => $_POST,
        ];
        update_option('shopxpert_fake_order_log', $log);
    }

    private function get_fraud_score($email, $ip, $phone) {
        // Integrate with a real API here using the API key from settings
        $settings = get_option('shopxpert_fake_order_detection_settings');
        $api_key = $settings['fraud_api_key'] ?? '';
        // Example: call your API and return a score (0-100)
        // return $score;
        return 0; // Stub
    }
} 