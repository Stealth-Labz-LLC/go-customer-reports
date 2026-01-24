<?php
/**
 * Environment Configuration
 * Detects local vs production environment
 */

$localHosts = ['localhost', '127.0.0.1', '::1'];
$serverName = $_SERVER['SERVER_NAME'] ?? '';

define('IS_LOCAL', in_array($serverName, $localHosts) || str_contains($serverName, '.local'));
define('ROOT_PATH', dirname(__DIR__));
