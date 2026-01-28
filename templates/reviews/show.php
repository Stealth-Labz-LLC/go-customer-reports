<?php
$pageTitle = ($review->meta_title ?? $review->name . ' Review') . ' | ' . $site->name;
$metaDescription = $review->meta_description ?? ($review->short_description ?? '');
$ogImage = $review->featured_image ?? null;
$ogType = 'product';
$rating = floatval($review->rating_overall ?? 0);
$isTopPick = $rating >= 4.5;
$fullStars = floor($rating);
$hasHalf = ($rating - $fullStars) >= 0.5;

// Schema.org structured data
$schemaData = [
    '@context' => 'https://schema.org',
    '@type' => 'Product',
    'name' => $review->name,
    'description' => $review->short_description ?? $review->meta_description ?? '',
    'brand' => [
        '@type' => 'Brand',
        'name' => $review->brand ?? $review->name
    ],
    'review' => [
        '@type' => 'Review',
        'reviewRating' => [
            '@type' => 'Rating',
            'ratingValue' => number_format($rating, 1),
            'bestRating' => '5',
            'worstRating' => '1'
        ],
        'author' => [
            '@type' => 'Organization',
            'name' => $site->name
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name' => $site->name
        ],
        'datePublished' => $review->published_at ? date('Y-m-d', strtotime($review->published_at)) : date('Y-m-d'),
        'reviewBody' => strip_tags($review->short_description ?? '')
    ],
    'aggregateRating' => [
        '@type' => 'AggregateRating',
        'ratingValue' => number_format($rating, 1),
        'bestRating' => '5',
        'worstRating' => '1',
        'ratingCount' => '1'
    ]
];

// Add image if available
if (!empty($review->featured_image)) {
    $schemaData['image'] = $review->featured_image;
}

// Add offers if affiliate URL exists
if (!empty($review->affiliate_url)) {
    $schemaData['offers'] = [
        '@type' => 'Offer',
        'url' => $review->affiliate_url,
        'availability' => 'https://schema.org/InStock'
    ];
    if (!empty($review->price)) {
        $schemaData['offers']['price'] = $review->price;
        $schemaData['offers']['priceCurrency'] = 'USD';
    }
}

ob_start();
?>

<!-- Schema.org Product/Review structured data -->
<script type="application/ld+json">
<?= json_encode($schemaData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?>
</script>

<?php if (!empty($breadcrumbs)): ?>
    <?php include __DIR__ . '/../partials/breadcrumbs.php'; ?>
<?php endif; ?>

<!-- Review Hero Section -->
<section class="cr-review-hero">
    <div class="container">
        <div class="cr-review-hero-inner">
            <div class="cr-review-hero-content">
                <?php if ($isTopPick): ?>
                <div class="cr-review-hero-badge">
                    <i class="fas fa-award"></i> Editor's Choice
                </div>
                <?php endif; ?>

                <h1><?= htmlspecialchars($review->name) ?> Review</h1>

                <?php if ($review->brand): ?>
                <div class="cr-review-hero-brand">by <?= htmlspecialchars($review->brand) ?></div>
                <?php endif; ?>

                <?php if ($review->rating_overall): ?>
                <div class="cr-review-hero-rating">
                    <span class="cr-review-hero-score"><?= number_format($rating, 1) ?></span>
                    <div class="cr-review-hero-stars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                        <i class="fas fa-star<?= $i <= $fullStars ? ' filled' : ($i == $fullStars + 1 && $hasHalf ? '-half-alt filled' : '') ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <span class="cr-review-hero-label">Overall Rating</span>
                </div>
                <?php endif; ?>

                <?php if (!empty($review->affiliate_url)): ?>
                <a href="<?= htmlspecialchars($review->affiliate_url) ?>" target="_blank" rel="nofollow sponsored" class="cr-review-hero-cta">
                    <?= htmlspecialchars($review->cta_text ?? 'Check Best Price') ?> <i class="fas fa-external-link-alt"></i>
                </a>
                <?php endif; ?>
            </div>

            <?php if (!empty($review->featured_image)): ?>
            <div class="cr-review-hero-image">
                <img src="<?= IMAGE_BASE_URL . htmlspecialchars($review->featured_image) ?>" alt="<?= htmlspecialchars($review->name) ?>">
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Review Layout: Sticky Sidebar Left + Content Right -->
<div class="cr-review-page">
    <div class="container py-4">
        <div class="row">
            <!-- LEFT COLUMN - Product Card (Sticky) -->
            <div class="col-lg-3 col-md-12">
                <div class="cr-review-product-card">
                    <!-- Editor's Choice Badge -->
                    <?php if ($isTopPick): ?>
                    <div class="cr-product-badge">
                        <i class="fas fa-award"></i> Editor's Choice
                    </div>
                    <?php endif; ?>

                    <!-- Product Image -->
                    <div class="cr-review-product-image">
                        <?php if (!empty($review->featured_image)): ?>
                        <img src="<?= IMAGE_BASE_URL . htmlspecialchars($review->featured_image) ?>" alt="<?= htmlspecialchars($review->name) ?>">
                        <?php else: ?>
                        <div class="cr-review-image-placeholder">
                            <i class="fas fa-box"></i>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Product Title -->
                    <h2 class="cr-review-product-title"><?= htmlspecialchars($review->name) ?></h2>

                    <!-- Brand -->
                    <?php if ($review->brand): ?>
                    <div class="cr-review-product-brand">by <?= htmlspecialchars($review->brand) ?></div>
                    <?php endif; ?>

                    <!-- Overall Rating Display -->
                    <?php if ($review->rating_overall): ?>
                    <div class="cr-product-overall-rating">
                        <div class="cr-overall-score"><?= number_format($rating, 1) ?></div>
                        <div class="cr-overall-meta">
                            <div class="cr-review-stars">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star<?= $i <= $fullStars ? ' filled' : ($i == $fullStars + 1 && $hasHalf ? '-half-alt filled' : '') ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <span class="cr-rating-label">Overall Score</span>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Category Ratings -->
                    <?php if ($review->rating_ingredients || $review->rating_value || $review->rating_effectiveness || $review->rating_customer_experience): ?>
                    <div class="cr-product-ratings-breakdown">
                        <?php if ($review->rating_ingredients): ?>
                        <div class="cr-rating-row">
                            <span class="cr-rating-label">Ingredients</span>
                            <div class="cr-rating-bar">
                                <div class="cr-rating-fill" style="width: <?= (floatval($review->rating_ingredients) / 5) * 100 ?>%"></div>
                            </div>
                            <span class="cr-rating-value"><?= number_format(floatval($review->rating_ingredients), 1) ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ($review->rating_value): ?>
                        <div class="cr-rating-row">
                            <span class="cr-rating-label">Value</span>
                            <div class="cr-rating-bar">
                                <div class="cr-rating-fill" style="width: <?= (floatval($review->rating_value) / 5) * 100 ?>%"></div>
                            </div>
                            <span class="cr-rating-value"><?= number_format(floatval($review->rating_value), 1) ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ($review->rating_effectiveness): ?>
                        <div class="cr-rating-row">
                            <span class="cr-rating-label">Effectiveness</span>
                            <div class="cr-rating-bar">
                                <div class="cr-rating-fill" style="width: <?= (floatval($review->rating_effectiveness) / 5) * 100 ?>%"></div>
                            </div>
                            <span class="cr-rating-value"><?= number_format(floatval($review->rating_effectiveness), 1) ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ($review->rating_customer_experience): ?>
                        <div class="cr-rating-row">
                            <span class="cr-rating-label">Experience</span>
                            <div class="cr-rating-bar">
                                <div class="cr-rating-fill" style="width: <?= (floatval($review->rating_customer_experience) / 5) * 100 ?>%"></div>
                            </div>
                            <span class="cr-rating-value"><?= number_format(floatval($review->rating_customer_experience), 1) ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Price Display -->
                    <?php if ($review->price): ?>
                    <div class="cr-product-price-box">
                        <div class="cr-price-label">Starting at</div>
                        <div class="cr-price-amount"><?= htmlspecialchars($review->price) ?></div>
                        <?php if ($review->price_note): ?>
                        <div class="cr-price-note"><?= htmlspecialchars($review->price_note) ?></div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Primary CTA -->
                    <div class="cr-product-cta-section">
                        <?php if (!empty($review->affiliate_url)): ?>
                        <a href="<?= htmlspecialchars($review->affiliate_url) ?>" target="_blank" rel="nofollow sponsored" class="cr-cta-primary">
                            <?= htmlspecialchars($review->cta_text ?? 'Check Price') ?> <i class="fas fa-external-link-alt"></i>
                        </a>
                        <?php endif; ?>
                        <a href="#review-content" class="cr-cta-secondary">
                            Read Full Review <i class="fas fa-chevron-down"></i>
                        </a>
                    </div>

                    <!-- Trust Signals -->
                    <div class="cr-product-trust-signals">
                        <div class="cr-trust-item">
                            <i class="fas fa-shield-alt"></i>
                            <span>Verified Review</span>
                        </div>
                        <div class="cr-trust-item">
                            <i class="fas fa-undo"></i>
                            <span>Money-Back Guarantee</span>
                        </div>
                        <div class="cr-trust-item">
                            <i class="fas fa-truck"></i>
                            <span>Fast Shipping</span>
                        </div>
                    </div>

                    <!-- Short Description / Why We Recommend -->
                    <?php if ($review->short_description): ?>
                    <div class="cr-product-why-recommend">
                        <div class="cr-why-title"><i class="fas fa-lightbulb"></i> Why We Recommend</div>
                        <p><?= htmlspecialchars($review->short_description) ?></p>
                    </div>
                    <?php endif; ?>

                    <!-- Quick Pros -->
                    <?php if (!empty($review->pros) && count($review->pros) > 0): ?>
                    <div class="cr-product-quick-pros">
                        <div class="cr-quickpros-title">Key Benefits</div>
                        <ul>
                            <?php foreach (array_slice($review->pros, 0, 3) as $pro): ?>
                            <li><i class="fas fa-check"></i> <?= htmlspecialchars($pro) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- RIGHT COLUMN - Review Content (Scrolls) -->
            <div class="col-lg-9 col-md-12">
                <div class="cr-review-content-wrap" id="review-content">
                    <!-- Author Meta -->
                    <div class="cr-review-author-meta">
                        <div class="cr-review-author-avatar">
                            <i class="fas fa-user-circle cr-author-icon-large"></i>
                        </div>
                        <div class="cr-review-author-info">
                            <span class="cr-review-author-name">by <?= htmlspecialchars($site->name) ?> Team</span>
                            <?php if ($review->published_at): ?>
                            <span class="cr-review-date">Updated <?= date('F j, Y', strtotime($review->published_at)) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Pros & Cons -->
                    <?php if (!empty($review->pros) || !empty($review->cons)): ?>
                    <div class="cr-review-pros-cons">
                        <?php if (!empty($review->pros)): ?>
                        <div class="cr-review-pros">
                            <div class="cr-pros-title"><i class="fas fa-thumbs-up"></i> Pros</div>
                            <div class="cr-pros-content">
                                <ul>
                                    <?php foreach ($review->pros as $pro): ?>
                                    <li><?= htmlspecialchars($pro) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($review->cons)): ?>
                        <div class="cr-review-cons">
                            <div class="cr-cons-title"><i class="fas fa-thumbs-down"></i> Cons</div>
                            <div class="cr-cons-content">
                                <ul>
                                    <?php foreach ($review->cons as $con): ?>
                                    <li><?= htmlspecialchars($con) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Main Content -->
                    <div class="cr-review-content">
                        <?= $review->content ?>
                    </div>

                    <!-- Product Details Table -->
                    <?php if (!empty($reviewCategories) || $review->brand): ?>
                    <div class="cr-review-details">
                        <div class="cr-review-details-title">
                            <h3><?= htmlspecialchars($review->name) ?> Details</h3>
                        </div>

                        <?php if ($review->brand): ?>
                        <div class="cr-review-details-item">
                            <div class="cr-details-label">Brand:</div>
                            <div class="cr-details-value"><?= htmlspecialchars($review->brand) ?></div>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($reviewCategories)): ?>
                        <div class="cr-review-details-item">
                            <div class="cr-details-label">Category:</div>
                            <div class="cr-details-value">
                                <?php foreach ($reviewCategories as $cat): ?>
                                <a href="<?= BASE_URL ?>/category/<?= htmlspecialchars($cat->slug) ?>"><?= htmlspecialchars($cat->name) ?></a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <!-- ========================================
                         FINAL VERDICT BOX - Critical for Conversion
                         ======================================== -->
                    <div class="cr-final-verdict">
                        <div class="cr-verdict-header">
                            <h3>Final Verdict</h3>
                        </div>
                        <div class="cr-verdict-content">
                            <div class="cr-verdict-score-wrap">
                                <?php if (!empty($review->featured_image)): ?>
                                <img src="<?= IMAGE_BASE_URL . htmlspecialchars($review->featured_image) ?>" alt="<?= htmlspecialchars($review->name) ?>" class="cr-verdict-product-img">
                                <?php endif; ?>
                                <div class="cr-verdict-score">
                                    <div class="cr-verdict-score-number"><?= number_format($rating, 1) ?></div>
                                    <div class="cr-verdict-score-stars">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star<?= $i <= $fullStars ? ' filled' : '' ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <?php if ($isTopPick): ?>
                                    <div class="cr-verdict-badge">Editor's Choice</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="cr-verdict-summary">
                                <h4><?= htmlspecialchars($review->name) ?></h4>
                                <?php if ($review->short_description): ?>
                                <p><?= htmlspecialchars($review->short_description) ?></p>
                                <?php else: ?>
                                <p>Based on our comprehensive analysis, <?= htmlspecialchars($review->name) ?> <?= $rating >= 4.0 ? 'is a solid choice' : 'has room for improvement' ?> in its category.</p>
                                <?php endif; ?>

                                <?php if (!empty($review->pros)): ?>
                                <ul class="cr-verdict-highlights">
                                    <?php foreach (array_slice($review->pros, 0, 3) as $pro): ?>
                                    <li><i class="fas fa-check-circle"></i> <?= htmlspecialchars($pro) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="cr-verdict-cta">
                            <?php if (!empty($review->affiliate_url)): ?>
                            <a href="<?= htmlspecialchars($review->affiliate_url) ?>" target="_blank" rel="nofollow sponsored" class="cr-verdict-btn-primary">
                                <?= htmlspecialchars($review->cta_text ?? 'Check Best Price') ?> <i class="fas fa-external-link-alt"></i>
                            </a>
                            <?php endif; ?>
                            <div class="cr-verdict-trust">
                                <span><i class="fas fa-shield-alt"></i> Verified Review</span>
                                <span><i class="fas fa-lock"></i> Secure Purchase</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Related Reviews Section -->
<?php if (!empty($relatedReviews)): ?>
<section class="cr-related-reviews">
    <div class="container">
        <div class="cr-related-header">
            <h2>Related Reviews</h2>
            <?php if ($primaryCategory): ?>
            <a href="<?= BASE_URL ?>/category/<?= htmlspecialchars($primaryCategory->slug) ?>" class="cr-related-link">View All <?= htmlspecialchars($primaryCategory->name) ?> Reviews &rarr;</a>
            <?php endif; ?>
        </div>
        <div class="row g-4">
            <?php foreach ($relatedReviews as $relReview):
                $relUrl = BASE_URL . ($primaryCategory
                    ? '/category/' . htmlspecialchars($primaryCategory->slug) . '/reviews/' . htmlspecialchars($relReview->slug)
                    : '/reviews/' . htmlspecialchars($relReview->slug));
            ?>
            <div class="col-md-6 col-lg-3">
                <div class="cr-review-card h-100">
                    <?php if (!empty($relReview->featured_image)): ?>
                    <a href="<?= $relUrl ?>" class="cr-review-card-img">
                        <img src="<?= IMAGE_BASE_URL . htmlspecialchars($relReview->featured_image) ?>" alt="<?= htmlspecialchars($relReview->name) ?>">
                    </a>
                    <?php endif; ?>
                    <div class="cr-review-card-body">
                        <h3 class="cr-review-card-title">
                            <a href="<?= $relUrl ?>"><?= htmlspecialchars($relReview->name) ?></a>
                        </h3>
                        <?php if ($relReview->rating_overall): ?>
                        <div class="cr-review-card-rating">
                            <span class="cr-rating-score"><?= number_format(floatval($relReview->rating_overall), 1) ?></span>
                            <span class="cr-rating-stars">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star<?= $i <= floor(floatval($relReview->rating_overall)) ? ' filled' : '' ?>"></i>
                                <?php endfor; ?>
                            </span>
                        </div>
                        <?php endif; ?>
                        <a href="<?= $relUrl ?>" class="cr-btn-sm">Read Review</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Mobile Sticky CTA Bar -->
<?php if (!empty($review->affiliate_url)): ?>
<div class="cr-mobile-cta-bar">
    <div class="cr-mobile-cta-info">
        <span class="cr-mobile-cta-name"><?= htmlspecialchars($review->name) ?></span>
        <?php if ($review->rating_overall): ?>
        <span class="cr-mobile-cta-rating"><?= number_format($rating, 1) ?> <i class="fas fa-star filled"></i></span>
        <?php endif; ?>
    </div>
    <a href="<?= htmlspecialchars($review->affiliate_url) ?>" target="_blank" rel="nofollow sponsored" class="cr-mobile-cta-btn">
        <?= htmlspecialchars($review->cta_text ?? 'Check Price') ?>
    </a>
</div>
<?php endif; ?>

<?php
$__content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
