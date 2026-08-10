<?php
declare(strict_types=1);

require_once __DIR__ . '/payments-bootstrap.php';
require_once __DIR__ . '/save-payment.php';
require_once __DIR__ . '/load-payment.php';
require_once __DIR__ . '/quote-payment-patch.php';

/**
 * @param array<string, mixed> $quote
 * @return array{ok: bool, error?: string, payment_id?: string, quote?: array<string, mixed>}
 */
function pay_mark_quote_paid_offline(array $config, array $quote, string $auditNote = ''): array
{
    $status = (string) ($quote['status'] ?? '');
    if ($status === 'paid') {
        return ['ok' => false, 'error' => 'Quote is already paid.'];
    }
    if (!in_array($status, ['approved', 'checkout_open'], true)) {
        return ['ok' => false, 'error' => 'Only approved quotes can be marked paid offline.'];
    }

    $paymentsDir = pay_payments_dir($config);
    $finalPrice = (float) ($quote['final_price'] ?? 0);
    if ($finalPrice <= 0) {
        return ['ok' => false, 'error' => 'Quote has no payable amount.'];
    }

    $existingPaymentId = (string) ($quote['payment_id'] ?? '');
    if ($existingPaymentId !== '' && pay_valid_payment_id($existingPaymentId)) {
        $existing = pay_load_payment($paymentsDir, $existingPaymentId);
        if ($existing !== null && ($existing['status'] ?? '') === 'open') {
            $existing['status'] = 'cancelled_offline';
            $existing['cancelled_at'] = gmdate('c');
            pay_save_payment($paymentsDir, $existing);
        }
    }

    $paymentId = pay_new_id();
    $now = gmdate('c');
    $note = trim($auditNote);
    $payment = [
        'id' => $paymentId,
        'quote_id' => (string) ($quote['id'] ?? ''),
        'work_request_id' => (string) ($quote['work_request_id'] ?? ''),
        'brand' => (string) ($quote['brand'] ?? ($config['brand'] ?? '')),
        'amount_cents' => (int) round($finalPrice * 100),
        'currency' => strtolower((string) (pay_stripe_settings($config)['currency'] ?? 'usd')),
        'snapshot_final_price' => $finalPrice,
        'stripe_checkout_session_id' => null,
        'stripe_payment_intent_id' => null,
        'status' => 'paid',
        'created_at' => $now,
        'paid_at' => $now,
        'paid_via' => 'manual_offline',
        'offline_audit_note' => $note !== '' ? $note : 'Marked paid offline by operator.',
        'webhook_event_ids' => [],
    ];

    if (!pay_save_payment($paymentsDir, $payment)) {
        return ['ok' => false, 'error' => 'Could not save payment record.'];
    }

    $quote = pay_patch_quote_payment_fields($quote, [
        'status' => 'paid',
        'payment_id' => $paymentId,
        'paid_at' => $now,
        'paid_via' => 'manual_offline',
        'checkout_session_id' => null,
        'checkout_url' => null,
    ]);

    return ['ok' => true, 'payment_id' => $paymentId, 'quote' => $quote];
}