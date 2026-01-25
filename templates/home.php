<?php
$pageTitle = $site->name . ' - Trusted Reviews & Expert Recommendations';
$metaDescription = $site->tagline ?? 'Your trusted source for unbiased product reviews, expert recommendations, and buying guides.';

// Category images mapping (using Unsplash for high-quality stock photos)
$categoryImages = [
    'home' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=600&h=400&fit=crop',
    'health' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=600&h=400&fit=crop',
    'fitness' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=600&h=400&fit=crop',
    'pets' => 'https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=600&h=400&fit=crop',
    'beauty' => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=600&h=400&fit=crop',
    'senior' => 'https://images.unsplash.com/photo-1447452001602-7090c7ab2db3?w=600&h=400&fit=crop',
    'water' => 'https://images.unsplash.com/photo-1548839140-29a749e1cf4d?w=600&h=400&fit=crop',
    'tech' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=600&h=400&fit=crop',
    'garden' => 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=600&h=400&fit=crop',
    'kitchen' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=600&h=400&fit=crop',
    'outdoor' => 'https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?w=600&h=400&fit=crop',
    'baby' => 'https://images.unsplash.com/photo-1515488042361-ee00e0ddd4e4?w=600&h=400&fit=crop',
    'automotive' => 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?w=600&h=400&fit=crop',
    'electronics' => 'https://images.unsplash.com/photo-1550009158-9ebf69173e03?w=600&h=400&fit=crop',
    'default' => 'https://images.unsplash.com/photo-1557804506-669a67965ba0?w=600&h=400&fit=crop',
];

// Get image for category based on slug keywords
function getCategoryImage($slug, $categoryImages) {
    $slug = strtolower($slug);
    foreach ($categoryImages as $key => $url) {
        if (strpos($slug, $key) !== false) {
            return $url;
        }
    }
    return $categoryImages['default'];
}

// Count totals
$totalArticles = \App\Models\Article::count($site->id);
$totalReviews = \App\Models\Review::count($site->id);
$totalCategories = count($categories);

ob_start();
?>

<!-- Hero Section -->
<section class="cr-hero">
    <div class="container">
        <div class="cr-hero-inner">
            <div class="cr-hero-content">
                <h1>Buy Smarter.<br>Live Better.</h1>
                <p class="lead">Expert reviews and unbiased recommendations to help you make confident buying decisions. We research so you don't have to.</p>

                <div class="cr-hero-stats">
                    <div class="cr-hero-stat">
                        <div class="cr-hero-stat-number"><?= number_format($totalArticles) ?>+</div>
                        <div class="cr-hero-stat-label">Articles</div>
                    </div>
                    <div class="cr-hero-stat">
                        <div class="cr-hero-stat-number"><?= number_format($totalReviews) ?>+</div>
                        <div class="cr-hero-stat-label">Reviews</div>
                    </div>
                    <div class="cr-hero-stat">
                        <div class="cr-hero-stat-number"><?= $totalCategories ?></div>
                        <div class="cr-hero-stat-label">Categories</div>
                    </div>
                </div>
            </div>

            <div class="cr-hero-image">
                <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=600&h=500&fit=crop" alt="Expert reviewing products">
                <div class="cr-hero-image-overlay">
                    <i class="fas fa-shield-alt"></i>
                    <span>Trusted Reviews</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Category Showcase Carousel -->
<?php if (!empty($categories)): ?>
<section class="cr-category-carousel">
    <div class="container">
        <div class="cr-carousel-header">
            <h2 class="cr-carousel-title">Explore Our Expert Coverage</h2>
            <div class="cr-carousel-nav">
                <button class="cr-carousel-btn" onclick="scrollCarousel(-1)" aria-label="Previous">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="cr-carousel-btn" onclick="scrollCarousel(1)" aria-label="Next">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>

        <div class="cr-carousel-wrapper">
            <div class="cr-carousel-track" id="categoryCarousel">
                <?php foreach ($categories as $cat):
                    $catImage = getCategoryImage($cat->slug, $categoryImages);
                    $articleCount = \App\Models\Article::countByCategory($site->id, $cat->id);
                ?>
                <a href="/category/<?= htmlspecialchars($cat->slug) ?>" class="cr-showcase-card">
                    <img src="<?= $catImage ?>" alt="<?= htmlspecialchars($cat->name) ?>">
                    <div class="cr-showcase-overlay">
                        <span class="cr-showcase-badge">Explore</span>
                        <h3 class="cr-showcase-title"><?= htmlspecialchars($cat->name) ?></h3>
                        <div class="cr-showcase-count"><?= $articleCount ?> articles</div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Category Tab Navigation -->
<?php if (!empty($categories)): ?>
<nav class="cr-category-tabs">
    <div class="container">
        <div class="cr-tabs-wrapper">
            <div class="cr-tabs-list">
                <a href="/" class="cr-tab-item active">Home</a>
                <?php foreach (array_slice($categories, 0, 8) as $cat): ?>
                <a href="/category/<?= htmlspecialchars($cat->slug) ?>" class="cr-tab-item"><?= htmlspecialchars($cat->name) ?></a>
                <?php endforeach; ?>
            </div>
            <a href="/categories" class="cr-tabs-see-all">See all <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</nav>
<?php endif; ?>

<!-- Main Content Area -->
<div class="cr-home-content">
    <div class="container">
        <div class="cr-content-main">
            <!-- Primary Content -->
            <div class="cr-content-primary">

                <!-- Featured Article -->
                <?php if (!empty($latestArticles) && count($latestArticles) > 0):
                    $featured = $latestArticles[0];
                    $featuredUrl = !empty($featured->category_slug)
                        ? '/category/' . htmlspecialchars($featured->category_slug) . '/' . htmlspecialchars($featured->slug)
                        : '/articles/' . htmlspecialchars($featured->slug);
                ?>
                <div class="cr-featured-card">
                    <?php if (!empty($featured->featured_image)): ?>
                    <a href="<?= $featuredUrl ?>" class="cr-featured-card-img">
                        <img src="<?= htmlspecialchars($featured->featured_image) ?>" alt="<?= htmlspecialchars($featured->title) ?>">
                    </a>
                    <?php endif; ?>
                    <div class="cr-featured-card-body">
                        <?php if (!empty($featured->category_name)): ?>
                        <span class="cr-featured-card-category"><?= htmlspecialchars($featured->category_name) ?></span>
                        <?php endif; ?>
                        <h2 class="cr-featured-card-title">
                            <a href="<?= $featuredUrl ?>"><?= htmlspecialchars($featured->title) ?></a>
                        </h2>
                        <?php if (!empty($featured->excerpt)): ?>
                        <p class="cr-featured-card-excerpt"><?= htmlspecialchars(mb_substr($featured->excerpt, 0, 200)) ?>...</p>
                        <?php endif; ?>
                        <div class="cr-featured-card-meta">
                            <span class="cr-article-card-date">
                                <i class="far fa-calendar-alt"></i>
                                <?= $featured->published_at ? date('M j, Y', strtotime($featured->published_at)) : '' ?>
                            </span>
                            <a href="<?= $featuredUrl ?>" class="cr-article-card-link">Read More &rarr;</a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Articles by Category Sections -->
                <?php if (!empty($articlesByCategory)): ?>
                <?php foreach (array_slice($articlesByCategory, 0, 3) as $group): ?>
                <section class="cr-section" style="padding-top: 0;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="cr-section-title mb-0"><?= htmlspecialchars($group['category']->name) ?></h2>
                        <a href="/category/<?= htmlspecialchars($group['category']->slug) ?>" class="text-decoration-none" style="color:var(--cr-green);font-size:0.9rem;">View All &rarr;</a>
                    </div>
                    <div class="row g-3">
                        <?php foreach (array_slice($group['articles'], 0, 4) as $article): ?>
                        <div class="col-6 col-md-3">
                            <?php require __DIR__ . '/partials/article-card.php'; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </section>
                <?php endforeach; ?>
                <?php endif; ?>

            </div>

            <!-- Sidebar -->
            <div class="cr-content-sidebar">

                <!-- Latest Reviews Widget -->
                <?php if (!empty($latestReviews)): ?>
                <div class="cr-sidebar-featured">
                    <h3 class="cr-sidebar-featured-title"><i class="fas fa-star" style="color: var(--cr-gold); margin-right: 8px;"></i>Latest Reviews</h3>
                    <?php foreach (array_slice($latestReviews, 0, 4) as $review):
                        $reviewUrl = !empty($review->category_slug)
                            ? '/category/' . htmlspecialchars($review->category_slug) . '/reviews/' . htmlspecialchars($review->slug)
                            : '/reviews/' . htmlspecialchars($review->slug);
                    ?>
                    <div class="cr-mini-card">
                        <?php if (!empty($review->featured_image)): ?>
                        <a href="<?= $reviewUrl ?>" class="cr-mini-card-img">
                            <img src="<?= htmlspecialchars($review->featured_image) ?>" alt="<?= htmlspecialchars($review->name) ?>">
                        </a>
                        <?php endif; ?>
                        <div class="cr-mini-card-body">
                            <h4 class="cr-mini-card-title">
                                <a href="<?= $reviewUrl ?>"><?= htmlspecialchars($review->name) ?></a>
                            </h4>
                            <div class="cr-mini-card-meta">
                                <?php if ($review->rating_overall): ?>
                                <span style="color: var(--cr-green); font-weight: 600;"><?= number_format(floatval($review->rating_overall), 1) ?></span> Rating
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <a href="/categories" class="btn-cr btn btn-sm w-100 mt-3">View All Reviews</a>
                </div>
                <?php endif; ?>

                <!-- Top Lists Widget -->
                <?php if (!empty($latestListicles)): ?>
                <div class="cr-sidebar-featured">
                    <h3 class="cr-sidebar-featured-title"><i class="fas fa-trophy" style="color: var(--cr-gold); margin-right: 8px;"></i>Buying Guides</h3>
                    <?php foreach (array_slice($latestListicles, 0, 3) as $listicle):
                        $listicleUrl = !empty($listicle->category_slug)
                            ? '/category/' . htmlspecialchars($listicle->category_slug) . '/top/' . htmlspecialchars($listicle->slug)
                            : '/top/' . htmlspecialchars($listicle->slug);
                    ?>
                    <div class="cr-mini-card">
                        <?php if (!empty($listicle->featured_image)): ?>
                        <a href="<?= $listicleUrl ?>" class="cr-mini-card-img">
                            <img src="<?= htmlspecialchars($listicle->featured_image) ?>" alt="<?= htmlspecialchars($listicle->title) ?>">
                        </a>
                        <?php endif; ?>
                        <div class="cr-mini-card-body">
                            <h4 class="cr-mini-card-title">
                                <a href="<?= $listicleUrl ?>"><?= htmlspecialchars($listicle->title) ?></a>
                            </h4>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Trust Signals Widget -->
                <div class="cr-sidebar-featured" style="background: linear-gradient(135deg, var(--cr-navy) 0%, #0d0d1a 100%); color: #fff;">
                    <h3 class="cr-sidebar-featured-title" style="color: #fff; border-bottom-color: var(--cr-green-light);">
                        <i class="fas fa-shield-alt" style="color: var(--cr-green-light); margin-right: 8px;"></i>Why Trust Us?
                    </h3>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="display: flex; align-items: center; gap: 10px; padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.1);">
                            <i class="fas fa-check-circle" style="color: var(--cr-green-light);"></i>
                            <span>Unbiased Expert Reviews</span>
                        </li>
                        <li style="display: flex; align-items: center; gap: 10px; padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.1);">
                            <i class="fas fa-check-circle" style="color: var(--cr-green-light);"></i>
                            <span>Thorough Research</span>
                        </li>
                        <li style="display: flex; align-items: center; gap: 10px; padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.1);">
                            <i class="fas fa-check-circle" style="color: var(--cr-green-light);"></i>
                            <span>Real User Insights</span>
                        </li>
                        <li style="display: flex; align-items: center; gap: 10px; padding: 10px 0;">
                            <i class="fas fa-check-circle" style="color: var(--cr-green-light);"></i>
                            <span>No Hidden Agendas</span>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Trust Banner -->
<section class="cr-trust-banner">
    <div class="container">
        <div class="cr-trust-inner">
            <div class="cr-trust-content">
                <h2>Research You Can Rely On</h2>
                <p>We thoroughly research and test products to bring you honest, unbiased recommendations that save you time and money.</p>
            </div>
            <div class="cr-trust-stats">
                <div class="cr-trust-stat">
                    <div class="cr-trust-stat-icon"><i class="fas fa-file-alt"></i></div>
                    <div class="cr-trust-stat-number"><?= number_format($totalArticles) ?></div>
                    <div class="cr-trust-stat-label">Articles</div>
                </div>
                <div class="cr-trust-stat">
                    <div class="cr-trust-stat-icon"><i class="fas fa-star"></i></div>
                    <div class="cr-trust-stat-number"><?= number_format($totalReviews) ?></div>
                    <div class="cr-trust-stat-label">Reviews</div>
                </div>
                <div class="cr-trust-stat">
                    <div class="cr-trust-stat-icon"><i class="fas fa-folder-open"></i></div>
                    <div class="cr-trust-stat-number"><?= $totalCategories ?></div>
                    <div class="cr-trust-stat-label">Categories</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Carousel JavaScript -->
<script>
function scrollCarousel(direction) {
    const track = document.getElementById('categoryCarousel');
    const scrollAmount = 300;
    track.scrollBy({ left: direction * scrollAmount, behavior: 'smooth' });
}
</script>

<?php
$__content = ob_get_clean();
require __DIR__ . '/layouts/app.php';
