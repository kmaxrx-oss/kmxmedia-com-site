# Paste into KMX `config.php`

**Server path:**  
`/home/u467937533/domains/kmxmedia.com/public_html/api/config.php`

Set **`smtp.password`** on the server only.

```php
<?php
return [
    'brand' => 'KMX Media',
    'from_name' => 'KMX Media',
    'from_email' => 'webdev@starglassdigital.com',
    'operator_email' => 'webdev@starglassdigital.com',
    'notify_email' => 'webdev@starglassdigital.com',
    'storage_dir' => dirname(__DIR__, 2) . '/private/work-requests',
    'allowed_origins' => [
        'https://kmxmedia.com',
        'https://www.kmxmedia.com',
    ],
    'smtp' => [
        'enabled' => true,
        'host' => 'smtp.hostinger.com',
        'port' => 465,
        'encryption' => 'ssl',
        'username' => 'webdev@starglassdigital.com',
        'password' => 'PASTE_HOSTINGER_MAILBOX_PASSWORD_HERE',
        'ehlo' => 'kmxmedia.com',
    ],
];
```