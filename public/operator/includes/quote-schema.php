<?php
declare(strict_types=1);

/** @return array{ok: bool, errors: list<string>, quote: array<string, mixed>|null} */
function op_parse_quote_post(array $post, ?array $existing, string $brand): array
{
    $errors = [];
    $requestId = trim((string) ($post['work_request_id'] ?? ''));
    if (!op_valid_request_id($requestId)) {
        $errors[] = 'Invalid work request id.';
    }

    $quoteId = trim((string) ($post['quote_id'] ?? ''));
    if ($existing !== null) {
        $quoteId = (string) ($existing['id'] ?? $quoteId);
    } elseif (!op_valid_quote_id($quoteId)) {
        $errors[] = 'Invalid quote id.';
    }

    $packageCode = strtoupper(trim((string) ($post['package_code'] ?? '')));
    if (!in_array($packageCode, ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'], true)) {
        $errors[] = 'Invalid package code.';
    }

    $lineItemsRaw = $post['line_items'] ?? [];
    $lineItems = [];
    if (is_array($lineItemsRaw)) {
        $i = 0;
        foreach ($lineItemsRaw as $row) {
            if (!is_array($row)) {
                continue;
            }
            $label = trim((string) ($row['label'] ?? ''));
            if ($label === '') {
                continue;
            }
            $i++;
            $lineItems[] = [
                'id' => 'li-' . $i,
                'label' => $label,
                'category' => trim((string) ($row['category'] ?? 'scope')),
                'quantity' => max(0, (float) ($row['quantity'] ?? 1)),
                'unit_price' => (float) ($row['unit_price'] ?? 0),
                'hours_min' => (float) ($row['hours_min'] ?? 0),
                'hours_max' => (float) ($row['hours_max'] ?? 0),
                'required' => !empty($row['required']),
                'later_option' => !empty($row['later_option']),
            ];
        }
    }

    $phasesRaw = $post['phases'] ?? [];
    $phases = [];
    if (is_array($phasesRaw)) {
        $order = 1;
        foreach ($phasesRaw as $row) {
            if (!is_array($row)) {
                continue;
            }
            $title = trim((string) ($row['title'] ?? ''));
            if ($title === '') {
                continue;
            }
            $phases[] = [
                'order' => $order,
                'title' => $title,
                'summary' => trim((string) ($row['summary'] ?? '')),
                'optional' => !empty($row['optional']),
            ];
            $order++;
        }
    }

    $optionalLaterRaw = trim((string) ($post['optional_later_text'] ?? ''));
    $optionalLater = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $optionalLaterRaw) ?: [])));

    if ($errors !== []) {
        return ['ok' => false, 'errors' => $errors, 'quote' => null];
    }

    $now = gmdate('c');
    $quote = [
        'id' => $quoteId,
        'work_request_id' => $requestId,
        'created_at' => (string) ($existing['created_at'] ?? $now),
        'updated_at' => $now,
        'status' => 'draft',
        'brand' => $brand,
        'business_name' => trim((string) ($post['business_name'] ?? ($existing['business_name'] ?? ''))),
        'contact_name' => trim((string) ($post['contact_name'] ?? ($existing['contact_name'] ?? ''))),
        'contact_email' => trim((string) ($post['contact_email'] ?? ($existing['contact_email'] ?? ''))),
        'package_code' => $packageCode,
        'package_name' => trim((string) ($post['package_name'] ?? '')),
        'scope_summary' => trim((string) ($post['scope_summary'] ?? '')),
        'line_items' => $lineItems,
        'estimated_hours_min' => (float) ($post['estimated_hours_min'] ?? 0),
        'estimated_hours_max' => (float) ($post['estimated_hours_max'] ?? 0),
        'subtotal_min' => (float) ($post['subtotal_min'] ?? 0),
        'subtotal_max' => (float) ($post['subtotal_max'] ?? 0),
        'final_price' => (float) ($post['final_price'] ?? 0),
        'deposit_required' => (float) ($post['deposit_required'] ?? 0),
        'phases' => $phases,
        'optional_later' => $optionalLater,
        'operator_notes' => trim((string) ($post['operator_notes'] ?? '')),
        'client_facing_notes' => trim((string) ($post['client_facing_notes'] ?? '')),
        'seeded_from' => $existing['seeded_from'] ?? null,
    ];

    return ['ok' => true, 'errors' => [], 'quote' => $quote];
}