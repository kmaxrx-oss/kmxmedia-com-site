import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const root = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const dist = path.join(root, 'dist');
const source = path.join(dist, 'sitemap-0.xml');
const target = path.join(dist, 'sitemap.xml');

if (!fs.existsSync(source)) {
  console.error('copy-sitemap: dist/sitemap-0.xml not found — run astro build first');
  process.exit(1);
}

fs.copyFileSync(source, target);
console.log('copy-sitemap: dist/sitemap-0.xml -> dist/sitemap.xml');