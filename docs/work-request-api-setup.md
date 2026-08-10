# Work Request API — Self-Hosted (Hostinger PHP)

No third-party form processors. Submissions POST to your own endpoint, save as JSON, and email you a summary.

---

## Endpoint

```
POST /api/work-request.php
Content-Type: application/json
```

Deployed from `public/api/` → `public_html/api/` when you upload `dist/`.

---

## One-time server setup (each domain)

### 1. Upload build

```bash
npm run build
```

Upload all of `dist/` to `public_html/` via SFTP.

### 2. Create config

On the server, copy:

```
public_html/api/config.example.php  →  public_html/api/config.php
```

Edit `config.php`:

| Key | Star Glass | KMX Media |
|-----|------------|-----------|
| `brand` | Star Glass Digital | KMX Media |
| `notify_email` | your inbox | your inbox |
| `from_email` | noreply@starglassdigital.com | noreply@kmxmedia.com |
| `allowed_origins` | `https://starglassdigital.com` | `https://kmxmedia.com` |

`storage_dir` defaults to `{domain}/private/work-requests/` (above `public_html`). PHP creates it on first submit.

**Never commit `config.php`.**

### 3. Verify storage is not web-accessible

`private/work-requests/` must sit **outside** `public_html`. The default path does this automatically.

### 4. Smoke test

1. Open `https://yourdomain.com/work-request`
2. Submit a test request
3. Confirm `private/work-requests/{id}.json` exists on server
4. Confirm notification email arrives

---

## Local development

`npm run dev` does not run PHP. Options:

- Test API on staging/live Hostinger after upload
- Use local PHP: `php -S localhost:8080 -t dist` from project root after build

---

## What the client sees

- Trust panel: work request, not automatic charge
- Thanks page: “Request received — we’ll reply with scope, price, and access steps”
- No passwords, no Stripe, no deposit

---

## Phase 2+ (later)

- Private admin view to read `work_requests` and build `quotes`
- Client approval page (first rung only)
- Stripe Checkout attached to approved quote

See [work-request-data-model.md](./work-request-data-model.md).

---

## Rejected for this wedge

- Formspree and other third-party form processors
- Password fields on public form
- Automatic client-facing quotes from estimator
- Stripe on raw form submit