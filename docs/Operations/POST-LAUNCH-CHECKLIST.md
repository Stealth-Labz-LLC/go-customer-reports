# Post-Launch Checklist

**Run this checklist after every site update goes live.**

---

## Phase 1: Functional Audit

### 1.1 Core Pages
- [ ] Homepage loads — all sections render
- [ ] `/articles` — article listing with pagination
- [ ] `/reviews` — review listing with pagination and sorting
- [ ] `/categories` — category listing page
- [ ] `/category/{slug}` — category page shows articles, reviews, listicles
- [ ] `/category/{cat}/{slug}` — single article page loads
- [ ] `/category/{cat}/reviews/{slug}` — single review page loads
- [ ] `/category/{cat}/top/{slug}` — single listicle page loads
- [ ] `/search` — search page loads and returns results
- [ ] `/about-us`, `/privacy`, `/terms` — static pages load
- [ ] `/sitemap.xml` — generates valid XML sitemap index
- [ ] `/sitemap-articles.xml` — sub-sitemap generates
- [ ] `/robots.txt` — generates valid robots file

### 1.2 Content Display
- [ ] Featured images display correctly
- [ ] Star ratings render properly
- [ ] Sub-ratings with progress bars display
- [ ] Pros/cons lists display
- [ ] CTA buttons visible and styled
- [ ] Affiliate links working (open in new tab, nofollow)
- [ ] Pagination working on index pages
- [ ] Breadcrumbs display on content pages

### 1.3 Responsiveness
- [ ] Homepage on mobile viewport
- [ ] Review pages on mobile (sticky CTA bar visible)
- [ ] Listicle pages on mobile
- [ ] Navigation hamburger menu works on mobile
- [ ] Images scale properly

### 1.4 Error Handling
- [ ] Invalid slug returns styled 404 page
- [ ] No PHP errors in browser
- [ ] No broken images (check console)

---

## Phase 2: UI/UX

### 2.1 Header
- [ ] Logo displays correctly
- [ ] Navigation links: Home, Articles, Reviews, Categories, About
- [ ] Search bar functional
- [ ] Mobile menu functions
- [ ] Consistent across all pages

### 2.2 Footer
- [ ] Newsletter signup form submits successfully
- [ ] Footer links work (About, Privacy, Terms)
- [ ] Copyright year current
- [ ] Consistent across all pages

### 2.3 Design System
- [ ] Hero sections use `.hero-section` (home) or `.hero-section-simple` (inner)
- [ ] Section eyebrows display correctly
- [ ] Badge palette consistent (bg-success, bg-amber, bg-dark)
- [ ] No inline styles in templates (except dynamic/framework-required)
- [ ] Teal/amber/slate color scheme consistent

### 2.4 Content Cards
- [ ] Review cards display uniformly (vertical and horizontal)
- [ ] Article cards display uniformly
- [ ] Placeholder icons show for missing images
- [ ] Hover states work

### 2.5 Sidebars
- [ ] Review sidebar sticky and displays correctly
- [ ] Listicle sidebar widgets all render
- [ ] CTA buttons prominent
- [ ] Trust signals visible

---

## Phase 3: Integrations

### 3.1 Newsletter Signup
- [ ] Footer form captures name + email
- [ ] Form submits to `/api/submit.php`
- [ ] Success/error messages display
- [ ] Data reaches webhook endpoint

### 3.2 Webhook
- [ ] `api/webhook-helper.php` loads config from secrets
- [ ] Lead data formatted correctly (E.164 phone, ISO date)
- [ ] Webhook POST succeeds (check Stealth Labz portal)

### 3.3 GTM
- [ ] GTM container loads (check page source)
- [ ] GTM noscript iframe present

### 3.4 Cookie Banner
- [ ] Banner appears on first visit
- [ ] Accept button hides banner
- [ ] Consent persisted in cookie

---

## Phase 4: SEO

### 4.1 Meta Tags
- [ ] Each page has unique title
- [ ] Each page has meta description
- [ ] Titles follow pattern: "Page | Site Name"
- [ ] Open Graph tags present (og:title, og:description, og:image)
- [ ] Twitter Card tags present

### 4.2 URL Structure
- [ ] URLs are category-based (`/category/{cat}/...`)
- [ ] Legacy URLs 301 redirect correctly
- [ ] Canonical URLs consistent

### 4.3 Sitemap & Robots
- [ ] `/sitemap.xml` returns sitemap index with 5 sub-sitemaps
- [ ] All sub-sitemaps generate valid XML
- [ ] All content types included with category URLs
- [ ] Draft pages excluded from sitemap
- [ ] `/robots.txt` blocks campaign directories

### 4.4 Schema.org
- [ ] Reviews have Product + Review schema
- [ ] Listicles have ItemList schema
- [ ] Articles have Article schema

---

## Phase 5: Security

### 5.1 Input/Output
- [ ] All output escaped with `htmlspecialchars()`
- [ ] All queries use prepared statements
- [ ] No raw user input in SQL

### 5.2 Configuration
- [ ] `secrets.php` not in git
- [ ] Error display off in production
- [ ] Security headers in `.htaccess`

### 5.3 Files
- [ ] No sensitive files web-accessible
- [ ] `/config/` protected
- [ ] `/storage/` protected

---

## Phase 6: Git Hygiene

- [ ] No uncommitted changes
- [ ] No sensitive files committed
- [ ] `.gitignore` up to date
- [ ] GitHub Actions deploying successfully

---

## Completion Criteria

A site update is "production ready" when:

1. **Functional** — All pages load without errors
2. **Content** — Images display, links work, affiliate CTAs functional
3. **Responsive** — Works on mobile and desktop
4. **Integrations** — Newsletter, webhook, GTM all working
5. **SEO** — Meta tags, sitemap, schema, clean URLs
6. **Secure** — Escaping, prepared statements, headers
7. **Clean** — No uncommitted changes, no secrets in git
