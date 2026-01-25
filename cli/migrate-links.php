#!/usr/bin/env php
<?php
/**
 * Link Migration Script
 *
 * Converts external links from old WordPress domains to internal relative links.
 * This improves SEO by creating proper internal linking structure.
 *
 * Usage:
 *   php cli/migrate-links.php --dry-run    # Preview without updating
 *   php cli/migrate-links.php              # Update links
 */

if (php_sapi_name() !== 'cli') {
    die("This script must be run from the command line.\n");
}

// Enable error reporting for CLI
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Disable output buffering for real-time output
if (ob_get_level()) ob_end_clean();

require_once __DIR__ . '/../app/bootstrap.php';

use App\Core\Database;

// ============================================================================
// CONFIGURATION
// ============================================================================

$SITE_ID = 1;

// Old WordPress domains to convert to internal links
$OLD_DOMAINS = [
    'customer-reports.org',
    'www.customer-reports.org',
    'homevibeguide.com',
    'www.homevibeguide.com',
    'itsavibefitness.com',
    'www.itsavibefitness.com',
    'petsvibeguide.com',
    'www.petsvibeguide.com',
    'seniortimesguide.com',
    'www.seniortimesguide.com',
    'cleanwatersguide.com',
    'www.cleanwatersguide.com',
    'beautyvibeguide.com',
    'www.beautyvibeguide.com',
    'stealthlabz.com',
    'www.stealthlabz.com',
    'shopper-guide.com',
    'www.shopper-guide.com',
    'evergreenevolutions.com',
    'www.evergreenevolutions.com',
];

// ============================================================================
// PARSE ARGUMENTS
// ============================================================================

$options = getopt('', ['dry-run', 'help', 'verbose']);

if (isset($options['help'])) {
    echo "Link Migration Script\n";
    echo "=====================\n\n";
    echo "Converts external links from old WordPress domains to internal relative links.\n\n";
    echo "Usage:\n";
    echo "  php cli/migrate-links.php [options]\n\n";
    echo "Options:\n";
    echo "  --dry-run    Preview without updating\n";
    echo "  --verbose    Show each link conversion\n";
    echo "  --help       Show this help\n";
    exit(0);
}

$dryRun = isset($options['dry-run']);
$verbose = isset($options['verbose']);

// ============================================================================
// MAIN
// ============================================================================

echo "\n";
echo "=========================================\n";
echo " Link Migration Script\n";
echo "=========================================\n";
echo " Mode: " . ($dryRun ? "DRY RUN" : "LIVE UPDATE") . "\n";
echo "=========================================\n\n";
flush();

echo "Connecting to database...\n";
$db = Database::getInstance();
echo "Connected.\n\n";
flush();

// Domains array for link conversion
$domains = $OLD_DOMAINS;

$stats = [
    'articles_scanned' => 0,
    'articles_updated' => 0,
    'reviews_scanned' => 0,
    'reviews_updated' => 0,
    'listicles_scanned' => 0,
    'listicles_updated' => 0,
    'links_converted' => 0,
];

// ============================================================================
// MIGRATE ARTICLE LINKS
// ============================================================================

echo "=== Processing Articles ===\n\n";

// Get total count first
$countResult = $db->fetchOne("SELECT COUNT(*) as total FROM content_articles WHERE site_id = ?", [$SITE_ID]);
$totalArticles = (int) $countResult->total;
echo "  Found {$totalArticles} articles to scan.\n";
flush();

// Process in batches to avoid memory issues
$batchSize = 100;
$offset = 0;

while ($offset < $totalArticles) {
    $articles = $db->fetchAll(
        "SELECT id, content FROM content_articles WHERE site_id = ? LIMIT ? OFFSET ?",
        [$SITE_ID, $batchSize, $offset]
    );

    if (empty($articles)) break;

    foreach ($articles as $article) {
        $stats['articles_scanned']++;
        $linkCount = 0;
        $newContent = convertLinks($article->content, $domains, $verbose, $linkCount);

        if ($newContent !== $article->content) {
            $stats['articles_updated']++;
            $stats['links_converted'] += $linkCount;

            if (!$dryRun) {
                $db->query("UPDATE content_articles SET content = ? WHERE id = ?", [$newContent, $article->id]);
            }

            if ($verbose) {
                echo "  Article ID {$article->id}: {$linkCount} links converted\n";
            }
        }
    }

    $offset += $batchSize;
    echo "  Processed {$stats['articles_scanned']} of {$totalArticles} articles...\n";
    flush();
}
echo "  Done. {$stats['articles_updated']} articles updated.\n\n";

// ============================================================================
// MIGRATE REVIEW LINKS
// ============================================================================

echo "=== Processing Reviews ===\n\n";

$countResult = $db->fetchOne("SELECT COUNT(*) as total FROM content_reviews WHERE site_id = ? AND content IS NOT NULL", [$SITE_ID]);
$totalReviews = (int) $countResult->total;
echo "  Found {$totalReviews} reviews to scan.\n";
flush();

$offset = 0;
while ($offset < $totalReviews) {
    $reviews = $db->fetchAll(
        "SELECT id, content FROM content_reviews WHERE site_id = ? AND content IS NOT NULL LIMIT ? OFFSET ?",
        [$SITE_ID, $batchSize, $offset]
    );

    if (empty($reviews)) break;

    foreach ($reviews as $review) {
        $stats['reviews_scanned']++;
        $linkCount = 0;
        $newContent = convertLinks($review->content, $domains, $verbose, $linkCount);

        if ($newContent !== $review->content) {
            $stats['reviews_updated']++;
            $stats['links_converted'] += $linkCount;

            if (!$dryRun) {
                $db->query("UPDATE content_reviews SET content = ? WHERE id = ?", [$newContent, $review->id]);
            }

            if ($verbose) {
                echo "  Review ID {$review->id}: {$linkCount} links converted\n";
            }
        }
    }

    $offset += $batchSize;
}
echo "  Done. {$stats['reviews_updated']} reviews updated.\n\n";

// ============================================================================
// MIGRATE LISTICLE LINKS
// ============================================================================

echo "=== Processing Listicles ===\n\n";

$countResult = $db->fetchOne("SELECT COUNT(*) as total FROM content_listicles WHERE site_id = ?", [$SITE_ID]);
$totalListicles = (int) $countResult->total;
echo "  Found {$totalListicles} listicles to scan.\n";
flush();

$offset = 0;
while ($offset < $totalListicles) {
    $listicles = $db->fetchAll(
        "SELECT id, intro_content, conclusion_content FROM content_listicles WHERE site_id = ? LIMIT ? OFFSET ?",
        [$SITE_ID, $batchSize, $offset]
    );

    if (empty($listicles)) break;

    foreach ($listicles as $listicle) {
        $stats['listicles_scanned']++;
        $updated = false;
        $totalLinks = 0;

        // Process intro
        if ($listicle->intro_content) {
            $linkCount = 0;
            $newIntro = convertLinks($listicle->intro_content, $domains, $verbose, $linkCount);
            if ($newIntro !== $listicle->intro_content) {
                $totalLinks += $linkCount;
                if (!$dryRun) {
                    $db->query("UPDATE content_listicles SET intro_content = ? WHERE id = ?", [$newIntro, $listicle->id]);
                }
                $updated = true;
            }
        }

        // Process conclusion
        if ($listicle->conclusion_content) {
            $linkCount = 0;
            $newConclusion = convertLinks($listicle->conclusion_content, $domains, $verbose, $linkCount);
            if ($newConclusion !== $listicle->conclusion_content) {
                $totalLinks += $linkCount;
                if (!$dryRun) {
                    $db->query("UPDATE content_listicles SET conclusion_content = ? WHERE id = ?", [$newConclusion, $listicle->id]);
                }
                $updated = true;
            }
        }

        if ($updated) {
            $stats['listicles_updated']++;
            $stats['links_converted'] += $totalLinks;

            if ($verbose) {
                echo "  Listicle ID {$listicle->id}: {$totalLinks} links converted\n";
            }
        }
    }

    $offset += $batchSize;
}
echo "  Done. {$stats['listicles_updated']} listicles updated.\n\n";

// ============================================================================
// MIGRATE LISTICLE ITEMS
// ============================================================================

echo "=== Processing Listicle Items ===\n\n";

$countResult = $db->fetchOne(
    "SELECT COUNT(*) as total FROM content_listicle_items li
     JOIN content_listicles l ON li.listicle_id = l.id
     WHERE l.site_id = ? AND li.description IS NOT NULL",
    [$SITE_ID]
);
$totalItems = (int) $countResult->total;
echo "  Found {$totalItems} listicle items to scan.\n";
flush();

$itemsUpdated = 0;
$itemsScanned = 0;
$offset = 0;

while ($offset < $totalItems) {
    $items = $db->fetchAll(
        "SELECT li.id, li.description
         FROM content_listicle_items li
         JOIN content_listicles l ON li.listicle_id = l.id
         WHERE l.site_id = ? AND li.description IS NOT NULL
         LIMIT ? OFFSET ?",
        [$SITE_ID, $batchSize, $offset]
    );

    if (empty($items)) break;

    foreach ($items as $item) {
        $itemsScanned++;
        $linkCount = 0;
        $newDesc = convertLinks($item->description, $domains, $verbose, $linkCount);

        if ($newDesc !== $item->description) {
            $itemsUpdated++;
            $stats['links_converted'] += $linkCount;

            if (!$dryRun) {
                $db->query("UPDATE content_listicle_items SET description = ? WHERE id = ?", [$newDesc, $item->id]);
            }
        }
    }

    $offset += $batchSize;
}
echo "  Done. {$itemsUpdated} listicle items updated.\n\n";

// ============================================================================
// RESULTS
// ============================================================================

echo "=========================================\n";
echo " RESULTS\n";
echo "=========================================\n";
echo " Articles:  {$stats['articles_scanned']} scanned, {$stats['articles_updated']} updated\n";
echo " Reviews:   {$stats['reviews_scanned']} scanned, {$stats['reviews_updated']} updated\n";
echo " Listicles: {$stats['listicles_scanned']} scanned, {$stats['listicles_updated']} updated\n";
echo " Listicle Items: {$itemsUpdated} updated\n";
echo "-----------------------------------------\n";
echo " Total Links Converted: {$stats['links_converted']}\n";
echo "=========================================\n";

if ($dryRun) {
    echo "\n(Dry run - no changes made)\n";
}

echo "\nDone!\n";

// ============================================================================
// FUNCTIONS
// ============================================================================

/**
 * Convert external links to internal relative links
 * Uses string operations instead of regex for reliability
 */
function convertLinks(string $content, array $domains, bool $verbose, int &$linkCount): string
{
    $linkCount = 0;

    foreach ($domains as $domain) {
        // Look for href="http(s)://domain patterns
        foreach (['https://', 'http://'] as $protocol) {
            $search = $protocol . $domain;

            $pos = 0;
            while (($pos = stripos($content, 'href="' . $search, $pos)) !== false) {
                // Find the closing quote
                $urlStart = $pos + 6; // skip 'href="'
                $urlEnd = strpos($content, '"', $urlStart);
                if ($urlEnd === false) {
                    $pos++;
                    continue;
                }

                $fullUrl = substr($content, $urlStart, $urlEnd - $urlStart);
                $path = parse_url($fullUrl, PHP_URL_PATH) ?? '/';
                if (empty($path)) $path = '/';

                $replacement = 'href="' . $path . '"';
                $content = substr($content, 0, $pos) . $replacement . substr($content, $urlEnd + 1);
                $linkCount++;

                if ($verbose) {
                    echo "    Converting: {$fullUrl} -> {$path}\n";
                }

                $pos += strlen($replacement);
            }

            // Also check single quotes
            $pos = 0;
            while (($pos = stripos($content, "href='" . $search, $pos)) !== false) {
                $urlStart = $pos + 6;
                $urlEnd = strpos($content, "'", $urlStart);
                if ($urlEnd === false) {
                    $pos++;
                    continue;
                }

                $fullUrl = substr($content, $urlStart, $urlEnd - $urlStart);
                $path = parse_url($fullUrl, PHP_URL_PATH) ?? '/';
                if (empty($path)) $path = '/';

                $replacement = 'href="' . $path . '"';
                $content = substr($content, 0, $pos) . $replacement . substr($content, $urlEnd + 1);
                $linkCount++;

                if ($verbose) {
                    echo "    Converting: {$fullUrl} -> {$path}\n";
                }

                $pos += strlen($replacement);
            }
        }
    }

    return $content;
}
