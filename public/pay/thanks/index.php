<?php
declare(strict_types=1);

$configPath = dirname(__DIR__, 2) . '/api/config.php';
if (!is_file($configPath)) {
    http_response_code(503);
    echo 'Not configured.';
    exit;
}
/** @var array<string, mixed> $config */
$config = require $configPath;
require_once dirname(__DIR__) . '/includes/render-pay.php';

$brand = (string) ($config['brand'] ?? 'Payment');
pay_layout_start('Payment received', $brand);
echo '<h1>Payment received</h1>';
echo '<div class="card">';
echo '<p>Thank you. Your payment was submitted successfully.</p>';
echo '<p class="muted">' . pay_h($brand) . ' will follow up by email with confirmation and next steps.</p>';
echo '</div>';
pay_layout_end();