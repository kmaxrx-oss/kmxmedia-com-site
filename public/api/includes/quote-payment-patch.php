<?php
declare(strict_types=1);

/** Keys that Phase 5 may write on a quote JSON (never content fields). */
function pay_allowed_quote_payment_keys(): array
{
    return [
        'status',
        'payment_id',
        'checkout_session_id',
        'checkout_url',
        'checkout_created_at',
        'checkout_expires_at',
        'paid_at',
        'paid_via',
        'updated_at',
    ];
}

/**
 * @param array<string, mixed> $quote
 * @param array<string, mixed> $paymentFields
 */
function pay_patch_quote_payment_fields(array $quote, array $paymentFields): array
{
    $allowed = pay_allowed_quote_payment_keys();
    foreach ($paymentFields as $key => $value) {
        if (in_array($key, $allowed, true)) {
            $quote[$key] = $value;
        }
    }
    $quote['updated_at'] = gmdate('c');
    return $quote;
}