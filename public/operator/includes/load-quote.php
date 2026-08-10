<?php
declare(strict_types=1);

/** @return array<string, mixed>|null */
function op_load_quote(string $quotesDir, string $id): ?array
{
    if (!op_valid_quote_id($id) || $quotesDir === '' || !is_dir($quotesDir)) {
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