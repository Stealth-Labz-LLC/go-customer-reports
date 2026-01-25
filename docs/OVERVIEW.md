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
| Total Lines of Code | ~5,900 |
| PHP | 3,034 lines |
| CSS | 2,875 lines |
| Code Files | 36 |
| Total Commits | 24 |
| Production Status | Live |
| Dependencies | Zero (no npm/composer) |

### Content Types

1. **Reviews** (`/reviews/{slug}`) — Product reviews with ratings, pros/cons, affiliate CTAs
2. **Listicles** (`/top/{slug}`) — "Top 10 Best X" comparison guides
3. **Articles** (`/articles/{slug}`) — Informational blog content
4. **Categories** (`/category/{slug}`) — Content grouped by topic
5. **Pages** (`/{slug}`) — Static pages (about, contact, privacy)

---

## URL Structure

| Route | Template | Description |
|-------|----------|-------------|
| `/` | `home.php` | Homepage with latest content |
| `/articles` | `articles/index.php` | Article listing (paginated) |
| `/articles/{slug}` | `articles/show.php` | Single article |
| `/reviews` | `reviews/index.php` | Review listing (paginated) |
| `/reviews/{slug}` | `reviews/show.php` | Single product review |
| `/top` | `listicles/index.php` | Listicle listing |
| `/top/{slug}` | `listicles/show.php` | Single listicle |
| `/category/{slug}` | `categories/show.php` | Category page |
| `/{slug}` | `pages/default.php` | Static pages |
| `/sitemap.xml` | `sitemap.php` | XML sitemap |

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
| [Deployment](Operations/DEPLOYMENT.md) | GitHub Actions CI/CD |
| [Development](Operations/DEVELOPMENT.md) | Local setup |
