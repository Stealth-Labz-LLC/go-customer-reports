<?php /** Expects: $review */ ?>
<div class="card h-100">
    <?php if (!empty($review->featured_image)): ?>
    <a href="/reviews/<?= htmlspecialchars($review->slug) ?>">
        <img src="<?= htmlspecialchars($review->featured_image) ?>" class="card-img-top" alt="<?= htmlspecialchars($review->name) ?>" style="height: 200px; object-fit: cover;">
    </a>
    <?php endif; ?>
    <div class="card-body d-flex flex-column">
        <?php if (!empty($review->brand)): ?>
        <div class="small text-uppercase fw-semibold text-muted mb-1"><?= htmlspecialchars($review->brand) ?></div>
        <?php endif; ?>
        <h5 class="card-title">
            <a href="/reviews/<?= htmlspecialchars($review->slug) ?>" class="text-decoration-none text-dark">
                <?= htmlspecialchars($review->name) ?>
            </a>
        </h5>
        <?php if ($review->rating_overall): ?>
        <div class="mb-2">
            <?php require __DIR__ . '/rating-stars.php'; ?>
        </div>
        <?php endif; ?>
        <?php if (!empty($review->short_description)): ?>
        <p class="card-text small text-muted flex-grow-1"><?= htmlspecialchars(mb_substr($review->short_description, 0, 100)) ?>...</p>
        <?php endif; ?>
        <div class="d-flex justify-content-between align-items-center mt-2">
            <?php if (!empty($review->price)): ?>
            <span class="fw-bold" style="color: var(--color-primary)"><?= htmlspecialchars($review->price) ?></span>
            <?php endif; ?>
            <a href="/reviews/<?= htmlspecialchars($review->slug) ?>" class="btn btn-primary btn-sm">Read Review</a>
        </div>
    </div>
</div>
