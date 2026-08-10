<?php
declare(strict_types=1);

/** @return array<string, mixed>|null */
function ap_load_approval_session(string $sessionsDir, string $tokenHash): ?array
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

function ap_save_approval_session(string $sessionsDir, array $session): bool
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