<?php
$pageTitle = $site->name . ' - Customer Reviews & Recommendations';
$metaDescription = $site->tagline ?? 'A top source for customer reviews and recommendations on every day products.';
ob_start();
?>

<!-- Hero -->
<section class="cr-hero">
    <div class="container">
        <div class="cr-hero-icon">
            <i class="fas fa-shield-alt"></i>
        </div>
        <h1><?= htmlspecialchars($site->name) ?>: Trusted Reviews. Smarter Choices.</h1>
        <p class="lead"><?= htmlspecialchars($site->tagline ?? 'A top source for customer reviews and recommendations on every day products.') ?></p>
        <div class="cr-hero-stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
        </div>
    </div>
</section>

<!-- Latest Reviews -->
<?php if (!empty($latestReviews)): ?>
<section class="cr-section">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="cr-section-title mb-0">Latest Reviews</h2>
            <a href="/reviews" class="btn-cr btn btn-sm">View All</a>
        </div>
        <div class="row g-4">
            <?php foreach (array_slice($latestReviews, 0, 4) as $review): ?>
            <div class="col-md-6 col-lg-3">
                <div class="cr-review-card">
                    <h3><a href="/reviews/<?= htmlspecialchars($review->slug) ?>"><?= htmlspecialchars($review->name) ?></a></h3>
                    <p><?= htmlspecialchars($review->short_description ?? '') ?></p>
                    <?php if ($review->rating_overall): ?>
                    <div class="cr-stars mb-2">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                        <i class="fas fa-star<?= $i <= round($review->rating_overall) ? '' : ' opacity-25' ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <?php endif; ?>
                    <a href="/reviews/<?= htmlspecialchars($review->slug) ?>" class="btn-cr btn btn-sm">Read Review</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Categories Grid -->
<?php if (!empty($categories)): ?>
<section class="cr-section cr-section-alt">
    <div class="container">
        <h2 class="text-center fw-bold mb-2">You Asked, We Delivered.</h2>
        <p class="text-center text-muted mb-4">Your most requested reviews and guides, all in one place.</p>
        <div class="row g-3 justify-content-center">
            <?php foreach ($categories as $cat): ?>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="cr-listicle-card">
                    <div style="width:100%;height:80px;background:var(--cr-bg-light);border-radius:6px;display:flex;align-items:center;justify-content:center;margin-bottom:0.75rem;">
                        <i class="fas fa-folder-open" style="font-size:1.5rem;color:var(--cr-green);"></i>
                    </div>
                    <h4><?= htmlspecialchars($cat->name) ?></h4>
                    <a href="/category/<?= htmlspecialchars($cat->slug) ?>" class="cr-category-btn"><?= htmlspecialchars($cat->name) ?></a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Articles by Category -->
<?php if (!empty($articlesByCategory)): ?>
<?php foreach ($articlesByCategory as $group): ?>
<section class="cr-section">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="cr-section-title mb-0"><?= htmlspecialchars($group['category']->name) ?></h2>
            <a href="/category/<?= htmlspecialchars($group['category']->slug) ?>" class="text-decoration-none" style="color:var(--cr-green);font-size:0.9rem;">View All &rarr;</a>
        </div>
        <div class="row g-3">
            <?php foreach (array_slice($group['articles'], 0, 6) as $article): ?>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="/articles/<?= htmlspecialchars($article->slug) ?>" class="text-decoration-none">
                    <div class="cr-article-card">
                        <div class="cr-article-card-img d-flex align-items-center justify-content-center">
                            <i class="fas fa-file-alt" style="font-size:2rem;color:var(--cr-border);"></i>
                        </div>
                        <div class="cr-article-card-body">
                            <h3><?= htmlspecialchars($article->title) ?></h3>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endforeach; ?>
<?php endif; ?>

<!-- Latest Articles (fallback if no categories) -->
<?php if (empty($articlesByCategory) && !empty($latestArticles)): ?>
<section class="cr-section">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="cr-section-title mb-0">Latest Articles</h2>
            <a href="/articles" class="btn-cr btn btn-sm">View All</a>
        </div>
        <div class="row g-3">
            <?php foreach (array_slice($latestArticles, 0, 6) as $article): ?>
            <div class="col-6 col-md-4">
                <a href="/articles/<?= htmlspecialchars($article->slug) ?>" class="text-decoration-none">
                    <div class="cr-article-card">
                        <div class="cr-article-card-img d-flex align-items-center justify-content-center">
                            <i class="fas fa-file-alt" style="font-size:2rem;color:var(--cr-border);"></i>
                        </div>
                        <div class="cr-article-card-body">
                            <h3><?= htmlspecialchars($article->title) ?></h3>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php
$__content = ob_get_clean();
require __DIR__ . '/layouts/app.php';
