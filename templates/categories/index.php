<?php
$pageTitle = 'Browse All Categories | ' . $site->name;
$metaDescription = 'Browse all ' . count($categories) . ' categories on ' . $site->name . '. Find expert reviews, articles, and buying guides across every topic.';

$categoryImages = [
    'beauty'              => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=600&h=400&fit=crop',
    'behavior'            => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=600&h=400&fit=crop',
    'city-guide'          => 'https://images.unsplash.com/photo-1480714378408-67cf0d13bc1b?w=600&h=400&fit=crop',
    'culinary'            => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=600&h=400&fit=crop',
    'food'                => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=600&h=400&fit=crop',
    'health-wellness'     => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=600&h=400&fit=crop',
    'home'                => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=600&h=400&fit=crop',
    'nutrition'           => 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=600&h=400&fit=crop',
    'senior-health'       => 'https://images.unsplash.com/photo-1447452001602-7090c7ab2db3?w=600&h=400&fit=crop',
    'state-guide'         => 'https://images.unsplash.com/photo-1501594907352-04cda38ebc29?w=600&h=400&fit=crop',
    'sustainable-living'  => 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=600&h=400&fit=crop',
    'training'            => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=600&h=400&fit=crop',
    'travel'              => 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=600&h=400&fit=crop',
    'weight-loss'         => 'https://images.unsplash.com/photo-1538805060514-97d9cc17730c?w=600&h=400&fit=crop',
];
$defaultCatImage = 'https://images.unsplash.com/photo-1557804506-669a67965ba0?w=600&h=400&fit=crop';

// Compute totals from category data
$totalArticles = 0;
$totalReviews = 0;
$totalGuides = 0;
foreach ($categories as $cat) {
    $totalArticles += ($cat->article_count ?? 0);
    $totalReviews += ($cat->review_count ?? 0);
    $totalGuides += ($cat->listicle_count ?? 0);
}

ob_start();
?>

<!-- Hero -->
<section class="hero-section-simple text-white">
    <div class="container-xl">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <span class="section-eyebrow"><i class="fas fa-th-large me-1"></i> Explore Topics</span>
                <h1 class="display-5 fw-bold mb-3">Browse All Categories</h1>
                <p class="lead text-white-50 mb-4">Explore <?= count($categories) ?> expert-curated topics covering everything from health and nutrition to travel and home improvement.</p>
                <div class="d-flex flex-wrap gap-3">
                    <span class="hero-stat"><i class="fas fa-folder-open"></i> <strong><?= count($categories) ?></strong> Categories</span>
                    <span class="hero-stat"><i class="fas fa-file-alt"></i> <strong><?= number_format($totalArticles) ?></strong> Articles</span>
                    <span class="hero-stat"><i class="fas fa-star"></i> <strong><?= number_format($totalReviews) ?></strong> Reviews</span>
                    <span class="hero-stat"><i class="fas fa-trophy"></i> <strong><?= number_format($totalGuides) ?></strong> Buying Guides</span>
                </div>
            </div>
            <div class="col-lg-5 mt-4 mt-lg-0">
                <form action="<?= BASE_URL ?>/search" method="GET">
                    <div class="input-group input-group-lg">
                        <input type="search" name="q" class="form-control" placeholder="Search all categories..." aria-label="Search">
                        <button type="submit" class="btn btn-success"><i class="fas fa-search"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Quick Jump -->
<div class="bg-light border-bottom py-3">
    <div class="container-xl">
        <div class="d-flex flex-nowrap overflow-auto gap-2 align-items-center pb-1">
            <span class="text-muted small fw-bold me-1 flex-shrink-0">Jump to:</span>
            <?php foreach ($categories as $cat): ?>
            <a href="#cat-<?= htmlspecialchars($cat->slug) ?>" class="btn btn-sm btn-outline-secondary"><?= htmlspecialchars($cat->name) ?></a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Category Grid -->
<div class="container-xl py-5">
    <div class="row g-4 g-lg-5">
        <?php foreach ($categories as $cat):
            $catImg = $categoryImages[$cat->slug] ?? $defaultCatImage;
        ?>
        <div class="col-md-6 col-lg-4" id="cat-<?= htmlspecialchars($cat->slug) ?>">
            <a href="<?= BASE_URL ?>/category/<?= htmlspecialchars($cat->slug) ?>" class="card h-100 text-decoration-none shadow-sm border-0 overflow-hidden category-card">
                <img src="<?= $catImg ?>" class="card-img-top" alt="<?= htmlspecialchars($cat->name) ?>">
                <div class="card-body d-flex flex-column">
                    <h3 class="h5 text-dark fw-bold mb-2"><?= htmlspecialchars($cat->name) ?></h3>
                    <?php if (!empty($cat->description)): ?>
                    <p class="text-muted small mb-3 flex-grow-1"><?= htmlspecialchars(mb_substr($cat->description, 0, 150)) ?></p>
                    <?php endif; ?>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="badge bg-success"><?= number_format($cat->article_count) ?> articles</span>
                        <?php if ($cat->review_count > 0): ?>
                        <span class="badge bg-amber text-white"><?= $cat->review_count ?> reviews</span>
                        <?php endif; ?>
                        <?php if ($cat->listicle_count > 0): ?>
                        <span class="badge bg-dark bg-opacity-10 text-dark"><?= $cat->listicle_count ?> guides</span>
                        <?php endif; ?>
                    </div>
                    <span class="text-success small fw-bold mt-auto">Browse Category <i class="fas fa-arrow-right ms-1"></i></span>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- CTA -->
<section class="hero-section-simple text-white">
    <div class="container-xl text-center">
        <h2 class="h4 fw-bold mb-2">Can't find what you're looking for?</h2>
        <p class="text-white-50 mb-4">Search across <?= number_format($totalArticles) ?> articles, <?= number_format($totalReviews) ?> reviews, and <?= number_format($totalGuides) ?> buying guides.</p>
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="<?= BASE_URL ?>/search" class="btn btn-success btn-lg"><i class="fas fa-search me-2"></i>Search Everything</a>
            <a href="<?= BASE_URL ?>/articles" class="btn btn-outline-light btn-lg">Browse Articles</a>
            <a href="<?= BASE_URL ?>/reviews" class="btn btn-outline-light btn-lg">Browse Reviews</a>
        </div>
    </div>
</section>

<?php
$__content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
