# Customer Reports - SEO

Technical SEO implementation.

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

Old URLs automatically redirect to category-based structure:

| Old URL | Redirects To |
|---------|--------------|
| `/articles/{slug}` | `/category/{cat}/{slug}` |
| `/reviews/{slug}` | `/category/{cat}/reviews/{slug}` |
| `/top/{slug}` | `/category/{cat}/top/{slug}` |

---

## Schema.org Markup

JSON-LD structured data is generated for all content types.

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
    "author": { "@type": "Person", "name": "Author Name" }
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
    { "@type": "ListItem", "position": 1, "name": "Product Name" }
  ]
}
```

### Articles (`articles/show.php`)

```json
{
  "@context": "https://schema.org",
  "@type": "Article",
  "headline": "Article Title",
  "author": { "@type": "Person", "name": "Author Name" },
  "publisher": { "@type": "Organization", "name": "Customer Reports" }
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
- `website` — Homepage, category pages
- `article` — Articles
- `product` — Reviews

### Twitter Cards

```html
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Page Title">
<meta name="twitter:description" content="Description">
<meta name="twitter:image" content="https://customer-reports.org/image.jpg">
```

---

## Sitemap

### Sitemap Index

**URL:** `/sitemap.xml`

The sitemap uses an index structure with sub-sitemaps per content type:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <sitemap><loc>https://customer-reports.org/sitemap-articles.xml</loc></sitemap>
  <sitemap><loc>https://customer-reports.org/sitemap-reviews.xml</loc></sitemap>
  <sitemap><loc>https://customer-reports.org/sitemap-listicles.xml</loc></sitemap>
  <sitemap><loc>https://customer-reports.org/sitemap-categories.xml</loc></sitemap>
  <sitemap><loc>https://customer-reports.org/sitemap-pages.xml</loc></sitemap>
</sitemapindex>
```

### Sub-Sitemaps

| Sub-Sitemap | Contents |
|-------------|----------|
| `sitemap-articles.xml` | Homepage + `/articles` browse page + all published articles |
| `sitemap-reviews.xml` | `/reviews` browse page + all published reviews |
| `sitemap-listicles.xml` | All published listicles |
| `sitemap-categories.xml` | `/categories` + all categories |
| `sitemap-pages.xml` | All published static pages |

Each URL includes `<changefreq>` and `<priority>` values. Content URLs include `<lastmod>` when available.

**Note:** Pages with `status = 'draft'` are automatically excluded since the Page model filters by `status = 'published'`.

---

## robots.txt

**URL:** `/robots.txt`

Dynamically generated:

```
User-agent: *
Allow: /

Sitemap: https://customer-reports.org/sitemap.xml

Disallow: /api/
Disallow: /cli/
Disallow: /config/
Disallow: /app/
Disallow: /cr/
Disallow: /eb/
Disallow: /ee25/
Disallow: /qr/
Disallow: /sc/
Disallow: /ss/
```

---

## Favicon

- `/favicon.svg` — Primary (all modern browsers)
- `/favicon-32x32.png` — Fallback
- `/favicon-16x16.png` — Fallback
- `/apple-touch-icon.png` — iOS

---

## Internal Linking

Automated internal linking via `cli/add-internal-links.php`:
- Scans article content for category keywords
- Links keywords to relevant articles in matching categories
- Max 3 cross-category links per article

---

## Campaign/Funnel Protection

Campaign directories (`/cr/`, `/eb/`, `/ee25/`, `/qr/`, `/sc/`, `/ss/`) are protected from indexing:

1. **robots.txt** — `Disallow` rules for all directories
2. **Meta tags** — `<meta name="robots" content="noindex, nofollow">` in all campaign PHP files

---

## Page Variables

Templates set these variables before including `layouts/app.php`:

| Variable | Purpose | Default |
|----------|---------|---------|
| `$pageTitle` | `<title>` tag | `$site->name` |
| `$metaDescription` | Meta description | `$site->tagline` |
| `$ogImage` | Open Graph image | `null` |
| `$ogType` | Open Graph type | `website` |
