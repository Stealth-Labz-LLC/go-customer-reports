<?php /** Expects: $review */ ?>
<div class="cr-review-card h-100">
    <?php if (!empty($review->featured_image)): ?>
    <a href="/reviews/<?= htmlspecialchars($review->slug) ?>" class="cr-review-card-img">
        <img src="<?= htmlspecialchars($review->featured_image) ?>" alt="<?= htmlspecialchars($review->name) ?>">
    </a>
    <?php else: ?>
    <a href="/reviews/<?= htmlspecialchars($review->slug) ?>" class="cr-review-card-img cr-review-card-placeholder">
        <i class="fas fa-box"></i>
    </a>
    <?php endif; ?>
    <div class="cr-review-card-body">
        <?php if (!empty($review->brand)): ?>
        <div class="cr-review-card-brand"><?= htmlspecialchars($review->brand) ?></div>
        <?php endif; ?>
        <h3 class="cr-review-card-title">
            <a href="/reviews/<?= htmlspecialchars($review->slug) ?>"><?= htmlspecialchars($review->name) ?></a>
        </h3>
        <?php if ($review->rating_overall): ?>
        <div class="cr-review-card-rating">
            <span class="cr-rating-score"><?= number_format(floatval($review->rating_overall), 1) ?></span>
            <div class="cr-rating-stars">
                <?php
                $rating = floatval($review->rating_overall);
                $fullStars = floor($rating);
                $hasHalf = ($rating - $fullStars) >= 0.5;
                for ($i = 1; $i <= 5; $i++): ?>
                <i class="fas fa-star<?= $i <= $fullStars ? ' filled' : ($i == $fullStars + 1 && $hasHalf ? '-half-alt filled' : '') ?>"></i>
                <?php endfor; ?>
            </div>
        </div>
        <?php endif; ?>
        <?php if (!empty($review->short_description)): ?>
        <p class="cr-review-card-desc"><?= htmlspecialchars(mb_substr($review->short_description, 0, 100)) ?>...</p>
        <?php endif; ?>
        <div class="cr-review-card-footer">
            <?php if (!empty($review->price)): ?>
            <span class="cr-review-card-price"><?= htmlspecialchars($review->price) ?></span>
            <?php endif; ?>
            <a href="/reviews/<?= htmlspecialchars($review->slug) ?>" class="cr-btn-sm">Read Review</a>
        </div>
    </div>
</div>
