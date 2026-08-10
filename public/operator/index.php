<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/list-requests.php';
require_once __DIR__ . '/includes/render.php';

$brand = (string) ($config['brand'] ?? 'Operator');
$storageDir = op_storage_dir($config);
$rows = op_list_requests($storageDir);

op_layout_start('Work requests', $brand);
op_render_header($brand);
echo '<h2>Work requests</h2>';

if ($rows === []) {
    echo '<p class="muted">No work requests yet.</p>';
} else {
    echo '<div class="card" style="padding:0;overflow:auto;"><table><thead><tr>';
    foreach (['Date', 'Business', 'Contact', 'Email', 'Pkg', 'Urgency', 'Status', 'Source'] as $heading) {
        echo '<th>' . op_h($heading) . '</th>';
    }
    echo '</tr></thead><tbody>';
    foreach ($rows as $row) {
        $id = (string) $row['id'];
        echo '<tr>';
        echo '<td>' . op_h((string) $row['created_at']) . '</td>';
        echo '<td><a href="/operator/request.php?id=' . op_h($id) . '">' . op_h((string) $row['business_name']) . '</a></td>';
        echo '<td>' . op_h((string) $row['contact_name']) . '</td>';
        echo '<td>' . op_h((string) $row['email']) . '</td>';
        echo '<td>' . op_h((string) $row['primary_package']) . '</td>';
        echo '<td>' . op_h((string) $row['urgency']) . '</td>';
        echo '<td>' . op_h((string) $row['status']) . '</td>';
        echo '<td>' . op_h((string) $row['source']) . '</td>';
        echo '</tr>';
    }
    echo '</tbody></table></div>';
}

op_layout_end();