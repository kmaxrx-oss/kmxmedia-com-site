<?php
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/api/includes/load-payment.php';
require_once dirname(__DIR__, 2) . '/api/includes/payments-bootstrap.php';

function op_payments_dir(array $config): string
{
    return pay_payments_dir($config);
}

/** @return array<string, mixed>|null */
function op_load_payment(string $paymentsDir, string $id): ?array
{
    return pay_load_payment($paymentsDir, $id);
}

function op_valid_payment_id(string $id): bool
{
    return pay_valid_payment_id($id);
}