<?php
/**
 * Copy to config.php on the server. Never commit config.php.
 * See docs/hostinger-email-setup.md and docs/config.php.paste-star-glass.md
 */
return [
    'brand' => 'Star Glass Digital',
    'site_url' => 'https://starglassdigital.com',
    'from_name' => 'Star Glass Digital',
    'from_email' => 'webdev@starglassdigital.com',
    'operator_email' => 'webdev@starglassdigital.com',
    'notify_email' => 'webdev@starglassdigital.com',
    'storage_dir' => dirname(__DIR__, 2) . '/private/work-requests',
    'quotes_dir' => dirname(__DIR__, 2) . '/private/quotes',
    'approval_sessions_dir' => dirname(__DIR__, 2) . '/private/quote-approval-sessions',
    'approvals_dir' => dirname(__DIR__, 2) . '/private/quote-approvals',
    'payments_dir' => dirname(__DIR__, 2) . '/private/quote-payments',
    'allowed_origins' => [
        'https://starglassdigital.com',
        'https://www.starglassdigital.com',
    ],
    'smtp' => [
        'enabled' => true,
        'host' => 'smtp.hostinger.com',
        'port' => 465,
        'encryption' => 'ssl',
        'username' => 'webdev@starglassdigital.com',
        'password' => '', // Hostinger mailbox password — set on server only
        'ehlo' => 'starglassdigital.com',
    ],
    'admin' => [
        'enabled' => true,
        'password_hash' => '', // password_hash() — set on server only via scripts/set-admin-password.mjs
        'session_name' => 'sgd_operator',
    ],
    'approval' => [
        'token_ttl_days' => 14,
        'terms_version' => '2026-06-28',
        'authorization_text' => 'You are approving the scope, package, and total price shown on this page. This tells Star Glass Digital you agree to the work described above. Submitting this approval does not charge your card or process any payment. After you approve, we will contact you by email with next steps — such as invoice details, scheduling, or project kickoff.',
    ],
    'stripe' => [
        'enabled' => true,
        'test_mode' => true,
        'secret_key' => '', // sk_test_… — set on server only
        'webhook_secret' => '', // whsec_… — set on server only
        'currency' => 'usd',
    ],
];