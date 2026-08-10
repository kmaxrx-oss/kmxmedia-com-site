<?php
declare(strict_types=1);

/** @param array<string, mixed> $request */
function op_seed_quote_from_request(array $request, string $brand, string $quoteId, string $requestId): array
{
    $estimator = is_array($request['estimator_snapshot'] ?? null) ? $request['estimator_snapshot'] : [];
    $recommendation = is_array($request['recommendation'] ?? null) ? $request['recommendation'] : [];
    $now = gmdate('c');

    $packageCode = (string) ($estimator['recommended_package'] ?? $request['primary_package'] ?? 'A');
    $packageName = (string) ($estimator['package_name'] ?? $recommendation['packageName'] ?? 'Scoped work');
    $quoteMin = (float) ($estimator['suggested_quote_min'] ?? 0);
    $quoteMax = (float) ($estimator['suggested_quote_max'] ?? 0);
    $hoursMin = (float) ($estimator['estimated_hours_min'] ?? 0);
    $hoursMax = (float) ($estimator['estimated_hours_max'] ?? 0);
    $finalPrice = $quoteMin > 0 && $quoteMax > 0 ? round(($quoteMin + $quoteMax) / 2, 2) : $quoteMin;

    $lineItems = [];
    $note = trim((string) ($estimator['suggested_quote_note'] ?? ''));
    if ($note !== '') {
        $lineItems[] = [
            'id' => 'li-1',
            'label' => $note,
            'category' => 'scope',
            'quantity' => 1,
            'unit_price' => $finalPrice,
            'hours_min' => $hoursMin,
            'hours_max' => $hoursMax,
            'required' => true,
            'later_option' => false,
        ];
    } else {
        $lineItems[] = [
            'id' => 'li-1',
            'label' => $packageName,
            'category' => 'package',
            'quantity' => 1,
            'unit_price' => $finalPrice,
            'hours_min' => $hoursMin,
            'hours_max' => $hoursMax,
            'required' => true,
            'later_option' => false,
        ];
    }

    $addOns = is_array($estimator['add_on_labels'] ?? null) ? $estimator['add_on_labels'] : [];
    $idx = 2;
    foreach ($addOns as $label) {
        if (!is_string($label) || trim($label) === '') {
            continue;
        }
        $lineItems[] = [
            'id' => 'li-' . $idx,
            'label' => $label,
            'category' => 'add_on',
            'quantity' => 1,
            'unit_price' => 0,
            'hours_min' => 0,
            'hours_max' => 0,
            'required' => false,
            'later_option' => true,
        ];
        $idx++;
    }

    $phases = [];
    $phasedPath = is_array($recommendation['phasedPath'] ?? null) ? $recommendation['phasedPath'] : [];
    $order = 1;
    foreach ($phasedPath as $title) {
        if (!is_string($title) || trim($title) === '') {
            continue;
        }
        $phases[] = [
            'order' => $order,
            'title' => $title,
            'summary' => '',
            'optional' => $order > 1,
        ];
        $order++;
    }

    $optionalLater = [];
    $nextStep = trim((string) ($recommendation['optionalNextStep'] ?? ''));
    if ($nextStep !== '') {
        $optionalLater[] = $nextStep;
    }
    $nextRung = trim((string) ($estimator['optional_next_rung'] ?? ''));
    if ($nextRung !== '') {
        $optionalLater[] = 'Package ' . $nextRung;
    }

    return [
        'id' => $quoteId,
        'work_request_id' => $requestId,
        'created_at' => $now,
        'updated_at' => $now,
        'status' => 'draft',
        'brand' => $brand,
        'business_name' => (string) ($request['business_name'] ?? ''),
        'contact_name' => (string) ($request['contact_name'] ?? ''),
        'contact_email' => (string) ($request['email'] ?? ''),
        'package_code' => $packageCode,
        'package_name' => $packageName,
        'scope_summary' => trim((string) ($request['problem_summary'] ?? '')),
        'line_items' => $lineItems,
        'estimated_hours_min' => $hoursMin,
        'estimated_hours_max' => $hoursMax,
        'subtotal_min' => $quoteMin,
        'subtotal_max' => $quoteMax,
        'final_price' => $finalPrice,
        'deposit_required' => 0,
        'phases' => $phases,
        'optional_later' => array_values(array_unique($optionalLater)),
        'operator_notes' => '',
        'client_facing_notes' => '',
        'seeded_from' => [
            'estimator_snapshot' => $estimator,
            'recommendation' => $recommendation,
            'primary_package' => $request['primary_package'] ?? null,
        ],
    ];
}