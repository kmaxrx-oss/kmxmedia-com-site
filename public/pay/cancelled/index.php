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
pay_layout_start('Payment cancelled', $brand);
echo '<h1>Payment cancelled</h1>';
echo '<div class="card">';
echo '<p>No payment was taken.</p>';
echo '<p class="muted">If you still want to proceed, contact ' . pay_h($brand) . ' or use the payment link we sent you.</p>';
echo '</div>';
pay_layout_end();