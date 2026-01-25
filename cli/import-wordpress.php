#!/usr/bin/env php
<?php
/**
 * WordPress Content Importer
 *
 * Imports content from WordPress databases into Customer Reports.
 * All content merges into site_id = 1 (customer-reports.org)
 *
 * Usage:
 *   php cli/import-wordpress.php                    # Import all sites
 *   php cli/import-wordpress.php --dry-run          # Preview without importing
 *   php cli/import-wordpress.php --site=homevibeguide.com  # Import single site
 *   php cli/import-wordpress.php --site=homevibeguide.com --dry-run
 */

// Must run from CLI
if (php_sapi_name() !== 'cli') {
    die("This script must be run from the command line.\n");
}

// Bootstrap
require_once __DIR__ . '/../app/bootstrap.php';

use App\Core\Database;

// ============================================================================
// CONFIGURATION
// ============================================================================

$SITE_ID = 1; // All content goes to customer-reports.org

// WordPress database mapping (domain => database name)
$WP_DATABASES = [
    'customer-reports.org' => 'customerreports_dev',
    'homevibeguide.com' => 'homevibeguide_dev',
    'itsavibefitness.com' => 'itsavibefitness_dev',
    'petsvibeguide.com' => 'petsvibeguide_dev',
    'seniortimesguide.com' => 'seniortimesguide_dev',
    'cleanwatersguide.com' => 'cleanwatersguide_dev',
    'beautyvibeguide.com' => 'beautyvibeguide_dev',
    'stealthlabz.com' => 'stealthlabz_SLDB',
    'shopper-guide.com' => 'shopperguide_dev',
    'evergreenevolutions.com' => 'evergreenevoluti_dev',
];

// ============================================================================
// PARSE ARGUMENTS
// ============================================================================

$options = getopt('', ['dry-run', 'site:', 'help']);

if (isset($options['help'])) {
    echo "WordPress Content Importer\n";
    echo "==========================\n\n";
    echo "Usage:\n";
    echo "  php cli/import-wordpress.php [options]\n\n";
    echo "Options:\n";
    echo "  --dry-run       Preview import without writing to database\n";
    echo "  --site=DOMAIN   Import only from specified domain\n";
    echo "  --help          Show this help message\n\n";
    echo "Examples:\n";
    echo "  php cli/import-wordpress.php --dry-run\n";
    echo "  php cli/import-wordpress.php --site=homevibeguide.com\n";
    exit(0);
}

$dryRun = isset($options['dry-run']);
$singleSite = $options['site'] ?? null;

if ($singleSite && !isset($WP_DATABASES[$singleSite])) {
    echo "Error: Unknown site '{$singleSite}'\n";
    echo "Available sites:\n";
    foreach (array_keys($WP_DATABASES) as $domain) {
        echo "  - {$domain}\n";
    }
    exit(1);
}

// ============================================================================
// IMPORTER CLASS
// ============================================================================

class WordPressImporter
{
    private Database $mainDb;
    private \PDO $wpPdo;
    private int $siteId;
    private bool $dryRun;

    private array $stats = [
        'articles' => 0,
        'reviews' => 0,
        'listicles' => 0,
        'pages' => 0,
        'categories' => 0,
        'skipped' => 0,
    ];

    private array $categoryMap = []; // WP slug => our category ID

    public function __construct(int $siteId, bool $dryRun = false)
    {
        $this->mainDb = Database::getInstance();
        $this->siteId = $siteId;
        $this->dryRun = $dryRun;
    }

    public function import(string $domain, string $wpDatabase): array
    {
        $this->stats = ['articles' => 0, 'reviews' => 0, 'listicles' => 0, 'pages' => 0, 'categories' => 0, 'skipped' => 0];
        $this->categoryMap = [];

        echo "\n=== Importing: {$domain} ({$wpDatabase}) ===\n";

        // Connect to WordPress database
        $secrets = require dirname(__DIR__) . '/config/secrets.php';
        $this->wpPdo = new \PDO(
            "mysql:host={$secrets['db_host']};dbname={$wpDatabase};charset=utf8mb4",
            $secrets['db_user'],
            $secrets['db_pass'],
            [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ]
        );

        // Import in order
        $this->importCategories();
        $this->importPosts();

        return $this->stats;
    }

    // ========================================================================
    // CATEGORIES
    // ========================================================================

    private function importCategories(): void
    {
        echo "  Importing categories...\n";

        $stmt = $this->wpPdo->prepare("
            SELECT t.term_id, t.name, t.slug, tt.description, tt.parent
            FROM wp_terms t
            JOIN wp_term_taxonomy tt ON t.term_id = tt.term_id
            WHERE tt.taxonomy = 'category'
        ");
        $stmt->execute();
        $wpCategories = $stmt->fetchAll();

        foreach ($wpCategories as $wpCat) {
            // Check if category already exists (by slug)
            $existing = $this->mainDb->fetchOne(
                "SELECT id FROM content_categories WHERE site_id = ? AND slug = ?",
                [$this->siteId, $wpCat->slug]
            );

            if ($existing) {
                $this->categoryMap[$wpCat->slug] = $existing->id;
                continue;
            }

            if (!$this->dryRun) {
                $this->mainDb->query(
                    "INSERT INTO content_categories (site_id, slug, name, description) VALUES (?, ?, ?, ?)",
                    [$this->siteId, $wpCat->slug, $wpCat->name, $wpCat->description ?: null]
                );
                $catId = $this->mainDb->pdo()->lastInsertId();
                $this->categoryMap[$wpCat->slug] = $catId;
            }

            $this->stats['categories']++;
        }

        echo "    Categories: {$this->stats['categories']} new\n";
    }

    // ========================================================================
    // POSTS
    // ========================================================================

    private function importPosts(): void
    {
        echo "  Importing posts...\n";

        $stmt = $this->wpPdo->prepare("
            SELECT ID, post_title, post_name, post_content, post_excerpt, post_date, post_type, post_author
            FROM wp_posts
            WHERE post_status = 'publish'
            AND post_type IN ('post', 'page', 'casino', 'game', 'organization')
        ");
        $stmt->execute();
        $posts = $stmt->fetchAll();

        $total = count($posts);
        $current = 0;

        foreach ($posts as $post) {
            $current++;
            $type = $this->classifyContent($post);

            // Check for duplicate slug
            $table = match($type) {
                'review' => 'content_reviews',
                'listicle' => 'content_listicles',
                'page' => 'content_pages',
                default => 'content_articles',
            };

            $existing = $this->mainDb->fetchOne(
                "SELECT id FROM {$table} WHERE site_id = ? AND slug = ?",
                [$this->siteId, $post->post_name]
            );

            if ($existing) {
                $this->stats['skipped']++;
                continue;
            }

            // Get metadata
            $meta = $this->getPostMeta($post->ID);
            $categoryIds = $this->getPostCategoryIds($post->ID);

            // Clean content
            $content = $this->cleanContent($post->post_content);
            $excerpt = $post->post_excerpt ?: $this->extractExcerpt($content);

            // Get featured image
            $featuredImage = $this->getFeaturedImage($post->ID);

            // Import based on type
            switch ($type) {
                case 'review':
                    $this->importReview($post, $meta, $content, $excerpt, $featuredImage, $categoryIds);
                    break;
                case 'listicle':
                    $this->importListicle($post, $meta, $content, $excerpt, $featuredImage, $categoryIds);
                    break;
                case 'page':
                    $this->importPage($post, $meta, $content);
                    break;
                default:
                    $this->importArticle($post, $meta, $content, $excerpt, $featuredImage, $categoryIds);
            }

            // Progress
            if ($current % 50 === 0) {
                echo "    Processed {$current}/{$total}...\n";
            }
        }

        echo "    Articles: {$this->stats['articles']}, Reviews: {$this->stats['reviews']}, ";
        echo "Listicles: {$this->stats['listicles']}, Pages: {$this->stats['pages']}, Skipped: {$this->stats['skipped']}\n";
    }

    // ========================================================================
    // CONTENT CLASSIFICATION
    // ========================================================================

    private function classifyContent($post): string
    {
        $content = $post->post_content ?? '';
        $title = $post->post_title ?? '';
        $slug = $post->post_name ?? '';
        $postType = $post->post_type ?? 'post';

        // Page detection
        if ($postType === 'page' || in_array($slug, ['about', 'about-us', 'contact', 'contact-us', 'terms', 'terms-of-service', 'privacy', 'privacy-policy', 'home'])) {
            return 'page';
        }

        // Custom post types that are reviews
        if (in_array($postType, ['casino', 'organization', 'game'])) {
            return 'review';
        }

        // Review detection patterns
        $reviewPatterns = [
            '/class=["\'].*?pros.*?["\']/',
            '/class=["\'].*?cons.*?["\']/',
            '/star-rating/',
            '/rating_overall|rating_value/',
            '/affiliate_url|product_url/',
            '/<div[^>]*space-organization/',
        ];

        foreach ($reviewPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return 'review';
            }
        }

        // Listicle detection patterns
        $listiclePatterns = [
            '/^top[\s-]?\d+/i',
            '/^best[\s-]/i',
            '/^\d+[\s-]best/i',
            '/<h2[^>]*>\s*\d+\.\s*/i',
        ];

        foreach ($listiclePatterns as $pattern) {
            if (preg_match($pattern, $title) || preg_match($pattern, $content)) {
                return 'listicle';
            }
        }

        return 'article';
    }

    // ========================================================================
    // IMPORT METHODS
    // ========================================================================

    private function importArticle($post, array $meta, string $content, string $excerpt, ?string $featuredImage, array $categoryIds): void
    {
        if ($this->dryRun) {
            $this->stats['articles']++;
            return;
        }

        $this->mainDb->query(
            "INSERT INTO content_articles
            (site_id, slug, title, excerpt, content, featured_image, meta_title, meta_description, author_name, status, published_at, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'published', ?, NOW(), NOW())",
            [
                $this->siteId,
                $post->post_name,
                $post->post_title,
                $excerpt,
                $content,
                $featuredImage,
                $meta['_yoast_wpseo_title'] ?? null,
                $meta['_yoast_wpseo_metadesc'] ?? null,
                $this->getAuthorName($post->post_author),
                $post->post_date,
            ]
        );

        $articleId = $this->mainDb->pdo()->lastInsertId();
        $this->attachCategories('content_article_category', 'article_id', $articleId, $categoryIds);

        $this->stats['articles']++;
    }

    private function importReview($post, array $meta, string $content, string $excerpt, ?string $featuredImage, array $categoryIds): void
    {
        $pros = $this->extractPros($post->post_content);
        $cons = $this->extractCons($post->post_content);
        $ratings = $this->extractRatings($meta);

        if ($this->dryRun) {
            $this->stats['reviews']++;
            return;
        }

        $this->mainDb->query(
            "INSERT INTO content_reviews
            (site_id, slug, name, brand, short_description, content, featured_image,
             price, affiliate_url, cta_text,
             rating_overall, rating_ingredients, rating_value, rating_effectiveness, rating_customer_experience,
             pros, cons, meta_title, meta_description, author_name, status, published_at, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'published', ?, NOW(), NOW())",
            [
                $this->siteId,
                $post->post_name,
                $post->post_title,
                $meta['brand'] ?? null,
                $excerpt,
                $content,
                $featuredImage,
                $meta['price'] ?? null,
                $meta['affiliate_url'] ?? $meta['product_url'] ?? null,
                $meta['cta_text'] ?? 'Check Availability',
                $ratings['overall'],
                $ratings['ingredients'],
                $ratings['value'],
                $ratings['effectiveness'],
                $ratings['customer_experience'],
                json_encode($pros),
                json_encode($cons),
                $meta['_yoast_wpseo_title'] ?? null,
                $meta['_yoast_wpseo_metadesc'] ?? null,
                $this->getAuthorName($post->post_author),
                $post->post_date,
            ]
        );

        $reviewId = $this->mainDb->pdo()->lastInsertId();
        $this->attachCategories('content_review_category', 'review_id', $reviewId, $categoryIds);

        $this->stats['reviews']++;
    }

    private function importListicle($post, array $meta, string $content, string $excerpt, ?string $featuredImage, array $categoryIds): void
    {
        $items = $this->extractListicleItems($post->post_content);
        $parts = $this->splitListicleContent($content);

        if ($this->dryRun) {
            $this->stats['listicles']++;
            return;
        }

        $this->mainDb->query(
            "INSERT INTO content_listicles
            (site_id, slug, title, excerpt, introduction, conclusion, items, featured_image,
             meta_title, meta_description, author_name, status, published_at, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'published', ?, NOW(), NOW())",
            [
                $this->siteId,
                $post->post_name,
                $post->post_title,
                $excerpt,
                $parts['introduction'],
                $parts['conclusion'],
                json_encode($items),
                $featuredImage,
                $meta['_yoast_wpseo_title'] ?? null,
                $meta['_yoast_wpseo_metadesc'] ?? null,
                $this->getAuthorName($post->post_author),
                $post->post_date,
            ]
        );

        $listicleId = $this->mainDb->pdo()->lastInsertId();
        $this->attachCategories('content_listicle_category', 'listicle_id', $listicleId, $categoryIds);

        $this->stats['listicles']++;
    }

    private function importPage($post, array $meta, string $content): void
    {
        $template = match($post->post_name) {
            'home' => 'home',
            'about', 'about-us' => 'about',
            'contact', 'contact-us' => 'contact',
            'terms', 'terms-of-service', 'tos' => 'terms',
            'privacy', 'privacy-policy' => 'privacy',
            default => 'default',
        };

        if ($this->dryRun) {
            $this->stats['pages']++;
            return;
        }

        $this->mainDb->query(
            "INSERT INTO content_pages
            (site_id, slug, title, content, template, meta_title, meta_description, status, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, 'published', NOW(), NOW())",
            [
                $this->siteId,
                $post->post_name,
                $post->post_title,
                $content,
                $template,
                $meta['_yoast_wpseo_title'] ?? null,
                $meta['_yoast_wpseo_metadesc'] ?? null,
            ]
        );

        $this->stats['pages']++;
    }

    // ========================================================================
    // HELPER METHODS
    // ========================================================================

    private function getPostMeta(int $postId): array
    {
        $stmt = $this->wpPdo->prepare("SELECT meta_key, meta_value FROM wp_postmeta WHERE post_id = ?");
        $stmt->execute([$postId]);
        $meta = [];
        foreach ($stmt->fetchAll() as $row) {
            $meta[$row->meta_key] = $row->meta_value;
        }
        return $meta;
    }

    private function getPostCategoryIds(int $postId): array
    {
        $stmt = $this->wpPdo->prepare("
            SELECT t.slug
            FROM wp_terms t
            JOIN wp_term_taxonomy tt ON t.term_id = tt.term_id
            JOIN wp_term_relationships tr ON tt.term_taxonomy_id = tr.term_taxonomy_id
            WHERE tr.object_id = ? AND tt.taxonomy = 'category'
        ");
        $stmt->execute([$postId]);

        $ids = [];
        foreach ($stmt->fetchAll() as $row) {
            if (isset($this->categoryMap[$row->slug])) {
                $ids[] = $this->categoryMap[$row->slug];
            }
        }
        return $ids;
    }

    private function attachCategories(string $table, string $column, int $contentId, array $categoryIds): void
    {
        foreach ($categoryIds as $catId) {
            $this->mainDb->query(
                "INSERT IGNORE INTO {$table} ({$column}, category_id) VALUES (?, ?)",
                [$contentId, $catId]
            );
        }
    }

    private function getFeaturedImage(int $postId): ?string
    {
        $stmt = $this->wpPdo->prepare("
            SELECT pm.meta_value
            FROM wp_postmeta pm
            JOIN wp_posts p ON pm.meta_value = p.ID
            WHERE pm.post_id = ? AND pm.meta_key = '_thumbnail_id'
        ");
        $stmt->execute([$postId]);
        $result = $stmt->fetch();

        if (!$result) return null;

        $stmt = $this->wpPdo->prepare("SELECT guid FROM wp_posts WHERE ID = ?");
        $stmt->execute([$result->meta_value]);
        $image = $stmt->fetch();

        return $image ? $image->guid : null;
    }

    private function getAuthorName(int $authorId): ?string
    {
        $stmt = $this->wpPdo->prepare("SELECT display_name FROM wp_users WHERE ID = ?");
        $stmt->execute([$authorId]);
        $author = $stmt->fetch();
        return $author ? $author->display_name : null;
    }

    private function cleanContent(string $content): string
    {
        // Remove WordPress block comments
        $content = preg_replace('/<!-- wp:[^>]+-->/', '', $content);
        $content = preg_replace('/<!-- \/wp:[^>]+-->/', '', $content);
        // Remove empty paragraphs
        $content = preg_replace('/<p>\s*<\/p>/', '', $content);
        // Clean up whitespace
        $content = preg_replace('/\n{3,}/', "\n\n", $content);
        return trim($content);
    }

    private function extractExcerpt(string $content, int $length = 160): string
    {
        $text = strip_tags($content);
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);
        return mb_strlen($text) > $length ? mb_substr($text, 0, $length) . '...' : $text;
    }

    private function extractPros(string $content): array
    {
        $pros = [];
        if (preg_match('/<div[^>]*class=["\'][^"\']*space-pros[^"\']*["\'][^>]*>.*?<ul[^>]*>(.*?)<\/ul>/is', $content, $matches)) {
            preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $matches[1], $items);
            $pros = array_map('strip_tags', $items[1]);
        }
        if (empty($pros) && preg_match('/Pros.*?<ul[^>]*>(.*?)<\/ul>/is', $content, $matches)) {
            preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $matches[1], $items);
            $pros = array_map('strip_tags', $items[1]);
        }
        return array_filter(array_map('trim', $pros));
    }

    private function extractCons(string $content): array
    {
        $cons = [];
        if (preg_match('/<div[^>]*class=["\'][^"\']*space-cons[^"\']*["\'][^>]*>.*?<ul[^>]*>(.*?)<\/ul>/is', $content, $matches)) {
            preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $matches[1], $items);
            $cons = array_map('strip_tags', $items[1]);
        }
        if (empty($cons) && preg_match('/Cons.*?<ul[^>]*>(.*?)<\/ul>/is', $content, $matches)) {
            preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $matches[1], $items);
            $cons = array_map('strip_tags', $items[1]);
        }
        return array_filter(array_map('trim', $cons));
    }

    private function extractRatings(array $meta): array
    {
        return [
            'overall' => $meta['rating_overall'] ?? $meta['_rating_overall'] ?? null,
            'ingredients' => $meta['rating_ingredients'] ?? $meta['_rating_ingredients'] ?? null,
            'value' => $meta['rating_value'] ?? $meta['_rating_value'] ?? null,
            'effectiveness' => $meta['rating_effectiveness'] ?? $meta['_rating_effectiveness'] ?? null,
            'customer_experience' => $meta['rating_customer_experience'] ?? $meta['_rating_customer_experience'] ?? null,
        ];
    }

    private function extractListicleItems(string $content): array
    {
        $items = [];
        preg_match_all('/<h[23][^>]*>\s*(\d+)\.\s*(.+?)<\/h[23]>/is', $content, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $items[] = [
                'rank' => (int) $match[1],
                'name' => strip_tags($match[2]),
                'badge' => null,
                'brand' => null,
                'rating' => null,
                'features' => [],
                'affiliate_url' => null,
                'cta_text' => 'Learn More',
            ];
        }
        return $items;
    }

    private function splitListicleContent(string $content): array
    {
        $parts = ['introduction' => '', 'conclusion' => ''];

        if (preg_match('/^(.*?)(?=<h2)/is', $content, $match)) {
            $parts['introduction'] = trim($match[1]);
        }
        if (preg_match('/<h[23][^>]*>.*?(Conclusion|Final Thoughts|Our Verdict).*?<\/h[23]>(.*?)$/is', $content, $match)) {
            $parts['conclusion'] = trim($match[2]);
        }
        return $parts;
    }
}

// ============================================================================
// MAIN EXECUTION
// ============================================================================

echo "\n";
echo "=========================================\n";
echo " WordPress Content Importer\n";
echo "=========================================\n";
echo " Target: customer-reports.org (site_id={$SITE_ID})\n";
echo " Mode: " . ($dryRun ? "DRY RUN (no changes)" : "LIVE IMPORT") . "\n";
echo "=========================================\n";

$importer = new WordPressImporter($SITE_ID, $dryRun);
$totals = ['articles' => 0, 'reviews' => 0, 'listicles' => 0, 'pages' => 0, 'categories' => 0, 'skipped' => 0];

$databasesToImport = $singleSite
    ? [$singleSite => $WP_DATABASES[$singleSite]]
    : $WP_DATABASES;

foreach ($databasesToImport as $domain => $database) {
    try {
        $stats = $importer->import($domain, $database);
        foreach ($stats as $key => $value) {
            $totals[$key] += $value;
        }
    } catch (\Exception $e) {
        echo "  ERROR: " . $e->getMessage() . "\n";
    }
}

echo "\n=========================================\n";
echo " TOTALS\n";
echo "=========================================\n";
echo " Categories: {$totals['categories']}\n";
echo " Articles:   {$totals['articles']}\n";
echo " Reviews:    {$totals['reviews']}\n";
echo " Listicles:  {$totals['listicles']}\n";
echo " Pages:      {$totals['pages']}\n";
echo " Skipped:    {$totals['skipped']}\n";
echo "=========================================\n";

if ($dryRun) {
    echo "\n(Dry run - no changes made. Run without --dry-run to import.)\n";
}

echo "\nDone!\n";
