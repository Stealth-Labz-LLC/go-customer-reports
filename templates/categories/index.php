<?php
$pageTitle = 'Browse Categories | ' . $site->name;
$metaDescription = 'Browse all categories on ' . $site->name . '. Find reviews, articles, and guides organized by topic.';
ob_start();
?>

<!-- Breadcrumbs -->
<div class="cr-breadcrumbs">
    <div class="container">
        <span><a href="<?= BASE_URL ?>/">Home</a> &raquo; <span class="current">Categories</span></span>
    </div>
</div>

<!-- Page Header -->
<div class="cr-page-header">
    <div class="container">
        <h1>Browse Categories</h1>
        <p>Explore our content organized by topic. Find exactly what you're looking for.</p>
    </div>
</div>

<!-- Main Content -->
<div class="cr-index-page">
    <div class="container py-4">
        <?php if (!empty($categories)): ?>
        <div class="cr-results-info mb-3">
            <span><?= count($categories) ?> categories</span>
        </div>
        <div class="row g-4">
            <?php foreach ($categories as $category): ?>
            <div class="col-md-6 col-lg-4">
                <a href="<?= BASE_URL ?>/category/<?= htmlspecialchars($category->slug) ?>" class="cr-category-card">
                    <div class="cr-category-card-inner">
                        <h3 class="cr-category-card-title"><?= htmlspecialchars($category->name) ?></h3>
                        <?php if (!empty($category->description)): ?>
                        <p class="cr-category-card-desc"><?= htmlspecialchars($category->description) ?></p>
                        <?php endif; ?>
                        <div class="cr-category-card-counts">
                            <?php if ($category->review_count > 0): ?>
                            <span class="cr-count-badge"><i class="fas fa-star"></i> <?= $category->review_count ?> reviews</span>
                            <?php endif; ?>
                            <?php if ($category->article_count > 0): ?>
                            <span class="cr-count-badge"><i class="fas fa-file-alt"></i> <?= $category->article_count ?> articles</span>
                            <?php endif; ?>
                            <?php if ($category->listicle_count > 0): ?>
                            <span class="cr-count-badge"><i class="fas fa-list-ol"></i> <?= $category->listicle_count ?> lists</span>
                            <?php endif; ?>
                            <?php if ($category->review_count == 0 && $category->article_count == 0 && $category->listicle_count == 0): ?>
                            <span class="cr-count-badge cr-count-empty">Coming soon</span>
                            <?php endif; ?>
                        </div>
                        <span class="cr-category-card-link">Browse Category <i class="fas fa-chevron-right"></i></span>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="cr-empty-state">
            <i class="fas fa-folder-open"></i>
            <h3>No categories yet</h3>
            <p>Check back soon for organized content.</p>
            <a href="<?= BASE_URL ?>/" class="cr-btn">Back to Home</a>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php
$__content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
