<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/rate-limit.php';
require_once __DIR__ . '/includes/load-quote.php';
require_once __DIR__ . '/includes/approval-session.php';
require_once __DIR__ . '/includes/client-view.php';
require_once __DIR__ . '/includes/render-client.php';

$config = ap_load_config();
ap_start_session();

$brand = (string) ($config['brand'] ?? 'Quote approval');
$sessionsDir = ap_approval_sessions_dir($config);
$quotesDir = ap_quotes_dir($config);
$token = trim((string) ($_GET['token'] ?? ''));

function ap_render_error(string $brand, string $message): void
{
    ap_layout_start('Quote approval', $brand);
    echo '<div class="error">' . ap_h($message) . '</div>';
    ap_layout_end();
    exit;
}

if (!ap_valid_token($token)) {
    ap_rate_limit_record($config);
    ap_render_error($brand, 'This approval link is invalid or has expired.');
}

$tokenHash = ap_token_hash($token);
$session = ap_load_approval_session($sessionsDir, $tokenHash);
if ($session === null) {
    ap_rate_limit_record($config);
    ap_render_error($brand, 'This approval link is invalid or has expired.');
}

$quoteId = (string) ($session['quote_id'] ?? '');
$quote = ap_valid_quote_id($quoteId) ? ap_load_quote($quotesDir, $quoteId) : null;
if ($quote === null) {
    ap_render_error($brand, 'This approval link is invalid or has expired.');
}

if (($quote['status'] ?? '') === 'approved') {
    ap_layout_start('Already approved', $brand);
    ap_render_quote_summary(ap_client_view($config, $quote));
    echo '<div class="card"><p>This quote has already been approved. No further action is needed.</p></div>';
    ap_layout_end();
    exit;
}

$sessionStatus = (string) ($session['status'] ?? '');
if ($sessionStatus !== 'active') {
    ap_render_error($brand, 'This approval link is invalid or has expired.');
}

$expiresAt = (string) ($session['expires_at'] ?? '');
if ($expiresAt !== '' && strtotime($expiresAt) !== false && strtotime($expiresAt) < time()) {
    ap_render_error($brand, 'This approval link is invalid or has expired.');
}

if (($quote['status'] ?? '') !== 'pending_approval') {
    ap_render_error($brand, 'This approval link is invalid or has expired.');
}

$_SESSION['approval_token'] = $token;
$view = ap_client_view($config, $quote);
$csrf = ap_csrf_token();

ap_layout_start('Approve quote', $brand);
ap_render_quote_summary($view);

echo '<div class="card"><h2>Authorization</h2>';
echo '<div class="terms">' . nl2br(ap_h((string) $view['authorization_text'])) . '</div></div>';

echo '<form method="post" action="/approve/submit.php">';
echo '<input type="hidden" name="csrf_token" value="' . ap_h($csrf) . '">';
echo '<input type="hidden" name="token" value="' . ap_h($token) . '">';
echo '<div class="card"><h2>Your approval</h2>';
echo '<label for="approver_name">Your name</label>';
echo '<input type="text" id="approver_name" name="approver_name" required value="' . ap_h((string) $view['contact_name']) . '">';
echo '<label for="approver_email">Your email</label>';
echo '<input type="email" id="approver_email" name="approver_email" required value="' . ap_h((string) $view['contact_email']) . '">';
echo '<p class="muted" style="margin-top:1rem;">By submitting, you authorize the scope and price above. This is not a payment.</p>';
echo '<p style="margin-top:1rem;"><button type="submit" class="btn">Approve quote</button></p>';
echo '</div></form>';

ap_layout_end();