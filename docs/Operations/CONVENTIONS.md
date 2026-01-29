# Coding Conventions

## File Organization

### PHP Files
- Models: `app/Models/{Name}.php` (PascalCase, singular)
- Core: `app/Core/{Name}.php` (PascalCase)
- Templates: `templates/{section}/{name}.php` (lowercase)
- Partials: `templates/partials/{name}.php` (lowercase, kebab-case)
- Config: `config/{name}.php` (lowercase)

### Frontend Assets
- CSS: `css/style.css` (single file — all styles)
- Images: `images/` (static) or `uploads/` (user content)

---

## Code Style

### PHP
- Use `<?php` opening tag (never short tags)
- Classes use PascalCase: `Review`, `Database`
- Methods use camelCase: `findBySlug()`
- Variables use camelCase: `$siteId`
- Constants use UPPER_SNAKE_CASE: `BASE_PATH`
- Always use strict comparison (`===`, `!==`)
- Type hints on function parameters when possible

### CSS
- Use CSS custom properties (design tokens) from `:root`
- Component classes: descriptive names (`.hero-section`, `.review-card-h`, `.footer-cta-form-panel`)
- Mobile-first responsive design
- No `!important` unless absolutely necessary
- **No inline styles** — all styling via CSS classes

### HTML/Templates
- Always escape output: `<?= htmlspecialchars($var) ?>`
- Use semantic HTML5 elements
- Include alt text on images
- Use proper heading hierarchy (h1 → h2 → h3)
- Use Bootstrap utility classes for layout and spacing

---

## Design System

### Color Tokens

All colors use CSS custom properties defined in `:root`:

| Token | Value | Usage |
|-------|-------|-------|
| `--cr-teal` | #0d7377 | Primary brand, success, nav |
| `--cr-teal-dark` | #0a5c5f | Hover states |
| `--cr-amber` | #e6a817 | Accent, ratings, CTAs |
| `--cr-amber-dark` | #c48f13 | Hover states |
| `--cr-slate` | #1a2332 | Dark backgrounds, heroes |
| `--cr-gray-50` | #f8f9fa | Light section backgrounds |

```css
/* CORRECT — use tokens */
.component {
    background: var(--cr-teal);
    color: #fff;
}

/* INCORRECT — hardcoded brand colors */
.component {
    background: #0d7377;
}
```

### Hero Sections

| Class | Usage |
|-------|-------|
| `.hero-section` | Homepage — large gradient with overlay, stats row |
| `.hero-section-simple` | All inner pages — compact teal-to-slate gradient |

Every page uses one of these hero classes. No custom one-off hero backgrounds.

### Section Eyebrows

| Class | Usage |
|-------|-------|
| `.section-eyebrow` | Teal uppercase label with underline (default) |
| `.section-eyebrow-amber` | Amber uppercase label (reviews, listicles) |

### Badge Palette

Only these badge styles are used:

| Class | Usage |
|-------|-------|
| `bg-success` | Category tags, positive states |
| `bg-amber text-white` | Rating badges, highlights, editor's choice |
| `bg-dark bg-opacity-10 text-dark` | Neutral/secondary badges |
| `bg-danger` | Low ratings (< 3.0) |

**Banned:** `bg-warning`, `bg-info`, `bg-primary` — not in the design system.

### Button Styles

| Class | Usage |
|-------|-------|
| `btn-success` | Primary CTA (green) |
| `btn-amber` | Amber CTA (newsletter, highlights) |
| `btn-outline-success` | Secondary CTA |
| `btn-outline-light` | CTA on dark backgrounds |
| `btn-outline-amber` | Secondary amber CTA |

### Inline Styles Policy

**No inline styles in templates.** The only acceptable inline styles are:
- Dynamic progress bar `width:` (computed from PHP)
- GTM noscript `display:none;visibility:hidden` (required by Google)
- JS-toggled `display:none` (cookie banner, newsletter message)
- Logo `filter: brightness(0) invert(1)` (dynamic logo inversion)

Everything else must be a CSS class in `css/style.css`.

---

## Error Handling

### PHP
- Use try/catch for database operations
- Log errors, don't expose to users
- Return 404 for missing content
- Never expose stack traces in production

### Database
- Always use prepared statements (PDO)
- Never concatenate user input into queries
- Check for null results before accessing properties

---

## Security Rules

### Input Sanitization
```php
// Escape for HTML output
<?= htmlspecialchars($userInput) ?>

// Use prepared statements for database
$db->fetchOne("SELECT * FROM table WHERE id = ?", [$id]);
```

### General Rules
- Never expose secrets in code or logs
- Keep `config/secrets.php` out of git
- Use parameterized queries for all database access
- Security headers configured in `.htaccess`

---

## Git Workflow

### Branches
- `main` - Production (auto-deploys via GitHub Actions)

### Commit Messages
Use conventional commits:
- `feat:` new feature
- `fix:` bug fix
- `refactor:` code restructuring
- `style:` formatting, CSS
- `docs:` documentation
- `chore:` maintenance

### Never Commit
- `config/secrets.php` (credentials)
- `storage/logs/*`
- `.claude/` folder
- `*.zip` files
- IDE settings

---

## Template Patterns

### Layout Wrapper Pattern

Each page template follows this structure:

```php
<?php
$pageTitle = 'Page Title | ' . $site->name;
$metaDescription = 'Description for SEO';
ob_start();
?>

<!-- Page content here -->

<?php
$__content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
```

### Including Partials

```php
<?php foreach ($reviews as $review): ?>
    <?php require __DIR__ . '/../partials/review-card.php'; ?>
<?php endforeach; ?>
```

### Checking for Empty Data

```php
<?php if (!empty($reviews)): ?>
    <!-- Show reviews -->
<?php else: ?>
    <!-- Show empty state -->
<?php endif; ?>
```

---

## Database Patterns

### Model Methods

Static methods that return objects:

```php
// Single record
$review = Review::findBySlug($siteId, $slug);

// Multiple records (with optional category exclusion for homepage)
$articles = Article::latest($siteId, 6, 0, ['city-guide', 'state-guide']);

// With pagination
$reviews = Review::latestPaginated($siteId, $perPage, $offset, $sort);

// By relationship
$reviews = Review::byCategory($siteId, $categoryId);
```

---

## URL Conventions

### Route Patterns

All content uses category-based URLs:

| Pattern | Example | Handler |
|---------|---------|---------|
| Article | `/category/{cat}/{slug}` | `articleShow($slug, $cat)` |
| Review | `/category/{cat}/reviews/{slug}` | `reviewShow($slug, $cat)` |
| Listicle | `/category/{cat}/top/{slug}` | `listicleShow($slug, $cat)` |
| Category | `/category/{slug}` | `categoryShow($slug)` |
| Static Page | `/{slug}` | `pageShow($slug)` |

### Slug Rules

- Lowercase only
- Hyphens for word separation
- No underscores
- No special characters
