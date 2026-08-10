<?php
declare(strict_types=1);

function ap_h(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function ap_layout_start(string $title, string $brand): void
{
    echo '<!doctype html><html lang="en"><head><meta charset="UTF-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '<meta name="robots" content="noindex,nofollow">';
    echo '<title>' . ap_h($title) . ' | ' . ap_h($brand) . '</title>';
    echo '<style>
      body{margin:0;font-family:system-ui,-apple-system,Segoe UI,sans-serif;background:#fbfaf6;color:#22332d;line-height:1.55}
      .wrap{max-width:42rem;margin:0 auto;padding:1.5rem}
      h1{font-size:1.6rem;margin:0 0 0.5rem}
      h2{font-size:0.85rem;text-transform:uppercase;letter-spacing:.08em;color:#7a583a;margin:1.5rem 0 0.5rem}
      .card{background:#fff;border:1px solid #e0d5c5;border-radius:1rem;padding:1.1rem 1.2rem;margin:1rem 0}
      .muted{color:#596861;font-size:0.95rem}
      .price{font-size:1.35rem;font-weight:800;color:#22332d}
      ul{margin:0.4rem 0;padding-left:1.2rem}
      label{display:block;margin:0.5rem 0 0.25rem;font-weight:600;font-size:0.9rem}
      input[type=text],input[type=email]{width:100%;padding:0.55rem;border:1px solid #d4cab8;border-radius:0.5rem}
      .btn{display:inline-block;background:#22332d;color:#fbfaf6;border:0;border-radius:999px;padding:0.7rem 1.2rem;font-weight:700;cursor:pointer}
      .error{background:#fef2f2;border:1px solid #fecaca;color:#991b1b;padding:0.75rem;border-radius:0.5rem}
      .terms{white-space:pre-wrap;font-size:0.92rem;color:#394941}
    </style></head><body><div class="wrap">';
}

function ap_layout_end(): void
{
    echo '</div></body></html>';
}

/** @param array<string, mixed> $view */
function ap_render_quote_summary(array $view): void
{
    echo '<p class="muted">' . ap_h((string) $view['brand']) . '</p>';
    echo '<h1>Quote for ' . ap_h((string) $view['business_name']) . '</h1>';
    echo '<div class="card"><h2>Package</h2><p><strong>' . ap_h((string) $view['package_name']) . '</strong></p></div>';
    echo '<div class="card"><h2>Scope</h2><p>' . nl2br(ap_h((string) $view['scope_summary'])) . '</p></div>';

    if (($view['line_items'] ?? []) !== []) {
        echo '<div class="card"><h2>Included work</h2><ul>';
        foreach ($view['line_items'] as $item) {
            if (!is_array($item)) {
                continue;
            }
            $qty = (float) ($item['quantity'] ?? 1);
            $label = (string) ($item['label'] ?? '');
            $suffix = $qty > 1 ? ' (×' . $qty . ')' : '';
            echo '<li>' . ap_h($label . $suffix) . '</li>';
        }
        echo '</ul></div>';
    }

    if (($view['phases'] ?? []) !== []) {
        echo '<div class="card"><h2>Phased approach</h2><ul>';
        foreach ($view['phases'] as $phase) {
            if (!is_array($phase)) {
                continue;
            }
            $title = (string) ($phase['title'] ?? '');
            $summary = trim((string) ($phase['summary'] ?? ''));
            $opt = !empty($phase['optional']) ? ' (optional)' : '';
            echo '<li><strong>' . ap_h($title . $opt) . '</strong>';
            if ($summary !== '') {
                echo ' — ' . ap_h($summary);
            }
            echo '</li>';
        }
        echo '</ul></div>';
    }

    if (($view['optional_line_items'] ?? []) !== [] || ($view['optional_later'] ?? []) !== []) {
        echo '<div class="card"><h2>Optional later work</h2><ul>';
        foreach ($view['optional_line_items'] ?? [] as $item) {
            if (!is_array($item)) {
                continue;
            }
            echo '<li>' . ap_h((string) ($item['label'] ?? '')) . '</li>';
        }
        foreach ($view['optional_later'] ?? [] as $item) {
            echo '<li>' . ap_h((string) $item) . '</li>';
        }
        echo '</ul></div>';
    }

    echo '<div class="card"><h2>Price</h2><p class="price">$' . ap_h(number_format((float) ($view['final_price'] ?? 0), 2)) . '</p>';
    if ($view['deposit_required'] !== null) {
        echo '<p class="muted">Deposit if applicable: $' . ap_h(number_format((float) $view['deposit_required'], 2)) . ' (not collected on this page)</p>';
    }
    echo '<p class="muted">This approval is not a payment or automatic charge.</p></div>';
}