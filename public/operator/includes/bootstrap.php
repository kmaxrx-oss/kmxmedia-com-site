<?php
declare(strict_types=1);

function op_config_path(): string
{
    return dirname(__DIR__, 2) . '/api/config.php';
}

/** @return array<string, mixed> */
function op_load_config(): array
{
    $path = op_config_path();
    if (!is_file($path)) {
        http_response_code(503);
        echo 'Server not configured.';
        exit;
    }
    /** @var array<string, mixed> $config */
    $config = require $path;
    return $config;
}

function op_storage_dir(array $config): string
{
    $dir = (string) ($config['storage_dir'] ?? '');
    if ($dir === '' || !is_dir($dir)) {
        return $dir;
    }
    $real = realpath($dir);
    return $real !== false ? $real : $dir;
}

function op_logs_dir(array $config): string
{
    $storage = op_storage_dir($config);
    if ($storage === '') {
        return '';
    }
    $logDir = dirname($storage) . '/logs';
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0750, true);
    }
    return $logDir;
}

function op_session_name(array $config): string
{
    $admin = $config['admin'] ?? [];
    if (is_array($admin) && !empty($admin['session_name'])) {
        return (string) $admin['session_name'];
    }
    return 'sgd_operator';
}

function op_start_session(array $config): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443);

    session_name(op_session_name($config));
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/operator/',
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Strict',
    ]);
    session_start();
}

function op_admin_enabled(array $config): bool
{
    $admin = $config['admin'] ?? null;
    if (!is_array($admin)) {
        return false;
    }
    return !empty($admin['enabled']) && !empty($admin['password_hash']);
}

function op_quotes_dir(array $config): string
{
    $dir = (string) ($config['quotes_dir'] ?? '');
    if ($dir === '') {
        $storage = op_storage_dir($config);
        if ($storage !== '') {
            $dir = dirname($storage) . '/quotes';
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

function op_valid_quote_id(string $id): bool
{
    return (bool) preg_match('/^[a-f0-9]{32}$/', $id);
}

function op_new_id(): string
{
    return bin2hex(random_bytes(16));
}

function op_approval_sessions_dir(array $config): string
{
    $dir = (string) ($config['approval_sessions_dir'] ?? '');
    if ($dir === '') {
        $storage = op_storage_dir($config);
        if ($storage !== '') {
            $dir = dirname($storage) . '/quote-approval-sessions';
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

function op_approvals_dir(array $config): string
{
    $dir = (string) ($config['approvals_dir'] ?? '');
    if ($dir === '') {
        $storage = op_storage_dir($config);
        if ($storage !== '') {
            $dir = dirname($storage) . '/quote-approvals';
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

/** @return array<string, mixed> */
function op_approval_settings(array $config): array
{
    $approval = $config['approval'] ?? [];
    return is_array($approval) ? $approval : [];
}

function op_token_hash(string $token): string
{
    return hash('sha256', $token);
}

function op_valid_approval_token(string $token): bool
{
    return (bool) preg_match('/^[a-f0-9]{64}$/', $token);
}