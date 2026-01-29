<?php
$pageTitle = ($activeCategory ? $activeCategory->name . ' Articles' : 'All Articles') . ' | ' . $site->name;
$metaDescription = 'Browse ' . number_format($totalArticles) . ' articles on ' . $site->name . '. Expert reviews, guides, and recommendations.';
$searchQuery = '';
$searchCategory = $categorySlug;

ob_start();
?>

<!-- Header -->
<section class="hero-section-simple text-white">
    <div class="container-xl">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <span class="section-eyebrow"><i class="fas fa-file-alt me-1"></i> Fresh Content</span>
                <h1 class="fw-bold mb-2"><?= $activeCategory ? htmlspecialchars($activeCategory->name) . ' Articles' : 'All Articles' ?></h1>
                <p class="text-white-50 mb-0"><?= number_format($totalArticles) ?> articles to explore</p>
            </div>
            <div class="col-lg-5 mt-3 mt-lg-0">
                <?php $size = ''; require __DIR__ . '/../partials/search-bar.php'; ?>
            </div>
        </div>
    </div>
</section>

<!-- Category Filter Pills -->
<div class="bg-light border-bottom py-3">
    <div class="container-xl">
        <div class="d-flex flex-nowrap overflow-auto gap-2 align-items-center pb-1">
            <span class="text-muted small me-1 flex-shrink-0">Filter:</span>
            <a href="<?= BASE_URL ?>/articles?sort=<?= urlencode($sort) ?>"
               class="btn btn-sm <?= !$categorySlug ? 'btn-success' : 'btn-outline-secondary' ?>">All</a>
            <?php foreach ($categories as $cat): ?>
            <a href="<?= BASE_URL ?>/articles?category=<?= htmlspecialchars($cat->slug) ?>&sort=<?= urlencode($sort) ?>"
               class="btn btn-sm <?= $categorySlug === $cat->slug ? 'btn-success' : 'btn-outline-secondary' ?>">
                <?= htmlspecialchars($cat->name) ?>
                <span class="badge bg-white text-dark ms-1"><?= number_format($cat->article_count) ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Sort + Results Info -->
<div class="container-xl pt-4">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-3">
        <div class="d-flex gap-2 align-items-center">
            <span class="text-muted small">Sort:</span>
            <?php
            $sortOptions = ['newest' => 'Newest', 'oldest' => 'Oldest', 'title' => 'A-Z'];
            foreach ($sortOptions as $key => $label):
                $sortUrl = BASE_URL . '/articles?' . ($categorySlug ? 'category=' . urlencode($categorySlug) . '&' : '') . 'sort=' . $key;
            ?>
            <a href="<?= $sortUrl ?>" class="btn btn-sm <?= $sort === $key ? 'btn-success' : 'btn-outline-secondary' ?>"><?= $label ?></a>
            <?php endforeach; ?>
        </div>
        <span class="text-muted small">Page <?= $page ?> of <?= $totalPages ?></span>
    </div>
</div>

<!-- Articles Grid -->
<div class="container-xl pb-5">
    <?php if (!empty($articles)): ?>
    <div class="row g-4">
        <?php foreach ($articles as $article): ?>
        <div class="col-md-6 col-lg-4">
            <?php require __DIR__ . '/../partials/article-card.php'; ?>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        <?php
        $currentPage = $page;
        $baseUrl = BASE_URL . '/articles?' . ($categorySlug ? 'category=' . urlencode($categorySlug) . '&' : '') . 'sort=' . urlencode($sort);
        require __DIR__ . '/../partials/pagination.php';
        ?>
    </div>
    <?php else: ?>
    <div class="text-center py-5">
        <i class="fas fa-search fa-3x text-muted mb-3 d-block"></i>
        <h3>No articles found</h3>
        <p class="text-muted">Try a different category or check back soon.</p>
    </div>
    <?php endif; ?>
</div>

<?php
$__content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
