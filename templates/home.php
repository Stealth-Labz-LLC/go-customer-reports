<?php
$pageTitle = $site->name . ' - Trusted Reviews & Expert Recommendations';
$metaDescription = $site->tagline ?? 'Your trusted source for unbiased product reviews, expert recommendations, and buying guides.';
$hideHeaderSearch = true;

ob_start();
?>

<!-- Hero -->
<section class="bg-dark text-white py-5">
    <div class="container-xl py-lg-5">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="display-4 fw-bold mb-3">Buy Smarter.<br>Live Better.</h1>
                <p class="lead text-white-50 mb-4">We combine expert analysis with predictive AI to deliver the most comprehensive, unbiased product reviews and buying guides on the web.</p>
                <div class="d-flex flex-wrap gap-3 mb-4">
                    <a href="<?= BASE_URL ?>/categories" class="btn btn-success btn-lg">Browse Categories</a>
                    <a href="<?= BASE_URL ?>/articles" class="btn btn-outline-light btn-lg">Explore Articles</a>
                </div>
                <div class="d-flex gap-4 text-white-50 small">
                    <span><i class="fas fa-file-alt me-1"></i> <strong class="text-white"><?= number_format($totalArticles) ?></strong> Articles</span>
                    <span><i class="fas fa-star me-1"></i> <strong class="text-white"><?= number_format($totalReviews) ?></strong> Reviews</span>
                    <span><i class="fas fa-folder-open me-1"></i> <strong class="text-white"><?= count($categories) ?></strong> Categories</span>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-center">
                <div class="bg-success bg-opacity-10 rounded-4 p-5">
                    <i class="fas fa-shield-alt fa-5x text-success mb-3 d-block"></i>
                    <span class="text-white-50">Trusted by thousands of readers</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How We Work — Powered By Section -->
<section class="py-5">
    <div class="container-xl">
        <div class="text-center mb-5">
            <h2 class="h3 fw-bold">Powered by Data. Driven by Expertise.</h2>
            <p class="text-muted col-lg-8 mx-auto">Our review process combines cutting-edge technology with real-world research to deliver recommendations you can trust.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:64px;height:64px;">
                        <i class="fas fa-brain fa-lg text-success"></i>
                    </div>
                    <h5>Predictive AI Analytics</h5>
                    <p class="text-muted small">Our AI models analyze market trends, consumer sentiment, and product performance data to surface insights human reviewers alone would miss.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:64px;height:64px;">
                        <i class="fas fa-fingerprint fa-lg text-success"></i>
                    </div>
                    <h5>Deanonymization Intelligence</h5>
                    <p class="text-muted small">We aggregate anonymized behavioral data across thousands of consumer touchpoints to understand what real buyers actually want — not just what they say.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:64px;height:64px;">
                        <i class="fas fa-microscope fa-lg text-success"></i>
                    </div>
                    <h5>Expert Editorial Review</h5>
                    <p class="text-muted small">Every piece of content is vetted by subject-matter experts who validate AI findings, test products hands-on, and ensure our recommendations hold up in the real world.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Browse by Category -->
<?php
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
?>
<?php if (!empty($categories)): ?>
<section class="py-5 bg-light">
    <div class="container-xl">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 mb-0">Browse by Category</h2>
            <a href="<?= BASE_URL ?>/categories" class="text-decoration-none text-success small">View All <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="row g-3">
            <?php foreach ($categories as $cat):
                $catImg = $categoryImages[$cat->slug] ?? $defaultCatImage;
            ?>
            <div class="col-6 col-md-4 col-lg-3">
                <a href="<?= BASE_URL ?>/category/<?= htmlspecialchars($cat->slug) ?>" class="card h-100 text-decoration-none shadow-sm border-0 overflow-hidden category-card">
                    <img src="<?= $catImg ?>" class="card-img-top" alt="<?= htmlspecialchars($cat->name) ?>">
                    <div class="card-img-overlay d-flex flex-column justify-content-end p-0">
                        <div class="bg-dark bg-opacity-75 text-white p-3">
                            <h5 class="card-title mb-1 fw-bold"><?= htmlspecialchars($cat->name) ?></h5>
                            <div class="d-flex gap-2">
                                <span class="badge bg-success"><?= number_format($cat->article_count) ?> articles</span>
                                <?php if ($cat->review_count > 0): ?>
                                <span class="badge bg-warning text-dark"><?= $cat->review_count ?> reviews</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Latest Articles -->
<?php if (!empty($latestArticles)): ?>
<section class="py-5">
    <div class="container-xl">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 mb-0">Latest Articles</h2>
            <a href="<?= BASE_URL ?>/articles" class="text-decoration-none text-success small">View All <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="row g-4">
            <?php foreach ($latestArticles as $article): ?>
            <div class="col-md-6 col-lg-4">
                <?php require __DIR__ . '/partials/article-card.php'; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Product Reviews -->
<?php if (!empty($latestReviews)): ?>
<section class="py-5 bg-light">
    <div class="container-xl">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
            <div>
                <h2 class="h4 mb-1"><i class="fas fa-star text-warning me-2"></i>Product Reviews</h2>
                <p class="text-muted small mb-0">Expert ratings and honest recommendations on products that matter.</p>
            </div>
            <a href="<?= BASE_URL ?>/reviews" class="btn btn-outline-success btn-sm">Browse All Reviews <i class="fas fa-arrow-right"></i></a>
        </div>
        <hr class="mb-4">
        <div class="row g-4">
            <?php foreach ($latestReviews as $review): ?>
            <div class="col-md-6 col-lg-4">
                <?php require __DIR__ . '/partials/review-card.php'; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Newsletter CTA -->
<section class="py-5 bg-dark text-white">
    <div class="container-xl">
        <div class="row justify-content-center text-center">
            <div class="col-lg-6">
                <h2 class="h4 fw-bold mb-2">Stay in the Loop</h2>
                <p class="text-white-50 mb-4">Get the latest reviews, buying guides, and expert picks delivered straight to your inbox. No spam, ever.</p>
                <form id="homepageNewsletter">
                    <div class="row g-2 justify-content-center">
                        <div class="col-sm-4">
                            <input type="text" name="name" class="form-control" placeholder="Your name" required>
                        </div>
                        <div class="col-sm-5">
                            <input type="email" name="email" class="form-control" placeholder="Your email" required>
                        </div>
                        <div class="col-sm-3">
                            <button type="submit" class="btn btn-success w-100">Subscribe</button>
                        </div>
                    </div>
                </form>
                <div id="homepageNewsletterMsg" class="small mt-2" style="display:none;"></div>
                <p class="text-white-50 mt-2 mb-0" style="font-size:.7rem;">By subscribing you agree to our <a href="<?= BASE_URL ?>/privacy" class="text-white-50">Privacy Policy</a>.</p>
            </div>
        </div>
    </div>
</section>

<script>
document.getElementById('homepageNewsletter').addEventListener('submit', function(e) {
    e.preventDefault();
    var form = this;
    var msg = document.getElementById('homepageNewsletterMsg');
    var name = form.querySelector('[name="name"]').value.trim();
    var email = form.querySelector('[name="email"]').value.trim();
    if (!name || !email) { msg.style.display='block'; msg.className='small text-danger'; msg.textContent='Please fill in all fields.'; return; }
    var btn = form.querySelector('button[type="submit"]');
    btn.disabled = true; btn.textContent = '...';
    var fd = new FormData();
    fd.append('name', name); fd.append('email', email); fd.append('phone', '');
    fd.append('campaign', 'newsletter'); fd.append('source', 'homepage_cta'); fd.append('consent', '1');
    fetch('<?= BASE_URL ?>/api/submit.php', { method: 'POST', body: fd })
    .then(function(r) { return r.json(); })
    .then(function() { msg.style.display='block'; msg.className='small text-success'; msg.textContent='Thanks for subscribing!'; form.reset(); })
    .catch(function() { msg.style.display='block'; msg.className='small text-success'; msg.textContent='Thanks for subscribing!'; form.reset(); })
    .finally(function() { btn.disabled=false; btn.textContent='Subscribe'; });
});
</script>

<?php
$__content = ob_get_clean();
require __DIR__ . '/layouts/app.php';
