# Customer Reports - Deployment

CI/CD pipeline and deployment process.

---

## GitHub Actions

**File:** `.github/workflows/deploy.yml`

Automated deployment on push to `main` branch.

### Trigger

```yaml
on:
  push:
    branches: [main]
```

### Process

1. Push to `main` branch
2. GitHub Actions triggered
3. SFTP upload to production server
4. Files synced to `public_html/`

### Secrets Required

| Secret | Description |
|--------|-------------|
| `FTP_SERVER` | Hostname (e.g., `ftp.customer-reports.org`) |
| `FTP_USERNAME` | FTP/SFTP username |
| `FTP_PASSWORD` | FTP/SFTP password |

---

## Manual Deployment

If needed, manual deployment via FTP:

1. Connect to server via FTP/SFTP
2. Upload changed files to `public_html/`
3. Ensure `config/secrets.php` exists on server (not in repo)

---

## Server Requirements

| Requirement | Minimum |
|-------------|---------|
| PHP | 7.4+ |
| MySQL | 5.7+ |
| Apache | mod_rewrite enabled |

### PHP Extensions

- PDO
- PDO_MySQL
- JSON
- mbstring

---

## File Permissions

```
public_html/           755
├── index.php          644
├── .htaccess          644
├── config/            755
│   └── secrets.php    600  (sensitive)
├── storage/           775  (writable)
│   └── logs/          775
└── uploads/           755
```

---

## Environment Configuration

### Production (`config/secrets.php`)

```php
<?php
return [
    'db_host' => 'localhost',
    'db_name' => 'customerreports_articles',
    'db_user' => 'your_db_user',
    'db_pass' => 'your_db_password',
];
```

This file is gitignored and must exist on the server.

---

## Database Setup

### Initial Setup

1. Create database `customerreports_articles`
2. Import schema (tables created during migration)
3. Insert site record:

```sql
INSERT INTO content_sites (domain, name, tagline)
VALUES ('customer-reports.org', 'Customer Reports', 'Trusted Reviews. Smarter Choices.');
```

### Migrations

No migration system — schema changes applied directly via SQL.

---

## Rollback

To rollback a deployment:

1. Identify the last good commit: `git log --oneline`
2. Revert: `git revert <commit-hash>`
3. Push to main: `git push`
4. GitHub Actions will deploy the reverted state

Or manually:

1. Download previous release from GitHub
2. Upload via FTP

---

## Monitoring

### Error Logs

Check `storage/logs/` for application errors.

### Server Logs

- Apache error log: `/var/log/apache2/error.log` (or cPanel equivalent)
- PHP error log: As configured in `php.ini`

---

## Post-Deployment Checklist

- [ ] Site loads without errors
- [ ] Homepage displays content
- [ ] Review pages load with ratings
- [ ] Listicle pages load with products
- [ ] Article pages load
- [ ] Images display correctly
- [ ] Sitemap.xml generates
- [ ] 404 page works for invalid URLs
