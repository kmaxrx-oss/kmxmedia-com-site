<?php
declare(strict_types=1);

/** @param array<string, mixed> $quote */
function ap_build_authorization_text(array $config, array $quote): string
{
    $settings = ap_approval_settings($config);
    $base = trim((string) ($settings['authorization_text'] ?? ''));
    $notes = trim((string) ($quote['client_facing_notes'] ?? ''));
    if ($notes !== '') {
        return $base === '' ? $notes : ($base . "\n\n" . $notes);
    }
    return $base;
}

/** @param array<string, mixed> $quote @return array<string, mixed> */
function ap_client_view(array $config, array $quote): array
{
    $lineItems = is_array($quote['line_items'] ?? null) ? $quote['line_items'] : [];
    $requiredLines = [];
    $optionalLines = [];
    foreach ($lineItems as $item) {
        if (!is_array($item)) {
            continue;
        }
        $label = trim((string) ($item['label'] ?? ''));
        if ($label === '') {
            continue;
        }
        $row = [
            'label' => $label,
            'quantity' => (float) ($item['quantity'] ?? 1),
        ];
        if (!empty($item['later_option'])) {
            $optionalLines[] = $row;
        } else {
            $requiredLines[] = $row;
        }
    }

    $phases = [];
    $rawPhases = is_array($quote['phases'] ?? null) ? $quote['phases'] : [];
    foreach ($rawPhases as $phase) {
        if (!is_array($phase)) {
            continue;
        }
        $title = trim((string) ($phase['title'] ?? ''));
        if ($title === '') {
            continue;
        }
        $phases[] = [
            'title' => $title,
            'summary' => trim((string) ($phase['summary'] ?? '')),
            'optional' => !empty($phase['optional']),
        ];
    }

    $optionalLater = is_array($quote['optional_later'] ?? null) ? $quote['optional_later'] : [];
    $deposit = (float) ($quote['deposit_required'] ?? 0);

    return [
        'brand' => (string) ($quote['brand'] ?? ($config['brand'] ?? '')),
        'business_name' => (string) ($quote['business_name'] ?? ''),
        'package_name' => (string) ($quote['package_name'] ?? ''),
        'scope_summary' => (string) ($quote['scope_summary'] ?? ''),
        'line_items' => $requiredLines,
        'optional_line_items' => $optionalLines,
        'phases' => $phases,
        'optional_later' => array_values(array_filter(array_map('strval', $optionalLater))),
        'final_price' => (float) ($quote['final_price'] ?? 0),
        'deposit_required' => $deposit > 0 ? $deposit : null,
        'authorization_text' => ap_build_authorization_text($config, $quote),
        'contact_name' => (string) ($quote['contact_name'] ?? ''),
        'contact_email' => (string) ($quote['contact_email'] ?? ''),
    ];
}