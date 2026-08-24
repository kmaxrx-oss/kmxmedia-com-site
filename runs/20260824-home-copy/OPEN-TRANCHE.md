# OPEN-TRANCHE

```yaml
id: 20260824-home-copy
status: GATE_PENDING
project: kmxmedia-com-site
implementer: AUTHORIZED
mode: patch-first
opened_by: Operator GO "proceed with dual lane skill for first page"
opened_at: 2026-08-24
net_new_shape: no
forge_ref: none
forge_waived: none
h3_ingest_required: yes
ingest_ref: runs/20260824-copy-ingest/unit-map.md CLOSED_PASS
```

## Goal (one sentence)

Apply ingested `copy/pages/home.md` onto the existing Astro homepage shell (`/`) with gray media overlays; do not build `/start/` brief UI or other launch routes.

## Authorized paths (only these)

- `src/pages/index.astro`
- `src/components/MediaPlaceholder.astro`
- `src/components/JsonLd.astro`
- `src/styles/kmx-tokens.css`
- `public/llms.txt`
- `runs/20260824-home-copy/`
- `lanes/README.md`

## Forbidden paths / actions

- Other `src/pages/*` routes (`/start/`, CS web design, etc.)
- PHP `/api` `/operator` `/approve` `/pay`
- Live SFTP deploy except Operator GO 2026-08-24 "published with link I can see" (homepage only)
- Inventing prices, reviews, case-study narratives
- Restoring retired MN copy
- Opening another tranche
- Context-to-Brief interactive brief UI (deferred)

## Done-when (objective checklist)

- [x] Homepage title, meta, H1, funnel thirds, four-door links, FAQ, and CTA match `copy/pages/home.md` (ASCII punctuation)
- [x] Three media slots use gray `MediaPlaceholder` with overlay text from copy
- [x] Primary CTA points to `/start/` (route may 404 until a later tranche)
- [x] `npx astro build` succeeds
- [x] No live deploy

## Verify commands

```text
npx astro build
```

## Killed concepts

- Homepage as sole Colorado Springs web-design SERP owner
- Combined booking+estimator owner page
- Old homepage intake form as the primary conversion on `/`

## State snapshot

- Tried: copy ingest CLOSED_PASS @ 03473fe
- Failed: none
- Next if blocked: stop; do not invent `/start/` brief

## Report path

`runs/20260824-home-copy/implementer-report.md`
