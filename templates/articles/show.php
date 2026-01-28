<?php
$pageTitle = ($article->meta_title ?? $article->title) . ' | ' . $site->name;
$metaDescription = $article->meta_description ?? ($article->excerpt ?? '');
$ogImage = $article->featured_image ?? null;
$ogType = 'article';

// Article schema
$schemaData = [
    '@context' => 'https://schema.org',
    '@type' => 'Article',
    'headline' => $article->title,
    'description' => $article->excerpt ?? $article->meta_description ?? '',
    'author' => [
        '@type' => 'Organization',
        'name' => $site->name
    ],
    'publisher' => [
        '@type' => 'Organization',
        'name' => $site->name,
        'logo' => [
            '@type' => 'ImageObject',
            'url' => 'https://' . $site->domain . '/images/logo.svg'
        ]
    ],
    'datePublished' => $article->published_at ? date('c', strtotime($article->published_at)) : date('c'),
    'dateModified' => $article->updated_at ? date('c', strtotime($article->updated_at)) : date('c'),
    'mainEntityOfPage' => [
        '@type' => 'WebPage',
        '@id' => 'https://' . $site->domain . '/category/' . ($primaryCategory->slug ?? '') . '/' . $article->slug
    ]
];

if (!empty($article->author_name)) {
    $schemaData['author'] = [
        '@type' => 'Person',
        'name' => $article->author_name
    ];
}

if (!empty($article->featured_image)) {
    $schemaData['image'] = $article->featured_image;
}

ob_start();
?>

<!-- Schema.org Article structured data -->
<script type="application/ld+json">
<?= json_encode($schemaData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?>
</script>

<?php if (!empty($breadcrumbs)): ?>
    <?php include __DIR__ . '/../partials/breadcrumbs.php'; ?>
<?php endif; ?>

<!-- Article Hero -->
<section class="bg-dark text-white py-5">
    <div class="container-xl">
        <?php if (!empty($articleCategories)): ?>
        <div class="mb-2">
            <?php foreach ($articleCategories as $cat): ?>
            <a href="<?= BASE_URL ?>/category/<?= htmlspecialchars($cat->slug) ?>" class="badge bg-success text-decoration-none me-1"><?= htmlspecialchars($cat->name) ?></a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <h1 class="fw-bold mb-3"><?= htmlspecialchars($article->title) ?></h1>

        <?php if (!empty($article->excerpt)): ?>
        <p class="lead text-white-50 mb-3"><?= htmlspecialchars($article->excerpt) ?></p>
        <?php endif; ?>

        <div class="d-flex flex-wrap gap-3 text-white-50 small">
            <?php if ($article->author_name): ?>
            <span><i class="fas fa-user me-1"></i> <?= htmlspecialchars($article->author_name) ?></span>
            <?php endif; ?>
            <?php if ($article->published_at): ?>
            <span><i class="far fa-calendar-alt me-1"></i> <?= date('F j, Y', strtotime($article->published_at)) ?></span>
            <?php endif; ?>
            <?php if ($article->reading_time): ?>
            <span><i class="far fa-clock me-1"></i> <?= htmlspecialchars($article->reading_time) ?> min read</span>
            <?php endif; ?>
        </div>
    </div>
</section>

<div class="container-xl py-4">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <?php if (!empty($article->featured_image)): ?>
            <img src="<?= IMAGE_BASE_URL . htmlspecialchars($article->featured_image) ?>" alt="<?= htmlspecialchars($article->title) ?>" class="img-fluid rounded shadow-sm mb-4 w-100" style="max-height:450px;object-fit:cover;">
            <?php endif; ?>

            <article class="article-content">
                <?= $article->content ?>
            </article>

            <!-- Share + Categories -->
            <div class="border-top pt-4 mt-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <div class="d-flex gap-2 mb-2">
                        <span class="text-muted small me-1">Share:</span>
                        <a href="https://twitter.com/intent/tweet?url=<?= urlencode($site->url . '/category/' . $primaryCategory->slug . '/' . $article->slug) ?>&text=<?= urlencode($article->title) ?>" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($site->url . '/category/' . $primaryCategory->slug . '/' . $article->slug) ?>" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= urlencode($site->url . '/category/' . $primaryCategory->slug . '/' . $article->slug) ?>&title=<?= urlencode($article->title) ?>" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                    <?php if (!empty($articleCategories)): ?>
                    <div class="mb-2">
                        <?php foreach ($articleCategories as $cat): ?>
                        <a href="<?= BASE_URL ?>/category/<?= htmlspecialchars($cat->slug) ?>" class="badge bg-dark bg-opacity-10 text-dark text-decoration-none"><?= htmlspecialchars($cat->name) ?></a>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Related Reviews (Cross-link to money pages) -->
            <?php if (!empty($relatedReviews)): ?>
            <div class="mt-4">
                <h3 class="h5 fw-bold mb-3"><i class="fas fa-star text-warning me-2"></i>Related Product Reviews</h3>
                <div class="row g-3">
                    <?php foreach ($relatedReviews as $review):
                        $reviewUrl = BASE_URL . '/category/' . htmlspecialchars($primaryCategory->slug) . '/reviews/' . htmlspecialchars($review->slug);
                    ?>
                    <div class="col-md-6">
                        <a href="<?= $reviewUrl ?>" class="card border-0 shadow-sm h-100 text-decoration-none overflow-hidden">
                            <div class="row g-0">
                                <?php if (!empty($review->featured_image)): ?>
                                <div class="col-4">
                                    <img src="<?= IMAGE_BASE_URL . htmlspecialchars($review->featured_image) ?>" alt="<?= htmlspecialchars($review->name) ?>" class="img-fluid h-100" style="object-fit:cover;">
                                </div>
                                <div class="col-8">
                                <?php else: ?>
                                <div class="col-12">
                                <?php endif; ?>
                                    <div class="card-body py-2">
                                        <span class="fw-bold text-dark small"><?= htmlspecialchars($review->name) ?></span>
                                        <?php if ($review->rating_overall): ?>
                                        <div class="mt-1">
                                            <span class="badge bg-success"><?= number_format(floatval($review->rating_overall), 1) ?> <i class="fas fa-star"></i></span>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Related Articles -->
            <?php if (!empty($relatedArticles)): ?>
            <div class="mt-5">
                <h2 class="h5 fw-bold mb-3">Keep Reading</h2>
                <div class="row g-3">
                    <?php foreach ($relatedArticles as $relArticle):
                        $relArticleUrl = BASE_URL . '/category/' . htmlspecialchars($primaryCategory->slug) . '/' . htmlspecialchars($relArticle->slug);
                    ?>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100 overflow-hidden">
                            <?php if (!empty($relArticle->featured_image)): ?>
                            <a href="<?= $relArticleUrl ?>">
                                <img src="<?= IMAGE_BASE_URL . htmlspecialchars($relArticle->featured_image) ?>" alt="<?= htmlspecialchars($relArticle->title) ?>" class="card-img-top" style="height:150px;object-fit:cover;">
                            </a>
                            <?php endif; ?>
                            <div class="card-body">
                                <h3 class="h6 fw-bold">
                                    <a href="<?= $relArticleUrl ?>" class="text-dark text-decoration-none"><?= htmlspecialchars($relArticle->title) ?></a>
                                </h3>
                                <?php if (!empty($relArticle->excerpt)): ?>
                                <p class="text-muted small mb-2"><?= htmlspecialchars(mb_substr($relArticle->excerpt, 0, 100)) ?>...</p>
                                <?php endif; ?>
                                <a href="<?= $relArticleUrl ?>" class="text-success text-decoration-none small fw-bold">Read More <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="sticky-lg-top" style="top:80px;">
                <!-- More in Category -->
                <?php if (!empty($relatedArticles)): ?>
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-dark text-white fw-bold small">
                        <i class="fas fa-folder-open me-1"></i> More in <?= htmlspecialchars($primaryCategory->name ?? 'This Category') ?>
                    </div>
                    <div class="list-group list-group-flush">
                        <?php foreach (array_slice($relatedArticles, 0, 5) as $relArticle):
                            $relArticleUrl = BASE_URL . '/category/' . htmlspecialchars($primaryCategory->slug) . '/' . htmlspecialchars($relArticle->slug);
                        ?>
                        <a href="<?= $relArticleUrl ?>" class="list-group-item list-group-item-action d-flex align-items-center gap-2 small">
                            <?php if (!empty($relArticle->featured_image)): ?>
                            <img src="<?= IMAGE_BASE_URL . htmlspecialchars($relArticle->featured_image) ?>" alt="" class="rounded" style="width:40px;height:40px;object-fit:cover;">
                            <?php endif; ?>
                            <span class="text-truncate"><?= htmlspecialchars($relArticle->title) ?></span>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Browse Categories -->
                <?php if (!empty($allCategories)): ?>
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-dark text-white fw-bold small">
                        <i class="fas fa-th-large me-1"></i> Browse Categories
                    </div>
                    <div class="list-group list-group-flush">
                        <?php foreach ($allCategories as $cat):
                            $isActive = $primaryCategory && $cat->id === $primaryCategory->id;
                        ?>
                        <a href="<?= BASE_URL ?>/category/<?= htmlspecialchars($cat->slug) ?>" class="list-group-item list-group-item-action small <?= $isActive ? 'active' : '' ?>">
                            <?= htmlspecialchars($cat->name) ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Top Reviews -->
                <?php if (!empty($relatedReviews)): ?>
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-dark text-white fw-bold small">
                        <i class="fas fa-star me-1"></i> Top Rated Products
                    </div>
                    <div class="list-group list-group-flush">
                        <?php foreach ($relatedReviews as $review):
                            $reviewUrl = BASE_URL . '/category/' . htmlspecialchars($primaryCategory->slug) . '/reviews/' . htmlspecialchars($review->slug);
                        ?>
                        <a href="<?= $reviewUrl ?>" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small fw-bold"><?= htmlspecialchars($review->name) ?></span>
                                <?php if ($review->rating_overall): ?>
                                <span class="badge bg-success"><?= number_format(floatval($review->rating_overall), 1) ?> <i class="fas fa-star"></i></span>
                                <?php endif; ?>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Trust Widget -->
                <div class="card border-0 bg-success text-white">
                    <div class="card-body text-center small">
                        <i class="fas fa-shield-alt fa-2x mb-2 d-block"></i>
                        <strong>Why Trust <?= htmlspecialchars($site->name) ?>?</strong>
                        <ul class="list-unstyled mt-2 mb-0 small">
                            <li><i class="fas fa-check-circle me-1"></i> Expert Research</li>
                            <li><i class="fas fa-check-circle me-1"></i> Unbiased Reviews</li>
                            <li><i class="fas fa-check-circle me-1"></i> Data-Driven Rankings</li>
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
