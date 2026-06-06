# Media Slot Alt Inventory

## 1. Source documents read

* `AGENTS.md` - LOCAL_MIRROR. Project law, placeholder image law, media guardrails, no hero image, no photos, no stock imagery, `.jpg` convention, and required media inventory fields.
* `.grok/config.toml` - LOCAL_MIRROR. Confirms active paths for the bucket doc, homepage mockup, schema spec, AI-readable grid spec, media slot inventory, and placeholder image.
* `docs/kmxmedia-content-strategy-buckets.md` - LOCAL_MIRROR. Provides bucket 12 responsive/media rules, bucket 13 Astro image handling, bucket 14 performance budget, and bucket 15 schema/content visibility rules.
* `docs/homepage-text-mockup-v2.md` - LOCAL_MIRROR. Provides accepted visible homepage section order, outcome cards, industries, service sections, FAQ, final CTA, and footer content.
* `docs/schema-content-spec.md` - LOCAL_MIRROR_SCHEMA. Provides schema/content visibility constraints and service meanings that media must not contradict.
* `docs/ai-readable-grid-spec.md` - LOCAL_MIRROR. Provides extraction-friendly service, business type, customer action, AI, booking/intake, FAQ, and recommendation-panel content structures.
* Local filesystem under `src/assets/` - LOCAL_MIRROR. Confirms expected media assets and placeholder file exist locally.

## 2. Media strategy verdict

* No hero image. LOCAL_MIRROR.
* No photos. LOCAL_MIRROR.
* No stock people. LOCAL_MIRROR.
* No generic AI/robot/brain/circuit imagery. LOCAL_MIRROR.
* No Colorado Springs tourist/mountain/skyline imagery. LOCAL_MIRROR.
* Media supports comprehension only. CONTEXT_DERIVED.
* The intake form and live recommendation panel remain visually dominant. LOCAL_MIRROR.
* Missing assets use `src/assets/placeholder.png` with documented intended meaning. LOCAL_MIRROR.
* Current expected assets all exist locally, including `src/assets/placeholder.png`; no slot needs placeholder backing at this inventory stage. LOCAL_MIRROR.

## 3. Asset existence inventory

| Asset path | Expected use | Exists locally? | File size if available | Ready / placeholder / blocked | Notes |
|---|---|---:|---:|---|---|
| `src/assets/icons/icon-calls.jpg` | Outcome card icon for calls | yes | 76672 bytes | ready | Use at small icon size only. LOCAL_MIRROR |
| `src/assets/icons/icon-booking.jpg` | Outcome card icon for booking | yes | 85356 bytes | ready | Use for booking/appointments/rides/consultations. LOCAL_MIRROR |
| `src/assets/icons/icon-quote-intake.jpg` | Quote request and customer intake icon | yes | 83225 bytes | ready | Shared by quote and intake unless future distinct intake asset is approved. LOCAL_MIRROR |
| `src/assets/icons/icon-local-seo.jpg` | Local SEO / GBP icon | yes | 96007 bytes | ready | Use for GBP/local search conversion concepts. LOCAL_MIRROR |
| `src/assets/icons/icon-automation.jpg` | Automation/admin reduction icon | yes | 98313 bytes | ready | Use for reduce-admin-work and automation contexts. LOCAL_MIRROR |
| `src/assets/icons/icon-ai-followup.jpg` | AI follow-up icon | yes | 86728 bytes | ready | Use for follow-up and AI workflow agents, not generic AI imagery. LOCAL_MIRROR |
| `src/assets/icons/icon-domain.jpg` | Domain setup flag icon | yes | 87303 bytes | ready | Use only for light domain readiness, not primary offer. LOCAL_MIRROR |
| `src/assets/icons/icon-ecommerce.jpg` | Ecommerce flag icon | yes | 88039 bytes | ready | Use only as future/separate-rate flag. LOCAL_MIRROR |
| `src/assets/icons/icon-contractors.jpg` | Contractors/home services industry icon | yes | 105235 bytes | ready | Use in industry cards. LOCAL_MIRROR |
| `src/assets/icons/icon-transportation.jpg` | Transportation/private rides industry icon | yes | 92309 bytes | ready | Vertical-option language only. LOCAL_MIRROR |
| `src/assets/icons/icon-medspa-clinic.jpg` | Med spas/clinics industry icon | yes | 110508 bytes | ready | Avoid medical outcome claims. LOCAL_MIRROR |
| `src/assets/icons/icon-repair-services.jpg` | Repair services industry icon | yes | 105991 bytes | ready | Avoid emergency/24-7 claims unless verified. LOCAL_MIRROR |
| `src/assets/icons/icon-professional-services.jpg` | Consultants/professional services industry icon | yes | 92636 bytes | ready | Also acceptable temporary restaurant/bar fallback if no restaurant-specific icon is created. CONTEXT_DERIVED |
| `src/assets/icons/icon-event-vendors.jpg` | Event vendors industry icon | yes | 123670 bytes | ready | Use for event inquiry workflow. LOCAL_MIRROR |
| `src/assets/diagrams/diagram-seo-gbp-loop.jpg` | Local SEO / GBP / website relationship diagram | yes | 90173 bytes | ready | Hide/collapse on mobile; show md+. LOCAL_MIRROR |
| `src/assets/diagrams/diagram-booking-intake-flow.jpg` | Booking / quote / intake flow diagram | yes | 67977 bytes | ready | Hide/collapse on mobile; show md+. LOCAL_MIRROR |
| `src/assets/diagrams/diagram-ai-followup-flow.jpg` | Workflow automation / AI follow-up flow diagram | yes | 90907 bytes | ready | Hide/collapse on mobile; show md+. LOCAL_MIRROR |
| `src/assets/diagrams/diagram-intake-to-plan-flow.jpg` | Intake to recommended plan flow diagram | yes | 93939 bytes | ready | Keep below/after form; never above form on mobile. LOCAL_MIRROR |
| `src/assets/textures/subtle-local-grid.jpg` | Subtle decorative background texture | yes | 35995 bytes | ready | CSS background at very low opacity; empty alt if not an img. LOCAL_MIRROR |
| `src/assets/placeholder.png` | Fallback for missing intended assets | yes | 6146 bytes | ready | Required fallback exists; use only if intended asset missing later. LOCAL_MIRROR |

## 4. Media slots by homepage section

### Hero

| Field | Value |
|---|---|
| section | Hero |
| slot name | Hero media |
| intended asset path | none |
| fallback placeholder path | none |
| intended visual meaning | No hero image; first viewport should prioritize Colorado Springs web design copy and intake path. |
| alt text | Not applicable |
| screen-reader description | No media slot. |
| decorative vs informational | Not applicable |
| mobile visibility rule | No hero image on mobile. |
| desktop visibility rule | No hero image on desktop. |
| lazy-loading rule | Not applicable |
| width/height or sizing guidance | Not applicable |
| ready / placeholder / blocked | ready: intentionally no media |

### Intake form + live recommendation panel

| Field | Value |
|---|---|
| section | Intake form + live recommendation panel |
| slot name | Intake to plan flow diagram |
| intended asset path | `src/assets/diagrams/diagram-intake-to-plan-flow.jpg` |
| fallback placeholder path | `src/assets/placeholder.png` |
| intended visual meaning | Shows the progression from intake answers to recommended plan to scoped build. |
| alt text | Diagram showing intake answers becoming a recommended website plan. |
| screen-reader description | The diagram explains that the visitor answers questions, sees a recommended plan, and uses that plan as the starting point for scoping. |
| decorative vs informational | informational |
| mobile visibility rule | Hidden or collapsed on mobile; replace with short text summary if needed. |
| desktop visibility rule | May appear at `md+`, below the form area or in a later process/support block; never above the form. |
| lazy-loading rule | Lazy-load if below initial viewport. |
| width/height or sizing guidance | Preserve aspect ratio; max 520-600px on lg/xl; do not place inside mobile panel. |
| ready / placeholder / blocked | ready |

### Outcome cards

| Field | Value |
|---|---|
| section | Outcome cards |
| slot name | Outcome card icons |
| intended asset path | `src/assets/icons/icon-calls.jpg`, `src/assets/icons/icon-booking.jpg`, `src/assets/icons/icon-quote-intake.jpg`, `src/assets/icons/icon-ai-followup.jpg`, `src/assets/icons/icon-automation.jpg` |
| fallback placeholder path | `src/assets/placeholder.png` |
| intended visual meaning | Small supportive icons for each customer action/outcome. |
| alt text | Usually empty alt because each icon is paired with visible card text. |
| screen-reader description | Screen reader gets the card heading and text; icons add visual scanning support only. |
| decorative vs informational | decorative when paired with visible labels |
| mobile visibility rule | Visible as small 28-32px icons if they do not compete with form completion. |
| desktop visibility rule | Visible as 40-48px icons max. |
| lazy-loading rule | Lazy-load if below fold; do not lazy-load if rendered in initial viewport after form only if it causes flash. |
| width/height or sizing guidance | Mobile 28-32px; md 32-40px; lg+ 40-48px; include width/height attributes. |
| ready / placeholder / blocked | ready |

### Local SEO / GBP section

| Field | Value |
|---|---|
| section | Local SEO / GBP section |
| slot name | Local SEO / GBP relationship diagram |
| intended asset path | `src/assets/diagrams/diagram-seo-gbp-loop.jpg` |
| fallback placeholder path | `src/assets/placeholder.png` |
| intended visual meaning | Shows GBP discovery feeding into a website action path for calls, clicks, bookings, quote requests, or intake. |
| alt text | Diagram showing Google Business Profile discovery leading to website conversion actions. |
| screen-reader description | Google Business Profile helps people find the business, while the website gives visitors a clear path to call, click, book, request a quote, or submit intake details. |
| decorative vs informational | informational |
| mobile visibility rule | Hidden/collapsed on mobile; provide equivalent text bullets. |
| desktop visibility rule | Visible at `md+` beside or below section copy. |
| lazy-loading rule | Lazy-load below fold. |
| width/height or sizing guidance | Preserve aspect ratio; max 520-600px lg/xl; do not distort. |
| ready / placeholder / blocked | ready |

### Booking / quote / intake systems section

| Field | Value |
|---|---|
| section | Booking / quote / intake systems section |
| slot name | Booking / quote / intake flow diagram |
| intended asset path | `src/assets/diagrams/diagram-booking-intake-flow.jpg` |
| fallback placeholder path | `src/assets/placeholder.png` |
| intended visual meaning | Shows customer action flowing into structured booking, quote, or intake details and cleaner business handoff. |
| alt text | Diagram showing a customer request becoming structured booking, quote, or intake details. |
| screen-reader description | The diagram explains that customers submit details through the site, those details are organized, and the business receives clearer information for follow-up. |
| decorative vs informational | informational |
| mobile visibility rule | Hidden/collapsed on mobile; use short text bullets as replacement. |
| desktop visibility rule | Visible at `md+` below or beside section text. |
| lazy-loading rule | Lazy-load below fold. |
| width/height or sizing guidance | Preserve aspect ratio; max 520-600px lg/xl. |
| ready / placeholder / blocked | ready |

### Workflow automation / AI follow-up section

| Field | Value |
|---|---|
| section | Workflow automation / AI follow-up section |
| slot name | AI follow-up flow diagram |
| intended asset path | `src/assets/diagrams/diagram-ai-followup-flow.jpg` |
| fallback placeholder path | `src/assets/placeholder.png` |
| intended visual meaning | Shows first response, missing-info collection, routing, reminders, and human handoff. |
| alt text | Diagram showing AI workflow follow-up from first response to human handoff. |
| screen-reader description | The diagram explains that AI workflow agents can help with first response, missing information, lead routing, reminders, customer intake, and handoff to a person. |
| decorative vs informational | informational |
| mobile visibility rule | Hidden/collapsed on mobile; provide text equivalent. |
| desktop visibility rule | Visible at `md+` below or beside section text. |
| lazy-loading rule | Lazy-load below fold. |
| width/height or sizing guidance | Preserve aspect ratio; max 520-600px lg/xl. |
| ready / placeholder / blocked | ready |

### Built for Colorado Springs service businesses section

| Field | Value |
|---|---|
| section | Built for Colorado Springs service businesses section |
| slot name | Industry card icons |
| intended asset path | `src/assets/icons/icon-contractors.jpg`, `src/assets/icons/icon-transportation.jpg`, `src/assets/icons/icon-medspa-clinic.jpg`, `src/assets/icons/icon-repair-services.jpg`, `src/assets/icons/icon-professional-services.jpg`, `src/assets/icons/icon-event-vendors.jpg` |
| fallback placeholder path | `src/assets/placeholder.png` |
| intended visual meaning | Small supportive icons for business type categories. |
| alt text | Usually empty alt because icons are paired with visible industry labels. |
| screen-reader description | Screen reader receives the visible industry card text; icons are visual orientation only. |
| decorative vs informational | decorative when paired with labels |
| mobile visibility rule | Visible as small icons only if they do not increase clutter; otherwise text-only cards are acceptable. |
| desktop visibility rule | Visible in industry grid at md+. |
| lazy-loading rule | Lazy-load if below fold. |
| width/height or sizing guidance | Mobile 28-32px; md 32-40px; lg+ 40-48px. |
| ready / placeholder / blocked | ready, except restaurant/bar icon decision is BLOCKED |

### FAQ

| Field | Value |
|---|---|
| section | FAQ |
| slot name | FAQ media |
| intended asset path | none |
| fallback placeholder path | none |
| intended visual meaning | FAQ remains text-first for clarity and schema alignment. |
| alt text | Not applicable |
| screen-reader description | No media slot. |
| decorative vs informational | Not applicable |
| mobile visibility rule | No media. |
| desktop visibility rule | No media. |
| lazy-loading rule | Not applicable |
| width/height or sizing guidance | Not applicable |
| ready / placeholder / blocked | ready: intentionally no media |

### Final CTA

| Field | Value |
|---|---|
| section | Final CTA |
| slot name | Final CTA media |
| intended asset path | none |
| fallback placeholder path | none |
| intended visual meaning | CTA should return visitors to the intake path without extra imagery. |
| alt text | Not applicable |
| screen-reader description | No media slot. |
| decorative vs informational | Not applicable |
| mobile visibility rule | No media. |
| desktop visibility rule | No media. |
| lazy-loading rule | Not applicable |
| width/height or sizing guidance | Not applicable |
| ready / placeholder / blocked | ready: intentionally no media |

### Footer

| Field | Value |
|---|---|
| section | Footer |
| slot name | Footer media |
| intended asset path | none |
| fallback placeholder path | none |
| intended visual meaning | Footer stays minimal and does not create a second conversion path. |
| alt text | Not applicable |
| screen-reader description | No media slot. |
| decorative vs informational | Not applicable |
| mobile visibility rule | No media. |
| desktop visibility rule | No media. |
| lazy-loading rule | Not applicable |
| width/height or sizing guidance | Not applicable |
| ready / placeholder / blocked | ready: intentionally no media |

## 5. Outcome card icon slots

| Outcome card | Intended asset path | Fallback placeholder path | Intended visual meaning | Alt text | Screen-reader description | Decorative vs informational | Mobile visibility rule | Desktop visibility rule | Lazy-loading rule | Sizing guidance | Ready / placeholder / blocked |
|---|---|---|---|---|---|---|---|---|---|---|---|
| Get more calls from Google | `src/assets/icons/icon-calls.jpg` | `src/assets/placeholder.png` | Phone/call action from local search. | empty alt when paired with heading | Visible card text explains the call outcome. | decorative | 28-32px icon allowed | 40-48px max | lazy-load if below fold | width/height attributes | ready |
| Book appointments / rides / consultations | `src/assets/icons/icon-booking.jpg` | `src/assets/placeholder.png` | Booking or scheduling action. | empty alt when paired with heading | Visible card text explains booking. | decorative | 28-32px icon allowed | 40-48px max | lazy-load if below fold | width/height attributes | ready |
| Collect quote requests | `src/assets/icons/icon-quote-intake.jpg` | `src/assets/placeholder.png` | Structured quote request details. | empty alt when paired with heading | Visible card text explains quote capture. | decorative | 28-32px icon allowed | 40-48px max | lazy-load if below fold | width/height attributes | ready |
| Capture customer intake | `src/assets/icons/icon-quote-intake.jpg` | `src/assets/placeholder.png` | Structured intake details. | empty alt when paired with heading | Visible card text explains intake capture. | decorative | 28-32px icon allowed | 40-48px max | lazy-load if below fold | width/height attributes | ready with shared asset; distinct icon decision BLOCKED |
| Follow up faster | `src/assets/icons/icon-ai-followup.jpg` | `src/assets/placeholder.png` | Faster first response and follow-up. | empty alt when paired with heading | Visible card text explains follow-up. | decorative | 28-32px icon allowed | 40-48px max | lazy-load if below fold | width/height attributes | ready |
| Reduce admin work | `src/assets/icons/icon-automation.jpg` | `src/assets/placeholder.png` | Automation reducing repetitive admin. | empty alt when paired with heading | Visible card text explains reduced admin work. | decorative | 28-32px icon allowed | 40-48px max | lazy-load if below fold | width/height attributes | ready |

## 6. Industry card icon slots

| Industry card | Intended asset path | Fallback placeholder path | Intended visual meaning | Alt text | Screen-reader description | Decorative vs informational | Mobile visibility rule | Desktop visibility rule | Lazy-loading rule | Sizing guidance | Ready / placeholder / blocked |
|---|---|---|---|---|---|---|---|---|---|---|---|
| contractors and home services | `src/assets/icons/icon-contractors.jpg` | `src/assets/placeholder.png` | Contractor/home service category. | empty alt when paired with label | Visible card text names category. | decorative | 28-32px allowed | 40-48px max | lazy-load below fold | width/height attributes | ready |
| transportation / shuttle / private rides | `src/assets/icons/icon-transportation.jpg` | `src/assets/placeholder.png` | Transportation/private ride category. | empty alt when paired with label | Visible card text names category; no case-study claim. | decorative | 28-32px allowed | 40-48px max | lazy-load below fold | width/height attributes | ready |
| med spas / clinics | `src/assets/icons/icon-medspa-clinic.jpg` | `src/assets/placeholder.png` | Med spa/clinic inquiry category. | empty alt when paired with label | Visible card text names category. | decorative | 28-32px allowed | 40-48px max | lazy-load below fold | width/height attributes | ready |
| repair services | `src/assets/icons/icon-repair-services.jpg` | `src/assets/placeholder.png` | Repair/service request category. | empty alt when paired with label | Visible card text names category. | decorative | 28-32px allowed | 40-48px max | lazy-load below fold | width/height attributes | ready |
| consultants / professional services | `src/assets/icons/icon-professional-services.jpg` | `src/assets/placeholder.png` | Professional services category. | empty alt when paired with label | Visible card text names category. | decorative | 28-32px allowed | 40-48px max | lazy-load below fold | width/height attributes | ready |
| event vendors | `src/assets/icons/icon-event-vendors.jpg` | `src/assets/placeholder.png` | Event vendor inquiry category. | empty alt when paired with label | Visible card text names category. | decorative | 28-32px allowed | 40-48px max | lazy-load below fold | width/height attributes | ready |
| restaurants / bars if represented | `src/assets/icons/icon-professional-services.jpg` or future restaurant-specific asset | `src/assets/placeholder.png` | Reservation, catering, private event, or waitlist category. | empty alt when paired with label | Visible card text names restaurant/bar use case. | decorative | 28-32px allowed if used | 40-48px max | lazy-load below fold | width/height attributes | BLOCKED: decide if existing icon is sufficient or a new asset is needed |

## 7. Diagram slots

| Diagram | Intended asset path | Fallback placeholder path | Intended visual meaning | Alt text | Screen-reader description | Decorative vs informational | Mobile visibility rule | Desktop visibility rule | Lazy-loading rule | Sizing guidance | Ready / placeholder / blocked |
|---|---|---|---|---|---|---|---|---|---|---|---|
| Local SEO / GBP relationship | `src/assets/diagrams/diagram-seo-gbp-loop.jpg` | `src/assets/placeholder.png` | GBP discovery connects to website conversion actions. | Diagram showing Google Business Profile discovery leading to website conversion actions. | GBP helps discovery; the website helps visitors call, click, book, request a quote, or submit intake. | informational | hidden/collapsed on mobile with text equivalent | visible at md+ | lazy-load below fold | preserve aspect ratio; max 520-600px lg/xl | ready |
| Booking / quote / intake flow | `src/assets/diagrams/diagram-booking-intake-flow.jpg` | `src/assets/placeholder.png` | Customer details become structured business handoff. | Diagram showing customer requests becoming booking, quote, or intake details. | Customers submit details through the site; the business receives clearer information for follow-up. | informational | hidden/collapsed on mobile with text equivalent | visible at md+ | lazy-load below fold | preserve aspect ratio; max 520-600px lg/xl | ready |
| Workflow automation / AI follow-up flow | `src/assets/diagrams/diagram-ai-followup-flow.jpg` | `src/assets/placeholder.png` | First response, missing info, routing, reminders, and handoff. | Diagram showing AI workflow follow-up from first response to human handoff. | AI workflow agents can support first response, missing information, lead routing, reminders, customer intake, and human handoff. | informational | hidden/collapsed on mobile with text equivalent | visible at md+ | lazy-load below fold | preserve aspect ratio; max 520-600px lg/xl | ready |
| Intake to recommended plan to scoped build | `src/assets/diagrams/diagram-intake-to-plan-flow.jpg` | `src/assets/placeholder.png` | Intake answers assemble a recommended plan and launch direction. | Diagram showing intake answers becoming a recommended website plan. | The visitor answers questions, sees a recommended plan, and uses that plan as the starting point for scoping. | informational | hidden/collapsed on mobile; never above the form | visible at md+ below/after form or in process support area | lazy-load below fold | preserve aspect ratio; max 520-600px lg/xl | ready |

Diagram rules from bucket 12:

* Diagrams are hidden/collapsed on mobile.
* Diagrams are visible at `md+`.
* Preserve aspect ratio.
* Lazy-load below fold.
* Never place diagrams above the form on mobile.
* Avoid loading hidden heavy media on mobile if possible.

## 8. Texture slot

| Field | Value |
|---|---|
| asset path | `src/assets/textures/subtle-local-grid.jpg` |
| fallback placeholder path | none; remove texture if unavailable |
| intended visual meaning | Subtle structure/background atmosphere that supports the systems/workflow feel without competing with form fields. |
| alt text | empty alt if ever inserted as image; preferred implementation is CSS background with no alt. |
| screen-reader description | Decorative texture; no screen-reader content. |
| decorative vs informational | decorative |
| mobile visibility rule | May appear on mobile at very low opacity. |
| desktop visibility rule | May appear on desktop at very low opacity. |
| lazy-loading rule | If CSS background, keep lightweight and avoid blocking form render; if image element is used, lazy-load below fold only. |
| width/height or sizing guidance | CSS background; remove from form section if contrast suffers. |
| ready / placeholder / blocked | ready |

Rules:

* Use at very low opacity.
* May appear on mobile.
* Remove from form section if contrast suffers.
* Decorative; empty alt if used as CSS background or non-content image.

## 9. Placeholder rules

* If an intended asset is missing, use `src/assets/placeholder.png`.
* Still write intended alt text and screen-reader description.
* Mark slot as placeholder-backed.
* Do not claim final media integration is complete while placeholder-backed.
* Do not use external URLs.
* Do not use random stock imagery.
* Do not invent final asset paths.
* `src/assets/placeholder.png` exists locally and is available as fallback. LOCAL_MIRROR.

## 10. Accessibility rules

* Informational images get concise alt text.
* Decorative images get empty alt or CSS background treatment.
* Icons paired with visible text can usually be decorative.
* Diagrams need meaningful alt text or nearby text equivalent.
* Never use images as the only source of important service meaning.
* Screen-reader descriptions should explain the purpose of diagrams, not visual decoration.
* If a diagram is hidden on mobile, the mobile text replacement must preserve the same service meaning.
* Focus order should remain form fields, recommendation panel, then rest of page; media must not create keyboard traps.

## 11. Performance/responsive rules

* Use width/height attributes.
* Use optimized JPGs.
* Lazy-load below-fold images.
* No hero image.
* No large media above fold on mobile.
* Do not load hidden heavy media on mobile if avoidable.
* Use responsive image handling for diagrams at `md+`.
* Icons should remain small: 28-32px mobile, 32-40px md, 40-48px lg+.
* Diagrams should preserve aspect ratio and max around 520-600px at lg/xl.
* Background texture must be lightweight and very low opacity.
* Do not let media reduce form contrast, form completion speed, or recommendation-panel legibility.

## 12. BLOCKED / follow-up media decisions

* No expected asset files are missing locally. LOCAL_MIRROR.
* Restaurant/bar category needs its own icon decision if represented as a card. BLOCKED.
* Outcome "customer intake" may need a distinct icon if `icon-quote-intake.jpg` is not visually clear enough. BLOCKED.
* Decide whether any diagrams should be omitted from v1 if too heavy or if they compete with the intake flow. BLOCKED.
* Decide whether diagrams use Astro image handling or plain `img` with explicit dimensions during implementation. BLOCKED.
* Decide whether the subtle texture is omitted from the form section if contrast suffers during visual testing. BLOCKED.
