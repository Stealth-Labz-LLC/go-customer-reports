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

        // Route matching
        switch (true) {
            case $path === '/':
                $this->home();
                break;

            case $path === '/articles':
                $this->articleIndex();
                break;

            case preg_match('#^/articles/([a-z0-9\-]+)$#', $path, $m):
                $this->articleShow($m[1]);
                break;

            case $path === '/reviews':
                $this->reviewIndex();
                break;

            case preg_match('#^/reviews/([a-z0-9\-]+)$#', $path, $m):
                $this->reviewShow($m[1]);
                break;

            case preg_match('#^/best-([a-z0-9\-]+)$#', $path, $m):
            case preg_match('#^/top-([a-z0-9\-]+)$#', $path, $m):
                $this->listicleShow($m[1]);
                break;

            case preg_match('#^/category/([a-z0-9\-]+)$#', $path, $m):
                $this->categoryShow($m[1]);
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

    private function articleIndex(): void
    {
        $site = $this->site;
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 12;
        $offset = ($page - 1) * $perPage;

        $articles = \App\Models\Article::latest($site->id, $perPage, $offset);
        $total = \App\Models\Article::count($site->id);
        $categories = \App\Models\Category::all($site->id);

        $this->render('articles/index', compact('site', 'articles', 'categories', 'page', 'perPage', 'total'));
    }

    private function articleShow(string $slug): void
    {
        $site = $this->site;
        $article = \App\Models\Article::findBySlug($site->id, $slug);

        if (!$article) {
            $this->notFound();
            return;
        }

        $articleCategories = \App\Models\Article::getCategories($article->id);
        $this->render('articles/show', compact('site', 'article', 'articleCategories'));
    }

    private function reviewIndex(): void
    {
        $site = $this->site;
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 12;
        $offset = ($page - 1) * $perPage;

        $reviews = \App\Models\Review::latest($site->id, $perPage, $offset);
        $total = \App\Models\Review::count($site->id);
        $categories = \App\Models\Category::all($site->id);

        $this->render('reviews/index', compact('site', 'reviews', 'categories', 'page', 'perPage', 'total'));
    }

    private function reviewShow(string $slug): void
    {
        $site = $this->site;
        $review = \App\Models\Review::findBySlug($site->id, $slug);

        if (!$review) {
            $this->notFound();
            return;
        }

        $review->pros = !empty($review->pros) ? json_decode($review->pros, true) : [];
        $review->cons = !empty($review->cons) ? json_decode($review->cons, true) : [];

        $reviewCategories = \App\Models\Review::getCategories($review->id);
        $this->render('reviews/show', compact('site', 'review', 'reviewCategories'));
    }

    private function listicleShow(string $slug): void
    {
        $site = $this->site;
        $listicle = \App\Models\Listicle::findBySlug($site->id, $slug);

        if (!$listicle) {
            $this->notFound();
            return;
        }

        $listicle->items = !empty($listicle->items) ? json_decode($listicle->items, true) : [];
        $this->render('listicles/show', compact('site', 'listicle'));
    }

    private function categoryShow(string $slug): void
    {
        $site = $this->site;
        $category = \App\Models\Category::findBySlug($site->id, $slug);

        if (!$category) {
            $this->notFound();
            return;
        }

        $articles = \App\Models\Article::byCategory($site->id, $category->id);
        $reviews = \App\Models\Review::byCategory($site->id, $category->id, 6);

        $this->render('categories/show', compact('site', 'category', 'articles', 'reviews'));
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

    private function notFound(): void
    {
        http_response_code(404);
        $site = $this->site;
        require $this->templateDir . '/404.php';
        exit;
    }
}
