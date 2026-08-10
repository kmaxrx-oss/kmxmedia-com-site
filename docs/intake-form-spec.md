# Intake Form Spec — Local Business Service Request

**Page title:** KMX Media — Start a Work Request  
**Route:** `https://kmxmedia.com/work-request`  
**Status:** v3 — general local services intake (not prospect-specific)  
**Audience:** Twin Cities / Northfield local businesses — fixes, growth, builds, apps, booking, GBP  
**Implementation:** `src/data/work-request-form.ts` + `src/components/work-request/` — see [work-request-build-plan.md](./work-request-build-plan.md)

---

## What this form is for

A **general service intake** for local businesses. They may arrive with:

- a broken or embarrassing website,
- a site that doesn’t bring in calls or bookings,
- no real customer path (everything goes to the phone),
- a Google Business Profile that’s stale or wrong,
- a need for **booking**, **AI agents**, **web apps**, **custom software**, or **iPhone/Android apps**,
- or all of the above.

**The form’s job:** capture what they need now, show a clear path to the **full stack** when that’s appropriate, and never ask for passwords.

**Not for:** one brewery, one diagnosis, one technical failure type. Outreach emails are short; **this form carries the full menu.**

---

## Product flow

```
Outreach or referral → this intake → scoped quote → approval/payment →
secure access (phase 2) → delivery → ongoing care / GBP management (optional)
```

**Upsell law:** Every submission should leave room to grow from **fix → findable → bookable → app-enabled → managed**.

---

## Service ladder (how to think about packages)

Use this internally and to structure the form. Clients climb the ladder; they don’t have to buy the top rung day one.

| Rung | What they get | Plain outcome |
|------|----------------|---------------|
| **0 — Diagnose** | First look | “Here’s what’s wrong and what it costs to fix.” |
| **1 — Stabilize** | Website rescue | “My site works again on phones and Google.” |
| **2 — Findable** | SEO + structured facts + **GBP optimization** | “People can find us and see correct hours, services, photos.” |
| **3 — Actionable** | Customer path + **booking / intake** | “Customers can book, request, or order without phone tag.” |
| **3b — Automated** | **AI agent design** + workflow automation | “Routine follow-up, intake, and routing start fast — staff keep judgment.” |
| **4 — Built** | **Web apps**, **custom software**, **iOS / Android** | “Booking and business workflows in software, not just pages.” |
| **5 — Managed** | Hosting/support + **GBP management** | “Someone keeps the site and Google profile accurate every month.” |

**Booking is the hero upsell** between rung 3 and 4 — many local businesses still book by phone; surface **website booking with their rules** before custom software.

**AI agents** sit between booking and apps: follow-up, intake, routing, reminders — **designed for people** (human review and handoff), not “replace your staff.”

**Software development** = web apps, integrations, and mobile when off-the-shelf WordPress/plugins aren’t enough.

**GBP is not “part of SEO” in the form** — list it separately so owners who only care about Google Maps still see themselves.

---

## Full service menu (everything the form can route to)

### Website & WordPress

- Fix errors, broken pages, plugin mess, mobile problems
- Cleanup, updates, access discovery
- New pages, CTAs, menu/order/contact path cleanup
- Hosting migration or management on current host

### Findability

- Local SEO (site)
- Structured business facts / AI answer readiness (`llms.txt`, FAQ, schema)
- **Google Business Profile optimization** (one-time audit + setup)
- **Google Business Profile management** (ongoing posts, hours, photos, Q&A)

### Customer systems (high priority upsell)

- Booking / appointments / reservations (with **their** rules)
- Quote request / intake forms
- Email list + specials sends

### AI agents & workflow automation

- **AI agent design** for local businesses (intake, first response, qualification, routing)
- Follow-up and reminder flows
- FAQ and missing-info collection before human handoff
- Staff notification and summary handoff
- Tied to website, booking, email, or custom tools — always with human oversight

**Say:** “Agents that start the routine work — your team finishes it.”  
**Not:** “AI agency” or autonomous bots without handoff.

### Software development, web apps & mobile

- **Custom software development** (scoped business tools, dashboards, workflows)
- **Web applications** (booking portals, customer accounts, admin panels)
- **iPhone / iOS apps** (especially booking-first)
- **Android apps**
- API and tool **integrations** (Square, email, CRM, scheduling, etc.)

### Ongoing

- Website care plans ($49–$109/mo tiers)
- GBP management add-on ($79–$149/mo — see pricing section)

---

## Page framing

### H1

**Start a Work Request**

### Subhead

Tell us what’s going on with your website or what you want customers to be able to do. We’ll confirm scope and price before any work starts.

### About Kirk

> Kirk Kincaid — **KMX Media**, Northfield / Twin Cities. Local web development, WordPress repair, booking systems, **AI agent design**, **web apps**, **software development**, and **iOS/Android apps** for small businesses.
> Examples: [twincitiesshuttle.com](https://twincitiesshuttle.com) · [kushbysaba.com](https://kushbysaba.com)

### Trust block

> This is a **work request**, not an automatic charge.  
> **Do not** enter website or hosting passwords here.  
> After we agree on scope, we’ll use a **secure link** or a **temporary admin user** — never your main password.  
> We only do what’s in the approved request. Everything else gets quoted separately.

---

## Form structure overview

| Section | Purpose |
|---------|---------|
| 1 | Business info |
| 2 | **Why are you here?** (entry path — fix / grow / build / unsure) |
| 3 | **What do you need?** (checkboxes — full service map) |
| 4 | **Primary package** (radio cards — upsell ladder) |
| 5 | **Booking, apps, agents & build details** (conditional — upsell depth) |
| 6 | Access comfort |
| 7 | Domain / hosting notes (conditional) |
| 8 | Authorization |
| 9 | Payment preference |

**Mobile:** Sections 1–2–3 (top 3 goals)–4–6–8 visible first. Expand “All services & packages” for the rest.

---

## Section 1 — Business info

| Field | ID | Required |
|-------|-----|----------|
| Business name | `business_name` | yes |
| Contact name | `contact_name` | yes |
| Email | `work_email` | yes |
| Phone | `phone` | yes if urgent |
| Website URL | `website_url` | no — “Don’t have one yet” checkbox |
| No website yet | `no_website` | — |
| Google Business Profile link | `gbp_url` | no |
| Service area | `service_area` | no — e.g. Northfield, Twin Cities |
| Best contact method | `contact_method` | yes |
| Urgency | `urgency` | yes |

**Urgency:** Today / urgent · This week · Not urgent · Just exploring

---

## Section 2 — Why are you here? (entry path)

Single select — sets tone and default package suggestion.

| Label | Value | Default package hint |
|-------|-------|---------------------|
| Something’s wrong with our website | `fix` | A |
| We need more calls, visits, or bookings | `grow` | B or C |
| We want to build or upgrade (site, booking, app, agents, software) | `build` | D, E, I, or F |
| Not sure — help us figure it out | `unsure` | F |

**Helper:** Pick the closest fit. You can change the package below.

---

## Section 3 — What do you need help with?

Checkbox group — **select all that apply**. Drives upsell recommendations.

### Website problems (plain language — no technical jargon)

- [ ] Site shows an error or won’t load
- [ ] Site looks bad or broken on phones
- [ ] Site doesn’t show up well when people search
- [ ] Customers can’t tell how to call, book, or order
- [ ] WordPress / plugin / update issues
- [ ] We don’t know who manages our site or hosting
- [ ] We need a new website or redesign

### Google Business Profile

- [ ] **Google Business Profile optimization** (one-time setup / cleanup)
- [ ] **Google Business Profile ongoing management** (posts, hours, photos, Q&A)
- [ ] Our Google listing doesn’t match our website or hours

### Findability & AI visibility

- [ ] Local SEO improvements
- [ ] Help us show up when people ask AI assistants for local recommendations
- [ ] Structured hours, menu, services, and FAQs for search and AI

### AI agents & automation

- [ ] **AI agent design** — follow-up, intake, or customer questions
- [ ] Faster first response to leads and form submissions
- [ ] Collect missing info before staff follow up
- [ ] Route requests to the right person or department
- [ ] Reminders and handoff summaries for staff
- [ ] Reduce repetitive admin (not replace our people)

### Booking & customer flow (priority upsell)

- [ ] **Online booking or appointment requests**
- [ ] Reservations with our rules (hours, capacity, party size, etc.)
- [ ] Quote or intake forms
- [ ] Reduce phone calls for the same questions
- [ ] Email list / specials / events

### Software development, web apps & mobile

- [ ] **Custom software development** (business-specific tools or workflows)
- [ ] **Web application** (customer portal, booking, admin dashboard)
- [ ] **iPhone / iOS app**
- [ ] **Android app**
- [ ] Integrations with tools we already use (Square, email, scheduling, etc.)

### Ongoing

- [ ] Someone to maintain the website monthly
- [ ] Hosting migration or better hosting setup

**`problem_summary`** — textarea: “Describe what’s going on in your own words.”

---

## Section 4 — Primary package (radio cards)

One required selection. Prices are **ranges** — final quote after first look.

### A — Website rescue

**$125–$250**

Fix what’s broken so the site works for visitors again. Includes triage on WordPress and hosting where needed.

---

### B — Findable locally

**$650–$950**

Rescue (if needed) + **local SEO** + **Google Business Profile optimization** + structured business facts for search and AI.

**Includes:** site/GBP consistency, categories, hours, photos guidance, basic schema/FAQ layer.

---

### C — Customer path + booking

**$1,200–$2,500**

Everything in B + clear CTAs + **booking / request / intake flow** on the website with **your rules** (hours, limits, confirmations).

**Best for:** restaurants, salons, clinics, venues, services still doing everything by phone.

---

### D — Web app & custom software

**$2,500–$8,000+** (scoped)

**Software development** and **web applications** — booking systems, customer portals, staff dashboards, integrations, and workflow tools that go beyond a standard website.

---

### E — Mobile app (iOS and/or Android)

**$4,000–$12,000+** (scoped)

**iPhone/iOS** and/or **Android** app — especially **booking-first** apps tied to your workflow. Phased delivery; website + booking often come first.

**Helper:** Many clients start with C on the website, then add mobile in phase 2.

---

### F — Full local digital stack

**$3,500–$12,000+** (scoped)

End-to-end phases: working site, findability, GBP, booking, **AI agents** where useful, optional **web app / custom software** and/or **mobile apps**.

**Form copy:** “Fix the foundation, then build the full customer path — booking, automation, and software as needed.”

---

### I — AI agents & workflow automation

**$1,500–$4,500+** (scoped)

Design **AI agents for your business** — first response, intake, qualification, routing, reminders, FAQ support, and clean **human handoff**. Often pairs with website booking (C) or a web app (D).

**Includes:** agent rules, oversight boundaries, connection to forms/booking/email, testing, staff instructions.

**Say:** “Practical agents that start the work your team would rather not do manually.”

---

### G — Diagnose first

**$0–$125** first look

We review the site and Google presence, then send a fixed quote for the right package.

---

### H — Ongoing care only

**Website:** $49–$109/mo (see tiers in Section 7)  
**GBP management add-on:** **$79–$149/mo** (posts, hours, photos, Q&A monitoring)

For clients who mainly need maintenance, not a project.

---

## Section 5 — Booking, apps, agents & build details (conditional)

**Show when** any of: booking, agent, software/web app, iOS, Android checkboxes, or packages C, D, E, F, I selected.

### Booking & customer actions

| Field | Type |
|-------|------|
| What should customers be able to do? | checkboxes: book appointment · request reservation · request quote · pay deposit · view availability · other |
| Booking rules we must respect | textarea — hours, closed days, min/max party, capacity, phone-only policies |
| Currently using for orders/booking | text — Square, phone, spreadsheet, nothing, etc. |

### AI agents (if agent checkboxes or package I)

| Field | Type |
|-------|------|
| What should the agent help with? | checkboxes: first response · collect missing info · qualify leads · route to staff · send reminders · answer FAQs · summarize for handoff |
| Who reviews before customers get a final answer? | select: Always a person / Sometimes automated / Not sure |
| Where should it connect? | checkboxes: website forms · booking · email · text/SMS · other |

### Software & mobile

| Field | Type |
|-------|------|
| Build type needed | checkboxes: web app · custom software · iOS · Android · integration only |
| iOS app priority | select: Not needed / Nice to have / Primary goal |
| Android app priority | select: Not needed / Nice to have / Primary goal |
| Timeline | select: ASAP / This quarter / Planning ahead |

**Upsell copy on this section:**

> **Typical path:** fix the site → booking on the website → **AI agents** for follow-up and intake → **web or mobile app** when you need a dedicated product. We’ll phase it so you’re not buying everything at once.

---

## Section 6 — Access comfort

Always visible when website rescue or WordPress work is in scope.

- [ ] I can create a temporary WordPress admin user
- [ ] I want help creating a temporary admin user
- [ ] I can provide hosting access if needed
- [ ] I need Google Business Profile manager access instructions
- [ ] I want to talk first before sharing access
- [ ] Not sure

**GBP note:** For optimization/management, we’ll ask for **Google Business Profile manager** access after scope is approved — not your Google account password on this form.

---

## Section 7 — Hosting & ongoing tiers (conditional)

**Show when:** hosting, migration, or package H selected.

### Migration

**$250** one-time — move WordPress, DNS, smoke-test.

### Managed hosting + support

| Tier | Monthly | Requests/mo |
|------|---------|-------------|
| Basic | $79 | 2 |
| Standard | $89 | 3 |
| Plus | $109 | 5 |

### Current host only

**$49/mo** — 2 small website requests/mo.

### GBP management (ongoing)

**$79–$149/mo** — regular posts, hours/photo updates, Q&A monitoring, alignment with website. Pair with any care plan or standalone.

---

## Section 8 — Authorization

**All submissions:**

- [ ] I understand this starts a work request, not an automatic charge. KMX Media confirms scope and price before work begins.

**If A or G (rescue/diagnose):**

- [ ] I authorize limited access to diagnose and repair the website issues described above.

---

## Section 9 — Payment preference

- Pay before work begins
- Pay after agreed milestone
- Pay on completion (small jobs only)
- Card invoice
- Venmo / PayPal / Zelle
- Need invoice for records

**Default:** new clients pay before start for rescue; deposit for B–F.

---

## Upsell logic (operator / future live panel)

When checkboxes combine, suggest package upgrade in confirmation email:

| If they selected… | Suggest |
|-------------------|---------|
| Site broken + GBP mismatch | **B** |
| Booking + phone overload | **C** |
| Booking + iOS primary | **C now, E phase 2** |
| SEO + AI visibility + GBP | **B** |
| Follow-up / intake / admin pain | **I** or **C + I** |
| Web app + booking | **D** or **F** phased |
| Agents + booking + app | **F** phased: C → I → D/E |
| Custom software / integration | **D** |
| Everything checked | **F** with written phase plan |

**Completion report always includes:** “Optional next step: [one rung up].”

---

## Phase 2 — Secure access

Never on public form. After approval:

- Temporary WordPress admin, and/or
- Hosting panel, and/or
- Google Business Profile manager invite

---

## Post-submit

**Thanks page:** Request received — we’ll reply with package, price, and access steps. Urgent: 507-602-2949.

**Operator reply template:**

> **Your path:** [package]  
> **Scope:** [plain language]  
> **Price:** $[range or fixed]  
> **Phases:** [if F or apps — what’s now vs later]  
> **Access:** [WP / hosting / GBP manager]  
> **Payment:** [terms]

---

## Relationship to homepage intake

| Form | Role |
|------|------|
| **This spec** (`/work-request`) | **Clients** requesting work — fixes through full stack |
| **Homepage intake** | **Prospects** exploring a new workflow website plan |

Align messaging over time; do not merge forms yet.

---

## Language rules (public-facing)

| Use | Avoid |
|-----|-------|
| Site won’t load / shows an error | HTTPS, SSL, redirect, critical error |
| Google Business Profile | GBP jargon without spelling out first mention |
| Booking with your rules | OpenTable, generic “reservations platform” |
| iPhone app / Android app | “Mobile solution” |
| AI agent design | “AI agency,” autonomous bots, “replace staff” |
| Custom software / web app | Jargon without outcome |
| Work request | Send me your password |

---

## Implementation checklist

- [x] Entry path (Section 2) drives visible package hints — `work-request-recommendation.ts`
- [x] Section 5 expands for booking/app upsell — conditional in `WorkRequestForm.astro`
- [x] GBP fields and checkboxes distinct from SEO
- [x] `no_website` path skips URL
- [x] No password fields; phase 2 documented
- [x] Mobile-first layout; full service menu in Section 3
- [x] Self-hosted API — `public/api/work-request.php` (see [work-request-api-setup.md](./work-request-api-setup.md))
- [x] Operator estimator — `src/data/work-request-estimator.ts` (not shown to clients)
- [ ] Server `config.php` + live smoke test

---

## Open decisions

- [ ] Final GBP optimization vs management price pins
- [ ] App package floor pricing after first scoped project
- [ ] Form backend + secure access tool
- [ ] Live “recommended package” panel on page (v2)

---

## Related docs

- [work-request-page-copy.md](./work-request-page-copy.md) — update to match v3
- [offer-stack.md](./offer-stack.md) — align on next pass
- [pricing-rate-card.md](./pricing-rate-card.md)