# Homepage Text Mockup

**Source:** LOCAL_MIRROR `docs/kmxmedia-content-strategy-buckets.md` (and the original research report)

**Purpose:** Full visible homepage copy and content structure in reading order for the v1 deployable wedge. This is the writing/spec artifact. Code must implement from this.

**Obeys:** AGENTS.md (mockup-before-build law, content guardrails, .jpg convention, no primary "contact me for quote" path, etc.)

**Locked section order:**

1. Short hero
2. Intake form + live recommendation panel
3. Outcome cards
4. Local SEO / Google Business Profile section
5. Booking / quote / intake systems section
6. Workflow automation / AI follow-up section
7. Built for Colorado Springs service businesses section
8. Minimal FAQ
9. Final CTA
10. Footer / minimal contact support if needed

**Evidence labels used throughout:**

* LOCAL_MIRROR for content drawn directly from buckets/research
* CONTEXT_DERIVED for synthesis and organization
* BLOCKED for any claim that requires real KMX business facts (see final section)

---

## 1. Short hero

**Section purpose:** Anchor on local web design, immediately differentiate with workflow/intake/SEO/booking outcomes, and drive directly into the form. Short so form is the dominant first interaction.

**Target intent / keyword cluster:** Colorado Springs web design / website design (LOCAL_MIRROR primary targets). Buyer intent: hire a local partner for a site that actually generates calls, bookings, quotes, and follow-up.

**Visible heading:** Colorado Springs Web Design for Local Businesses That Need More Leads

**Visible subheading or intro copy:** We build workflow websites that turn visitors into calls, bookings, quote requests, and structured follow-up — not just pretty pages. The intake form below assembles the right plan for your business.

**Body copy:** Local businesses in Colorado Springs lose leads when their site only informs instead of works. Our sites are built to capture the actions that matter: phone calls from Google, self-booking, qualified quotes, and automated follow-up that reduces admin drag.

**CTA copy if any:** (none in hero; form is the CTA)

**Notes on what must not be said:** Do not lead with “AI agency Colorado Springs.” Do not use generic agency language like “award-winning design” or “dominate your market.” Do not promise fake results or use tourist imagery references. Keep focus on practical outcomes for service businesses (LOCAL_MIRROR guardrails).

---

## 2. Intake form + live recommendation panel

**Section purpose:** The core product experience. The form is the primary conversion object. The live recommendation panel shows the plan assembling in real time from answers. Form-first on mobile; side-by-side on desktop per locked rules in bucket 12.

**Target intent / keyword cluster:** All primary and secondary clusters (web design, local SEO/GBP, booking/intake forms, workflow automation, AI follow-up, lead generation, verticals). The form fields themselves carry the keyword intent.

**Visible heading:** Tell Us What Your Website Needs to Do

**Visible subheading or intro copy:** Answer a few questions about your Colorado Springs business. We’ll show you the recommended workflow website plan as you go.

**Body copy:** (The form itself is the body. See fields below.)

**CTA copy if any:** Submit intake (at end of form)

**Notes on what must not be said:** Do not make the form feel like a generic contact form. Do not hide the recommendation panel on mobile. The panel must update instantly.

### The exact first 10 fields (in order, LOCAL_MIRROR)

1. **What kind of business do you run?**  
   Label: What kind of business do you run?  
   Helper text: Choose the closest fit so we can recommend the right website workflow.  
   Input type: Select  
   Suggested answer options: contractor, home service, transportation or shuttle, restaurant or bar, med spa or clinic, repair service, consultant, local professional service, event vendor, other  
   Which recommendation-panel rule it feeds: Business type → recommended package and vertical-specific sections  
   Which content/schema/AI-readable bucket it supports: Industries served, service matrix, business type / recommended workflow matrix

2. **Where do you want to get customers from?**  
   Label: Where do you want to get customers from?  
   Helper text: Choose the places you actually serve.  
   Input type: Checkbox group  
   Suggested answer options: Colorado Springs, Monument, Falcon, Fountain, Manitou Springs, Black Forest, Security-Widefield, other nearby areas  
   Which recommendation-panel rule it feeds: Service area → Local SEO / GBP signals and areaServed  
   Which content/schema/AI-readable bucket it supports: Local SEO / GBP section, LocalBusiness vs Organization decision (BLOCKED until facts known)

3. **What should your website help your business do first?**  
   Label: What should your website help your business do first?  
   Helper text: Pick the outcomes that matter most.  
   Input type: Checkbox group  
   Suggested answer options: get more calls from Google, book appointments or rides, collect quote requests, collect customer intake, improve GBP conversion, follow up with leads faster, automate admin work, build a better local business website  
   Which recommendation-panel rule it feeds: Goals → package direction and core sections  
   Which content/schema/AI-readable bucket it supports: Outcome cards, service sections, customer action / website feature matrix

4. **Which actions should visitors be able to take on the site?**  
   Label: Which actions should visitors be able to take on the site?  
   Helper text: Select the actions you want customers to take without calling back and forth.  
   Input type: Checkbox group  
   Suggested answer options: call, text, request a quote, book an appointment, schedule a consultation, request a ride, fill out an intake form, ask a question  
   Which recommendation-panel rule it feeds: Actions → specific form features and CTAs in sections  
   Which content/schema/AI-readable bucket it supports: Booking / quote / intake systems, AI follow-up use-case table

5. **Do customers need online booking, scheduling, rides, or reservations?**  
   Label: Do customers need online booking, scheduling, rides, or reservations?  
   Helper text: Examples: appointment booking, consultation scheduling, shuttle or private ride booking, table or event reservation requests.  
   Input type: Radio group (Yes/No with examples)  
   Suggested answer options: Yes (with details), No  
   Which recommendation-panel rule it feeds: Booking needs → Booking add-on and section  
   Which content/schema/AI-readable bucket it supports: Booking/quote/intake use-case table

6. **Do you need quote requests or customer intake forms?**  
   Label: Do you need quote requests or customer intake forms?  
   Helper text: Examples: service estimate requests, job details, project scope, new-client intake, patient inquiry, event inquiry.  
   Input type: Radio group (Yes/No with examples)  
   Suggested answer options: Yes (with details), No  
   Which recommendation-panel rule it feeds: Intake/quote needs → Quote / intake add-on and section  
   Which content/schema/AI-readable bucket it supports: Booking/quote/intake use-case table, service matrix

7. **Do you want help getting more calls and clicks from Google or Google Business Profile?**  
   Label: Do you want help getting more calls and clicks from Google or Google Business Profile?  
   Helper text: Choose all that apply.  
   Input type: Checkbox group  
   Suggested answer options: local SEO, Google Business Profile setup, GBP conversion help, Google Maps visibility, better call and website-click conversion  
   Which recommendation-panel rule it feeds: Local SEO/GBP needs → Local SEO / GBP add-on  
   Which content/schema/AI-readable bucket it supports: Local SEO / GBP / website relationship explanation, LocalBusiness schema support

8. **What repetitive work should be automated?**  
   Label: What repetitive work should be automated?  
   Helper text: Common answers.  
   Input type: Checkbox group  
   Suggested answer options: lead follow-up, reminders, missed-call text-back, quote routing, intake routing, email responses, review requests, admin handoff  
   Which recommendation-panel rule it feeds: Automation needs → Workflow + AI add-ons  
   Which content/schema/AI-readable bucket it supports: Workflow automation / AI follow-up section, AI follow-up use-case table

9. **Would AI follow-up or AI customer intake help your business?**  
   Label: Would AI follow-up or AI customer intake help your business?  
   Helper text: Examples.  
   Input type: Radio group  
   Suggested answer options: Yes (with examples: instant first response, lead qualification, appointment reminders, FAQ replies, after-hours intake, handoff to a real person), No  
   Which recommendation-panel rule it feeds: AI needs → AI follow-up add-on  
   Which content/schema/AI-readable bucket it supports: Workflow automation / AI follow-up section, AI follow-up use-case table

10. **What is your current website or Google Business Profile, and what is not working?**  
    Label: What is your current website or Google Business Profile, and what is not working?  
    Helper text: Paste your site or GBP link if you have one, then tell us the main bottleneck.  
    Input type: Textarea  
    Suggested answer options: (free text + link)  
    Which recommendation-panel rule it feeds: Current state → tailored next-step and “before” language in sections  
    Which content/schema/AI-readable bucket it supports: Process transparency, proof/trust without testimonials, future case studies

**Live recommendation panel text states**

**Initial empty state:**  
Your plan so far  
Answer the questions above. We’ll show the recommended workflow website sections and add-ons as you go.  
(Progress meter: Step 1 of 10)

**After question 3 (goals):**  
Your plan so far  
Starter Workflow Homepage  
Core sections: Hero, Outcome cards, Industries  
Recommended for: [selected goals]  
(Progress meter: Step 3 of 10)

**After question 5 or 6 (booking/quote/intake):**  
Your plan so far  
Conversion Intake Site  
Core sections: Booking / quote / intake systems  
Add-ons: [Booking or Quote/intake if selected]  
(Progress meter: Step 5 or 6 of 10)

**After question 10 (current state):**  
Your plan so far  
Workflow + Local SEO Site  
Core sections: Local SEO / GBP, Workflow automation / AI follow-up  
Add-ons: [selected]  
Next step: This is the starting scope. We’ll refine the exact build together.  
(Progress meter: Step 10 of 10 — Ready to submit)

**Final required state (after submit, but shown in mockup as the assembled view):**  
Thank you. Your intake has been captured.  
Recommended plan: [full list of sections + add-ons]  
We will follow up within one business day to scope the build.

**Package direction:**  
Use only the allowed names. No invented exact prices (BLOCKED until rate research).  
Starter Workflow Homepage (base)  
Conversion Intake Site (adds booking/quote/intake)  
Workflow + Local SEO Site (adds local SEO/GBP + automation)

**Add-on chip language:**  
Local SEO / GBP  
Booking  
Quote / intake  
AI follow-up  
Domain setup (light)  
Ecommerce (flag only — separate rate class, BLOCKED details)

---

## 3. Outcome cards

**Section purpose:** Make the “what this actually does” visible and scannable. Reinforces the form goals.

**Target intent / keyword cluster:** Lead generation / conversion (LOCAL_MIRROR)

**Visible heading:** What Your Website Should Help Customers Do

**Visible subheading or intro copy:** Real outcomes for Colorado Springs service businesses.

**Body copy:** (cards)

**CTA copy if any:** (none; form drives)

**Notes on what must not be said:** Do not use vague “increase visibility.” Tie to specific actions.

Cards (concise copy, CONTEXT_DERIVED from buckets):

- **Get more calls from Google**  
  Local SEO signals + clear CTAs turn search views into phone calls.

- **Book appointments / rides / consultations**  
  Self-service scheduling replaces back-and-forth emails and calls.

- **Collect quote requests**  
  Structured forms capture job details up front so estimates are faster to qualify.

- **Capture customer intake**  
  Turn website inquiries into complete, routed information instead of vague messages.

- **Follow up faster**  
  Automated first response and reminders so fewer leads go cold.

- **Reduce admin work**  
  Connect forms, routing, and reminders so you spend less time on repetitive tasks.

---

## 4. Local SEO / Google Business Profile section

**Section purpose:** Differentiate by showing website + GBP as a system for discovery and conversion.

**Target intent / keyword cluster:** Colorado Springs local SEO / Google Business Profile optimization (LOCAL_MIRROR)

**Visible heading:** Get More Calls and Clicks from Google and Google Business Profile

**Visible subheading or intro copy:** Your website and Google Business Profile should work together.

**Body copy:** GBP helps people find you. The website closes the lead with the right information and easy actions. We build sites that support local search signals and turn profile views into calls, clicks, bookings, or quote requests.

**CTA copy if any:** (none)

**Notes on what must not be said:** Do not promise to “rank #1.” Focus on conversion of existing local traffic.

---

## 5. Booking / quote / intake systems section

**Section purpose:** Show the website as a working front desk.

**Target intent / keyword cluster:** Booking and intake forms (LOCAL_MIRROR)

**Visible heading:** Booking Forms, Quote Requests, and Customer Intake Built In

**Visible subheading or intro copy:** Let customers do the work that used to require phone calls.

**Body copy:** Add the exact flows your business needs: appointment or ride booking, structured quote requests, or detailed customer intake. The right details come in the first time, routed to the right person or system.

**CTA copy if any:** (none)

**Notes on what must not be said:** Do not make it sound like generic SaaS. Tie to the specific vertical actions from the form.

---

## 6. Workflow automation / AI follow-up section

**Section purpose:** Differentiator — the site handles repetitive work after the lead arrives.

**Target intent / keyword cluster:** Workflow automation / AI follow-up (LOCAL_MIRROR)

**Visible heading:** Workflow Automation and AI Follow-Up for Local Businesses

**Visible subheading or intro copy:** Connect the website to the rest of your operations.

**Body copy:** Missed-call text-backs. Lead routing. Automated reminders. First-response AI that qualifies and hands off to a real person. The goal is fewer leads slipping through the cracks and less time spent on repetitive admin.

**CTA copy if any:** (none)

**Notes on what must not be said:** Do not hype “AI agents that replace staff.” Frame as practical time-saver with human oversight (LOCAL_MIRROR).

---

## 7. Built for Colorado Springs service businesses section

**Section purpose:** Reassure vertical fit without over-specific claims.

**Target intent / keyword cluster:** Industries served / vertical opportunities (LOCAL_MIRROR)

**Visible heading:** Built for Colorado Springs Service Businesses

**Visible subheading or intro copy:** Different businesses need different conversion paths.

**Body copy:** We work with contractors and home services, transportation and private rides, med spas and clinics, repair services, consultants and professional services, event vendors, and restaurants or bars focused on reservations, catering, private events, or waitlist capture.

**CTA copy if any:** (none)

**Notes on what must not be said:** Do not list every possible business. Keep to the buckets list. No fake local proof.

---

## 8. Minimal FAQ

**Section purpose:** Objection handling and long-tail support. Not for rich results.

**Target intent / keyword cluster:** Long-tail search and objection handling (LOCAL_MIRROR)

**Visible heading:** Questions Colorado Springs Business Owners Usually Ask

**Visible subheading or intro copy:** (none or very short)

**Body copy:** (6–8 concise Q&A)

**CTA copy if any:** (none)

**Notes on what must not be said:** No “we guarantee first page” answers. Keep practical.

FAQ examples (from buckets, LOCAL_MIRROR + CONTEXT_DERIVED):

- What does a Colorado Springs small business website need to do today?  
  It should help drive calls, bookings, quotes, intake, and follow-up — not just look modern.

- Do I need both a website and a Google Business Profile?  
  Usually yes. GBP helps discovery. The website turns views into qualified actions.

- Can you build a website that books appointments or rides online?  
  Yes. We add the right scheduling or reservation flow for your customers.

- Can you add quote request or intake forms?  
  Yes. We capture the details that let you qualify leads faster.

- What is a workflow website?  
  A site built to collect the right customer details, route them, and follow up automatically.

- Can AI help with follow-up?  
  Yes — for first response, reminders, and qualification, with human oversight.

- Can you improve my current site instead of rebuilding?  
  Sometimes. If the structure supports the workflows you need, we can optimize. Otherwise a clean rebuild is usually faster.

- How long does this take?  
  (BLOCKED — depends on scope and your answers in the form. We scope after intake.)

---

## 9. Final CTA

**Section purpose:** Direct conversion after the full story. Reinforces that the form is the way in.

**Target intent / keyword cluster:** All high-intent clusters.

**Visible heading:** Ready to See Your Workflow Website Plan?

**Visible subheading or intro copy:** Answer the questions in the form above. We’ll show you the recommended sections and add-ons.

**Body copy:** (short) This is the starting scope. We refine the exact build in the next conversation.

**CTA copy if any:** Start Your Intake (links back to or emphasizes the form)

**Notes on what must not be said:** Do not create a competing “contact us for a quote” button as primary. Intake form remains the main path.

---

## 10. Footer / minimal contact support if needed

**Section purpose:** Basic support only. Do not create a second conversion path that competes with the form.

**Target intent / keyword cluster:** (none primary)

**Visible heading:** (minimal or none)

**Visible subheading or intro copy:** (none)

**Body copy:** KMX Media — Colorado Springs workflow websites. Questions after your intake? We follow up within one business day.

**CTA copy if any:** (none primary; intake is the path)

**Notes on what must not be said:** No big “Get a free quote” or “Contact us” as the dominant footer element. Keep intake dominant.

---

## BLOCKED facts before final homepage copy/schema

These must be verified before any final copy or schema is published (LOCAL_MIRROR from buckets + research):

* Public business address or explicit service-area-only status
* Primary phone and email for public use
* Google Business Profile URL and primary category (if exists)
* Real, verified service areas (Colorado Springs first; only add nearby areas that are actually served)
* Logo asset URL
* Public hours (if any)
* sameAs URLs (social, etc.)
* Real testimonials, case studies, or certifications that can be published with permission
* Pricing visibility or rate research (currently BLOCKED — no exact prices or “starting at” without data)
* In-person vs remote vs both model

Until these are known, all LocalBusiness vs Organization decisions, service area claims in schema, specific local proof in copy, and any pricing language remain BLOCKED.

---

**End of mockup.** This is the complete visible text and structure in reading order. Future code and media must implement from this document (and the other required specs when created). All content respects the locked order, evidence labels, and guardrails from AGENTS.md and the buckets document.