# Customer Reports - Overview

**A PHP MVC affiliate review site platform for product reviews and recommendations.**

> Publish reviews. Rank products. Monetize with affiliate links. Zero WordPress.

---

## What Is Customer Reports?

Customer Reports is a production affiliate review platform that publishes product reviews, comparison listicles, and informational articles — all optimized for affiliate monetization. Built with a clean MVC architecture and zero external dependencies.

**Live Site:** https://customer-reports.org

---

## Current State

| Metric | Value |
|--------|-------|
| Articles | 12,995 |
| Reviews | 148+ |
| Listicles | 100+ |
| Categories | 14 |
| Production Status | Live |
| Dependencies | Zero (no npm/composer) |

### Content Types

1. **Reviews** (`/category/{cat}/reviews/{slug}`) — Product reviews with ratings, pros/cons, affiliate CTAs
2. **Listicles** (`/category/{cat}/top/{slug}`) — "Top 10 Best X" comparison guides
3. **Articles** (`/category/{cat}/{slug}`) — Informational blog content
4. **Categories** (`/category/{slug}`) — Content grouped by topic
5. **Pages** (`/{slug}`) — Static pages (about, privacy, terms)

---

## URL Structure

Category-based URL hierarchy for SEO:

| Route | Template | Description |
|-------|----------|-------------|
| `/` | `home.php` | Homepage with latest content |
| `/articles` | `articles/index.php` | All articles (paginated, filterable) |
| `/reviews` | `reviews/index.php` | All reviews (paginated, sortable) |
| `/categories` | `categories/index.php` | All categories |
| `/category/{slug}` | `categories/show.php` | Category page with articles, reviews, listicles |
| `/category/{cat}/{slug}` | `articles/show.php` | Single article |
| `/category/{cat}/reviews/{slug}` | `reviews/show.php` | Single product review |
| `/category/{cat}/top/{slug}` | `listicles/show.php` | Single listicle |
| `/search` | `search.php` | Full-text search across all content |
| `/{slug}` | `pages/default.php` | Static pages |
| `/sitemap.xml` | Dynamic | XML sitemap index with sub-sitemaps |
| `/robots.txt` | Dynamic | Robots file |

### Legacy URL Redirects

Old URLs automatically 301 redirect to the category-based structure:
- `/articles/{slug}` → `/category/{cat}/{slug}`
- `/reviews/{slug}` → `/category/{cat}/reviews/{slug}`
- `/top/{slug}` → `/category/{cat}/top/{slug}`

---

## Design System

The frontend uses a teal/amber/slate color palette with CSS design tokens:

| Token | Value | Usage |
|-------|-------|-------|
| `--cr-teal` | #0d7377 | Primary brand color, success states |
| `--cr-amber` | #e6a817 | Accent highlights, ratings, CTAs |
| `--cr-slate` | #1a2332 | Dark backgrounds, hero sections |

All templates use Bootstrap 5.3.3 utilities — no inline styles. Shared hero sections (`.hero-section`, `.hero-section-simple`), section eyebrows, and consistent badge palette across all pages.

---

## Environments

| Environment | Detection | URL |
|-------------|-----------|-----|
| Local | `localhost` | `customer-reports.local` |
| Production | `main` branch | `customer-reports.org` |

---

## Company & Repository

- **Platform:** Customer Reports
- **Parent Company:** Stealth Labz LLC
- **Domain:** customer-reports.org
- **Repository:** github.com/Stealth-Labz-LLC/go-customer-reports

---

## Documentation Index

### Business Documents (this folder)

| Document | Description |
|----------|-------------|
| [Overview](OVERVIEW.md) | This file — project summary |
| [Technology](TECHNOLOGY.md) | Tech stack and architecture |
| [Product](PRODUCT.md) | Features, content types, monetization |
| [Cost](COST.md) | Build cost analysis |
| [Opportunity](OPPORTUNITY.md) | Revenue potential and ROI |

### Technical Operations (Operations/)

| Document | Description |
|----------|-------------|
| [Architecture](Operations/ARCHITECTURE.md) | MVC structure, routing, database |
| [SEO](Operations/SEO.md) | Schema.org, meta tags, sitemap, robots.txt |
| [Deployment](Operations/DEPLOYMENT.md) | GitHub Actions CI/CD |
| [Development](Operations/DEVELOPMENT.md) | Local setup |
| [Conventions](Operations/CONVENTIONS.md) | Code style, design system, patterns |
| [Post-Launch Checklist](Operations/POST-LAUNCH-CHECKLIST.md) | QA checklist |
