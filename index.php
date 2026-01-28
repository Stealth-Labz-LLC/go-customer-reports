<?php
/**
 * Front Controller
 * Routes content URLs through the template system
 * Existing campaign directories (cr/, eb/, ee25/, etc.) are served directly
 */

require_once __DIR__ . '/app/bootstrap.php';

use App\Models\Site;
use App\Core\Router;

// Load site config — try current domain first, fall back to production domain
$domain = $_SERVER['SERVER_NAME'] ?? 'customer-reports.org';
$site = Site::findByDomain($domain);
if (!$site) {
    $site = Site::findByDomain('customer-reports.org');
}

if (!$site) {
    // Fallback: if DB isn't configured yet, show a simple message
    http_response_code(503);
    echo 'Site configuration not found. Please run database/schema.sql first.';
    exit;
}

// Dispatch the request
// Strip subdirectory prefix for local development (e.g., /go-customer-reports/)
$requestUri = $_SERVER['REQUEST_URI'];
if (IS_LOCAL) {
    $basePath = dirname($_SERVER['SCRIPT_NAME']);
    if ($basePath !== '/' && strpos($requestUri, $basePath) === 0) {
        $requestUri = substr($requestUri, strlen($basePath)) ?: '/';
    }
}

$router = new Router($site);
$router->dispatch($requestUri);
