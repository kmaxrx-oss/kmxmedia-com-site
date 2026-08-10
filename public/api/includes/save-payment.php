<?php
declare(strict_types=1);

require_once __DIR__ . '/payments-bootstrap.php';

function pay_save_payment(string $paymentsDir, array $payment): bool
{
    $id = (string) ($payment['id'] ?? '');
    if (!pay_valid_payment_id($id) || $paymentsDir === '' || !is_dir($paymentsDir)) {
        return false;
    }
    $base = realpath($paymentsDir);
    if ($base === false) {
        return false;
    }
    $path = $base . DIRECTORY_SEPARATOR . $id . '.json';
    $tmp = $path . '.tmp';
    $encoded = json_encode($payment, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    if ($encoded === false || file_put_contents($tmp, $encoded, LOCK_EX) === false) {
        return false;
    }
    return rename($tmp, $path);
}