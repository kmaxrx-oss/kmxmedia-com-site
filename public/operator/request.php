<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/load-request.php';
require_once __DIR__ . '/includes/load-quote.php';
require_once __DIR__ . '/includes/list-quotes-for-request.php';
require_once __DIR__ . '/includes/prepare-approval.php';
require_once __DIR__ . '/includes/revoke-approval.php';
require_once __DIR__ . '/includes/create-checkout.php';
require_once __DIR__ . '/includes/mark-paid-offline.php';
require_once __DIR__ . '/includes/render.php';

$brand = (string) ($config['brand'] ?? 'Operator');
$storageDir = op_storage_dir($config);
$quotesDir = op_quotes_dir($config);
$id = (string) ($_GET['id'] ?? '');
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!op_csrf_verify($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Invalid session token. Refresh and try again.';
    } else {
        $action = trim((string) ($_POST['action'] ?? ''));
        $quoteId = trim((string) ($_POST['quote_id'] ?? ''));
        $quote = op_valid_quote_id($quoteId) ? op_load_quote($quotesDir, $quoteId) : null;
        if ($quote === null) {
            $errors[] = 'Quote not found.';
        } elseif ($action === 'prepare_approval') {
            $result = op_prepare_quote_approval($config, $quote);
            if ($result['ok']) {
                $_SESSION['approval_url_flash'] = (string) ($result['url'] ?? '');
                header('Location: /operator/quote.php?id=' . urlencode($quoteId) . '&prepared=1');
                exit;
            }
            $errors[] = (string) ($result['error'] ?? 'Could not prepare approval.');
        } elseif ($action === 'revoke_approval') {
            $result = op_revoke_quote_approval($config, $quote);
            if ($result['ok']) {
                header('Location: /operator/request.php?id=' . urlencode((string) ($quote['work_request_id'] ?? $id)) . '&revoked=1');
                exit;
            }
            $errors[] = (string) ($result['error'] ?? 'Could not revoke approval.');
        } elseif ($action === 'create_checkout') {
            $result = op_create_quote_checkout($config, $quote);
            if ($result['ok']) {
                $_SESSION['checkout_url_flash'] = (string) ($result['url'] ?? '');
                header('Location: /operator/quote.php?id=' . urlencode($quoteId) . '&checkout=1');
                exit;
            }
            $errors[] = (string) ($result['error'] ?? 'Could not create checkout.');
        } elseif ($action === 'mark_paid_offline') {
            $note = trim((string) ($_POST['offline_note'] ?? 'Marked paid offline from request view'));
            $result = op_mark_quote_paid_offline($config, $quote, $note);
            if ($result['ok']) {
                header('Location: /operator/request.php?id=' . urlencode((string) ($quote['work_request_id'] ?? $id)) . '&paid_offline=1');
                exit;
            }
            $errors[] = (string) ($result['error'] ?? 'Could not mark paid offline.');
        }
    }
}

if (!op_valid_request_id($id)) {
    http_response_code(404);
    op_layout_start('Not found', $brand);
    op_render_header($brand);
    echo '<p class="muted">Request not found.</p>';
    op_layout_end();
    exit;
}

$record = op_load_request($storageDir, $id);
if ($record === null) {
    http_response_code(404);
    op_layout_start('Not found', $brand);
    op_render_header($brand);
    echo '<p class="muted">Request not found.</p>';
    op_layout_end();
    exit;
}

$booking = is_array($record['booking_details'] ?? null) ? $record['booking_details'] : [];
$ai = is_array($record['ai_agent_details'] ?? null) ? $record['ai_agent_details'] : [];
$app = is_array($record['app_software_details'] ?? null) ? $record['app_software_details'] : [];
$hosting = is_array($record['hosting_interest'] ?? null) ? $record['hosting_interest'] : [];
$recommendation = is_array($record['recommendation'] ?? null) ? $record['recommendation'] : [];
$estimator = is_array($record['estimator_snapshot'] ?? null) ? $record['estimator_snapshot'] : [];

$quote = op_find_quote_for_request($quotesDir, $id);
$quoteStatus = (string) ($quote['status'] ?? '');
$csrf = op_csrf_token();

op_layout_start((string) ($record['business_name'] ?? 'Request'), $brand);
op_render_header($brand);
echo '<p><a href="/operator/">&larr; All requests</a></p>';
echo '<h2>' . op_h((string) ($record['business_name'] ?? 'Work request')) . '</h2>';

if (isset($_GET['revoked'])) {
    echo '<div class="success">Approval revoked. Quote returned to draft.</div>';
}
if (isset($_GET['paid_offline'])) {
    echo '<div class="success">Quote marked paid offline.</div>';
}
foreach ($errors as $err) {
    echo '<div class="error">' . op_h($err) . '</div>';
}

echo '<div class="card"><h2>Quote</h2>';
if ($quote === null) {
    echo '<p class="muted">No quote yet.</p>';
    echo '<a class="btn" href="/operator/quote.php?request_id=' . op_h($id) . '">Create draft quote</a>';
} else {
    $statusLabel = $quoteStatus !== '' ? $quoteStatus : 'draft';
    echo '<p class="muted">Status: <strong>' . op_h($statusLabel) . '</strong>';
    if (!empty($quote['updated_at'])) {
        echo ' · Updated ' . op_h((string) $quote['updated_at']);
    }
    if (!empty($quote['prepared_for_client_at']) && $quoteStatus === 'pending_approval') {
        echo ' · Prepared ' . op_h((string) $quote['prepared_for_client_at']);
    }
    if (!empty($quote['approved_at']) && in_array($quoteStatus, ['approved', 'checkout_open', 'paid'], true)) {
        echo ' · Approved ' . op_h((string) $quote['approved_at']);
    }
    if ($quoteStatus === 'paid' && !empty($quote['paid_at'])) {
        echo ' · Paid ' . op_h((string) $quote['paid_at']);
    }
    echo '</p>';
    op_render_field_grid([
        'Quote ID' => $quote['id'] ?? null,
        'Package' => ($quote['package_code'] ?? '') . ' — ' . ($quote['package_name'] ?? ''),
        'Final price' => $quote['final_price'] ?? null,
    ]);
    echo '<div class="actions" style="margin-top:0.75rem;">';
    if ($quoteStatus === 'draft') {
        echo '<a class="btn" href="/operator/quote.php?id=' . op_h((string) $quote['id']) . '">Edit draft quote</a>';
        echo '<form method="post" style="display:inline" action="/operator/request.php?id=' . op_h($id) . '">';
        echo '<input type="hidden" name="csrf_token" value="' . op_h($csrf) . '">';
        echo '<input type="hidden" name="quote_id" value="' . op_h((string) $quote['id']) . '">';
        echo '<input type="hidden" name="action" value="prepare_approval">';
        echo '<button type="submit" class="btn btn-secondary">Prepare for client approval</button></form>';
    } elseif ($quoteStatus === 'pending_approval') {
        echo '<a class="btn btn-secondary" href="/operator/quote.php?id=' . op_h((string) $quote['id']) . '">View quote (read-only)</a>';
        echo '<form method="post" style="display:inline" action="/operator/request.php?id=' . op_h($id) . '">';
        echo '<input type="hidden" name="csrf_token" value="' . op_h($csrf) . '">';
        echo '<input type="hidden" name="quote_id" value="' . op_h((string) $quote['id']) . '">';
        echo '<input type="hidden" name="action" value="revoke_approval">';
        echo '<button type="submit" class="btn btn-secondary">Revoke approval link</button></form>';
    } elseif (in_array($quoteStatus, ['approved', 'checkout_open', 'paid'], true)) {
        echo '<a class="btn btn-secondary" href="/operator/quote.php?id=' . op_h((string) $quote['id']) . '">View quote (locked)</a>';
        if (!empty($quote['approval_id'])) {
            echo '<a class="btn btn-secondary" href="/operator/approval.php?id=' . op_h((string) $quote['approval_id']) . '">View approval record</a>';
        }
        if (in_array($quoteStatus, ['approved', 'checkout_open'], true)) {
            echo '<form method="post" style="display:inline" action="/operator/request.php?id=' . op_h($id) . '">';
            echo '<input type="hidden" name="csrf_token" value="' . op_h($csrf) . '">';
            echo '<input type="hidden" name="quote_id" value="' . op_h((string) $quote['id']) . '">';
            echo '<input type="hidden" name="action" value="create_checkout">';
            echo '<button type="submit" class="btn">' . op_h($quoteStatus === 'checkout_open' ? 'Replace checkout link' : 'Create checkout link') . '</button></form>';
        }
        if ($quoteStatus === 'paid' && !empty($quote['payment_id'])) {
            echo '<a class="btn btn-secondary" href="/operator/payment.php?id=' . op_h((string) $quote['payment_id']) . '">View payment record</a>';
        }
    }
    echo '</div>';
}
echo '</div>';

echo '<div class="card"><h2>Business &amp; contact</h2>';
op_render_field_grid([
    'Business' => $record['business_name'] ?? null,
    'Contact' => $record['contact_name'] ?? null,
    'Email' => $record['email'] ?? null,
    'Phone' => $record['phone'] ?? null,
    'Contact method' => $record['contact_method'] ?? null,
    'Service area' => $record['service_area'] ?? null,
    'Urgency' => $record['urgency'] ?? null,
]);
echo '</div>';

echo '<div class="card"><h2>Site &amp; GBP</h2>';
op_render_field_grid([
    'Website' => !empty($record['no_website']) ? 'No website yet' : ($record['website_url'] ?? null),
    'GBP URL' => $record['gbp_url'] ?? null,
]);
echo '</div>';

echo '<div class="card"><h2>Needs &amp; path</h2>';
op_render_field_grid([
    'Entry path' => $record['entry_path'] ?? null,
    'Needs' => $record['needs'] ?? null,
    'Problem summary' => $record['problem_summary'] ?? null,
]);
echo '</div>';

echo '<div class="card"><h2>Package</h2>';
op_render_field_grid([
    'Primary package' => $record['primary_package'] ?? null,
    'Suggested package' => $recommendation['suggestedPackage'] ?? null,
    'Package name' => $recommendation['packageName'] ?? null,
    'Package price' => $recommendation['packagePrice'] ?? null,
    'Rationale' => $recommendation['rationale'] ?? null,
    'Phased path' => $recommendation['phasedPath'] ?? null,
    'Optional next step' => $recommendation['optionalNextStep'] ?? null,
]);
echo '</div>';

echo '<div class="card"><h2>Estimator snapshot</h2>';
op_render_field_grid([
    'Recommended package' => $estimator['recommended_package'] ?? null,
    'Package name' => $estimator['package_name'] ?? null,
    'Client price range' => $estimator['client_price_range'] ?? null,
    'Est. hours min' => $estimator['estimated_hours_min'] ?? null,
    'Est. hours max' => $estimator['estimated_hours_max'] ?? null,
    'Quote min' => $estimator['suggested_quote_min'] ?? null,
    'Quote max' => $estimator['suggested_quote_max'] ?? null,
    'Quote note' => $estimator['suggested_quote_note'] ?? null,
    'Likely access' => $estimator['likely_access'] ?? null,
    'Optional next rung' => $estimator['optional_next_rung'] ?? null,
    'Add-ons' => $estimator['add_on_labels'] ?? null,
    'Urgency multiplier' => $estimator['urgency_multiplier'] ?? null,
    'Estimator status' => $estimator['status'] ?? null,
]);
echo '</div>';

if ($booking !== [] || $ai !== [] || $app !== [] || ($record['access_comfort'] ?? []) !== []) {
    echo '<div class="card"><h2>Workflow details</h2>';
    op_render_field_grid([
        'Customer actions' => $booking['customer_actions'] ?? null,
        'Booking rules' => $booking['booking_rules'] ?? null,
        'Current tools' => $booking['current_tools'] ?? null,
        'Agent help' => $ai['agent_help'] ?? null,
        'Agent review' => $ai['agent_review'] ?? null,
        'Agent connect' => $ai['agent_connect'] ?? null,
        'Build type' => $app['build_type'] ?? null,
        'iOS priority' => $app['ios_priority'] ?? null,
        'Android priority' => $app['android_priority'] ?? null,
        'Timeline' => $app['timeline'] ?? null,
        'Access comfort' => $record['access_comfort'] ?? null,
    ]);
    echo '</div>';
}

if ($hosting !== [] || ($record['payment_preference'] ?? '') !== '') {
    echo '<div class="card"><h2>Hosting &amp; payment preference</h2>';
    op_render_field_grid([
        'Migration interest' => $hosting['migration'] ?? null,
        'GBP management interest' => $hosting['gbp_management'] ?? null,
        'Payment preference' => $record['payment_preference'] ?? null,
    ]);
    echo '</div>';
}

echo '<div class="card"><h2>Meta</h2>';
op_render_field_grid([
    'ID' => $record['id'] ?? $id,
    'Created' => $record['created_at'] ?? null,
    'Submitted' => $record['submitted_at'] ?? null,
    'Status' => $record['status'] ?? null,
    'Source' => $record['source'] ?? null,
]);
echo '<details><summary>Developer view (collapsed JSON)</summary><pre>' . op_h(json_encode($record, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) . '</pre></details>';
echo '</div>';

op_layout_end();