<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/rate-limit.php';
require_once __DIR__ . '/includes/load-quote.php';
require_once __DIR__ . '/includes/approval-session.php';
require_once __DIR__ . '/includes/client-view.php';
require_once __DIR__ . '/includes/render-client.php';
require_once __DIR__ . '/includes/save-approval.php';

$config = ap_load_config();
ap_start_session();

$brand = (string) ($config['brand'] ?? 'Quote approval');
$sessionsDir = ap_approval_sessions_dir($config);
$quotesDir = ap_quotes_dir($config);

function ap_submit_error(string $brand, string $message): void
{
    ap_layout_start('Quote approval', $brand);
    echo '<div class="error">' . ap_h($message) . '</div>';
    echo '<p><a href="javascript:history.back()">Go back</a></p>';
    ap_layout_end();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    ap_submit_error($brand, 'Invalid request.');
}

if (!ap_csrf_verify($_POST['csrf_token'] ?? null)) {
    ap_submit_error($brand, 'Your session expired. Refresh the approval link and try again.');
}

$token = trim((string) ($_POST['token'] ?? ($_SESSION['approval_token'] ?? '')));
if (!ap_valid_token($token)) {
    ap_rate_limit_record($config);
    if (ap_rate_limit_check($config)['blocked']) {
        http_response_code(429);
        ap_submit_error($brand, 'Too many attempts. Please try again later.');
    }
    ap_submit_error($brand, 'This approval link is invalid or has expired.');
}

$tokenHash = ap_token_hash($token);
$session = ap_load_approval_session($sessionsDir, $tokenHash);
if ($session === null) {
    ap_rate_limit_record($config);
    if (ap_rate_limit_check($config)['blocked']) {
        http_response_code(429);
        ap_submit_error($brand, 'Too many attempts. Please try again later.');
    }
    ap_submit_error($brand, 'This approval link is invalid or has expired.');
}

if (($session['status'] ?? '') !== 'active') {
    ap_submit_error($brand, 'This approval link is invalid or has expired.');
}

$expiresAt = (string) ($session['expires_at'] ?? '');
if ($expiresAt !== '' && strtotime($expiresAt) !== false && strtotime($expiresAt) < time()) {
    ap_submit_error($brand, 'This approval link is invalid or has expired.');
}

$quoteId = (string) ($session['quote_id'] ?? '');
$quote = ap_valid_quote_id($quoteId) ? ap_load_quote($quotesDir, $quoteId) : null;
if ($quote === null) {
    ap_submit_error($brand, 'This approval link is invalid or has expired.');
}

if (($quote['status'] ?? '') === 'approved') {
    header('Location: /approve/?token=' . urlencode($token));
    exit;
}

$authText = ap_build_authorization_text($config, $quote);
$result = ap_save_approval(
    $config,
    $session,
    (string) ($_POST['approver_name'] ?? ''),
    (string) ($_POST['approver_email'] ?? ''),
    $authText
);

if (!$result['ok']) {
    if (($result['error'] ?? '') === 'already_approved') {
        header('Location: /approve/?token=' . urlencode($token));
        exit;
    }
    ap_submit_error($brand, (string) ($result['error'] ?? 'Could not save approval.'));
}

unset($_SESSION['approval_token']);
header('Location: /approve/thanks.php');
exit;