# KMX Media Project Law

Project identity:
KMX Media homepage / workflow-intake site.

Site type:
Astro static / non-CMS website.

Primary goal:
A conversion-first Colorado Springs web design homepage where the intake form and live recommendation panel are the product experience.

Core selection pressure:
Fast, intent-oriented automatic intake with minimal babysitting, no generic “contact me for quote” path as the primary conversion behavior.

Primary source of truth:
Always read `docs/kmxmedia-content-strategy-buckets.md` before writing or changing:

* visible homepage copy
* schema / JSON-LD
* AI-readable content grids
* form labels and helper text
* recommendation panel logic or copy
* package/add-on wording
* responsive/mobile behavior
* media integration
* image alt text or screen-reader descriptions

## Mockup-before-build law

Before implementing homepage content or layout changes, create the relevant mockup/spec document first.

Required docs when relevant:

1. `docs/homepage-text-mockup.md`
   Must include the full page in reading order:

   * hero
   * intake form section
   * first 10 form fields
   * helper text
   * live recommendation panel text
   * outcome cards
   * local SEO / GBP section
   * booking / quote / intake section
   * workflow automation / AI section
   * industries section
   * FAQ
   * final CTA
   * footer contact/support text if any

2. `docs/schema-content-spec.md`
   Must include:

   * schema types proposed
   * visible content that supports each schema type
   * blocked real-world business facts
   * fields to avoid unless verified
   * fake claims prohibited
   * LocalBusiness vs Organization decision status
   * Service schema candidates
   * FAQPage caveat

3. `docs/ai-readable-grid-spec.md`
   Must include:

   * service matrix
   * business type / recommended workflow matrix
   * customer action / website feature matrix
   * local SEO / GBP / website relationship explanation
   * AI follow-up use-case table
   * booking / quote / intake use-case table
   * FAQ answer bank
   * workflow website definition block

4. `docs/media-slot-alt-inventory.md`
   Must include every image/media slot before media is integrated:

   * section
   * intended asset path
   * whether real asset exists
   * fallback placeholder path
   * alt text
   * screen-reader description
   * whether image is decorative or informational
   * mobile visibility rule
   * lazy-loading rule

If an image asset is not ready, use:
`src/assets/placeholder.png`

For every placeholder image, still write the intended screen-reader description and intended final visual description in `docs/media-slot-alt-inventory.md`. The placeholder should never leave us confused about what the image is supposed to represent.

If `src/assets/placeholder.png` does not exist, create a simple non-branded placeholder image only if necessary. Otherwise report BLOCKED and ask for authorization.

## Placeholder image law

When an intended image does not exist:

* use `src/assets/placeholder.png`
* do not invent a final asset path
* do not use random stock imagery
* do not use external image URLs
* write the intended alt text and screen-reader meaning before coding
* mark the media slot as placeholder-backed
* do not claim final media integration is complete

## Content/source separation law

Do not stitch final copy directly into components from memory.

Correct sequence:

1. Read bucket doc.
2. Write or update mockup/spec doc.
3. Ask for approval or proceed only if explicitly authorized.
4. Implement code from the accepted mockup/spec.
5. Verify build and responsive behavior.
6. Report exact files changed and evidence labels.

## Content guardrails

Do not:

* write generic agency copy
* lead with “AI agency Colorado Springs”
* keyword-stuff Colorado Springs
* make fake claims
* invent fake ratings, reviews, service areas, address, offers, prices, testimonials, certifications, or case studies
* create or prioritize “contact me for quote” as the main path
* hide key schema-supporting content on mobile
* let schema describe claims not visible on the page
* use normal contact as the primary conversion behavior

Do:

* anchor visible content in Colorado Springs web design / website design
* differentiate with local SEO / GBP conversion
* explain booking, quote, customer intake, workflow automation, and AI follow-up as business outcomes
* keep the intake form and live recommendation panel dominant
* preserve mobile-first form completion
* make the user feel the site plan is assembling from their answers
* preserve the `.jpg` media asset convention:

  * `src/assets/icons/*.jpg`
  * `src/assets/diagrams/*.jpg`
  * `src/assets/textures/subtle-local-grid.jpg`

## Responsive/media law

Use `docs/kmxmedia-content-strategy-buckets.md` bucket 12 for:

* breakpoint rules
* form/recommendation layout
* media visibility
* icon sizing
* diagram sizing
* texture use
* card grids
* content density
* touch/accessibility
* progress meter
* performance constraints
* mobile v1 acceptance checks

No hero image.
No photos.
No stock people.
No generic AI/robot/brain/circuit imagery.
No Colorado Springs tourist/mountain/skyline imagery.

## Security/deployment law

* Keep `.vscode/sftp.json` ignored, local-only, and never committed.
* Do not deploy, upload, or write to live without explicit authorization.
* Do not create deploy scripts unless explicitly authorized.
* Do not expose credentials.

## Evidence labeling

Any claim about local files, git, build, deployment, schema, live site, or server state must carry evidence labels:

* LOCAL_MIRROR
* LOCAL_MIRROR_SCHEMA
* PUBLIC_LIVE
* CONTEXT_DERIVED
* BLOCKED

## Before touching content checklist

Before writing or changing content:

1. Read `docs/kmxmedia-content-strategy-buckets.md`.
2. Identify which bucket governs the change.
3. Update the relevant mockup/spec doc.
4. Preserve intent-focused keyword strategy.
5. Preserve schema truthfulness.
6. Preserve mobile-first intake priority.
7. Preserve no-contact-form-as-primary-path rule.
8. Use placeholder image law for missing assets.
9. Report what changed and how it was verified.