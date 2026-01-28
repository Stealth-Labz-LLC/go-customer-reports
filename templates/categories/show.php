<?php
$pageTitle = ($category->meta_title ?? $category->name) . ' | ' . $site->name;
$metaDescription = $category->meta_description ?? ('Browse ' . $category->name . ' reviews, articles, and buying guides on ' . $site->name);

// Category images mapping
$categoryImages = [
    'home' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=400&h=250&fit=crop',
    'health' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=400&h=250&fit=crop',
    'fitness' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=400&h=250&fit=crop',
    'pets' => 'https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=400&h=250&fit=crop',
    'beauty' => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=400&h=250&fit=crop',
    'senior' => 'https://images.unsplash.com/photo-1447452001602-7090c7ab2db3?w=400&h=250&fit=crop',
    'water' => 'https://images.unsplash.com/photo-1548839140-29a749e1cf4d?w=400&h=250&fit=crop',
    'tech' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=400&h=250&fit=crop',
    'garden' => 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=400&h=250&fit=crop',
    'kitchen' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=400&h=250&fit=crop',
    'outdoor' => 'https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?w=400&h=250&fit=crop',
    'baby' => 'https://images.unsplash.com/photo-1515488042361-ee00e0ddd4e4?w=400&h=250&fit=crop',
    'automotive' => 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?w=400&h=250&fit=crop',
    'electronics' => 'https://images.unsplash.com/photo-1550009158-9ebf69173e03?w=400&h=250&fit=crop',
    'default' => 'https://images.unsplash.com/photo-1557804506-669a67965ba0?w=400&h=250&fit=crop',
];

function getCatImage($slug, $images) {
    $slug = strtolower($slug);
    foreach ($images as $key => $url) {
        if (strpos($slug, $key) !== false) {
            return $url;
        }
    }
    return $images['default'];
}

$catImage = getCatImage($category->slug, $categoryImages);

ob_start();
?>

<!-- Breadcrumbs -->
<div class="cr-breadcrumbs">
    <div class="container">
        <span><a href="<?= BASE_URL ?>/">Home</a> &raquo; <a href="<?= BASE_URL ?>/categories">Categories</a> &raquo; <span class="current"><?= htmlspecialchars($category->name) ?></span></span>
    </div>
</div>

<!-- Category Hero -->
<section class="cr-cat-hero">
    <div class="container">
        <div class="cr-cat-hero-inner">
            <div class="cr-cat-hero-content">
                <h1><?= htmlspecialchars($category->name) ?></h1>
                <?php if ($category->description): ?>
                <p class="cr-cat-hero-desc"><?= htmlspecialchars($category->description) ?></p>
                <?php else: ?>
                <p class="cr-cat-hero-desc">Explore our expert reviews, in-depth articles, and comprehensive buying guides for <?= htmlspecialchars(strtolower($category->name)) ?>.</p>
                <?php endif; ?>

                <div class="cr-cat-hero-stats">
                    <div class="cr-cat-stat">
                        <div class="cr-cat-stat-number"><?= number_format($articleCount) ?></div>
                        <div class="cr-cat-stat-label">Articles</div>
                    </div>
                    <div class="cr-cat-stat">
                        <div class="cr-cat-stat-number"><?= $reviewCount ?></div>
                        <div class="cr-cat-stat-label">Reviews</div>
                    </div>
                    <div class="cr-cat-stat">
                        <div class="cr-cat-stat-number"><?= $listicleCount ?></div>
                        <div class="cr-cat-stat-label">Guides</div>
                    </div>
                </div>
            </div>

            <div class="cr-cat-hero-image">
                <img src="<?= $catImage ?>" alt="<?= htmlspecialchars($category->name) ?>">
            </div>
        </div>
    </div>
</section>

<!-- Content Type Tabs -->
<nav class="cr-content-tabs">
    <div class="container">
        <div class="cr-content-tabs-inner">
            <div class="cr-content-tabs-list">
                <a href="#all" class="cr-content-tab active">All Content <span class="cr-content-tab-count"><?= $articleCount + $reviewCount + $listicleCount ?></span></a>
                <?php if ($articleCount > 0): ?>
                <a href="#articles" class="cr-content-tab">Articles <span class="cr-content-tab-count"><?= $articleCount ?></span></a>
                <?php endif; ?>
                <?php if ($reviewCount > 0): ?>
                <a href="#reviews" class="cr-content-tab">Reviews <span class="cr-content-tab-count"><?= $reviewCount ?></span></a>
                <?php endif; ?>
                <?php if ($listicleCount > 0): ?>
                <a href="#guides" class="cr-content-tab">Buying Guides <span class="cr-content-tab-count"><?= $listicleCount ?></span></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="cr-cat-page">
    <div class="container">
        <div class="cr-cat-layout">
            <!-- Main Column -->
            <div class="cr-cat-main">

                <!-- Featured Article -->
                <?php if (!empty($articles) && count($articles) > 0):
                    $featured = $articles[0];
                    $featuredUrl = BASE_URL . '/category/' . htmlspecialchars($category->slug) . '/' . htmlspecialchars($featured->slug);
                ?>
                <div class="cr-cat-featured">
                    <?php if (!empty($featured->featured_image)): ?>
                    <a href="<?= $featuredUrl ?>" class="cr-cat-featured-img">
                        <img src="<?= IMAGE_BASE_URL . htmlspecialchars($featured->featured_image) ?>" alt="<?= htmlspecialchars($featured->title) ?>">
                    </a>
                    <?php else: ?>
                    <div class="cr-cat-featured-img">
                        <div class="cr-cat-featured-img-placeholder">
                            <i class="fas fa-newspaper"></i>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="cr-cat-featured-body">
                        <span class="cr-cat-featured-badge">Featured</span>
                        <h2 class="cr-cat-featured-title">
                            <a href="<?= $featuredUrl ?>"><?= htmlspecialchars($featured->title) ?></a>
                        </h2>
                        <?php if (!empty($featured->excerpt)): ?>
                        <p class="cr-cat-featured-excerpt"><?= htmlspecialchars(mb_substr($featured->excerpt, 0, 250)) ?>...</p>
                        <?php endif; ?>
                        <div class="cr-cat-featured-meta">
                            <span class="cr-cat-featured-date">
                                <i class="far fa-calendar-alt"></i>
                                <?= $featured->published_at ? date('M j, Y', strtotime($featured->published_at)) : '' ?>
                            </span>
                            <a href="<?= $featuredUrl ?>" class="cr-cat-featured-link">Read Article &rarr;</a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Reviews Section -->
                <?php if (!empty($reviews)): ?>
                <section class="cr-cat-section" id="reviews">
                    <div class="cr-cat-section-header">
                        <h2 class="cr-cat-section-title"><i class="fas fa-star"></i> Product Reviews</h2>
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

                <!-- Buying Guides Section -->
                <?php if (!empty($listicles)): ?>
                <section class="cr-cat-section" id="guides">
                    <div class="cr-cat-section-header">
                        <h2 class="cr-cat-section-title"><i class="fas fa-trophy"></i> Buying Guides</h2>
                        <span class="text-muted"><?= count($listicles) ?> guides</span>
                    </div>
                    <div class="row g-4">
                        <?php foreach ($listicles as $listicle):
                            $listicleUrl = BASE_URL . '/category/' . htmlspecialchars($category->slug) . '/top/' . htmlspecialchars($listicle->slug);
                        ?>
                        <div class="col-md-6">
                            <div class="cr-listicle-card h-100">
                                <?php if (!empty($listicle->featured_image)): ?>
                                <a href="<?= $listicleUrl ?>" class="cr-listicle-card-img">
                                    <img src="<?= IMAGE_BASE_URL . htmlspecialchars($listicle->featured_image) ?>" alt="<?= htmlspecialchars($listicle->title) ?>">
                                </a>
                                <?php else: ?>
                                <a href="<?= $listicleUrl ?>" class="cr-listicle-card-img cr-listicle-card-placeholder">
                                    <i class="fas fa-list-ol"></i>
                                </a>
                                <?php endif; ?>
                                <div class="cr-listicle-card-body">
                                    <h3 class="cr-listicle-card-title">
                                        <a href="<?= $listicleUrl ?>"><?= htmlspecialchars($listicle->title) ?></a>
                                    </h3>
                                    <?php if (!empty($listicle->excerpt)): ?>
                                    <p class="cr-listicle-card-excerpt"><?= htmlspecialchars(mb_substr($listicle->excerpt, 0, 120)) ?>...</p>
                                    <?php endif; ?>
                                    <div class="cr-listicle-card-meta">
                                        <?php if ($listicle->published_at): ?>
                                        <span class="cr-listicle-card-date"><i class="far fa-calendar-alt"></i> <?= date('M j, Y', strtotime($listicle->published_at)) ?></span>
                                        <?php endif; ?>
                                        <a href="<?= $listicleUrl ?>" class="cr-listicle-card-link">View Guide &rarr;</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </section>
                <?php endif; ?>

                <!-- Articles Section -->
                <?php if (!empty($articles) && count($articles) > 1): ?>
                <section class="cr-cat-section" id="articles">
                    <div class="cr-cat-section-header">
                        <h2 class="cr-cat-section-title"><i class="fas fa-file-alt"></i> Articles & Guides</h2>
                        <span class="text-muted"><?= count($articles) ?> articles</span>
                    </div>
                    <div class="row g-4">
                        <?php foreach (array_slice($articles, 1) as $article): ?>
                        <div class="col-md-6 col-lg-4">
                            <?php require __DIR__ . '/../partials/article-card.php'; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </section>
                <?php endif; ?>

                <!-- Empty State -->
                <?php if (empty($reviews) && empty($articles) && empty($listicles)): ?>
                <div class="cr-empty-state">
                    <i class="fas fa-folder-open"></i>
                    <h3>No content yet</h3>
                    <p>Check back soon for reviews and articles in this category.</p>
                    <a href="<?= BASE_URL ?>/" class="cr-btn">Back to Home</a>
                </div>
                <?php endif; ?>

            </div>

            <!-- Sidebar -->
            <div class="cr-cat-sidebar">

                <!-- Top Reviews Widget -->
                <?php if (!empty($reviews)): ?>
                <div class="cr-cat-widget">
                    <h3 class="cr-cat-widget-title"><i class="fas fa-star"></i> Top Rated</h3>
                    <?php foreach (array_slice($reviews, 0, 3) as $review):
                        $reviewUrl = BASE_URL . '/category/' . htmlspecialchars($category->slug) . '/reviews/' . htmlspecialchars($review->slug);
                    ?>
                    <div class="cr-review-mini">
                        <?php if (!empty($review->featured_image)): ?>
                        <a href="<?= $reviewUrl ?>" class="cr-review-mini-img">
                            <img src="<?= IMAGE_BASE_URL . htmlspecialchars($review->featured_image) ?>" alt="<?= htmlspecialchars($review->name) ?>">
                        </a>
                        <?php endif; ?>
                        <div class="cr-review-mini-body">
                            <h4 class="cr-review-mini-title">
                                <a href="<?= $reviewUrl ?>"><?= htmlspecialchars($review->name) ?></a>
                            </h4>
                            <?php if ($review->rating_overall): ?>
                            <div class="cr-review-mini-rating">
                                <span><?= number_format(floatval($review->rating_overall), 1) ?></span> / 5.0
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Browse Categories Widget -->
                <div class="cr-cat-widget">
                    <h3 class="cr-cat-widget-title"><i class="fas fa-folder-open"></i> Browse Categories</h3>
                    <ul class="cr-cat-nav-list">
                        <?php foreach ($allCategories as $cat):
                            $isActive = $cat->id === $category->id;
                            $catCount = \App\Models\Article::countByCategory($site->id, $cat->id);
                        ?>
                        <li class="cr-cat-nav-item">
                            <a href="<?= BASE_URL ?>/category/<?= htmlspecialchars($cat->slug) ?>" class="cr-cat-nav-link <?= $isActive ? 'active' : '' ?>">
                                <?= htmlspecialchars($cat->name) ?>
                                <span class="cr-cat-nav-count"><?= $catCount ?></span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Trust Widget -->
                <div class="cr-cat-widget cr-trust-widget-dark">
                    <h3 class="cr-cat-widget-title cr-trust-widget-title">
                        <i class="fas fa-shield-alt cr-trust-widget-icon"></i> Why Trust Us?
                    </h3>
                    <ul class="cr-trust-list-dark">
                        <li>
                            <i class="fas fa-check-circle cr-trust-check"></i>
                            <span>Expert Reviews</span>
                        </li>
                        <li>
                            <i class="fas fa-check-circle cr-trust-check"></i>
                            <span>Unbiased Ratings</span>
                        </li>
                        <li>
                            <i class="fas fa-check-circle cr-trust-check"></i>
                            <span>Real Research</span>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</div>

<?php
$__content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
