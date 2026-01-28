<?php
/**
 * Listicle Sidebar Widgets
 * Matches the water-filtration-system sidebar structure
 */

// Get top 3 items for comparison/recommendations
$topItems = array_slice($listicle->items ?? [], 0, 3);
?>

<!-- Navigation Widget -->
<div class="cr-sidebar-widget">
    <div class="widget-title"><strong>Browse</strong></div>
    <hr class="widget-break">
    <div class="widget-content">
        <a href="<?= BASE_URL ?>/" class="widget-link">Home</a>
        <a href="<?= BASE_URL ?>/categories" class="widget-link">All Categories</a>
        <?php if (!empty($primaryCategory)): ?>
        <a href="<?= BASE_URL ?>/category/<?= htmlspecialchars($primaryCategory->slug) ?>" class="widget-link"><?= htmlspecialchars($primaryCategory->name) ?></a>
        <?php endif; ?>
    </div>
</div>

<!-- Related Categories Widget -->
<?php
$categories = \App\Models\Category::topLevel($site->id);
if (!empty($categories)):
?>
<div class="cr-sidebar-widget">
    <div class="widget-title"><strong>Related Categories</strong></div>
    <hr class="widget-break">
    <div class="widget-content">
        <?php foreach (array_slice($categories, 0, 5) as $cat): ?>
        <a href="<?= BASE_URL ?>/category/<?= htmlspecialchars($cat->slug) ?>" class="widget-link"><?= htmlspecialchars($cat->name) ?></a>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Compare Top Picks Widget -->
<?php if (count($topItems) >= 2): ?>
<div class="cr-sidebar-widget">
    <div class="widget-title"><strong>Compare Our Top Picks</strong></div>
    <hr class="widget-break">
    <div class="widget-content">
        <?php if (isset($topItems[0], $topItems[1])): ?>
        <div class="compare-item">
            <?= htmlspecialchars($topItems[0]['name'] ?? 'Product 1') ?>
            <br><span class="vs-text">vs.</span><br>
            <?= htmlspecialchars($topItems[1]['name'] ?? 'Product 2') ?>
        </div>
        <?php endif; ?>
        <?php if (isset($topItems[0], $topItems[2])): ?>
        <hr class="widget-break">
        <div class="compare-item">
            <?= htmlspecialchars($topItems[0]['name'] ?? 'Product 1') ?>
            <br><span class="vs-text">vs.</span><br>
            <?= htmlspecialchars($topItems[2]['name'] ?? 'Product 3') ?>
        </div>
        <?php endif; ?>
        <div class="widget-icon">
            <i class="fas fa-balance-scale"></i>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Recommended Reviews Widget -->
<?php if (!empty($topItems)): ?>
<div class="cr-sidebar-widget">
    <div class="widget-title"><strong>Recommended Reviews</strong></div>
    <hr class="widget-break">
    <div class="widget-content">
        <?php foreach ($topItems as $item): ?>
        <?php if (!empty($item['affiliate_url'])): ?>
        <a href="<?= htmlspecialchars($item['affiliate_url']) ?>" class="widget-review-link" target="_blank" rel="nofollow sponsored">
            <?php if (!empty($item['brand_logo'])): ?>
            <img src="<?= IMAGE_BASE_URL . htmlspecialchars($item['brand_logo']) ?>" alt="<?= htmlspecialchars($item['name'] ?? '') ?>" class="widget-logo">
            <?php elseif (!empty($item['image'])): ?>
            <img src="<?= IMAGE_BASE_URL . htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name'] ?? '') ?>" class="widget-logo">
            <?php endif; ?>
            <span><?= htmlspecialchars($item['name'] ?? '') ?> Review</span>
        </a>
        <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- How We Rank Widget -->
<div class="cr-sidebar-widget">
    <div class="widget-title"><strong>How We Rank</strong></div>
    <hr class="widget-break">
    <div class="widget-content">
        <strong>Expert Analysis</strong><br>
        Our team of experts highlights useful information so you can easily compare products to find the one that's right for you.
        <br><br>
        <strong>Data-Driven Tech</strong><br>
        Our technology analyzes thousands of purchase trends to bring you the top product recommendations.
        <div class="widget-icon">
            <i class="fas fa-chart-line"></i>
        </div>
    </div>
</div>

<!-- Why Trust Our Reviews Widget -->
<div class="cr-sidebar-widget">
    <div class="widget-title"><strong>Why Trust Our Reviews?</strong></div>
    <hr class="widget-break">
    <div class="widget-content">
        Thousands of shoppers have used <?= htmlspecialchars($site->name) ?> to help find the best products.
        <br><br>
        We provide unbiased, data-driven recommendations.
        <div class="widget-icon">
            <i class="fas fa-shield-alt"></i>
        </div>
    </div>
</div>

<!-- Research You Can Count On Widget -->
<div class="cr-sidebar-widget">
    <div class="widget-title"><strong>Research You Can Count On</strong></div>
    <hr class="widget-break">
    <div class="widget-content">
        <div class="research-stats">
            <div class="stat-item">
                <span class="stat-number"><?= count($listicle->items ?? []) ?>+</span>
                <span class="stat-label">Models Evaluated</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">5</span>
                <span class="stat-label">Topics Considered</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">15+</span>
                <span class="stat-label">Hours of Research</span>
            </div>
        </div>
        <div class="widget-icon">
            <i class="fas fa-microscope"></i>
        </div>
    </div>
</div>

<!-- Reliable, Safe & Secure Widget -->
<div class="cr-sidebar-widget">
    <div class="widget-title"><strong>Reliable, Safe &amp; Secure</strong></div>
    <hr class="widget-break">
    <div class="widget-content">
        Helping users make smarter purchases online.
        <div class="trust-badges">
            <i class="fas fa-lock"></i>
            <i class="fas fa-check-circle"></i>
            <i class="fas fa-certificate"></i>
        </div>
    </div>
</div>
