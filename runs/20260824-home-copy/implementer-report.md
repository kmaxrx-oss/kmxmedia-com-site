# Implementer report — 20260824-home-copy

```yaml
tranche: 20260824-home-copy
role: implementer
verdict: PASS_CANDIDATE
self_pass: no
deploy: DEPLOYED_LIVE
live_url: https://kmxmedia.com/
```

## What shipped (local)

- Homepage rebuilt from `copy/pages/home.md`
- Title, meta, H1, funnel copy, four-door links, FAQ, CTA to `/start/`
- Three gray media slots with overlay text from copy
- JSON-LD Organization + WebPage + FAQ from visible FAQ only
- `llms.txt` Colorado Springs + start + CS web-design URLs

## Verify

- `npx astro build` — pass
- SFTP `scripts/deploy-dist.mjs` — pass 2026-08-24
- Live GET `https://kmxmedia.com/` — 200, H1 "Websites That Help Customers Take the Next Step", Cache-Control max-age=0, last-modified 2026-08-24 04:55:06 UTC
- `/start/` and other launch doors not built this tranche (404 until later OPEN-TRANCHE)

## Not done (out of scope)

- `/start/` living brief UI (404 until later tranche)
- Other launch pages
- Live deploy
- Gate CLOSED_PASS (checker, not implementer)

## Notes

Door cards use copy H3 labels plus Continue links only. No invented case-study bodies on "Selected KMX Work".
