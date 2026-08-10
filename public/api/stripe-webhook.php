<?php
declare(strict_types=1);

$configPath = __DIR__ . '/config.php';
if (!is_file($configPath)) {
    http_response_code(503);
    echo 'Not configured.';
    exit;
}

/** @var array<string, mixed> $config */
$config = require $configPath;

require_once __DIR__ . '/includes/stripe-verify.php';
require_once __DIR__ . '/includes/process-webhook.php';

$stripe = $config['stripe'] ?? [];
$webhookSecret = is_array($stripe) ? (string) ($stripe['webhook_secret'] ?? '') : '';
$quotesDir = (string) ($config['quotes_dir'] ?? '');
if ($quotesDir === '' && !empty($config['storage_dir'])) {
    $quotesDir = dirname((string) $config['storage_dir']) . '/quotes';
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

$payload = file_get_contents('php://input');
if (!is_string($payload) || $payload === '') {
    http_response_code(400);
    echo 'empty payload';
    exit;
}

$sigHeader = (string) ($_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '');
$verify = pay_verify_stripe_signature($payload, $sigHeader, $webhookSecret);
if (!$verify['ok']) {
    http_response_code(400);
    echo 'invalid signature';
    exit;
}

$event = json_decode($payload, true);
if (!is_array($event)) {
    http_response_code(400);
    echo 'invalid json';
    exit;
}

$result = pay_process_stripe_event($config, $quotesDir, $event);
if (!$result['ok']) {
    http_response_code(500);
    echo 'processing failed';
    exit;
}

http_response_code(200);
echo json_encode(['received' => true, 'action' => $result['action'] ?? 'ok']);