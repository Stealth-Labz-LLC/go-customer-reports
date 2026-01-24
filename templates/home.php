<?php
$pageTitle = $site->name . ' - ' . ($site->tagline ?? '');
$metaDescription = $site->tagline ?? '';
ob_start();
?>

<div class="container">
    <section class="text-center py-5 mb-4">
        <h1 class="display-5 fw-bold"><?= htmlspecialchars($site->name) ?></h1>
        <p class="lead text-muted"><?= htmlspecialchars($site->tagline ?? '') ?></p>
    </section>

    <?php if (!empty($latestReviews)): ?>
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Latest Reviews</h2>
            <a href="/reviews" class="text-decoration-none">View All &rarr;</a>
        </div>
        <div class="row g-4">
            <?php foreach (array_slice($latestReviews, 0, 3) as $review): ?>
            <div class="col-md-4">
                <?php require __DIR__ . '/partials/review-card.php'; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <?php if (!empty($latestArticles)): ?>
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Latest Articles</h2>
            <a href="/articles" class="text-decoration-none">View All &rarr;</a>
        </div>
        <div class="row g-4">
            <?php foreach (array_slice($latestArticles, 0, 3) as $article): ?>
            <div class="col-md-4">
                <?php require __DIR__ . '/partials/article-card.php'; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <?php if (!empty($categories)): ?>
    <section class="mb-5">
        <h2 class="fw-bold mb-4">Categories</h2>
        <div class="d-flex flex-wrap gap-2">
            <?php foreach ($categories as $cat): ?>
            <a href="/category/<?= htmlspecialchars($cat->slug) ?>" class="btn btn-outline-primary"><?= htmlspecialchars($cat->name) ?></a>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
</div>

<?php
$__content = ob_get_clean();
require __DIR__ . '/layouts/app.php';
