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

// Add author if available
if (!empty($article->author_name)) {
    $schemaData['author'] = [
        '@type' => 'Person',
        'name' => $article->author_name
    ];
}

// Add image if available
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
<section class="cr-article-hero">
    <div class="container">
        <div class="cr-article-hero-inner">
            <?php if (!empty($articleCategories)): ?>
            <div class="cr-article-categories">
                <?php foreach ($articleCategories as $cat): ?>
                <a href="/category/<?= htmlspecialchars($cat->slug) ?>" class="cr-article-cat-badge"><?= htmlspecialchars($cat->name) ?></a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <h1 class="cr-article-title"><?= htmlspecialchars($article->title) ?></h1>

            <?php if (!empty($article->excerpt)): ?>
            <p class="cr-article-excerpt"><?= htmlspecialchars($article->excerpt) ?></p>
            <?php endif; ?>

            <div class="cr-article-meta">
                <?php if ($article->author_name): ?>
                <span class="cr-article-author">
                    <i class="fas fa-user"></i> <?= htmlspecialchars($article->author_name) ?>
                </span>
                <?php endif; ?>
                <?php if ($article->published_at): ?>
                <span class="cr-article-date">
                    <i class="far fa-calendar-alt"></i> <?= date('F j, Y', strtotime($article->published_at)) ?>
                </span>
                <?php endif; ?>
                <?php if ($article->reading_time): ?>
                <span class="cr-article-reading-time">
                    <i class="far fa-clock"></i> <?= htmlspecialchars($article->reading_time) ?> min read
                </span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<div class="container py-4">
    <div class="row">
        <!-- Main Content Column -->
        <div class="col-lg-8">
            <?php if (!empty($article->featured_image)): ?>
            <div class="cr-article-featured-image">
                <img src="<?= htmlspecialchars($article->featured_image) ?>" alt="<?= htmlspecialchars($article->title) ?>">
            </div>
            <?php endif; ?>

            <article class="cr-article-content">
                <?= $article->content ?>
            </article>

            <!-- Article Footer -->
            <div class="cr-article-footer">
                <div class="cr-article-share">
                    <span>Share this article:</span>
                    <div class="cr-share-buttons">
                        <a href="https://twitter.com/intent/tweet?url=<?= urlencode($site->url . '/category/' . $primaryCategory->slug . '/' . $article->slug) ?>&text=<?= urlencode($article->title) ?>" target="_blank" rel="noopener" class="cr-share-btn cr-share-twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($site->url . '/category/' . $primaryCategory->slug . '/' . $article->slug) ?>" target="_blank" rel="noopener" class="cr-share-btn cr-share-facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= urlencode($site->url . '/category/' . $primaryCategory->slug . '/' . $article->slug) ?>&title=<?= urlencode($article->title) ?>" target="_blank" rel="noopener" class="cr-share-btn cr-share-linkedin">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>

                <?php if (!empty($articleCategories)): ?>
                <div class="cr-article-tags">
                    <span>Categories:</span>
                    <?php foreach ($articleCategories as $cat): ?>
                    <a href="/category/<?= htmlspecialchars($cat->slug) ?>" class="cr-article-tag"><?= htmlspecialchars($cat->name) ?></a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Related Reviews (Cross-link to money pages) -->
            <?php if (!empty($relatedReviews)): ?>
            <div class="cr-article-related-reviews">
                <h3><i class="fas fa-star"></i> Related Product Reviews</h3>
                <div class="cr-related-reviews-grid">
                    <?php foreach ($relatedReviews as $review):
                        $reviewUrl = '/category/' . htmlspecialchars($primaryCategory->slug) . '/reviews/' . htmlspecialchars($review->slug);
                    ?>
                    <a href="<?= $reviewUrl ?>" class="cr-related-review-card">
                        <?php if (!empty($review->featured_image)): ?>
                        <img src="<?= htmlspecialchars($review->featured_image) ?>" alt="<?= htmlspecialchars($review->name) ?>">
                        <?php else: ?>
                        <div class="cr-related-review-placeholder"><i class="fas fa-box"></i></div>
                        <?php endif; ?>
                        <div class="cr-related-review-info">
                            <span class="cr-related-review-name"><?= htmlspecialchars($review->name) ?></span>
                            <?php if ($review->rating_overall): ?>
                            <span class="cr-related-review-rating"><?= number_format(floatval($review->rating_overall), 1) ?> <i class="fas fa-star"></i></span>
                            <?php endif; ?>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Related Articles -->
            <?php if (!empty($relatedArticles)): ?>
            <div class="cr-related-articles">
                <h2>Keep Reading</h2>
                <div class="row g-4">
                    <?php foreach ($relatedArticles as $relArticle):
                        $relArticleUrl = '/category/' . htmlspecialchars($primaryCategory->slug) . '/' . htmlspecialchars($relArticle->slug);
                    ?>
                    <div class="col-md-6">
                        <div class="cr-related-article-card">
                            <?php if (!empty($relArticle->featured_image)): ?>
                            <a href="<?= $relArticleUrl ?>" class="cr-related-article-img">
                                <img src="<?= htmlspecialchars($relArticle->featured_image) ?>" alt="<?= htmlspecialchars($relArticle->title) ?>">
                            </a>
                            <?php endif; ?>
                            <div class="cr-related-article-body">
                                <h3 class="cr-related-article-title">
                                    <a href="<?= $relArticleUrl ?>"><?= htmlspecialchars($relArticle->title) ?></a>
                                </h3>
                                <?php if (!empty($relArticle->excerpt)): ?>
                                <p class="cr-related-article-excerpt"><?= htmlspecialchars(mb_substr($relArticle->excerpt, 0, 100)) ?>...</p>
                                <?php endif; ?>
                                <a href="<?= $relArticleUrl ?>" class="cr-related-article-link">Read More &rarr;</a>
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
            <div class="cr-article-sidebar">

                <!-- In This Category Widget -->
                <?php if (!empty($relatedArticles)): ?>
                <div class="cr-article-widget">
                    <h3 class="cr-article-widget-title">
                        <i class="fas fa-folder-open"></i> More in <?= htmlspecialchars($primaryCategory->name ?? 'This Category') ?>
                    </h3>
                    <div class="cr-article-widget-list">
                        <?php foreach (array_slice($relatedArticles, 0, 5) as $relArticle):
                            $relArticleUrl = '/category/' . htmlspecialchars($primaryCategory->slug) . '/' . htmlspecialchars($relArticle->slug);
                        ?>
                        <a href="<?= $relArticleUrl ?>" class="cr-article-widget-item">
                            <?php if (!empty($relArticle->featured_image)): ?>
                            <img src="<?= htmlspecialchars($relArticle->featured_image) ?>" alt="">
                            <?php endif; ?>
                            <span><?= htmlspecialchars($relArticle->title) ?></span>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Browse Categories Widget -->
                <?php if (!empty($allCategories)): ?>
                <div class="cr-article-widget">
                    <h3 class="cr-article-widget-title">
                        <i class="fas fa-th-large"></i> Browse Categories
                    </h3>
                    <ul class="cr-article-cat-list">
                        <?php foreach ($allCategories as $cat):
                            $isActive = $primaryCategory && $cat->id === $primaryCategory->id;
                        ?>
                        <li>
                            <a href="/category/<?= htmlspecialchars($cat->slug) ?>" class="<?= $isActive ? 'active' : '' ?>">
                                <?= htmlspecialchars($cat->name) ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <!-- Top Reviews Widget -->
                <?php if (!empty($relatedReviews)): ?>
                <div class="cr-article-widget cr-article-widget-dark">
                    <h3 class="cr-article-widget-title">
                        <i class="fas fa-star"></i> Top Rated Products
                    </h3>
                    <div class="cr-article-widget-reviews">
                        <?php foreach ($relatedReviews as $review):
                            $reviewUrl = '/category/' . htmlspecialchars($primaryCategory->slug) . '/reviews/' . htmlspecialchars($review->slug);
                        ?>
                        <a href="<?= $reviewUrl ?>" class="cr-widget-review-item">
                            <div class="cr-widget-review-info">
                                <span class="cr-widget-review-name"><?= htmlspecialchars($review->name) ?></span>
                                <?php if ($review->rating_overall): ?>
                                <div class="cr-widget-review-rating">
                                    <span><?= number_format(floatval($review->rating_overall), 1) ?></span>
                                    <i class="fas fa-star"></i>
                                </div>
                                <?php endif; ?>
                            </div>
                            <span class="cr-widget-review-link">View Review &rarr;</span>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Trust Widget -->
                <div class="cr-article-widget cr-article-widget-trust">
                    <h3 class="cr-article-widget-title">
                        <i class="fas fa-shield-alt"></i> Why Trust <?= htmlspecialchars($site->name) ?>?
                    </h3>
                    <ul class="cr-trust-list">
                        <li><i class="fas fa-check-circle"></i> Expert Research</li>
                        <li><i class="fas fa-check-circle"></i> Unbiased Reviews</li>
                        <li><i class="fas fa-check-circle"></i> Data-Driven Rankings</li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</div>

<?php
$__content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
