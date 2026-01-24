<?php
$pageTitle = ($review->meta_title ?? $review->name . ' Review') . ' | ' . $site->name;
$metaDescription = $review->meta_description ?? ($review->short_description ?? '');
ob_start();
?>

<article class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <header class="text-center mb-4">
                <?php if (!empty($review->featured_image)): ?>
                <img src="<?= htmlspecialchars($review->featured_image) ?>" class="mb-4" alt="<?= htmlspecialchars($review->name) ?>" style="max-height: 200px; object-fit: contain;">
                <?php endif; ?>

                <?php if ($review->rating_overall): ?>
                <div class="mb-3">
                    <?php $rating = $review->rating_overall; require __DIR__ . '/../partials/rating-stars.php'; ?>
                </div>
                <?php endif; ?>

                <h1 class="fw-bold"><?= htmlspecialchars($review->name) ?></h1>

                <?php if ($review->brand): ?>
                <p class="text-muted fs-5">by <?= htmlspecialchars($review->brand) ?></p>
                <?php endif; ?>
            </header>

            <?php if ($review->affiliate_url): ?>
            <div class="bg-light rounded p-4 text-center mb-4">
                <?php if ($review->price): ?>
                <p class="fs-3 fw-bold mb-1" style="color: var(--color-primary)"><?= htmlspecialchars($review->price) ?></p>
                <?php endif; ?>
                <?php if ($review->price_note): ?>
                <p class="small fw-semibold text-accent mb-3"><?= htmlspecialchars($review->price_note) ?></p>
                <?php endif; ?>
                <a href="<?= htmlspecialchars($review->affiliate_url) ?>" target="_blank" rel="nofollow" class="btn btn-primary btn-lg">
                    <?= htmlspecialchars($review->cta_text ?? 'Check Availability') ?> &rarr;
                </a>
            </div>
            <?php endif; ?>

            <?php if (!empty($review->pros) || !empty($review->cons)): ?>
            <div class="row g-3 mb-4">
                <?php if (!empty($review->pros)): ?>
                <div class="col-md-6">
                    <div class="p-3 rounded" style="background: #f0fdf4;">
                        <h5 class="fw-bold text-success mb-3">&#10003; Pros</h5>
                        <ul class="list-unstyled mb-0">
                            <?php foreach ($review->pros as $pro): ?>
                            <li class="mb-2 small"><span class="text-success me-2">&#10003;</span><?= htmlspecialchars($pro) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <?php endif; ?>
                <?php if (!empty($review->cons)): ?>
                <div class="col-md-6">
                    <div class="p-3 rounded" style="background: #fef2f2;">
                        <h5 class="fw-bold text-danger mb-3">&#10007; Cons</h5>
                        <ul class="list-unstyled mb-0">
                            <?php foreach ($review->cons as $con): ?>
                            <li class="mb-2 small"><span class="text-danger me-2">&#10007;</span><?= htmlspecialchars($con) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <div class="review-content">
                <?= $review->content ?>
            </div>
        </div>
    </div>
</article>

<?php
$__content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
