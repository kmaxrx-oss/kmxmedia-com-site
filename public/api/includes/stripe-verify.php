<?php
declare(strict_types=1);

/**
 * @return array{ok: bool, error?: string}
 */
function pay_verify_stripe_signature(string $payload, string $sigHeader, string $secret, int $tolerance = 300): array
{
    if ($secret === '' || $sigHeader === '') {
        return ['ok' => false, 'error' => 'missing_secret'];
    }

    $timestamp = null;
    $signatures = [];
    foreach (explode(',', $sigHeader) as $part) {
        $part = trim($part);
        if (!str_contains($part, '=')) {
            continue;
        }
        [$key, $value] = explode('=', $part, 2);
        if ($key === 't') {
            $timestamp = $value;
        } elseif ($key === 'v1') {
            $signatures[] = $value;
        }
    }

    if ($timestamp === null || $signatures === []) {
        return ['ok' => false, 'error' => 'invalid_header'];
    }

    if (abs(time() - (int) $timestamp) > $tolerance) {
        return ['ok' => false, 'error' => 'timestamp'];
    }

    $signed = $timestamp . '.' . $payload;
    $expected = hash_hmac('sha256', $signed, $secret);
    $match = false;
    foreach ($signatures as $sig) {
        if (hash_equals($expected, $sig)) {
            $match = true;
            break;
        }
    }

    return $match ? ['ok' => true] : ['ok' => false, 'error' => 'signature'];
}