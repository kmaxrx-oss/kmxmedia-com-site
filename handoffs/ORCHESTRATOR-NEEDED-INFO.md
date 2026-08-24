# Needed info for Orchestrator / Seam Knowledge Intake

Supplied from local and live KMX so Scout's BLOCKED platform unknowns are no longer empty. Evidence labels are honest.

Date: 2026-08-24
PROJECT_ROOT: `C:\Projects\kmxmedia-com-site`

## Case

Existing codebase mutation, not a greenfield host. Live site already exists at https://kmxmedia.com/ (`PUBLIC_LIVE`). Local tree is Astro static + PHP handlers. Previous marketing copy is RETIRED. Shells + tokens + forms remain.

## Platform (Scout SKI list)

| Seam | Truth | Evidence |
|---|---|---|
| Rendering | Astro 6 static build, Hostinger public_html, LiteSpeed lsphp 8.3, hCDN | LOCAL_MIRROR + PUBLIC_LIVE |
| Routes | `/`, `/work-request/`, `/work-request/thanks/` plus PHP `/api/`, `/operator/`, `/approve/`, `/pay/` | LOCAL_MIRROR |
| Intake today | Homepage `IntakeForm` + recommendation panel; `/work-request` `WorkRequestForm`; PHP `public/api/work-request.php` | LOCAL_MIRROR |
| Lead storage | JSON under `~/domains/kmxmedia.com/private/work-requests/` (outside docroot) | DB_VERIFIED |
| Visitor state | work-request local `sessionStorage` fallback (`mode=local`); no account/login on public site | LOCAL_MIRROR |
| AI interaction | No live AI chat or planner. Chat/AI CTA is architecture, not shipped | PUBLIC_LIVE |
| CRM / email | PHP SMTP via `api/config.php` (secrets on server); operator email webdev@starglassdigital.com | LOCAL_MIRROR |
| Analytics | Not inventoried | BLOCKED |
| Auth | Public anonymous. Operator PHP session on `/operator/` | LOCAL_MIRROR |
| CMS | None. Git + SFTP deploy of `dist/` | LOCAL_MIRROR |
| Hosting | Hostinger hPanel, user `u467937533`, apex HTTPS, www 301 to apex | PUBLIC_LIVE / DB_VERIFIED |

## Brand facts Orchestrator may use (do not invent more)

- Name: KMX Media
- Phone: 507-602-2949
- Email: webdev@starglassdigital.com
- Geography lock: Colorado Springs (INV-020)
- Proof sites named in current form data: twincitiesshuttle.com, kushbysaba.com (do not invent case-study narrative)
- Prices, packages, SLAs, hours, staff names: BLOCKED unless already in `docs/pricing-rate-card.md` and marked for public use after copy retirement review

## Conversion locks Orchestrator must obey

- Hub URL: `/start/` (INV-026)
- Every page Search-to-AI funnel: SERP match, pain, AI/brief handoff (INV-006, INV-026)
- Repair pages must not dump visitors into new-site intake first (INV-029, INV-043)
- Booking and intake stay two pages (INV-027)
- Homepage does not solely own `web design colorado springs` (INV-022, INV-023)
- Media: gray `MediaPlaceholder` backing overlay text; label the intended image/video; do not specify fake photography (INV-033)

## Dual-lane after copy lands

- **Ingest:** Orchestrator writes one markdown file per page under `copy/pages/`.
- **Implement:** CLOSED until `runs/<id>/OPEN-TRANCHE.md`. First implement proof is Context-to-Brief Spine on Home + four representative entrances (INV-075), not shipping all 20 URLs.
