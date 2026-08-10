# Hostinger Email — webdev@starglassdigital.com

Phase 1 requires operator email delivery. PHP `mail()` alone is not trusted until messages land.

**CONTEXT_DERIVED (Hostinger/Kodee):** Forwarding `webdev@` to an external Outlook address is **not available** with current Hostinger mail tools.

**Workaround for “lands in Outlook”:** set `notify_email` => `kmaxrx@outlook.com` in `config.php`. The PHP handler sends **via SMTP from webdev@** directly to Outlook — no Hostinger forwarder required.

**Also add webdev@ in Outlook** (IMAP `imap.hostinger.com:993` SSL) to read copies in the webdev mailbox and reply as webdev.

---

## Target setup

| Role | Address |
|------|---------|
| Send work-request notifications | `webdev@starglassdigital.com` |
| Receive notifications | `webdev@starglassdigital.com` (read in Outlook via IMAP) |
| Reply to clients as | `webdev@starglassdigital.com` |

Kodee confirms: **webdev@starglassdigital.com exists** and **SMTP is enabled**.

---

## 1. Mailbox (done)

Address: `webdev@starglassdigital.com`  
Password: set in Hostinger hPanel — needed only in server `config.php` (never in git or chat).

---

## 2. Read webdev@ in Outlook (recommended — no forwarding)

Add the Hostinger mailbox as an account in Outlook:

| Setting | Value |
|---------|--------|
| **IMAP server** | `imap.hostinger.com` |
| **IMAP port** | `993` |
| **IMAP encryption** | SSL/TLS |
| **SMTP server** | `smtp.hostinger.com` |
| **SMTP port** | `465` (SSL) or `587` (TLS) |
| **Username** | `webdev@starglassdigital.com` |
| **Password** | Hostinger mailbox password |

**Outlook (desktop):** File → Add Account → enter `webdev@starglassdigital.com` → advanced/IMAP manual setup with values above.

**Outlook (web):** Settings → Mail → Sync email → add account (if your plan supports external IMAP).

Work-request emails set **Reply-To** to the client's email. Reply from the **webdev@** identity so clients see Star Glass Digital, not a personal address.

---

## 3. Server config (`public_html/api/config.php`)

Paste-ready templates: [config.php.paste-star-glass.md](./config.php.paste-star-glass.md) and KMX equivalent.

**Rules:**
- `notify_email` must be `webdev@starglassdigital.com` (not Outlook — forwarding unavailable)
- Set `smtp.password` to your Hostinger mailbox password on the server only
- Kodee cannot edit these files — use hPanel File Manager or SFTP

---

## 4. Verify delivery

1. Set `smtp.password` in both domain `config.php` files on server
2. Submit smoke test on `/work-request`
3. Check **webdev@** inbox in Outlook (or Hostinger webmail)
4. SFTP: read `/domains/starglassdigital.com/private/logs/mail.log` — look for `SMTP ok`

---

## KMX Media

Same mailbox for notifications is fine. Subject line includes `[KMX Media]` from `brand` in config.  
Path: `/domains/kmxmedia.com/public_html/api/config.php`

---

## Phase 1 gate — CLOSED

Form → JSON → SMTP → operator inbox is live. Smoke-test JSON files removed from server.

## Outlook reply-as (separate task)

Phase 1 does **not** require reply-as. For professional client replies:

1. Add `webdev@starglassdigital.com` as **full IMAP + SMTP** in Outlook (not receive-only).
2. Test: send `webdev@` → `kmaxrx@outlook.com`; confirm **From** shows webdev@.
3. On work-request Reply: **To** = client (Reply-To) · **From** = `webdev@`.
4. Try SMTP port **587 STARTTLS** only if **465 SSL** fails in Outlook — do not change website `config.php`.

Bear Paw likely uses a connected business mailbox the same way — match that setup, not personal-inbox delivery alone.