<?php
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/api/includes/create-checkout.php';
require_once __DIR__ . '/save-quote.php';

/** @return array{ok: bool, error?: string, url?: string, payment_id?: string} */
function op_create_quote_checkout(array $config, array $quote): array
{
    $quotesDir = op_quotes_dir($config);
    $result = pay_create_quote_checkout($config, $quotesDir, $quote);
    if (!$result['ok']) {
        return $result;
    }
    $updated = $result['quote'] ?? null;
    if (!is_array($updated) || !op_save_quote($quotesDir, $updated)) {
        return ['ok' => false, 'error' => 'Could not update quote for checkout.'];
    }
    return [
        'ok' => true,
        'url' => (string) ($result['url'] ?? ''),
        'payment_id' => (string) ($result['payment_id'] ?? ''),
    ];
}