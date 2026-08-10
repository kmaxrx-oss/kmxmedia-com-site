<?php
declare(strict_types=1);

function ap_config_path(): string
{
    return dirname(__DIR__, 2) . '/api/config.php';
}

/** @return array<string, mixed> */
function ap_load_config(): array
{
    $path = ap_config_path();
    if (!is_file($path)) {
        http_response_code(503);
        echo 'Not configured.';
        exit;
    }
    /** @var array<string, mixed> $config */
    return require $path;
}

function ap_quotes_dir(array $config): string
{
    $dir = (string) ($config['quotes_dir'] ?? '');
    if ($dir === '' && !empty($config['storage_dir'])) {
        $dir = dirname((string) $config['storage_dir']) . '/quotes';
    }
    return $dir !== '' && is_dir($dir) ? (realpath($dir) ?: $dir) : $dir;
}

function ap_approval_sessions_dir(array $config): string
{
    $dir = (string) ($config['approval_sessions_dir'] ?? '');
    if ($dir === '' && !empty($config['storage_dir'])) {
        $dir = dirname((string) $config['storage_dir']) . '/quote-approval-sessions';
    }
    return $dir !== '' && is_dir($dir) ? (realpath($dir) ?: $dir) : $dir;
}

function ap_approvals_dir(array $config): string
{
    $dir = (string) ($config['approvals_dir'] ?? '');
    if ($dir === '' && !empty($config['storage_dir'])) {
        $dir = dirname((string) $config['storage_dir']) . '/quote-approvals';
    }
    return $dir !== '' && is_dir($dir) ? (realpath($dir) ?: $dir) : $dir;
}

/** @return array<string, mixed> */
function ap_approval_settings(array $config): array
{
    $approval = $config['approval'] ?? [];
    return is_array($approval) ? $approval : [];
}

function ap_token_hash(string $token): string
{
    return hash('sha256', $token);
}

function ap_valid_token(string $token): bool
{
    return (bool) preg_match('/^[a-f0-9]{64}$/', $token);
}

function ap_valid_quote_id(string $id): bool
{
    return (bool) preg_match('/^[a-f0-9]{32}$/', $id);
}

function ap_new_id(): string
{
    return bin2hex(random_bytes(16));
}

function ap_start_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443);
    session_name('sgd_approve');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/approve/',
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Strict',
    ]);
    session_start();
}