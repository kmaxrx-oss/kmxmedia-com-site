<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/load-approval.php';
require_once __DIR__ . '/includes/render.php';

$brand = (string) ($config['brand'] ?? 'Operator');
$approvalsDir = op_approvals_dir($config);
$id = trim((string) ($_GET['id'] ?? ''));

if (!op_valid_approval_id($id)) {
    http_response_code(404);
    op_layout_start('Not found', $brand);
    op_render_header($brand);
    echo '<p class="muted">Approval record not found.</p>';
    op_layout_end();
    exit;
}

$record = op_load_approval($approvalsDir, $id);
if ($record === null) {
    http_response_code(404);
    op_layout_start('Not found', $brand);
    op_render_header($brand);
    echo '<p class="muted">Approval record not found.</p>';
    op_layout_end();
    exit;
}

op_layout_start('Approval record', $brand);
op_render_header($brand);
echo '<p><a href="/operator/request.php?id=' . op_h((string) ($record['work_request_id'] ?? '')) . '">&larr; Back to request</a></p>';
echo '<h2>Client approval record</h2>';
echo '<p class="muted">Read-only · internal</p>';

op_render_field_grid([
    'Approval ID' => $record['id'] ?? null,
    'Quote ID' => $record['quote_id'] ?? null,
    'Work request' => $record['work_request_id'] ?? null,
    'Approved at' => $record['approved_at'] ?? null,
    'Approver name' => $record['approver_name'] ?? null,
    'Approver email' => $record['approver_email'] ?? null,
    'Final price' => isset($record['final_price']) ? '$' . number_format((float) $record['final_price'], 2) : null,
    'Terms version' => $record['terms_version'] ?? null,
]);

echo '<div class="card"><h2>Authorization text (snapshot)</h2>';
echo '<pre style="white-space:pre-wrap">' . op_h((string) ($record['authorization_text'] ?? '')) . '</pre></div>';

op_layout_end();