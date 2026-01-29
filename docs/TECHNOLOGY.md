# Customer Reports - Technology

Tech stack, architecture, and design system.

---

## Tech Stack

| Layer | Technology | Notes |
|-------|------------|-------|
| **Backend** | PHP 8.3 | Custom MVC framework, no dependencies |
| **Frontend** | Bootstrap 5.3.3 + Vanilla JS | Responsive, no build step |
| **CSS** | Custom design system with CSS tokens | Teal/amber/slate palette |
| **Templates** | PHP server-side rendering | Templates + reusable partials |
| **Database** | MySQL | Multi-site content storage |
| **Deployment** | GitHub Actions | Auto-deploy via SFTP on push to `main` |
| **Images** | File-based | `/uploads/` directory, `IMAGE_BASE_URL` config |

---

## Architecture

Custom **MVC framework** with database-driven content:

```
REQUEST → Front Controller → Router → Controller Method → Model → Template → RESPONSE
```

### Core Principle: Zero Dependencies

No Composer, no npm, no build step. The entire codebase is self-contained and runs on any PHP hosting with MySQL.

### Key Components

| Component | Count | Purpose |
|-----------|-------|---------|
| Router | 1 | URL matching, controller methods, sitemap generation |
| Models | 6 | Article, Review, Listicle, Category, Page, Site |
| Core Framework | 5 | Database, Router, Security, LeadStorage, Logger |
| Templates | 18 | Page templates + layouts |
| Partials | 8 | Reusable components (cards, sidebar, header, footer, etc.) |
| Config | 2 | Environment detection, secrets |
| API | 2 | Lead submission endpoint, webhook helper |

---

## Architecture Diagram

```
┌─────────────────────────────────────────────────────────┐
│                        REQUEST                           │
└─────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────┐
│              index.php (Front Controller)                │
│              + .htaccess URL rewriting                   │
└─────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────┐
│                     Router.php                           │
│   /  →  home()                                          │
│   /search  →  searchPage()                              │
│   /category/{cat}/{slug}  →  articleShow()              │
│   /category/{cat}/reviews/{slug}  →  reviewShow()       │
│   /category/{cat}/top/{slug}  →  listicleShow()         │
│   /sitemap.xml  →  sitemapIndex()                       │
│   /sitemap-{section}.xml  →  sitemapSection()           │
│   /robots.txt  →  robotsTxt()                           │
└─────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────┐
│                    Model Layer                           │
│     Review::findBySlug()  |  Article::latest()          │
│     Category::allWithCounts()  |  Page::findBySlug()    │
│              Database queries via PDO                    │
└─────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────┐
│              Template + Data → HTML                      │
│     + Schema.org JSON-LD  |  + Open Graph tags          │
│     layouts/app.php wraps all page templates             │
│     partials/ shared across pages                        │
└─────────────────────────────────────────────────────────┘
```

---

## Design System

### Color Palette (CSS Tokens)

| Token | Value | Usage |
|-------|-------|-------|
| `--cr-teal` | #0d7377 | Primary brand, success states, nav, buttons |
| `--cr-teal-dark` | #0a5c5f | Hover states |
| `--cr-amber` | #e6a817 | Accent highlights, rating badges, CTAs |
| `--cr-amber-dark` | #c48f13 | Hover states |
| `--cr-slate` | #1a2332 | Dark backgrounds, hero gradients |
| `--cr-gray-50` | #f8f9fa | Light backgrounds, alternating sections |

### Hero Sections

| Class | Usage |
|-------|-------|
| `.hero-section` | Homepage — full gradient with overlay |
| `.hero-section-simple` | Inner pages — compact teal-to-slate gradient |

### Section Eyebrows

| Class | Usage |
|-------|-------|
| `.section-eyebrow` | Uppercase label, teal underline |
| `.section-eyebrow-amber` | Uppercase label, amber underline (for reviews/listicles) |

### Badge Palette

| Badge | Usage |
|-------|-------|
| `bg-success` | Category badges, positive states |
| `bg-amber text-white` | Rating badges, highlights |
| `bg-dark bg-opacity-10 text-dark` | Neutral/secondary badges |

**Banned:** `bg-warning`, `bg-info` — not in the design system.

### Utility Classes

| Class | Purpose |
|-------|---------|
| `.btn-amber` | Amber CTA button |
| `.btn-outline-amber` | Amber outline button |
| `.bg-amber` | Amber background |
| `.progress-sm` | Thin progress bars (6px) |
| `.sub-rating-label` | Sub-rating label (min-width: 85px) |
| `.sidebar-sticky` | Sidebar sticky offset |
| `.product-card-img` | Product image in sidebar card |
| `.product-hero-img` | Product image in hero section |
| `.article-hero-img` | Article featured image |

---

## Database Schema

### Core Tables

| Table | Purpose |
|-------|---------|
| `content_sites` | Multi-site support (domain, name, tagline, GTM ID) |
| `content_articles` | Blog posts / informational content |
| `content_reviews` | Product reviews with ratings |
| `content_listicles` | "Top X" comparison lists |
| `content_categories` | Content categorization |
| `content_pages` | Static pages (about, privacy, terms) |

### Junction Tables

| Table | Purpose |
|-------|---------|
| `content_article_category` | Article ↔ Category |
| `content_review_category` | Review ↔ Category |
| `content_listicle_category` | Listicle ↔ Category |

---

## Directory Structure

```
go-customer-reports/
├── index.php               # Front controller
├── .htaccess               # URL rewriting, security headers
├── app/                    # Application logic (MVC)
│   ├── bootstrap.php       # Autoloader, config loading
│   ├── Core/               # Framework classes
│   │   ├── Database.php    # PDO wrapper, singleton
│   │   ├── Router.php      # URL routing + controller methods
│   │   ├── Security.php    # CSRF, sanitization
│   │   ├── LeadStorage.php # Lead backup (JSON)
│   │   └── Logger.php      # Application logging
│   └── Models/             # Database models
│       ├── Article.php
│       ├── Review.php
│       ├── Listicle.php
│       ├── Category.php
│       ├── Page.php
│       └── Site.php
├── api/                    # Lead submission endpoint
│   ├── submit.php          # Form handler
│   └── webhook-helper.php  # Webhook to Stealth Labz portal
├── cli/                    # Command-line scripts
│   ├── add-internal-links.php
│   └── add-noindex.php
├── config/                 # Configuration
│   ├── environment.php     # Environment detection
│   └── secrets.php         # DB + webhook credentials (gitignored)
├── templates/              # Views
│   ├── layouts/app.php     # Master layout (SEO meta, GTM, cookie banner)
│   ├── home.php            # Homepage
│   ├── search.php          # Search results
│   ├── 404.php             # Not found
│   ├── articles/           # Article templates (index, show)
│   ├── reviews/            # Review templates (index, show)
│   ├── listicles/          # Listicle templates (show)
│   ├── categories/         # Category templates (index, show)
│   ├── pages/              # Static page templates (default)
│   └── partials/           # Reusable components
│       ├── header.php
│       ├── footer.php
│       ├── review-card.php     # Vertical + horizontal card
│       ├── article-card.php
│       ├── listicle-sidebar.php
│       ├── search-bar.php
│       └── breadcrumbs.php
├── css/style.css           # All styles — design system + components
├── images/                 # Static images (logo)
├── uploads/                # User-uploaded content
├── storage/                # Logs (gitignored)
├── cr/, eb/, ee25/, qr/, sc/, ss/  # Campaign funnels (noindex)
└── .github/workflows/      # CI/CD pipeline
```

---

## Key Technical Decisions

| Decision | Rationale |
|----------|-----------|
| No WordPress | Clean architecture, faster, no plugin bloat |
| No framework (Laravel, etc.) | Minimal footprint, runs anywhere |
| MySQL database | Proper content management, relationships |
| PHP templates | Simple, no build step, fast rendering |
| Bootstrap 5.3.3 | Responsive out of the box, familiar |
| CSS design tokens | Runtime theming, consistent palette |
| Zero inline styles | All styling via CSS classes |
| SFTP deployment | Simple, reliable for cPanel hosting |

---

## Security

| Feature | Implementation |
|---------|----------------|
| SQL Injection | PDO prepared statements |
| XSS Prevention | `htmlspecialchars()` on all output |
| CSRF Protection | Token validation on forms |
| Input Sanitization | `Security::sanitize()` for all input |
| Security Headers | CSP, X-Frame-Options in .htaccess |
| Campaign Protection | noindex meta + robots.txt Disallow |

---

*For detailed technical implementation, see [Operations/ARCHITECTURE.md](Operations/ARCHITECTURE.md)*
