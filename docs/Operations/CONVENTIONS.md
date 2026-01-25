# Coding Conventions

## File Organization

### PHP Files
- Models: `app/Models/{Name}.php` (PascalCase, singular)
- Core: `app/Core/{Name}.php` (PascalCase)
- Templates: `templates/{section}/{name}.php` (lowercase)
- Partials: `templates/partials/{name}.php` (lowercase, kebab-case)
- Config: `config/{name}.php` (lowercase)

### Frontend Assets
- CSS: `css/style.css` (single file)
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
- BEM-ish naming: `.cr-component`, `.cr-component-element`
- Prefix all classes with `cr-` for namespacing
- Mobile-first responsive design
- No `!important` unless absolutely necessary

### HTML/Templates
- Always escape output: `<?= htmlspecialchars($var) ?>`
- Use semantic HTML5 elements
- Include alt text on images
- Use proper heading hierarchy (h1 → h2 → h3)

---

## CSS Token System

### Required: Use Design Tokens

All CSS values should use tokens from `:root`:

```css
/* CORRECT */
.cr-button {
    background: var(--cr-green);
    color: #fff;
    border-radius: 4px;
}

/* INCORRECT - hardcoded brand colors */
.cr-button {
    background: #34b269;
}
```

### Available Tokens

| Category | Examples |
|----------|----------|
| Colors | `--cr-green`, `--cr-navy`, `--cr-gold`, `--cr-text` |
| Backgrounds | `--cr-bg`, `--cr-bg-light` |
| Borders | `--cr-border` |
| Hover states | `--cr-green-hover`, `--cr-navy-light` |

### Adding New Tokens

If you need a new value, add it to `:root` in `css/style.css` first:

```css
:root {
    --cr-new-color: #abc123;
}
```

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
All user input must be sanitized:

```php
// Escape for HTML output
<?= htmlspecialchars($userInput) ?>

// Use prepared statements for database
$db->fetchOne("SELECT * FROM table WHERE id = ?", [$id]);
```

### Output Escaping
Always escape output in templates:
```php
<?= htmlspecialchars($variable) ?>
```

### General Rules
- Never expose secrets in code or logs
- Keep `config/secrets.php` out of git
- Use parameterized queries for all database access
- Security headers configured in `.htaccess`

---

## Git Workflow

### Branches
- `main` - Production (auto-deploys)

### Commit Messages
Use conventional commits:
- `feat:` new feature
- `fix:` bug fix
- `refactor:` code restructuring
- `style:` formatting, CSS
- `docs:` documentation
- `chore:` maintenance

Examples:
```
feat: add listicle index page
fix: resolve image path issue
refactor: standardize URL routes
docs: add project documentation
```

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
class Review
{
    public static function findBySlug(int $siteId, string $slug): ?object
    {
        $db = Database::getInstance();
        return $db->fetchOne(
            "SELECT * FROM content_reviews WHERE site_id = ? AND slug = ?",
            [$siteId, $slug]
        );
    }
}
```

### Query Patterns

```php
// Single record
$review = Review::findBySlug($siteId, $slug);

// Multiple records
$reviews = Review::latest($siteId, 12);

// With pagination
$reviews = Review::latest($siteId, $perPage, $offset);

// By relationship
$reviews = Review::byCategory($siteId, $categoryId);
```

---

## URL Conventions

### Route Patterns

| Pattern | Example | Handler |
|---------|---------|---------|
| Index | `/reviews` | `reviewIndex()` |
| Show | `/reviews/{slug}` | `reviewShow($slug)` |

### Slug Rules

- Lowercase only
- Hyphens for word separation
- No underscores (converted on input)
- No special characters

---

## Comments

- Comment the **why**, not the **what**
- Use PHPDoc for complex functions
- Keep inline comments short
- Remove TODO comments before committing (or create issues)

---

*Conventions established January 2026.*
