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

            case $path === '/articles':
                $this->redirectToCategories();
                break;

            case preg_match('#^/articles/([a-zA-Z0-9\-_]+)$#i', $path, $m):
                $this->redirectArticle(strtolower($m[1]));
                break;

            case $path === '/reviews':
                $this->redirectToCategories();
                break;

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
                $this->sitemap();
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
        $categories = \App\Models\Category::topLevel($siteId);

        // Articles grouped by category for homepage sections
        $articlesByCategory = [];
        foreach ($categories as $cat) {
            $catArticles = \App\Models\Article::byCategory($siteId, $cat->id, 6);
            if (!empty($catArticles)) {
                $articlesByCategory[] = [
                    'category' => $cat,
                    'articles' => $catArticles,
                ];
            }
        }

        $this->render('home', compact('site', 'latestArticles', 'latestReviews', 'latestListicles', 'categories', 'articlesByCategory'));
    }

    private function articleShow(string $slug, ?string $categorySlug = null): void
    {
        $site = $this->site;
        $article = \App\Models\Article::findBySlug($site->id, $slug);

        if (!$article) {
            $this->notFound();
            return;
        }

        // Get primary category
        $primaryCategory = \App\Models\Category::find($article->primary_category_id);

        // Verify URL matches primary category (redirect if wrong category)
        if ($primaryCategory && $categorySlug !== $primaryCategory->slug) {
            $this->redirect301("/category/{$primaryCategory->slug}/{$article->slug}");
            return;
        }

        $articleCategories = \App\Models\Article::getCategories($article->id);
        $breadcrumbs = $this->buildBreadcrumbs($primaryCategory, $article->title);

        // Get related articles from same category (exclude current)
        $relatedArticles = [];
        if ($primaryCategory) {
            $categoryArticles = \App\Models\Article::byCategory($site->id, $primaryCategory->id, 7);
            $relatedArticles = array_filter($categoryArticles, fn($a) => $a->id !== $article->id);
            $relatedArticles = array_slice($relatedArticles, 0, 6);
        }

        // Get all categories for sidebar
        $allCategories = \App\Models\Category::all($site->id);

        // Get related reviews from same category for cross-linking
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

        // Get primary category
        $primaryCategory = \App\Models\Category::find($review->primary_category_id);

        // Verify URL matches primary category (redirect if wrong category)
        if ($primaryCategory && $categorySlug !== $primaryCategory->slug) {
            $this->redirect301("/category/{$primaryCategory->slug}/reviews/{$review->slug}");
            return;
        }

        $review->pros = !empty($review->pros) ? json_decode($review->pros, true) : [];
        $review->cons = !empty($review->cons) ? json_decode($review->cons, true) : [];

        $reviewCategories = \App\Models\Review::getCategories($review->id);
        $breadcrumbs = $this->buildBreadcrumbs($primaryCategory, 'Reviews', "/category/{$primaryCategory->slug}/reviews", $review->name);

        // Get related reviews from same category (exclude current)
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

        // Get primary category
        $primaryCategory = \App\Models\Category::find($listicle->primary_category_id);

        // Verify URL matches primary category (redirect if wrong category)
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

        // Get content for this category
        $articles = \App\Models\Article::byCategory($site->id, $category->id, 20);
        $reviews = \App\Models\Review::byCategory($site->id, $category->id, 12);
        $listicles = \App\Models\Listicle::byCategory($site->id, $category->id, 6);

        // Get counts
        $articleCount = \App\Models\Article::countByCategory($site->id, $category->id);
        $reviewCount = count($reviews);
        $listicleCount = count($listicles);

        // Get all categories for sidebar
        $allCategories = \App\Models\Category::all($site->id);

        $this->render('categories/show', compact('site', 'category', 'articles', 'reviews', 'listicles', 'articleCount', 'reviewCount', 'listicleCount', 'allCategories'));
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

    private function sitemap(): void
    {
        $site = $this->site;
        $articles = \App\Models\Article::latest($site->id, 1000);
        $reviews = \App\Models\Review::latest($site->id, 1000);
        $listicles = \App\Models\Listicle::latest($site->id, 1000);
        $categories = \App\Models\Category::all($site->id);

        header('Content-Type: application/xml; charset=utf-8');
        require $this->templateDir . '/sitemap.php';
        exit;
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

        // Start output buffering for the content
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
        $breadcrumbs = [
            ['label' => 'Home', 'url' => '/'],
        ];

        if ($category) {
            $breadcrumbs[] = ['label' => $category->name, 'url' => "/category/{$category->slug}"];
        }

        // Process remaining items (pairs of label, url or just final label)
        for ($i = 0; $i < count($items); $i++) {
            $label = $items[$i];
            $url = isset($items[$i + 1]) && strpos($items[$i + 1], '/') === 0 ? $items[++$i] : null;
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
