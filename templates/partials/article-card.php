<?php
/** Expects: $article (with category_slug from joined query) */
$articleUrl = BASE_URL . (!empty($article->category_slug)
    ? '/category/' . htmlspecialchars($article->category_slug) . '/' . htmlspecialchars($article->slug)
    : '/articles/' . htmlspecialchars($article->slug));
?>
<div class="card h-100 shadow-sm">
    <?php if (!empty($article->featured_image)): ?>
    <a href="<?= $articleUrl ?>">
        <img src="<?= IMAGE_BASE_URL . htmlspecialchars($article->featured_image) ?>" class="card-img-top" alt="<?= htmlspecialchars($article->title) ?>">
    </a>
    <?php else: ?>
    <a href="<?= $articleUrl ?>" class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height:180px">
        <i class="fas fa-file-alt fa-2x text-muted"></i>
    </a>
    <?php endif; ?>
    <div class="card-body d-flex flex-column">
        <?php if (!empty($article->category_name)): ?>
        <span class="badge bg-success mb-2 align-self-start"><?= htmlspecialchars($article->category_name) ?></span>
        <?php endif; ?>
        <h5 class="card-title">
            <a href="<?= $articleUrl ?>" class="text-decoration-none text-dark"><?= htmlspecialchars($article->title) ?></a>
        </h5>
        <?php if (!empty($article->excerpt)): ?>
        <p class="card-text text-muted small flex-grow-1"><?= htmlspecialchars(mb_substr($article->excerpt, 0, 120)) ?>...</p>
        <?php endif; ?>
        <div class="d-flex justify-content-between align-items-center mt-auto pt-2">
            <?php if ($article->published_at): ?>
            <small class="text-muted"><i class="far fa-calendar-alt"></i> <?= date('M j, Y', strtotime($article->published_at)) ?></small>
            <?php endif; ?>
            <a href="<?= $articleUrl ?>" class="btn btn-sm btn-outline-success">Read More</a>
        </div>
    </div>
</div>
