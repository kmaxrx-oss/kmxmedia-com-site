<?php
declare(strict_types=1);

require_once __DIR__ . '/payments-bootstrap.php';

/** @return array<string, mixed>|null */
function pay_load_payment(string $paymentsDir, string $id): ?array
{
    if (!pay_valid_payment_id($id) || $paymentsDir === '' || !is_dir($paymentsDir)) {
        return null;
    }
    $base = realpath($paymentsDir);
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

/** @return array<string, mixed>|null */
function pay_find_payment_by_session(string $paymentsDir, string $sessionId): ?array
{
    if ($paymentsDir === '' || !is_dir($paymentsDir) || $sessionId === '') {
        return null;
    }
    $files = glob(rtrim($paymentsDir, '/\\') . '/*.json') ?: [];
    foreach ($files as $file) {
        $raw = file_get_contents($file);
        if (!is_string($raw)) {
            continue;
        }
        $json = json_decode($raw, true);
        if (!is_array($json)) {
            continue;
        }
        if (($json['stripe_checkout_session_id'] ?? '') === $sessionId) {
            return $json;
        }
    }
    return null;
}