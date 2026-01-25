<?php
$pageTitle = ($article->meta_title ?? $article->title) . ' | ' . $site->name;
$metaDescription = $article->meta_description ?? ($article->excerpt ?? '');
ob_start();
?>

<?php if (!empty($breadcrumbs)): ?>
    <?php include __DIR__ . '/../partials/breadcrumbs.php'; ?>
<?php endif; ?>

<article class="container" style="padding-top: 2rem;">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <?php if (!empty($article->featured_image)): ?>
            <img src="<?= htmlspecialchars($article->featured_image) ?>" class="img-fluid rounded mb-4" alt="<?= htmlspecialchars($article->title) ?>">
            <?php endif; ?>

            <h1 class="fw-bold mb-3"><?= htmlspecialchars($article->title) ?></h1>

            <div class="text-muted mb-4">
                <?php if ($article->author_name): ?>
                <span>By <?= htmlspecialchars($article->author_name) ?></span> &middot;
                <?php endif; ?>
                <span><?= $article->published_at ? date('F j, Y', strtotime($article->published_at)) : '' ?></span>
            </div>

            <?php if (!empty($articleCategories)): ?>
            <div class="mb-4">
                <?php foreach ($articleCategories as $cat): ?>
                <a href="/category/<?= htmlspecialchars($cat->slug) ?>" class="badge bg-primary text-decoration-none"><?= htmlspecialchars($cat->name) ?></a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <div class="article-content">
                <?= $article->content ?>
            </div>
        </div>
    </div>
</article>

<?php
$__content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
