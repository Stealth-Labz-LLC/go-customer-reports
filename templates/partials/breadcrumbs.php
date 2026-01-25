<?php
/**
 * Breadcrumb Navigation Component
 *
 * Renders breadcrumbs with Schema.org BreadcrumbList structured data.
 *
 * Usage: include with $breadcrumbs array
 * Each item: ['label' => 'Name', 'url' => '/path' or null for current page]
 */

if (empty($breadcrumbs)) return;
?>

<div class="cr-breadcrumbs">
    <div class="container">
        <nav aria-label="Breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
            <?php foreach ($breadcrumbs as $index => $crumb): ?>
                <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <?php if ($crumb['url'] !== null): ?>
                        <a href="<?= htmlspecialchars($crumb['url']) ?>" itemprop="item">
                            <span itemprop="name"><?= htmlspecialchars($crumb['label']) ?></span>
                        </a>
                        <meta itemprop="position" content="<?= $index + 1 ?>">
                        <span class="separator"> / </span>
                    <?php else: ?>
                        <span class="current" itemprop="name"><?= htmlspecialchars($crumb['label']) ?></span>
                        <meta itemprop="position" content="<?= $index + 1 ?>">
                    <?php endif; ?>
                </span>
            <?php endforeach; ?>
        </nav>
    </div>
</div>
