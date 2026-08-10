<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/load-request.php';
require_once __DIR__ . '/includes/load-quote.php';
require_once __DIR__ . '/includes/list-quotes-for-request.php';
require_once __DIR__ . '/includes/seed-quote-from-request.php';
require_once __DIR__ . '/includes/quote-schema.php';
require_once __DIR__ . '/includes/save-quote.php';
require_once __DIR__ . '/includes/prepare-approval.php';
require_once __DIR__ . '/includes/revoke-approval.php';
require_once __DIR__ . '/includes/create-checkout.php';
require_once __DIR__ . '/includes/mark-paid-offline.php';
require_once __DIR__ . '/includes/render.php';

$brand = (string) ($config['brand'] ?? 'Operator');
$storageDir = op_storage_dir($config);
$quotesDir = op_quotes_dir($config);
$errors = [];
$saved = isset($_GET['saved']);
$prepared = isset($_GET['prepared']);
$checkoutReady = isset($_GET['checkout']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!op_csrf_verify($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Invalid session token. Refresh and try again.';
    } else {
        $action = trim((string) ($_POST['action'] ?? 'save_draft'));
        $existingId = trim((string) ($_POST['quote_id'] ?? ''));
        $existing = op_valid_quote_id($existingId) ? op_load_quote($quotesDir, $existingId) : null;

        if ($action === 'prepare_approval') {
            if ($existing === null) {
                $errors[] = 'Quote not found.';
            } else {
                $result = op_prepare_quote_approval($config, $existing);
                if ($result['ok']) {
                    $_SESSION['approval_url_flash'] = (string) ($result['url'] ?? '');
                    header('Location: /operator/quote.php?id=' . urlencode($existingId) . '&prepared=1');
                    exit;
                }
                $errors[] = (string) ($result['error'] ?? 'Could not prepare approval.');
            }
        } elseif ($action === 'revoke_approval') {
            if ($existing === null) {
                $errors[] = 'Quote not found.';
            } else {
                $result = op_revoke_quote_approval($config, $existing);
                if ($result['ok']) {
                    header('Location: /operator/quote.php?id=' . urlencode($existingId) . '&revoked=1');
                    exit;
                }
                $errors[] = (string) ($result['error'] ?? 'Could not revoke approval.');
            }
        } elseif ($action === 'create_checkout') {
            if ($existing === null) {
                $errors[] = 'Quote not found.';
            } else {
                $result = op_create_quote_checkout($config, $existing);
                if ($result['ok']) {
                    $_SESSION['checkout_url_flash'] = (string) ($result['url'] ?? '');
                    header('Location: /operator/quote.php?id=' . urlencode($existingId) . '&checkout=1');
                    exit;
                }
                $errors[] = (string) ($result['error'] ?? 'Could not create checkout.');
            }
        } elseif ($action === 'mark_paid_offline') {
            if ($existing === null) {
                $errors[] = 'Quote not found.';
            } else {
                $note = trim((string) ($_POST['offline_note'] ?? ''));
                $result = op_mark_quote_paid_offline($config, $existing, $note);
                if ($result['ok']) {
                    header('Location: /operator/quote.php?id=' . urlencode($existingId) . '&paid_offline=1');
                    exit;
                }
                $errors[] = (string) ($result['error'] ?? 'Could not mark paid offline.');
            }
        } else {
            if ($existing !== null && ($existing['status'] ?? '') !== 'draft') {
                $errors[] = 'Only draft quotes can be edited.';
            } else {
                $parsed = op_parse_quote_post($_POST, $existing, $brand);

                if (!$parsed['ok']) {
                    $errors = $parsed['errors'];
                } else {
                    $quote = $parsed['quote'];
                    $requestId = (string) $quote['work_request_id'];
                    $request = op_load_request($storageDir, $requestId);
                    if ($request === null) {
                        $errors[] = 'Work request not found.';
                    } elseif ($existing === null) {
                        $dup = op_find_draft_for_request($quotesDir, $requestId);
                        if ($dup !== null) {
                            header('Location: /operator/quote.php?id=' . urlencode((string) $dup['id']));
                            exit;
                        }
                    }

                    if ($errors === [] && op_save_quote($quotesDir, $quote)) {
                        header('Location: /operator/quote.php?id=' . urlencode((string) $quote['id']) . '&saved=1');
                        exit;
                    }
                    if ($errors === []) {
                        $errors[] = 'Could not save quote.';
                    }
                }
            }
        }
    }
}

$quoteId = trim((string) ($_GET['id'] ?? ''));
$requestId = trim((string) ($_GET['request_id'] ?? ''));
$quote = null;
$isNew = false;

if (op_valid_quote_id($quoteId)) {
    $quote = op_load_quote($quotesDir, $quoteId);
    if ($quote === null) {
        http_response_code(404);
        op_layout_start('Not found', $brand);
        op_render_header($brand);
        echo '<p class="muted">Quote not found.</p>';
        op_layout_end();
        exit;
    }
    $requestId = (string) ($quote['work_request_id'] ?? '');
} elseif (op_valid_request_id($requestId)) {
    $existing = op_find_quote_for_request($quotesDir, $requestId);
    if ($existing !== null) {
        header('Location: /operator/quote.php?id=' . urlencode((string) $existing['id']));
        exit;
    }
    $request = op_load_request($storageDir, $requestId);
    if ($request === null) {
        http_response_code(404);
        op_layout_start('Not found', $brand);
        op_render_header($brand);
        echo '<p class="muted">Work request not found.</p>';
        op_layout_end();
        exit;
    }
    $quote = op_seed_quote_from_request($request, $brand, op_new_id(), $requestId);
    $isNew = true;
} else {
    http_response_code(400);
    op_layout_start('Quote', $brand);
    op_render_header($brand);
    echo '<p class="muted">Missing quote or request id.</p>';
    op_layout_end();
    exit;
}

$status = (string) ($quote['status'] ?? 'draft');
$isDraft = $status === 'draft';
$isLocked = in_array($status, ['pending_approval', 'approved', 'checkout_open', 'paid'], true);
$request = op_load_request($storageDir, (string) $quote['work_request_id']);
$lineItems = is_array($quote['line_items'] ?? null) ? $quote['line_items'] : [];
$phases = is_array($quote['phases'] ?? null) ? $quote['phases'] : [];
$optionalLater = is_array($quote['optional_later'] ?? null) ? $quote['optional_later'] : [];
$optionalLaterText = implode("\n", $optionalLater);
$csrf = op_csrf_token();

$pageTitles = [
    'pending_approval' => 'Quote pending approval',
    'approved' => 'Approved quote',
    'checkout_open' => 'Quote — checkout open',
    'paid' => 'Paid quote',
];
$pageTitle = $isNew ? 'New draft quote' : ($isDraft ? 'Edit draft quote' : ($pageTitles[$status] ?? 'Quote'));
op_layout_start($pageTitle, $brand);
op_render_header($brand);
echo '<p><a href="/operator/request.php?id=' . op_h((string) $quote['work_request_id']) . '">&larr; Back to request</a></p>';
echo '<h2>' . op_h($pageTitle) . '</h2>';
echo '<p class="muted">Status: <strong>' . op_h($status) . '</strong>';
if ($isLocked) {
    echo ' · read-only';
}
echo '</p>';

if ($saved) {
    echo '<div class="success">Draft quote saved.</div>';
}
if (isset($_GET['revoked'])) {
    echo '<div class="success">Approval revoked. Quote returned to draft.</div>';
}
if ($prepared && !empty($_SESSION['approval_url_flash'])) {
    $flashUrl = (string) $_SESSION['approval_url_flash'];
    unset($_SESSION['approval_url_flash']);
    echo '<div class="success">Client approval link ready (copy now — shown once):<br><code style="word-break:break-all">' . op_h($flashUrl) . '</code></div>';
}
if ($checkoutReady && !empty($_SESSION['checkout_url_flash'])) {
    $flashUrl = (string) $_SESSION['checkout_url_flash'];
    unset($_SESSION['checkout_url_flash']);
    echo '<div class="success">Stripe checkout link ready (copy now — shown once):<br><code style="word-break:break-all">' . op_h($flashUrl) . '</code></div>';
}
if (isset($_GET['paid_offline'])) {
    echo '<div class="success">Quote marked paid offline. Payment record saved.</div>';
}
foreach ($errors as $err) {
    echo '<div class="error">' . op_h($err) . '</div>';
}

if ($isLocked) {
    op_render_field_grid([
        'Business' => $quote['business_name'] ?? null,
        'Contact' => $quote['contact_name'] ?? null,
        'Email' => $quote['contact_email'] ?? null,
        'Package' => ($quote['package_code'] ?? '') . ' — ' . ($quote['package_name'] ?? ''),
        'Scope' => $quote['scope_summary'] ?? null,
        'Final price' => isset($quote['final_price']) ? '$' . number_format((float) $quote['final_price'], 2) : null,
        'Prepared for client' => $quote['prepared_for_client_at'] ?? null,
        'Approved at' => $quote['approved_at'] ?? null,
        'Approval ID' => $quote['approval_id'] ?? null,
        'Payment ID' => $quote['payment_id'] ?? null,
        'Paid at' => $quote['paid_at'] ?? null,
        'Paid via' => $quote['paid_via'] ?? null,
        'Checkout expires' => $quote['checkout_expires_at'] ?? null,
    ]);

    if ($status === 'pending_approval') {
        echo '<form method="post" action="/operator/quote.php" class="actions">';
        echo '<input type="hidden" name="csrf_token" value="' . op_h($csrf) . '">';
        echo '<input type="hidden" name="quote_id" value="' . op_h((string) $quote['id']) . '">';
        echo '<input type="hidden" name="action" value="revoke_approval">';
        echo '<button type="submit" class="btn btn-secondary">Revoke approval link</button></form>';
    } else {
        echo '<div class="actions">';
        if (!empty($quote['approval_id'])) {
            echo '<a class="btn btn-secondary" href="/operator/approval.php?id=' . op_h((string) $quote['approval_id']) . '">View approval record</a>';
        }
        if (in_array($status, ['approved', 'checkout_open'], true)) {
            echo '<form method="post" style="display:inline" action="/operator/quote.php">';
            echo '<input type="hidden" name="csrf_token" value="' . op_h($csrf) . '">';
            echo '<input type="hidden" name="quote_id" value="' . op_h((string) $quote['id']) . '">';
            echo '<input type="hidden" name="action" value="create_checkout">';
            $checkoutLabel = $status === 'checkout_open' ? 'Replace checkout link' : 'Create Stripe checkout link';
            echo '<button type="submit" class="btn">' . op_h($checkoutLabel) . '</button></form>';
            echo '<form method="post" style="display:inline" action="/operator/quote.php" onsubmit="return confirm(\'Mark this quote paid offline? This cannot be undone.\');">';
            echo '<input type="hidden" name="csrf_token" value="' . op_h($csrf) . '">';
            echo '<input type="hidden" name="quote_id" value="' . op_h((string) $quote['id']) . '">';
            echo '<input type="hidden" name="action" value="mark_paid_offline">';
            echo '<input type="hidden" name="offline_note" value="Phase 5 operator offline payment">';
            echo '<button type="submit" class="btn btn-secondary">Mark paid offline</button></form>';
        }
        if ($status === 'paid' && !empty($quote['payment_id'])) {
            echo '<a class="btn btn-secondary" href="/operator/payment.php?id=' . op_h((string) $quote['payment_id']) . '">View payment record</a>';
        }
        echo '</div>';
    }

    op_layout_end();
    exit;
}

echo '<form method="post" action="/operator/quote.php" id="quote-form">';
echo '<input type="hidden" name="csrf_token" value="' . op_h($csrf) . '">';
echo '<input type="hidden" name="quote_id" value="' . op_h((string) $quote['id']) . '">';
echo '<input type="hidden" name="work_request_id" value="' . op_h((string) $quote['work_request_id']) . '">';
echo '<input type="hidden" name="action" value="save_draft">';

echo '<div class="card"><h2>Client context</h2><div class="form-grid">';
echo '<input type="hidden" name="business_name" value="' . op_h((string) $quote['business_name']) . '">';
echo '<input type="hidden" name="contact_name" value="' . op_h((string) $quote['contact_name']) . '">';
echo '<input type="hidden" name="contact_email" value="' . op_h((string) $quote['contact_email']) . '">';
op_render_field_grid([
    'Business' => $quote['business_name'] ?? null,
    'Contact' => $quote['contact_name'] ?? null,
    'Email' => $quote['contact_email'] ?? null,
]);
echo '</div></div>';

echo '<div class="card"><h2>Package &amp; scope</h2><div class="form-grid">';
echo '<div class="form-row"><label for="package_code">Package code</label>';
echo '<select name="package_code" id="package_code">';
foreach (['G', 'A', 'B', 'C', 'I', 'D', 'E', 'F', 'H'] as $code) {
    $sel = ((string) ($quote['package_code'] ?? '') === $code) ? ' selected' : '';
    echo '<option value="' . op_h($code) . '"' . $sel . '>' . op_h($code) . '</option>';
}
echo '</select></div>';
echo '<div class="form-row"><label for="package_name">Package name</label>';
echo '<input type="text" name="package_name" id="package_name" value="' . op_h((string) ($quote['package_name'] ?? '')) . '"></div>';
echo '<div class="form-row"><label for="scope_summary">Scope summary</label>';
echo '<textarea name="scope_summary" id="scope_summary">' . op_h((string) ($quote['scope_summary'] ?? '')) . '</textarea></div>';
echo '</div></div>';

echo '<div class="card"><h2>Line items</h2><p class="calc-hint">Totals auto-calculate from line items; you can override pricing fields below.</p>';
echo '<div class="line-items"><table id="line-items-table"><thead><tr>';
foreach (['Label', 'Category', 'Qty', 'Unit $', 'Hrs min', 'Hrs max', 'Req', 'Later', ''] as $h) {
    echo '<th>' . op_h($h) . '</th>';
}
echo '</tr></thead><tbody>';
foreach ($lineItems as $i => $item) {
    if (!is_array($item)) {
        continue;
    }
    echo '<tr class="line-item-row">';
    echo '<td><input type="text" name="line_items[' . $i . '][label]" value="' . op_h((string) ($item['label'] ?? '')) . '"></td>';
    echo '<td><input type="text" name="line_items[' . $i . '][category]" value="' . op_h((string) ($item['category'] ?? 'scope')) . '"></td>';
    echo '<td><input type="number" step="0.01" min="0" class="li-qty" name="line_items[' . $i . '][quantity]" value="' . op_h((string) ($item['quantity'] ?? 1)) . '"></td>';
    echo '<td><input type="number" step="0.01" min="0" class="li-price" name="line_items[' . $i . '][unit_price]" value="' . op_h((string) ($item['unit_price'] ?? 0)) . '"></td>';
    echo '<td><input type="number" step="0.01" min="0" class="li-hmin" name="line_items[' . $i . '][hours_min]" value="' . op_h((string) ($item['hours_min'] ?? 0)) . '"></td>';
    echo '<td><input type="number" step="0.01" min="0" class="li-hmax" name="line_items[' . $i . '][hours_max]" value="' . op_h((string) ($item['hours_max'] ?? 0)) . '"></td>';
    echo '<td><input type="checkbox" name="line_items[' . $i . '][required]" value="1"' . (!empty($item['required']) ? ' checked' : '') . '></td>';
    echo '<td><input type="checkbox" name="line_items[' . $i . '][later_option]" value="1"' . (!empty($item['later_option']) ? ' checked' : '') . '></td>';
    echo '<td><button type="button" class="btn btn-secondary remove-line">×</button></td>';
    echo '</tr>';
}
echo '</tbody></table></div>';
echo '<button type="button" class="btn btn-secondary" id="add-line">Add line item</button></div>';

echo '<div class="card"><h2>Phase recommendation</h2><div id="phases-list" class="form-grid">';
foreach ($phases as $i => $phase) {
    if (!is_array($phase)) {
        continue;
    }
    echo '<div class="form-row phase-row"><label>Phase ' . ($i + 1) . '</label>';
    echo '<input type="text" name="phases[' . $i . '][title]" placeholder="Title" value="' . op_h((string) ($phase['title'] ?? '')) . '">';
    echo '<input type="text" name="phases[' . $i . '][summary]" placeholder="Summary" value="' . op_h((string) ($phase['summary'] ?? '')) . '">';
    echo '<label><input type="checkbox" name="phases[' . $i . '][optional]" value="1"' . (!empty($phase['optional']) ? ' checked' : '') . '> Optional phase</label></div>';
}
echo '</div><button type="button" class="btn btn-secondary" id="add-phase">Add phase</button>';
echo '<div class="form-row" style="margin-top:1rem;"><label for="optional_later_text">Optional later (one per line)</label>';
echo '<textarea name="optional_later_text" id="optional_later_text">' . op_h($optionalLaterText) . '</textarea></div></div>';

echo '<div class="card"><h2>Pricing</h2><p class="calc-hint" id="calc-summary"></p><div class="form-grid">';
$pricingFields = [
    'estimated_hours_min' => 'Est. hours min',
    'estimated_hours_max' => 'Est. hours max',
    'subtotal_min' => 'Quote range min',
    'subtotal_max' => 'Quote range max',
    'final_price' => 'Final price',
    'deposit_required' => 'Deposit (informational)',
];
foreach ($pricingFields as $name => $label) {
    echo '<div class="form-row"><label for="' . op_h($name) . '">' . op_h($label) . '</label>';
    echo '<input type="number" step="0.01" min="0" name="' . op_h($name) . '" id="' . op_h($name) . '" value="' . op_h((string) ($quote[$name] ?? 0)) . '"></div>';
}
echo '</div></div>';

echo '<div class="card"><h2>Notes</h2><div class="form-grid">';
echo '<div class="form-row"><label for="operator_notes">Operator notes (internal)</label>';
echo '<textarea name="operator_notes" id="operator_notes">' . op_h((string) ($quote['operator_notes'] ?? '')) . '</textarea></div>';
echo '<div class="form-row"><label for="client_facing_notes">Client-facing notes (stored, not published)</label>';
echo '<textarea name="client_facing_notes" id="client_facing_notes">' . op_h((string) ($quote['client_facing_notes'] ?? '')) . '</textarea></div>';
echo '</div></div>';

echo '<div class="actions"><button type="submit" class="btn">Save draft</button>';
if (!$isNew) {
    echo '<button type="submit" class="btn btn-secondary" formaction="/operator/quote.php" name="action" value="prepare_approval">Prepare for client approval</button>';
}
echo '<a class="btn btn-secondary" href="/operator/request.php?id=' . op_h((string) $quote['work_request_id']) . '">Cancel</a></div>';
echo '</form>';

echo '<script>
(function () {
  const table = document.querySelector("#line-items-table tbody");
  const calcSummary = document.getElementById("calc-summary");
  let lineIndex = ' . count($lineItems) . ';
  let phaseIndex = ' . count($phases) . ';

  function num(v) { const n = parseFloat(v); return Number.isFinite(n) ? n : 0; }

  function recalc() {
    let subtotal = 0, hmin = 0, hmax = 0;
    document.querySelectorAll(".line-item-row").forEach((row) => {
      const qty = num(row.querySelector(".li-qty")?.value);
      const price = num(row.querySelector(".li-price")?.value);
      subtotal += qty * price;
      hmin += num(row.querySelector(".li-hmin")?.value);
      hmax += num(row.querySelector(".li-hmax")?.value);
    });
    calcSummary.textContent = "Calculated from line items: subtotal $" + subtotal.toFixed(2) + ", hours " + hmin.toFixed(2) + "–" + hmax.toFixed(2);
    const autoFields = [
      ["subtotal_min", subtotal], ["subtotal_max", subtotal],
      ["estimated_hours_min", hmin], ["estimated_hours_max", hmax]
    ];
    autoFields.forEach(([id, val]) => {
      const el = document.getElementById(id);
      if (el && el.dataset.manual !== "1") el.value = val.toFixed(2);
    });
  }

  ["subtotal_min","subtotal_max","estimated_hours_min","estimated_hours_max","final_price","deposit_required"].forEach((id) => {
    const el = document.getElementById(id);
    if (!el) return;
    el.addEventListener("input", () => { el.dataset.manual = "1"; });
  });

  document.getElementById("add-line")?.addEventListener("click", () => {
    const tr = document.createElement("tr");
    tr.className = "line-item-row";
    tr.innerHTML = `
      <td><input type="text" name="line_items[${lineIndex}][label]" value=""></td>
      <td><input type="text" name="line_items[${lineIndex}][category]" value="scope"></td>
      <td><input type="number" step="0.01" min="0" class="li-qty" name="line_items[${lineIndex}][quantity]" value="1"></td>
      <td><input type="number" step="0.01" min="0" class="li-price" name="line_items[${lineIndex}][unit_price]" value="0"></td>
      <td><input type="number" step="0.01" min="0" class="li-hmin" name="line_items[${lineIndex}][hours_min]" value="0"></td>
      <td><input type="number" step="0.01" min="0" class="li-hmax" name="line_items[${lineIndex}][hours_max]" value="0"></td>
      <td><input type="checkbox" name="line_items[${lineIndex}][required]" value="1" checked></td>
      <td><input type="checkbox" name="line_items[${lineIndex}][later_option]" value="1"></td>
      <td><button type="button" class="btn btn-secondary remove-line">×</button></td>`;
    table.appendChild(tr);
    lineIndex++;
    bindRow(tr);
    recalc();
  });

  document.getElementById("add-phase")?.addEventListener("click", () => {
    const wrap = document.getElementById("phases-list");
    const div = document.createElement("div");
    div.className = "form-row phase-row";
    div.innerHTML = `
      <label>Phase ${phaseIndex + 1}</label>
      <input type="text" name="phases[${phaseIndex}][title]" placeholder="Title">
      <input type="text" name="phases[${phaseIndex}][summary]" placeholder="Summary">
      <label><input type="checkbox" name="phases[${phaseIndex}][optional]" value="1"> Optional phase</label>`;
    wrap.appendChild(div);
    phaseIndex++;
  });

  function bindRow(row) {
    row.querySelectorAll("input").forEach((inp) => inp.addEventListener("input", recalc));
    row.querySelector(".remove-line")?.addEventListener("click", () => { row.remove(); recalc(); });
  }

  document.querySelectorAll(".line-item-row").forEach(bindRow);
  recalc();
})();
</script>';

op_layout_end();