<?php
/**
 * Add Internal Links CLI
 * Scans articles and adds links to other articles when titles are mentioned
 *
 * Usage: php cli/add-internal-links.php [--dry-run]
 */

require_once __DIR__ . '/../app/bootstrap.php';

use App\Core\Database;

$dryRun = in_array('--dry-run', $argv);
$db = Database::getInstance();
$siteId = 1;

echo "Internal Link Builder\n";
echo $dryRun ? "DRY RUN - No changes will be made\n" : "LIVE RUN - Will update database\n";
echo str_repeat('-', 50) . "\n\n";

// Get all published articles with their categories
$articles = $db->fetchAll("
    SELECT a.id, a.slug, a.title, a.content, c.slug as category_slug
    FROM content_articles a
    LEFT JOIN content_categories c ON a.primary_category_id = c.id
    WHERE a.site_id = ? AND a.status = 'published' AND c.slug IS NOT NULL
", [$siteId]);

echo "Found " . count($articles) . " articles\n\n";

// Build lookup: title => url
$linkMap = [];
foreach ($articles as $article) {
    $title = strtolower(trim($article->title));
    // Skip very short titles (< 4 words) to avoid false matches
    if (str_word_count($title) >= 3) {
        $linkMap[$title] = [
            'id' => $article->id,
            'url' => "/category/{$article->category_slug}/{$article->slug}",
            'title' => $article->title
        ];
    }
}

echo "Indexed " . count($linkMap) . " articles for linking\n\n";

$updated = 0;
$linksAdded = 0;

foreach ($articles as $article) {
    $content = $article->content;
    $originalContent = $content;
    $articleLinks = 0;

    foreach ($linkMap as $searchTitle => $linkData) {
        // Don't link to self
        if ($linkData['id'] === $article->id) continue;

        // Skip if already contains a link to this article
        if (strpos($content, $linkData['url']) !== false) continue;

        // Case-insensitive search for title in content (not inside existing links)
        $pattern = '/(?<!["\'>])(' . preg_quote($linkData['title'], '/') . ')(?![^<]*<\/a>)/i';

        // Only replace first occurrence
        $newContent = preg_replace($pattern, '<a href="' . $linkData['url'] . '">$1</a>', $content, 1, $count);

        if ($count > 0) {
            $content = $newContent;
            $articleLinks++;
            $linksAdded++;

            // Limit links per article to avoid over-optimization
            if ($articleLinks >= 5) break;
        }
    }

    if ($content !== $originalContent) {
        echo "Article #{$article->id}: +{$articleLinks} links - {$article->title}\n";

        if (!$dryRun) {
            $db->query("UPDATE content_articles SET content = ? WHERE id = ?", [$content, $article->id]);
        }
        $updated++;
    }
}

echo "\n" . str_repeat('-', 50) . "\n";
echo "Updated: {$updated} articles\n";
echo "Links added: {$linksAdded}\n";

if ($dryRun) {
    echo "\nRun without --dry-run to apply changes.\n";
}
