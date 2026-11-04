# The Blueprint Summit Landing Page

Standalone WordPress + Elementor authoring environment and Vercel static deployment for The Blueprint Summit.

## Architecture

- WordPress, MariaDB, Nginx, Elementor, and WP-CLI run in an isolated Docker Compose stack.
- Local WordPress authoring URL: `http://localhost:8096/wp-admin/`
- The production artifact is exported to `vercel-static/` and deployed to a separate Vercel project.
- Vercel Web Analytics is loaded with `/_vercel/insights/script.js` on every exported page.
- The repository intentionally excludes `.env.local`, Docker volumes, WordPress runtime data, and `.vercel` state.

Vercel does not provide persistent PHP/MySQL Docker hosting. WordPress is the editing source; the visitor-facing Vercel deployment is a static export.

## Start WordPress

```bash
docker compose --env-file .env.local up -d db wordpress nginx
docker compose --env-file .env.local --profile tools run --rm wp-cli core is-installed || ./scripts/bootstrap-wordpress.sh
```

## Export for Vercel

```bash
node scripts/build-static-export.mjs
```

## Event

- Event: The Blueprint Summit
- Dates: January 15–17, 2027
- Venue: DoubleTree by Hilton Hotel Ontario Airport
- Registration: https://www.eventbrite.com/e/the-blueprint-summit-tickets-2003832748693

