<?php
declare(strict_types=1);

require_once __DIR__ . '/load-quote.php';
require_once __DIR__ . '/approval-session.php';

/** @return array{ok: bool, error?: string, approval_id?: string} */
function ap_save_approval(
    array $config,
    array $session,
    string $approverName,
    string $approverEmail,
    string $authorizationText
): array {
    $quotesDir = ap_quotes_dir($config);
    $sessionsDir = ap_approval_sessions_dir($config);
    $approvalsDir = ap_approvals_dir($config);
    $quoteId = (string) ($session['quote_id'] ?? '');

    if ($quoteId === '' || !ap_valid_quote_id($quoteId)) {
        return ['ok' => false, 'error' => 'Invalid quote.'];
    }

    $quote = ap_load_quote($quotesDir, $quoteId);
    if ($quote === null) {
        return ['ok' => false, 'error' => 'Quote not found.'];
    }

    if (($quote['status'] ?? '') === 'approved') {
        return ['ok' => false, 'error' => 'already_approved'];
    }

    if (($quote['status'] ?? '') !== 'pending_approval') {
        return ['ok' => false, 'error' => 'Quote is not awaiting approval.'];
    }

    $name = trim($approverName);
    $email = trim($approverEmail);
    if ($name === '' || $email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['ok' => false, 'error' => 'Enter your name and a valid email address.'];
    }

    if ($approvalsDir === '' || !is_dir($approvalsDir)) {
        @mkdir($approvalsDir, 0750, true);
    }
    if ($approvalsDir === '' || !is_dir($approvalsDir)) {
        return ['ok' => false, 'error' => 'Storage not configured.'];
    }

    $approvalId = ap_new_id();
    $now = gmdate('c');

    $approval = [
        'id' => $approvalId,
        'quote_id' => $quoteId,
        'work_request_id' => (string) ($quote['work_request_id'] ?? ($session['work_request_id'] ?? '')),
        'brand' => (string) ($quote['brand'] ?? ($session['brand'] ?? ($config['brand'] ?? ''))),
        'approved_at' => $now,
        'approver_name' => $name,
        'approver_email' => $email,
        'terms_version' => (string) ($session['terms_version'] ?? ''),
        'authorization_text' => $authorizationText,
        'final_price' => (float) ($quote['final_price'] ?? 0),
        'session_token_hash' => (string) ($session['token_hash'] ?? ''),
    ];

    $base = realpath($approvalsDir);
    if ($base === false) {
        return ['ok' => false, 'error' => 'Storage not configured.'];
    }
    $approvalPath = $base . DIRECTORY_SEPARATOR . $approvalId . '.json';
    $approvalTmp = $approvalPath . '.tmp';
    $encoded = json_encode($approval, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    if ($encoded === false || file_put_contents($approvalTmp, $encoded, LOCK_EX) === false) {
        return ['ok' => false, 'error' => 'Could not save approval record.'];
    }

    $quoteBase = realpath($quotesDir);
    if ($quoteBase === false) {
        @unlink($approvalTmp);
        return ['ok' => false, 'error' => 'Quote storage not found.'];
    }
    $quotePath = $quoteBase . DIRECTORY_SEPARATOR . $quoteId . '.json';
    $quoteTmp = $quotePath . '.tmp';

    $quote['status'] = 'approved';
    $quote['approved_at'] = $now;
    $quote['approval_id'] = $approvalId;
    $quote['updated_at'] = $now;

    $quoteEncoded = json_encode($quote, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    if ($quoteEncoded === false || file_put_contents($quoteTmp, $quoteEncoded, LOCK_EX) === false) {
        @unlink($approvalTmp);
        return ['ok' => false, 'error' => 'Could not update quote.'];
    }

    if (!rename($quoteTmp, $quotePath)) {
        @unlink($approvalTmp);
        @unlink($quoteTmp);
        return ['ok' => false, 'error' => 'Could not update quote.'];
    }

    if (!rename($approvalTmp, $approvalPath)) {
        return ['ok' => false, 'error' => 'Could not save approval record.'];
    }

    $session['status'] = 'consumed';
    $session['consumed_at'] = $now;
    $session['approval_id'] = $approvalId;
    ap_save_approval_session($sessionsDir, $session);

    return ['ok' => true, 'approval_id' => $approvalId];
}