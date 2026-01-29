<?php
$pageTitle = ($category->meta_title ?? $category->name) . ' | ' . $site->name;
$metaDescription = $category->meta_description ?? ('Browse ' . $category->name . ' reviews, articles, and buying guides on ' . $site->name);
$searchQuery = '';
$searchCategory = $category->slug;

// Category images (same map used on homepage + index)
$categoryImages = [
    'beauty'              => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=1200&h=400&fit=crop',
    'behavior'            => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=1200&h=400&fit=crop',
    'city-guide'          => 'https://images.unsplash.com/photo-1480714378408-67cf0d13bc1b?w=1200&h=400&fit=crop',
    'culinary'            => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=1200&h=400&fit=crop',
    'food'                => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=1200&h=400&fit=crop',
    'health-wellness'     => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=1200&h=400&fit=crop',
    'home'                => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=1200&h=400&fit=crop',
    'nutrition'           => 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=1200&h=400&fit=crop',
    'senior-health'       => 'https://images.unsplash.com/photo-1447452001602-7090c7ab2db3?w=1200&h=400&fit=crop',
    'state-guide'         => 'https://images.unsplash.com/photo-1501594907352-04cda38ebc29?w=1200&h=400&fit=crop',
    'sustainable-living'  => 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=1200&h=400&fit=crop',
    'training'            => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=1200&h=400&fit=crop',
    'travel'              => 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=1200&h=400&fit=crop',
    'weight-loss'         => 'https://images.unsplash.com/photo-1538805060514-97d9cc17730c?w=1200&h=400&fit=crop',
];
$defaultCatImage = 'https://images.unsplash.com/photo-1557804506-669a67965ba0?w=1200&h=400&fit=crop';
$catHeroImg = $categoryImages[$category->slug] ?? $defaultCatImage;

// Pull first article as featured (only on page 1)
$featuredArticle = ($page === 1 && !empty($articles)) ? $articles[0] : null;
$gridArticles = ($page === 1 && !empty($articles)) ? array_slice($articles, 1) : $articles;

ob_start();
?>

<!-- Breadcrumbs -->
<nav aria-label="breadcrumb" class="bg-light border-bottom py-2">
    <div class="container-xl">
        <ol class="breadcrumb mb-0 small">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/" class="text-success">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/categories" class="text-success">Categories</a></li>
            <li class="breadcrumb-item active"><?= htmlspecialchars($category->name) ?></li>
        </ol>
    </div>
</nav>

<!-- Category Hero -->
<section class="position-relative text-white category-hero-section">
    <img src="<?= $catHeroImg ?>" alt="<?= htmlspecialchars($category->name) ?>" class="category-hero-img">
    <div class="category-hero-overlay"></div>
    <div class="container-xl category-hero-content py-5">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="display-5 fw-bold mb-2"><?= htmlspecialchars($category->name) ?></h1>
                <?php if ($category->description): ?>
                <p class="lead mb-3 opacity-75"><?= htmlspecialchars($category->description) ?></p>
                <?php endif; ?>
                <div class="d-flex flex-wrap gap-3 mb-3">
                    <span class="badge bg-success fs-6 py-2 px-3"><i class="fas fa-file-alt me-1"></i> <?= number_format($articleCount) ?> Articles</span>
                    <?php if ($reviewCount > 0): ?>
                    <span class="badge bg-amber text-white fs-6 py-2 px-3"><i class="fas fa-star me-1"></i> <?= $reviewCount ?> Reviews</span>
                    <?php endif; ?>
                    <?php if ($listicleCount > 0): ?>
                    <span class="badge bg-dark bg-opacity-10 text-dark fs-6 py-2 px-3"><i class="fas fa-trophy me-1"></i> <?= $listicleCount ?> Guides</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-5 mt-3 mt-lg-0">
                <?php $size = ''; require __DIR__ . '/../partials/search-bar.php'; ?>
            </div>
        </div>
    </div>
</section>

<!-- Featured Article (page 1 only) -->
<?php if ($featuredArticle && $page === 1):
    $featUrl = BASE_URL . '/category/' . htmlspecialchars($category->slug) . '/' . htmlspecialchars($featuredArticle->slug);
?>
<section class="py-5">
    <div class="container-xl">
        <span class="section-eyebrow"><i class="fas fa-bolt me-1"></i> Featured</span>
        <h2 class="h5 fw-bold mb-3 section-heading">Featured Article</h2>
        <div class="card border-0 shadow overflow-hidden">
            <div class="row g-0">
                <?php if (!empty($featuredArticle->featured_image)): ?>
                <div class="col-lg-6">
                    <a href="<?= $featUrl ?>">
                        <img src="<?= IMAGE_BASE_URL . htmlspecialchars($featuredArticle->featured_image) ?>" alt="<?= htmlspecialchars($featuredArticle->title) ?>" class="card-img-top w-100 h-100">
                    </a>
                </div>
                <?php endif; ?>
                <div class="<?= !empty($featuredArticle->featured_image) ? 'col-lg-6' : 'col-12' ?>">
                    <div class="card-body d-flex flex-column justify-content-center p-4 p-lg-5">
                        <span class="badge bg-success mb-2 align-self-start"><?= htmlspecialchars($category->name) ?></span>
                        <h3 class="fw-bold mb-3">
                            <a href="<?= $featUrl ?>" class="text-dark text-decoration-none"><?= htmlspecialchars($featuredArticle->title) ?></a>
                        </h3>
                        <?php if (!empty($featuredArticle->excerpt)): ?>
                        <p class="text-muted mb-3"><?= htmlspecialchars(mb_substr($featuredArticle->excerpt, 0, 200)) ?>...</p>
                        <?php endif; ?>
                        <div class="d-flex align-items-center gap-3">
                            <a href="<?= $featUrl ?>" class="btn btn-success fw-bold">Read Article <i class="fas fa-arrow-right ms-1"></i></a>
                            <?php if ($featuredArticle->published_at): ?>
                            <span class="text-muted small"><i class="far fa-calendar-alt me-1"></i> <?= date('M j, Y', strtotime($featuredArticle->published_at)) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Product Reviews (page 1 only) -->
<?php if (!empty($reviews) && $page === 1): ?>
<section class="py-5 bg-light">
    <div class="container-xl">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <span class="section-eyebrow section-eyebrow-amber"><i class="fas fa-star me-1"></i> Expert Rated</span>
                <h2 class="h5 fw-bold mb-1 section-heading">Product Reviews</h2>
                <p class="text-muted small mb-0"><?= $reviewCount ?> expert reviews in <?= htmlspecialchars($category->name) ?></p>
            </div>
            <a href="<?= BASE_URL ?>/reviews?category=<?= urlencode($category->slug) ?>" class="btn btn-outline-success btn-sm">View All <i class="fas fa-arrow-right ms-1"></i></a>
        </div>
        <div class="row g-4">
            <?php foreach (array_slice($reviews, 0, 6) as $review): ?>
            <div class="col-md-6 col-lg-4">
                <?php require __DIR__ . '/../partials/review-card.php'; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Buying Guides (page 1 only) -->
<?php if (!empty($listicles) && $page === 1): ?>
<section class="py-5">
    <div class="container-xl">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <span class="section-eyebrow"><i class="fas fa-trophy me-1"></i> Top Picks</span>
                <h2 class="h5 fw-bold mb-1 section-heading">Buying Guides</h2>
                <p class="text-muted small mb-0">Top picks and comparison guides in <?= htmlspecialchars($category->name) ?></p>
            </div>
        </div>
        <div class="row g-4">
            <?php foreach ($listicles as $listicle):
                $listicleUrl = BASE_URL . '/category/' . htmlspecialchars($category->slug) . '/top/' . htmlspecialchars($listicle->slug);
            ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0 overflow-hidden">
                    <?php if (!empty($listicle->featured_image)): ?>
                    <a href="<?= $listicleUrl ?>">
                        <img src="<?= IMAGE_BASE_URL . htmlspecialchars($listicle->featured_image) ?>" class="card-img-top" alt="<?= htmlspecialchars($listicle->title) ?>">
                    </a>
                    <?php else: ?>
                    <a href="<?= $listicleUrl ?>" class="card-img-top bg-light d-flex align-items-center justify-content-center">
                        <i class="fas fa-list-ol fa-2x text-muted"></i>
                    </a>
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column">
                        <span class="badge bg-dark bg-opacity-10 text-dark mb-2 align-self-start">Buying Guide</span>
                        <h5 class="card-title fw-bold">
                            <a href="<?= $listicleUrl ?>" class="text-dark text-decoration-none"><?= htmlspecialchars($listicle->title) ?></a>
                        </h5>
                        <?php if (!empty($listicle->excerpt)): ?>
                        <p class="card-text text-muted small flex-grow-1"><?= htmlspecialchars(mb_substr($listicle->excerpt, 0, 120)) ?>...</p>
                        <?php endif; ?>
                        <a href="<?= $listicleUrl ?>" class="btn btn-sm btn-outline-success mt-auto">View Top Picks <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- All Articles -->
<section class="py-5 <?= (!empty($listicles) && $page === 1) ? '' : 'bg-light' ?>">
    <div class="container-xl">
        <div class="row">
            <!-- Main Column -->
            <div class="col-lg-9">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                    <span class="section-eyebrow"><i class="fas fa-file-alt me-1"></i> Articles</span>
                <h2 class="h5 fw-bold mb-0 section-heading">All Articles</h2>
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted small me-1">Sort:</span>
                        <?php
                        $sortOptions = ['newest' => 'Newest', 'oldest' => 'Oldest', 'title' => 'A-Z'];
                        foreach ($sortOptions as $key => $label):
                            $url = BASE_URL . '/category/' . htmlspecialchars($category->slug) . '?sort=' . $key;
                        ?>
                        <a href="<?= $url ?>" class="btn btn-sm <?= $sort === $key ? 'btn-success' : 'btn-outline-secondary' ?>"><?= $label ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php if ($totalPages > 1): ?>
                <p class="text-muted small mb-3">Page <?= $page ?> of <?= $totalPages ?> &middot; <?= number_format($articleCount) ?> articles</p>
                <?php endif; ?>

                <?php if (!empty($gridArticles)): ?>
                <div class="row g-4">
                    <?php foreach ($gridArticles as $article): ?>
                    <div class="col-md-6 col-lg-4">
                        <?php require __DIR__ . '/../partials/article-card.php'; ?>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    <?php
                    $currentPage = $page;
                    $baseUrl = BASE_URL . '/category/' . htmlspecialchars($category->slug) . '?sort=' . urlencode($sort);
                    require __DIR__ . '/../partials/pagination.php';
                    ?>
                </div>
                <?php elseif (empty($articles)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-folder-open fa-3x text-muted mb-3 d-block"></i>
                    <h3>No articles yet</h3>
                    <p class="text-muted">Check back soon for content in this category.</p>
                    <a href="<?= BASE_URL ?>/" class="btn btn-success">Back to Home</a>
                </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-3">
                <div class="sticky-lg-top sidebar-sticky">
                    <!-- Browse Categories -->
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-dark text-white fw-bold small">
                            <i class="fas fa-folder-open me-1"></i> Browse Categories
                        </div>
                        <div class="list-group list-group-flush">
                            <?php foreach ($allCategories as $cat): ?>
                            <a href="<?= BASE_URL ?>/category/<?= htmlspecialchars($cat->slug) ?>"
                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center small <?= $cat->id === $category->id ? 'active' : '' ?>">
                                <?= htmlspecialchars($cat->name) ?>
                                <span class="badge <?= $cat->id === $category->id ? 'bg-white text-dark' : 'bg-secondary' ?> rounded-pill"><?= $cat->article_count ?? 0 ?></span>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Top Reviews -->
                    <?php if (!empty($reviews)): ?>
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-dark text-white fw-bold small">
                            <i class="fas fa-star me-1"></i> Top Rated
                        </div>
                        <div class="list-group list-group-flush">
                            <?php foreach (array_slice($reviews, 0, 5) as $review):
                                $reviewUrl = BASE_URL . '/category/' . htmlspecialchars($category->slug) . '/reviews/' . htmlspecialchars($review->slug);
                            ?>
                            <a href="<?= $reviewUrl ?>" class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="small text-truncate"><?= htmlspecialchars($review->name) ?></span>
                                    <?php if ($review->rating_overall): ?>
                                    <span class="badge bg-success ms-2"><?= number_format(floatval($review->rating_overall), 1) ?></span>
                                    <?php endif; ?>
                                </div>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Trust Widget -->
                    <div class="card border-0 bg-success text-white">
                        <div class="card-body text-center small">
                            <i class="fas fa-shield-alt fa-2x mb-2 d-block"></i>
                            <strong>Trusted Reviews</strong>
                            <p class="mb-0 mt-1 small opacity-75">Expert research and unbiased recommendations across <?= number_format($articleCount) ?> articles.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$__content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
