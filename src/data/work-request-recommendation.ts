import type { EntryPath, PackageId } from './work-request-form';
import { entryPaths, packages } from './work-request-form';

export interface RecommendationInput {
  entryPath: EntryPath | '';
  selectedNeeds: string[];
  selectedPackage: PackageId | '';
}

export interface WorkRequestRecommendation {
  suggestedPackage: PackageId;
  packageName: string;
  packagePrice: string;
  rationale: string;
  phasedPath: string[];
  optionalNextStep: string;
}

const packageMap = Object.fromEntries(packages.map((p) => [p.id, p])) as Record<
  PackageId,
  (typeof packages)[number]
>;

function hasAny(needs: string[], ids: string[]): boolean {
  return ids.some((id) => needs.includes(id));
}

export function suggestPackage(input: RecommendationInput): PackageId {
  const { entryPath, selectedNeeds, selectedPackage } = input;
  const needs = selectedNeeds;

  if (selectedPackage) return selectedPackage;

  const entryDefault = entryPaths.find((e) => e.value === entryPath)?.defaultPackage;
  if (entryDefault && entryPath === 'unsure') return entryDefault;

  const siteBroken = hasAny(needs, ['site_error', 'mobile_broken', 'wp_issues']);
  const gbp = hasAny(needs, ['gbp_optimize', 'gbp_mismatch', 'gbp_manage']);
  const booking = hasAny(needs, ['online_booking', 'reservations', 'quote_forms', 'reduce_calls']);
  const agents = hasAny(needs, ['agent_design', 'first_response', 'collect_info', 'routing', 'reminders', 'reduce_admin']);
  const software = hasAny(needs, ['custom_software', 'web_app', 'integrations']);
  const ios = needs.includes('ios');
  const android = needs.includes('android');
  const seo = hasAny(needs, ['local_seo', 'ai_visibility', 'structured_facts', 'search_weak']);
  const ongoing = hasAny(needs, ['monthly_care', 'hosting_migration']);

  if (ongoing && needs.length <= 2) return 'H';
  if (siteBroken && !seo && !booking && !agents && !software && !ios && !android) return 'A';
  if (siteBroken && gbp && !booking) return 'B';
  if (booking && (ios || android)) return 'C';
  if (booking && !agents && !software) return 'C';
  if (agents && booking) return 'F';
  if (agents && !booking) return 'I';
  if (software && booking) return 'D';
  if (software) return 'D';
  if (seo && gbp) return 'B';
  if (agents) return 'I';
  if (entryDefault) return entryDefault;
  return 'G';
}

export function buildWorkRequestRecommendation(input: RecommendationInput): WorkRequestRecommendation {
  const suggested = suggestPackage(input);
  const pkg = packageMap[suggested];
  const needs = input.selectedNeeds;

  const booking = hasAny(needs, ['online_booking', 'reservations', 'quote_forms']);
  const agents = hasAny(needs, ['agent_design', 'first_response', 'collect_info', 'routing']);
  const apps = hasAny(needs, ['ios', 'android', 'web_app', 'custom_software']);

  const phasedPath: string[] = [];
  if (hasAny(needs, ['site_error', 'mobile_broken', 'wp_issues']) || ['A', 'G'].includes(suggested)) {
    phasedPath.push('Fix the site');
  }
  if (booking || suggested === 'C') phasedPath.push('Booking on the website');
  if (agents || suggested === 'I') phasedPath.push('AI agents for follow-up and intake');
  if (apps || ['D', 'E'].includes(suggested)) phasedPath.push('Web or mobile app when you need a dedicated product');

  if (!phasedPath.length) {
    phasedPath.push('Diagnose', 'Quote the right package', 'Deliver in agreed phases');
  }

  let rationale = `Based on your entry path and selected needs, ${pkg.name} is the closest starting package.`;
  if (input.selectedPackage && input.selectedPackage !== suggested) {
    rationale = `You selected ${pkg.name}. We will confirm scope and price after review.`;
  }

  const ladder: PackageId[] = ['A', 'B', 'C', 'I', 'D', 'E', 'F'];
  const idx = ladder.indexOf(suggested);
  const next = idx >= 0 && idx < ladder.length - 1 ? packageMap[ladder[idx + 1]] : null;
  const optionalNextStep = next
    ? `Optional next step: ${next.name} (${next.price})`
    : 'We will include phased options in our reply if a larger stack makes sense.';

  return {
    suggestedPackage: suggested,
    packageName: pkg.name,
    packagePrice: pkg.price,
    rationale,
    phasedPath,
    optionalNextStep,
  };
}