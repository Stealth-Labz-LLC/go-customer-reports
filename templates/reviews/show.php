<?php
$pageTitle = ($review->meta_title ?? $review->name . ' Review') . ' | ' . $site->name;
$metaDescription = $review->meta_description ?? ($review->short_description ?? '');
$ogImage = $review->featured_image ?? null;
$ogType = 'product';
$rating = floatval($review->rating_overall ?? 0);
$isTopPick = $rating >= 4.5;
$fullStars = floor($rating);
$hasHalf = ($rating - $fullStars) >= 0.3;
$emptyStars = 5 - $fullStars - ($hasHalf ? 1 : 0);

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

if (!empty($review->featured_image)) {
    $schemaData['image'] = $review->featured_image;
}

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

// Rating color helper
$ratingClass = 'bg-success';
if ($rating && $rating < 3.0) $ratingClass = 'bg-danger';
elseif ($rating && $rating < 4.0) $ratingClass = 'bg-warning text-dark';

ob_start();
?>

<!-- Schema.org Product/Review structured data -->
<script type="application/ld+json">
<?= json_encode($schemaData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?>
</script>

<?php if (!empty($breadcrumbs)): ?>
    <?php include __DIR__ . '/../partials/breadcrumbs.php'; ?>
<?php endif; ?>

<!-- Review Hero -->
<section class="bg-dark text-white py-5">
    <div class="container-xl">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <?php if ($isTopPick): ?>
                <span class="badge bg-warning text-dark mb-2"><i class="fas fa-award me-1"></i> Editor's Choice</span>
                <?php endif; ?>

                <h1 class="fw-bold mb-2"><?= htmlspecialchars($review->name) ?> Review</h1>

                <?php if ($review->brand): ?>
                <p class="text-white-50 mb-3">by <?= htmlspecialchars($review->brand) ?></p>
                <?php endif; ?>

                <?php if ($review->rating_overall): ?>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <span class="display-6 fw-bold"><?= number_format($rating, 1) ?></span>
                    <div>
                        <div class="text-warning">
                            <?php for ($i = 0; $i < $fullStars; $i++): ?><i class="fas fa-star"></i><?php endfor; ?>
                            <?php if ($hasHalf): ?><i class="fas fa-star-half-alt"></i><?php endif; ?>
                            <?php for ($i = 0; $i < $emptyStars; $i++): ?><i class="far fa-star"></i><?php endfor; ?>
                        </div>
                        <span class="text-white-50 small">Overall Rating</span>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($review->affiliate_url)): ?>
                <a href="<?= htmlspecialchars($review->affiliate_url) ?>" target="_blank" rel="nofollow sponsored" class="btn btn-success btn-lg fw-bold">
                    <?= htmlspecialchars($review->cta_text ?? 'Check Best Price') ?> <i class="fas fa-external-link-alt ms-1"></i>
                </a>
                <?php endif; ?>
            </div>

            <?php if (!empty($review->featured_image)): ?>
            <div class="col-lg-5 d-none d-lg-block text-center">
                <img src="<?= IMAGE_BASE_URL . htmlspecialchars($review->featured_image) ?>" alt="<?= htmlspecialchars($review->name) ?>" class="img-fluid rounded shadow" style="max-height:300px;object-fit:contain;">
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<div class="container-xl py-4">
    <div class="row">
        <!-- LEFT COLUMN - Product Card (Sticky) -->
        <div class="col-lg-3">
            <div class="sticky-lg-top" style="top:80px;">
                <div class="card border-0 shadow-sm mb-3">
                    <?php if ($isTopPick): ?>
                    <div class="bg-warning text-dark text-center small fw-bold py-1">
                        <i class="fas fa-award me-1"></i> Editor's Choice
                    </div>
                    <?php endif; ?>

                    <!-- Product Image -->
                    <?php if (!empty($review->featured_image)): ?>
                    <img src="<?= IMAGE_BASE_URL . htmlspecialchars($review->featured_image) ?>" alt="<?= htmlspecialchars($review->name) ?>" class="card-img-top" style="height:180px;object-fit:contain;padding:1rem;background:#f8f9fa;">
                    <?php else: ?>
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height:180px;">
                        <i class="fas fa-box fa-3x text-muted"></i>
                    </div>
                    <?php endif; ?>

                    <div class="card-body">
                        <h2 class="h6 fw-bold mb-1"><?= htmlspecialchars($review->name) ?></h2>
                        <?php if ($review->brand): ?>
                        <div class="text-muted small mb-2">by <?= htmlspecialchars($review->brand) ?></div>
                        <?php endif; ?>

                        <!-- Overall Rating -->
                        <?php if ($review->rating_overall): ?>
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="badge <?= $ratingClass ?> fs-5"><?= number_format($rating, 1) ?></span>
                            <div>
                                <div class="text-warning small">
                                    <?php for ($i = 0; $i < $fullStars; $i++): ?><i class="fas fa-star"></i><?php endfor; ?>
                                    <?php if ($hasHalf): ?><i class="fas fa-star-half-alt"></i><?php endif; ?>
                                    <?php for ($i = 0; $i < $emptyStars; $i++): ?><i class="far fa-star"></i><?php endfor; ?>
                                </div>
                                <span class="text-muted" style="font-size:.7rem;">Overall Score</span>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Sub-Ratings -->
                        <?php
                        $subRatings = [];
                        if ($review->rating_ingredients) $subRatings['Ingredients'] = floatval($review->rating_ingredients);
                        if ($review->rating_value) $subRatings['Value'] = floatval($review->rating_value);
                        if ($review->rating_effectiveness) $subRatings['Effectiveness'] = floatval($review->rating_effectiveness);
                        if ($review->rating_customer_experience) $subRatings['Experience'] = floatval($review->rating_customer_experience);
                        ?>
                        <?php if (!empty($subRatings)): ?>
                        <div class="mb-3">
                            <?php foreach ($subRatings as $label => $val): ?>
                            <div class="d-flex align-items-center mb-1 small">
                                <span class="text-muted me-2" style="min-width:80px;font-size:.75rem;"><?= $label ?></span>
                                <div class="progress flex-grow-1" style="height:5px;">
                                    <div class="progress-bar bg-success" style="width:<?= ($val / 5) * 100 ?>%"></div>
                                </div>
                                <span class="text-muted ms-2" style="font-size:.75rem;"><?= number_format($val, 1) ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>

                        <!-- Price -->
                        <?php if ($review->price): ?>
                        <div class="border-top pt-2 mb-2">
                            <div class="text-muted small">Starting at</div>
                            <div class="h5 fw-bold text-dark mb-0"><?= htmlspecialchars($review->price) ?></div>
                            <?php if ($review->price_note): ?>
                            <div class="text-muted small"><?= htmlspecialchars($review->price_note) ?></div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <!-- CTAs -->
                        <div class="d-grid gap-2 mt-3">
                            <?php if (!empty($review->affiliate_url)): ?>
                            <a href="<?= htmlspecialchars($review->affiliate_url) ?>" target="_blank" rel="nofollow sponsored" class="btn btn-success fw-bold">
                                <?= htmlspecialchars($review->cta_text ?? 'Check Price') ?> <i class="fas fa-external-link-alt ms-1"></i>
                            </a>
                            <?php endif; ?>
                            <a href="#review-content" class="btn btn-outline-secondary btn-sm">
                                Read Full Review <i class="fas fa-chevron-down ms-1"></i>
                            </a>
                        </div>

                        <!-- Trust Signals -->
                        <div class="border-top mt-3 pt-2 text-muted small">
                            <div class="mb-1"><i class="fas fa-shield-alt text-success me-1"></i> Verified Review</div>
                            <div class="mb-1"><i class="fas fa-undo text-success me-1"></i> Money-Back Guarantee</div>
                            <div><i class="fas fa-truck text-success me-1"></i> Fast Shipping</div>
                        </div>

                        <!-- Why We Recommend -->
                        <?php if ($review->short_description): ?>
                        <div class="border-top mt-3 pt-2">
                            <div class="small fw-bold mb-1"><i class="fas fa-lightbulb text-warning me-1"></i> Why We Recommend</div>
                            <p class="text-muted small mb-0"><?= htmlspecialchars($review->short_description) ?></p>
                        </div>
                        <?php endif; ?>

                        <!-- Quick Pros -->
                        <?php if (!empty($review->pros) && count($review->pros) > 0): ?>
                        <div class="border-top mt-3 pt-2">
                            <div class="small fw-bold mb-1">Key Benefits</div>
                            <ul class="list-unstyled small text-muted mb-0">
                                <?php foreach (array_slice($review->pros, 0, 3) as $pro): ?>
                                <li class="mb-1"><i class="fas fa-check text-success me-1"></i> <?= htmlspecialchars($pro) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN - Review Content -->
        <div class="col-lg-9" id="review-content">
            <!-- Author Meta -->
            <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom">
                <i class="fas fa-user-circle fa-2x text-muted"></i>
                <div>
                    <span class="fw-bold small">by <?= htmlspecialchars($site->name) ?> Team</span>
                    <?php if ($review->published_at): ?>
                    <br><span class="text-muted small">Updated <?= date('F j, Y', strtotime($review->published_at)) ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Pros & Cons -->
            <?php if (!empty($review->pros) || !empty($review->cons)): ?>
            <div class="row g-3 mb-4">
                <?php if (!empty($review->pros)): ?>
                <div class="col-md-6">
                    <div class="card border-0 h-100" style="background-color: #d4edda;">
                        <div class="card-body">
                            <h3 class="h6 fw-bold text-success mb-3"><i class="fas fa-thumbs-up me-1"></i> Pros</h3>
                            <ul class="list-unstyled mb-0 small">
                                <?php foreach ($review->pros as $pro): ?>
                                <li class="mb-2"><i class="fas fa-check text-success me-1"></i> <?= htmlspecialchars($pro) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <?php if (!empty($review->cons)): ?>
                <div class="col-md-6">
                    <div class="card border-0 h-100" style="background-color: #f8d7da;">
                        <div class="card-body">
                            <h3 class="h6 fw-bold text-danger mb-3"><i class="fas fa-thumbs-down me-1"></i> Cons</h3>
                            <ul class="list-unstyled mb-0 small">
                                <?php foreach ($review->cons as $con): ?>
                                <li class="mb-2"><i class="fas fa-times text-danger me-1"></i> <?= htmlspecialchars($con) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- Main Content -->
            <div class="article-content mb-4">
                <?= $review->content ?>
            </div>

            <!-- Product Details -->
            <?php if (!empty($reviewCategories) || $review->brand): ?>
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-bold"><i class="fas fa-info-circle text-success me-2"></i><?= htmlspecialchars($review->name) ?> Details</div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <?php if ($review->brand): ?>
                        <tr>
                            <td class="fw-bold small" style="width:30%">Brand</td>
                            <td class="small"><?= htmlspecialchars($review->brand) ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($review->price): ?>
                        <tr>
                            <td class="fw-bold small">Price</td>
                            <td class="small"><?= htmlspecialchars($review->price) ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($review->rating_overall): ?>
                        <tr>
                            <td class="fw-bold small">Rating</td>
                            <td class="small"><?= number_format($rating, 1) ?>/5</td>
                        </tr>
                        <?php endif; ?>
                        <?php if (!empty($reviewCategories)): ?>
                        <tr>
                            <td class="fw-bold small">Category</td>
                            <td class="small">
                                <?php foreach ($reviewCategories as $cat): ?>
                                <a href="<?= BASE_URL ?>/category/<?= htmlspecialchars($cat->slug) ?>" class="text-success"><?= htmlspecialchars($cat->name) ?></a>
                                <?php endforeach; ?>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
            <?php endif; ?>

            <!-- Final Verdict -->
            <div class="card border-success border-2 shadow mb-4">
                <div class="card-header bg-success text-white fw-bold">
                    <i class="fas fa-gavel me-2"></i> Final Verdict
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto text-center">
                            <?php if (!empty($review->featured_image)): ?>
                            <img src="<?= IMAGE_BASE_URL . htmlspecialchars($review->featured_image) ?>" alt="<?= htmlspecialchars($review->name) ?>" class="rounded mb-2" style="width:80px;height:80px;object-fit:contain;">
                            <?php endif; ?>
                            <div class="h3 fw-bold text-success mb-0"><?= number_format($rating, 1) ?></div>
                            <div class="text-warning small">
                                <?php for ($i = 0; $i < $fullStars; $i++): ?><i class="fas fa-star"></i><?php endfor; ?>
                                <?php if ($hasHalf): ?><i class="fas fa-star-half-alt"></i><?php endif; ?>
                                <?php for ($i = 0; $i < $emptyStars; $i++): ?><i class="far fa-star"></i><?php endfor; ?>
                            </div>
                            <?php if ($isTopPick): ?>
                            <span class="badge bg-warning text-dark mt-1 small">Editor's Choice</span>
                            <?php endif; ?>
                        </div>
                        <div class="col">
                            <h4 class="fw-bold mb-2"><?= htmlspecialchars($review->name) ?></h4>
                            <?php if ($review->short_description): ?>
                            <p class="text-muted mb-2"><?= htmlspecialchars($review->short_description) ?></p>
                            <?php else: ?>
                            <p class="text-muted mb-2">Based on our comprehensive analysis, <?= htmlspecialchars($review->name) ?> <?= $rating >= 4.0 ? 'is a solid choice' : 'has room for improvement' ?> in its category.</p>
                            <?php endif; ?>

                            <?php if (!empty($review->pros)): ?>
                            <ul class="list-unstyled small mb-0">
                                <?php foreach (array_slice($review->pros, 0, 3) as $pro): ?>
                                <li class="mb-1"><i class="fas fa-check-circle text-success me-1"></i> <?= htmlspecialchars($pro) ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-top text-center">
                        <?php if (!empty($review->affiliate_url)): ?>
                        <a href="<?= htmlspecialchars($review->affiliate_url) ?>" target="_blank" rel="nofollow sponsored" class="btn btn-success btn-lg fw-bold px-5">
                            <?= htmlspecialchars($review->cta_text ?? 'Check Best Price') ?> <i class="fas fa-external-link-alt ms-1"></i>
                        </a>
                        <?php endif; ?>
                        <div class="d-flex justify-content-center gap-3 mt-2 text-muted small">
                            <span><i class="fas fa-shield-alt me-1"></i> Verified Review</span>
                            <span><i class="fas fa-lock me-1"></i> Secure Purchase</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Related Reviews -->
<?php if (!empty($relatedReviews)): ?>
<section class="bg-light py-5">
    <div class="container-xl">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 fw-bold mb-0">Related Reviews</h2>
            <?php if ($primaryCategory): ?>
            <a href="<?= BASE_URL ?>/category/<?= htmlspecialchars($primaryCategory->slug) ?>" class="text-success text-decoration-none small">View All <?= htmlspecialchars($primaryCategory->name) ?> Reviews <i class="fas fa-arrow-right"></i></a>
            <?php endif; ?>
        </div>
        <div class="row g-4">
            <?php foreach ($relatedReviews as $relReview):
                $relUrl = BASE_URL . ($primaryCategory
                    ? '/category/' . htmlspecialchars($primaryCategory->slug) . '/reviews/' . htmlspecialchars($relReview->slug)
                    : '/reviews/' . htmlspecialchars($relReview->slug));
            ?>
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm overflow-hidden review-card">
                    <?php if (!empty($relReview->featured_image)): ?>
                    <a href="<?= $relUrl ?>">
                        <img src="<?= IMAGE_BASE_URL . htmlspecialchars($relReview->featured_image) ?>" class="card-img-top" alt="<?= htmlspecialchars($relReview->name) ?>">
                    </a>
                    <?php endif; ?>
                    <div class="card-body">
                        <h3 class="h6 fw-bold">
                            <a href="<?= $relUrl ?>" class="text-dark text-decoration-none"><?= htmlspecialchars($relReview->name) ?></a>
                        </h3>
                        <?php if ($relReview->rating_overall): ?>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-success"><?= number_format(floatval($relReview->rating_overall), 1) ?></span>
                            <span class="text-warning small">
                                <?php $rs = floor(floatval($relReview->rating_overall)); for ($i = 0; $i < $rs; $i++): ?><i class="fas fa-star"></i><?php endfor; ?>
                            </span>
                        </div>
                        <?php endif; ?>
                        <a href="<?= $relUrl ?>" class="btn btn-outline-success btn-sm w-100">Read Review</a>
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
<div class="fixed-bottom bg-dark text-white d-lg-none border-top border-secondary mobile-cta-bar" style="z-index:1040;">
    <div class="container-xl py-2 d-flex justify-content-between align-items-center">
        <div>
            <span class="small fw-bold"><?= htmlspecialchars($review->name) ?></span>
            <?php if ($review->rating_overall): ?>
            <span class="badge bg-success ms-1"><?= number_format($rating, 1) ?> <i class="fas fa-star"></i></span>
            <?php endif; ?>
        </div>
        <a href="<?= htmlspecialchars($review->affiliate_url) ?>" target="_blank" rel="nofollow sponsored" class="btn btn-success btn-sm fw-bold">
            <?= htmlspecialchars($review->cta_text ?? 'Check Price') ?>
        </a>
    </div>
</div>
<?php endif; ?>

<?php
$__content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
