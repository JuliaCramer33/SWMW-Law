# SWMW Law — WordPress Site (wp-content)

Overview of the **SWMW Law** website codebase for developers. This repository contains the theme, custom plugins, and GitHub Actions used for the site. The site runs on **WordPress** and is deployed to **WP Engine**.

---

## What’s in This Repo

- **`themes/swmw-law/`** — Custom theme (primary codebase)
- **`plugins/`** — Plugins, including custom `core-block-custom-breakpoints`
- **`.github/workflows/`** — CI/CD: deploy to WP Engine staging and production
- **`.deployignore`** — Excludes dev/config/source from deploy; only built assets go up

---

## Tech Stack

| Layer | Technology |
|-------|------------|
| **CMS** | WordPress |
| **Theme** | Custom theme `swmw-law` (PHP, block-based) |
| **Styling** | SCSS → compiled `dist/css/style.css`, `theme.json` (design tokens, block styles) |
| **Scripts** | JavaScript (ES modules), built with `@wordpress/scripts` → `dist/js/main.js`, `admin.js` |
| **Blocks** | ACF blocks (block.json + PHP templates), block patterns |
| **Custom fields** | Advanced Custom Fields Pro (ACF), theme-defined field groups |
| **Hosting / Deploy** | WP Engine (staging: `swmwlawstg`, production: `swmwlawnew`) |
| **Local dev** | Intended for **Local by Flywheel** (or any local WordPress stack) |
| **Node** | Node 18 LTS (for theme and plugin builds) |

---

## Theme: `swmw-law`

- **Namespace:** `SWMW_Law`
- **Text domain:** `swmw-law`
- **Styles:** SCSS in `assets/scss/` → Webpack/build → `dist/css/style.css`
- **Scripts:** `assets/js/` (main entry, blocks) → `dist/js/main.js` (and admin build)
- **Fonts:** Google Fonts (Montserrat, Poppins); Splide carousel from CDN

### Custom Post Types (in theme)

| Post type | Slug | Purpose |
|-----------|------|---------|
| Attorney | `attorney` | Team/attorney profiles; archive at `/attorneys/` |
| Result | `swmw_result` | Case results; archive at `/results/`, ordered by amount |
| City | `city` | Job sites / cities (no public archive) |
| Testimonial | (internal) | Client testimonials |

### Taxonomies

- **Attorney:** `attorney_position` (position/ordering)
- **Results:** `swmw_result_category`, `swmw_result_status` (e.g. “featured”)
- **Cities:** used by jobsite blocks

### ACF Blocks (block.json + PHP)

Registered in `includes/blocks/blocks.php`:

- `hero`, `logo-grid`, `expandable-card`, `image-split`, `results`, `attorneys`
- `tabs`, `tab-panel`, `accordion`, `accordion-panel`
- `testimonials`, `hero-dropdown-menu`
- `jobsites-by-city`, `jobsites-az`, `jobsites-directory`

Block patterns (e.g. hero variants, image-split, slanted badge, credential rows) live under `patterns/`.

### Theme behavior (high level)

- **Attorneys:** Archive shows 16 per page; sorted by position priority then name; custom image size `attorney-card` (400×480); “Back to top” on archive.
- **Results:** Amounts parsed from text (e.g. “$7 Million”) and stored as numeric meta `result_amount_num` for ordering; archives/taxonomy exclude “featured” and order by amount desc; JSON-LD (CollectionPage, BreadcrumbList) on results archives.
- **Options:** ACF “Theme General Settings” options page.
- **Misc:** SVG uploads allowed; button block icons; block animation styles (fade-in, slide-up, etc.); breadcrumbs; Results CSV importer under **Tools** (update/create results from CSV or URL).

---

## Custom Plugin

- **`plugins/core-block-custom-breakpoints/`** — Custom breakpoints for core blocks (Bootstrap 5, `@wordpress/scripts`). Built with `npm run build`; output used in block editor and front.

---

## Spin-Up Process

### Prerequisites

- **Local by Flywheel** (or equivalent: PHP 7.4+, MySQL, WordPress)
- **Node.js** 18 LTS (for building theme and plugin assets)
- **npm** 7+

### 1. Get the site running (Local)

1. Clone or open the project so that the WordPress root is something like:
   - `…/Local Sites/swmw-law/app/public/`
2. In Local, use an existing site “swmw-law” or create one and point it at this `public` folder.
3. Start the site in Local so you have a working WP install and database.

### 2. Install PHP dependencies (if any)

- The theme and plugin do not use Composer in the repo; ACF Pro and other plugins are assumed present in `plugins/` (as in a typical WP setup).

### 3. Build theme assets

```bash
cd themes/swmw-law
npm ci
npm run build
```

- Produces `dist/css/style.css`, `dist/js/main.js`, `dist/js/main.asset.php`, and admin assets.

### 4. Build plugin assets (core-block-custom-breakpoints)

```bash
cd plugins/core-block-custom-breakpoints
npm ci
npm run build
```

### 5. WordPress configuration

- Ensure **Advanced Custom Fields Pro** is active (theme expects ACF for blocks and options).
- Activate theme **SWMW Law** and any required plugins.
- Set permalinks (e.g. Post name) so `/attorneys/` and `/results/` work.

### 6. Development commands (theme)

From `themes/swmw-law`:

- `npm run start` — watch mode for JS/CSS
- `npm run lint` — lint CSS, JS, PHP
- `npm run format` — format code

---

## Deployment (GitHub Actions)

- **Staging:** Push to `develop` → workflow builds theme + plugin, then deploys to WP Engine env `swmwlawstg` under `wp-content/`.
- **Production:** Push to `main` → same build steps, deploys to `swmwlawnew`.

Secrets: `WPE_SSHG_KEY_PRIVATE` for WP Engine SSH deploy.  
Build uses Node 18, `npm ci`, and installs `libvips-dev` for the plugin’s sharp dependency. Only contents under `wp-content/` are deployed; `.deployignore` excludes source and dev-only files so only compiled assets and PHP/config go up.

---

## Repo structure (wp-content)

```
wp-content/
├── .github/workflows/
│   ├── deploy-staging.yml    # develop → staging
│   └── deploy-production.yml # main → production
├── .deployignore
├── plugins/
│   ├── core-block-custom-breakpoints/   # custom (built with npm)
│   ├── advanced-custom-fields-pro/
│   └── … (other plugins)
├── themes/
│   └── swmw-law/
│       ├── assets/           # SCSS, JS source
│       ├── dist/             # Built CSS/JS (commit or build on deploy)
│       ├── includes/        # Blocks, post types, ACF, setup, scripts
│       ├── patterns/
│       ├── template-parts/
│       ├── functions.php
│       ├── theme.json
│       ├── style.css
│       └── package.json
└── uploads/                  # Media (often not in repo)
```

---

## Quick reference for outside developers

- **Where templates live:** `themes/swmw-law/` (archive/single templates, `template-parts/`, block templates in `includes/blocks/*/template.php`).
- **Where to add blocks:** `themes/swmw-law/includes/blocks/<block-name>/` with `block.json` and optional `fields.php`/`template.php`; register the folder name in `includes/blocks/blocks.php`.
- **Where CPTs are defined:** `themes/swmw-law/includes/post-types/` (attorneys, results, cities, testimonial).
- **Build before testing:** Run `npm run build` in the theme and in `core-block-custom-breakpoints` so `dist/` is up to date.
- **Local URL:** Depends on Local (e.g. `https://swmw-law.local`). Use that when configuring WP or debugging.
