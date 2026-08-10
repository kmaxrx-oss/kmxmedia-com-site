# Work Request Data Model

**Status:** Phase 1 — intake storage only  
**Path:** Quote builder (Phase 2) → client approval (Phase 3) → Stripe Checkout (Phase 4)

The `/work-request` form is a **quote-building intake**, not a payment form. Clients are not charged on submit.

---

## Flow

```
/work-request (intake) → work_request stored → operator email
    → manual quote (Phase 2)
    → client approval — first rung only (Phase 3)
    → stripe_checkout_session (Phase 4)
```

---

## `work_requests` (Phase 1 — implemented)

Stored as JSON files: `private/work-requests/{id}.json` on the server.

| Field | Type | Notes |
|-------|------|-------|
| `id` | string | 32-char hex |
| `created_at` | ISO8601 | UTC |
| `status` | enum | `new` → later: `quoted`, `approved`, `declined`, `completed` |
| `business_name` | string | required |
| `contact_name` | string | required |
| `email` | string | required |
| `phone` | string | optional |
| `website_url` | string | empty if `no_website` |
| `no_website` | bool | |
| `gbp_url` | string | optional |
| `service_area` | string | optional |
| `urgency` | string | |
| `contact_method` | string | |
| `entry_path` | string | `fix` / `grow` / `build` / `unsure` |
| `needs` | string[] | checkbox IDs |
| `primary_package` | string | A–I, G, H |
| `problem_summary` | string | |
| `booking_details` | object | customer_actions, booking_rules, current_tools |
| `ai_agent_details` | object | agent_help, agent_review, agent_connect |
| `app_software_details` | object | build_type, ios/android priority, timeline |
| `access_comfort` | string[] | |
| `hosting_interest` | object | migration, gbp_management flags |
| `payment_preference` | string | preference only — no charge |
| `recommendation` | object | client-facing package path |
| `estimator_snapshot` | object | **operator-facing** — hours, quote range, access notes |
| `source` | string | e.g. `starglassdigital.com/work-request` |

---

## `quotes` (Phase 2 — not built yet)

| Field | Type |
|-------|------|
| `id` | string |
| `work_request_id` | string |
| `package_code` | string |
| `line_items` | array |
| `estimated_hours_min` | number |
| `estimated_hours_max` | number |
| `subtotal_min` | number |
| `subtotal_max` | number |
| `final_price` | number |
| `deposit_required` | number |
| `phases` | array |
| `optional_later` | array |
| `notes` | string |
| `status` | `draft` / `sent` / `approved` / `checkout_created` / `paid` |

---

## `quote_line_items` (Phase 2)

| Field | Type |
|-------|------|
| `label` | string |
| `category` | string |
| `quantity` | number |
| `unit_price` | number |
| `hours_min` | number |
| `hours_max` | number |
| `required` | bool |
| `later_option` | bool |

---

## Estimator (operator-only)

Client sees package name + published price **range** in the suggestion panel.

Operator receives in `estimator_snapshot`:

- `estimated_hours_min` / `estimated_hours_max`
- `suggested_quote_min` / `suggested_quote_max`
- `suggested_quote_note`
- `likely_access[]`
- `optional_next_rung`
- `add_on_labels[]`

Logic: `src/data/work-request-estimator.ts`

**Do not** show raw hourly math on the public form.

---

## Related

- [work-request-api-setup.md](./work-request-api-setup.md)
- [work-request-build-plan.md](./work-request-build-plan.md)
- [intake-form-spec.md](./intake-form-spec.md)