<?php
$pageTitle = 'Reviews | ' . $site->name;
$metaDescription = 'Honest product reviews from ' . $site->name;
ob_start();
?>

<div class="container">
    <h1 class="fw-bold mb-4">Reviews</h1>

    <?php if (!empty($reviews)): ?>
    <div class="row g-4">
        <?php foreach ($reviews as $review): ?>
        <div class="col-md-4">
            <?php require __DIR__ . '/../partials/review-card.php'; ?>
        </div>
        <?php endforeach; ?>
    </div>

    <?php if ($total > $perPage): ?>
    <nav class="mt-5">
        <ul class="pagination justify-content-center">
            <?php $totalPages = ceil($total / $perPage); ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                <a class="page-link" href="/reviews?page=<?= $i ?>"><?= $i ?></a>
            </li>
            <?php endfor; ?>
        </ul>
    </nav>
    <?php endif; ?>

    <?php else: ?>
    <p class="text-muted">No reviews yet.</p>
    <?php endif; ?>
</div>

<?php
$__content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
