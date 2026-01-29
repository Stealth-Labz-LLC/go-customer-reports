<?php
/**
 * Listicle Sidebar Widgets — Bootstrap 5.3.3
 * Expects: $listicle, $site, $primaryCategory (optional)
 */
$topItems = array_slice($listicle->items ?? [], 0, 3);
$categories = \App\Models\Category::topLevel($site->id);
?>

<!-- Browse Widget -->
<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-dark text-white fw-bold small">Browse</div>
    <div class="list-group list-group-flush">
        <a href="<?= BASE_URL ?>/" class="list-group-item list-group-item-action small">Home</a>
        <a href="<?= BASE_URL ?>/categories" class="list-group-item list-group-item-action small">All Categories</a>
        <?php if (!empty($primaryCategory)): ?>
        <a href="<?= BASE_URL ?>/category/<?= htmlspecialchars($primaryCategory->slug) ?>" class="list-group-item list-group-item-action small fw-bold"><?= htmlspecialchars($primaryCategory->name) ?></a>
        <?php endif; ?>
    </div>
</div>

<!-- Related Categories -->
<?php if (!empty($categories)): ?>
<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-dark text-white fw-bold small">Related Categories</div>
    <div class="list-group list-group-flush">
        <?php foreach (array_slice($categories, 0, 5) as $cat): ?>
        <a href="<?= BASE_URL ?>/category/<?= htmlspecialchars($cat->slug) ?>" class="list-group-item list-group-item-action small"><?= htmlspecialchars($cat->name) ?></a>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Compare Top Picks -->
<?php if (count($topItems) >= 2): ?>
<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-dark text-white fw-bold small"><i class="fas fa-balance-scale me-1"></i> Compare Top Picks</div>
    <div class="card-body text-center small">
        <div class="mb-2">
            <strong><?= htmlspecialchars($topItems[0]['name'] ?? 'Product 1') ?></strong>
            <div class="text-muted my-1">vs.</div>
            <strong><?= htmlspecialchars($topItems[1]['name'] ?? 'Product 2') ?></strong>
        </div>
        <?php if (isset($topItems[2])): ?>
        <hr>
        <div>
            <strong><?= htmlspecialchars($topItems[0]['name'] ?? 'Product 1') ?></strong>
            <div class="text-muted my-1">vs.</div>
            <strong><?= htmlspecialchars($topItems[2]['name'] ?? 'Product 3') ?></strong>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<!-- Recommended Reviews -->
<?php if (!empty($topItems)): ?>
<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-dark text-white fw-bold small"><i class="fas fa-star me-1"></i> Recommended</div>
    <div class="list-group list-group-flush">
        <?php foreach ($topItems as $item): ?>
        <?php if (!empty($item['affiliate_url'])): ?>
        <a href="<?= htmlspecialchars($item['affiliate_url']) ?>" target="_blank" rel="nofollow sponsored" class="list-group-item list-group-item-action d-flex align-items-center gap-2 small">
            <?php if (!empty($item['brand_logo'])): ?>
            <img src="<?= IMAGE_BASE_URL . htmlspecialchars($item['brand_logo']) ?>" alt="" class="listicle-sidebar-thumb">
            <?php elseif (!empty($item['image'])): ?>
            <img src="<?= IMAGE_BASE_URL . htmlspecialchars($item['image']) ?>" alt="" class="listicle-sidebar-thumb">
            <?php endif; ?>
            <span><?= htmlspecialchars($item['name'] ?? '') ?></span>
        </a>
        <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- How We Rank -->
<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-dark text-white fw-bold small"><i class="fas fa-chart-line me-1"></i> How We Rank</div>
    <div class="card-body small text-muted">
        <p class="mb-2"><strong class="text-dark">Expert Analysis</strong><br>Our team highlights useful information so you can easily compare products.</p>
        <p class="mb-0"><strong class="text-dark">Data-Driven Tech</strong><br>Our technology analyzes thousands of purchase trends to bring you top recommendations.</p>
    </div>
</div>

<!-- Research Stats -->
<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-dark text-white fw-bold small"><i class="fas fa-microscope me-1"></i> Research Stats</div>
    <div class="card-body text-center">
        <div class="row g-2">
            <div class="col-6">
                <div class="h5 fw-bold text-success mb-0"><?= count($listicle->items ?? []) ?>+</div>
                <div class="text-muted listicle-stat-label">Models Evaluated</div>
            </div>
            <div class="col-6">
                <div class="h5 fw-bold text-success mb-0">5</div>
                <div class="text-muted listicle-stat-label">Topics Considered</div>
            </div>
            <div class="col-6">
                <div class="h5 fw-bold text-success mb-0">15+</div>
                <div class="text-muted listicle-stat-label">Hours Research</div>
            </div>
        </div>
    </div>
</div>

<!-- Trust Widget -->
<div class="card border-0 bg-success text-white mb-3">
    <div class="card-body text-center small">
        <i class="fas fa-shield-alt fa-2x mb-2 d-block"></i>
        <strong>Reliable, Safe & Secure</strong>
        <p class="mb-2 mt-1 small opacity-75">Helping users make smarter purchases online.</p>
        <div class="d-flex justify-content-center gap-3">
            <i class="fas fa-lock fa-lg"></i>
            <i class="fas fa-check-circle fa-lg"></i>
            <i class="fas fa-certificate fa-lg"></i>
        </div>
    </div>
</div>
