<?php
declare(strict_types=1);

/** @return list<array<string, mixed>> */
function op_list_requests(string $storageDir): array
{
    if ($storageDir === '' || !is_dir($storageDir)) {
        return [];
    }

    $files = glob(rtrim($storageDir, '/\\') . '/*.json') ?: [];
    $rows = [];

    foreach ($files as $file) {
        $raw = file_get_contents($file);
        if (!is_string($raw)) {
            continue;
        }
        $json = json_decode($raw, true);
        if (!is_array($json)) {
            continue;
        }
        $rows[] = [
            'id' => (string) ($json['id'] ?? basename($file, '.json')),
            'created_at' => (string) ($json['created_at'] ?? ''),
            'business_name' => (string) ($json['business_name'] ?? ''),
            'contact_name' => (string) ($json['contact_name'] ?? ''),
            'email' => (string) ($json['email'] ?? ''),
            'primary_package' => (string) ($json['primary_package'] ?? ''),
            'urgency' => (string) ($json['urgency'] ?? ''),
            'status' => (string) ($json['status'] ?? ''),
            'source' => (string) ($json['source'] ?? ''),
        ];
    }

    usort($rows, static function (array $a, array $b): int {
        return strcmp((string) $b['created_at'], (string) $a['created_at']);
    });

    return $rows;
}