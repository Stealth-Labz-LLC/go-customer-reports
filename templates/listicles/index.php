<?php
$pageTitle = 'Top Picks & Comparisons | ' . $site->name;
$metaDescription = 'Find the best products with our expert-curated top picks and comparison guides from ' . $site->name . '.';
ob_start();
?>

<!-- Breadcrumbs -->
<div class="cr-breadcrumbs">
    <div class="container">
        <span><a href="/">Home</a> &raquo; <span class="current">Top Picks</span></span>
    </div>
</div>

<!-- Page Header -->
<div class="cr-page-header">
    <div class="container">
        <h1>Top Picks & Comparisons</h1>
        <p>Expert-curated lists to help you find the best products for your needs.</p>
    </div>
</div>

<!-- Main Content -->
<div class="cr-index-page">
    <div class="container py-4">
        <div class="row">
            <!-- Main Column -->
            <div class="col-lg-9">
                <?php if (!empty($listicles)): ?>
                <div class="cr-results-info mb-3">
                    <span>Showing <?= count($listicles) ?> of <?= $total ?> guides</span>
                </div>
                <div class="row g-4">
                    <?php foreach ($listicles as $listicle):
                        $listicleUrl = !empty($listicle->category_slug)
                            ? '/category/' . htmlspecialchars($listicle->category_slug) . '/top/' . htmlspecialchars($listicle->slug)
                            : '/top/' . htmlspecialchars($listicle->slug);
                    ?>
                    <div class="col-md-6">
                        <div class="cr-listicle-card h-100">
                            <?php if (!empty($listicle->featured_image)): ?>
                            <a href="<?= $listicleUrl ?>" class="cr-listicle-card-img">
                                <img src="<?= htmlspecialchars($listicle->featured_image) ?>" alt="<?= htmlspecialchars($listicle->title) ?>">
                            </a>
                            <?php else: ?>
                            <a href="<?= $listicleUrl ?>" class="cr-listicle-card-img cr-listicle-card-placeholder">
                                <i class="fas fa-list-ol"></i>
                            </a>
                            <?php endif; ?>
                            <div class="cr-listicle-card-body">
                                <h3 class="cr-listicle-card-title">
                                    <a href="<?= $listicleUrl ?>"><?= htmlspecialchars($listicle->title) ?></a>
                                </h3>
                                <?php if (!empty($listicle->excerpt)): ?>
                                <p class="cr-listicle-card-excerpt"><?= htmlspecialchars(mb_substr($listicle->excerpt, 0, 120)) ?>...</p>
                                <?php endif; ?>
                                <div class="cr-listicle-card-meta">
                                    <?php if ($listicle->published_at): ?>
                                    <span class="cr-listicle-card-date"><i class="far fa-calendar-alt"></i> <?= date('M j, Y', strtotime($listicle->published_at)) ?></span>
                                    <?php endif; ?>
                                    <a href="<?= $listicleUrl ?>" class="cr-listicle-card-link">View Guide &rarr;</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($total > $perPage): ?>
                <nav class="cr-pagination mt-5">
                    <?php $totalPages = ceil($total / $perPage); ?>
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="/top?page=<?= $page - 1 ?>">&laquo; Prev</a>
                        </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                            <a class="page-link" href="/top?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="/top?page=<?= $page + 1 ?>">Next &raquo;</a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <?php endif; ?>

                <?php else: ?>
                <div class="cr-empty-state">
                    <i class="fas fa-search"></i>
                    <h3>No guides yet</h3>
                    <p>Check back soon for new top picks and comparison guides.</p>
                </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-3">
                <div class="cr-sidebar">
                    <!-- Why Trust Us Widget -->
                    <div class="cr-sidebar-widget cr-trust-widget">
                        <h4 class="cr-widget-title">How We Rank</h4>
                        <ul class="cr-trust-list">
                            <li><i class="fas fa-check-circle"></i> Hands-on testing</li>
                            <li><i class="fas fa-check-circle"></i> Expert evaluation</li>
                            <li><i class="fas fa-check-circle"></i> User reviews analyzed</li>
                            <li><i class="fas fa-check-circle"></i> Regular updates</li>
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
