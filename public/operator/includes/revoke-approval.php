<?php
declare(strict_types=1);

require_once __DIR__ . '/approval-session.php';
require_once __DIR__ . '/save-quote.php';

/** @return array{ok: bool, error?: string} */
function op_revoke_quote_approval(array $config, array $quote): array
{
    $quotesDir = op_quotes_dir($config);
    $sessionsDir = op_approval_sessions_dir($config);
    $status = (string) ($quote['status'] ?? '');

    if ($status !== 'pending_approval') {
        return ['ok' => false, 'error' => 'Only pending approval quotes can be revoked.'];
    }

    $quoteId = (string) ($quote['id'] ?? '');
    $sessionHash = (string) ($quote['approval_session_hash'] ?? '');
    if ($sessionHash !== '') {
        $session = op_load_approval_session($sessionsDir, $sessionHash);
        if ($session !== null) {
            $session['status'] = 'revoked';
            op_save_approval_session($sessionsDir, $session);
        }
    } else {
        $active = op_find_active_session_for_quote($sessionsDir, $quoteId);
        if ($active !== null) {
            $active['status'] = 'revoked';
            op_save_approval_session($sessionsDir, $active);
        }
    }

    $quote['status'] = 'draft';
    $quote['updated_at'] = gmdate('c');
    unset($quote['approval_session_hash'], $quote['prepared_for_client_at']);

    if (!op_save_quote($quotesDir, $quote)) {
        return ['ok' => false, 'error' => 'Could not return quote to draft.'];
    }

    return ['ok' => true];
}