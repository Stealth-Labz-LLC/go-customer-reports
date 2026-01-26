<?php
/**
 * Secrets Configuration Template
 *
 * Copy this file to secrets.php and fill in your actual values.
 * secrets.php is git-ignored for security.
 *
 * DO NOT commit secrets.php to version control!
 */

return [
    // Stealth Labz Portal Webhook
    'webhook_url' => 'https://portal.stealthlabz.com/source/pixel/REPLACE_WITH_YOUR_UUID',

    // Content Database (cPanel MySQL) - where articles live
    'db_host' => 'localhost',
    'db_name' => 'customerreports_articles',
    'db_user' => 'YOUR_DB_USER',
    'db_pass' => 'YOUR_DB_PASSWORD',

    // WordPress Database (read-only, for migration only)
    'wp_db_name' => 'customerreports_dev',
    'wp_db_user' => 'YOUR_WP_DB_USER',
    'wp_db_pass' => 'YOUR_WP_DB_PASSWORD',
];
