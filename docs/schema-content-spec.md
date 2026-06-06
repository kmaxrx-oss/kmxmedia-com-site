# Schema Content Spec

## 1. Source documents read

* `AGENTS.md` - LOCAL_MIRROR. Project law, accepted mockup pointer, schema truthfulness rules, fake-claim prohibitions, evidence labels, and deployment guardrails.
* `.grok/config.toml` - LOCAL_MIRROR. Confirms active homepage text mockup path is `docs/homepage-text-mockup-v2.md` and schema spec target is `docs/schema-content-spec.md`.
* `docs/kmxmedia-content-strategy-buckets.md` - LOCAL_MIRROR. Provides content buckets, responsive visibility rules, schema/content visibility by breakpoint, LocalBusiness blocking status, and prohibited fake facts.
* `docs/homepage-text-mockup-v2.md` - LOCAL_MIRROR. Accepted visible homepage copy source for hero, intake form, live recommendation panel, outcome cards, local SEO / GBP, booking / quote / intake, workflow automation / AI agents, industries, FAQ, CTA, footer, and blocked facts.

## 2. Schema strategy verdict

* Use `Organization` as the safe default until LocalBusiness facts are verified. LOCAL_MIRROR_SCHEMA.
* Use `LocalBusiness` only if real publishable local address or eligible local business presence is verified. LOCAL_MIRROR_SCHEMA + BLOCKED.
* Use `WebSite`, `WebPage`, and `Service` where visible content supports them. LOCAL_MIRROR_SCHEMA.
* Use `ContactPoint` only when public phone/email are supplied. LOCAL_MIRROR_SCHEMA + BLOCKED.
* Use `FAQPage` only for visible FAQ structure and not as a rich-result tactic. LOCAL_MIRROR_SCHEMA.

Verdict: Organization-first graph with conditional LocalBusiness and ContactPoint. Service nodes are allowed only for services visibly represented in v2. FAQPage is optional and must mirror visible FAQ copy exactly enough to avoid hidden claims. CONTEXT_DERIVED.

## 3. Proposed schema graph

### Organization fallback node

Purpose: Identify KMX Media as the publisher/provider of the homepage and services while avoiding unverified local-business claims. LOCAL_MIRROR_SCHEMA.

Visible content support from v2 mockup: KMX Media is named in the hero/body and footer as building Colorado Springs workflow websites; the site describes web design, local SEO / GBP conversion, booking / quote / intake systems, workflow automation, and AI workflow agents. LOCAL_MIRROR.

Fields allowed now:

* `@type`: `Organization`
* `@id`: canonical organization id, once canonical homepage URL is confirmed or placeholder id is kept internal
* `name`: exact public business name only after confirmed, currently expected as KMX Media
* `url`: canonical homepage URL only after confirmed
* `description`: visible summary based on v2 copy

Fields BLOCKED:

* `logo`
* `email`
* `telephone`
* `address`
* `sameAs`
* `areaServed`
* `foundingDate`
* `founder`

Fields prohibited:

* `aggregateRating`
* fake `Review`
* fake awards/certifications
* fake `sameAs`
* fake address or service areas
* unverified LocalBusiness properties embedded on the Organization node

### LocalBusiness conditional node

Purpose: Represent a verified eligible local business presence only if KMX has publishable local facts. LOCAL_MIRROR_SCHEMA.

Visible content support from v2 mockup: v2 anchors on Colorado Springs web design and Colorado Springs service businesses, but it explicitly blocks service area schema and local proof until facts are verified. LOCAL_MIRROR.

Fields allowed now:

* None for final public schema until local presence is verified. BLOCKED.

Fields BLOCKED:

* `@type`: specific LocalBusiness subtype or `LocalBusiness`
* `name`
* `url`
* `address`
* `geo`
* `telephone`
* `email`
* `openingHoursSpecification`
* `areaServed`
* `priceRange`
* `sameAs`
* `hasMap`

Fields prohibited:

* fake address
* fake service areas
* fake hours
* fake GBP category
* fake ratings/reviews
* fake `priceRange`
* fake map or GBP URL

### WebSite node

Purpose: Describe the website as the publisher surface for the KMX Media homepage. LOCAL_MIRROR_SCHEMA.

Visible content support from v2 mockup: v2 defines a KMX Media homepage / workflow-intake site with a primary intake experience. LOCAL_MIRROR.

Fields allowed now:

* `@type`: `WebSite`
* `@id`: website id after canonical URL is confirmed, or placeholder omitted until implementation
* `name`: KMX Media
* `url`: canonical homepage URL once verified
* `publisher`: Organization fallback node

Fields BLOCKED:

* `potentialAction` for search if no site search exists
* `inLanguage` if multilingual/language facts are not finalized

Fields prohibited:

* fake search action
* fake language alternates
* fake publisher facts

### WebPage homepage node

Purpose: Describe the homepage as the page about Colorado Springs workflow websites, intake systems, local SEO / GBP conversion, booking / quote / intake, workflow automation, and AI workflow agents. LOCAL_MIRROR_SCHEMA.

Visible content support from v2 mockup: Hero, intake form, live recommendation panel, outcome cards, service sections, FAQ, final CTA, and footer all support the page topic. LOCAL_MIRROR.

Fields allowed now:

* `@type`: `WebPage`
* `@id`: homepage page id after canonical URL is confirmed, or generated relative to canonical URL later
* `url`: canonical homepage URL once verified
* `name`: visible page title derived from v2 hero/page intent
* `description`: visible summary derived from v2 copy
* `isPartOf`: WebSite node
* `about`: Organization fallback node or Service concepts visibly present on page

Fields BLOCKED:

* `primaryImageOfPage` unless a real page image/logo is selected and visible
* `datePublished` / `dateModified` unless implementation has trustworthy dates
* `breadcrumb` unless visible breadcrumb or intentional site structure exists

Fields prohibited:

* claims not visible on mobile and desktop
* fake dates
* fake images
* fake local proof

### Service nodes

Purpose: Represent the core services visibly described in v2. LOCAL_MIRROR_SCHEMA.

Visible content support from v2 mockup: Dedicated sections and intake fields support Colorado Springs web design / workflow websites, local SEO / GBP conversion, booking / quote / customer intake systems, and workflow automation / AI follow-up / AI workflow agents. LOCAL_MIRROR.

Fields allowed now:

* `@type`: `Service`
* `name`
* `description`
* `provider`: Organization fallback node
* `serviceType`, if derived from visible language

Fields BLOCKED:

* `areaServed`
* `offers`
* `priceRange`
* `termsOfService`
* `availableChannel`
* `hasOfferCatalog`

Fields prohibited:

* fake prices
* fake offers
* fake service areas
* fake availability
* fake guarantees
* fake rankings or outcomes

### ContactPoint conditional node

Purpose: Provide public contact metadata only when KMX supplies publishable phone/email/contact channel facts. LOCAL_MIRROR_SCHEMA.

Visible content support from v2 mockup: Contact capture fields appear in the intake, but public contact facts are explicitly blocked. The footer avoids unverified phone/email. LOCAL_MIRROR.

Fields allowed now:

* None for final public schema until public phone/email are supplied. BLOCKED.

Fields BLOCKED:

* `telephone`
* `email`
* `contactType`
* `areaServed`
* `availableLanguage`
* `hoursAvailable`

Fields prohibited:

* using form fields as public contact facts
* fake phone/email
* fake 24/7 availability
* fake language coverage

### FAQPage optional node

Purpose: Represent visible FAQ questions and answers if the final page includes them. LOCAL_MIRROR_SCHEMA.

Visible content support from v2 mockup: Section 8 contains visible FAQ questions about small business websites, GBP, booking, quote/intake, workflow websites, AI workflow agents, current-site optimization, and timeline. LOCAL_MIRROR.

Fields allowed now:

* `@type`: `FAQPage`
* `mainEntity`: visible `Question` / `acceptedAnswer` pairs from v2 FAQ

Fields BLOCKED:

* timeline specifics beyond "BLOCKED until scope is known"
* pricing answers
* guarantees
* service-area specifics

Fields prohibited:

* hidden FAQ answers
* unsupported claims
* fake rich-result bait
* ranking guarantees
* invented pricing or timelines

## 4. LocalBusiness vs Organization decision table

| Condition | Use Organization? | Use LocalBusiness? | Evidence required | Current status |
|---|---:|---:|---|---|
| KMX has a public brand/provider identity but local address/presence is not verified | Yes | No | Visible business name and homepage copy | Organization is safe default. LOCAL_MIRROR_SCHEMA |
| KMX has a real publishable local address eligible for LocalBusiness markup | Yes | Yes | Publishable address, canonical URL, phone/email if used, local business presence | BLOCKED |
| KMX is service-area-only with no publishable address but has eligible local presence | Yes | Conditional | Verified service-area-only business facts and schema-safe local presence decision | BLOCKED |
| KMX only wants broad organization/service provider representation | Yes | No | Confirmed public business name and canonical URL | Partially BLOCKED until exact public name and URL are confirmed |
| KMX wants areaServed in schema | Yes | Conditional | Real served areas approved for public use | BLOCKED |
| KMX wants phone/email contact metadata | Yes | Conditional | Public phone/email and contact purpose | BLOCKED |
| KMX wants ratings, reviews, testimonials, certifications, or case studies in schema | Yes | No unless verified and visible | Real publishable proof with permission and visible page support | BLOCKED; fake proof prohibited |

## 5. Service schema candidates

### Colorado Springs web design / workflow websites

Visible section support: Hero, intake form, outcome cards, final CTA, footer, and "Built for Colorado Springs Service Businesses" all support this service. LOCAL_MIRROR.

Suggested service name: Colorado Springs Workflow Website Design.

Short service description: Website design for Colorado Springs service businesses, focused on calls, bookings, quote requests, customer intake, follow-up, and cleaner lead details. LOCAL_MIRROR_SCHEMA.

Customer action enabled: Start a recommended website plan; call, book, request a quote, fill out intake, or ask a question depending on selected workflow. LOCAL_MIRROR.

Related intake fields: Fields 1, 2, 3, 4, 10, 13. LOCAL_MIRROR.

Schema fields allowed now:

* `@type`: `Service`
* `name`
* `description`
* `provider`
* `serviceType`: Web design / workflow website design

BLOCKED fields:

* `areaServed`
* `offers`
* `priceRange`
* `serviceOutput`
* guaranteed results

### Local SEO / Google Business Profile conversion

Visible section support: Field 7, recommendation panel Local SEO / GBP state, Local SEO / Google Business Profile section, outcome card "Get more calls from Google", FAQ about website + GBP. LOCAL_MIRROR.

Suggested service name: Local SEO and Google Business Profile Conversion.

Short service description: Website-side local conversion support that helps Google Business Profile visitors move into calls, clicks, bookings, quote requests, or intake details. LOCAL_MIRROR_SCHEMA.

Customer action enabled: Calls, clicks, booking requests, quote requests, and intake from local search / GBP traffic. LOCAL_MIRROR.

Related intake fields: Fields 2, 3, 4, 7, 10. LOCAL_MIRROR.

Schema fields allowed now:

* `@type`: `Service`
* `name`
* `description`
* `provider`
* `serviceType`: Local SEO / GBP conversion

BLOCKED fields:

* `areaServed`
* `hasMap`
* GBP URL/category
* rankings
* reviews/ratings
* offers/prices

### Booking / quote / customer intake systems

Visible section support: Fields 4, 5, 6, recommendation panel booking / quote / intake state, outcome cards, Booking / Quote / Intake Systems section, FAQ booking and quote/intake questions. LOCAL_MIRROR.

Suggested service name: Booking, Quote Request, and Customer Intake Systems.

Short service description: Website workflows for appointment scheduling, ride or reservation requests, quote requests, project details, new-client intake, event inquiries, confirmations, and handoff language. LOCAL_MIRROR_SCHEMA.

Customer action enabled: Book, schedule, request a quote, submit intake details, request a ride/reservation, or send complete inquiry details. LOCAL_MIRROR.

Related intake fields: Fields 3, 4, 5, 6, 10, 13, 17. LOCAL_MIRROR.

Schema fields allowed now:

* `@type`: `Service`
* `name`
* `description`
* `provider`
* `serviceType`: Booking / quote / customer intake systems

BLOCKED fields:

* `offers`
* `priceRange`
* appointment availability
* external booking URLs
* payment or checkout claims
* industry-specific claims not visible or verified

### Workflow automation / AI follow-up / AI workflow agents

Visible section support: Fields 8 and 9, recommendation panel automation / AI state, Workflow Automation / AI Follow-Up section, outcome card "Follow up faster", FAQ "What do AI workflow agents do?" LOCAL_MIRROR.

Suggested service name: Workflow Automation and AI Workflow Agents.

Short service description: Practical AI workflow agents and automations for first response, missing-info collection, lead qualification, routing, reminders, customer intake, and clean human handoff. LOCAL_MIRROR_SCHEMA.

Customer action enabled: Faster follow-up, better lead qualification, reminders, FAQ replies, intake summaries, and customer handoff after form submission or inquiry. LOCAL_MIRROR.

Related intake fields: Fields 3, 4, 8, 9, 10, 18. LOCAL_MIRROR.

Schema fields allowed now:

* `@type`: `Service`
* `name`
* `description`
* `provider`
* `serviceType`: Workflow automation / AI workflow agents

BLOCKED fields:

* guaranteed response times
* 24/7 claims
* pricing
* platform integrations not visible or verified
* autonomous-agent claims beyond human-supervised use cases

## 6. FAQ schema caveat

FAQ content is useful for clarity and machine-readable structure. LOCAL_MIRROR_SCHEMA.

Do not treat `FAQPage` as a rich-result tactic. CONTEXT_DERIVED.

Use only visible FAQ questions and answers from the final implemented page. LOCAL_MIRROR_SCHEMA.

Do not add hidden FAQ answers, ranking claims, pricing claims, fake timelines, fake service areas, unsupported proof, or unsupported business facts. LOCAL_MIRROR_SCHEMA.

If FAQ copy changes during implementation, schema must be reviewed against the final visible FAQ text before deployment. CONTEXT_DERIVED.

## 7. Content visibility requirements

* Schema must describe visible page content. LOCAL_MIRROR_SCHEMA.
* Any service represented in schema must have visible text on mobile and desktop. LOCAL_MIRROR_SCHEMA.
* Responsive hiding must not remove the only visible support for a schema claim. LOCAL_MIRROR_SCHEMA.
* Collapsed content is acceptable only if accessible and not deceptive. LOCAL_MIRROR_SCHEMA.
* Local SEO / GBP, booking / quote / intake, workflow automation / AI follow-up, and AI workflow agent meanings must remain visible enough on mobile to support matching schema. LOCAL_MIRROR_SCHEMA.
* Service schema should not rely only on live recommendation panel states because panel content may vary by form answers. CONTEXT_DERIVED.
* Form labels and helper text can support service meaning, but final schema should also have stable section text support. CONTEXT_DERIVED.

## 8. Explicit schema prohibitions

* no fake ratings
* no fake reviews
* no fake prices
* no fake offers
* no fake address
* no fake service areas
* no invented testimonials
* no invented case studies
* no invented certifications
* no 24/7 claims unless verified
* no multilingual claims unless verified
* no fake sameAs URLs
* no fake GBP URL, map URL, or category
* no ranking guarantees
* no hidden schema claims unsupported by visible copy

## 9. BLOCKED facts before final schema

* exact public business name
* canonical homepage URL
* publishable local address vs service-area-only
* primary phone/email
* real service areas
* GBP URL and category
* logo URL
* hours
* sameAs URLs
* testimonials/case studies/certifications
* pricing visibility
* in-person vs remote

All listed facts are BLOCKED until supplied and verified for public use. BLOCKED.

## 10. Implementation notes for later code

* JSON-LD should be generated from this spec, not improvised in components. CONTEXT_DERIVED.
* Schema should be reviewed again after final visible copy is implemented. CONTEXT_DERIVED.
* Missing facts should remain placeholders or be omitted, not invented. LOCAL_MIRROR_SCHEMA.
* Evidence labels must be preserved in the implementation report. LOCAL_MIRROR.
* `Organization`, `WebSite`, `WebPage`, and supported `Service` nodes are the likely first implementation shape. CONTEXT_DERIVED.
* `LocalBusiness` and `ContactPoint` should remain omitted until the blocked facts are resolved. LOCAL_MIRROR_SCHEMA + BLOCKED.
* `FAQPage` should be implemented only if the visible FAQ ships with matching questions and answers. LOCAL_MIRROR_SCHEMA.
* Schema must be checked against mobile and desktop visibility before any live deployment. LOCAL_MIRROR_SCHEMA.
