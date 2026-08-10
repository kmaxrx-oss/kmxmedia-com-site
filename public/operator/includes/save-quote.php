<?php
declare(strict_types=1);

function op_save_quote(string $quotesDir, array $quote): bool
{
    $id = (string) ($quote['id'] ?? '');
    if (!op_valid_quote_id($id) || $quotesDir === '' || !is_dir($quotesDir)) {
        return false;
    }

    $base = realpath($quotesDir);
    if ($base === false) {
        return false;
    }

    $path = $base . DIRECTORY_SEPARATOR . $id . '.json';
    $tmp = $path . '.tmp';

    $encoded = json_encode($quote, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    if ($encoded === false) {
        return false;
    }

    if (file_put_contents($tmp, $encoded, LOCK_EX) === false) {
        return false;
    }

    return rename($tmp, $path);
}