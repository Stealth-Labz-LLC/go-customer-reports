<?php
/**
 * Add Internal Links CLI
 * Scans articles and adds links to other articles when titles are mentioned
 *
 * Usage: php cli/add-internal-links.php [--dry-run] [--batch=100] [--offset=0]
 */

require_once __DIR__ . '/../app/bootstrap.php';

use App\Core\Database;

// Parse args
$dryRun = in_array('--dry-run', $argv);
$batch = 500;
$offset = 0;

foreach ($argv as $arg) {
    if (strpos($arg, '--batch=') === 0) $batch = (int)substr($arg, 8);
    if (strpos($arg, '--offset=') === 0) $offset = (int)substr($arg, 9);
}

$db = Database::getInstance();
$siteId = 1;

echo "Internal Link Builder\n";
echo $dryRun ? "DRY RUN\n" : "LIVE RUN\n";
echo "Batch: {$batch} | Offset: {$offset}\n";
echo str_repeat('-', 50) . "\n";

// Get total count
$total = $db->fetchOne("SELECT COUNT(*) as cnt FROM content_articles WHERE site_id = ? AND status = 'published'", [$siteId])->cnt;
echo "Total articles: {$total}\n\n";

// Build link map from ALL articles (we need this for matching)
echo "Building link index... ";
$allArticles = $db->fetchAll("
    SELECT a.id, a.slug, a.title, c.slug as category_slug
    FROM content_articles a
    LEFT JOIN content_categories c ON a.primary_category_id = c.id
    WHERE a.site_id = ? AND a.status = 'published' AND c.slug IS NOT NULL
", [$siteId]);

$linkMap = [];
foreach ($allArticles as $article) {
    $title = trim($article->title);
    if (str_word_count($title) >= 3 && strlen($title) >= 15) {
        $linkMap[$article->id] = [
            'url' => "/category/{$article->category_slug}/{$article->slug}",
            'title' => $title,
            'pattern' => '/\b(' . preg_quote($title, '/') . ')\b(?![^<]*<\/a>)/i'
        ];
    }
}
echo count($linkMap) . " indexed\n\n";

// Process batch
$articles = $db->fetchAll("
    SELECT a.id, a.title, a.content
    FROM content_articles a
    LEFT JOIN content_categories c ON a.primary_category_id = c.id
    WHERE a.site_id = ? AND a.status = 'published' AND c.slug IS NOT NULL
    ORDER BY a.id
    LIMIT ? OFFSET ?
", [$siteId, $batch, $offset]);

echo "Processing articles {$offset} to " . ($offset + count($articles)) . "...\n\n";

$updated = 0;
$linksAdded = 0;

foreach ($articles as $i => $article) {
    $content = $article->content;
    $originalContent = $content;
    $articleLinks = 0;

    foreach ($linkMap as $targetId => $linkData) {
        if ($targetId === $article->id) continue;
        if (strpos($content, $linkData['url']) !== false) continue;

        $newContent = preg_replace($linkData['pattern'], '<a href="' . $linkData['url'] . '">$1</a>', $content, 1, $count);

        if ($count > 0) {
            $content = $newContent;
            $articleLinks++;
            $linksAdded++;
            if ($articleLinks >= 5) break;
        }
    }

    if ($content !== $originalContent) {
        echo "[" . ($offset + $i + 1) . "] #{$article->id}: +{$articleLinks} links\n";
        if (!$dryRun) {
            $db->query("UPDATE content_articles SET content = ? WHERE id = ?", [$content, $article->id]);
        }
        $updated++;
    }

    // Progress every 100
    if (($i + 1) % 100 === 0) {
        echo "... processed " . ($offset + $i + 1) . "\n";
    }
}

echo "\n" . str_repeat('-', 50) . "\n";
echo "Batch complete: {$updated} articles updated, {$linksAdded} links\n";

$nextOffset = $offset + $batch;
if ($nextOffset < $total) {
    echo "\nNext batch: php cli/add-internal-links.php" . ($dryRun ? " --dry-run" : "") . " --offset={$nextOffset}\n";
} else {
    echo "\nAll batches complete!\n";
}
