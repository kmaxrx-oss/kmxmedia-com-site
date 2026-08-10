<?php
declare(strict_types=1);

/** @return array<string, mixed>|null */
function op_load_approval_session(string $sessionsDir, string $tokenHash): ?array
{
    if ($sessionsDir === '' || !is_dir($sessionsDir)) {
        return null;
    }
    $base = realpath($sessionsDir);
    if ($base === false) {
        return null;
    }
    $path = $base . DIRECTORY_SEPARATOR . $tokenHash . '.json';
    $real = realpath($path);
    if ($real === false || !is_file($real) || dirname($real) !== $base) {
        return null;
    }
    $raw = file_get_contents($real);
    if (!is_string($raw)) {
        return null;
    }
    $json = json_decode($raw, true);
    return is_array($json) ? $json : null;
}

function op_save_approval_session(string $sessionsDir, array $session): bool
{
    $hash = (string) ($session['token_hash'] ?? '');
    if ($hash === '' || $sessionsDir === '' || !is_dir($sessionsDir)) {
        return false;
    }
    $base = realpath($sessionsDir);
    if ($base === false) {
        return false;
    }
    $path = $base . DIRECTORY_SEPARATOR . $hash . '.json';
    $tmp = $path . '.tmp';
    $encoded = json_encode($session, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    if ($encoded === false || file_put_contents($tmp, $encoded, LOCK_EX) === false) {
        return false;
    }
    return rename($tmp, $path);
}

/** @return array<string, mixed>|null */
function op_find_active_session_for_quote(string $sessionsDir, string $quoteId): ?array
{
    if ($sessionsDir === '' || !is_dir($sessionsDir)) {
        return null;
    }
    $files = glob(rtrim($sessionsDir, '/\\') . '/*.json') ?: [];
    foreach ($files as $file) {
        $raw = file_get_contents($file);
        if (!is_string($raw)) {
            continue;
        }
        $json = json_decode($raw, true);
        if (!is_array($json)) {
            continue;
        }
        if (($json['quote_id'] ?? '') === $quoteId && ($json['status'] ?? '') === 'active') {
            return $json;
        }
    }
    return null;
}