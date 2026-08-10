<?php
declare(strict_types=1);

function op_h(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function op_layout_start(string $title, string $brand): void
{
    echo '<!doctype html><html lang="en"><head>';
    echo '<meta charset="UTF-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '<meta name="robots" content="noindex,nofollow">';
    echo '<title>' . op_h($title) . ' | ' . op_h($brand) . ' Operator</title>';
    echo '<style>
      :root { color-scheme: dark; }
      * { box-sizing: border-box; }
      body { margin: 0; font-family: system-ui, -apple-system, Segoe UI, sans-serif; background: #0f172a; color: #e2e8f0; line-height: 1.5; }
      a { color: #67e8f9; text-decoration: none; }
      a:hover { text-decoration: underline; }
      .wrap { max-width: 1100px; margin: 0 auto; padding: 1.25rem; }
      header { display: flex; justify-content: space-between; align-items: center; gap: 1rem; border-bottom: 1px solid #1e293b; padding-bottom: 1rem; margin-bottom: 1.5rem; }
      h1 { margin: 0; font-size: 1.35rem; }
      h2 { margin: 1.5rem 0 0.5rem; font-size: 1rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.08em; }
      table { width: 100%; border-collapse: collapse; font-size: 0.92rem; }
      th, td { text-align: left; padding: 0.65rem 0.5rem; border-bottom: 1px solid #1e293b; vertical-align: top; }
      th { color: #94a3b8; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.06em; }
      .card { background: #111827; border: 1px solid #1e293b; border-radius: 0.75rem; padding: 1rem 1.1rem; margin-bottom: 1rem; }
      .grid { display: grid; gap: 0.35rem 1rem; grid-template-columns: minmax(9rem, 12rem) 1fr; }
      .label { color: #94a3b8; }
      .muted { color: #94a3b8; font-size: 0.9rem; }
      .btn, button { display: inline-block; background: #0e7490; color: #fff; border: 0; border-radius: 0.5rem; padding: 0.55rem 0.9rem; font-weight: 600; cursor: pointer; }
      .btn-secondary { background: #334155; }
      .error { background: #450a0a; border: 1px solid #7f1d1d; color: #fecaca; padding: 0.75rem 1rem; border-radius: 0.5rem; margin-bottom: 1rem; }
      .login-box { max-width: 24rem; margin: 4rem auto; }
      input[type=password] { width: 100%; padding: 0.6rem 0.7rem; border-radius: 0.5rem; border: 1px solid #334155; background: #0b1220; color: #e2e8f0; }
      details { margin-top: 1rem; }
      summary { cursor: pointer; color: #94a3b8; }
      pre { white-space: pre-wrap; word-break: break-word; background: #0b1220; padding: 0.75rem; border-radius: 0.5rem; overflow: auto; font-size: 0.8rem; }
      .success { background: #052e16; border: 1px solid #166534; color: #bbf7d0; padding: 0.75rem 1rem; border-radius: 0.5rem; margin-bottom: 1rem; }
      .form-grid { display: grid; gap: 0.75rem; }
      .form-row { display: grid; gap: 0.35rem; }
      .form-row label { color: #94a3b8; font-size: 0.85rem; }
      input[type=text], input[type=number], input[type=email], textarea, select {
        width: 100%; padding: 0.55rem 0.65rem; border-radius: 0.5rem; border: 1px solid #334155;
        background: #0b1220; color: #e2e8f0; font: inherit;
      }
      textarea { min-height: 5rem; resize: vertical; }
      .line-items { overflow: auto; }
      .calc-hint { color: #64748b; font-size: 0.8rem; }
      .actions { display: flex; gap: 0.75rem; flex-wrap: wrap; margin-top: 1rem; }
    </style></head><body><div class="wrap">';
}

function op_layout_end(): void
{
    echo '</div></body></html>';
}

function op_render_header(string $brand, bool $showNav = true): void
{
    echo '<header><div><p class="muted" style="margin:0;">Operator</p><h1>' . op_h($brand) . '</h1></div>';
    if ($showNav) {
        echo '<div style="display:flex;gap:0.75rem;align-items:center;">';
        echo '<a class="btn btn-secondary" href="/operator/">Requests</a>';
        echo '<form method="post" action="/operator/logout.php" style="margin:0;">';
        echo '<button type="submit" class="btn btn-secondary">Log out</button></form></div>';
    }
    echo '</header>';
}

/** @param mixed $value */
function op_format_value($value): string
{
    if ($value === null || $value === '') {
        return '—';
    }
    if (is_bool($value)) {
        return $value ? 'Yes' : 'No';
    }
    if (is_array($value)) {
        if ($value === []) {
            return '—';
        }
        $flat = [];
        foreach ($value as $item) {
            if (is_scalar($item) || $item === null) {
                $flat[] = (string) $item;
            } else {
                $flat[] = json_encode($item, JSON_UNESCAPED_SLASHES);
            }
        }
        return implode(', ', $flat);
    }
    return (string) $value;
}

/** @param array<string, mixed> $record */
function op_render_field_grid(array $pairs): void
{
    echo '<div class="grid">';
    foreach ($pairs as $label => $value) {
        echo '<div class="label">' . op_h($label) . '</div><div>' . op_h(op_format_value($value)) . '</div>';
    }
    echo '</div>';
}