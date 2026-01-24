<?php
$pageTitle = ($category->meta_title ?? $category->name) . ' | ' . $site->name;
$metaDescription = $category->meta_description ?? ('Browse ' . $category->name . ' content on ' . $site->name);
ob_start();
?>

<div class="container">
    <h1 class="fw-bold mb-2"><?= htmlspecialchars($category->name) ?></h1>
    <?php if ($category->description): ?>
    <p class="text-muted mb-4"><?= htmlspecialchars($category->description) ?></p>
    <?php endif; ?>

    <?php if (!empty($reviews)): ?>
    <section class="mb-5">
        <h2 class="fw-bold mb-3">Reviews</h2>
        <div class="row g-4">
            <?php foreach ($reviews as $review): ?>
            <div class="col-md-4">
                <?php require __DIR__ . '/../partials/review-card.php'; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <?php if (!empty($articles)): ?>
    <section class="mb-5">
        <h2 class="fw-bold mb-3">Articles</h2>
        <div class="row g-4">
            <?php foreach ($articles as $article): ?>
            <div class="col-md-4">
                <?php require __DIR__ . '/../partials/article-card.php'; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
</div>

<?php
$__content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
