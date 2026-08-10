<?php
declare(strict_types=1);

function pay_h(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function pay_layout_start(string $title, string $brand): void
{
    echo '<!doctype html><html lang="en"><head><meta charset="UTF-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '<meta name="robots" content="noindex,nofollow">';
    echo '<title>' . pay_h($title) . ' | ' . pay_h($brand) . '</title>';
    echo '<style>
      body{margin:0;font-family:system-ui,-apple-system,Segoe UI,sans-serif;background:#fbfaf6;color:#22332d;line-height:1.55}
      .wrap{max-width:42rem;margin:0 auto;padding:1.5rem}
      h1{font-size:1.6rem;margin:0 0 0.5rem}
      .card{background:#fff;border:1px solid #e0d5c5;border-radius:1rem;padding:1.1rem 1.2rem;margin:1rem 0}
      .muted{color:#596861;font-size:0.95rem}
    </style></head><body><div class="wrap">';
}

function pay_layout_end(): void
{
    echo '</div></body></html>';
}