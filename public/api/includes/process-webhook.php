<?php
declare(strict_types=1);

require_once __DIR__ . '/payments-bootstrap.php';
require_once __DIR__ . '/load-payment.php';
require_once __DIR__ . '/save-payment.php';
require_once __DIR__ . '/quote-payment-patch.php';

/**
 * @param array<string, mixed> $event
 * @return array{ok: bool, error?: string, duplicate?: bool, action?: string}
 */
function pay_process_stripe_event(
    array $config,
    string $quotesDir,
    array $event
): array {
    $type = (string) ($event['type'] ?? '');
    $obj = $event['data']['object'] ?? null;
    if (!is_array($obj)) {
        return ['ok' => false, 'error' => 'invalid_event'];
    }

    $eventId = (string) ($event['id'] ?? '');
    $paymentsDir = pay_payments_dir($config);

    if ($type === 'checkout.session.completed') {
        return pay_webhook_session_completed($config, $quotesDir, $paymentsDir, $obj, $eventId);
    }
    if ($type === 'checkout.session.expired') {
        return pay_webhook_session_expired($config, $quotesDir, $paymentsDir, $obj, $eventId);
    }

    return ['ok' => true, 'action' => 'ignored'];
}

/**
 * @param array<string, mixed> $session
 * @return array{ok: bool, error?: string, duplicate?: bool, action?: string}
 */
function pay_webhook_session_completed(
    array $config,
    string $quotesDir,
    string $paymentsDir,
    array $session,
    string $eventId
): array {
    $paymentId = (string) ($session['metadata']['payment_id'] ?? '');
    $quoteId = (string) ($session['metadata']['quote_id'] ?? '');
    if (!pay_valid_payment_id($paymentId) || !preg_match('/^[a-f0-9]{32}$/', $quoteId)) {
        return ['ok' => false, 'error' => 'missing_metadata'];
    }

    $payment = pay_load_payment($paymentsDir, $paymentId);
    if ($payment === null) {
        return ['ok' => false, 'error' => 'payment_not_found'];
    }

    $seen = is_array($payment['webhook_event_ids'] ?? null) ? $payment['webhook_event_ids'] : [];
    if ($eventId !== '' && in_array($eventId, $seen, true)) {
        return ['ok' => true, 'duplicate' => true, 'action' => 'completed_duplicate'];
    }

    if (($payment['status'] ?? '') === 'paid') {
        return ['ok' => true, 'duplicate' => true, 'action' => 'already_paid'];
    }

    $quote = pay_load_quote_for_webhook($quotesDir, $quoteId);
    if ($quote === null) {
        return ['ok' => false, 'error' => 'quote_not_found'];
    }

    $now = gmdate('c');
    $payment['status'] = 'paid';
    $payment['paid_at'] = $now;
    $payment['paid_via'] = 'stripe_webhook';
    $payment['stripe_payment_intent_id'] = (string) ($session['payment_intent'] ?? '');
    if ($eventId !== '') {
        $seen[] = $eventId;
        $payment['webhook_event_ids'] = $seen;
    }

    if (!pay_save_payment($paymentsDir, $payment)) {
        return ['ok' => false, 'error' => 'save_payment_failed'];
    }

    $quote = pay_patch_quote_payment_fields($quote, [
        'status' => 'paid',
        'payment_id' => $paymentId,
        'paid_at' => $now,
        'paid_via' => 'stripe_webhook',
        'checkout_session_id' => (string) ($session['id'] ?? ''),
    ]);

    if (!pay_save_quote_for_webhook($quotesDir, $quote)) {
        return ['ok' => false, 'error' => 'save_quote_failed'];
    }

    return ['ok' => true, 'action' => 'completed'];
}

/**
 * @param array<string, mixed> $session
 * @return array{ok: bool, error?: string, duplicate?: bool, action?: string}
 */
function pay_webhook_session_expired(
    array $config,
    string $quotesDir,
    string $paymentsDir,
    array $session,
    string $eventId
): array {
    $paymentId = (string) ($session['metadata']['payment_id'] ?? '');
    $quoteId = (string) ($session['metadata']['quote_id'] ?? '');
    if (!pay_valid_payment_id($paymentId)) {
        return ['ok' => true, 'action' => 'expired_no_payment'];
    }

    $payment = pay_load_payment($paymentsDir, $paymentId);
    if ($payment === null) {
        return ['ok' => true, 'action' => 'expired_orphan'];
    }

    $seen = is_array($payment['webhook_event_ids'] ?? null) ? $payment['webhook_event_ids'] : [];
    if ($eventId !== '' && in_array($eventId, $seen, true)) {
        return ['ok' => true, 'duplicate' => true, 'action' => 'expired_duplicate'];
    }

    if (($payment['status'] ?? '') === 'paid') {
        return ['ok' => true, 'action' => 'expired_already_paid'];
    }

    $payment['status'] = 'expired';
    $payment['expired_at'] = gmdate('c');
    if ($eventId !== '') {
        $seen[] = $eventId;
        $payment['webhook_event_ids'] = $seen;
    }
    pay_save_payment($paymentsDir, $payment);

    if (preg_match('/^[a-f0-9]{32}$/', $quoteId)) {
        $quote = pay_load_quote_for_webhook($quotesDir, $quoteId);
        if ($quote !== null && ($quote['status'] ?? '') === 'checkout_open') {
            $quote = pay_patch_quote_payment_fields($quote, [
                'status' => 'approved',
                'checkout_session_id' => null,
                'checkout_url' => null,
                'checkout_expires_at' => null,
            ]);
            pay_save_quote_for_webhook($quotesDir, $quote);
        }
    }

    return ['ok' => true, 'action' => 'expired'];
}

/** @return array<string, mixed>|null */
function pay_load_quote_for_webhook(string $quotesDir, string $id): ?array
{
    if (!preg_match('/^[a-f0-9]{32}$/', $id) || $quotesDir === '' || !is_dir($quotesDir)) {
        return null;
    }
    $base = realpath($quotesDir);
    if ($base === false) {
        return null;
    }
    $path = $base . DIRECTORY_SEPARATOR . $id . '.json';
    $real = realpath($path);
    if ($real === false || !is_file($real) || dirname($real) !== $base) {
        return null;
    }
    $raw = file_get_contents($real);
    if (!is_string($raw)) {
        return null;
    }
    $json = json_decode($raw, true);
    return is_array($json) ? $json : null;
}

/** @param array<string, mixed> $quote */
function pay_save_quote_for_webhook(string $quotesDir, array $quote): bool
{
    $id = (string) ($quote['id'] ?? '');
    if (!preg_match('/^[a-f0-9]{32}$/', $id) || $quotesDir === '' || !is_dir($quotesDir)) {
        return false;
    }
    $base = realpath($quotesDir);
    if ($base === false) {
        return false;
    }
    $path = $base . DIRECTORY_SEPARATOR . $id . '.json';
    $tmp = $path . '.tmp';
    $encoded = json_encode($quote, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    if ($encoded === false || file_put_contents($tmp, $encoded, LOCK_EX) === false) {
        return false;
    }
    return rename($tmp, $path);
}