<?php
$pageTitle = ($category->meta_title ?? $category->name) . ' | ' . $site->name;
$metaDescription = $category->meta_description ?? ('Browse ' . $category->name . ' reviews and articles on ' . $site->name);
ob_start();
?>

<!-- Breadcrumbs -->
<div class="cr-breadcrumbs">
    <div class="container">
        <span><a href="/">Home</a> &raquo; <span class="current"><?= htmlspecialchars($category->name) ?></span></span>
    </div>
</div>

<!-- Page Header -->
<div class="cr-page-header">
    <div class="container">
        <h1><?= htmlspecialchars($category->name) ?></h1>
        <?php if ($category->description): ?>
        <p><?= htmlspecialchars($category->description) ?></p>
        <?php endif; ?>
    </div>
</div>

<!-- Main Content -->
<div class="cr-index-page">
    <div class="container py-4">
        <!-- Reviews Section -->
        <?php if (!empty($reviews)): ?>
        <section class="cr-category-section mb-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="cr-section-title mb-0"><i class="fas fa-star"></i> Product Reviews</h2>
                <span class="text-muted"><?= count($reviews) ?> reviews</span>
            </div>
            <div class="row g-4">
                <?php foreach ($reviews as $review): ?>
                <div class="col-md-6 col-lg-4">
                    <?php require __DIR__ . '/../partials/review-card.php'; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- Articles Section -->
        <?php if (!empty($articles)): ?>
        <section class="cr-category-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="cr-section-title mb-0"><i class="fas fa-file-alt"></i> Articles & Guides</h2>
                <span class="text-muted"><?= count($articles) ?> articles</span>
            </div>
            <div class="row g-4">
                <?php foreach ($articles as $article): ?>
                <div class="col-md-6 col-lg-4">
                    <?php require __DIR__ . '/../partials/article-card.php'; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- Empty State -->
        <?php if (empty($reviews) && empty($articles)): ?>
        <div class="cr-empty-state">
            <i class="fas fa-folder-open"></i>
            <h3>No content yet</h3>
            <p>Check back soon for reviews and articles in this category.</p>
            <a href="/" class="cr-btn">Back to Home</a>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php
$__content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
