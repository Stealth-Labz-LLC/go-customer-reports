<?php
/**
 * Add Internal Links CLI
 * Links keywords to articles in matching categories
 *
 * Usage: php cli/add-internal-links.php [--dry-run] [--batch=500] [--offset=0]
 */

require_once __DIR__ . '/../app/bootstrap.php';

use App\Core\Database;

$dryRun = in_array('--dry-run', $argv);
$batch = 500;
$offset = 0;

foreach ($argv as $arg) {
    if (strpos($arg, '--batch=') === 0) $batch = (int)substr($arg, 8);
    if (strpos($arg, '--offset=') === 0) $offset = (int)substr($arg, 9);
}

$db = Database::getInstance();
$siteId = 1;

// Category keywords => category_id
$categoryKeywords = [
    // Weight Loss (17)
    'weight loss' => 17, 'lose weight' => 17, 'fat loss' => 17, 'burning calories' => 17,
    'diet plan' => 17, 'slimming' => 17,

    // Nutrition (20)
    'nutrition' => 20, 'vitamins' => 20, 'nutrients' => 20, 'protein intake' => 20,
    'healthy eating' => 20, 'dietary' => 20, 'macros' => 20,

    // Training (21)
    'workout' => 21, 'exercise' => 21, 'training' => 21, 'strength training' => 21,
    'cardio' => 21, 'HIIT' => 21, 'resistance band' => 21, 'dumbbell' => 21,

    // Health & Wellness (23)
    'wellness' => 23, 'mental health' => 23, 'self-care' => 23, 'stress relief' => 23,
    'meditation' => 23, 'mindfulness' => 23, 'sleep quality' => 23,

    // Beauty (15)
    'skincare' => 15, 'beauty routine' => 15, 'anti-aging' => 15, 'moisturizer' => 15,
    'cosmetics' => 15, 'makeup' => 15,

    // Food (16)
    'recipes' => 16, 'cooking' => 16, 'meal prep' => 16, 'ingredients' => 16,
    'delicious' => 16, 'kitchen' => 16,

    // Culinary (13)
    'culinary' => 13, 'gourmet' => 13, 'chef' => 13, 'cuisine' => 13,

    // Sustainable Living (11)
    'sustainable' => 11, 'eco-friendly' => 11, 'green living' => 11, 'zero waste' => 11,
    'organic' => 11, 'environmental' => 11,

    // Travel (19)
    'travel' => 19, 'vacation' => 19, 'destination' => 19, 'trip' => 19,
    'tourism' => 19, 'getaway' => 19,

    // Senior Health (39)
    'senior health' => 39, 'elderly' => 39, 'aging' => 39, 'retirement' => 39,
    'over 50' => 39, 'golden years' => 39,

    // Behavior (22)
    'habits' => 22, 'behavior' => 22, 'motivation' => 22, 'discipline' => 22,
    'mindset' => 22, 'goal setting' => 22,
];

echo "Internal Link Builder (Keyword Mode)\n";
echo $dryRun ? "DRY RUN\n" : "LIVE RUN\n";
echo "Batch: {$batch} | Offset: {$offset}\n";
echo str_repeat('-', 50) . "\n";

// Pre-fetch one random article per category
echo "Loading target articles per category...\n";
$categoryArticles = [];
foreach (array_unique(array_values($categoryKeywords)) as $catId) {
    $articles = $db->fetchAll("
        SELECT a.slug, c.slug as cat_slug, a.title
        FROM content_articles a
        JOIN content_categories c ON a.primary_category_id = c.id
        WHERE a.site_id = ? AND a.primary_category_id = ? AND a.status = 'published'
        ORDER BY RAND() LIMIT 20
    ", [$siteId, $catId]);

    if (!empty($articles)) {
        $categoryArticles[$catId] = $articles;
        echo "  Category {$catId}: " . count($articles) . " articles\n";
    }
}

// Get total
$total = $db->fetchOne("SELECT COUNT(*) as cnt FROM content_articles WHERE site_id = ? AND status = 'published'", [$siteId])->cnt;
echo "\nTotal articles: {$total}\n";

// Process batch
$articles = $db->fetchAll("
    SELECT a.id, a.title, a.content, a.primary_category_id
    FROM content_articles a
    WHERE a.site_id = ? AND a.status = 'published'
    ORDER BY a.id
    LIMIT ? OFFSET ?
", [$siteId, $batch, $offset]);

echo "Processing " . count($articles) . " articles...\n\n";

$updated = 0;
$linksAdded = 0;

foreach ($articles as $i => $article) {
    $content = $article->content;
    $originalContent = $content;
    $articleLinks = 0;
    $usedCategories = [$article->primary_category_id]; // Don't link to own category

    foreach ($categoryKeywords as $keyword => $catId) {
        // Skip own category and already-used categories
        if (in_array($catId, $usedCategories)) continue;
        if (!isset($categoryArticles[$catId])) continue;

        // Case-insensitive search, not inside existing links or tags
        $pattern = '/(?<!["\'>\/])\\b(' . preg_quote($keyword, '/') . ')\\b(?![^<]*<\/a>)/i';

        if (preg_match($pattern, $content)) {
            // Pick random article from this category
            $target = $categoryArticles[$catId][array_rand($categoryArticles[$catId])];
            $url = "/category/{$target->cat_slug}/{$target->slug}";

            // Replace first occurrence only
            $content = preg_replace($pattern, '<a href="' . $url . '">$1</a>', $content, 1);
            $articleLinks++;
            $linksAdded++;
            $usedCategories[] = $catId;

            // Max 3 cross-category links per article
            if ($articleLinks >= 3) break;
        }
    }

    if ($content !== $originalContent) {
        echo "[" . ($offset + $i + 1) . "] #{$article->id}: +{$articleLinks} links\n";
        if (!$dryRun) {
            $db->query("UPDATE content_articles SET content = ? WHERE id = ?", [$content, $article->id]);
        }
        $updated++;
    }

    if (($i + 1) % 100 === 0) {
        echo "... processed " . ($offset + $i + 1) . "\n";
    }
}

echo "\n" . str_repeat('-', 50) . "\n";
echo "Updated: {$updated} articles | Links added: {$linksAdded}\n";

$nextOffset = $offset + $batch;
if ($nextOffset < $total) {
    echo "\nNext: php cli/add-internal-links.php" . ($dryRun ? " --dry-run" : "") . " --offset={$nextOffset}\n";
} else {
    echo "\nAll done!\n";
}
