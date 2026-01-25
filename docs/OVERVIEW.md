# Customer Reports - Overview

**A PHP MVC affiliate review site platform for product reviews and recommendations.**

> Publish reviews. Rank products. Monetize with affiliate links. Zero WordPress.

---

## What Is Customer Reports?

Customer Reports is a production-ready affiliate review platform that publishes product reviews, comparison listicles, and informational articles — all optimized for affiliate monetization. Built as a complete WordPress replacement with a clean MVC architecture.

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
5. **Pages** (`/{slug}`) — Static pages (contact, privacy, terms)

---

## URL Structure

Category-based URL hierarchy for SEO:

| Route | Template | Description |
|-------|----------|-------------|
| `/` | `home.php` | Homepage with latest content |
| `/categories` | `categories/index.php` | All categories |
| `/category/{slug}` | `categories/show.php` | Category page with articles, reviews, listicles |
| `/category/{cat}/{slug}` | `articles/show.php` | Single article |
| `/category/{cat}/reviews/{slug}` | `reviews/show.php` | Single product review |
| `/category/{cat}/top/{slug}` | `listicles/show.php` | Single listicle |
| `/{slug}` | `pages/default.php` | Static pages |
| `/sitemap.xml` | Dynamic | XML sitemap |
| `/robots.txt` | Dynamic | Robots file |

### Legacy URL Redirects

Old URLs automatically 301 redirect to new structure:
- `/articles/{slug}` → `/category/{cat}/{slug}`
- `/reviews/{slug}` → `/category/{cat}/reviews/{slug}`
- `/top/{slug}` → `/category/{cat}/top/{slug}`

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

### Technical Operations (Operations/)

| Document | Description |
|----------|-------------|
| [Architecture](Operations/ARCHITECTURE.md) | MVC structure, routing, database |
| [SEO](Operations/SEO.md) | Schema.org, meta tags, sitemap, robots.txt |
| [Deployment](Operations/DEPLOYMENT.md) | GitHub Actions CI/CD |
| [Development](Operations/DEVELOPMENT.md) | Local setup |
| [Conventions](Operations/CONVENTIONS.md) | Code style and patterns |
| [Post-Launch Checklist](Operations/POST-LAUNCH-CHECKLIST.md) | QA checklist |
