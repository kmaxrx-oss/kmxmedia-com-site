<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/render-client.php';

$config = ap_load_config();
$brand = (string) ($config['brand'] ?? 'Quote approval');

ap_layout_start('Thank you', $brand);
echo '<h1>Thank you</h1>';
echo '<div class="card">';
echo '<p>Your quote approval has been recorded.</p>';
echo '<p>No payment was taken on this page.</p>';
echo '<p>' . ap_h($brand) . ' will follow up by email with next steps — including invoice details, scheduling, or kickoff information when applicable.</p>';
echo '</div>';
ap_layout_end();