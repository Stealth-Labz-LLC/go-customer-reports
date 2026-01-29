# Customer Reports - Architecture

Detailed technical architecture of the platform.

---

## Request Lifecycle

```
1. Request hits index.php (front controller)
2. Bootstrap loads autoloader + config
3. Site model fetches site config from database by domain
4. Router parses URL, matches route pattern
5. Router calls appropriate method (e.g., reviewShow)
6. Method queries database via Model
7. Method passes data to template
8. Template renders HTML with layout wrapper
9. Response sent to browser
```

---

## Router

**File:** `app/Core/Router.php`

The Router is a single class that handles all URL routing and acts as the controller layer.

### Route Patterns

Category-based URL structure with search, sitemaps, and legacy redirects:

```php
switch (true) {
    case $path === '/':
        $this->home();
        break;

    case $path === '/search':
        $this->searchPage();
        break;

    // === CONTENT BROWSE ===
    case $path === '/articles':
        $this->articleIndex();
        break;
    case $path === '/reviews':
        $this->reviewIndex();
        break;

    // === CATEGORY-BASED URLS ===
    case preg_match('#^/category/([...]+)/reviews/([...]+)$#i', $path, $m):
        $this->reviewShow(strtolower($m[2]), strtolower($m[1]));
        break;
    case preg_match('#^/category/([...]+)/top/([...]+)$#i', $path, $m):
        $this->listicleShow(strtolower($m[2]), strtolower($m[1]));
        break;
    case preg_match('#^/category/([...]+)/([...]+)$#i', $path, $m):
        $this->articleShow(strtolower($m[2]), strtolower($m[1]));
        break;
    case preg_match('#^/category/([...]+)$#i', $path, $m):
        $this->categoryShow(strtolower($m[1]));
        break;

    // === SITEMAPS ===
    case $path === '/sitemap.xml':
        $this->sitemapIndex();
        break;
    case preg_match('#^/sitemap-(articles|reviews|listicles|categories|pages)\.xml$#', $path, $m):
        $this->sitemapSection($m[1]);
        break;

    // === LEGACY REDIRECTS (301) ===
    case preg_match('#^/articles/([...]+)$#i', $path, $m):
        $this->redirectArticle(strtolower($m[1]));
        break;
    // ... reviews, top redirects

    // === STATIC PAGES (catch-all) ===
    default:
        $slug = ltrim($path, '/');
        $this->pageShow($slug);
}
```

### Homepage Feed Filtering

The `home()` method excludes guide categories from all feeds:

```php
$excludeSlugs = ['city-guide', 'state-guide'];
$latestArticles = Article::latest($siteId, 6, 0, $excludeSlugs);
$latestReviews = Review::latest($siteId, 4, 0, $excludeSlugs);
$latestListicles = Listicle::latest($siteId, 4, 0, $excludeSlugs);
```

---

## Models

**Location:** `app/Models/`

Static methods that return database results as objects.

### Pattern

```php
class Review
{
    public static function findBySlug(int $siteId, string $slug): ?object
    {
        $db = Database::getInstance();
        return $db->fetchOne(
            "SELECT * FROM content_reviews WHERE site_id = ? AND slug = ? AND status = 'published'",
            [$siteId, $slug]
        );
    }

    public static function latest(int $siteId, int $limit = 12, int $offset = 0, array $excludeCategorySlugs = []): array
    {
        // Builds dynamic WHERE clause to exclude categories by slug
        // Used by homepage to filter out guide content
    }
}
```

### Available Models

| Model | Table | Key Methods |
|-------|-------|-------------|
| Article | content_articles | findBySlug, latest, byCategory, byCategoryPaginated, count, countByCategory, search, searchCount, getCategories |
| Review | content_reviews | findBySlug, latest, latestPaginated, byCategory, count, countByCategory, getCategories |
| Listicle | content_listicles | findBySlug, latest, count |
| Category | content_categories | findBySlug, all, allWithCounts, topLevel |
| Page | content_pages | findBySlug, all |
| Site | content_sites | findByDomain |

---

## Database

**File:** `app/Core/Database.php`

PDO wrapper with singleton pattern.

### Query Methods

```php
// Single row
$db->fetchOne("SELECT * FROM table WHERE id = ?", [$id]);

// Multiple rows
$db->fetchAll("SELECT * FROM table WHERE status = ?", [$status]);

// Execute (INSERT/UPDATE/DELETE)
$db->query("UPDATE table SET col = ? WHERE id = ?", [$val, $id]);
```

---

## Templates

**Location:** `templates/`

PHP templates with a layout wrapper pattern.

### Layout Pattern

Each page template:
1. Sets `$pageTitle` and `$metaDescription`
2. Starts output buffering with `ob_start()`
3. Outputs page content
4. Captures buffer to `$__content`
5. Includes layout wrapper

### Layout Wrapper (`layouts/app.php`)

Provides: DOCTYPE, head (meta, CSS, fonts, GTM), header partial, `$__content` slot, footer partial, cookie banner, scripts.

### Partials

Reusable components included with `require`:

| Partial | Purpose |
|---------|---------|
| `header.php` | Sticky navbar with nav links + search |
| `footer.php` | Footer with newsletter CTA, site links, copyright |
| `review-card.php` | Review card — vertical (default) or horizontal (3-zone) |
| `article-card.php` | Article card for listings |
| `listicle-sidebar.php` | Listicle page sidebar (browse, compare, stats, trust) |
| `search-bar.php` | Reusable search input (used in header + search page) |
| `breadcrumbs.php` | Breadcrumb navigation for content pages |

---

## Database Schema

### content_sites

| Column | Type | Purpose |
|--------|------|---------|
| id | INT PK | Site ID |
| domain | VARCHAR(255) UNIQUE | Domain for routing |
| name | VARCHAR(255) | Display name |
| tagline | TEXT | Site tagline |
| gtm_container_id | VARCHAR(50) | Google Tag Manager ID |

### content_reviews

| Column | Type | Purpose |
|--------|------|---------|
| id | INT PK | Review ID |
| site_id | INT FK | Site reference |
| slug | VARCHAR(255) | URL slug |
| name | VARCHAR(255) | Product name |
| brand | VARCHAR(255) | Brand name |
| short_description | TEXT | Summary text |
| content | LONGTEXT | Full review HTML |
| featured_image | VARCHAR(500) | Image path |
| affiliate_url | VARCHAR(500) | Affiliate link |
| cta_text | VARCHAR(100) | Button text |
| price | VARCHAR(50) | Display price |
| rating_overall | DECIMAL(2,1) | Overall rating (0-5) |
| rating_ingredients | DECIMAL(2,1) | Sub-rating |
| rating_value | DECIMAL(2,1) | Sub-rating |
| rating_effectiveness | DECIMAL(2,1) | Sub-rating |
| rating_customer_experience | DECIMAL(2,1) | Sub-rating |
| pros | JSON | Array of pros |
| cons | JSON | Array of cons |
| status | ENUM | draft, published, scheduled |
| primary_category_id | INT FK | Primary category |

### content_listicles

Items stored as JSON array in `items` column:

```json
[
    {
        "rank": 1,
        "badge": "Best Overall",
        "name": "Product Name",
        "brand": "Brand Name",
        "brand_logo": "/uploads/brand-logo.png",
        "image": "/uploads/product.jpg",
        "product_image": "/uploads/product-photo.jpg",
        "rating": 4.8,
        "features": ["Feature 1", "Feature 2"],
        "savings": "15%",
        "affiliate_url": "https://...",
        "cta_text": "Check Price"
    }
]
```

### content_pages

Database-driven static pages. Pages with `status = 'published'` are accessible at `/{slug}`. Drafting a page (setting status to `draft`) removes it from the site and sitemap automatically.

---

## Security

### SQL Injection Prevention
All queries use PDO prepared statements with parameter binding.

### XSS Prevention
All output escaped with `htmlspecialchars()`.

### CSRF Protection
Token generation and validation in `Security` class for forms.

---

## Multi-Site Support

The platform supports multiple sites from a single codebase:

1. Request comes in for `customer-reports.org`
2. `Site::findByDomain()` looks up site config
3. All queries filtered by `site_id`
4. Templates use `$site->name`, `$site->tagline`, etc.
