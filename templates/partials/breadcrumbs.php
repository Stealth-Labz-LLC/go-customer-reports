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

<div class="bg-light border-bottom py-2">
    <div class="container-xl">
        <nav aria-label="Breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
            <ol class="breadcrumb mb-0 small flex-nowrap overflow-auto">
                <?php foreach ($breadcrumbs as $index => $crumb): ?>
                <li class="breadcrumb-item<?= $crumb['url'] === null ? ' active' : '' ?>"
                    itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"
                    <?= $crumb['url'] === null ? ' aria-current="page"' : '' ?>>
                    <?php if ($crumb['url'] !== null): ?>
                        <a href="<?= htmlspecialchars($crumb['url']) ?>" itemprop="item">
                            <span itemprop="name"><?= htmlspecialchars($crumb['label']) ?></span>
                        </a>
                    <?php else: ?>
                        <span itemprop="name"><?= htmlspecialchars($crumb['label']) ?></span>
                    <?php endif; ?>
                    <meta itemprop="position" content="<?= $index + 1 ?>">
                </li>
                <?php endforeach; ?>
            </ol>
        </nav>
    </div>
</div>
