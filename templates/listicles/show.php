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
    <div class="row justify-content-center">
        <div class="col-lg-10">

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
                <?php
                    $rank = $item['rank'] ?? ($index + 1);
                    $rankClass = $rank <= 3 ? "rank-{$rank}" : '';
                ?>
                <div class="cr-product-card">
                    <!-- Rank Badge -->
                    <div class="cr-rank-badge <?= $rankClass ?>"><?= $rank ?></div>

                    <!-- Ribbon Badge (if provided) -->
                    <?php if (!empty($item['badge'])): ?>
                    <div class="cr-ribbon <?= ($rank === 1) ? 'ribbon-gold' : '' ?>"><?= htmlspecialchars($item['badge']) ?></div>
                    <?php endif; ?>

                    <div class="cr-product-card-body">
                        <!-- Product Image -->
                        <?php if (!empty($item['image'])): ?>
                        <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name'] ?? '') ?>" class="cr-product-image">
                        <?php else: ?>
                        <div class="cr-product-image d-flex align-items-center justify-content-center">
                            <i class="fas fa-box" style="font-size: 3rem; color: var(--cr-border);"></i>
                        </div>
                        <?php endif; ?>

                        <!-- Product Info -->
                        <div class="cr-product-info">
                            <?php if (!empty($item['brand'])): ?>
                            <div class="cr-product-brand"><?= htmlspecialchars($item['brand']) ?></div>
                            <?php endif; ?>

                            <h3 class="cr-product-name"><?= htmlspecialchars($item['name'] ?? '') ?></h3>

                            <!-- Rating -->
                            <?php if (!empty($item['rating'])): ?>
                            <div class="cr-rating-section">
                                <span class="cr-rating-large"><?= number_format($item['rating'], 1) ?></span>
                                <div class="cr-rating-stars">
                                    <?php
                                    $rating = floatval($item['rating']);
                                    $fullStars = floor($rating / 2); // Convert 10-scale to 5-star
                                    for ($i = 1; $i <= 5; $i++):
                                    ?>
                                    <i class="fas fa-star<?= $i <= $fullStars ? '' : ' opacity-25' ?>"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if (!empty($item['description'])): ?>
                            <p class="cr-product-description"><?= htmlspecialchars($item['description']) ?></p>
                            <?php endif; ?>

                            <!-- Features -->
                            <?php if (!empty($item['features']) && is_array($item['features'])): ?>
                            <ul class="cr-features">
                                <?php foreach (array_slice($item['features'], 0, 4) as $feature): ?>
                                <li><?= htmlspecialchars($feature) ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <?php endif; ?>
                        </div>

                        <!-- CTA Section -->
                        <div class="cr-cta-section">
                            <?php if (!empty($item['price'])): ?>
                            <div class="cr-product-price"><?= htmlspecialchars($item['price']) ?></div>
                            <?php endif; ?>

                            <?php if (!empty($item['affiliate_url'])): ?>
                            <a href="<?= htmlspecialchars($item['affiliate_url']) ?>" target="_blank" rel="nofollow sponsored" class="cr-cta-btn">
                                <?= htmlspecialchars($item['cta_text'] ?? 'View Deal') ?>
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
    </div>
</div>

<?php
$__content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
