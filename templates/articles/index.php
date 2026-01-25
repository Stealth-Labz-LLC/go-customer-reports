<?php
$pageTitle = 'Articles & Guides | ' . $site->name;
$metaDescription = 'Expert articles and buying guides from ' . $site->name . '. Learn everything you need to make informed decisions.';
ob_start();
?>

<!-- Breadcrumbs -->
<div class="cr-breadcrumbs">
    <div class="container">
        <span><a href="/">Home</a> &raquo; <span class="current">Articles</span></span>
    </div>
</div>

<!-- Page Header -->
<div class="cr-page-header">
    <div class="container">
        <h1>Articles & Guides</h1>
        <p>Expert insights and buying guides to help you make informed decisions.</p>
    </div>
</div>

<!-- Main Content -->
<div class="cr-index-page">
    <div class="container py-4">
        <div class="row">
            <!-- Main Column -->
            <div class="col-lg-9">
                <?php if (!empty($articles)): ?>
                <div class="cr-results-info mb-3">
                    <span>Showing <?= count($articles) ?> of <?= $total ?> articles</span>
                </div>
                <div class="row g-4">
                    <?php foreach ($articles as $article): ?>
                    <div class="col-md-6 col-lg-4">
                        <?php require __DIR__ . '/../partials/article-card.php'; ?>
                    </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($total > $perPage): ?>
                <nav class="cr-pagination mt-5">
                    <?php $totalPages = ceil($total / $perPage); ?>
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="/articles?page=<?= $page - 1 ?>">&laquo; Prev</a>
                        </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                            <a class="page-link" href="/articles?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="/articles?page=<?= $page + 1 ?>">Next &raquo;</a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <?php endif; ?>

                <?php else: ?>
                <div class="cr-empty-state">
                    <i class="fas fa-newspaper"></i>
                    <h3>No articles yet</h3>
                    <p>Check back soon for new articles and guides.</p>
                </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-3">
                <div class="cr-sidebar">
                    <!-- Categories Widget -->
                    <?php if (!empty($categories)): ?>
                    <div class="cr-sidebar-widget">
                        <h4 class="cr-widget-title">Categories</h4>
                        <ul class="cr-category-list">
                            <?php foreach ($categories as $cat): ?>
                            <li>
                                <a href="/category/<?= htmlspecialchars($cat->slug) ?>">
                                    <i class="fas fa-chevron-right"></i>
                                    <?= htmlspecialchars($cat->name) ?>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>

                    <!-- Popular Topics Widget -->
                    <div class="cr-sidebar-widget">
                        <h4 class="cr-widget-title">Popular Topics</h4>
                        <div class="cr-tag-cloud">
                            <a href="/reviews" class="cr-tag">Product Reviews</a>
                            <a href="/articles" class="cr-tag">Buying Guides</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$__content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
