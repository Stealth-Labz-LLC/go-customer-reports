# go-customer-reports

PHP-based marketing and advertorial landing pages. Each subdirectory contains a campaign or set of page variants.

## Project Structure

```
├── cr/          Customer Reports (review articles)
├── eb/          Evergreen Botanicals campaigns
├── ee25/        Electric Eye 2025 campaigns (multi-variant)
├── qr/          QR landing pages (auto savings, etc.)
├── sc/          Single campaign pages
├── ss/          Secondary staging pages
├── tmp/         Templates and reusable components
├── css/         Shared stylesheets
└── images/      Shared media assets
```

## Tech Stack

- PHP 7.4 (Apache / cPanel)
- Bootstrap 5.3.0 (CDN)
- jQuery 3.6.0 (CDN)
- Fancybox 3.5.7 (CDN)
- Google Tag Manager

## Local Development

### Option 1: Docker (recommended)

```bash
docker-compose up
```

Site available at `http://localhost:8080`

### Option 2: PHP built-in server

Requires PHP installed locally.

```bash
# Windows
serve.bat

# Or manually
php -S localhost:8080
```

Site available at `http://localhost:8080`

### Option 3: XAMPP / WAMP

1. Clone this repo into your `htdocs` (XAMPP) or `www` (WAMP) directory
2. Start Apache
3. Visit `http://localhost/go-customer-reports/`

## Deployment

Files are deployed directly to a cPanel-based hosting environment. The `.htaccess` configures the PHP 7.4 handler automatically.

## Adding a New Campaign

1. Create a new directory at the root (e.g., `new-campaign/`)
2. Use files in `tmp/` as starting templates (`header.php`, `footer.php`, `advertorial.php`, etc.)
3. Add campaign-specific assets in a subfolder (css, images, js)
4. Update tracking links and GTM as needed
