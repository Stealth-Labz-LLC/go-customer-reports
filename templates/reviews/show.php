<?php
$pageTitle = ($review->meta_title ?? $review->name . ' Review') . ' | ' . $site->name;
$metaDescription = $review->meta_description ?? ($review->short_description ?? '');
ob_start();
?>

<!-- Breadcrumbs -->
<div class="cr-breadcrumbs">
    <div class="container">
        <span><a href="/">Home</a> &raquo; <a href="/reviews">Reviews</a> &raquo; <span class="current"><?= htmlspecialchars($review->name) ?></span></span>
    </div>
</div>

<!-- Review Layout: Sticky Sidebar Left + Content Right -->
<div class="cr-review-page">
    <div class="container py-4">
        <div class="row">
            <!-- LEFT COLUMN - Product Card (Sticky) -->
            <div class="col-lg-3 col-md-12">
                <div class="cr-review-product-card">
                    <!-- Product Image -->
                    <div class="cr-review-product-image">
                        <?php if (!empty($review->featured_image)): ?>
                        <img src="<?= htmlspecialchars($review->featured_image) ?>" alt="<?= htmlspecialchars($review->name) ?>">
                        <?php else: ?>
                        <div class="cr-review-image-placeholder">
                            <i class="fas fa-box"></i>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Star Rating -->
                    <?php if ($review->rating_overall): ?>
                    <div class="cr-review-product-rating">
                        <?php
                        $rating = floatval($review->rating_overall);
                        $fullStars = floor($rating);
                        $hasHalf = ($rating - $fullStars) >= 0.5;
                        ?>
                        <div class="cr-review-stars">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star<?= $i <= $fullStars ? ' filled' : ($i == $fullStars + 1 && $hasHalf ? '-half-alt filled' : '') ?>"></i>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Product Title -->
                    <h1 class="cr-review-product-title"><?= htmlspecialchars($review->name) ?></h1>

                    <!-- Brand -->
                    <?php if ($review->brand): ?>
                    <div class="cr-review-product-brand">by <?= htmlspecialchars($review->brand) ?></div>
                    <?php endif; ?>

                    <!-- Short Description -->
                    <?php if ($review->short_description): ?>
                    <div class="cr-review-product-desc">
                        <p><?= htmlspecialchars($review->short_description) ?></p>
                    </div>
                    <?php endif; ?>

                    <!-- Price & CTA -->
                    <div class="cr-review-product-cta">
                        <?php if ($review->price): ?>
                        <div class="cr-review-price-info">
                            <p class="cr-review-price"><?= htmlspecialchars($review->price) ?></p>
                            <?php if ($review->price_note): ?>
                            <p class="cr-review-price-note"><?= htmlspecialchars($review->price_note) ?></p>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <?php if ($review->affiliate_url): ?>
                        <a href="<?= htmlspecialchars($review->affiliate_url) ?>" target="_blank" rel="nofollow sponsored" class="cr-review-cta-btn">
                            <?= htmlspecialchars($review->cta_text ?? 'Check Availability') ?> <i class="fas fa-arrow-circle-right"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN - Review Content (Scrolls) -->
            <div class="col-lg-9 col-md-12">
                <div class="cr-review-content-wrap">
                    <!-- Author Meta -->
                    <div class="cr-review-author-meta">
                        <div class="cr-review-author-avatar">
                            <img src="/images/avatar-default.png" alt="Reviewer" onerror="this.style.display='none'">
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
                            <div class="cr-pros-title">Pros</div>
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
                            <div class="cr-cons-title">Cons</div>
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
                                <a href="/category/<?= htmlspecialchars($cat->slug) ?>"><?= htmlspecialchars($cat->name) ?></a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Rating Breakdown -->
                    <?php if ($review->rating_overall): ?>
                    <div class="cr-review-rating-breakdown">
                        <div class="cr-rating-breakdown-logo">
                            <div class="cr-rating-breakdown-logo-img">
                                <?php if (!empty($review->featured_image)): ?>
                                <img src="<?= htmlspecialchars($review->featured_image) ?>" alt="<?= htmlspecialchars($review->name) ?>">
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="cr-rating-breakdown-content">
                            <div class="cr-rating-overall">
                                <label>Overall Rating</label>
                                <div class="cr-rating-overall-stars">
                                    <?php
                                    $rating = floatval($review->rating_overall);
                                    $fullStars = floor($rating);
                                    ?>
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star<?= $i <= $fullStars ? ' filled' : '' ?>"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>

                            <div class="cr-rating-items">
                                <?php if ($review->rating_quality): ?>
                                <div class="cr-rating-item">
                                    <label>Quality</label>
                                    <div class="cr-rating-item-stars">
                                        <?php $r = floor(floatval($review->rating_quality)); ?>
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star<?= $i <= $r ? ' filled' : '' ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <?php if ($review->rating_value): ?>
                                <div class="cr-rating-item">
                                    <label>Value</label>
                                    <div class="cr-rating-item-stars">
                                        <?php $r = floor(floatval($review->rating_value)); ?>
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star<?= $i <= $r ? ' filled' : '' ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <?php if ($review->rating_effectiveness): ?>
                                <div class="cr-rating-item">
                                    <label>Effectiveness</label>
                                    <div class="cr-rating-item-stars">
                                        <?php $r = floor(floatval($review->rating_effectiveness)); ?>
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star<?= $i <= $r ? ' filled' : '' ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <?php if ($review->rating_experience): ?>
                                <div class="cr-rating-item">
                                    <label>Customer Experience</label>
                                    <div class="cr-rating-item-stars">
                                        <?php $r = floor(floatval($review->rating_experience)); ?>
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star<?= $i <= $r ? ' filled' : '' ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$__content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
