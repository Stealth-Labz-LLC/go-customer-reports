<?php
/** Expects: $review (with category_slug from joined query) */
$reviewUrl = BASE_URL . (!empty($review->category_slug)
    ? '/category/' . htmlspecialchars($review->category_slug) . '/reviews/' . htmlspecialchars($review->slug)
    : '/reviews/' . htmlspecialchars($review->slug));
$rating = $review->rating_overall ? floatval($review->rating_overall) : null;
$cta = !empty($review->cta_text) ? $review->cta_text : 'Read Full Review';

// Rating color
$ratingClass = 'bg-success';
if ($rating && $rating < 3.0) $ratingClass = 'bg-danger';
elseif ($rating && $rating < 4.0) $ratingClass = 'bg-warning text-dark';

// Star display
$fullStars = $rating ? floor($rating) : 0;
$halfStar = $rating ? ($rating - $fullStars >= 0.3) : false;
$emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
?>
<div class="card h-100 shadow-sm border-0 overflow-hidden review-card">
    <a href="<?= $reviewUrl ?>" class="d-block position-relative">
        <?php if (!empty($review->featured_image)): ?>
        <img src="<?= IMAGE_BASE_URL . htmlspecialchars($review->featured_image) ?>" class="card-img-top" alt="<?= htmlspecialchars($review->name) ?>">
        <?php else: ?>
        <div class="card-img-top bg-light d-flex align-items-center justify-content-center">
            <i class="fas fa-box-open fa-3x text-muted"></i>
        </div>
        <?php endif; ?>
        <?php if ($rating): ?>
        <div class="position-absolute top-0 end-0 m-2">
            <span class="badge <?= $ratingClass ?> fs-6 shadow"><?= number_format($rating, 1) ?>/5</span>
        </div>
        <?php endif; ?>
    </a>

    <div class="card-body d-flex flex-column">
        <?php if (!empty($review->category_name)): ?>
        <span class="badge bg-dark bg-opacity-10 text-dark mb-2 align-self-start small"><?= htmlspecialchars($review->category_name) ?></span>
        <?php endif; ?>

        <h5 class="card-title fw-bold mb-2">
            <a href="<?= $reviewUrl ?>" class="text-decoration-none text-dark"><?= htmlspecialchars($review->name) ?></a>
        </h5>

        <?php if (!empty($review->short_description)): ?>
        <p class="card-text text-muted small mb-3"><?= htmlspecialchars(mb_substr($review->short_description, 0, 100)) ?></p>
        <?php endif; ?>

        <!-- Star Rating -->
        <?php if ($rating): ?>
        <div class="mb-2">
            <span class="text-warning">
                <?php for ($i = 0; $i < $fullStars; $i++): ?><i class="fas fa-star"></i><?php endfor; ?>
                <?php if ($halfStar): ?><i class="fas fa-star-half-alt"></i><?php endif; ?>
                <?php for ($i = 0; $i < $emptyStars; $i++): ?><i class="far fa-star"></i><?php endfor; ?>
            </span>
            <span class="text-muted small ms-1">(<?= number_format($rating, 1) ?>)</span>
        </div>
        <?php endif; ?>

        <!-- Sub-ratings (compact) -->
        <?php
        $subRatings = [];
        if (!empty($review->rating_effectiveness)) $subRatings['Effectiveness'] = floatval($review->rating_effectiveness);
        if (!empty($review->rating_value)) $subRatings['Value'] = floatval($review->rating_value);
        if (!empty($review->rating_ingredients)) $subRatings['Ingredients'] = floatval($review->rating_ingredients);
        ?>
        <?php if (!empty($subRatings)): ?>
        <div class="mb-3 small">
            <?php foreach ($subRatings as $label => $val): ?>
            <div class="d-flex align-items-center mb-1">
                <span class="text-muted me-2" style="min-width:85px"><?= $label ?></span>
                <div class="progress flex-grow-1" style="height:6px">
                    <div class="progress-bar bg-success" style="width:<?= ($val / 5) * 100 ?>%"></div>
                </div>
                <span class="text-muted ms-2"><?= number_format($val, 1) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Price + CTA -->
        <div class="mt-auto pt-2">
            <?php if (!empty($review->price)): ?>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="h5 fw-bold text-dark mb-0"><?= htmlspecialchars($review->price) ?></span>
                <?php if (!empty($review->author_name)): ?>
                <span class="text-muted small">by <?= htmlspecialchars($review->author_name) ?></span>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            <div class="d-grid">
                <a href="<?= $reviewUrl ?>" class="btn btn-success fw-bold"><?= htmlspecialchars($cta) ?> <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
</div>
