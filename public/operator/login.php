<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/rate-limit.php';
require_once __DIR__ . '/includes/render.php';

$config = op_load_config();
$brand = (string) ($config['brand'] ?? 'Operator');

if (!op_admin_enabled($config)) {
    http_response_code(503);
    echo 'Operator access is not configured.';
    exit;
}

op_start_session($config);

if (!empty($_SESSION['operator_authenticated'])) {
    header('Location: /operator/');
    exit;
}

$error = '';
$logDir = op_logs_dir($config);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $limit = op_rate_limit_check($logDir);
    if ($limit['blocked']) {
        $error = 'Too many failed attempts. Try again in about 15 minutes.';
    } else {
        $password = (string) ($_POST['password'] ?? '');
        $admin = $config['admin'] ?? [];
        $hash = is_array($admin) ? (string) ($admin['password_hash'] ?? '') : '';

        if ($hash !== '' && $password !== '' && password_verify($password, $hash)) {
            session_regenerate_id(true);
            $_SESSION['operator_authenticated'] = true;
            op_rate_limit_clear($logDir);
            header('Location: /operator/');
            exit;
        }

        op_rate_limit_record_failure($logDir);
        $error = 'Invalid password.';
    }
}

op_layout_start('Sign in', $brand);
op_render_header($brand, false);
echo '<div class="login-box card">';
echo '<h2 style="margin-top:0;">Sign in</h2><p class="muted">Read-only work request access.</p>';
if ($error !== '') {
    echo '<div class="error">' . op_h($error) . '</div>';
}
echo '<form method="post" action="/operator/login.php">';
echo '<label class="label" for="password">Password</label>';
echo '<input id="password" name="password" type="password" autocomplete="current-password" required style="margin:0.5rem 0 1rem;">';
echo '<button type="submit" class="btn">Sign in</button></form></div>';
op_layout_end();