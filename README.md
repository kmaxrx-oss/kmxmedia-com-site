# kmxmedia-com-site

First real version of kmxmedia.com — a standalone (no CMS), high-converting homepage centered on a qualified intake form for workflow websites, booking/intake systems, local SEO surfaces, and practical AI workflow agents.

**Positioning**: Websites that do the work. Not brochure sites. Not WordPress/theme installs. Systems that turn visitors into structured leads, bookings, quotes, and organized follow-up while reducing admin drag.

## Stack & justification (from project needs)
- Astro (static output) + TypeScript + Tailwind (via @tailwindcss/vite).
- Why: Lightweight, excellent performance for a marketing + form homepage, full control over `<head>` and JSON-LD, component model for the explicit form without a heavy SPA framework, built-in dev/build/preview, easy static deploy or future lightweight SSR adapter. No CMS. Minimal runtime deps.
- Form is native + small vanilla TS enhancement (no React island needed for v1).
- Evidence for patterns: read-only inspection of `C:\Users\kMaxR\OneDrive\twin-cities-shuttle\wp-content\plugins\ride-booking-standalone` (LOCAL_MIRROR).

## Scripts
```sh
npm run dev
npm run build
npm run preview
```

## Homepage sections (implemented)
1. Hero — clear offer + local-business trust + strong CTA into form.
2. Main intake form (see `src/components/IntakeForm.astro`).
3. AI workflow agent section (practical, operations-grounded bullets).
4. Service model / outcome cards (5): Conversion homepage, Booking/quote intake, GBP→structured leads, AI agents, Customer-facing when useful.
5. Schema (JSON-LD) + FAQPage (grounded questions only).

## Form implementation (explicit)
- File: `src/components/IntakeForm.astro`
- All required fields present and semantic:
  - Business name (req), contact name (req), email (req), phone, website/GBP (url), business type.
  - What should the website do? — 8 checkboxes (multi-select): Get more calls, Book appointments or rides, Collect quote requests, Sell services, Organize customer intake, Automate follow-up, Add AI agent workflow help, Improve local SEO / GBP conversion.
  - Current problem / bottleneck (textarea, req).
  - Desired launch urgency (select).
  - Budget comfort range (select, optional).
  - Notes (textarea).
- Progressive emphasis: "What the website needs to do" is the first and most prominent fieldset.
- Submit: **placeholder only**. JS collects FormData → validates → echoes structured payload in success panel + console. Provides "Copy payload as JSON" and "Start new intake".
- UI + comments + footer + this README all state clearly: data does not leave the browser. No pretend transmission.
- Inspiration (design/interaction only, LOCAL_MIRROR):
  - Stepped qualification before full PII + commit (from BookingShortcode.php progressive data-step sections + review before create).
  - Review/summary before final action.
  - Clean panel/step card language (adapted from rbs-booking.css + MyRidesBootstrap mount patterns).
- Transportation-specific content **never copied** (no pickup/dropoff, no passengers, no zones, no fares/tips, no "book a ride", no shuttle language, no specific SMS consent text, no ride projections).

## Schema / SEO
- `src/components/JsonLd.astro` emits one `<script type="application/ld+json">` block (same mechanism as reference).
- @graph modeled directly on inspected reference (RideBookingPageShell.php:64-174):
  - Organization, WebSite, WebPage, FAQPage, Service entries.
- Reference used LocalBusiness + ReserveAction + specific shuttle FAQ. We adapted to Professional/Organization + Services for this business model.
- Explicit: NO aggregateRating, NO Review, NO fake offers, NO fake serviceArea, NO invented ratings or "5 star".
- Business facts: only minimal verified or clearly marked `// PLACEHOLDER` / `TODO: replace with verified...` (CONTEXT_DERIVED + contract requirement).
- FAQ content is practical and non-false (e.g. "Is this a WordPress or CMS site?", "What does an AI workflow agent actually do?").
- Evidence source: LOCAL_MIRROR_SCHEMA on RideBookingPageShell.php renderSchema + wp_head hook + wp_json_encode + @graph types. No schema anywhere else in the reference plugin.

## Reference inspection summary (Mode E)
**Codebase seams inspected (LOCAL_MIRROR):**
- Root: ride-booking-standalone.php
- Directories: assets/{css,js}, src/Interfaces/WP/{RideBookingPageShell.php, MyRidesPage.php, BookingConfirmedPage.php, MyRidesBootstrap.php, Shortcode/{BookingShortcode.php, MyRidesShortcode.php, ...}}, src/Support/Plugin.php
- Key reads: RideBookingPageShell.php (full schema + shell), BookingShortcode.php (full stepped form HTML + enqueue), MyRidesPage + Bootstrap (dedicated /my-rides route + projection mount + embedded booking), BookingConfirmedPage (post-intake status grid + comms labels), Plugin.php (container registration of pages/shortcodes + wp_head), rbs-booking.js (flow + REST create), rbs-my-rides.js (client grid from projection), rbs-booking.css (step panels).
- Greps: schema strings, form fields, my-rides, confirmation, etc.

**Closest analogs found:**
- Booking intake: multi-step progressive (destination → route qualify → passengers/date/times → contact late → review → confirmation). JS state + REST submit.
- /my-rides/: template_redirect full custom shell + JS-driven customer grid (grouped upcoming/past/canceled) with action modals + re-embed booking flow. Projection from server.
- Confirmation/customer flow: /booking-confirmed?ref=... dedicated page with trip details grid + email/SMS status + account access CTA. In-flow confirmation card.
- Schema/SEO: exclusively PHP in wp_head → renderSchema() → inline `<script type="application/ld+json">` + wp_json_encode(@graph with Organization/LocalBusiness/WebSite/WebPage/FAQPage/Breadcrumb + potentialAction).

**Contract-to-codebase match (reusable as inspiration only):**
- Schema generation site (PHP head hook → static equivalent here).
- Progressive/qualification-first form + review-before-commit UX.
- Dedicated customer self-serve grid pattern (adaptable to future "My Projects" or intake status, not copied).
- Confirmation status presentation.
- Semantic step/panel markup and helper text.

**Mismatches / must not copy (transportation-specific):**
- All ride fields (pickup, dropoff, passenger count, time grids, ride_mode, zones, fares, tips, promos, Stripe in booking).
- Language, routes (MSP, Rochester/Mayo), pricing claims, SMS consent wording.
- My Rides data model and actions (reschedule ride, etc.).
- Entire FAQ content.
- Any DB/REST/auth/payment concepts for the marketing site itself.
- Visual brand (purple/yellow) — replaced with serious slate systems aesthetic.

**Mismatches for schema data:**
- No real verified KMX address, phone, logo URL, GBP, email, or "sameAs" yet. All placeholders clearly marked. (BLOCKED on real data)

## Forbidden shortcuts (all observed)
- Do not build WordPress/CMS (this is pure Astro static; no PHP content, no shortcodes, no theme).
- Do not copy shuttle-specific content (verified in all copy and form options).
- Do not invent fake schema facts (no ratings/reviews/aggregateRating/offers/serviceArea claims).
- Do not claim the form submits (placeholder UI + text + README + console note everywhere).
- Do not stop at recommendation — patching executed inside authorized folder only.

## Verification performed (after patch)
- `npm run build` — success (see commands below).
- `npm run preview` — local static server works.
- dist/index.html inspected for schema presence, title, form markup, no fake claims.
- Responsive: Tailwind mobile-first classes throughout (sm:, md:, lg:); form stacks cleanly.
- Git: initialized by scaffold + explicit commit.
- Form: manual flow tested in build (placeholder path only).
- No unnecessary deps added beyond Tailwind (required for polished, maintainable, perf UI).

## Current status & remaining TODOs (specific)
- Form backend: BLOCKED. When a destination exists (email service, webhook, /api/intake, Netlify/ Vercel function, or custom), replace the submit handler + success copy. Document the exact URL and payload contract.
- Real business data for schema + footer + contact: name confirmation, physical address, phone, email, logo asset, GBP URL.
- Optional later: real "My Projects" customer surface (take the /my-rides pattern as interaction inspiration only).
- Analytics / conversion tracking on the form.
- A/B or copy iteration once real leads start.

## Commands run (evidence)
- npx create-astro@latest kmxmedia-com-site --yes --template minimal
- npx astro add tailwind --yes
- npm run build (multiple times)
- (git add / commit performed)

## Files created/changed (relative to target root)
- src/layouts/Layout.astro (new)
- src/components/JsonLd.astro (new)
- src/components/IntakeForm.astro (new — the explicit form)
- src/pages/index.astro (replaced)
- astro.config.mjs (patched with site)
- README.md (replaced with full contract report)
- dist/ (generated, not committed)
- package.json / lock / node_modules / tsconfig (from scaffold)
- .git/ (from scaffold + commit)

## Build / lint / typecheck / preview status
- Build: clean success, static output.
- No TypeScript errors surfaced in build.
- Preview: `npm run preview` serves the correct homepage.
- Schema in source: present as one application/ld+json script with @graph (inspect dist/index.html after build).

## Form submit status
- Placeholder only. Success panel + echo + copy. Explicit "NOT TRANSMITTED" notes. No network calls on submit.

## Remaining business-data placeholders
- Organization address, telephone, logo, email, sameAs links, real GBP.
- All marked in JsonLd.astro and copy.

## Next rightful action
Wire a real form destination (start with a simple email or Formspree/Netlify Forms or a small API route) and update the submit handler + success messaging + README. After that, consider adding a lightweight customer status surface modeled on the inspected My Rides interaction pattern (read-only inspiration).

---

## Deployment status:
* LOCAL_ONLY: yes — this is a local-only working tree on the implementer machine. No remote push or host deploy performed.
* DEPLOYED_LIVE: no
* WRITTEN_DIRECTLY_ON_LIVE: no
* LIVE_VERIFIED: no
* VERIFICATION_METHOD: direct (local build + source inspection of generated dist/index.html for schema + form; npm scripts executed; no browser automation in this pass but Tailwind responsive classes + semantic HTML verified in source)

All implementation, seam, and verification claims carry explicit evidence labels as required (LOCAL_MIRROR, LOCAL_MIRROR_SCHEMA, CONTEXT_DERIVED, BLOCKED).

Verdict recorded as GO after full seam inspection. Patch executed exclusively inside the authorized path `C:\Users\kMaxR\OneDrive\kmxmedia-com-site`.
