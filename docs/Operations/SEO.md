# Customer Reports - SEO

Technical SEO implementation details.

---

## URL Structure

All content uses category-based URLs for SEO hierarchy:

| Content Type | URL Pattern | Example |
|--------------|-------------|---------|
| Article | `/category/{cat}/{slug}` | `/category/nutrition/protein-guide` |
| Review | `/category/{cat}/reviews/{slug}` | `/category/supplements/reviews/whey-protein` |
| Listicle | `/category/{cat}/top/{slug}` | `/category/fitness/top/best-treadmills` |
| Category | `/category/{slug}` | `/category/health-wellness` |
| Static Page | `/{slug}` | `/privacy` |

### 301 Redirects

Old WordPress-style URLs automatically redirect:

| Old URL | Redirects To |
|---------|--------------|
| `/articles/{slug}` | `/category/{cat}/{slug}` |
| `/reviews/{slug}` | `/category/{cat}/reviews/{slug}` |
| `/top/{slug}` | `/category/{cat}/top/{slug}` |
| `/articles` | `/categories` |
| `/reviews` | `/categories` |
| `/top` | `/categories` |

---

## Schema.org Markup

JSON-LD structured data is automatically generated for all content types.

### Product Reviews (`reviews/show.php`)

```json
{
  "@context": "https://schema.org",
  "@type": "Product",
  "name": "Product Name",
  "review": {
    "@type": "Review",
    "reviewRating": {
      "@type": "Rating",
      "ratingValue": "4.5",
      "bestRating": "5"
    },
    "author": {
      "@type": "Person",
      "name": "Author Name"
    }
  },
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "4.5",
    "bestRating": "5",
    "ratingCount": "1"
  }
}
```

### Listicles (`listicles/show.php`)

```json
{
  "@context": "https://schema.org",
  "@type": "ItemList",
  "name": "Top 10 Best Products",
  "numberOfItems": 10,
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "name": "Product Name",
      "url": "/category/cat/reviews/product-slug"
    }
  ]
}
```

### Articles (`articles/show.php`)

```json
{
  "@context": "https://schema.org",
  "@type": "Article",
  "headline": "Article Title",
  "author": {
    "@type": "Person",
    "name": "Author Name"
  },
  "publisher": {
    "@type": "Organization",
    "name": "Customer Reports"
  },
  "datePublished": "2026-01-25T00:00:00+00:00"
}
```

---

## Meta Tags

### Standard Meta

All pages include via `layouts/app.php`:

```html
<title>Page Title | Site Name</title>
<meta name="description" content="Page description">
<link rel="canonical" href="https://customer-reports.org/path">
```

### Open Graph

```html
<meta property="og:title" content="Page Title">
<meta property="og:description" content="Description">
<meta property="og:site_name" content="Customer Reports">
<meta property="og:type" content="website|article|product">
<meta property="og:url" content="https://customer-reports.org/path">
<meta property="og:image" content="https://customer-reports.org/image.jpg">
```

**og:type values:**
- `website` - Homepage, category pages
- `article` - Articles
- `product` - Reviews

### Twitter Cards

```html
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Page Title">
<meta name="twitter:description" content="Description">
<meta name="twitter:image" content="https://customer-reports.org/image.jpg">
```

---

## Sitemap

**URL:** `/sitemap.xml`

Dynamically generated via `Router::sitemap()`. Includes:

- Homepage
- All categories
- All articles (with category URLs)
- All reviews (with category URLs)
- All listicles (with category URLs)

### Format

```xml
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
    <loc>https://customer-reports.org/</loc>
    <changefreq>daily</changefreq>
    <priority>1.0</priority>
  </url>
  <url>
    <loc>https://customer-reports.org/category/nutrition/protein-guide</loc>
  </url>
</urlset>
```

---

## robots.txt

**URL:** `/robots.txt`

Dynamically generated via `Router::robotsTxt()`.

```
# robots.txt for Customer Reports

User-agent: *
Allow: /

# Sitemap
Sitemap: https://customer-reports.org/sitemap.xml

# Disallow admin/API paths
Disallow: /api/
Disallow: /cli/
Disallow: /config/
Disallow: /app/

# Disallow campaign/funnel directories
Disallow: /cr/
Disallow: /eb/
Disallow: /ee25/
Disallow: /qr/
Disallow: /sc/
Disallow: /ss/
```

---

## Favicon

**Files:**
- `/favicon.svg` - Primary (all modern browsers)
- `/favicon-32x32.png` - Fallback
- `/favicon-16x16.png` - Fallback
- `/apple-touch-icon.png` - iOS

Favicon is the Customer Reports shield icon extracted from the logo.

---

## Internal Linking

Automated internal linking via `cli/add-internal-links.php`:

- Scans article content for category keywords
- Links keywords to relevant articles in matching categories
- Max 3 cross-category links per article
- Keywords mapped: "weight loss", "nutrition", "workout", "wellness", etc.

**Usage:**
```bash
php cli/add-internal-links.php --dry-run
php cli/add-internal-links.php
```

---

## Campaign/Funnel Protection

Campaign directories (`/cr/`, `/eb/`, `/ee25/`, `/qr/`, `/sc/`, `/ss/`) are protected from indexing:

1. **robots.txt** - `Disallow` rules for all directories
2. **Meta tags** - `<meta name="robots" content="noindex, nofollow">` in all PHP files

---

## Page Variables

Templates can set these variables before including `layouts/app.php`:

| Variable | Purpose | Default |
|----------|---------|---------|
| `$pageTitle` | `<title>` tag | `$site->name` |
| `$metaDescription` | Meta description | `$site->tagline` |
| `$ogImage` | Open Graph image | `null` |
| `$ogType` | Open Graph type | `website` |

**Example:**
```php
<?php
$pageTitle = $review->name . ' Review | ' . $site->name;
$metaDescription = $review->short_description;
$ogImage = $review->featured_image;
$ogType = 'product';
```

---

*SEO implementation completed January 2026.*
