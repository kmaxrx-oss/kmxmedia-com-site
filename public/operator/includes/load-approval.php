<?php
declare(strict_types=1);

function op_valid_approval_id(string $id): bool
{
    return (bool) preg_match('/^[a-f0-9]{32}$/', $id);
}

/** @return array<string, mixed>|null */
function op_load_approval(string $approvalsDir, string $id): ?array
{
    if (!op_valid_approval_id($id) || $approvalsDir === '' || !is_dir($approvalsDir)) {
        return null;
    }
    $base = realpath($approvalsDir);
    if ($base === false) {
        return null;
    }
    $path = $base . DIRECTORY_SEPARATOR . $id . '.json';
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