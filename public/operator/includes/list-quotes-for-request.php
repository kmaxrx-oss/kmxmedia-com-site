<?php
declare(strict_types=1);

/** @return array<string, mixed>|null */
function op_find_quote_for_request(string $quotesDir, string $requestId): ?array
{
    if (!op_valid_request_id($requestId) || $quotesDir === '' || !is_dir($quotesDir)) {
        return null;
    }

    $files = glob(rtrim($quotesDir, '/\\') . '/*.json') ?: [];
    foreach ($files as $file) {
        $raw = file_get_contents($file);
        if (!is_string($raw)) {
            continue;
        }
        $json = json_decode($raw, true);
        if (!is_array($json)) {
            continue;
        }
        if (($json['work_request_id'] ?? '') === $requestId) {
            return $json;
        }
    }

    return null;
}

/** @return array<string, mixed>|null */
function op_find_draft_for_request(string $quotesDir, string $requestId): ?array
{
    $quote = op_find_quote_for_request($quotesDir, $requestId);
    if ($quote !== null && ($quote['status'] ?? '') === 'draft') {
        return $quote;
    }
    return null;
}