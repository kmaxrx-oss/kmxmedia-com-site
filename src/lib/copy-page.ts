import fs from 'node:fs';
import path from 'node:path';

export type OverlaySlot = {
  slotId: string;
  kind: 'image' | 'video';
  overlay: string;
  label: string;
};

export type Heading = {
  level: 2 | 3;
  text: string;
};

export type FaqItem = {
  q: string;
  a: string;
};

export type CopyPage = {
  slug: string;
  url: string;
  lane: string;
  ownerQuestion: string;
  primarySerp: string;
  secondarySerp: string[];
  internalLinks: string[];
  ctaTarget: string;
  name: string;
  title: string;
  description: string;
  h1: string;
  topHtml: string;
  middleHtml: string;
  bottomHtml: string;
  primaryAction: string;
  startHref: string;
  overlays: OverlaySlot[];
  headings: Heading[];
  faqs: FaqItem[];
};

const COPY_DIR = path.resolve(process.cwd(), 'copy/pages');

export function toAscii(input: string): string {
  return input
    .replace(/[\u2014\u2013]/g, ' - ')
    .replace(/[\u2012\u2010]/g, '-')
    .replace(/\u2026/g, '...')
    .replace(/[\u201C\u201D]/g, '"')
    .replace(/[\u2018\u2019]/g, "'")
    .replace(/\u00A0/g, ' ')
    .normalize('NFKD')
    .replace(/[\u0300-\u036f]/g, '')
    .replace(/[ \t]{2,}/g, ' ');
}

function escapeHtml(input: string): string {
  return input
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}

export function markdownToHtml(markdown: string): string {
  const cleaned = toAscii(markdown).trim();
  if (!cleaned) return '';
  const paragraphs = cleaned.split(/\n\s*\n/).map((block) => {
    let html = escapeHtml(block.replace(/\n/g, ' ').replace(/\s+/g, ' ').trim());
    html = html.replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2">$1</a>');
    html = html.replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>');
    return `<p>${html}</p>`;
  });
  return paragraphs.join('\n');
}

function parseList(raw: string | undefined): string[] {
  if (!raw) return [];
  const match = raw.trim().match(/^\[(.*)\]$/s);
  if (!match) return [];
  return match[1]
    .split(',')
    .map((item) => item.trim().replace(/^['"]|['"]$/g, ''))
    .filter(Boolean);
}

function frontmatterValue(fm: string, key: string): string {
  const match = fm.match(new RegExp(`^${key}:\\s*(.+)$`, 'm'));
  return match ? match[1].trim() : '';
}

function sectionsMap(body: string): Record<string, string> {
  const map: Record<string, string> = {};
  for (const part of body.split(/^## /m).slice(1)) {
    const nl = part.indexOf('\n');
    if (nl < 0) continue;
    map[part.slice(0, nl).trim()] = part.slice(nl + 1).trim();
  }
  return map;
}

function section(body: string, heading: string): string {
  const map = sectionsMap(body);
  if (map[heading]) return map[heading];
  const found = Object.entries(map).find(([title]) => title.startsWith(heading));
  return found ? found[1] : '';
}

function subsection(body: string, heading: string, next?: string): string {
  const start = body.indexOf(heading);
  if (start < 0) return '';
  let after = body.slice(start + heading.length);
  const nl = after.indexOf('\n');
  if (nl >= 0) after = after.slice(nl + 1);
  if (!next) return after.trim();
  const end = after.indexOf(next);
  return (end < 0 ? after : after.slice(0, end)).trim();
}

function parseTable(block: string): string[][] {
  return block
    .split('\n')
    .map((line) => line.trim())
    .filter((line) => line.startsWith('|'))
    .filter((line) => !/^\|[\s-|:]+\|$/.test(line))
    .map((line) =>
      line
        .split('|')
        .slice(1, -1)
        .map((cell) => toAscii(cell.trim())),
    )
    .filter((row) => row.length > 0 && !/^slot_id$/i.test(row[0] || '') && row[0] !== 'Q');
}

function parsePage(raw: string): CopyPage {
  const fmMatch = raw.replace(/\r\n/g, '\n').match(/^---\n([\s\S]*?)\n---\n([\s\S]*)$/);
  if (!fmMatch) {
    throw new Error('Copy file missing YAML frontmatter');
  }
  const fm = fmMatch[1];
  const body = fmMatch[2];
  const slug = frontmatterValue(fm, 'slug');
  const url = frontmatterValue(fm, 'url');
  const nameMatch = body.match(/^#\s+(.+)$/m);
  const metadata = section(body, 'Metadata');
  const funnel = section(body, 'Funnel copy (Search-to-AI)');
  const overlayBlock = section(body, 'Overlay text and media slots');
  const headingBlock = section(body, 'Headings (H2-H3)');
  const faqBlock = section(body, 'FAQ') || section(body, 'FAQ (optional; guest-safe)');

  const top = subsection(funnel, '### Top ~30 percent', '### Middle ~40 percent');
  const middle = subsection(funnel, '### Middle ~40 percent', '### Bottom ~30 percent');
  const bottomFull = subsection(funnel, '### Bottom ~30 percent');
  const primaryMatch = bottomFull.match(/\*\*Primary action:\*\*\s*(.+)/);
  const bottom = bottomFull.replace(/\*\*Primary action:\*\*\s*.+/g, '').trim();
  const startMatch = bottomFull.match(/\((\/start\/[^)]*)\)/) || funnel.match(/\((\/start\/[^)]*)\)/);

  const overlays: OverlaySlot[] = parseTable(overlayBlock)
    .filter((row) => row.length >= 4)
    .map((row) => ({
      slotId: row[0],
      kind: row[1] === 'video' ? 'video' : 'image',
      overlay: toAscii(row[2]),
      label: toAscii(row[3]),
    }));

  const headings: Heading[] = headingBlock
    .split('\n')
    .map((line) => line.trim())
    .filter(Boolean)
    .map((line) => {
      const h3 = line.match(/^-?\s*H3:?\s*(.+)$/i);
      if (h3) return { level: 3 as const, text: toAscii(h3[1].trim()) };
      const h2 = line.match(/^-?\s*H2:?\s*(.+)$/i);
      if (h2) return { level: 2 as const, text: toAscii(h2[1].trim()) };
      return null;
    })
    .filter((item): item is Heading => item !== null);

  const faqs: FaqItem[] = parseTable(faqBlock)
    .filter((row) => row.length >= 2)
    .map((row) => ({ q: toAscii(row[0]), a: toAscii(row[1]) }));

  const title = toAscii((metadata.match(/^- title:\s*(.+)$/m) || [])[1] || '');
  const description = toAscii((metadata.match(/^- meta_description:\s*(.+)$/m) || [])[1] || '');
  const h1 = toAscii((metadata.match(/^- H1:\s*(.+)$/m) || [])[1] || '');

  return {
    slug,
    url,
    lane: frontmatterValue(fm, 'lane'),
    ownerQuestion: toAscii(frontmatterValue(fm, 'owner_question')),
    primarySerp: toAscii(frontmatterValue(fm, 'primary_serp')),
    secondarySerp: parseList(frontmatterValue(fm, 'secondary_serp')).map(toAscii),
    internalLinks: parseList(frontmatterValue(fm, 'internal_links_to')),
    ctaTarget: frontmatterValue(fm, 'cta_target') || '/start/',
    name: toAscii(nameMatch?.[1] || slug),
    title,
    description,
    h1,
    topHtml: markdownToHtml(top),
    middleHtml: markdownToHtml(middle),
    bottomHtml: markdownToHtml(bottom),
    primaryAction: toAscii(primaryMatch?.[1] || 'Start My Website Plan'),
    startHref: startMatch?.[1] || '/start/',
    overlays,
    headings,
    faqs,
  };
}

let cache: CopyPage[] | null = null;

export function loadCopyPages(): CopyPage[] {
  if (cache) return cache;
  cache = fs
    .readdirSync(COPY_DIR)
    .filter((file) => file.endsWith('.md'))
    .map((file) => parsePage(fs.readFileSync(path.join(COPY_DIR, file), 'utf8')));
  return cache;
}

export function loadCopyPage(slug: string): CopyPage {
  const page = loadCopyPages().find((item) => item.slug === slug);
  if (!page) throw new Error(`Unknown copy slug: ${slug}`);
  return page;
}

export function pageNameForUrl(url: string): string {
  const page = loadCopyPages().find((item) => item.url === url);
  return page?.name || url.replace(/^\/|\/$/g, '').replace(/-/g, ' ');
}

export const LANE_EYEBROW: Record<string, string> = {
  '1-website': 'Colorado Springs web design',
  '2-visibility': 'Search visibility',
  '3-system': 'Booking and intake',
  '4-rescue': 'Website repair',
  hub: 'Project brief',
  proof: 'Selected work',
  home: 'Colorado Springs web design',
};

export const START_DOORS = [
  { href: '/colorado-springs-web-design/', h3: 'New Website or Redesign' },
  { href: '/website-conversion-optimization/', h3: 'Visibility and Website Conversion' },
  { href: '/custom-booking-systems/', h3: 'Booking, Quote, Intake, or Automation' },
  { href: '/website-repair-rescue/', h3: 'Website Repair and Continuing Care' },
];

export const PROOF_SITES = [
  { href: 'https://twincitiesshuttle.com/', h3: 'Twin Cities Shuttle' },
  { href: 'https://kushbysaba.com/', h3: 'Kush by Saba' },
];
