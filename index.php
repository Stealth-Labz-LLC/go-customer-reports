<?php
/**
 * Front Controller
 * Routes content URLs through the template system
 * Existing campaign directories (cr/, eb/, ee25/, etc.) are served directly
 */

require_once __DIR__ . '/app/bootstrap.php';

use App\Models\Site;
use App\Core\Router;

// Load site config (customer-reports.org)
$site = Site::findByDomain('customer-reports.org');

if (!$site) {
    // Fallback: if DB isn't configured yet, show a simple message
    http_response_code(503);
    echo 'Site configuration not found. Please run database/schema.sql first.';
    exit;
}

// Dispatch the request
$router = new Router($site);
$router->dispatch($_SERVER['REQUEST_URI']);
