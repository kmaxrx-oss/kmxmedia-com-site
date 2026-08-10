# Homepage — Informed by Work Request Form

**Principle:** Build `/work-request` first. Let real client intake shape what the homepage needs to say — not the other way around.

---

## Two forms, two jobs

| Form | Route | Audience | Job |
|------|-------|----------|-----|
| **Work request** | `/work-request` | Clients with a problem or scoped project | Capture needs → quote → deliver |
| **Planning intake** | `/` (homepage) | Prospects exploring a new workflow website | Assemble a plan → conversation |

Do not merge. Homepage should **point to** `/work-request` for anyone ready to start work.

---

## What building work-request teaches the homepage

### 1. Package ladder clarity

Work-request forces packages A → H/I with plain prices. Homepage should show **max 3 entry offers**, not the full menu:

- Rescue ($125–$250)
- Findable ($650–$950)
- Customer path + booking ($1,200–$2,500)

Full stack (agents, apps, software) stays on `/work-request` and in footer CTA — not hero clutter.

### 2. Entry path language

Work-request entry paths (fix / grow / build / unsure) map to homepage hero variants:

| Entry path | Homepage emphasis |
|------------|-------------------|
| fix | “Website rescue” CTA → `/work-request` |
| grow | Outcome cards: calls, GBP, findability |
| build | Booking, agents, apps — link to work request |
| unsure | “Start a work request — we’ll recommend a path” |

### 3. Plain language wins

Form spec bans HTTPS/SSL jargon. Homepage must match:

- “Site won’t load” not “critical error”
- “Google Business Profile” spelled out once
- “Work request” not “contact for quote”

### 4. Trust block reuse

Work-request trust panel (no passwords, no auto-charge) should appear in homepage footer or near primary CTA — same words, smaller.

### 5. Proof sites

`twincitiesshuttle.com` + `kushbysaba.com` belong on both pages. Work-request proves operator context for non-shuttle verticals (breweries, restaurants).

### 6. Service area

Work-request collects `service_area` free text. Homepage intake uses checkbox regions. Align lists:

- Northfield
- Twin Cities metro
- Rochester
- Southern Minnesota

### 7. What homepage should NOT copy

| Work-request | Homepage — skip |
|--------------|-----------------|
| Authorization checkboxes | Not needed on planning form |
| Access comfort | Not needed until client stage |
| Payment preference | Quote stage only |
| Full 40+ need checkboxes | Use 8–10 outcome goals instead |
| Package radio cards | Use recommendation panel + softer packages |

### 8. Recommendation panel divergence

| Work-request panel | Homepage panel |
|--------------------|----------------|
| Suggested **package** + price range | Suggested **site structure** + workflow |
| Phased path: fix → book → agents → app | Sections: hero, services, booking flow, SEO |
| Upsell to next package rung | Upsell to “start work request” when ready |

Same UX pattern, different output. Reuse component structure, not logic.

---

## Homepage update sequence (after work-request live)

1. **Hero CTA** — primary button → `/work-request`; secondary keeps planning intake scroll
2. **Package strip** — 3 cards mirroring A/B/C from work-request data file
3. **Outcome cards** — keep; align labels with work-request need groups
4. **Footer** — rescue price + work request link (already partial)
5. **Schema / llms.txt** — service list matches work-request packages, not CO Springs research copy
6. **Retire** Colorado Springs geography in `index.astro` and `IntakeForm.astro` service areas

---

## Open questions (resolve after first 5 submissions)

- Which entry path converts most? → Hero default CTA
- Which needs cluster together? → Homepage outcome card order
- Do people select package G (diagnose) often? → Add “not sure” hero path
- Mobile drop-off section? → Shorten homepage intake, push to work-request earlier

---

## Related

- [work-request-build-plan.md](./work-request-build-plan.md)
- [intake-form-spec.md](./intake-form-spec.md) — “Relationship to homepage intake” section