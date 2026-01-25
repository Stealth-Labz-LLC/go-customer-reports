<?php
$pageTitle = ($review->meta_title ?? $review->name . ' Review') . ' | ' . $site->name;
$metaDescription = $review->meta_description ?? ($review->short_description ?? '');
ob_start();
?>

<?php if (!empty($breadcrumbs)): ?>
    <?php include __DIR__ . '/../partials/breadcrumbs.php'; ?>
<?php endif; ?>

<!-- Review Layout: Sticky Sidebar Left + Content Right -->
<div class="cr-review-page">
    <div class="container py-4">
        <div class="row">
            <!-- LEFT COLUMN - Product Card (Sticky) -->
            <div class="col-lg-3 col-md-12">
                <div class="cr-review-product-card">
                    <?php
                    $rating = floatval($review->rating_overall ?? 0);
                    $isTopPick = $rating >= 4.5;
                    ?>

                    <!-- Editor's Choice Badge -->
                    <?php if ($isTopPick): ?>
                    <div class="cr-product-badge">
                        <i class="fas fa-award"></i> Editor's Choice
                    </div>
                    <?php endif; ?>

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

                    <!-- Product Title -->
                    <h1 class="cr-review-product-title"><?= htmlspecialchars($review->name) ?></h1>

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
                                <?php
                                $fullStars = floor($rating);
                                $hasHalf = ($rating - $fullStars) >= 0.5;
                                ?>
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
                        <?php else: ?>
                        <a href="#review-content" class="cr-cta-primary">
                            Learn More <i class="fas fa-chevron-down"></i>
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
                                <?php if ($review->rating_ingredients): ?>
                                <div class="cr-rating-item">
                                    <label>Ingredients</label>
                                    <div class="cr-rating-item-stars">
                                        <?php $r = floor(floatval($review->rating_ingredients)); ?>
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

                                <?php if ($review->rating_customer_experience): ?>
                                <div class="cr-rating-item">
                                    <label>Customer Experience</label>
                                    <div class="cr-rating-item-stars">
                                        <?php $r = floor(floatval($review->rating_customer_experience)); ?>
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
