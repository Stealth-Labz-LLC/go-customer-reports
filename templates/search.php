<?php
$pageTitle = ($query ? "Search: {$query}" : 'Search') . ' | ' . $site->name;
$metaDescription = $query ? "Search results for '{$query}' on {$site->name}" : "Search articles, reviews, and guides on {$site->name}";
$searchQuery = $query;
$searchCategory = $categorySlug;

ob_start();
?>

<!-- Search Hero -->
<section class="hero-section-simple text-white">
    <div class="container-xl">
        <span class="section-eyebrow"><i class="fas fa-search me-1"></i> Search</span>
        <h1 class="fw-bold mb-3">Search</h1>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <?php $size = 'lg'; require __DIR__ . '/partials/search-bar.php'; ?>
            </div>
        </div>
    </div>
</section>

<!-- Category Filter Pills -->
<div class="bg-light border-bottom py-3">
    <div class="container-xl">
        <div class="d-flex flex-wrap gap-2">
            <a href="<?= BASE_URL ?>/search?q=<?= urlencode($query) ?>" class="btn btn-sm <?= !$categorySlug ? 'btn-success' : 'btn-outline-secondary' ?>">All</a>
            <?php foreach ($categories as $cat): ?>
            <a href="<?= BASE_URL ?>/search?q=<?= urlencode($query) ?>&category=<?= htmlspecialchars($cat->slug) ?>"
               class="btn btn-sm <?= $categorySlug === $cat->slug ? 'btn-success' : 'btn-outline-secondary' ?>">
                <?= htmlspecialchars($cat->name) ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Results -->
<div class="container-xl py-4">
    <?php if ($query === ''): ?>
        <div class="text-center py-5">
            <i class="fas fa-search fa-3x text-muted mb-3 d-block"></i>
            <h3 class="text-muted">Enter a search term to find articles, reviews, and guides</h3>
        </div>

    <?php elseif ($totalArticles === 0 && $totalReviews === 0 && $totalListicles === 0): ?>
        <div class="text-center py-5">
            <i class="fas fa-search fa-3x text-muted mb-3 d-block"></i>
            <h3>No results found for "<?= htmlspecialchars($query) ?>"</h3>
            <p class="text-muted">Try different keywords or browse by category below.</p>
        </div>

    <?php else: ?>
        <p class="text-muted mb-4">
            Found <?= number_format($totalArticles) ?> articles, <?= $totalReviews ?> reviews, and <?= $totalListicles ?> guides
            for "<strong><?= htmlspecialchars($query) ?></strong>"
            <?php if ($activeCategory): ?>
                in <strong><?= htmlspecialchars($activeCategory->name) ?></strong>
            <?php endif; ?>
        </p>

        <!-- Reviews (if any) -->
        <?php if (!empty($reviews)): ?>
        <h2 class="h5 mb-3"><i class="fas fa-star text-warning"></i> Reviews (<?= $totalReviews ?>)</h2>
        <div class="row g-4 mb-5">
            <?php foreach ($reviews as $review): ?>
            <div class="col-md-6 col-lg-4">
                <?php require __DIR__ . '/partials/review-card.php'; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Listicles (if any) -->
        <?php if (!empty($listicles)): ?>
        <h2 class="h5 mb-3"><i class="fas fa-trophy text-warning"></i> Buying Guides (<?= $totalListicles ?>)</h2>
        <div class="row g-4 mb-5">
            <?php foreach ($listicles as $listicle):
                $listicleUrl = BASE_URL . (!empty($listicle->category_slug)
                    ? '/category/' . htmlspecialchars($listicle->category_slug) . '/top/' . htmlspecialchars($listicle->slug)
                    : '/top/' . htmlspecialchars($listicle->slug));
            ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <?php if (!empty($listicle->featured_image)): ?>
                    <a href="<?= $listicleUrl ?>"><img src="<?= IMAGE_BASE_URL . htmlspecialchars($listicle->featured_image) ?>" class="card-img-top" alt="<?= htmlspecialchars($listicle->title) ?>"></a>
                    <?php endif; ?>
                    <div class="card-body">
                        <span class="badge bg-dark bg-opacity-10 text-dark mb-2">Buying Guide</span>
                        <h5 class="card-title"><a href="<?= $listicleUrl ?>" class="text-decoration-none text-dark"><?= htmlspecialchars($listicle->title) ?></a></h5>
                        <?php if (!empty($listicle->excerpt)): ?>
                        <p class="card-text text-muted small"><?= htmlspecialchars(mb_substr($listicle->excerpt, 0, 120)) ?>...</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Articles -->
        <?php if (!empty($articles)): ?>
        <h2 class="h5 mb-3"><i class="fas fa-file-alt text-success"></i> Articles (<?= number_format($totalArticles) ?>)</h2>
        <div class="row g-4">
            <?php foreach ($articles as $article): ?>
            <div class="col-md-6 col-lg-4">
                <?php require __DIR__ . '/partials/article-card.php'; ?>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php
        $currentPage = $page;
        $baseUrl = BASE_URL . '/search?q=' . urlencode($query) . ($categorySlug ? '&category=' . urlencode($categorySlug) : '');
        require __DIR__ . '/partials/pagination.php';
        ?>
        <?php endif; ?>

    <?php endif; ?>
</div>

<?php
$__content = ob_get_clean();
require __DIR__ . '/layouts/app.php';
