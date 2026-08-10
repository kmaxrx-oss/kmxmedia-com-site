/** Canonical field + package definitions for /work-request — source: docs/intake-form-spec.md v3 */

export const BRAND = {
  name: 'KMX Media',
  eyebrow: 'KMX Media · Northfield / Twin Cities',
  email: 'webdev@starglassdigital.com',
  phone: '507-602-2949',
  phoneTel: 'tel:5076022949',
  smsTel: 'sms:5076022949',
  contact: {
    phoneLabel: 'Phone',
    smsLabel: 'Text',
    emailLabel: 'Email',
  },
  siteSource: 'kmxmedia.com/work-request',
  theme: 'kmx' as const,
  proofSites: [
    { label: 'twincitiesshuttle.com', href: 'https://twincitiesshuttle.com' },
    { label: 'kushbysaba.com', href: 'https://kushbysaba.com' },
  ],
} as const;

export type EntryPath = 'fix' | 'grow' | 'build' | 'unsure';
export type PackageId = 'A' | 'B' | 'C' | 'D' | 'E' | 'F' | 'G' | 'H' | 'I';

export const entryPaths: { label: string; value: EntryPath; hint: string; defaultPackage: PackageId }[] = [
  { label: "Something's wrong with our website", value: 'fix', hint: 'Package A — Website rescue', defaultPackage: 'A' },
  { label: 'We need more calls, visits, or bookings', value: 'grow', hint: 'Package B or C — Findable or customer path', defaultPackage: 'B' },
  { label: 'We want to build or upgrade (site, booking, app, agents, software)', value: 'build', hint: 'Packages D, E, I, or F', defaultPackage: 'F' },
  { label: 'Not sure — help us figure it out', value: 'unsure', hint: 'Package G — Diagnose first', defaultPackage: 'G' },
];

export const needGroups = [
  {
    id: 'website',
    title: 'Website problems',
    options: [
      { id: 'site_error', label: "Site shows an error or won't load" },
      { id: 'mobile_broken', label: 'Site looks bad or broken on phones' },
      { id: 'search_weak', label: "Site doesn't show up well when people search" },
      { id: 'no_customer_path', label: "Customers can't tell how to call, book, or order" },
      { id: 'wp_issues', label: 'WordPress / plugin / update issues' },
      { id: 'unknown_hosting', label: "We don't know who manages our site or hosting" },
      { id: 'new_site', label: 'We need a new website or redesign' },
    ],
  },
  {
    id: 'gbp',
    title: 'Google Business Profile',
    options: [
      { id: 'gbp_optimize', label: 'Google Business Profile optimization (one-time setup / cleanup)' },
      { id: 'gbp_manage', label: 'Google Business Profile ongoing management (posts, hours, photos, Q&A)' },
      { id: 'gbp_mismatch', label: "Our Google listing doesn't match our website or hours" },
    ],
  },
  {
    id: 'findability',
    title: 'Findability & AI visibility',
    options: [
      { id: 'local_seo', label: 'Local SEO improvements' },
      { id: 'ai_visibility', label: 'Help us show up when people ask AI assistants for local recommendations' },
      { id: 'structured_facts', label: 'Structured hours, menu, services, and FAQs for search and AI' },
    ],
  },
  {
    id: 'agents',
    title: 'AI agents & automation',
    options: [
      { id: 'agent_design', label: 'AI agent design — follow-up, intake, or customer questions' },
      { id: 'first_response', label: 'Faster first response to leads and form submissions' },
      { id: 'collect_info', label: 'Collect missing info before staff follow up' },
      { id: 'routing', label: 'Route requests to the right person or department' },
      { id: 'reminders', label: 'Reminders and handoff summaries for staff' },
      { id: 'reduce_admin', label: 'Reduce repetitive admin (not replace our people)' },
    ],
  },
  {
    id: 'booking',
    title: 'Booking & customer flow',
    options: [
      { id: 'online_booking', label: 'Online booking or appointment requests' },
      { id: 'reservations', label: 'Reservations with our rules (hours, capacity, party size, etc.)' },
      { id: 'quote_forms', label: 'Quote or intake forms' },
      { id: 'reduce_calls', label: 'Reduce phone calls for the same questions' },
      { id: 'email_list', label: 'Email list / specials / events' },
    ],
  },
  {
    id: 'software',
    title: 'Software development, web apps & mobile',
    options: [
      { id: 'custom_software', label: 'Custom software development (business-specific tools or workflows)' },
      { id: 'web_app', label: 'Web application (customer portal, booking, admin dashboard)' },
      { id: 'ios', label: 'iPhone / iOS app' },
      { id: 'android', label: 'Android app' },
      { id: 'integrations', label: 'Integrations with tools we already use (Square, email, scheduling, etc.)' },
    ],
  },
  {
    id: 'ongoing',
    title: 'Ongoing',
    options: [
      { id: 'monthly_care', label: 'Someone to maintain the website monthly' },
      { id: 'hosting_migration', label: 'Hosting migration or better hosting setup' },
    ],
  },
] as const;

export const packages: {
  id: PackageId;
  name: string;
  price: string;
  body: string;
  helper?: string;
}[] = [
  {
    id: 'A',
    name: 'Website rescue',
    price: '$125–$250',
    body: 'Fix what is broken so the site works for visitors again. Includes triage on WordPress and hosting where needed.',
  },
  {
    id: 'B',
    name: 'Findable locally',
    price: '$650–$950',
    body: 'Rescue (if needed) + local SEO + Google Business Profile optimization + structured business facts for search and AI.',
  },
  {
    id: 'C',
    name: 'Customer path + booking',
    price: '$1,200–$2,500',
    body: 'Everything in B + clear CTAs + booking / request / intake flow on the website with your rules.',
    helper: 'Best for businesses still doing everything by phone.',
  },
  {
    id: 'I',
    name: 'AI agents & workflow automation',
    price: '$1,500–$4,500+',
    body: 'AI agents for first response, intake, qualification, routing, reminders, and human handoff.',
    helper: 'Often pairs with website booking (C) or a web app (D).',
  },
  {
    id: 'D',
    name: 'Web app & custom software',
    price: '$2,500–$8,000+',
    body: 'Software development and web applications — portals, dashboards, integrations beyond a standard website.',
  },
  {
    id: 'E',
    name: 'Mobile app (iOS and/or Android)',
    price: '$4,000–$12,000+',
    body: 'iPhone/iOS and/or Android app — especially booking-first. Phased delivery; website + booking often come first.',
    helper: 'Many clients start with C on the website, then add mobile in phase 2.',
  },
  {
    id: 'F',
    name: 'Full local digital stack',
    price: '$3,500–$12,000+',
    body: 'Phased: working site, findability, GBP, booking, AI agents, optional web app / mobile apps.',
    helper: 'Fix the foundation, then build the full customer path as needed.',
  },
  {
    id: 'G',
    name: 'Diagnose first',
    price: '$0–$125',
    body: 'We review the site and Google presence, then send a fixed quote for the right package.',
  },
  {
    id: 'H',
    name: 'Ongoing care only',
    price: 'from $49/mo',
    body: 'Website maintenance ($49–$109/mo). Google Business Profile management add-on $79–$149/mo.',
  },
];

export const urgencyOptions = ['Today / urgent', 'This week', 'Not urgent', 'Just exploring'] as const;
export const contactMethods = ['Email', 'Phone', 'Text'] as const;

export const customerActions = [
  'Book appointment',
  'Request reservation',
  'Request quote',
  'Pay deposit',
  'View availability',
  'Other',
] as const;

export const agentHelpOptions = [
  'First response',
  'Collect missing info',
  'Qualify leads',
  'Route to staff',
  'Send reminders',
  'Answer FAQs',
  'Summarize for handoff',
] as const;

export const agentReviewOptions = ['Always a person', 'Sometimes automated', 'Not sure'] as const;
export const agentConnectOptions = ['Website forms', 'Booking', 'Email', 'Text / SMS', 'Other'] as const;

export const buildTypeOptions = [
  'Web app',
  'Custom software',
  'iOS',
  'Android',
  'Integration only',
] as const;

export const appPriorityOptions = ['Not needed', 'Nice to have', 'Primary goal'] as const;
export const timelineOptions = ['ASAP', 'This quarter', 'Planning ahead'] as const;

export const accessComfortOptions = [
  'I can create a temporary WordPress admin user',
  'I want help creating a temporary admin user',
  'I can provide hosting access if needed',
  'I need Google Business Profile manager access instructions',
  'I want to talk first before sharing access',
  'Not sure',
] as const;

export const paymentOptions = [
  'Pay before work begins',
  'Pay after agreed milestone',
  'Pay on completion (small jobs only)',
  'Card invoice',
  'Venmo / PayPal / Zelle',
  'Need invoice for records',
] as const;

/** Checkbox IDs that trigger Section 5 (booking / agents / build details) */
export const detailTriggerNeeds = new Set([
  'online_booking',
  'reservations',
  'quote_forms',
  'agent_design',
  'first_response',
  'collect_info',
  'routing',
  'reminders',
  'reduce_admin',
  'custom_software',
  'web_app',
  'ios',
  'android',
  'integrations',
]);

/** Checkbox IDs that trigger Section 7 (hosting) */
export const hostingTriggerNeeds = new Set(['hosting_migration', 'monthly_care']);

export const detailTriggerPackages = new Set<PackageId>(['C', 'D', 'E', 'F', 'I', 'H']);