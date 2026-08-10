<?php
declare(strict_types=1);

require_once __DIR__ . '/payments-bootstrap.php';
require_once __DIR__ . '/save-payment.php';
require_once __DIR__ . '/load-payment.php';
require_once __DIR__ . '/quote-payment-patch.php';
require_once __DIR__ . '/stripe-api.php';

/**
 * @param array<string, mixed> $quote
 * @return array{ok: bool, error?: string, url?: string, payment_id?: string}
 */
function pay_create_quote_checkout(array $config, string $quotesDir, array $quote): array
{
    if (!pay_stripe_enabled($config)) {
        return ['ok' => false, 'error' => 'Stripe is not configured.'];
    }

    $status = (string) ($quote['status'] ?? '');
    if ($status === 'paid') {
        return ['ok' => false, 'error' => 'Quote is already paid.'];
    }
    if (!in_array($status, ['approved', 'checkout_open'], true)) {
        return ['ok' => false, 'error' => 'Checkout is only available for approved quotes.'];
    }

    $finalPrice = (float) ($quote['final_price'] ?? 0);
    if ($finalPrice <= 0) {
        return ['ok' => false, 'error' => 'Quote has no payable amount.'];
    }

    $paymentsDir = pay_payments_dir($config);
    $existingPaymentId = (string) ($quote['payment_id'] ?? '');
    if ($existingPaymentId !== '' && pay_valid_payment_id($existingPaymentId)) {
        $existing = pay_load_payment($paymentsDir, $existingPaymentId);
        if ($existing !== null && ($existing['status'] ?? '') === 'open') {
            $existing['status'] = 'replaced';
            $existing['replaced_at'] = gmdate('c');
            pay_save_payment($paymentsDir, $existing);
        }
    }

    $paymentId = pay_new_id();
    $quoteId = (string) ($quote['id'] ?? '');
    $brand = (string) ($quote['brand'] ?? ($config['brand'] ?? ''));
    $siteUrl = rtrim((string) ($config['site_url'] ?? ''), '/');
    if ($siteUrl === '') {
        $allowed = $config['allowed_origins'][0] ?? '';
        $siteUrl = is_string($allowed) ? rtrim($allowed, '/') : '';
    }

    $settings = pay_stripe_settings($config);
    $currency = strtolower((string) ($settings['currency'] ?? 'usd'));
    $amountCents = (int) round($finalPrice * 100);
    $productName = trim($brand . ' — ' . (string) ($quote['package_name'] ?? 'Project quote'));
    $successUrl = $siteUrl . '/pay/thanks/?session_id={CHECKOUT_SESSION_ID}';
    $cancelUrl = $siteUrl . '/pay/cancelled/';

    $params = [
        'mode' => 'payment',
        'success_url' => $successUrl,
        'cancel_url' => $cancelUrl,
        'client_reference_id' => $quoteId,
        'line_items[0][quantity]' => 1,
        'line_items[0][price_data][currency]' => $currency,
        'line_items[0][price_data][unit_amount]' => $amountCents,
        'line_items[0][price_data][product_data][name]' => $productName,
        'metadata[payment_id]' => $paymentId,
        'metadata[quote_id]' => $quoteId,
        'metadata[work_request_id]' => (string) ($quote['work_request_id'] ?? ''),
        'metadata[brand]' => $brand,
        'metadata[approval_id]' => (string) ($quote['approval_id'] ?? ''),
    ];

    $contactEmail = trim((string) ($quote['contact_email'] ?? ''));
    if ($contactEmail !== '' && filter_var($contactEmail, FILTER_VALIDATE_EMAIL)) {
        $params['customer_email'] = $contactEmail;
    }

    $result = pay_stripe_create_checkout_session($config, $params);
    if (!$result['ok']) {
        return ['ok' => false, 'error' => (string) ($result['error'] ?? 'Could not create checkout.')];
    }

    $session = $result['session'] ?? [];
    $sessionId = (string) ($session['id'] ?? '');
    $checkoutUrl = (string) ($session['url'] ?? '');
    if ($sessionId === '' || $checkoutUrl === '') {
        return ['ok' => false, 'error' => 'Stripe returned an incomplete session.'];
    }

    $now = gmdate('c');
    $expiresAt = !empty($session['expires_at'])
        ? gmdate('c', (int) $session['expires_at'])
        : gmdate('c', time() + 86400);

    $payment = [
        'id' => $paymentId,
        'quote_id' => $quoteId,
        'work_request_id' => (string) ($quote['work_request_id'] ?? ''),
        'brand' => $brand,
        'amount_cents' => $amountCents,
        'currency' => $currency,
        'snapshot_final_price' => $finalPrice,
        'stripe_checkout_session_id' => $sessionId,
        'stripe_payment_intent_id' => null,
        'status' => 'open',
        'created_at' => $now,
        'paid_at' => null,
        'paid_via' => null,
        'webhook_event_ids' => [],
    ];

    if (!pay_save_payment($paymentsDir, $payment)) {
        return ['ok' => false, 'error' => 'Could not save payment record.'];
    }

    $quote = pay_patch_quote_payment_fields($quote, [
        'status' => 'checkout_open',
        'payment_id' => $paymentId,
        'checkout_session_id' => $sessionId,
        'checkout_url' => $checkoutUrl,
        'checkout_created_at' => $now,
        'checkout_expires_at' => $expiresAt,
    ]);

    return [
        'ok' => true,
        'url' => $checkoutUrl,
        'payment_id' => $paymentId,
        'quote' => $quote,
    ];
}