<?php
$pageTitle = 'Articles | ' . $site->name;
$metaDescription = 'Latest articles and guides from ' . $site->name;
ob_start();
?>

<div class="container">
    <h1 class="fw-bold mb-4">Articles</h1>

    <?php if (!empty($articles)): ?>
    <div class="row g-4">
        <?php foreach ($articles as $article): ?>
        <div class="col-md-4">
            <?php require __DIR__ . '/../partials/article-card.php'; ?>
        </div>
        <?php endforeach; ?>
    </div>

    <?php if ($total > $perPage): ?>
    <nav class="mt-5">
        <ul class="pagination justify-content-center">
            <?php $totalPages = ceil($total / $perPage); ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                <a class="page-link" href="/articles?page=<?= $i ?>"><?= $i ?></a>
            </li>
            <?php endfor; ?>
        </ul>
    </nav>
    <?php endif; ?>

    <?php else: ?>
    <p class="text-muted">No articles yet.</p>
    <?php endif; ?>
</div>

<?php
$__content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
