<?php
$pageTitle = $site->name . ' - Trusted Reviews & Expert Recommendations';
$metaDescription = $site->tagline ?? 'Your trusted source for unbiased product reviews, expert recommendations, and buying guides.';
$hideHeaderSearch = true;

ob_start();

// Category icon map
$categoryIcons = [
    'beauty'              => 'fa-spa',
    'behavior'            => 'fa-brain',
    'city-guide'          => 'fa-city',
    'culinary'            => 'fa-utensils',
    'food'                => 'fa-apple-alt',
    'health-wellness'     => 'fa-heartbeat',
    'home'                => 'fa-home',
    'nutrition'           => 'fa-seedling',
    'senior-health'       => 'fa-hand-holding-heart',
    'state-guide'         => 'fa-map-marked-alt',
    'sustainable-living'  => 'fa-leaf',
    'training'            => 'fa-dumbbell',
    'travel'              => 'fa-plane-departure',
    'weight-loss'         => 'fa-weight',
];
$defaultIcon = 'fa-folder-open';
?>

<!-- Hero -->
<section class="hero-section text-white">
    <div class="hero-overlay"></div>
    <div class="container-xl hero-content">
        <div class="row">
            <div class="col-lg-8 col-xl-7">
                <span class="hero-badge mb-3"><i class="fas fa-shield-alt me-1"></i> Trusted by Thousands</span>
                <h1 class="display-4 fw-bold mb-3">Buy Smarter.<br>Live Better.</h1>
                <p class="hero-lead mb-4">We combine expert analysis with predictive AI to deliver the most comprehensive, unbiased product reviews and buying guides on the web.</p>
                <div class="d-flex flex-wrap gap-3 mb-4">
                    <a href="<?= BASE_URL ?>/categories" class="btn btn-amber btn-lg">Browse Categories <i class="fas fa-arrow-right ms-2"></i></a>
                    <a href="<?= BASE_URL ?>/articles" class="btn btn-outline-light btn-lg">Explore Articles</a>
                </div>
                <div class="d-flex flex-wrap gap-3">
                    <span class="hero-stat">
                        <i class="fas fa-file-alt"></i>
                        <strong><?= number_format($totalArticles) ?></strong> Articles
                    </span>
                    <span class="hero-stat">
                        <i class="fas fa-star"></i>
                        <strong><?= number_format($totalReviews) ?></strong> Reviews
                    </span>
                    <span class="hero-stat">
                        <i class="fas fa-folder-open"></i>
                        <strong><?= count($categories) ?></strong> Categories
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How We Work -->
<section class="py-5">
    <div class="container-xl">
        <div class="text-center mb-5">
            <span class="section-eyebrow">How We Work</span>
            <h2 class="h3 fw-bold section-heading section-heading-center d-inline-block">Powered by Data. Driven by Expertise.</h2>
            <p class="text-muted col-lg-8 mx-auto mt-3">Our review process combines cutting-edge technology with real-world research to deliver recommendations you can trust.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <div class="feature-icon mx-auto mb-3">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h5 class="fw-bold">Predictive AI Analytics</h5>
                    <p class="text-muted mb-0">Our AI models analyze market trends, consumer sentiment, and product performance data to surface insights human reviewers alone would miss.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <div class="feature-icon mx-auto mb-3">
                        <i class="fas fa-fingerprint"></i>
                    </div>
                    <h5 class="fw-bold">Deanonymization Intelligence</h5>
                    <p class="text-muted mb-0">We aggregate anonymized behavioral data across thousands of consumer touchpoints to understand what real buyers actually want.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <div class="feature-icon mx-auto mb-3">
                        <i class="fas fa-microscope"></i>
                    </div>
                    <h5 class="fw-bold">Expert Editorial Review</h5>
                    <p class="text-muted mb-0">Every piece of content is vetted by subject-matter experts who validate AI findings, test products hands-on, and ensure our recommendations hold up.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Browse by Category -->
<?php if (!empty($categories)): ?>
<section class="section-alt py-5">
    <div class="container-xl">
        <span class="section-eyebrow">Explore Topics</span>
        <h2 class="h4 mb-4 section-heading">Browse by Category</h2>
        <div class="row g-2">
            <?php foreach ($categories as $cat):
                $icon = $categoryIcons[$cat->slug] ?? $defaultIcon;
            ?>
            <div class="col-6 col-md-4 col-lg-3">
                <a href="<?= BASE_URL ?>/category/<?= htmlspecialchars($cat->slug) ?>" class="category-chip">
                    <span class="category-chip-icon">
                        <i class="fas <?= $icon ?>"></i>
                    </span>
                    <span class="category-chip-body">
                        <span class="category-chip-name"><?= htmlspecialchars($cat->name) ?></span>
                        <span class="category-chip-count"><?= number_format($cat->article_count) ?> articles</span>
                    </span>
                    <i class="fas fa-chevron-right category-chip-arrow"></i>
                </a>
            </div>
            <?php endforeach; ?>
            <div class="col-6 col-md-4 col-lg-3">
                <a href="<?= BASE_URL ?>/categories" class="category-chip category-chip-viewall">
                    <span class="category-chip-icon">
                        <i class="fas fa-th-large"></i>
                    </span>
                    <span class="category-chip-body">
                        <span class="category-chip-name">View All</span>
                        <span class="category-chip-count"><?= count($categories) ?> categories</span>
                    </span>
                    <i class="fas fa-arrow-right category-chip-arrow"></i>
                </a>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Latest Articles -->
<?php if (!empty($latestArticles)): ?>
<section class="py-5">
    <div class="container-xl">
        <span class="section-eyebrow">Fresh Content</span>
        <h2 class="h4 mb-4 section-heading">Latest Articles</h2>
        <div class="row g-4">
            <?php foreach ($latestArticles as $article): ?>
            <div class="col-md-6 col-lg-4">
                <?php require __DIR__ . '/partials/article-card.php'; ?>
            </div>
            <?php endforeach; ?>
            <div class="col-md-6 col-lg-4 d-flex">
                <a href="<?= BASE_URL ?>/articles" class="view-all-card">
                    <i class="fas fa-arrow-right view-all-card-icon"></i>
                    <span class="view-all-card-text">View All Articles</span>
                    <span class="view-all-card-count"><?= number_format($totalArticles) ?> articles</span>
                </a>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Product Reviews -->
<?php if (!empty($latestReviews)): ?>
<section class="section-alt py-5">
    <div class="container-xl">
        <span class="section-eyebrow section-eyebrow-amber"><i class="fas fa-star me-1"></i> Expert Rated</span>
        <h2 class="h4 mb-1 section-heading">Product Reviews</h2>
        <p class="text-muted mb-4 mt-3">Expert ratings and honest recommendations on products that matter.</p>
        <div class="row g-4">
            <?php foreach ($latestReviews as $review): ?>
            <div class="col-12">
                <?php $reviewHorizontal = true; require __DIR__ . '/partials/review-card.php'; ?>
            </div>
            <?php endforeach; ?>
            <div class="col-12">
                <div class="text-center mt-2">
                    <a href="<?= BASE_URL ?>/reviews" class="btn btn-outline-success btn-lg fw-bold">Browse All Reviews <i class="fas fa-arrow-right ms-2"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>


<?php
$__content = ob_get_clean();
require __DIR__ . '/layouts/app.php';
