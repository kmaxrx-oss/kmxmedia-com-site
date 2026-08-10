# Company Direction — Local Web & Software + AI Integration

**Status:** CONTEXT_DERIVED — strategic draft for kmxmedia-com-site pivot  
**Last updated:** 2026-06-27  
**Evidence:** Operator context (Kirk Kincaid, Northfield MN); existing site is Colorado Springs placeholder from research phase

---

## What this company is

**KMX Media** — practical local web repair, workflow websites, and light software integration for small businesses in **Northfield, the Twin Cities, and southern Minnesota**.

Not an agency pitch. Not “AI agency” branding. The offer is:

1. **Fix what’s broken fast** ($250 urgent repairs)
2. **Build workflow websites** that capture calls, bookings, quotes, and intake
3. **Integrate AI where it reduces admin** — first response, follow-up, routing, summaries (human oversight always)
4. **Make local businesses findable** — SEO, Google Business Profile, and AI-readable site structure (`llms.txt`, schema, consistent facts)

**Credibility specimen:** https://twincitiesshuttle.com — Kirk’s own active booking/workflow site.

---

## Market positioning

| Dimension | Choice |
|-----------|--------|
| **Geography** | Northfield first, then Twin Cities metro, Rochester, southern MN |
| **Customer** | Restaurants, breweries, contractors, shuttles, clinics, professional services |
| **Anti-position** | Generic WordPress agencies, $5k brochure sites, black-box AI hype |
| **Tone** | Neighborly, practical, low-pressure — “I can take this off your plate” |

---

## Relationship to current Astro site

The repo at `kmxmedia-com-site` currently targets **Colorado Springs** (research-driven SERP work). This direction doc supersedes geography for **operator-led** work:

| Keep from CO build | Change for MN build |
|--------------------|---------------------|
| Intake form + live recommendation panel | Service areas → Northfield, Twin Cities, etc. |
| Workflow website framing | Hero/copy → Minnesota local |
| Outcome cards (calls, booking, quotes, AI follow-up) | Proof link → twincitiesshuttle.com |
| No fake testimonials | Pricing hints from `pricing-rate-card.md` |
| `.jpg` icon/diagram convention | Restaurant/brewery industry emphasis |

**Next implementation step:** `docs/homepage-text-mockup-v3-northfield.md` → then patch `index.astro` + `IntakeForm.astro` service areas.

---

## Service lines

### 1. Urgent repair (entry product)
- HTTP/HTTPS, SSL, plugin conflicts, white screens
- **$250 flat** — Tanzenwald is the template lead

### 2. Workflow websites
- Booking, quotes, intake, local SEO/GBP conversion
- Scoped via intake form; reference shuttle patterns internally

### 3. AI integration (practical, not hype)
- First-response drafts, missing-info collection, lead routing
- `llms.txt` + structured content so AI assistants cite correct hours/services
- Always with human review / handoff

### 4. Software touches
- Square, Mailchimp, form routing, light custom plugins
- Reservation systems with business rules (party minimums, hours, capacity)

### 5. Ongoing care (optional)
- $99–$129/mo — updates, backups, small edits
- Never the lead offer

---

## Site architecture (planned pages)

| Page | Purpose |
|------|---------|
| **Home** | Intake-first workflow website pitch (current `index.astro` shape) |
| **Services** (future) | Repair, workflow sites, SEO/AI, booking, email |
| **llms.txt** (future) | Machine-readable business facts for AI crawlers |
| **About** (future) | Kirk, local, Twin Cities Shuttle proof |

v1 ships **home only** per existing AGENTS.md scope.

---

## Lead pipeline

Prospects live in `/leads/`. First lead: **Tanzenwald Brewing Company** (Northfield brewery, HTTP broken, no reservations).

---

## Open decisions (BLOCKED until confirmed)

- [ ] Public business address / service area claims for schema
- [ ] Whether KMX Media and “AI integration company” share one brand or split later
- [ ] Domain: kmxmedia.com vs new domain
- [ ] When to retire Colorado Springs copy from production build

---

## Implementation order

1. ✅ Leads folder + Tanzenwald write-up
2. ✅ Pricing rate card
3. ✅ This direction doc
4. ✅ Homepage mockup v3 (Northfield / Twin Cities)
5. ✅ Patch homepage geography + pricing hints in recommendation panel
6. ✅ Add `public/llms.txt`
7. ✅ Build verify (`npm run build`)