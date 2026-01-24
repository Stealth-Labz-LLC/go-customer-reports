<?php
$pageTitle = ($listicle->meta_title ?? $listicle->title) . ' | ' . $site->name;
$metaDescription = $listicle->meta_description ?? ($listicle->excerpt ?? '');
ob_start();
?>

<article class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="fw-bold mb-4"><?= htmlspecialchars($listicle->title) ?></h1>

            <div class="text-muted mb-4">
                <?php if ($listicle->author_name): ?>
                <span>By <?= htmlspecialchars($listicle->author_name) ?></span> &middot;
                <?php endif; ?>
                <span><?= $listicle->published_at ? date('F j, Y', strtotime($listicle->published_at)) : '' ?></span>
            </div>

            <?php if (!empty($listicle->introduction)): ?>
            <div class="mb-4">
                <?= $listicle->introduction ?>
            </div>
            <?php endif; ?>

            <?php if (!empty($listicle->items)): ?>
            <div class="listicle-items">
                <?php foreach ($listicle->items as $item): ?>
                <div class="card mb-4 p-4">
                    <div class="d-flex align-items-start gap-3">
                        <span class="badge bg-primary rounded-circle fs-5 p-2"><?= $item['rank'] ?? '' ?></span>
                        <div class="flex-grow-1">
                            <h3 class="fw-bold mb-2"><?= htmlspecialchars($item['name'] ?? '') ?></h3>
                            <?php if (!empty($item['description'])): ?>
                            <p class="text-muted"><?= htmlspecialchars($item['description']) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($item['price'])): ?>
                            <span class="fw-bold" style="color: var(--color-primary)"><?= htmlspecialchars($item['price']) ?></span>
                            <?php endif; ?>
                            <?php if (!empty($item['affiliate_url'])): ?>
                            <a href="<?= htmlspecialchars($item['affiliate_url']) ?>" target="_blank" rel="nofollow" class="btn btn-primary btn-sm ms-2">Check Price</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <?php if (!empty($listicle->conclusion)): ?>
            <div class="mt-4">
                <h2 class="fw-bold mb-3">Final Thoughts</h2>
                <?= $listicle->conclusion ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</article>

<?php
$__content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
