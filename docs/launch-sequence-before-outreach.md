# Launch Sequence — Before First Outreach (Tanzenwald)

**Status:** BLOCKED until steps complete  
**Goal:** Email → work request form → scoped quote → secure access → repair

Do not send `leads/tanzenwald-outreach-email-draft.md` until every step below is done.

---

## Sequence

| Step | Task | Status |
|------|------|--------|
| 1 | **Buy domain** (KMX Media / local dev brand — TBD) | ☐ |
| 2 | **Deploy site** from `kmxmedia-com-site` (Astro build → host) | ☐ |
| 3 | **Build `/work-request` page** per [intake-form-spec.md](./intake-form-spec.md) | ☑ scaffold |
| 4 | **Wire form submission** (self-hosted PHP — see [work-request-api-setup.md](./work-request-api-setup.md)) | ☐ |
| 5 | **Set up secure access handoff** (phase 2 link — see form spec) | ☐ |
| 6 | **Smoke-test form** on mobile — no password fields on public form | ☐ |
| 7 | **Replace `WORK_REQUEST_URL`** in outreach draft with live URL | ☐ |
| 8 | **Send Tanzenwald email** | ☐ |

---

## Suggested URL paths

| Path | Purpose |
|------|---------|
| `/` | Local web + workflow intake (existing Astro homepage) |
| `/work-request` | **Tanzenwald outreach destination** — repair-focused, package cards |
| `/work-request/thanks` | Confirmation + what happens next |

**Placeholder until domain live:**  
`https://kmxmedia.com/work-request`

---

## Proof-site clarity on work-request page

The page must state:

> Kirk Kincaid operates Twin Cities Shuttle and provides local WordPress and website help through **KMX Media**. Examples: [twincitiesshuttle.com](https://twincitiesshuttle.com) · [kushbysaba.com](https://kushbysaba.com)

Otherwise a brewery ordering web work from a shuttle URL feels odd without context.

---

## Minimum viable for first send

- Domain + HTTPS live
- `/work-request` with sections 1–5 from form spec (packages, access comfort, authorization)
- Form emails Kirk on submit
- Secure credential link can be **manual v1** (password-protected page or one-time link Kirk sends in reply) if automated vault is not ready

---

## Related docs

- [intake-form-spec.md](./intake-form-spec.md)
- [work-request-page-copy.md](./work-request-page-copy.md)
- [offer-stack.md](./offer-stack.md)
- [../leads/tanzenwald-outreach-email-draft.md](../leads/tanzenwald-outreach-email-draft.md)