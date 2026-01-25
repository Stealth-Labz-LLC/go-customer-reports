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

$db = Database::getInstance();

// Build regex pattern for old domains
$domainPattern = implode('|', array_map(function($d) {
    return preg_quote($d, '/');
}, $OLD_DOMAINS));

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

$articles = $db->fetchAll(
    "SELECT id, content FROM content_articles WHERE site_id = ?",
    [$SITE_ID]
);

foreach ($articles as $article) {
    $stats['articles_scanned']++;

    $newContent = convertLinks($article->content, $domainPattern, $verbose, $linkCount);

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

    if ($stats['articles_scanned'] % 500 === 0) {
        echo "  Processed {$stats['articles_scanned']} articles...\n";
    }
}
echo "  Done. {$stats['articles_updated']} articles updated.\n\n";

// ============================================================================
// MIGRATE REVIEW LINKS
// ============================================================================

echo "=== Processing Reviews ===\n\n";

$reviews = $db->fetchAll(
    "SELECT id, content FROM content_reviews WHERE site_id = ? AND content IS NOT NULL",
    [$SITE_ID]
);

foreach ($reviews as $review) {
    $stats['reviews_scanned']++;

    $newContent = convertLinks($review->content, $domainPattern, $verbose, $linkCount);

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
echo "  Done. {$stats['reviews_updated']} reviews updated.\n\n";

// ============================================================================
// MIGRATE LISTICLE LINKS
// ============================================================================

echo "=== Processing Listicles ===\n\n";

$listicles = $db->fetchAll(
    "SELECT id, intro_content, conclusion_content FROM content_listicles WHERE site_id = ?",
    [$SITE_ID]
);

foreach ($listicles as $listicle) {
    $stats['listicles_scanned']++;
    $updated = false;
    $totalLinks = 0;

    // Process intro
    if ($listicle->intro_content) {
        $newIntro = convertLinks($listicle->intro_content, $domainPattern, $verbose, $linkCount);
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
        $newConclusion = convertLinks($listicle->conclusion_content, $domainPattern, $verbose, $linkCount);
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
echo "  Done. {$stats['listicles_updated']} listicles updated.\n\n";

// ============================================================================
// MIGRATE LISTICLE ITEMS
// ============================================================================

echo "=== Processing Listicle Items ===\n\n";

$items = $db->fetchAll(
    "SELECT li.id, li.description
     FROM content_listicle_items li
     JOIN content_listicles l ON li.listicle_id = l.id
     WHERE l.site_id = ? AND li.description IS NOT NULL",
    [$SITE_ID]
);

$itemsUpdated = 0;
foreach ($items as $item) {
    $newDesc = convertLinks($item->description, $domainPattern, $verbose, $linkCount);

    if ($newDesc !== $item->description) {
        $itemsUpdated++;
        $stats['links_converted'] += $linkCount;

        if (!$dryRun) {
            $db->query("UPDATE content_listicle_items SET description = ? WHERE id = ?", [$newDesc, $item->id]);
        }
    }
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
 */
function convertLinks(string $content, string $domainPattern, bool $verbose, int &$linkCount): string
{
    $linkCount = 0;

    // Pattern to match href attributes with old domain URLs
    // Captures: href="https://olddomain.com/some/path/"
    $pattern = '/href=["\']https?:\/\/(' . $domainPattern . ')([^"\']*)["\'/i';

    return preg_replace_callback($pattern, function($matches) use ($verbose, &$linkCount) {
        $domain = $matches[1];
        $path = $matches[2];

        // Clean up the path
        $path = rtrim($path, '/');
        if (empty($path)) {
            $path = '/';
        } elseif ($path[0] !== '/') {
            $path = '/' . $path;
        }

        // Remove any query strings or fragments for cleaner URLs
        // Keep them if they look intentional (like ?page=2)
        $pathParts = parse_url($path);
        $cleanPath = $pathParts['path'] ?? '/';

        $linkCount++;

        if ($verbose) {
            echo "    Converting: {$matches[0]} -> href=\"{$cleanPath}\"\n";
        }

        return 'href="' . $cleanPath . '"';
    }, $content);
}
