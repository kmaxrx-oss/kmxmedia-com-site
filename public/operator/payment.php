<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/load-payment.php';
require_once __DIR__ . '/includes/render.php';

$brand = (string) ($config['brand'] ?? 'Operator');
$paymentsDir = op_payments_dir($config);
$id = trim((string) ($_GET['id'] ?? ''));

if (!op_valid_payment_id($id)) {
    http_response_code(404);
    op_layout_start('Not found', $brand);
    op_render_header($brand);
    echo '<p class="muted">Payment record not found.</p>';
    op_layout_end();
    exit;
}

$record = op_load_payment($paymentsDir, $id);
if ($record === null) {
    http_response_code(404);
    op_layout_start('Not found', $brand);
    op_render_header($brand);
    echo '<p class="muted">Payment record not found.</p>';
    op_layout_end();
    exit;
}

op_layout_start('Payment record', $brand);
op_render_header($brand);
echo '<p><a href="/operator/request.php?id=' . op_h((string) ($record['work_request_id'] ?? '')) . '">&larr; Back to request</a></p>';
echo '<h2>Payment record</h2>';
echo '<p class="muted">Read-only · internal</p>';

$amount = isset($record['amount_cents']) ? '$' . number_format((int) $record['amount_cents'] / 100, 2) : null;
op_render_field_grid([
    'Payment ID' => $record['id'] ?? null,
    'Quote ID' => $record['quote_id'] ?? null,
    'Work request' => $record['work_request_id'] ?? null,
    'Status' => $record['status'] ?? null,
    'Amount' => $amount,
    'Currency' => $record['currency'] ?? null,
    'Paid via' => $record['paid_via'] ?? null,
    'Created' => $record['created_at'] ?? null,
    'Paid at' => $record['paid_at'] ?? null,
    'Stripe session' => $record['stripe_checkout_session_id'] ?? null,
    'Stripe payment intent' => $record['stripe_payment_intent_id'] ?? null,
    'Offline audit note' => $record['offline_audit_note'] ?? null,
]);

op_layout_end();