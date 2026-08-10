<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

require_once __DIR__ . '/mail-send.php';

$configPath = __DIR__ . '/config.php';
if (!is_file($configPath)) {
    http_response_code(503);
    echo json_encode(['error' => 'Server not configured. Copy config.example.php to config.php']);
    exit;
}

/** @var array<string, mixed> $config */
$config = require $configPath;

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$allowed = $config['allowed_origins'] ?? [];
if ($origin && is_array($allowed) && count($allowed) > 0) {
    if (in_array($origin, $allowed, true)) {
        header('Access-Control-Allow-Origin: ' . $origin);
        header('Vary: Origin');
    }
}

$raw = file_get_contents('php://input');
if ($raw === false || $raw === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Empty body']);
    exit;
}

/** @var array<string, mixed>|null $data */
$data = json_decode($raw, true);
if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON']);
    exit;
}

function str_field(array $data, string $key, int $max = 500): string
{
    $v = isset($data[$key]) ? trim((string) $data[$key]) : '';
    if (strlen($v) > $max) {
        $v = substr($v, 0, $max);
    }
    return $v;
}

function bool_field(array $data, string $key): bool
{
    return !empty($data[$key]);
}

function array_field(array $data, string $key): array
{
    if (!isset($data[$key]) || !is_array($data[$key])) {
        return [];
    }
    return array_values(array_filter(array_map('strval', $data[$key])));
}

$businessName = str_field($data, 'business_name', 200);
$contactName = str_field($data, 'contact_name', 200);
$email = str_field($data, 'work_email', 320);
$contactMethod = str_field($data, 'contact_method', 40);
$urgency = str_field($data, 'urgency', 80);
$entryPath = str_field($data, 'entry_path', 40);
$primaryPackage = str_field($data, 'primary_package', 4);

$errors = [];
if ($businessName === '') $errors[] = 'business_name required';
if ($contactName === '') $errors[] = 'contact_name required';
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'valid work_email required';
if ($contactMethod === '') $errors[] = 'contact_method required';
if ($urgency === '') $errors[] = 'urgency required';
if ($entryPath === '') $errors[] = 'entry_path required';
if ($primaryPackage === '') $errors[] = 'primary_package required';
if (!bool_field($data, 'auth_work_request')) $errors[] = 'auth_work_request required';

if (in_array($primaryPackage, ['A', 'G'], true) && !bool_field($data, 'auth_repair')) {
    $errors[] = 'auth_repair required for rescue/diagnose packages';
}

if ($errors) {
    http_response_code(422);
    echo json_encode(['error' => implode('; ', $errors)]);
    exit;
}

$estimator = is_array($data['estimator_snapshot'] ?? null) ? $data['estimator_snapshot'] : [];
$recommendation = is_array($data['recommendation'] ?? null) ? $data['recommendation'] : [];

$id = bin2hex(random_bytes(16));
$createdAt = gmdate('c');

$record = [
    'id' => $id,
    'created_at' => $createdAt,
    'status' => 'new',
    'business_name' => $businessName,
    'contact_name' => $contactName,
    'email' => $email,
    'phone' => str_field($data, 'phone', 40),
    'website_url' => bool_field($data, 'no_website') ? '' : str_field($data, 'website_url', 500),
    'no_website' => bool_field($data, 'no_website'),
    'gbp_url' => str_field($data, 'gbp_url', 500),
    'service_area' => str_field($data, 'service_area', 200),
    'urgency' => $urgency,
    'contact_method' => $contactMethod,
    'entry_path' => $entryPath,
    'needs' => array_field($data, 'needs'),
    'primary_package' => $primaryPackage,
    'problem_summary' => str_field($data, 'problem_summary', 5000),
    'booking_details' => [
        'customer_actions' => array_field($data, 'customer_actions'),
        'booking_rules' => str_field($data, 'booking_rules', 3000),
        'current_tools' => str_field($data, 'current_tools', 500),
    ],
    'ai_agent_details' => [
        'agent_help' => array_field($data, 'agent_help'),
        'agent_review' => str_field($data, 'agent_review', 80),
        'agent_connect' => array_field($data, 'agent_connect'),
    ],
    'app_software_details' => [
        'build_type' => array_field($data, 'build_type'),
        'ios_priority' => str_field($data, 'ios_priority', 40),
        'android_priority' => str_field($data, 'android_priority', 40),
        'timeline' => str_field($data, 'timeline', 40),
    ],
    'access_comfort' => array_field($data, 'access_comfort'),
    'hosting_interest' => [
        'migration' => bool_field($data, 'interest_migration'),
        'gbp_management' => bool_field($data, 'interest_gbp_manage'),
    ],
    'payment_preference' => str_field($data, 'payment_preference', 80),
    'auth_work_request' => true,
    'auth_repair' => bool_field($data, 'auth_repair'),
    'recommendation' => $recommendation,
    'estimator_snapshot' => $estimator,
    'source' => str_field($data, 'source', 200),
    'submitted_at' => str_field($data, 'submitted_at', 40) ?: $createdAt,
];

$storageDir = (string) ($config['storage_dir'] ?? '');
if ($storageDir === '') {
    http_response_code(503);
    echo json_encode(['error' => 'storage_dir not configured']);
    exit;
}

if (!is_dir($storageDir)) {
    if (!mkdir($storageDir, 0750, true) && !is_dir($storageDir)) {
        http_response_code(500);
        echo json_encode(['error' => 'Could not create storage directory']);
        exit;
    }
}

$file = $storageDir . '/' . $id . '.json';
$encoded = json_encode($record, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
if ($encoded === false || file_put_contents($file, $encoded, LOCK_EX) === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Could not save request']);
    exit;
}

$brand = (string) ($config['brand'] ?? 'Work request');
$notify = (string) ($config['notify_email'] ?? ($config['operator_email'] ?? ''));
$from = (string) ($config['from_email'] ?? 'webdev@starglassdigital.com');

$estPkg = is_string($estimator['recommended_package'] ?? null) ? $estimator['recommended_package'] : $primaryPackage;
$estHours = '';
if (isset($estimator['estimated_hours_min'], $estimator['estimated_hours_max'])) {
    $estHours = $estimator['estimated_hours_min'] . '–' . $estimator['estimated_hours_max'] . ' hrs';
}
$estQuote = is_string($estimator['suggested_quote_note'] ?? null) ? $estimator['suggested_quote_note'] : '';

$summary = implode("\n", array_filter([
    "[$brand] New work request — $businessName",
    "ID: $id",
    "Contact: $contactName <$email>",
    'Phone: ' . ($record['phone'] ?: '—'),
    "Urgency: $urgency",
    "Package selected: $primaryPackage",
    "Estimator package: $estPkg",
    $estHours !== '' ? "Est. labor: $estHours" : null,
    $estQuote !== '' ? $estQuote : null,
    'Needs: ' . (count($record['needs']) ? implode(', ', $record['needs']) : '—'),
    'Problem: ' . ($record['problem_summary'] ?: '—'),
    'Website: ' . ($record['no_website'] ? 'No website yet' : ($record['website_url'] ?: '—')),
    'Review JSON: ' . basename($file),
]));

if ($notify !== '') {
    $subject = "[$brand] Work request — $businessName (Pkg $primaryPackage)";
    wr_send_mail($config, $storageDir, $notify, $subject, $summary . "\n\n---\n" . $encoded, $email);
}

http_response_code(201);
echo json_encode(['ok' => true, 'id' => $id]);