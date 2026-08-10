# Work Request Build Plan

**Route:** `/work-request`  
**Product:** Quote-building intake — **not** payment, **not** third-party forms  
**Homepage intake:** Separate — prospects exploring a plan only

---

## Architecture

| Layer | Location |
|-------|----------|
| Spec | `docs/intake-form-spec.md` |
| Data model | `docs/work-request-data-model.md` |
| API setup | `docs/work-request-api-setup.md` |
| Field definitions | `src/data/work-request-form.ts` |
| Client recommendation | `src/data/work-request-recommendation.ts` |
| Operator estimator | `src/data/work-request-estimator.ts` |
| Submit client | `src/utils/work-request-submit.ts` |
| API (PHP) | `public/api/work-request.php` |
| UI | `src/components/work-request/` |

---

## Phases

### Phase 1 — Intake only — **CLOSED**

- [x] Full form sections 1–9
- [x] Client recommendation panel (package + phased path — no hourly math)
- [x] Operator `estimator_snapshot` on submit
- [x] Self-hosted `POST /api/work-request.php`
- [x] JSON storage `private/work-requests/{id}.json`
- [x] SMTP operator email (`webdev@` → `kmaxrx@outlook.com`) — **LIVE_VERIFIED**
- [x] Thanks page
- [x] Deploy + `config.php` on Hostinger

**Separate (not Phase 1):** Outlook reply-as `webdev@` — full IMAP/SMTP account in Outlook. See [hostinger-email-setup.md](./hostinger-email-setup.md).

**Rejected:** Formspree, passwords on form, Stripe on submit, client-facing auto-quotes

### Phase 2 — Quote builder (later)

Private admin: review `work_request`, adjust estimator, build `quote` + line items.

### Phase 3 — Client approval (later)

Client sees recommended path, phase 1 only, optional later rungs, approval checkbox.

### Phase 4 — Stripe (later)

`work_request → quote → approval → stripe_checkout_session → paid_work_order`

---

## Client trust copy

> This starts a work request, not an automatic charge.  
> We review your request and confirm scope and price before work begins.  
> Do not enter passwords here.

---

## Homepage

Do not merge forms. After 5–10 real submissions, align homepage using [homepage-informed-by-work-request.md](./homepage-informed-by-work-request.md).

---

## Verification

- [ ] `npm run build` — `dist/api/work-request.php` present
- [ ] Server `config.php` created
- [ ] Submit creates JSON + email
- [ ] Thanks page without `?mode=local` on production