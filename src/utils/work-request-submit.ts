import type { EstimatorSnapshot } from '../data/work-request-estimator';
import type { WorkRequestRecommendation } from '../data/work-request-recommendation';

export interface WorkRequestPayload {
  business_name: string;
  contact_name: string;
  work_email: string;
  phone: string;
  contact_method: string;
  urgency: string;
  website_url: string;
  no_website: boolean;
  gbp_url: string;
  service_area: string;
  entry_path: string;
  needs: string[];
  problem_summary: string;
  primary_package: string;
  customer_actions: string[];
  booking_rules: string;
  current_tools: string;
  agent_help: string[];
  agent_review: string;
  agent_connect: string[];
  build_type: string[];
  ios_priority: string;
  android_priority: string;
  timeline: string;
  access_comfort: string[];
  interest_migration: boolean;
  interest_gbp_manage: boolean;
  auth_work_request: boolean;
  auth_repair: boolean;
  payment_preference: string;
  recommendation: WorkRequestRecommendation;
  estimator_snapshot: EstimatorSnapshot;
  submitted_at: string;
  source: string;
}

const DEFAULT_ENDPOINT = '/api/work-request.php';

export async function submitWorkRequest(
  endpoint: string,
  payload: WorkRequestPayload,
): Promise<{ id?: string }> {
  const url = endpoint.trim() || DEFAULT_ENDPOINT;
  const response = await fetch(url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      Accept: 'application/json',
    },
    body: JSON.stringify(payload),
  });

  if (!response.ok) {
    let detail = '';
    try {
      const data = (await response.json()) as { error?: string };
      detail = data.error ? `: ${data.error}` : '';
    } catch {
      /* ignore */
    }
    throw new Error(`Submit failed (${response.status})${detail}`);
  }

  try {
    return (await response.json()) as { id?: string };
  } catch {
    return {};
  }
}