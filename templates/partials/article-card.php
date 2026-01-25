<?php
/** Expects: $article (with category_slug from joined query) */
$articleUrl = !empty($article->category_slug)
    ? '/category/' . htmlspecialchars($article->category_slug) . '/' . htmlspecialchars($article->slug)
    : '/articles/' . htmlspecialchars($article->slug);
?>
<div class="cr-article-card h-100">
    <?php if (!empty($article->featured_image)): ?>
    <a href="<?= $articleUrl ?>" class="cr-article-card-img">
        <img src="<?= htmlspecialchars($article->featured_image) ?>" alt="<?= htmlspecialchars($article->title) ?>">
    </a>
    <?php else: ?>
    <a href="<?= $articleUrl ?>" class="cr-article-card-img cr-article-card-placeholder">
        <i class="fas fa-file-alt"></i>
    </a>
    <?php endif; ?>
    <div class="cr-article-card-body">
        <h3 class="cr-article-card-title">
            <a href="<?= $articleUrl ?>"><?= htmlspecialchars($article->title) ?></a>
        </h3>
        <?php if (!empty($article->excerpt)): ?>
        <p class="cr-article-card-excerpt"><?= htmlspecialchars(mb_substr($article->excerpt, 0, 120)) ?>...</p>
        <?php endif; ?>
        <div class="cr-article-card-meta">
            <?php if ($article->published_at): ?>
            <span class="cr-article-card-date"><i class="far fa-calendar-alt"></i> <?= date('M j, Y', strtotime($article->published_at)) ?></span>
            <?php endif; ?>
            <a href="<?= $articleUrl ?>" class="cr-article-card-link">Read More &rarr;</a>
        </div>
    </div>
</div>
