<?php
/**
 * Environment Configuration
 * Detects local vs production environment
 */

$localHosts = ['localhost', '127.0.0.1', '::1'];
$serverName = $_SERVER['SERVER_NAME'] ?? '';

define('IS_LOCAL', in_array($serverName, $localHosts) || str_contains($serverName, '.local'));

// Base URL for assets (empty on production, subdirectory path on local)
$baseUrl = '';
if (IS_LOCAL && isset($_SERVER['SCRIPT_NAME'])) {
    $basePath = dirname($_SERVER['SCRIPT_NAME']);
    if ($basePath !== '/' && $basePath !== '\\') {
        $baseUrl = rtrim($basePath, '/\\');
    }
}
define('BASE_URL', $baseUrl);

// Image base URL - load from production when running locally
// This avoids having to download all images for local development
define('IMAGE_BASE_URL', IS_LOCAL ? 'https://customer-reports.org' : '');
