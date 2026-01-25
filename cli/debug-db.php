#!/usr/bin/env php
<?php
/**
 * Database Debug Script - isolates each step to find the failure point
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');

echo "Step 1: Script started\n";

echo "Step 2: Loading bootstrap...\n";
require_once __DIR__ . '/../app/bootstrap.php';
echo "Step 2: Bootstrap loaded\n";

echo "Step 3: Getting database instance...\n";
use App\Core\Database;
$db = Database::getInstance();
echo "Step 3: Database connected\n";

echo "Step 4: Running simple query...\n";
$result = $db->fetchOne("SELECT COUNT(*) as total FROM content_articles WHERE site_id = 1");
echo "Step 4: Found " . $result->total . " articles\n";

echo "Step 5: Testing fetchAll with LIMIT...\n";
$articles = $db->fetchAll("SELECT id FROM content_articles WHERE site_id = 1 LIMIT 5");
echo "Step 5: Fetched " . count($articles) . " rows\n";

echo "\nAll steps passed! Database is working.\n";
