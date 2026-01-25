# Customer Reports - Technology

A high-level overview of the technology stack, architecture, and design philosophy.

---

## Tech Stack

| Layer | Technology | Notes |
|-------|------------|-------|
| **Backend** | PHP 7.4+ | Custom MVC framework, no dependencies |
| **Frontend** | Bootstrap 5 + Vanilla JS | Responsive, no build step |
| **CSS** | Custom CSS with design tokens | CSS variables for theming |
| **Templates** | PHP server-side rendering | Templates + reusable partials |
| **Database** | MySQL | Multi-site content storage |
| **Deployment** | GitHub Actions | Auto-deploy via SFTP |
| **Images** | File-based | `/uploads/` directory |

---

## Architecture

Custom **MVC framework** with database-driven content:

```
REQUEST → Front Controller → Router → Controller Method → Model → Template → RESPONSE
```

### Core Principle: Zero Dependencies

No Composer, no npm, no build step. The entire codebase is self-contained and runs on any PHP hosting with MySQL.

### Key Architectural Components

| Component | Files | Purpose |
|-----------|-------|---------|
| Router | 1 | URL matching, dispatches to controller methods |
| Models | 6 | Article, Review, Listicle, Category, Page, Site |
| Core Framework | 5 | Database, Router, Security, LeadStorage, Logger |
| Templates | 17 | Pages + reusable partials |
| Config | 2 | Environment detection, secrets |

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
│   /  →  home()  |  /reviews  →  reviewIndex()           │
│   /reviews/{slug}  →  reviewShow()  |  etc.             │
└─────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────┐
│                    Model Layer                           │
│     Review::findBySlug()  |  Article::latest()          │
│              Database queries via PDO                    │
└─────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────┐
│              Template + Data → HTML                      │
│     reviews/show.php  |  partials/review-card.php       │
└─────────────────────────────────────────────────────────┘
```

---

## Database Schema

### Core Tables

| Table | Purpose |
|-------|---------|
| `content_sites` | Multi-site support (domain, name, tagline) |
| `content_articles` | Blog posts / informational content |
| `content_reviews` | Product reviews with ratings |
| `content_listicles` | "Top X" comparison lists |
| `content_categories` | Content categorization |
| `content_pages` | Static pages |

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
├── config/                 # Configuration
│   ├── environment.php     # Environment detection
│   └── secrets.php         # DB credentials (gitignored)
├── templates/              # Views
│   ├── layouts/app.php     # Master layout
│   ├── partials/           # Reusable components
│   ├── articles/           # Article templates
│   ├── reviews/            # Review templates
│   ├── listicles/          # Listicle templates
│   ├── categories/         # Category templates
│   └── pages/              # Static page templates
├── css/style.css           # All styles
├── images/                 # Static images (logo)
├── uploads/                # User-uploaded content
├── api/                    # Lead submission endpoint
├── storage/                # Logs (gitignored)
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
| Bootstrap 5 | Responsive out of the box, familiar |
| CSS variables | Runtime theming, easy customization |
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

---

*For detailed technical implementation, see [Operations/ARCHITECTURE.md](Operations/ARCHITECTURE.md)*
