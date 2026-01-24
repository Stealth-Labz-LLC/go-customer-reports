<?php /** Expects: $article */ ?>
<div class="card h-100">
    <?php if (!empty($article->featured_image)): ?>
    <a href="/articles/<?= htmlspecialchars($article->slug) ?>">
        <img src="<?= htmlspecialchars($article->featured_image) ?>" class="card-img-top" alt="<?= htmlspecialchars($article->title) ?>" style="height: 200px; object-fit: cover;">
    </a>
    <?php endif; ?>
    <div class="card-body d-flex flex-column">
        <h5 class="card-title">
            <a href="/articles/<?= htmlspecialchars($article->slug) ?>" class="text-decoration-none text-dark">
                <?= htmlspecialchars($article->title) ?>
            </a>
        </h5>
        <?php if (!empty($article->excerpt)): ?>
        <p class="card-text small text-muted flex-grow-1"><?= htmlspecialchars(mb_substr($article->excerpt, 0, 120)) ?>...</p>
        <?php endif; ?>
        <div class="small text-muted mt-2">
            <?= $article->published_at ? date('M j, Y', strtotime($article->published_at)) : '' ?>
        </div>
    </div>
</div>
