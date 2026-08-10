<?php
declare(strict_types=1);

function op_client_ip(): string
{
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    return preg_replace('/[^0-9a-fA-F:.]/', '', $ip) ?: 'unknown';
}

function op_rate_limit_path(string $logDir): string
{
    return rtrim($logDir, '/\\') . '/admin-login-attempts.json';
}

/** @return array{blocked: bool, remaining: int} */
function op_rate_limit_check(string $logDir, int $maxAttempts = 5, int $windowSeconds = 900): array
{
    $path = op_rate_limit_path($logDir);
    $ip = op_client_ip();
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

    $count = count($attempts);
    return [
        'blocked' => $count >= $maxAttempts,
        'remaining' => max(0, $maxAttempts - $count),
    ];
}

function op_rate_limit_record_failure(string $logDir, int $windowSeconds = 900): void
{
    $path = op_rate_limit_path($logDir);
    $ip = op_client_ip();
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
    $attempts[] = $now;
    $data[$ip] = $attempts;

    @file_put_contents($path, json_encode($data), LOCK_EX);
}

function op_rate_limit_clear(string $logDir): void
{
    $path = op_rate_limit_path($logDir);
    $ip = op_client_ip();
    if (!is_file($path)) {
        return;
    }
    $raw = file_get_contents($path);
    $decoded = is_string($raw) ? json_decode($raw, true) : null;
    if (!is_array($decoded) || !isset($decoded[$ip])) {
        return;
    }
    unset($decoded[$ip]);
    @file_put_contents($path, json_encode($decoded), LOCK_EX);
}