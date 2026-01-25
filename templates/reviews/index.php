<?php
$pageTitle = 'Product Reviews | ' . $site->name;
$metaDescription = 'Honest, in-depth product reviews from ' . $site->name . '. Find the best products with our expert ratings and analysis.';
ob_start();
?>

<!-- Breadcrumbs -->
<div class="cr-breadcrumbs">
    <div class="container">
        <span><a href="/">Home</a> &raquo; <span class="current">Reviews</span></span>
    </div>
</div>

<!-- Page Header -->
<div class="cr-page-header">
    <div class="container">
        <h1>Product Reviews</h1>
        <p>Honest, in-depth reviews to help you make smarter purchasing decisions.</p>
    </div>
</div>

<!-- Main Content -->
<div class="cr-index-page">
    <div class="container py-4">
        <div class="row">
            <!-- Main Column -->
            <div class="col-lg-9">
                <?php if (!empty($reviews)): ?>
                <div class="cr-results-info mb-3">
                    <span>Showing <?= count($reviews) ?> of <?= $total ?> reviews</span>
                </div>
                <div class="row g-4">
                    <?php foreach ($reviews as $review): ?>
                    <div class="col-md-6 col-lg-4">
                        <?php require __DIR__ . '/../partials/review-card.php'; ?>
                    </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($total > $perPage): ?>
                <nav class="cr-pagination mt-5">
                    <?php $totalPages = ceil($total / $perPage); ?>
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="/reviews?page=<?= $page - 1 ?>">&laquo; Prev</a>
                        </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                            <a class="page-link" href="/reviews?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="/reviews?page=<?= $page + 1 ?>">Next &raquo;</a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <?php endif; ?>

                <?php else: ?>
                <div class="cr-empty-state">
                    <i class="fas fa-search"></i>
                    <h3>No reviews yet</h3>
                    <p>Check back soon for new product reviews.</p>
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

                    <!-- Why Trust Us Widget -->
                    <div class="cr-sidebar-widget cr-trust-widget">
                        <h4 class="cr-widget-title">Why Trust Our Reviews?</h4>
                        <ul class="cr-trust-list">
                            <li><i class="fas fa-check-circle"></i> Independent testing</li>
                            <li><i class="fas fa-check-circle"></i> No sponsored content</li>
                            <li><i class="fas fa-check-circle"></i> Expert analysis</li>
                            <li><i class="fas fa-check-circle"></i> Real user feedback</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$__content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
