<?php
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/api/includes/mark-paid-offline.php';
require_once __DIR__ . '/save-quote.php';

/** @return array{ok: bool, error?: string, payment_id?: string} */
function op_mark_quote_paid_offline(array $config, array $quote, string $auditNote = ''): array
{
    $quotesDir = op_quotes_dir($config);
    $result = pay_mark_quote_paid_offline($config, $quote, $auditNote);
    if (!$result['ok']) {
        return $result;
    }
    $updated = $result['quote'] ?? null;
    if (!is_array($updated) || !op_save_quote($quotesDir, $updated)) {
        return ['ok' => false, 'error' => 'Could not update quote.'];
    }
    return [
        'ok' => true,
        'payment_id' => (string) ($result['payment_id'] ?? ''),
    ];
}