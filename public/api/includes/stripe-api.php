<?php
declare(strict_types=1);

require_once __DIR__ . '/payments-bootstrap.php';

/** @return array{ok: bool, error?: string, session?: array<string, mixed>} */
function pay_stripe_create_checkout_session(array $config, array $params): array
{
    $settings = pay_stripe_settings($config);
    $secret = (string) ($settings['secret_key'] ?? '');
    if ($secret === '') {
        return ['ok' => false, 'error' => 'Stripe is not configured.'];
    }

    $body = http_build_query($params);
    $ch = curl_init('https://api.stripe.com/v1/checkout/sessions');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $body,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $secret,
            'Content-Type: application/x-www-form-urlencoded',
        ],
        CURLOPT_TIMEOUT => 30,
    ]);
    $raw = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if (!is_string($raw)) {
        return ['ok' => false, 'error' => 'Stripe request failed.'];
    }
    $json = json_decode($raw, true);
    if ($code < 200 || $code >= 300 || !is_array($json)) {
        $msg = is_array($json) ? (string) ($json['error']['message'] ?? 'Stripe error') : 'Stripe error';
        return ['ok' => false, 'error' => $msg];
    }

    return ['ok' => true, 'session' => $json];
}

/** @return array{ok: bool, error?: string, session?: array<string, mixed>} */
function pay_stripe_expire_checkout_session(array $config, string $sessionId): array
{
    $settings = pay_stripe_settings($config);
    $secret = (string) ($settings['secret_key'] ?? '');
    if ($secret === '') {
        return ['ok' => false, 'error' => 'Stripe is not configured.'];
    }

    $ch = curl_init('https://api.stripe.com/v1/checkout/sessions/' . rawurlencode($sessionId) . '/expire');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $secret,
            'Content-Type: application/x-www-form-urlencoded',
        ],
        CURLOPT_TIMEOUT => 30,
    ]);
    $raw = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if (!is_string($raw)) {
        return ['ok' => false, 'error' => 'Stripe request failed.'];
    }
    $json = json_decode($raw, true);
    if ($code < 200 || $code >= 300 || !is_array($json)) {
        $msg = is_array($json) ? (string) ($json['error']['message'] ?? 'Stripe error') : 'Stripe error';
        return ['ok' => false, 'error' => $msg];
    }

    return ['ok' => true, 'session' => $json];
}