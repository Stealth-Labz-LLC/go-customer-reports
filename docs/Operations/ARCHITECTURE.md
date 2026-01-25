# Customer Reports - Architecture

Detailed technical architecture of the platform.

---

## Request Lifecycle

```
1. Request hits index.php (front controller)
2. Bootstrap loads autoloader + config
3. Site model fetches site config from database
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

Category-based URL structure with automatic redirects from legacy URLs:

```php
switch (true) {
    case $path === '/':
        $this->home();
        break;

    // === CATEGORY-BASED URLS ===

    // /category/{cat}/reviews/{slug}
    case preg_match('#^/category/([a-zA-Z0-9\-_]+)/reviews/([a-zA-Z0-9\-_]+)$#i', $path, $m):
        $this->reviewShow(strtolower($m[2]), strtolower($m[1]));
        break;

    // /category/{cat}/top/{slug}
    case preg_match('#^/category/([a-zA-Z0-9\-_]+)/top/([a-zA-Z0-9\-_]+)$#i', $path, $m):
        $this->listicleShow(strtolower($m[2]), strtolower($m[1]));
        break;

    // /category/{cat}/{slug} (articles)
    case preg_match('#^/category/([a-zA-Z0-9\-_]+)/([a-zA-Z0-9\-_]+)$#i', $path, $m):
        $this->articleShow(strtolower($m[2]), strtolower($m[1]));
        break;

    // /category/{slug}
    case preg_match('#^/category/([a-zA-Z0-9\-_]+)$#i', $path, $m):
        $this->categoryShow(strtolower($m[1]));
        break;

    // === LEGACY REDIRECTS (301) ===
    case preg_match('#^/articles/([a-zA-Z0-9\-_]+)$#i', $path, $m):
        $this->redirectArticle(strtolower($m[1]));
        break;

    // ... etc
}
```

### Controller Methods

Each route has a corresponding method that:
1. Gets the site object
2. Queries the database via Model
3. Handles 404 if not found
4. Renders the template with data

```php
private function reviewShow(string $slug): void
{
    $site = $this->site;
    $review = \App\Models\Review::findBySlug($site->id, $slug);

    if (!$review) {
        $this->notFound();
        return;
    }

    $review->pros = json_decode($review->pros, true) ?? [];
    $review->cons = json_decode($review->cons, true) ?? [];
    $reviewCategories = \App\Models\Review::getCategories($review->id);

    $this->render('reviews/show', compact('site', 'review', 'reviewCategories'));
}
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

    public static function latest(int $siteId, int $limit = 12, int $offset = 0): array
    {
        $db = Database::getInstance();
        return $db->fetchAll(
            "SELECT * FROM content_reviews WHERE site_id = ? AND status = 'published'
             ORDER BY published_at DESC LIMIT ? OFFSET ?",
            [$siteId, $limit, $offset]
        );
    }
}
```

### Available Models

| Model | Table | Key Methods |
|-------|-------|-------------|
| Article | content_articles | findBySlug, latest, byCategory, count, getCategories |
| Review | content_reviews | findBySlug, latest, byCategory, count, getCategories |
| Listicle | content_listicles | findBySlug, latest, count |
| Category | content_categories | findBySlug, all, topLevel |
| Page | content_pages | findBySlug |
| Site | content_sites | findByDomain |

---

## Database

**File:** `app/Core/Database.php`

PDO wrapper with singleton pattern.

### Connection

```php
class Database
{
    private static ?Database $instance = null;
    private PDO $pdo;

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            $secrets = require dirname(__DIR__, 2) . '/config/secrets.php';
            self::$instance = new self(
                $secrets['db_host'],
                $secrets['db_name'],
                $secrets['db_user'],
                $secrets['db_pass']
            );
        }
        return self::$instance;
    }
}
```

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

```php
<?php
$pageTitle = 'Product Reviews | ' . $site->name;
$metaDescription = 'Honest product reviews...';
ob_start();
?>

<!-- Page content here -->

<?php
$__content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
```

### Layout Wrapper (`layouts/app.php`)

```php
<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <meta name="description" content="<?= htmlspecialchars($metaDescription) ?>">
    <!-- CSS, fonts, etc. -->
</head>
<body>
    <?php require __DIR__ . '/../partials/header.php'; ?>

    <?= $__content ?>

    <?php require __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
```

### Partials

Reusable components included with `require`:

| Partial | Purpose |
|---------|---------|
| `header.php` | Site navigation |
| `footer.php` | Site footer |
| `review-card.php` | Review card for listings |
| `article-card.php` | Article card for listings |
| `rating-stars.php` | Star rating display |
| `listicle-sidebar.php` | Listicle page sidebar |

---

## Database Schema

### content_sites

```sql
CREATE TABLE content_sites (
    id INT PRIMARY KEY AUTO_INCREMENT,
    domain VARCHAR(255) UNIQUE,
    name VARCHAR(255),
    tagline TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### content_reviews

```sql
CREATE TABLE content_reviews (
    id INT PRIMARY KEY AUTO_INCREMENT,
    site_id INT,
    slug VARCHAR(255),
    name VARCHAR(255),
    short_description TEXT,
    content LONGTEXT,
    featured_image VARCHAR(500),
    affiliate_url VARCHAR(500),
    cta_text VARCHAR(100),
    price VARCHAR(50),
    rating_overall DECIMAL(2,1),
    rating_ingredients DECIMAL(2,1),
    rating_value DECIMAL(2,1),
    rating_effectiveness DECIMAL(2,1),
    rating_customer_experience DECIMAL(2,1),
    pros JSON,
    cons JSON,
    meta_title VARCHAR(255),
    meta_description TEXT,
    status ENUM('draft','published','scheduled'),
    published_at DATETIME,
    author_name VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY site_slug (site_id, slug)
);
```

### content_listicles

```sql
CREATE TABLE content_listicles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    site_id INT,
    slug VARCHAR(255),
    title VARCHAR(255),
    excerpt TEXT,
    introduction LONGTEXT,
    conclusion LONGTEXT,
    featured_image VARCHAR(500),
    items JSON,  -- Array of ranked products
    meta_title VARCHAR(255),
    meta_description TEXT,
    status ENUM('draft','published','scheduled'),
    published_at DATETIME,
    author_name VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY site_slug (site_id, slug)
);
```

### Listicle Items JSON Structure

```json
[
    {
        "rank": 1,
        "badge": "Best Overall",
        "name": "Product Name",
        "brand": "Brand Name",
        "brand_logo": "/uploads/brand-logo.png",
        "rating": 4.8,
        "features": ["Feature 1", "Feature 2"],
        "affiliate_url": "https://...",
        "cta_text": "Check Price"
    }
]
```

---

## Security

### SQL Injection Prevention

All queries use PDO prepared statements:

```php
$db->fetchOne(
    "SELECT * FROM reviews WHERE slug = ?",
    [$slug]  // Parameter binding
);
```

### XSS Prevention

All output escaped:

```php
<?= htmlspecialchars($review->name) ?>
```

### CSRF Protection

Token generation and validation in `Security` class for forms.

---

## Multi-Site Support

The platform supports multiple sites from a single codebase:

1. Request comes in for `customer-reports.org`
2. `Site::findByDomain()` looks up site config
3. All queries filtered by `site_id`
4. Templates use `$site->name`, `$site->tagline`, etc.

To add a new site:
1. Insert row into `content_sites`
2. Point domain to same hosting
3. Content automatically filtered by site_id
