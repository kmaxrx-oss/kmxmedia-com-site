# KMX Media Content Strategy Reference Buckets

**Source report:** LOCAL_MIRROR `C:\Users\kMaxR\Downloads\kmxmedia-research-report.md` (read June 2026 SERP and Google documentation findings).  
**Purpose:** Reusable, non-improvised buckets for homepage copy, schema, AI-readable structures, form fields, recommendation logic, differentiation, guardrails, open questions, and future media-query/responsive work.  
**Primary selection filter (from report):** Fast, intent-oriented automatic intake for Colorado Springs business owners needing web design + local SEO/GBP conversion + booking/quote/customer intake + workflow automation + AI follow-up.  
**Evidence rules used:** LOCAL_MIRROR = direct from the research report. CONTEXT_DERIVED = synthesis/organization of report content. BLOCKED = missing real KMX business facts or items not present in the report.

**Status:** Local reference only. No homepage copy written. No patches to index.astro or other files. No deployment. `.vscode/sftp.json` untouched.

**Important note for future media integration (CONTEXT_DERIVED):** The generated media assets are now converted to `.jpg`. Earlier planning may mention `.png` or `.svg`, but future image/media integration instructions should use `.jpg` asset paths unless Orchestrator says otherwise.

---

## 10. Pricing / Package Visibility Strategy

**Public-facing package names (CONTEXT_DERIVED)**  
Outcome-driven names that feel assembled from the intake answers (no specific names in research report):  
- Local Web Design Launch  
- Conversion + Local SEO / GBP  
- Booking & Intake System  
- Workflow + AI Automation Add-on  
- Full Workflow Website (design + SEO + booking/intake + automation)

**Price class language (CONTEXT_DERIVED)**  
Use ranges or "starting at" only if real rate research supports it. Otherwise describe value in terms of outcomes enabled (e.g., "designed to generate X qualified leads per month based on your traffic").

**What can be shown before rate research (BLOCKED until rate research provided)**  
High-level complexity tiers or "complexity signals" (e.g., number of forms, integrations, AI features). No dollar amounts or exact packages.

**What must remain unpriced until rate research is done (BLOCKED)**  
Any specific prices, "starting at $X", package totals, or monthly fees. All pricing is BLOCKED per open question in research: "Will KMX publish pricing or package starting points, or should all pricing remain off-page?"

**Add-on triggers (CONTEXT_DERIVED from form fields and previous buckets)**  
- Booking/scheduling needs → Booking Systems add-on (increases complexity)  
- Quote/intake needs → Quote/Intake Forms add-on  
- Local SEO/GBP selected → Local SEO / GBP Conversion add-on  
- Automation or AI selected → Workflow Automation + AI Follow-Up add-on (highest complexity add-on)  
- Domain setup / basic hosting → Light add-on only (minimal complexity)

**Page-count / complexity signals (CONTEXT_DERIVED)**  
- 1-3 key workflows (booking + quote + follow-up) = standard package  
- Multiple vertical-specific flows or heavy automation = higher tier  
- Ecommerce (if ever added) = separate rate class (not in current research)

**Ecommerce as separate rate class (CONTEXT_DERIVED)**  
If future needs include online sales, treat as distinct from core workflow website; requires separate rate research (not covered in current report).

**Booking/quote/intake as add-on complexity (LOCAL_MIRROR + CONTEXT_DERIVED)**  
These are core differentiators but add integration work (calendars, routing, data capture). Flag as complexity drivers in recommendation panel.

**AI follow-up as add-on complexity (LOCAL_MIRROR + CONTEXT_DERIVED)**  
Practical AI (first response, qualification, reminders) increases scope for prompt engineering, oversight rules, and testing. Present as valuable add-on, not core unless selected in form.

**Domain setup as light add-on only (CONTEXT_DERIVED)**  
Basic domain purchase, DNS, SSL – low effort, can be mentioned as included or small add-on without pricing until decided.

**Wording that avoids “contact me for quote” (LOCAL_MIRROR + CONTEXT_DERIVED)**  
"Your answers point to the following starting scope..."  
"This combination gives you these core sections plus the add-ons you selected."  
"The intake builds a clear plan we can refine together."  
"See the recommended structure below – we scope the exact build in the next conversation."

**Wording that avoids fake exact pricing (LOCAL_MIRROR + CONTEXT_DERIVED)**  
No invented numbers. Use "complexity-based" or "tailored to your selected workflows" language. All exact pricing is BLOCKED until rate research is complete and verified.

---

## 11. Proof / Trust Without Testimonials

**Process transparency (LOCAL_MIRROR + CONTEXT_DERIVED)**  
Make the intake → recommended plan → scoped build flow visible and reassuring. The form itself demonstrates the methodology: we ask the right questions first so the site is built around real outcomes.

**Intake → recommended plan → scoped build flow (CONTEXT_DERIVED)**  
Use the recommendation panel (from bucket 5) to show "Based on your answers, here is the plan assembling..." This builds trust by making the thinking visible without needing external proof yet.

**Local ownership / Colorado Springs relevance, only if truthful (LOCAL_MIRROR + BLOCKED)**  
Only use if KMX has verifiable local ties (address, service area, ownership). Currently BLOCKED until facts confirmed. Research emphasizes "Use only verifiable local proof."

**Twin Cities Shuttle as internal specimen leverage, but not public shuttle claims unless approved (CONTEXT_DERIVED from history and rules)**  
The reference plugin (ride-booking-standalone) provides proven patterns for intake, confirmation flows, and customer grids. Use internally for implementation inspiration only. Do not make public claims about "shuttle" or transportation work on the KMX site unless explicitly approved. (Previous instructions prohibit copying shuttle-specific content.)

**No fake reviews (LOCAL_MIRROR)**  
Research explicitly prohibits fake reviews, ratings, case studies. Do not invent any.

**No fake ratings (LOCAL_MIRROR)**  
No aggregateRating, star ratings, or invented review counts.

**No fake case studies (LOCAL_MIRROR)**  
No invented projects, results, or client names.

**No invented awards/certifications (LOCAL_MIRROR)**  
Only publish real ones when verified.

**Future placeholders for real case studies/testimonials/certifications (CONTEXT_DERIVED)**  
Add sections like:  
<!-- FUTURE: Real case study 1 -->  
<!-- FUTURE: Client testimonial with permission -->  
<!-- FUTURE: Certification logos -->  
These can be activated once real assets and approvals are available. Keep the v1 site credible by relying on process transparency and the live form instead.

---

# 12. Media Query / Responsive Strategy Buckets

Use these labels:

* LOCAL_MIRROR for instructions directly from this prompt/report.
* CONTEXT_DERIVED for any organization/synthesis you add.
* BLOCKED for missing assets, missing business facts, or unverified implementation details.

Add the following content in organized sub-buckets.

---

## 12.1 Breakpoint Model — Locked v1

Use this exact set. No custom or complex breakpoints in v1.

* Base mobile: `0–639px`

  * Single-column vertical flow.
  * Form-first.
  * All media minimal or collapsed.
  * Maximum content density reduction.

* `sm`: `640px+`

  * Minor spacing/typography scale-up.
  * Still primarily single-column.
  * Small grids or side-by-side elements may appear only if they do not compete with form.

* `md`: `768px+`

  * Two-column options become viable for non-form sections.
  * Form + recommendation panel can begin side-by-side testing.
  * Diagrams and larger cards may appear.

* `lg`: `1024px+`

  * Stable two-column layout for form + live recommendation panel.
  * Sticky behavior activates for panel only.
  * Full grids and diagrams.

* `xl`: `1280px+`

  * Minor max-width containment and extra breathing room.
  * No new layout changes; only refinement of spacing and card density.

Rule:
Changes at each breakpoint are strictly additive. Never introduce new elements or behaviors that only exist on larger screens without a clear mobile fallback.

---

## 12.2 Form + Live Recommendation Panel Layout by Breakpoint

Form is always the primary conversion object. The recommendation panel, titled “Your plan so far,” is secondary and must support form completion.

* Base mobile:

  * Form first, full width.
  * Recommendation panel appears as a single regular card after the core questions, after question 4 or 5.
  * Vertical stack only.
  * No side-by-side.

* `sm`:

  * Same as base.
  * Minor padding increase.
  * Panel card may gain subtle elevation but remains below form.

* `md`:

  * Form takes approximately 60% width.
  * Recommendation panel takes approximately 40% beside it, or below if the form is long.
  * Test both; form must remain dominant and easy to complete without horizontal scroll.

* `lg`:

  * Form left at approximately 55–60%.
  * Recommendation panel right and sticky.
  * Panel top-aligned to form start.
  * Panel never covers or overlaps form fields.

* `xl`:

  * Same proportions as `lg`.
  * Slightly increased max-width container and breathing room.
  * Panel remains sticky.

Rule:
The live panel must update instantly as form answers change at every breakpoint. On mobile, the update is visible in the card below the form.

---

## 12.3 Mobile Recommendation Panel Pattern — Locked v1

Use:
Regular card between form groups, after the core intake questions.

Rationale:
This is the safest v1 pattern. It has the lowest JS complexity, lowest risk of blocking form completion, and lowest risk of sticky overlap bugs on mobile. The user sees “Your plan so far” naturally as they progress without needing to tap, scroll past, or manage a drawer/accordion. On desktop it evolves into the sticky sidebar.

Avoid in v1:

* drawer
* bottom sheet
* sticky bottom card
* mobile sticky recommendation panel

Reason:
These add interaction cost and risk form obstruction.

---

## 12.4 Sticky Behavior

* Nothing sticky on mobile from base through `sm`.
* No sticky headers, CTAs, or panels that could cover form fields or reduce scrollable form area.
* Recommendation panel becomes sticky only at `lg+`.
* Sticky panel must stick to the top of the viewport aligned with the form start.
* Sticky panel must never cover or push form fields.
* Final CTA button inside the form can have a non-sticky “floating” treatment only if it appears after the last form group on mobile; otherwise keep it inline.
* Progress meter can be sticky-top on mobile only if it is extremely compact, such as a single thin bar, and does not reduce form field visibility.

---

## 12.5 Media Loading / Visibility Rules

Media must never compete with the form on any breakpoint.

Asset convention:
Although the original Orch 2 report references PNG assets, the actual generated media assets are now `.jpg`. Use `.jpg` paths going forward.

Always allowed on mobile:

* outcome card icons, small line-art JPGs
* industry card icons, small line-art JPGs
* subtle background texture if used
* very small flow diagram thumbnails only if under 100px wide and purely decorative

Hidden or collapsed on mobile, base through `sm`:

* all larger flow diagrams
* any mockup screenshots
* any multi-step process diagrams

Mobile replacement:

* short text bullets, or
* a low-priority “See how it works” expandable text block

`md+`:

* diagrams and mockups may appear according to sizing rules.
* Keep diagrams below or beside text.
* Never place large diagrams above the form.
* Never place large diagrams inside the live panel on mobile.

Background texture:

* Loads on all breakpoints.
* Must be extremely subtle.
* Must not reduce text/form contrast or legibility.

Forbidden:

* no hero image ever
* no photos
* no stock imagery
* no AI/robot/people/tourist imagery
* no Colorado Springs skyline/mountain/tourist imagery

---

## 12.6 Icon Sizing — JPG Assets

Approximate sizes:

* Mobile cards: `28–32px`
* Tablet cards (`md`): `32–40px`
* Desktop cards (`lg+`): `40–48px`

Rules:

* Icons must remain crisp at these sizes.
* Use width/height attributes.
* Use optimized JPG files.
* Never scale icons up beyond these ranges in v1.

Asset path convention:
`src/assets/icons/*.jpg`

Include these current paths:

* `src/assets/icons/icon-calls.jpg`
* `src/assets/icons/icon-booking.jpg`
* `src/assets/icons/icon-quote-intake.jpg`
* `src/assets/icons/icon-local-seo.jpg`
* `src/assets/icons/icon-automation.jpg`
* `src/assets/icons/icon-ai-followup.jpg`
* `src/assets/icons/icon-domain.jpg`
* `src/assets/icons/icon-ecommerce.jpg`
* `src/assets/icons/icon-contractors.jpg`
* `src/assets/icons/icon-transportation.jpg`
* `src/assets/icons/icon-medspa-clinic.jpg`
* `src/assets/icons/icon-repair-services.jpg`
* `src/assets/icons/icon-professional-services.jpg`
* `src/assets/icons/icon-event-vendors.jpg`
* `src/assets/diagrams/diagram-seo-gbp-loop.jpg`
* `src/assets/diagrams/diagram-booking-intake-flow.jpg`
* `src/assets/diagrams/diagram-ai-followup-flow.jpg`
* `src/assets/diagrams/diagram-intake-to-plan-flow.jpg`
* `src/assets/textures/subtle-local-grid.jpg`

Also include:

* icons are for cards/chips only
* diagrams are md+ only unless tiny thumbnail use is explicitly chosen
* texture can appear mobile if it remains low contrast

---

## 12.7 Diagram / Flow Sizing Rules — JPG Assets

Rules:

* Show diagrams starting at `md` and above only.
* Max width: `100%` of container on `md`.
* Max width on `lg/xl`: approximately `520–600px`.
* Preserve original aspect ratio.
* Never distort.
* Placement:

  * Always below section heading and primary text on `md`.
  * May move beside text on `lg+` if space allows without crowding.
* On mobile:

  * completely hidden, or
  * replaced by 2–3 short text bullets plus optional “Expand diagram” text link.

Asset path convention:
`src/assets/diagrams/*.jpg`

---

## 12.8 Background Texture

Asset:
`src/assets/textures/subtle-local-grid.jpg`

Rules:

* Use on body or major section containers at very low opacity.
* Appears on mobile.
* Do not hide below any breakpoint.
* Must never interfere with form field backgrounds, card surfaces, or text readability.
* If it reduces contrast on mobile, lower opacity further or remove from form-containing sections only.

---

## 12.9 Section Stacking — Locked Mobile Order

Mobile order is fixed and must never change:

1. Short hero, text + primary CTA into form
2. Intake form + live recommendation panel
3. Outcome cards
4. Local SEO / GBP section
5. Booking / quote / intake section
6. Workflow automation / AI section
7. Industries section
8. Minimal FAQ
9. Final CTA

`md+`:

* Outcome cards and industry cards may grid.
* SEO/GBP, booking, and automation sections may use two-column internal layouts.
* FAQ remains accordion or simple list.
* All sections remain in the same vertical order.

---

## 12.10 Card / Grid Responsive Behavior

* Outcome cards:

  * 1 column mobile
  * 2 columns `md`
  * 3 columns `lg`, max 3

* Industry cards:

  * 1 column mobile
  * 2 columns `md`
  * 3 columns `lg`, max 3

* Add-on chips:

  * horizontal scrollable row on mobile with snap if needed
  * wrap to multiple rows on `md+`

* Page/template cards inside recommendation panel:

  * 1 column mobile, stacked list
  * 2 columns on `md+` if space allows inside panel

Rule:
All grids must maintain consistent card heights, or use masonry only if it does not cause layout shift on live updates.

---

## 12.11 Content Density on Mobile

Shorten or hide on mobile:

* secondary descriptive paragraphs inside cards and sections
* long benefit lists
* industry descriptions

Mobile replacements:

* 1–2 sentence max cards
* icons + short labels
* collapsed “Key benefits” expandable if needed
* short tag + one-line value prop for industries

Must never hide on mobile:

* form fields
* form labels
* helper text
* validation messages
* primary value propositions in hero and outcome cards
* core CTA buttons
* live recommendation panel content, even if summarized
* progress meter
* section headings that orient the user

---

## 12.12 Touch Targets and Spacing

Mobile interactive elements:

* minimum tap target: `44 × 44px`
* text inputs/selects: minimum `48px` height
* checkbox/radio groups: at least `8–12px` between options
* buttons: minimum `48px` height, full-width or near full-width on mobile
* add-on chips: minimum `40px` height
* recommendation cards/panel items: minimum `44px` tap targets if interactive
* spacing between form groups/cards: minimum `24–32px` vertical on mobile

Rule:
Use generous internal spacing so live updates do not feel cramped.

---

## 12.13 Progress Meter

Mobile:

* compact top sticky bar is allowed only if extremely thin and non-obstructive.
* Should show current step count, e.g. “Step 3 of 7.”
* Must not push form content down or cover fields.

Desktop `lg+`:

* More prominent horizontal or vertical progress indicator beside or above the form.
* Can be larger and more visually detailed.

---

## 12.14 Mobile CTA Behavior

* Primary CTA appears inline at the end of the form on mobile.
* No persistent floating CTA bar on mobile in v1.
* Contextual CTAs inside the live recommendation panel are allowed only if clearly secondary and not distracting.
* Final form CTA should remain visible in natural flow.
* Do not auto-stick the final CTA unless future testing proves it increases completion without friction.

---

## 12.15 Accessibility Constraints

Preserve at all breakpoints:

* body/form text minimum `16px` on mobile
* headings clearly scaled, never smaller than `1.25×` base on mobile
* tap targets minimum `44 × 44px`
* visible focus states
* logical keyboard flow through form fields, then recommendation panel, then rest of page
* no keyboard traps
* respect `prefers-reduced-motion`
* color contrast minimum WCAG AA
* proper labels
* `aria-describedby` for helper text where useful
* screen-reader readable form and live panel
* live panel updates must not cause disorienting focus movement

---

## 12.16 Performance Constraints

* Optimize all JPG assets.
* Use width/height attributes to prevent layout shift.
* Lazy-load everything below initial viewport.
* Never lazy-load form or hero content.
* Above-the-fold media should be minimal.
* No diagrams or mockups above fold on mobile.
* Background texture must be lightweight.
* Do not load heavy repeating patterns.
* Avoid loading or decoding hidden media for current breakpoint.
* Use `srcset` and `sizes` for larger diagrams or mockups once shown at `md+`.
* Total above-the-fold weight target: keep low enough for form render/interactivity in under 2–3 seconds on typical mobile connections.

---

## 12.17 Mobile v1 Acceptance Check

Before deploy, all must be true:

* Form is fully visible and completable without horizontal scroll or zooming.
* Live recommendation panel updates instantly and is visible below the form without covering fields.
* All tap targets meet 44px minimum.
* No media reduces form legibility or contrast.
* Progress meter is visible but non-intrusive.
* Text is readable at default mobile font sizes.
* No overflow or cut-off.
* Keyboard navigation through form + panel works cleanly.
* Page loads and becomes interactive in under 3 seconds on typical 4G mobile.
* Core conversion path is obvious:
  answer questions → see live plan → submit.
* No layout shift when panel updates or images lazy-load below fold.
* All sections are reachable and readable in a single vertical scroll.

---

## 12.18 Locked Instruction

These rules are now locked for v1. Implementer must implement exactly this behavior before adding or styling media assets or wiring the live recommendation panel.

Any deviation requires explicit re-approval against these constraints.

---

# 13. Astro-Specific Image Handling Bucket

Purpose:
Translate the media/responsive rules into Astro-safe implementation guidance without writing implementation code yet.

Include:

* Current asset convention is `.jpg`.
* Icons live under `src/assets/icons/*.jpg`.
* Diagrams live under `src/assets/diagrams/*.jpg`.
* Texture lives at `src/assets/textures/subtle-local-grid.jpg`.
* Use explicit width/height attributes for all rendered images.
* Avoid layout shift from images.
* Prefer Astro image handling or optimized imports if already available in the project.
* If using plain `<img>`, enforce `loading="lazy"` for below-fold images and do not lazy-load hero/form content.
* Use `decoding="async"` for non-critical images where appropriate.
* Use `srcset`/`sizes` or Astro equivalent for larger diagrams at `md+`.
* Do not load hidden desktop diagrams on mobile if avoidable.
* Icons should be treated as small supportive media, not primary content.
* Background texture should be CSS-applied at very low opacity, not inserted as a content image.
* Never let images replace real text labels, headings, or form copy.
* Keep alt text short and functional.
* Decorative images should use empty alt text only when they add no information.

Evidence labels:

* LOCAL_MIRROR for bucket 12 instructions already in the file
* CONTEXT_DERIVED for Astro implementation guidance
* BLOCKED for anything requiring code inspection or build verification later

---

# 14. Performance Budget Bucket

Purpose:
Keep v1 fast enough that the intake form is immediately usable on mobile.

Include:

* Above-the-fold priority: short hero + form fields + essential CSS/JS.
* No hero image.
* No diagrams above fold on mobile.
* Recommendation panel JS must be lightweight and deterministic.
* Use small optimized JPG assets only.
* Lazy-load below-fold media.
* Prevent layout shift by declaring image dimensions.
* Avoid external media libraries.
* Avoid animation-heavy effects.
* Respect `prefers-reduced-motion`.
* Target: form visible and interactive within 2–3 seconds on typical mobile connections.
* Target: no visible layout shift when recommendation panel updates.
* Target: no horizontal scroll at 375px viewport.
* Any future deploy report should include build output, asset sizes if available, and local mobile preview notes.

Evidence labels:

* LOCAL_MIRROR where pulled from bucket 12
* CONTEXT_DERIVED for performance synthesis
* BLOCKED for actual measurements until implementation/build

---

# 15. Schema / Content Visibility by Breakpoint Bucket

Purpose:
Prevent responsive hiding/collapsing from breaking truthful schema, SEO, or AI-readable content.

Include:

* Schema must describe visible page content, not hidden claims.
* Mobile content density reductions must not hide core service meaning.
* If a service is represented in schema, there must be visible content for it on mobile and desktop.
* Collapsed FAQ/content is acceptable only if still accessible to users and not deceptive.
* Do not hide all Local SEO / GBP, Booking / quote / intake, Workflow automation / AI follow-up content on mobile.
* Section headings must remain visible on mobile.
* Form labels/helper text must remain visible and crawlable.
* The recommendation panel can summarize on mobile, but must still show package direction, pages/sections, triggered add-ons, and next step.
* Avoid desktop-only copy that contains important keyword/service meaning with no mobile equivalent.
* Do not mark up fake ratings, reviews, offers, prices, service areas, address, or testimonials.
* LocalBusiness vs Organization remains BLOCKED until KMX business facts are known.
* FAQPage remains optional and should not be treated as a rich-result tactic.
* AI-readable grids/tables should have mobile equivalents, even if stacked.

Evidence labels:

* LOCAL_MIRROR for research-report and bucket 12 rules
* CONTEXT_DERIVED for responsive/schema synthesis
* BLOCKED for missing KMX facts

---

# Future Implementation Contract Needed

The live recommendation panel should be split into its own implementation contract before patching, because it combines:

* form state
* recommendation rules
* package direction
* add-on triggers
* mobile placement
* desktop sticky behavior
* accessibility/live-region behavior
* schema/content guardrails

Do not create that contract yet. Just note that it is the next rightful implementation handoff after the reference buckets are accepted.

---

**1. File updated**  
Yes.

**2. Buckets 13–15 added**  
Yes. Added #13 Astro-Specific Image Handling Bucket, #14 Performance Budget Bucket, #15 Schema / Content Visibility by Breakpoint Bucket, each with the required content and evidence labels.

**3. Future implementation contract note added**  
Yes. Added exactly as specified after the new buckets.

**4. Any conflicts found**  
None. The new buckets were appended cleanly after the existing #12 without overlapping previous content or the top-level .jpg convention note. No contradictions with bucket 12 rules.

**5. Remaining open questions**  
- Full list from bucket 8 (KMX business facts for schema/copy).
- Rate research for bucket 10.
- Answers to the questions listed under the new buckets (other responsive buckets, Astro image handling, performance budget, schema-by-breakpoint, recommendation panel contract, other buckets before implementation).
- Authorization for the next step (recommendation panel contract or homepage patch).

**6. Recommended next action**  
Review the updated reference document. The mobile-first responsive strategy (bucket 12) plus the new Astro-specific, performance, and schema-visibility buckets (13-15) and the future contract note are now in place. Provide answers to any of the open questions if needed. Once ready, explicitly authorize either creating the separate implementation contract for the live recommendation panel or proceeding to the first homepage patch/implementation using the locked buckets. All work remains reference-only, LOCAL_ONLY.

Deployment status:

* LOCAL_ONLY: yes
* DEPLOYED_LIVE: no
* WRITTEN_DIRECTLY_ON_LIVE: no
* LIVE_VERIFIED: no
* VERIFICATION_METHOD: local reference-document update only

All instructions followed exactly. No homepage edits, no deployment, no sftp changes, no unauthorized commits. The reference document is now complete with buckets through 15 plus the note. Ready for your next direction.

