# Tanzenwald Brewing Company

**Status:** Active prospect  
**Last updated:** 2026-06-27  
**Website:** https://tanzenwald.com  
**Location:** 103 Water Street North, Northfield, MN 55057  
**Contact:** 507-366-2337 · erik@tanzenwald.com  

---

## Snapshot

Tanzenwald is a brewery and taproom in downtown Northfield. Their site works over **HTTPS** but **crashes over HTTP** with a WordPress critical error — so Google search links (which still use `http://`) fail on phones while manually typing `https://` works. They use **Square** for to-go ordering and have **no online reservation system**; a site popup says parties under 6 are walk-in only and groups of 6+ should call.

Strong local fit: Northfield business, WordPress stack Kirk can repair quickly, clear upsell path (SEO, AI local visibility, reservations, email).

---

## Site / tech notes

**Evidence:** PUBLIC_LIVE curl + browser checks, 2026-06-27

| URL | Result |
|-----|--------|
| `http://tanzenwald.com/` | **500** — “There has been a critical error on this website.” |
| `https://tanzenwald.com/` | **200** — loads normally |
| `http://www.tanzenwald.com/` | **500** |
| `https://www.tanzenwald.com/` | **200** |

**Root cause:** No server-level HTTP→HTTPS redirect. WordPress boots on plain HTTP and fatals before any redirect. Really Simple SSL is installed (`data-rsssl=1` on body) but is not preventing the error.

**Stack (from HTML):**
- WordPress 6.5.x
- Theme: Moose (Edge Themes) + child theme
- Plugins: Really Simple SSL, CleanTalk, Contact Form 7, WPBakery (js_composer), LayerSlider, Hustle (wordpress-popup)
- To-go: https://tanzenwaldbrewingcompany.square.site/
- Load balancer cookies (`X-Mapping-*`) — multiple backend nodes possible
- No HSTS header

**Mobile vs desktop:** Not a device issue. User-agent tests return identical HTTP failure / HTTPS success. The iOS symptom comes from Google opening `http://` links.

---

## Hours & reservation policy (from site)

**Tap room**
- Monday: closed
- Tue–Thu: 3pm–9pm
- Fri–Sat: 12pm–10pm
- Sun: 12pm–9pm

**Kitchen**
- Tue–Thu: 3pm–8pm
- Fri–Sat: 12pm–9pm
- Sun: 12pm–8pm

**Reservations (Hustle popup “Reservations”):**
> As of June 1st, we will no longer be taking reservations for parties with less than 6 guests. If you have a larger group with 6 or more guests please call the tap room at 507-366-2337.

**Current booking:** Phone only for 6+. No OpenTable, Resy, Amelia, or Square Appointments detected.

---

## Recommended offer

### Immediate fix — **$250** (flat)

- Force HTTP→HTTPS at Apache / hosting panel (before WordPress loads)
- Fix or remove Really Simple SSL cleanly
- Set / confirm `WP_HOME` and `WP_SITEURL` to HTTPS
- Smoke-test homepage, menu, contact, admin on mobile + desktop
- Nudge Google Search Console to HTTPS preferred domain (if they have access)

**Timeline:** Same day or next business day  
**Access:** Temporary WordPress admin, or hosting panel if `.htaccess` redirect requires it (explain why first)

---

### Add-ons

| Service | Price | What they get |
|---------|-------|----------------|
| **SEO foundation** | $450–$650 | Title/meta, local brewery/restaurant schema, GBP alignment, speed basics, internal links |
| **AI / local search readiness** | $200–$350 | `llms.txt`, structured facts, FAQ schema — show up when locals ask AI “where to drink in Northfield” |
| **SEO + AI bundle** | $550–$850 | Both above |
| **Reservation system** | $400–$750 | Online booking for **6+ only**, respects tap/kitchen hours, capacity limits, no double-booking |
| **Email marketing setup** | $300–$500 | List signup, specials template (pizza Wed, burger Fri/Sun), popup/footer integration |
| **Email marketing ongoing** | +$49/mo | Monthly send support (they provide content) |

---

### Hosting & ongoing support (if they need hosting)

**Option: migrate + managed hosting**

If they want off their current host (or don't have reliable hosting), Kirk migrates the full WordPress site and puts them on managed hosting with base monthly support included.

| Item | Price | Details |
|------|-------|---------|
| **Website migration** | **$250** one-time | Full WP move, SSL, DNS, smoke-test, email routing if needed |
| **Additional requests** | **$50 each** | Beyond monthly included; or $75/hr for larger work |
| **Email marketing add-on** | **+$49/mo** | Optional — newsletter/specials send support |

**Monthly tiers (migration $250 + pick one):**

| Tier | Price | Support requests/mo | Notes |
|------|-------|---------------------|-------|
| **Basic** | **$79/mo** | 2 | Stable site, rare updates |
| **Standard** | **$89/mo** | 3 | **Recommended for Tanzenwald** — specials, hours, popup tweaks |
| **Plus** | **$109/mo** | 5 | Frequent seasonal/menu/special changes |

All tiers: managed hosting, backups, WP/plugin updates, uptime monitoring.

**What counts as one support request (≤30 min each):**
- Hours or specials text update
- Popup or menu link change
- Small layout/copy fix
- Plugin or SSL issue triage
- Google Business Profile alignment tweak

**Not included in a support request (scoped separately):**
- New pages or major redesign
- Reservation system setup
- SEO/AI layer projects
- Email list build or automation setup

**If they keep their current host after the $250 fix:**
- **$49/mo** — management only (updates, backups, monitoring on their existing account)
- **2 support requests/month** included; $50 each additional

**Pitch line:**  
> "If you'd rather not deal with hosting, I'll migrate the site for $250. After that it's $79–$109 a month depending on how many small support requests you need — most taprooms land on $89 for three updates a month."

---

### Bundles

| Bundle | Price | Includes |
|--------|-------|------------|
| **A — Get it working** | $250 | HTTPS fix only |
| **B — Fix + findable** | $650–$850 | Fix + SEO + AI/local readiness |
| **C — Full taproom digital** | $1,200–$1,500 + optional $89/mo hosting | Fix + SEO/AI + reservations + email signup |
| **D — Fix + hosting** | $250 fix + $250 migration + $89/mo | HTTPS fix, migrate to managed hosting, 3 support requests/mo |

---

## Reservation system options

**Best fit: Square Appointments** ($400–$500 setup)  
Already on Square for to-go. Set min party size 6, block Mondays, cap covers per slot.

**Alternative: Amelia (WordPress plugin)** ($500–$750 setup)  
Keeps booking on their site; custom hour rules and party minimum.

**Skip:** OpenTable / Resy — fees and complexity for a taproom that rejects small-party reservations.

**Features to pitch:**
- Min 6 guests enforced at booking
- Separate tap vs kitchen hours
- Monday auto-blocked
- Max tables per time slot (overbooking prevention)
- Confirmation email with address/parking
- “Walk-ins welcome under 6” messaging
- Link from existing Hustle popup for 6+ groups

---

## Features they’d likely want (plain language)

1. Site loads correctly from Google on phones (**#1 pain**)
2. Online booking for groups of 6+ only
3. Easy specials/hours updates without breaking the site
4. Email list for weekly specials
5. Rank for “brewery Northfield” / “restaurant Northfield MN”
6. Show up in AI answers about Northfield dining/drinking
7. Square to-go linked clearly from homepage
8. Retire broken SSL plugin; server handles HTTPS

**How to describe “AI optimization” without jargon:**  
“I set up your site so Google and AI assistants can accurately answer questions about your hours, location, menu, and group bookings.”

---

## Access needed

| Task | Access |
|------|--------|
| HTTPS fix | WP admin **or** hosting/cPanel |
| SEO / AI layer | WP admin |
| Reservations | WP admin + Square account |
| Hosting move | Hosting + DNS |
| Email marketing | WP admin + Mailchimp (or similar) |

Lead with temporary WP admin. Only ask for hosting when you can name the exact reason.

---

## Talking points

- “Your site works on https but Google still sends people to http — that’s the critical error iPhone users see.”
- “This is a one-afternoon fix, not a rebuild.”
- “You’re already on Square — we can add group reservations without OpenTable fees.”
- “Northfield locals search on Google and increasingly ask ChatGPT/Perplexity — we make sure Tanzenwald is the answer.”
- Proof: https://twincitiesshuttle.com (Kirk’s own booking workflow site)

---

## Outreach log

| Date | Action | Result |
|------|--------|--------|
| 2026-06-27 | Site diagnosed (HTTP 500 / HTTPS 200) | Research complete |
| | | |