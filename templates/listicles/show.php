<?php
$pageTitle = ($listicle->meta_title ?? $listicle->title) . ' | ' . $site->name;
$metaDescription = $listicle->meta_description ?? ($listicle->excerpt ?? '');
ob_start();
?>

<!-- Listicle Header -->
<section class="cr-listicle-header">
    <div class="container">
        <h1><?= htmlspecialchars($listicle->title) ?></h1>
        <?php if (!empty($listicle->excerpt)): ?>
        <p class="lead"><?= htmlspecialchars($listicle->excerpt) ?></p>
        <?php endif; ?>
        <?php if ($listicle->published_at): ?>
        <span class="updated-date">Updated <?= date('F Y', strtotime($listicle->published_at)) ?></span>
        <?php endif; ?>
    </div>
</section>

<div class="container py-4">
    <div class="row">
        <!-- LEFT COLUMN - Product Cards (col-lg-9) -->
        <div class="col-lg-9 col-md-12">
            <!-- Introduction -->
            <?php if (!empty($listicle->introduction)): ?>
            <div class="cr-listicle-intro">
                <?= $listicle->introduction ?>
            </div>
            <?php endif; ?>

            <!-- Product Cards -->
            <?php if (!empty($listicle->items)): ?>
            <div class="cr-listicle-items">
                <?php foreach ($listicle->items as $index => $item): ?>
                <?php $rank = $item['rank'] ?? ($index + 1); ?>
                <div class="cr-item-card">
                    <!-- Ordinal Badge -->
                    <div class="cr-ordinal"><?= $rank ?></div>

                    <!-- Banner Badge -->
                    <?php if (!empty($item['badge'])): ?>
                    <div class="cr-badge-banner">
                        <span><?= htmlspecialchars($item['badge']) ?></span>
                    </div>
                    <?php endif; ?>

                    <!-- Card Content Row -->
                    <div class="cr-item-row">
                        <!-- Left: Brand Logo -->
                        <div class="cr-logo-col">
                            <?php if (!empty($item['brand_logo'])): ?>
                            <img src="<?= htmlspecialchars($item['brand_logo']) ?>" alt="<?= htmlspecialchars($item['name'] ?? '') ?>" class="cr-brand-logo">
                            <?php elseif (!empty($item['image'])): ?>
                            <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name'] ?? '') ?>" class="cr-brand-logo">
                            <?php else: ?>
                            <div class="cr-brand-logo cr-logo-placeholder">
                                <i class="fas fa-box"></i>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Center: Rating + Name + Features -->
                        <div class="cr-content-col">
                            <!-- Rating Section -->
                            <?php if (!empty($item['rating'])): ?>
                            <div class="cr-rating-row">
                                <span class="cr-rating-num"><?= number_format($item['rating'], 1) ?></span>
                                <span class="cr-rating-stars">
                                    <?php
                                    $rating = floatval($item['rating']);
                                    $fullStars = round($rating / 2);
                                    for ($i = 1; $i <= 5; $i++):
                                    ?>
                                    <i class="fas fa-star<?= $i <= $fullStars ? ' filled' : '' ?>"></i>
                                    <?php endfor; ?>
                                </span>
                            </div>
                            <?php endif; ?>

                            <!-- Product Name -->
                            <div class="cr-product-details">
                                <strong><?= htmlspecialchars($item['name'] ?? '') ?></strong>

                                <!-- Feature Bullets -->
                                <?php if (!empty($item['features']) && is_array($item['features'])): ?>
                                <ul>
                                    <?php foreach ($item['features'] as $feature): ?>
                                    <li><?= htmlspecialchars($feature) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php endif; ?>
                            </div>

                            <!-- Read Full Review Link -->
                            <?php if (!empty($item['affiliate_url'])): ?>
                            <div class="cr-review-link">
                                <a href="<?= htmlspecialchars($item['affiliate_url']) ?>" target="_blank" rel="nofollow sponsored">Read Full Review</a>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Right: CTA Button -->
                        <div class="cr-cta-col">
                            <?php if (!empty($item['affiliate_url'])): ?>
                            <a href="<?= htmlspecialchars($item['affiliate_url']) ?>" target="_blank" rel="nofollow sponsored" class="cr-cta-button">
                                <?= htmlspecialchars($item['cta_text'] ?? 'Check Price') ?>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Conclusion -->
            <?php if (!empty($listicle->conclusion)): ?>
            <div class="cr-listicle-conclusion">
                <h2>Final Thoughts</h2>
                <?= $listicle->conclusion ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- RIGHT COLUMN - Sidebar (col-lg-3) -->
        <div class="col-lg-3 col-md-12">
            <div class="cr-sidebar">
                <?php require __DIR__ . '/../partials/listicle-sidebar.php'; ?>
            </div>
        </div>
    </div>
</div>

<?php
$__content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
