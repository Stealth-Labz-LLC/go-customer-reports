# Customer Reports - Development

Local development setup and workflow.

---

## Requirements

- PHP 8.3
- MySQL 5.7+
- Apache with mod_rewrite (or nginx equivalent)
- Git

### Recommended

- XAMPP (Windows) or MAMP (Mac)
- VS Code with PHP extensions

---

## Local Setup

### 1. Clone Repository

```bash
git clone https://github.com/Stealth-Labz-LLC/go-customer-reports.git
cd go-customer-reports
```

### 2. Configure Apache Virtual Host

**XAMPP (httpd-vhosts.conf):**

```apache
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/go-customer-reports"
    ServerName customer-reports.local
    <Directory "C:/xampp/htdocs/go-customer-reports">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

**Add to hosts file:**

```
127.0.0.1 customer-reports.local
```

### 3. Create Database

```sql
CREATE DATABASE customerreports_articles;
```

Import schema and seed data (if available).

### 4. Configure Secrets

Copy `config/secrets.example.php` to `config/secrets.php`:

```php
<?php
return [
    'db_host' => 'localhost',
    'db_name' => 'customerreports_articles',
    'db_user' => 'root',
    'db_pass' => '',
    'webhook_url' => '',  // Optional for local dev
];
```

### 5. Add Site Record

```sql
INSERT INTO content_sites (domain, name, tagline)
VALUES ('customer-reports.local', 'Customer Reports', 'Trusted Reviews. Smarter Choices.');
```

### 6. Test

Visit `http://customer-reports.local` in your browser.

---

## Directory Structure

```
go-customer-reports/
├── app/                    # Application code
│   ├── bootstrap.php       # Autoloader
│   ├── Core/               # Framework classes
│   └── Models/             # Database models
├── config/                 # Configuration
│   ├── environment.php
│   ├── secrets.example.php
│   └── secrets.php         # (gitignored)
├── templates/              # View templates
├── css/                    # Stylesheets
├── images/                 # Static images
├── uploads/                # Uploaded content
├── api/                    # API endpoints
├── storage/                # Logs (gitignored)
├── docs/                   # Documentation
├── index.php               # Front controller
└── .htaccess               # URL rewriting
```

---

## Development Workflow

### Making Changes

1. Make changes
2. Test locally
3. Commit: `git commit -m "feat: description"`
4. Push: `git push origin main`
5. GitHub Actions auto-deploys to production

---

## Adding Content Types

### New Template

1. Create template in `templates/` (e.g., `templates/guides/show.php`)
2. Add route in `Router.php`
3. Add controller method in `Router.php`
4. Create model in `app/Models/` if needed
5. Create database table if needed

### New Partial

1. Create file in `templates/partials/`
2. Include with `require __DIR__ . '/../partials/name.php';`

---

## Debugging

### Enable PHP Errors

In `index.php` or `app/bootstrap.php`:

```php
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

### Check Logs

```bash
tail -f storage/logs/app.log
```

---

## Testing URLs

| URL | Expected |
|-----|----------|
| `/` | Homepage |
| `/articles` | Article listing |
| `/reviews` | Review listing |
| `/categories` | Category listing |
| `/category/{slug}` | Category page |
| `/search` | Search page |
| `/about-us` | About page |
| `/privacy` | Privacy policy |
| `/sitemap.xml` | XML sitemap index |
| `/robots.txt` | Robots file |
| `/nonexistent` | 404 page |

---

## Common Issues

### 404 on All Pages
- Check `.htaccess` exists and mod_rewrite is enabled
- Verify `AllowOverride All` in Apache config

### Database Connection Failed
- Verify `config/secrets.php` credentials
- Check MySQL is running
- Verify database exists

### Images Not Loading
- Check `uploads/` folder exists
- Verify `IMAGE_BASE_URL` is set correctly in environment config
- Check file permissions

### Blank Page
- Enable PHP error display
- Check Apache/PHP error logs
- Verify syntax errors in PHP files
