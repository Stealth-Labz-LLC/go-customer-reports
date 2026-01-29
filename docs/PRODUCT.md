# Customer Reports - Product

**A complete affiliate review site platform — reviews, listicles, articles, all optimized for conversions.**

---

## Content Types

### 1. Product Reviews (`/category/{cat}/reviews/{slug}`)

Individual product review pages with:
- Gradient hero section with product name, rating, and CTA
- Overall rating + sub-ratings (ingredients, value, effectiveness, experience)
- Star visualization with progress bars
- Pros and cons cards
- Full review content
- Sticky sidebar with product card, CTA button, price, affiliate link
- Related reviews section
- Mobile sticky CTA bar
- Schema.org Product + Review markup

**Monetization:** Affiliate links with customizable CTA buttons (hero + sidebar + mobile bar)

### 2. Comparison Listicles (`/category/{cat}/top/{slug}`)

"Top 10 Best X" comparison guides with:
- Ranked product cards with ordinal badges (#1, #2, etc.)
- Brand logos and product images
- Star ratings and feature lists per product
- CTA buttons per product
- Side-by-side comparison table
- Introduction and conclusion sections
- Sidebar with trust signals, methodology, research stats
- Schema.org ItemList markup

**Monetization:** Multiple affiliate links per listicle

### 3. Articles (`/category/{cat}/{slug}`)

Informational blog content with:
- Hero section with category badges
- Featured images
- Full article content with internal linking
- Author attribution and publication date
- Related reviews cross-linking (drives traffic to money pages)
- Related articles section
- Sidebar with category navigation and recent content
- Schema.org Article markup

**Monetization:** Internal links to reviews and listicles

### 4. Categories (`/category/{slug}`)

Topic-based content hubs with:
- Hero section with category description
- Tabbed content: articles, reviews, listicles
- Category statistics

**Purpose:** SEO structure, user navigation

### 5. Static Pages (`/{slug}`)

Database-driven pages (about, privacy, terms) with:
- Hero section with page title
- Full-width content area

---

## Platform Features

### Search
Full-text search across articles, reviews, and listicles with category filtering.

### Newsletter Signup
Footer CTA form captures name + email, submits to webhook for lead collection.

### Webhook Integration
Lead submission API (`/api/submit.php`) sends form data to Stealth Labz portal via cURL webhook.

### Homepage Feed Filtering
Guide categories (city-guide, state-guide) are excluded from homepage feeds to keep the homepage focused on product content. Guide content remains accessible via category pages and search.

---

## Revenue Model

### Affiliate Monetization

| Element | Location | Purpose |
|---------|----------|---------|
| CTA Button | Review hero | Above-fold conversion |
| CTA Button | Review sidebar (sticky) | Persistent conversion |
| Mobile CTA Bar | Review footer (mobile) | Mobile conversion |
| Product Cards | Listicle items | Multiple conversion points |
| "Check Price" | Listicle items | Direct product links |

### Scalability

- **Multi-site support** — Single codebase, multiple domains via `content_sites`
- **Category system** — Unlimited content organization
- **Template partials** — Consistent styling, easy updates

---

## Competitive Advantages

### 1. No WordPress
No plugin conflicts, no security vulnerabilities, no bloat. Clean PHP MVC that does exactly what's needed.

### 2. Zero Dependencies
No npm, no Composer, no build step. Runs on any $5/month shared hosting.

### 3. Database-Driven Content
Proper relational database for content, categories, and relationships. Easy to query, filter, and extend.

### 4. Optimized for Affiliate
Every template designed with conversions in mind — CTA buttons above the fold, trust signals in sidebars, rating visualizations, price displays.

### 5. SEO-Ready
- Clean category-based URL structure
- XML sitemap index with sub-sitemaps (articles, reviews, listicles, categories, pages)
- Schema.org markup on all content types (Product, Review, ItemList, Article)
- Open Graph + Twitter Card meta tags
- Proper heading hierarchy

### 6. Design System
Consistent teal/amber/slate design system with CSS tokens, shared hero sections, section eyebrows, and standardized badge palette across all pages. Zero inline styles.

---

## Features

- [x] Product review pages with ratings and affiliate CTAs
- [x] Comparison listicles with ranked products
- [x] Article system with categories
- [x] Category pages with tabbed content
- [x] Static pages (about, privacy, terms)
- [x] Full-text search with category filtering
- [x] Newsletter signup with webhook integration
- [x] Responsive design (Bootstrap 5.3.3)
- [x] Teal/amber/slate design system with CSS tokens
- [x] XML sitemap index with sub-sitemaps
- [x] Pagination and sorting on index pages
- [x] Star rating visualization with sub-ratings
- [x] Pros/cons display
- [x] Featured images
- [x] Related content recommendations (sidebar + cross-linking)
- [x] Schema.org markup (Product, Review, ItemList, Article)
- [x] Open Graph + Twitter Cards
- [x] Mobile sticky CTA bar on reviews
- [x] Breadcrumb navigation
- [x] Cookie consent banner
- [x] GTM integration
- [x] GitHub Actions auto-deploy
- [x] Legacy URL 301 redirects
