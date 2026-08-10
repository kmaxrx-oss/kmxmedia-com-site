<?php
declare(strict_types=1);

require_once __DIR__ . '/approval-session.php';
require_once __DIR__ . '/save-quote.php';

/** @return array{ok: bool, error?: string, token?: string, url?: string} */
function op_prepare_quote_approval(array $config, array $quote): array
{
    $quotesDir = op_quotes_dir($config);
    $sessionsDir = op_approval_sessions_dir($config);
    $settings = op_approval_settings($config);
    $ttlDays = max(1, (int) ($settings['token_ttl_days'] ?? 14));
    $termsVersion = (string) ($settings['terms_version'] ?? '2026-06-28');

    if (($quote['status'] ?? '') !== 'draft') {
        return ['ok' => false, 'error' => 'Only draft quotes can be prepared for approval.'];
    }

    $scope = trim((string) ($quote['scope_summary'] ?? ''));
    $packageName = trim((string) ($quote['package_name'] ?? ''));
    $finalPrice = (float) ($quote['final_price'] ?? 0);
    $lineItems = is_array($quote['line_items'] ?? null) ? $quote['line_items'] : [];
    $hasLine = false;
    foreach ($lineItems as $item) {
        if (is_array($item) && trim((string) ($item['label'] ?? '')) !== '') {
            $hasLine = true;
            break;
        }
    }

    if ($scope === '' || $packageName === '' || $finalPrice <= 0 || !$hasLine) {
        return ['ok' => false, 'error' => 'Add scope, package name, final price, and at least one line item before preparing.'];
    }

    $quoteId = (string) ($quote['id'] ?? '');
    $existing = op_find_active_session_for_quote($sessionsDir, $quoteId);
    if ($existing !== null) {
        $existing['status'] = 'revoked';
        op_save_approval_session($sessionsDir, $existing);
    }

    $token = bin2hex(random_bytes(32));
    $tokenHash = op_token_hash($token);
    $now = gmdate('c');
    $expires = gmdate('c', time() + ($ttlDays * 86400));

    $session = [
        'token_hash' => $tokenHash,
        'quote_id' => $quoteId,
        'work_request_id' => (string) ($quote['work_request_id'] ?? ''),
        'brand' => (string) ($quote['brand'] ?? ($config['brand'] ?? '')),
        'created_at' => $now,
        'expires_at' => $expires,
        'status' => 'active',
        'terms_version' => $termsVersion,
    ];

    if (!op_save_approval_session($sessionsDir, $session)) {
        return ['ok' => false, 'error' => 'Could not create approval session.'];
    }

    $quote['status'] = 'pending_approval';
    $quote['approval_session_hash'] = $tokenHash;
    $quote['prepared_for_client_at'] = $now;
    $quote['updated_at'] = $now;
    unset($quote['approved_at'], $quote['approval_id']);

    if (!op_save_quote($quotesDir, $quote)) {
        return ['ok' => false, 'error' => 'Could not update quote status.'];
    }

    $siteUrl = rtrim((string) ($config['site_url'] ?? ''), '/');
    if ($siteUrl === '') {
        $allowed = $config['allowed_origins'][0] ?? '';
        $siteUrl = is_string($allowed) ? rtrim($allowed, '/') : '';
    }
    $url = $siteUrl . '/approve/?token=' . $token;

    return ['ok' => true, 'token' => $token, 'url' => $url];
}