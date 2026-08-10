<?php
declare(strict_types=1);

function pay_payments_dir(array $config): string
{
    $dir = (string) ($config['payments_dir'] ?? '');
    if ($dir === '') {
        $storage = (string) ($config['storage_dir'] ?? '');
        if ($storage !== '') {
            $dir = dirname($storage) . '/quote-payments';
        }
    }
    if ($dir !== '' && !is_dir($dir)) {
        @mkdir($dir, 0750, true);
    }
    if ($dir === '' || !is_dir($dir)) {
        return $dir;
    }
    $real = realpath($dir);
    return $real !== false ? $real : $dir;
}

function pay_valid_payment_id(string $id): bool
{
    return (bool) preg_match('/^[a-f0-9]{32}$/', $id);
}

function pay_new_id(): string
{
    return bin2hex(random_bytes(16));
}

/** @return array<string, mixed> */
function pay_stripe_settings(array $config): array
{
    $stripe = $config['stripe'] ?? [];
    return is_array($stripe) ? $stripe : [];
}

function pay_stripe_enabled(array $config): bool
{
    $s = pay_stripe_settings($config);
    return !empty($s['enabled']) && !empty($s['secret_key']);
}