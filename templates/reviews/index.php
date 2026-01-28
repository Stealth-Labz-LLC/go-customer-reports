<?php
$pageTitle = ($activeCategory ? $activeCategory->name . ' Reviews' : 'Product Reviews') . ' | ' . $site->name;
$metaDescription = 'Browse ' . number_format($totalReviews) . ' expert product reviews on ' . $site->name . '. Unbiased ratings, pricing, and buying recommendations.';
$searchQuery = '';
$searchCategory = $categorySlug;

ob_start();
?>

<!-- Header -->
<section class="bg-dark text-white py-5">
    <div class="container-xl">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <span class="badge bg-warning text-dark mb-2"><i class="fas fa-star"></i> Product Reviews</span>
                <h1 class="mb-2"><?= $activeCategory ? htmlspecialchars($activeCategory->name) . ' Reviews' : 'Expert Product Reviews' ?></h1>
                <p class="text-white-50 mb-0">Honest ratings, real research, and unbiased recommendations. <?= number_format($totalReviews) ?> reviews and counting.</p>
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
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <span class="text-muted small me-1">Filter:</span>
            <a href="<?= BASE_URL ?>/reviews?sort=<?= urlencode($sort) ?>"
               class="btn btn-sm <?= !$categorySlug ? 'btn-success' : 'btn-outline-secondary' ?>">All</a>
            <?php foreach ($categories as $cat):
                if (($cat->review_count ?? 0) < 1) continue;
            ?>
            <a href="<?= BASE_URL ?>/reviews?category=<?= htmlspecialchars($cat->slug) ?>&sort=<?= urlencode($sort) ?>"
               class="btn btn-sm <?= $categorySlug === $cat->slug ? 'btn-success' : 'btn-outline-secondary' ?>">
                <?= htmlspecialchars($cat->name) ?>
                <span class="badge bg-white text-dark ms-1"><?= $cat->review_count ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Sort + Results Info -->
<div class="container-xl pt-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <div class="d-flex gap-2 align-items-center">
            <span class="text-muted small">Sort:</span>
            <?php
            $sortOptions = ['newest' => 'Newest', 'rating' => 'Top Rated', 'name' => 'A-Z'];
            foreach ($sortOptions as $key => $label):
                $sortUrl = BASE_URL . '/reviews?' . ($categorySlug ? 'category=' . urlencode($categorySlug) . '&' : '') . 'sort=' . $key;
            ?>
            <a href="<?= $sortUrl ?>" class="btn btn-sm <?= $sort === $key ? 'btn-success' : 'btn-outline-secondary' ?>"><?= $label ?></a>
            <?php endforeach; ?>
        </div>
        <span class="text-muted small">
            <?php if ($totalPages > 1): ?>Page <?= $page ?> of <?= $totalPages ?> &middot; <?php endif; ?>
            <?= number_format($totalReviews) ?> reviews
        </span>
    </div>
</div>

<!-- Reviews Grid -->
<div class="container-xl pb-5">
    <?php if (!empty($reviews)): ?>
    <div class="row g-4">
        <?php foreach ($reviews as $review): ?>
        <div class="col-md-6 col-lg-4">
            <?php require __DIR__ . '/../partials/review-card.php'; ?>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        <?php
        $currentPage = $page;
        $baseUrl = BASE_URL . '/reviews?' . ($categorySlug ? 'category=' . urlencode($categorySlug) . '&' : '') . 'sort=' . urlencode($sort);
        require __DIR__ . '/../partials/pagination.php';
        ?>
    </div>
    <?php else: ?>
    <div class="text-center py-5">
        <i class="fas fa-star fa-3x text-muted mb-3 d-block"></i>
        <h3>No reviews found</h3>
        <p class="text-muted">Try a different category or check back soon.</p>
    </div>
    <?php endif; ?>
</div>

<?php
$__content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
