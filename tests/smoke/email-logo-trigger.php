<?php
/**
 * Send a test email so the email-logo smoke test can verify the embedded logo.
 *
 * Boots CodeIgniter via the empty Test controller (the same CLI/bootstrap seam
 * PHPUnit uses) and then sends a password email through the real Email_messages
 * library. The message lands in Mailpit (ssmtp → mailpit:1025).
 *
 * Usage (inside the php-fpm container):
 *
 *   APP_ENV=testing php tests/smoke/email-logo-trigger.php
 */

$_SERVER['argv'] = ['email-logo-trigger', 'test'];

require __DIR__ . '/../../index.php';

$CI =& get_instance();
$CI->load->library('email_messages');

$recipient = getenv('EMAIL_RECIPIENT') ?: 'smoke-test@example.com';

$company_color = setting('company_color');

$settings = [
    'company_name' => setting('company_name'),
    'company_link' => setting('company_link'),
    'company_email' => setting('company_email'),
    'company_color' => !empty($company_color) && $company_color != DEFAULT_COMPANY_COLOR ? $company_color : null,
];

$CI->email_messages->send_password('smoke-test-password', $recipient, $settings);

echo 'sent:' . $recipient . PHP_EOL;
