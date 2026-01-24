<?php
/**
 * WordPress to Customer Reports Migration Script
 *
 * Usage:
 *   php database/migrate-wp.php              # Run migration
 *   php database/migrate-wp.php --dry-run    # Preview only
 *   php database/migrate-wp.php --limit=10   # Import first 10 posts
 */

require_once dirname(__DIR__) . '/app/bootstrap.php';

use App\Core\Database;

// Parse CLI args
$dryRun = in_array('--dry-run', $argv ?? []);
$limit = null;
foreach ($argv ?? [] as $arg) {
    if (str_starts_with($arg, '--limit=')) {
        $limit = (int) str_replace('--limit=', '', $arg);
    }
}

echo "=== WordPress Migration for customer-reports.org ===\n";
echo $dryRun ? "[DRY RUN MODE]\n\n" : "\n";

// Load secrets
$secrets = require dirname(__DIR__) . '/config/secrets.php';

// Connect to content DB
$db = Database::getInstance();

// Connect to WordPress DB
$wpHost = $secrets['db_host'] ?? 'localhost';
$wpDb = $secrets['wp_db_name'] ?? 'customerreports_dev';
$wpUser = $secrets['wp_db_user'] ?? $secrets['db_user'];
$wpPass = $secrets['wp_db_pass'] ?? $secrets['db_pass'];

try {
    $wp = Database::connect($wpHost, $wpDb, $wpUser, $wpPass);
} catch (\Exception $e) {
    die("Failed to connect to WordPress DB ({$wpDb}): " . $e->getMessage() . "\n");
}

echo "Connected to WordPress DB: {$wpDb}\n";

// Get site ID
$site = $db->fetchOne("SELECT id FROM content_sites WHERE domain = 'customer-reports.org'");
if (!$site) {
    die("Site 'customer-reports.org' not found in content_sites. Run schema.sql first.\n");
}
$siteId = $site->id;
echo "Site ID: {$siteId}\n\n";

// Stats
$stats = ['articles' => 0, 'reviews' => 0, 'listicles' => 0, 'pages' => 0, 'categories' => 0, 'skipped' => 0];

// ============================
// STEP 1: Import Categories
// ============================
echo "--- Importing Categories ---\n";

$wpCategories = $wp->fetchAll(
    "SELECT t.term_id, t.name, t.slug, tt.description, tt.parent
     FROM wp_terms t
     JOIN wp_term_taxonomy tt ON t.term_id = tt.term_id
     WHERE tt.taxonomy = 'category'"
);

foreach ($wpCategories as $cat) {
    if ($dryRun) {
        echo "  [category] {$cat->name} ({$cat->slug})\n";
    } else {
        $existing = $db->fetchOne(
            "SELECT id FROM content_categories WHERE site_id = ? AND slug = ?",
            [$siteId, $cat->slug]
        );

        if (!$existing) {
            $db->query(
                "INSERT INTO content_categories (site_id, slug, name, description) VALUES (?, ?, ?, ?)",
                [$siteId, $cat->slug, $cat->name, $cat->description ?? '']
            );
        }
    }
    $stats['categories']++;
}
echo "  Found: {$stats['categories']} categories\n\n";

// ============================
// STEP 2: Import Posts
// ============================
echo "--- Importing Posts ---\n";

$sql = "SELECT ID, post_title, post_name, post_content, post_excerpt, post_date, post_type, post_author, post_status
        FROM wp_posts
        WHERE post_status = 'publish'
        AND post_type IN ('post', 'page')
        ORDER BY post_date DESC";

if ($limit) {
    $sql .= " LIMIT {$limit}";
}

$posts = $wp->fetchAll($sql);
echo "  Found: " . count($posts) . " published posts\n\n";

foreach ($posts as $post) {
    $type = classifyContent($post);
    $content = cleanContent($post->post_content);
    $meta = getPostMeta($wp, $post->ID);
    $categories = getPostCategories($wp, $post->ID);
    $authorName = getAuthorName($wp, $post->post_author);

    if ($dryRun) {
        echo "  [{$type}] {$post->post_title} ({$post->post_name})\n";
        $stats[$type . 's']++;
        continue;
    }

    switch ($type) {
        case 'review':
            importReview($db, $siteId, $post, $content, $meta, $categories, $authorName);
            $stats['reviews']++;
            break;
        case 'listicle':
            importListicle($db, $siteId, $post, $content, $meta, $categories, $authorName);
            $stats['listicles']++;
            break;
        case 'page':
            importPage($db, $siteId, $post, $content, $meta);
            $stats['pages']++;
            break;
        default:
            importArticle($db, $siteId, $post, $content, $meta, $categories, $authorName);
            $stats['articles']++;
    }

    echo "  [{$type}] {$post->post_title}\n";
}

// ============================
// Summary
// ============================
echo "\n=== Migration Complete ===\n";
echo "Articles:   {$stats['articles']}\n";
echo "Reviews:    {$stats['reviews']}\n";
echo "Listicles:  {$stats['listicles']}\n";
echo "Pages:      {$stats['pages']}\n";
echo "Categories: {$stats['categories']}\n";
echo "Skipped:    {$stats['skipped']}\n";

// ============================
// Functions
// ============================

function classifyContent($post): string
{
    $content = $post->post_content ?? '';
    $title = $post->post_title ?? '';
    $slug = $post->post_name ?? '';
    $postType = $post->post_type ?? 'post';

    // Page detection
    if ($postType === 'page' || in_array($slug, ['about', 'contact', 'terms', 'privacy', 'home', 'about-us', 'contact-us'])) {
        return 'page';
    }

    // Review detection
    $reviewPatterns = [
        '/class=["\'].*?pros.*?["\']/',
        '/class=["\'].*?cons.*?["\']/',
        '/star-rating/',
        '/rating_overall|rating_value/',
        '/affiliate_url|product_url/',
    ];
    foreach ($reviewPatterns as $pattern) {
        if (preg_match($pattern, $content)) {
            return 'review';
        }
    }

    // Listicle detection
    $listiclePatterns = [
        '/^top[\s\-]?\d+/i',
        '/^best[\s\-]/i',
        '/^\d+[\s\-]best/i',
    ];
    foreach ($listiclePatterns as $pattern) {
        if (preg_match($pattern, $title)) {
            return 'listicle';
        }
    }

    // Check for numbered H2s in content
    if (preg_match_all('/<h2[^>]*>\s*\d+\.\s*/i', $content) >= 3) {
        return 'listicle';
    }

    return 'article';
}

function cleanContent(string $content): string
{
    $content = preg_replace('/<!-- wp:[^>]+-->/', '', $content);
    $content = preg_replace('/<!-- \/wp:[^>]+-->/', '', $content);
    $content = preg_replace('/<p>\s*<\/p>/', '', $content);
    $content = preg_replace('/\n{3,}/', "\n\n", $content);
    return trim($content);
}

function getPostMeta($wp, int $postId): array
{
    $rows = $wp->fetchAll(
        "SELECT meta_key, meta_value FROM wp_postmeta WHERE post_id = ?",
        [$postId]
    );
    $meta = [];
    foreach ($rows as $row) {
        $meta[$row->meta_key] = $row->meta_value;
    }
    return $meta;
}

function getPostCategories($wp, int $postId): array
{
    return $wp->fetchAll(
        "SELECT t.slug FROM wp_terms t
         JOIN wp_term_taxonomy tt ON t.term_id = tt.term_id
         JOIN wp_term_relationships tr ON tt.term_taxonomy_id = tr.term_taxonomy_id
         WHERE tr.object_id = ? AND tt.taxonomy = 'category'",
        [$postId]
    );
}

function getAuthorName($wp, int $authorId): ?string
{
    $author = $wp->fetchOne("SELECT display_name FROM wp_users WHERE ID = ?", [$authorId]);
    return $author->display_name ?? null;
}

function extractExcerpt(string $content, int $length = 160): string
{
    $text = strip_tags($content);
    $text = preg_replace('/\s+/', ' ', $text);
    $text = trim($text);
    return mb_substr($text, 0, $length);
}

function extractPros(string $content): array
{
    $pros = [];
    if (preg_match('/Pros.*?<ul[^>]*>(.*?)<\/ul>/is', $content, $matches)) {
        preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $matches[1], $items);
        $pros = array_map('strip_tags', $items[1] ?? []);
    }
    return array_values(array_filter(array_map('trim', $pros)));
}

function extractCons(string $content): array
{
    $cons = [];
    if (preg_match('/Cons.*?<ul[^>]*>(.*?)<\/ul>/is', $content, $matches)) {
        preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $matches[1], $items);
        $cons = array_map('strip_tags', $items[1] ?? []);
    }
    return array_values(array_filter(array_map('trim', $cons)));
}

function importArticle($db, int $siteId, $post, string $content, array $meta, array $categories, ?string $authorName): void
{
    $db->query(
        "INSERT INTO content_articles (site_id, slug, title, excerpt, content, meta_title, meta_description, status, published_at, author_name)
         VALUES (?, ?, ?, ?, ?, ?, ?, 'published', ?, ?)
         ON DUPLICATE KEY UPDATE title=VALUES(title), content=VALUES(content), updated_at=NOW()",
        [
            $siteId,
            $post->post_name,
            $post->post_title,
            $post->post_excerpt ?: extractExcerpt($content),
            $content,
            $meta['_yoast_wpseo_title'] ?? null,
            $meta['_yoast_wpseo_metadesc'] ?? null,
            $post->post_date,
            $authorName,
        ]
    );

    // Get article ID and attach categories
    $article = $db->fetchOne("SELECT id FROM content_articles WHERE site_id = ? AND slug = ?", [$siteId, $post->post_name]);
    if ($article) {
        attachCategories($db, 'content_article_category', 'article_id', $article->id, $siteId, $categories);
    }
}

function importReview($db, int $siteId, $post, string $content, array $meta, array $categories, ?string $authorName): void
{
    $pros = extractPros($post->post_content);
    $cons = extractCons($post->post_content);

    $db->query(
        "INSERT INTO content_reviews (site_id, slug, name, short_description, content, affiliate_url, rating_overall, pros, cons, meta_title, meta_description, status, published_at, author_name)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'published', ?, ?)
         ON DUPLICATE KEY UPDATE name=VALUES(name), content=VALUES(content), updated_at=NOW()",
        [
            $siteId,
            $post->post_name,
            $post->post_title,
            $post->post_excerpt ?: extractExcerpt($content),
            $content,
            $meta['affiliate_url'] ?? ($meta['product_url'] ?? null),
            $meta['rating_overall'] ?? ($meta['_rating_overall'] ?? null),
            !empty($pros) ? json_encode($pros) : null,
            !empty($cons) ? json_encode($cons) : null,
            $meta['_yoast_wpseo_title'] ?? null,
            $meta['_yoast_wpseo_metadesc'] ?? null,
            $post->post_date,
            $authorName,
        ]
    );

    $review = $db->fetchOne("SELECT id FROM content_reviews WHERE site_id = ? AND slug = ?", [$siteId, $post->post_name]);
    if ($review) {
        attachCategories($db, 'content_review_category', 'review_id', $review->id, $siteId, $categories);
    }
}

function importListicle($db, int $siteId, $post, string $content, array $meta, array $categories, ?string $authorName): void
{
    // Extract list items from numbered headings
    $items = [];
    preg_match_all('/<h[23][^>]*>\s*(\d+)\.\s*(.+?)<\/h[23]>/is', $post->post_content, $matches, PREG_SET_ORDER);
    foreach ($matches as $match) {
        $items[] = [
            'rank' => (int) $match[1],
            'name' => strip_tags($match[2]),
            'description' => '',
            'affiliate_url' => null,
        ];
    }

    // Extract intro (before first H2) and conclusion (after "Conclusion" heading)
    $intro = '';
    if (preg_match('/^(.*?)(?=<h2)/is', $content, $m)) {
        $intro = trim($m[1]);
    }
    $conclusion = '';
    if (preg_match('/<h[23][^>]*>.*?(Conclusion|Final Thoughts|Our Verdict).*?<\/h[23]>(.*?)$/is', $content, $m)) {
        $conclusion = trim($m[2]);
    }

    $db->query(
        "INSERT INTO content_listicles (site_id, slug, title, excerpt, introduction, conclusion, items, meta_title, meta_description, status, published_at, author_name)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'published', ?, ?)
         ON DUPLICATE KEY UPDATE title=VALUES(title), items=VALUES(items), updated_at=NOW()",
        [
            $siteId,
            $post->post_name,
            $post->post_title,
            $post->post_excerpt ?: extractExcerpt($content),
            $intro,
            $conclusion,
            json_encode($items),
            $meta['_yoast_wpseo_title'] ?? null,
            $meta['_yoast_wpseo_metadesc'] ?? null,
            $post->post_date,
            $authorName,
        ]
    );

    $listicle = $db->fetchOne("SELECT id FROM content_listicles WHERE site_id = ? AND slug = ?", [$siteId, $post->post_name]);
    if ($listicle) {
        attachCategories($db, 'content_listicle_category', 'listicle_id', $listicle->id, $siteId, $categories);
    }
}

function importPage($db, int $siteId, $post, string $content, array $meta): void
{
    $template = match ($post->post_name) {
        'home' => 'home',
        'about', 'about-us' => 'about',
        'contact', 'contact-us' => 'contact',
        'terms', 'terms-of-service', 'tos' => 'terms',
        'privacy', 'privacy-policy' => 'privacy',
        default => 'default',
    };

    $db->query(
        "INSERT INTO content_pages (site_id, slug, title, content, template, meta_title, meta_description, status)
         VALUES (?, ?, ?, ?, ?, ?, ?, 'published')
         ON DUPLICATE KEY UPDATE title=VALUES(title), content=VALUES(content), updated_at=NOW()",
        [
            $siteId,
            $post->post_name,
            $post->post_title,
            $content,
            $template,
            $meta['_yoast_wpseo_title'] ?? null,
            $meta['_yoast_wpseo_metadesc'] ?? null,
        ]
    );
}

function attachCategories($db, string $pivotTable, string $foreignKey, int $entityId, int $siteId, array $wpCategories): void
{
    // Clear existing
    $db->query("DELETE FROM {$pivotTable} WHERE {$foreignKey} = ?", [$entityId]);

    foreach ($wpCategories as $cat) {
        $category = $db->fetchOne(
            "SELECT id FROM content_categories WHERE site_id = ? AND slug = ?",
            [$siteId, $cat->slug]
        );
        if ($category) {
            $db->query(
                "INSERT IGNORE INTO {$pivotTable} ({$foreignKey}, category_id) VALUES (?, ?)",
                [$entityId, $category->id]
            );
        }
    }
}
