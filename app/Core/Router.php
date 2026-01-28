<?php
/**
 * Simple URL Router
 * Routes content URLs to the appropriate template
 */

namespace App\Core;

class Router
{
    private object $site;
    private string $templateDir;

    public function __construct(object $site)
    {
        $this->site = $site;
        $this->templateDir = dirname(__DIR__, 2) . '/templates';
    }

    public function dispatch(string $uri): void
    {
        // Strip query string and trailing slash
        $path = parse_url($uri, PHP_URL_PATH);
        $path = rtrim($path, '/');
        if (empty($path)) $path = '/';

        // Route matching - ORDER MATTERS (most specific first)
        switch (true) {
            case $path === '/':
                $this->home();
                break;

            // === SEARCH & BROWSE ===
            case $path === '/search':
                $this->searchPage();
                break;

            case $path === '/articles':
                $this->articlesIndex();
                break;

            case $path === '/reviews':
                $this->reviewsIndex();
                break;

            // === NEW CATEGORY-BASED URLS ===

            // Category > Reviews > Slug: /category/{cat}/reviews/{slug}
            case preg_match('#^/category/([a-zA-Z0-9\-_]+)/reviews/([a-zA-Z0-9\-_]+)$#i', $path, $m):
                $this->reviewShow(strtolower($m[2]), strtolower($m[1]));
                break;

            // Category > Top (Listicles) > Slug: /category/{cat}/top/{slug}
            case preg_match('#^/category/([a-zA-Z0-9\-_]+)/top/([a-zA-Z0-9\-_]+)$#i', $path, $m):
                $this->listicleShow(strtolower($m[2]), strtolower($m[1]));
                break;

            // Category > Article Slug: /category/{cat}/{slug}
            case preg_match('#^/category/([a-zA-Z0-9\-_]+)/([a-zA-Z0-9\-_]+)$#i', $path, $m):
                $this->articleShow(strtolower($m[2]), strtolower($m[1]));
                break;

            // Category Index: /categories
            case $path === '/categories':
                $this->categoryIndex();
                break;

            // Category Page: /category/{slug}
            case preg_match('#^/category/([a-zA-Z0-9\-_]+)$#i', $path, $m):
                $this->categoryShow(strtolower($m[1]));
                break;

            // === OLD URLS - 301 REDIRECT TO NEW STRUCTURE ===

            // /articles is now handled above as articlesIndex()

            case preg_match('#^/articles/([a-zA-Z0-9\-_]+)$#i', $path, $m):
                $this->redirectArticle(strtolower($m[1]));
                break;

            // /reviews is now handled above as reviewsIndex()

            case preg_match('#^/reviews/([a-zA-Z0-9\-_]+)$#i', $path, $m):
                $this->redirectReview(strtolower($m[1]));
                break;

            case $path === '/top':
                $this->redirectToCategories();
                break;

            case preg_match('#^/top/([a-zA-Z0-9\-_]+)$#i', $path, $m):
                $this->redirectListicle(strtolower($m[1]));
                break;

            // === UTILITY ROUTES ===

            case $path === '/robots.txt':
                $this->robotsTxt();
                break;

            case $path === '/sitemap.xml':
                $this->sitemapIndex();
                break;

            case preg_match('#^/sitemap-(articles|reviews|listicles|categories|pages)\.xml$#', $path, $m):
                $this->sitemapSection($m[1]);
                break;

            default:
                // Try as a static page slug
                $slug = ltrim($path, '/');
                $this->pageShow($slug);
        }
    }

    private function home(): void
    {
        $site = $this->site;
        $siteId = $site->id;

        $latestArticles = \App\Models\Article::latest($siteId, 6);
        $latestReviews = \App\Models\Review::latest($siteId, 6);
        $latestListicles = \App\Models\Listicle::latest($siteId, 4);
        $categories = \App\Models\Category::allWithCounts($siteId);

        $totalArticles = \App\Models\Article::count($siteId);
        $totalReviews = \App\Models\Review::count($siteId);

        $this->render('home', compact('site', 'latestArticles', 'latestReviews', 'latestListicles', 'categories', 'totalArticles', 'totalReviews'));
    }

    private function searchPage(): void
    {
        $site = $this->site;
        $siteId = $site->id;

        $query = trim($_GET['q'] ?? '');
        $categorySlug = trim($_GET['category'] ?? '');
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 24;
        $offset = ($page - 1) * $perPage;

        $categoryId = null;
        $activeCategory = null;
        if ($categorySlug) {
            $activeCategory = \App\Models\Category::findBySlug($siteId, $categorySlug);
            $categoryId = $activeCategory ? $activeCategory->id : null;
        }

        $articles = [];
        $reviews = [];
        $listicles = [];
        $totalArticles = 0;
        $totalReviews = 0;
        $totalListicles = 0;

        if ($query !== '') {
            $articles = \App\Models\Article::search($siteId, $query, $categoryId, $perPage, $offset);
            $totalArticles = \App\Models\Article::searchCount($siteId, $query, $categoryId);
            $reviews = \App\Models\Review::search($siteId, $query, $categoryId, 12, 0);
            $totalReviews = \App\Models\Review::searchCount($siteId, $query, $categoryId);
            $listicles = \App\Models\Listicle::search($siteId, $query, $categoryId, 12, 0);
            $totalListicles = \App\Models\Listicle::searchCount($siteId, $query, $categoryId);
        }

        $totalPages = (int) ceil($totalArticles / $perPage);
        $categories = \App\Models\Category::all($siteId);

        $this->render('search', compact(
            'site', 'query', 'categorySlug', 'activeCategory', 'page', 'perPage',
            'articles', 'reviews', 'listicles',
            'totalArticles', 'totalReviews', 'totalListicles',
            'totalPages', 'categories'
        ));
    }

    private function articlesIndex(): void
    {
        $site = $this->site;
        $siteId = $site->id;

        $page = max(1, (int) ($_GET['page'] ?? 1));
        $sort = $_GET['sort'] ?? 'newest';
        $categorySlug = trim($_GET['category'] ?? '');
        $perPage = 24;
        $offset = ($page - 1) * $perPage;

        $categoryId = null;
        $activeCategory = null;
        if ($categorySlug) {
            $activeCategory = \App\Models\Category::findBySlug($siteId, $categorySlug);
            $categoryId = $activeCategory ? $activeCategory->id : null;
        }

        if ($categoryId) {
            $articles = \App\Models\Article::byCategoryPaginated($siteId, $categoryId, $perPage, $offset, $sort);
            $totalArticles = \App\Models\Article::countByCategory($siteId, $categoryId);
        } else {
            $articles = \App\Models\Article::latest($siteId, $perPage, $offset);
            $totalArticles = \App\Models\Article::count($siteId);
        }

        $totalPages = (int) ceil($totalArticles / $perPage);
        $categories = \App\Models\Category::allWithCounts($siteId);

        $this->render('articles/index', compact(
            'site', 'articles', 'categories', 'page', 'totalPages', 'totalArticles',
            'sort', 'categorySlug', 'activeCategory', 'perPage'
        ));
    }

    private function reviewsIndex(): void
    {
        $site = $this->site;
        $siteId = $site->id;

        $page = max(1, (int) ($_GET['page'] ?? 1));
        $sort = $_GET['sort'] ?? 'newest';
        $categorySlug = trim($_GET['category'] ?? '');
        $perPage = 24;
        $offset = ($page - 1) * $perPage;

        $categoryId = null;
        $activeCategory = null;
        if ($categorySlug) {
            $activeCategory = \App\Models\Category::findBySlug($siteId, $categorySlug);
            $categoryId = $activeCategory ? $activeCategory->id : null;
        }

        if ($categoryId) {
            $reviews = \App\Models\Review::byCategoryPaginated($siteId, $categoryId, $perPage, $offset, $sort);
            $totalReviews = \App\Models\Review::countByCategory($siteId, $categoryId);
        } else {
            $reviews = \App\Models\Review::latestPaginated($siteId, $perPage, $offset, $sort);
            $totalReviews = \App\Models\Review::count($siteId);
        }

        $totalPages = (int) ceil($totalReviews / $perPage);
        $categories = \App\Models\Category::allWithCounts($siteId);

        $this->render('reviews/index', compact(
            'site', 'reviews', 'categories', 'page', 'totalPages', 'totalReviews',
            'sort', 'categorySlug', 'activeCategory', 'perPage'
        ));
    }

    private function articleShow(string $slug, ?string $categorySlug = null): void
    {
        $site = $this->site;
        $article = \App\Models\Article::findBySlug($site->id, $slug);

        if (!$article) {
            $this->notFound();
            return;
        }

        $primaryCategory = \App\Models\Category::find($article->primary_category_id);

        if ($primaryCategory && $categorySlug !== $primaryCategory->slug) {
            $this->redirect301("/category/{$primaryCategory->slug}/{$article->slug}");
            return;
        }

        $articleCategories = \App\Models\Article::getCategories($article->id);
        $breadcrumbs = $this->buildBreadcrumbs($primaryCategory, $article->title);

        $relatedArticles = [];
        if ($primaryCategory) {
            $categoryArticles = \App\Models\Article::byCategory($site->id, $primaryCategory->id, 7);
            $relatedArticles = array_filter($categoryArticles, fn($a) => $a->id !== $article->id);
            $relatedArticles = array_slice($relatedArticles, 0, 6);
        }

        $allCategories = \App\Models\Category::all($site->id);

        $relatedReviews = [];
        if ($primaryCategory) {
            $relatedReviews = \App\Models\Review::byCategory($site->id, $primaryCategory->id, 3);
        }

        $this->render('articles/show', compact('site', 'article', 'articleCategories', 'primaryCategory', 'breadcrumbs', 'relatedArticles', 'allCategories', 'relatedReviews'));
    }

    private function reviewShow(string $slug, ?string $categorySlug = null): void
    {
        $site = $this->site;
        $review = \App\Models\Review::findBySlug($site->id, $slug);

        if (!$review) {
            $this->notFound();
            return;
        }

        $primaryCategory = \App\Models\Category::find($review->primary_category_id);

        if ($primaryCategory && $categorySlug !== $primaryCategory->slug) {
            $this->redirect301("/category/{$primaryCategory->slug}/reviews/{$review->slug}");
            return;
        }

        $review->pros = !empty($review->pros) ? json_decode($review->pros, true) : [];
        $review->cons = !empty($review->cons) ? json_decode($review->cons, true) : [];

        $reviewCategories = \App\Models\Review::getCategories($review->id);
        $breadcrumbs = $this->buildBreadcrumbs($primaryCategory, 'Reviews', "/category/{$primaryCategory->slug}/reviews", $review->name);

        $relatedReviews = [];
        if ($primaryCategory) {
            $categoryReviews = \App\Models\Review::byCategory($site->id, $primaryCategory->id, 5);
            $relatedReviews = array_filter($categoryReviews, fn($r) => $r->id !== $review->id);
            $relatedReviews = array_slice($relatedReviews, 0, 4);
        }

        $this->render('reviews/show', compact('site', 'review', 'reviewCategories', 'primaryCategory', 'breadcrumbs', 'relatedReviews'));
    }

    private function listicleShow(string $slug, ?string $categorySlug = null): void
    {
        $site = $this->site;
        $listicle = \App\Models\Listicle::findBySlug($site->id, $slug);

        if (!$listicle) {
            $this->notFound();
            return;
        }

        $primaryCategory = \App\Models\Category::find($listicle->primary_category_id);

        if ($primaryCategory && $categorySlug !== $primaryCategory->slug) {
            $this->redirect301("/category/{$primaryCategory->slug}/top/{$listicle->slug}");
            return;
        }

        $listicle->items = !empty($listicle->items) ? json_decode($listicle->items, true) : [];
        $breadcrumbs = $this->buildBreadcrumbs($primaryCategory, 'Top Lists', "/category/{$primaryCategory->slug}/top", $listicle->title);

        $this->render('listicles/show', compact('site', 'listicle', 'primaryCategory', 'breadcrumbs'));
    }

    private function categoryIndex(): void
    {
        $site = $this->site;
        $categories = \App\Models\Category::allWithCounts($site->id);

        $this->render('categories/index', compact('site', 'categories'));
    }

    private function categoryShow(string $slug): void
    {
        $site = $this->site;
        $category = \App\Models\Category::findBySlug($site->id, $slug);

        if (!$category) {
            $this->notFound();
            return;
        }

        $page = max(1, (int) ($_GET['page'] ?? 1));
        $sort = $_GET['sort'] ?? 'newest';
        $type = $_GET['type'] ?? 'all';
        $perPage = 24;
        $offset = ($page - 1) * $perPage;

        // Paginated articles
        $articles = \App\Models\Article::byCategoryPaginated($site->id, $category->id, $perPage, $offset, $sort);
        $articleCount = \App\Models\Article::countByCategory($site->id, $category->id);
        $totalPages = (int) ceil($articleCount / $perPage);

        // Reviews and listicles (smaller sets, no pagination needed)
        $reviews = \App\Models\Review::byCategory($site->id, $category->id, 12);
        $listicles = \App\Models\Listicle::byCategory($site->id, $category->id, 6);
        $reviewCount = count($reviews);
        $listicleCount = count($listicles);

        // All categories for sidebar (single query with counts)
        $allCategories = \App\Models\Category::allWithCounts($site->id);

        $this->render('categories/show', compact(
            'site', 'category', 'articles', 'reviews', 'listicles',
            'articleCount', 'reviewCount', 'listicleCount',
            'allCategories', 'page', 'totalPages', 'sort', 'type', 'perPage'
        ));
    }

    private function pageShow(string $slug): void
    {
        $site = $this->site;
        $page = \App\Models\Page::findBySlug($site->id, $slug);

        if (!$page) {
            $this->notFound();
            return;
        }

        $this->render('pages/default', compact('site', 'page'));
    }

    private function sitemapIndex(): void
    {
        $domain = $this->site->domain;
        $sections = ['articles', 'reviews', 'listicles', 'categories', 'pages'];

        header('Content-Type: application/xml; charset=utf-8');
        echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        echo '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
        foreach ($sections as $section) {
            echo '  <sitemap>' . PHP_EOL;
            echo '    <loc>https://' . htmlspecialchars($domain) . '/sitemap-' . $section . '.xml</loc>' . PHP_EOL;
            echo '  </sitemap>' . PHP_EOL;
        }
        echo '</sitemapindex>' . PHP_EOL;
        exit;
    }

    private function sitemapSection(string $section): void
    {
        $site = $this->site;
        $domain = $site->domain;

        header('Content-Type: application/xml; charset=utf-8');
        echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        switch ($section) {
            case 'articles':
                // Homepage + browse page
                $this->sitemapUrl($domain, '/', 'daily', '1.0');
                $this->sitemapUrl($domain, '/articles', 'daily', '0.8');
                // All articles
                $articles = \App\Models\Article::latest($site->id, 100000);
                foreach ($articles as $item) {
                    if (!empty($item->category_slug)) {
                        $this->sitemapUrl($domain, "/category/{$item->category_slug}/{$item->slug}", 'monthly', '0.6', $item->updated_at);
                    }
                }
                break;

            case 'reviews':
                $this->sitemapUrl($domain, '/reviews', 'weekly', '0.8');
                $reviews = \App\Models\Review::latest($site->id, 100000);
                foreach ($reviews as $item) {
                    if (!empty($item->category_slug)) {
                        $this->sitemapUrl($domain, "/category/{$item->category_slug}/reviews/{$item->slug}", 'weekly', '0.9', $item->updated_at);
                    }
                }
                break;

            case 'listicles':
                $listicles = \App\Models\Listicle::latest($site->id, 100000);
                foreach ($listicles as $item) {
                    if (!empty($item->category_slug)) {
                        $this->sitemapUrl($domain, "/category/{$item->category_slug}/top/{$item->slug}", 'weekly', '0.9', $item->updated_at);
                    }
                }
                break;

            case 'categories':
                $this->sitemapUrl($domain, '/categories', 'weekly', '0.9');
                $categories = \App\Models\Category::all($site->id);
                foreach ($categories as $item) {
                    $this->sitemapUrl($domain, "/category/{$item->slug}", 'weekly', '0.8');
                }
                break;

            case 'pages':
                $pages = \App\Models\Page::all($site->id);
                foreach ($pages as $item) {
                    $this->sitemapUrl($domain, "/{$item->slug}", 'monthly', '0.4');
                }
                break;
        }

        echo '</urlset>' . PHP_EOL;
        exit;
    }

    private function sitemapUrl(string $domain, string $path, string $changefreq, string $priority, ?string $lastmod = null): void
    {
        echo '  <url>' . PHP_EOL;
        echo '    <loc>https://' . htmlspecialchars($domain) . htmlspecialchars($path) . '</loc>' . PHP_EOL;
        if ($lastmod) {
            echo '    <lastmod>' . date('c', strtotime($lastmod)) . '</lastmod>' . PHP_EOL;
        }
        echo '    <changefreq>' . $changefreq . '</changefreq>' . PHP_EOL;
        echo '    <priority>' . $priority . '</priority>' . PHP_EOL;
        echo '  </url>' . PHP_EOL;
    }

    private function robotsTxt(): void
    {
        $site = $this->site;
        header('Content-Type: text/plain; charset=utf-8');
        echo "# robots.txt for {$site->name}\n\n";
        echo "User-agent: *\n";
        echo "Allow: /\n\n";
        echo "# Sitemap\n";
        echo "Sitemap: https://{$site->domain}/sitemap.xml\n\n";
        echo "# Disallow admin/API paths\n";
        echo "Disallow: /api/\n";
        echo "Disallow: /cli/\n";
        echo "Disallow: /config/\n";
        echo "Disallow: /app/\n\n";
        echo "# Disallow campaign/funnel directories\n";
        echo "Disallow: /cr/\n";
        echo "Disallow: /eb/\n";
        echo "Disallow: /ee25/\n";
        echo "Disallow: /qr/\n";
        echo "Disallow: /sc/\n";
        echo "Disallow: /ss/\n";
        exit;
    }

    private function render(string $template, array $data = []): void
    {
        extract($data);
        $templateFile = $this->templateDir . '/' . $template . '.php';

        if (!file_exists($templateFile)) {
            $this->notFound();
            return;
        }

        ob_start();
        require $templateFile;
        $content = ob_get_clean();

        echo $content;
    }

    // =========================================================================
    // REDIRECTS (301 for old URL structure)
    // =========================================================================

    private function redirect301(string $url): void
    {
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: {$url}");
        exit;
    }

    private function redirectToCategories(): void
    {
        $this->redirect301('/categories');
    }

    private function redirectArticle(string $slug): void
    {
        $article = \App\Models\Article::findBySlug($this->site->id, $slug);
        if ($article && $article->primary_category_id) {
            $category = \App\Models\Category::find($article->primary_category_id);
            if ($category) {
                $this->redirect301("/category/{$category->slug}/{$article->slug}");
                return;
            }
        }
        $this->notFound();
    }

    private function redirectReview(string $slug): void
    {
        $review = \App\Models\Review::findBySlug($this->site->id, $slug);
        if ($review && $review->primary_category_id) {
            $category = \App\Models\Category::find($review->primary_category_id);
            if ($category) {
                $this->redirect301("/category/{$category->slug}/reviews/{$review->slug}");
                return;
            }
        }
        $this->notFound();
    }

    private function redirectListicle(string $slug): void
    {
        $listicle = \App\Models\Listicle::findBySlug($this->site->id, $slug);
        if ($listicle && $listicle->primary_category_id) {
            $category = \App\Models\Category::find($listicle->primary_category_id);
            if ($category) {
                $this->redirect301("/category/{$category->slug}/top/{$listicle->slug}");
                return;
            }
        }
        $this->notFound();
    }

    // =========================================================================
    // BREADCRUMBS
    // =========================================================================

    private function buildBreadcrumbs(?object $category, string ...$items): array
    {
        $baseUrl = defined('BASE_URL') ? BASE_URL : '';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => $baseUrl . '/'],
        ];

        if ($category) {
            $breadcrumbs[] = ['label' => $category->name, 'url' => $baseUrl . "/category/{$category->slug}"];
        }

        for ($i = 0; $i < count($items); $i++) {
            $label = $items[$i];
            $url = isset($items[$i + 1]) && strpos($items[$i + 1], '/') === 0 ? $baseUrl . $items[++$i] : null;
            $breadcrumbs[] = ['label' => $label, 'url' => $url];
        }

        return $breadcrumbs;
    }

    // =========================================================================
    // ERROR HANDLING
    // =========================================================================

    private function notFound(): void
    {
        http_response_code(404);
        $site = $this->site;
        require $this->templateDir . '/404.php';
        exit;
    }
}
