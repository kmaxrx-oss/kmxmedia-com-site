<?php
declare(strict_types=1);

/**
 * Send operator notification. Prefers Hostinger SMTP when configured.
 * Falls back to PHP mail() with envelope sender (-f).
 */
function wr_log_mail(string $storageDir, string $line): void
{
    $logDir = dirname($storageDir) . '/logs';
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0750, true);
    }
    $entry = gmdate('c') . ' ' . $line . PHP_EOL;
    @file_put_contents($logDir . '/mail.log', $entry, FILE_APPEND | LOCK_EX);
}

function wr_send_mail(array $config, string $storageDir, string $to, string $subject, string $body, string $replyTo): bool
{
    $from = (string) ($config['from_email'] ?? '');
    $fromName = (string) ($config['from_name'] ?? ($config['brand'] ?? 'Work Request'));

    $smtp = $config['smtp'] ?? null;
    if (is_array($smtp) && !empty($smtp['enabled']) && !empty($smtp['password'])) {
        $ok = wr_smtp_send($smtp, $from, $fromName, $to, $subject, $body, $replyTo);
        if ($ok) {
            wr_log_mail($storageDir, "SMTP ok to=$to subject=" . substr($subject, 0, 80));
            return true;
        }
        wr_log_mail($storageDir, "SMTP failed to=$to — falling back to mail()");
    }

    $headers = [];
    if ($from !== '') {
        $headers[] = 'From: ' . wr_encode_address($fromName, $from);
    }
    if ($replyTo !== '') {
        $headers[] = 'Reply-To: ' . $replyTo;
    }
    $headers[] = 'Content-Type: text/plain; charset=UTF-8';
    $headers[] = 'MIME-Version: 1.0';

    $params = $from !== '' ? ('-f ' . $from) : '';
    $ok = $params !== ''
        ? mail($to, $subject, $body, implode("\r\n", $headers), $params)
        : mail($to, $subject, $body, implode("\r\n", $headers));

    wr_log_mail($storageDir, ($ok ? 'mail() ok' : 'mail() failed') . " to=$to from=$from");
    return $ok;
}

function wr_encode_address(string $name, string $email): string
{
    $safeName = str_replace(['"', "\r", "\n"], '', $name);
    return '"' . $safeName . '" <' . $email . '>';
}

function wr_smtp_send(array $smtp, string $from, string $fromName, string $to, string $subject, string $body, string $replyTo): bool
{
    $host = (string) ($smtp['host'] ?? 'smtp.hostinger.com');
    $port = (int) ($smtp['port'] ?? 465);
    $user = (string) ($smtp['username'] ?? $from);
    $pass = (string) ($smtp['password'] ?? '');
    $encryption = (string) ($smtp['encryption'] ?? 'ssl');

    $remote = ($encryption === 'ssl' ? 'ssl://' : 'tcp://') . $host . ':' . $port;
    $fp = @stream_socket_client($remote, $errno, $errstr, 20, STREAM_CLIENT_CONNECT);
    if (!$fp) {
        return false;
    }

    stream_set_timeout($fp, 20);

    if (!wr_smtp_expect($fp, [220])) {
        fclose($fp);
        return false;
    }
    wr_smtp_cmd($fp, 'EHLO ' . ($smtp['ehlo'] ?? 'starglassdigital.com'));
    if (!wr_smtp_expect($fp, [250])) {
        fclose($fp);
        return false;
    }

    if ($encryption === 'tls') {
        wr_smtp_cmd($fp, 'STARTTLS');
        if (!wr_smtp_expect($fp, [220])) {
            fclose($fp);
            return false;
        }
        if (!stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
            fclose($fp);
            return false;
        }
        wr_smtp_cmd($fp, 'EHLO ' . ($smtp['ehlo'] ?? 'starglassdigital.com'));
        if (!wr_smtp_expect($fp, [250])) {
            fclose($fp);
            return false;
        }
    }

    wr_smtp_cmd($fp, 'AUTH LOGIN');
    if (!wr_smtp_expect($fp, [334])) {
        fclose($fp);
        return false;
    }
    wr_smtp_cmd($fp, base64_encode($user));
    if (!wr_smtp_expect($fp, [334])) {
        fclose($fp);
        return false;
    }
    wr_smtp_cmd($fp, base64_encode($pass));
    if (!wr_smtp_expect($fp, [235])) {
        fclose($fp);
        return false;
    }

    wr_smtp_cmd($fp, 'MAIL FROM:<' . $from . '>');
    if (!wr_smtp_expect($fp, [250])) {
        fclose($fp);
        return false;
    }
    wr_smtp_cmd($fp, 'RCPT TO:<' . $to . '>');
    if (!wr_smtp_expect($fp, [250, 251])) {
        fclose($fp);
        return false;
    }
    wr_smtp_cmd($fp, 'DATA');
    if (!wr_smtp_expect($fp, [354])) {
        fclose($fp);
        return false;
    }

    $message = '';
    $message .= 'Date: ' . gmdate('D, d M Y H:i:s') . " +0000\r\n";
    $message .= 'From: ' . wr_encode_address($fromName, $from) . "\r\n";
    $message .= 'To: <' . $to . ">\r\n";
    if ($replyTo !== '') {
        $message .= 'Reply-To: ' . $replyTo . "\r\n";
    }
    $message .= 'Subject: ' . wr_encode_header($subject) . "\r\n";
    $message .= "MIME-Version: 1.0\r\n";
    $message .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $message .= "Content-Transfer-Encoding: 8bit\r\n";
    $message .= "\r\n";
    $message .= str_replace(["\r\n.", "\n."], ["\r\n..", "\n.."], $body);
    $message .= "\r\n.\r\n";

    fwrite($fp, $message);
    if (!wr_smtp_expect($fp, [250])) {
        fclose($fp);
        return false;
    }

    wr_smtp_cmd($fp, 'QUIT');
    fclose($fp);
    return true;
}

function wr_smtp_cmd($fp, string $cmd): void
{
    fwrite($fp, $cmd . "\r\n");
}

function wr_smtp_expect($fp, array $codes): bool
{
    $response = '';
    while (($line = fgets($fp, 515)) !== false) {
        $response .= $line;
        if (isset($line[3]) && $line[3] === ' ') {
            break;
        }
    }
    if ($response === '') {
        return false;
    }
    $code = (int) substr($response, 0, 3);
    return in_array($code, $codes, true);
}

function wr_encode_header(string $value): string
{
    return preg_match('/[^\x20-\x7E]/', $value) ? ('=?UTF-8?B?' . base64_encode($value) . '?=') : $value;
}