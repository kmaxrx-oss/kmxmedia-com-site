import type { PackageId } from './work-request-form';
import { packages } from './work-request-form';
import { suggestPackage, type RecommendationInput } from './work-request-recommendation';

/** Operator-facing only — never show raw hourly math to clients. */
export interface EstimatorSnapshot {
  recommended_package: PackageId;
  package_name: string;
  client_price_range: string;
  estimated_hours_min: number;
  estimated_hours_max: number;
  suggested_quote_min: number;
  suggested_quote_max: number;
  suggested_quote_note: string;
  likely_access: string[];
  optional_next_rung: string;
  add_on_labels: string[];
  urgency_multiplier: number;
  status: 'operator_preview';
}

const packageBaseHours: Record<PackageId, [number, number]> = {
  G: [0.5, 1.5],
  A: [1, 2.5],
  B: [5, 9],
  C: [10, 22],
  I: [12, 35],
  D: [25, 100],
  E: [50, 180],
  F: [35, 160],
  H: [0.5, 2],
};

const packagePriceRange: Record<PackageId, [number, number]> = {
  G: [0, 125],
  A: [125, 250],
  B: [650, 950],
  C: [1200, 2500],
  I: [1500, 4500],
  D: [2500, 8000],
  E: [4000, 12000],
  F: [3500, 12000],
  H: [49, 109],
};

const needHourAddons: Record<string, [number, number, string]> = {
  site_error: [1, 2, 'Broken site / error'],
  mobile_broken: [1, 2, 'Mobile layout issues'],
  wp_issues: [1, 3, 'WordPress / plugin issues'],
  unknown_hosting: [1, 2, 'Hosting ownership discovery'],
  hosting_migration: [2, 5, 'Hosting migration'],
  new_site: [8, 20, 'New site / redesign scope'],
  gbp_optimize: [2, 4, 'Google Business Profile cleanup'],
  gbp_manage: [1, 2, 'GBP management setup'],
  gbp_mismatch: [1, 3, 'GBP / website alignment'],
  local_seo: [2, 5, 'Local SEO'],
  ai_visibility: [2, 5, 'AI / structured facts readiness'],
  structured_facts: [2, 5, 'Structured business facts'],
  quote_forms: [2, 5, 'Contact / intake form'],
  online_booking: [4, 12, 'Booking rules'],
  reservations: [4, 12, 'Reservation rules'],
  agent_design: [8, 20, 'AI intake / follow-up agent'],
  first_response: [4, 10, 'Faster first response flows'],
  web_app: [20, 60, 'Web app dashboard'],
  custom_software: [20, 60, 'Custom software'],
  ios: [5, 12, 'Mobile app discovery (iOS)'],
  android: [5, 12, 'Mobile app discovery (Android)'],
  integrations: [4, 12, 'Tool integrations'],
  monthly_care: [0.5, 1, 'Ongoing care onboarding'],
};

const ladder: PackageId[] = ['G', 'A', 'B', 'C', 'I', 'D', 'E', 'F'];

function urgencyMultiplier(urgency: string): number {
  if (urgency.toLowerCase().includes('today') || urgency.toLowerCase().includes('urgent')) return 1.2;
  return 1;
}

function likelyAccess(needs: string[], accessComfort: string[]): string[] {
  const notes: string[] = [];
  if (needs.some((n) => ['site_error', 'wp_issues', 'mobile_broken'].includes(n))) {
    notes.push('WordPress temporary admin first');
  }
  if (needs.includes('hosting_migration') || needs.includes('unknown_hosting')) {
    notes.push('Hosting panel if redirect cannot be set in WordPress');
  }
  if (needs.some((n) => n.startsWith('gbp_'))) {
    notes.push('Google Business Profile manager invite after approval');
  }
  if (accessComfort.some((a) => a.includes('talk first'))) {
    notes.push('Client prefers call before access');
  }
  if (!notes.length) notes.push('Review access needs after first look');
  return notes;
}

export function buildEstimatorSnapshot(input: {
  recommendation: RecommendationInput;
  urgency: string;
  access_comfort: string[];
}): EstimatorSnapshot {
  const pkgId = suggestPackage(input.recommendation);
  const pkg = packages.find((p) => p.id === pkgId)!;
  const [baseMin, baseMax] = packageBaseHours[pkgId];
  const [priceMin, priceMax] = packagePriceRange[pkgId];

  let addMin = 0;
  let addMax = 0;
  const addOnLabels: string[] = [];
  for (const need of input.recommendation.selectedNeeds) {
    const addon = needHourAddons[need];
    if (!addon) continue;
    addMin += addon[0];
    addMax += addon[1];
    addOnLabels.push(addon[2]);
  }

  const mult = urgencyMultiplier(input.urgency);
  const hoursMin = Math.round((baseMin + addMin) * mult * 10) / 10;
  const hoursMax = Math.round((baseMax + addMax) * mult * 10) / 10;

  const idx = ladder.indexOf(pkgId);
  const nextPkg = idx >= 0 && idx < ladder.length - 1 ? packages.find((p) => p.id === ladder[idx + 1]) : null;
  const optionalNextRung = nextPkg ? `${nextPkg.id} — ${nextPkg.name} (${nextPkg.price})` : 'Phased options in manual quote';

  let quoteNote = `Suggested range $${priceMin}–$${priceMax} — confirm after review`;
  if (pkgId === 'A') quoteNote = 'Often $250 flat for rescue — confirm after brief look';

  return {
    recommended_package: pkgId,
    package_name: pkg.name,
    client_price_range: pkg.price,
    estimated_hours_min: hoursMin,
    estimated_hours_max: hoursMax,
    suggested_quote_min: priceMin,
    suggested_quote_max: priceMax,
    suggested_quote_note: quoteNote,
    likely_access: likelyAccess(input.recommendation.selectedNeeds, input.access_comfort),
    optional_next_rung: optionalNextRung,
    add_on_labels: addOnLabels,
    urgency_multiplier: mult,
    status: 'operator_preview',
  };
}