#!/usr/bin/env php
<?php
/**
 * WordPress Content Cleanup Script
 *
 * Removes WordPress legacy artifacts from migrated content:
 * - Block comments (<!-- wp:paragraph -->, <!-- /wp:paragraph -->, etc.)
 * - Shortcodes ([aces-pros-1], [aces-cons-1], etc.)
 * - Empty paragraphs
 * - WordPress-specific classes (wp-block-heading, wp-block-list, etc.)
 * - Broken internal links (/rankings/..., etc.)
 *
 * Usage:
 *   php cli/cleanup-wordpress.php --dry-run    # Preview without updating
 *   php cli/cleanup-wordpress.php              # Update content
 *   php cli/cleanup-wordpress.php --verbose    # Show detailed changes
 */

if (php_sapi_name() !== 'cli') {
    die("This script must be run from the command line.\n");
}

error_reporting(E_ALL);
ini_set('display_errors', '1');

if (ob_get_level()) ob_end_clean();

require_once __DIR__ . '/../app/bootstrap.php';

use App\Core\Database;

// ============================================================================
// CONFIGURATION
// ============================================================================

$SITE_ID = 1;

// ============================================================================
// PARSE ARGUMENTS
// ============================================================================

$options = getopt('', ['dry-run', 'help', 'verbose']);

if (isset($options['help'])) {
    echo "WordPress Content Cleanup Script\n";
    echo "=================================\n\n";
    echo "Removes WordPress legacy artifacts from migrated content.\n\n";
    echo "Usage:\n";
    echo "  php cli/cleanup-wordpress.php [options]\n\n";
    echo "Options:\n";
    echo "  --dry-run    Preview without updating\n";
    echo "  --verbose    Show detailed changes\n";
    echo "  --help       Show this help\n";
    exit(0);
}

$dryRun = isset($options['dry-run']);
$verbose = isset($options['verbose']);

// ============================================================================
// CLEANUP FUNCTIONS
// ============================================================================

/**
 * Clean WordPress artifacts from content
 */
function cleanWordPressContent(string $content): array
{
    $original = $content;
    $changes = [];

    // 1. Remove WordPress block comments
    $blockCommentPattern = '/<!--\s*\/?wp:[a-z\-\/{}":,\s\d\[\]]+\s*-->/i';
    $newContent = preg_replace($blockCommentPattern, '', $content);
    if ($newContent !== $content) {
        $changes[] = 'Removed WordPress block comments';
        $content = $newContent;
    }

    // 2. Remove shortcodes but keep their content
    // Pattern: [shortcode-name attr="value"]content[/shortcode-name]
    $shortcodePatterns = [
        // [aces-pros-1 title="Pros"]content[/aces-pros-1] -> content
        '/\[aces-pros-\d+[^\]]*\](.*?)\[\/aces-pros-\d+\]/s',
        '/\[aces-cons-\d+[^\]]*\](.*?)\[\/aces-cons-\d+\]/s',
        // Other common shortcodes - keep content
        '/\[vc_[^\]]*\](.*?)\[\/vc_[^\]]*\]/s',
        '/\[et_pb_[^\]]*\](.*?)\[\/et_pb_[^\]]*\]/s',
    ];

    foreach ($shortcodePatterns as $pattern) {
        $newContent = preg_replace($pattern, '$1', $content);
        if ($newContent !== $content) {
            $changes[] = 'Removed shortcode wrappers (kept content)';
            $content = $newContent;
        }
    }

    // 3. Remove self-closing shortcodes entirely
    $selfClosingPatterns = [
        '/\[[a-z_\-0-9]+[^\]]*\/\]/i',  // [shortcode /]
    ];

    foreach ($selfClosingPatterns as $pattern) {
        $newContent = preg_replace($pattern, '', $content);
        if ($newContent !== $content) {
            $changes[] = 'Removed self-closing shortcodes';
            $content = $newContent;
        }
    }

    // 4. Remove empty paragraphs
    $emptyParagraphPatterns = [
        '/<p>\s*<\/p>/i',
        '/<p>&nbsp;<\/p>/i',
        '/<p>\s*&nbsp;\s*<\/p>/i',
    ];

    foreach ($emptyParagraphPatterns as $pattern) {
        $newContent = preg_replace($pattern, '', $content);
        if ($newContent !== $content) {
            $changes[] = 'Removed empty paragraphs';
            $content = $newContent;
        }
    }

    // 5. Remove WordPress-specific classes from elements
    $wpClasses = [
        'wp-block-heading',
        'wp-block-list',
        'wp-block-paragraph',
        'wp-block-image',
        'wp-block-quote',
        'wp-block-columns',
        'wp-block-column',
        'wp-block-group',
        'wp-block-separator',
        'wp-block-spacer',
        'has-text-align-center',
        'has-text-align-left',
        'has-text-align-right',
        'aligncenter',
        'alignleft',
        'alignright',
        'alignnone',
        'size-full',
        'size-large',
        'size-medium',
        'size-thumbnail',
    ];

    foreach ($wpClasses as $class) {
        // Remove class from class="..." attribute
        $pattern = '/\s*\b' . preg_quote($class, '/') . '\b\s*/';
        $newContent = preg_replace_callback(
            '/class="([^"]*)"/i',
            function ($matches) use ($pattern) {
                $classes = preg_replace($pattern, ' ', $matches[1]);
                $classes = preg_replace('/\s+/', ' ', trim($classes));
                return $classes ? 'class="' . $classes . '"' : '';
            },
            $content
        );
        if ($newContent !== $content) {
            $content = $newContent;
        }
    }

    // Check if any classes were actually removed
    if (preg_match('/wp-block-|aligncenter|alignleft|alignright/', $original) &&
        !preg_match('/wp-block-|aligncenter|alignleft|alignright/', $content)) {
        $changes[] = 'Removed WordPress classes';
    }

    // 6. Clean up empty class attributes
    $newContent = preg_replace('/\s*class="\s*"/', '', $content);
    if ($newContent !== $content) {
        $changes[] = 'Removed empty class attributes';
        $content = $newContent;
    }

    // 7. Fix broken internal links (old WordPress URLs)
    $brokenLinkPatterns = [
        // /rankings/... -> remove link but keep text
        '/<a[^>]*href="\/rankings\/[^"]*"[^>]*>(.*?)<\/a>/is' => '$1',
        // /category-name/... (old format without /category/) -> keep as-is for now
    ];

    foreach ($brokenLinkPatterns as $pattern => $replacement) {
        $newContent = preg_replace($pattern, $replacement, $content);
        if ($newContent !== $content) {
            $changes[] = 'Removed broken /rankings/ links';
            $content = $newContent;
        }
    }

    // 8. Clean up excessive whitespace/newlines
    $newContent = preg_replace('/\n{3,}/', "\n\n", $content);
    $newContent = preg_replace('/^\s+$/m', '', $newContent);
    if ($newContent !== $content) {
        $changes[] = 'Cleaned up whitespace';
        $content = $newContent;
    }

    // 9. Trim the content
    $content = trim($content);

    return [
        'content' => $content,
        'changed' => $content !== $original,
        'changes' => array_unique($changes),
    ];
}

// ============================================================================
// MAIN
// ============================================================================

echo "\n";
echo "=========================================\n";
echo " WordPress Content Cleanup\n";
echo "=========================================\n";
echo " Mode: " . ($dryRun ? "DRY RUN" : "LIVE UPDATE") . "\n";
echo "=========================================\n\n";
flush();

echo "Connecting to database...\n";
$db = Database::getInstance();
echo "Connected.\n\n";
flush();

$stats = [
    'articles_scanned' => 0,
    'articles_updated' => 0,
    'reviews_scanned' => 0,
    'reviews_updated' => 0,
    'listicles_scanned' => 0,
    'listicles_updated' => 0,
    'pages_scanned' => 0,
    'pages_updated' => 0,
];

$batchSize = 100;

// ============================================================================
// PROCESS ARTICLES
// ============================================================================

echo "=== Processing Articles ===\n\n";

$countResult = $db->fetchOne("SELECT COUNT(*) as total FROM content_articles WHERE site_id = ?", [$SITE_ID]);
$totalArticles = (int) $countResult->total;
echo "  Found {$totalArticles} articles to scan.\n";
flush();

$offset = 0;
while ($offset < $totalArticles) {
    $articles = $db->fetchAll(
        "SELECT id, title, content FROM content_articles WHERE site_id = ? LIMIT ? OFFSET ?",
        [$SITE_ID, $batchSize, $offset]
    );

    if (empty($articles)) break;

    foreach ($articles as $article) {
        $stats['articles_scanned']++;

        if (empty($article->content)) continue;

        $result = cleanWordPressContent($article->content);

        if ($result['changed']) {
            $stats['articles_updated']++;

            if (!$dryRun) {
                $db->query("UPDATE content_articles SET content = ? WHERE id = ?", [$result['content'], $article->id]);
            }

            if ($verbose) {
                echo "  Article ID {$article->id}: " . implode(', ', $result['changes']) . "\n";
            }
        }
    }

    $offset += $batchSize;
    echo "  Processed {$stats['articles_scanned']} of {$totalArticles} articles...\n";
    flush();
}
echo "  Done. {$stats['articles_updated']} articles updated.\n\n";

// ============================================================================
// PROCESS REVIEWS
// ============================================================================

echo "=== Processing Reviews ===\n\n";

$countResult = $db->fetchOne("SELECT COUNT(*) as total FROM content_reviews WHERE site_id = ?", [$SITE_ID]);
$totalReviews = (int) $countResult->total;
echo "  Found {$totalReviews} reviews to scan.\n";
flush();

$offset = 0;
while ($offset < $totalReviews) {
    $reviews = $db->fetchAll(
        "SELECT id, name, content FROM content_reviews WHERE site_id = ? LIMIT ? OFFSET ?",
        [$SITE_ID, $batchSize, $offset]
    );

    if (empty($reviews)) break;

    foreach ($reviews as $review) {
        $stats['reviews_scanned']++;

        if (empty($review->content)) continue;

        $result = cleanWordPressContent($review->content);

        if ($result['changed']) {
            $stats['reviews_updated']++;

            if (!$dryRun) {
                $db->query("UPDATE content_reviews SET content = ? WHERE id = ?", [$result['content'], $review->id]);
            }

            if ($verbose) {
                echo "  Review ID {$review->id}: " . implode(', ', $result['changes']) . "\n";
            }
        }
    }

    $offset += $batchSize;
}
echo "  Done. {$stats['reviews_updated']} reviews updated.\n\n";

// ============================================================================
// PROCESS LISTICLES (introduction and conclusion)
// ============================================================================

echo "=== Processing Listicles ===\n\n";

$countResult = $db->fetchOne("SELECT COUNT(*) as total FROM content_listicles WHERE site_id = ?", [$SITE_ID]);
$totalListicles = (int) $countResult->total;
echo "  Found {$totalListicles} listicles to scan.\n";
flush();

$offset = 0;
while ($offset < $totalListicles) {
    $listicles = $db->fetchAll(
        "SELECT id, title, introduction, conclusion FROM content_listicles WHERE site_id = ? LIMIT ? OFFSET ?",
        [$SITE_ID, $batchSize, $offset]
    );

    if (empty($listicles)) break;

    foreach ($listicles as $listicle) {
        $stats['listicles_scanned']++;
        $updated = false;
        $allChanges = [];

        // Process introduction
        if (!empty($listicle->introduction)) {
            $result = cleanWordPressContent($listicle->introduction);
            if ($result['changed']) {
                if (!$dryRun) {
                    $db->query("UPDATE content_listicles SET introduction = ? WHERE id = ?", [$result['content'], $listicle->id]);
                }
                $allChanges = array_merge($allChanges, $result['changes']);
                $updated = true;
            }
        }

        // Process conclusion
        if (!empty($listicle->conclusion)) {
            $result = cleanWordPressContent($listicle->conclusion);
            if ($result['changed']) {
                if (!$dryRun) {
                    $db->query("UPDATE content_listicles SET conclusion = ? WHERE id = ?", [$result['content'], $listicle->id]);
                }
                $allChanges = array_merge($allChanges, $result['changes']);
                $updated = true;
            }
        }

        if ($updated) {
            $stats['listicles_updated']++;
            if ($verbose) {
                echo "  Listicle ID {$listicle->id}: " . implode(', ', array_unique($allChanges)) . "\n";
            }
        }
    }

    $offset += $batchSize;
}
echo "  Done. {$stats['listicles_updated']} listicles updated.\n\n";

// ============================================================================
// PROCESS PAGES
// ============================================================================

echo "=== Processing Pages ===\n\n";

$countResult = $db->fetchOne("SELECT COUNT(*) as total FROM content_pages WHERE site_id = ?", [$SITE_ID]);
$totalPages = (int) $countResult->total;
echo "  Found {$totalPages} pages to scan.\n";
flush();

$offset = 0;
while ($offset < $totalPages) {
    $pages = $db->fetchAll(
        "SELECT id, title, content FROM content_pages WHERE site_id = ? LIMIT ? OFFSET ?",
        [$SITE_ID, $batchSize, $offset]
    );

    if (empty($pages)) break;

    foreach ($pages as $page) {
        $stats['pages_scanned']++;

        if (empty($page->content)) continue;

        $result = cleanWordPressContent($page->content);

        if ($result['changed']) {
            $stats['pages_updated']++;

            if (!$dryRun) {
                $db->query("UPDATE content_pages SET content = ? WHERE id = ?", [$result['content'], $page->id]);
            }

            if ($verbose) {
                echo "  Page ID {$page->id}: " . implode(', ', $result['changes']) . "\n";
            }
        }
    }

    $offset += $batchSize;
}
echo "  Done. {$stats['pages_updated']} pages updated.\n\n";

// ============================================================================
// RESULTS
// ============================================================================

echo "=========================================\n";
echo " RESULTS\n";
echo "=========================================\n";
echo " Articles:  {$stats['articles_scanned']} scanned, {$stats['articles_updated']} updated\n";
echo " Reviews:   {$stats['reviews_scanned']} scanned, {$stats['reviews_updated']} updated\n";
echo " Listicles: {$stats['listicles_scanned']} scanned, {$stats['listicles_updated']} updated\n";
echo " Pages:     {$stats['pages_scanned']} scanned, {$stats['pages_updated']} updated\n";
echo "=========================================\n";

$totalUpdated = $stats['articles_updated'] + $stats['reviews_updated'] + $stats['listicles_updated'] + $stats['pages_updated'];
echo " Total Updated: {$totalUpdated}\n";
echo "=========================================\n";

if ($dryRun) {
    echo "\n(Dry run - no changes made)\n";
    echo "Run without --dry-run to apply changes.\n";
}

echo "\nDone!\n";
