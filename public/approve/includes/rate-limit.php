<?php
declare(strict_types=1);

function ap_client_ip(): string
{
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    return preg_replace('/[^0-9a-fA-F:.]/', '', $ip) ?: 'unknown';
}

function ap_rate_limit_path(array $config): string
{
    $storage = (string) ($config['storage_dir'] ?? '');
    $logDir = $storage !== '' ? dirname($storage) . '/logs' : '';
    if ($logDir !== '' && !is_dir($logDir)) {
        @mkdir($logDir, 0750, true);
    }
    return rtrim($logDir, '/\\') . '/approval-rate-limit.json';
}

/** @return array{blocked: bool} */
function ap_rate_limit_check(array $config, int $maxAttempts = 5, int $windowSeconds = 900): array
{
    $path = ap_rate_limit_path($config);
    $ip = ap_client_ip();
    $now = time();
    $data = [];
    if (is_file($path)) {
        $raw = file_get_contents($path);
        $decoded = is_string($raw) ? json_decode($raw, true) : null;
        if (is_array($decoded)) {
            $data = $decoded;
        }
    }
    $attempts = [];
    if (isset($data[$ip]) && is_array($data[$ip])) {
        foreach ($data[$ip] as $ts) {
            if (is_int($ts) && ($now - $ts) < $windowSeconds) {
                $attempts[] = $ts;
            }
        }
    }
    return ['blocked' => count($attempts) >= $maxAttempts];
}

function ap_rate_limit_record(array $config): void
{
    $path = ap_rate_limit_path($config);
    $ip = ap_client_ip();
    $now = time();
    $data = [];
    if (is_file($path)) {
        $raw = file_get_contents($path);
        $decoded = is_string($raw) ? json_decode($raw, true) : null;
        if (is_array($decoded)) {
            $data = $decoded;
        }
    }
    $attempts = isset($data[$ip]) && is_array($data[$ip]) ? $data[$ip] : [];
    $attempts[] = $now;
    $data[$ip] = $attempts;
    @file_put_contents($path, json_encode($data), LOCK_EX);
}