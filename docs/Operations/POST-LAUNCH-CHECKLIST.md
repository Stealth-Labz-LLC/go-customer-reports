# Post-Launch Checklist

**Run this checklist after every site update goes live.** This is the standard process for auditing, hardening, and verifying affiliate review platforms.

> **When to use:** After initial development is complete and the site is live. Every major update should go through this before being considered "done."

---

## Phase 1: Functional Audit

### 1.1 Test Core Pages
- [ ] Homepage loads - all sections render
- [ ] `/categories` - category listing page works
- [ ] `/category/{slug}` - category page shows articles, reviews, listicles
- [ ] `/category/{cat}/{slug}` - single article page loads
- [ ] `/category/{cat}/reviews/{slug}` - single review page loads
- [ ] `/category/{cat}/top/{slug}` - single listicle page loads
- [ ] `/privacy`, `/terms`, `/contact` - static pages load
- [ ] `/sitemap.xml` - generates valid XML
- [ ] `/robots.txt` - generates valid robots file

### 1.2 Test Content Display
- [ ] Featured images display correctly
- [ ] Star ratings render properly
- [ ] Pros/cons lists display
- [ ] CTA buttons visible and styled
- [ ] Affiliate links working
- [ ] Pagination working on index pages

### 1.3 Test Responsiveness
- [ ] Homepage on mobile viewport
- [ ] Review pages on mobile
- [ ] Listicle pages on mobile
- [ ] Navigation works on mobile
- [ ] Images scale properly

### 1.4 Test Error Handling
- [ ] Invalid slug returns 404 page
- [ ] 404 page styled correctly
- [ ] No PHP errors in browser
- [ ] No broken images (check console)

---

## Phase 2: UI/UX Cleanup

### 2.1 Header
- [ ] Logo displays correctly
- [ ] Navigation links work
- [ ] Mobile menu functions
- [ ] Consistent across all pages

### 2.2 Footer
- [ ] Logo displays correctly
- [ ] Links work (Privacy, Terms, etc.)
- [ ] Copyright year current
- [ ] Consistent across all pages

### 2.3 Content Cards
- [ ] Review cards display uniformly
- [ ] Article cards display uniformly
- [ ] Listicle cards display uniformly
- [ ] Placeholder icons show for missing images
- [ ] Hover states work

### 2.4 Sidebars
- [ ] Review sidebar displays correctly
- [ ] Listicle sidebar displays correctly
- [ ] CTA buttons prominent
- [ ] Trust signals visible

---

## Phase 3: Content Verification

### 3.1 Database Content
- [ ] Reviews have featured images
- [ ] Articles have featured images
- [ ] Listicles have items populated
- [ ] Categories have content assigned
- [ ] No orphaned content

### 3.2 Image Paths
- [ ] Images load from `/uploads/`
- [ ] No broken image links
- [ ] No references to old WordPress paths
- [ ] Placeholder icons work for missing images

### 3.3 Affiliate Links
- [ ] CTA buttons have valid URLs
- [ ] Affiliate links open correctly
- [ ] Fallback behavior for missing URLs

---

## Phase 4: SEO Verification

### 4.1 Meta Tags
- [ ] Each page has unique title
- [ ] Each page has meta description
- [ ] Titles follow pattern: "Page | Site Name"
- [ ] Open Graph tags present (og:title, og:description, og:image)
- [ ] Twitter Card tags present

### 4.2 URL Structure
- [ ] URLs are category-based (`/category/{cat}/...`)
- [ ] URLs are lowercase with hyphens
- [ ] Legacy URLs (`/articles/`, `/reviews/`, `/top/`) 301 redirect
- [ ] Canonical URLs consistent

### 4.3 Sitemap & Robots
- [ ] `/sitemap.xml` accessible and valid
- [ ] All content types included with category URLs
- [ ] `/robots.txt` accessible
- [ ] Campaign directories blocked in robots.txt

### 4.4 Schema.org Markup
- [ ] Reviews have Product + Review schema
- [ ] Listicles have ItemList schema
- [ ] Articles have Article schema
- [ ] Test with Google Rich Results Test

### 4.5 Favicon
- [ ] `/favicon.svg` loads
- [ ] Favicon displays in browser tab

---

## Phase 5: Performance Check

### 5.1 Page Load
- [ ] Homepage loads < 3 seconds
- [ ] Review pages load < 3 seconds
- [ ] Images optimized
- [ ] No render-blocking issues

### 5.2 Database
- [ ] Queries performing well
- [ ] No N+1 query issues
- [ ] Indexes on slug columns

---

## Phase 6: Security Check

### 6.1 Input/Output
- [ ] All output escaped with `htmlspecialchars()`
- [ ] All queries use prepared statements
- [ ] No raw user input in SQL

### 6.2 Configuration
- [ ] `secrets.php` not in git
- [ ] Error display off in production
- [ ] Security headers in `.htaccess`

### 6.3 Files
- [ ] No sensitive files web-accessible
- [ ] `/config/` protected
- [ ] `/storage/` protected

---

## Phase 7: Documentation Check

### 7.1 Business Docs
- [ ] OVERVIEW.md current
- [ ] PRODUCT.md current
- [ ] TECHNOLOGY.md current
- [ ] COST.md current
- [ ] OPPORTUNITY.md current

### 7.2 Operations Docs
- [ ] ARCHITECTURE.md current
- [ ] DEPLOYMENT.md current
- [ ] DEVELOPMENT.md current
- [ ] CONVENTIONS.md current
- [ ] This checklist current

---

## Phase 8: Git Hygiene

### 8.1 Repository State
- [ ] No uncommitted changes
- [ ] No sensitive files committed
- [ ] `.gitignore` up to date
- [ ] Recent commits documented

### 8.2 Deployment
- [ ] GitHub Actions working
- [ ] Production matches main branch
- [ ] No failed deployments

---

## Quick Fixes Reference

### Images Not Loading
```sql
-- Check image paths
SELECT slug, featured_image FROM content_reviews WHERE featured_image IS NOT NULL LIMIT 10;

-- Fix WordPress paths
UPDATE content_reviews SET featured_image = REPLACE(featured_image, '/wp-content/uploads/', '/uploads/');
```

### 404 on Valid URLs
- Check Router.php regex patterns
- Ensure case-insensitive matching (`#i` flag)
- Verify slug exists in database

### Blank Page
- Enable error display temporarily
- Check PHP error logs
- Verify database connection

---

## Completion Criteria

A site is "production ready" when:

1. **Functional** - All pages load without errors
2. **Content** - Images display, links work
3. **Responsive** - Works on mobile and desktop
4. **SEO** - Meta tags, sitemap, clean URLs
5. **Secure** - Escaping, prepared statements, headers
6. **Documented** - All docs current
7. **Clean** - No uncommitted changes, no secrets in git

---

*Checklist version: 1.0 | January 2026*
