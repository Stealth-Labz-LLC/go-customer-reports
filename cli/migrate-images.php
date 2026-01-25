#!/usr/bin/env php
<?php
/**
 * Image Migration Script
 *
 * Downloads images from old WordPress sites and updates database references.
 *
 * Usage:
 *   php cli/migrate-images.php --dry-run    # Preview without downloading
 *   php cli/migrate-images.php              # Download and migrate
 */

if (php_sapi_name() !== 'cli') {
    die("This script must be run from the command line.\n");
}

require_once __DIR__ . '/../app/bootstrap.php';

use App\Core\Database;

// ============================================================================
// CONFIGURATION
// ============================================================================

$SITE_ID = 1;
$UPLOADS_DIR = dirname(__DIR__) . '/uploads/migrated/';

// Old WordPress domains to migrate from
$OLD_DOMAINS = [
    'customer-reports.org',
    'homevibeguide.com',
    'itsavibefitness.com',
    'petsvibeguide.com',
    'seniortimesguide.com',
    'cleanwatersguide.com',
    'beautyvibeguide.com',
    'stealthlabz.com',
    'shopper-guide.com',
    'evergreenevolutions.com',
];

// ============================================================================
// PARSE ARGUMENTS
// ============================================================================

$options = getopt('', ['dry-run', 'help', 'limit:']);

if (isset($options['help'])) {
    echo "Image Migration Script\n";
    echo "======================\n\n";
    echo "Usage:\n";
    echo "  php cli/migrate-images.php [options]\n\n";
    echo "Options:\n";
    echo "  --dry-run    Preview without downloading\n";
    echo "  --limit=N    Limit to N images (for testing)\n";
    echo "  --help       Show this help\n";
    exit(0);
}

$dryRun = isset($options['dry-run']);
$limit = isset($options['limit']) ? (int)$options['limit'] : 0;

// ============================================================================
// MAIN
// ============================================================================

echo "\n";
echo "=========================================\n";
echo " Image Migration Script\n";
echo "=========================================\n";
echo " Mode: " . ($dryRun ? "DRY RUN" : "LIVE MIGRATION") . "\n";
echo "=========================================\n\n";

$db = Database::getInstance();

// Create uploads directory
if (!$dryRun && !is_dir($UPLOADS_DIR)) {
    mkdir($UPLOADS_DIR, 0755, true);
    echo "Created directory: {$UPLOADS_DIR}\n\n";
}

// Build pattern for old domains
$domainPattern = implode('|', array_map(function($d) {
    return preg_quote($d, '/');
}, $OLD_DOMAINS));

$stats = [
    'found' => 0,
    'downloaded' => 0,
    'failed' => 0,
    'skipped' => 0,
];

// ============================================================================
// MIGRATE FEATURED IMAGES
// ============================================================================

echo "=== Migrating Featured Images ===\n\n";

// Articles
echo "Processing articles...\n";
$articles = $db->fetchAll(
    "SELECT id, featured_image FROM content_articles WHERE site_id = ? AND featured_image IS NOT NULL AND featured_image != ''",
    [$SITE_ID]
);

foreach ($articles as $article) {
    if ($limit > 0 && $stats['found'] >= $limit) break;

    $result = migrateImage($article->featured_image, $UPLOADS_DIR, $dryRun, $domainPattern);
    $stats['found']++;

    if ($result === false) {
        $stats['failed']++;
    } elseif ($result === null) {
        $stats['skipped']++;
    } else {
        $stats['downloaded']++;
        if (!$dryRun) {
            $db->query("UPDATE content_articles SET featured_image = ? WHERE id = ?", [$result, $article->id]);
        }
    }

    if ($stats['found'] % 100 === 0) {
        echo "  Processed {$stats['found']} images...\n";
    }
}
echo "  Articles done.\n\n";

// Reviews
echo "Processing reviews...\n";
$reviews = $db->fetchAll(
    "SELECT id, featured_image FROM content_reviews WHERE site_id = ? AND featured_image IS NOT NULL AND featured_image != ''",
    [$SITE_ID]
);

foreach ($reviews as $review) {
    if ($limit > 0 && $stats['found'] >= $limit) break;

    $result = migrateImage($review->featured_image, $UPLOADS_DIR, $dryRun, $domainPattern);
    $stats['found']++;

    if ($result === false) {
        $stats['failed']++;
    } elseif ($result === null) {
        $stats['skipped']++;
    } else {
        $stats['downloaded']++;
        if (!$dryRun) {
            $db->query("UPDATE content_reviews SET featured_image = ? WHERE id = ?", [$result, $review->id]);
        }
    }
}
echo "  Reviews done.\n\n";

// Listicles
echo "Processing listicles...\n";
$listicles = $db->fetchAll(
    "SELECT id, featured_image FROM content_listicles WHERE site_id = ? AND featured_image IS NOT NULL AND featured_image != ''",
    [$SITE_ID]
);

foreach ($listicles as $listicle) {
    if ($limit > 0 && $stats['found'] >= $limit) break;

    $result = migrateImage($listicle->featured_image, $UPLOADS_DIR, $dryRun, $domainPattern);
    $stats['found']++;

    if ($result === false) {
        $stats['failed']++;
    } elseif ($result === null) {
        $stats['skipped']++;
    } else {
        $stats['downloaded']++;
        if (!$dryRun) {
            $db->query("UPDATE content_listicles SET featured_image = ? WHERE id = ?", [$result, $listicle->id]);
        }
    }
}
echo "  Listicles done.\n\n";

// ============================================================================
// MIGRATE EMBEDDED IMAGES IN CONTENT
// ============================================================================

echo "=== Migrating Embedded Images in Content ===\n\n";

// Find and replace images in article content
echo "Processing article content...\n";
$articles = $db->fetchAll(
    "SELECT id, content FROM content_articles WHERE site_id = ? AND content REGEXP ?",
    [$SITE_ID, "https?://[^\"']*({$domainPattern})[^\"']*\\.(jpg|jpeg|png|gif|webp)"]
);

$contentUpdates = 0;
foreach ($articles as $article) {
    $newContent = migrateContentImages($article->content, $UPLOADS_DIR, $dryRun, $domainPattern, $stats);
    if ($newContent !== $article->content && !$dryRun) {
        $db->query("UPDATE content_articles SET content = ? WHERE id = ?", [$newContent, $article->id]);
        $contentUpdates++;
    }
}
echo "  Updated {$contentUpdates} articles with embedded images.\n\n";

// Reviews content
echo "Processing review content...\n";
$reviews = $db->fetchAll(
    "SELECT id, content FROM content_reviews WHERE site_id = ? AND content IS NOT NULL AND content REGEXP ?",
    [$SITE_ID, "https?://[^\"']*({$domainPattern})[^\"']*\\.(jpg|jpeg|png|gif|webp)"]
);

$contentUpdates = 0;
foreach ($reviews as $review) {
    $newContent = migrateContentImages($review->content, $UPLOADS_DIR, $dryRun, $domainPattern, $stats);
    if ($newContent !== $review->content && !$dryRun) {
        $db->query("UPDATE content_reviews SET content = ? WHERE id = ?", [$newContent, $review->id]);
        $contentUpdates++;
    }
}
echo "  Updated {$contentUpdates} reviews with embedded images.\n\n";

// ============================================================================
// RESULTS
// ============================================================================

echo "=========================================\n";
echo " RESULTS\n";
echo "=========================================\n";
echo " Found:      {$stats['found']}\n";
echo " Downloaded: {$stats['downloaded']}\n";
echo " Skipped:    {$stats['skipped']} (already local or not from old sites)\n";
echo " Failed:     {$stats['failed']}\n";
echo "=========================================\n";

if ($dryRun) {
    echo "\n(Dry run - no changes made)\n";
}

echo "\nDone!\n";

// ============================================================================
// FUNCTIONS
// ============================================================================

function migrateImage(string $url, string $uploadsDir, bool $dryRun, string $domainPattern): ?string
{
    // Skip if not from old domains
    if (!preg_match("/({$domainPattern})/i", $url)) {
        return null; // Skip, not from old site
    }

    // Skip if already local
    if (strpos($url, '/uploads/') === 0) {
        return null;
    }

    // Generate local filename
    $parsed = parse_url($url);
    $path = $parsed['path'] ?? '';
    $filename = basename($path);

    // Clean filename
    $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
    if (empty($filename) || $filename === '_') {
        $filename = md5($url) . '.jpg';
    }

    // Add hash to prevent collisions
    $hash = substr(md5($url), 0, 8);
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $base = pathinfo($filename, PATHINFO_FILENAME);
    $filename = $base . '_' . $hash . '.' . $ext;

    $localPath = $uploadsDir . $filename;
    $webPath = '/uploads/migrated/' . $filename;

    if ($dryRun) {
        return $webPath;
    }

    // Skip if already downloaded
    if (file_exists($localPath)) {
        return $webPath;
    }

    // Download
    $context = stream_context_create([
        'http' => [
            'timeout' => 30,
            'user_agent' => 'Mozilla/5.0 (compatible; ImageMigrator/1.0)',
        ],
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
        ],
    ]);

    $imageData = @file_get_contents($url, false, $context);

    if ($imageData === false) {
        return false; // Failed to download
    }

    // Save
    if (file_put_contents($localPath, $imageData) === false) {
        return false;
    }

    return $webPath;
}

function migrateContentImages(string $content, string $uploadsDir, bool $dryRun, string $domainPattern, array &$stats): string
{
    // Find all image URLs in content
    $pattern = '/https?:\/\/[^\s"\'<>]*(' . $domainPattern . ')[^\s"\'<>]*\.(jpg|jpeg|png|gif|webp)/i';

    return preg_replace_callback($pattern, function($matches) use ($uploadsDir, $dryRun, $domainPattern, &$stats) {
        $url = $matches[0];
        $stats['found']++;

        $result = migrateImage($url, $uploadsDir, $dryRun, $domainPattern);

        if ($result === false) {
            $stats['failed']++;
            return $url; // Keep original on failure
        } elseif ($result === null) {
            $stats['skipped']++;
            return $url;
        } else {
            $stats['downloaded']++;
            return $result;
        }
    }, $content);
}
