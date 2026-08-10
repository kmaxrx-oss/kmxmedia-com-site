<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

$config = op_load_config();

if (!op_admin_enabled($config)) {
    http_response_code(503);
    echo 'Operator access is not configured.';
    exit;
}

op_start_session($config);

if (empty($_SESSION['operator_authenticated'])) {
    header('Location: /operator/login.php');
    exit;
}