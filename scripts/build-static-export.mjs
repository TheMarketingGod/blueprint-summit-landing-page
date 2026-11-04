import fs from 'node:fs';
import path from 'node:path';

const root = process.cwd();
const output = path.join(root, 'vercel-static');
const local = 'http://localhost:8096';

fs.rmSync(output, { recursive: true, force: true });
fs.mkdirSync(output, { recursive: true });

const assetQueue = [];
const seen = new Set();

function cleanHtml(html) {
  let cleaned = html.replaceAll(local, '');
  if (!cleaned.includes('/_vercel/insights/script.js')) {
    cleaned = cleaned.replace('</head>', '<script defer src="/_vercel/insights/script.js"></script>\n</head>');
  }
  return cleaned;
}

function enqueue(text, baseUrl = '/') {
  const matches = text.match(/(?:https?:\/\/localhost:8096)?\/(?:wp-content|wp-includes)\/[^\s"'<>),]+/g) || [];
  for (let value of matches) {
    value = value.replace(local, '').replaceAll('&#038;', '&').split('?')[0].split('#')[0];
    assetQueue.push(value);
  }
  if (baseUrl.endsWith('.css')) {
    for (const match of text.matchAll(/url\((['"]?)([^)'"\s]+)\1\)/g)) {
      const value = match[2];
      if (value.startsWith('data:') || value.startsWith('http')) continue;
      const resolved = value.startsWith('/') ? value : path.posix.normalize(path.posix.join(path.posix.dirname(baseUrl), value));
      if (resolved.startsWith('/wp-content/') || resolved.startsWith('/wp-includes/')) assetQueue.push(resolved.split('?')[0]);
    }
  }
}

const response = await fetch(local + '/');
if (!response.ok) throw new Error(`Unable to export WordPress homepage (${response.status})`);
const html = cleanHtml(await response.text());
fs.writeFileSync(path.join(output, 'index.html'), html);
enqueue(html);

while (assetQueue.length) {
  const urlPath = assetQueue.shift();
  if (!urlPath || seen.has(urlPath)) continue;
  seen.add(urlPath);
  const assetResponse = await fetch(local + urlPath);
  if (!assetResponse.ok) continue;
  const target = path.join(output, urlPath.replace(/^\//, ''));
  fs.mkdirSync(path.dirname(target), { recursive: true });
  const buffer = Buffer.from(await assetResponse.arrayBuffer());
  fs.writeFileSync(target, buffer);
  if (urlPath.endsWith('.css')) enqueue(buffer.toString('utf8'), urlPath);
}

fs.writeFileSync(path.join(output, '404.html'), html);
console.log(`Exported Blueprint Summit landing page with ${seen.size} assets.`);

