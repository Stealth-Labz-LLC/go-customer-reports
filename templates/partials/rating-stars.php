<?php
/** Expects: $review->rating_overall (or can be used standalone with $rating) */
$_rating = $rating ?? ($review->rating_overall ?? 0);
$_fullStars = floor($_rating);
$_emptyStars = 5 - $_fullStars;
?>
<span class="text-warning">
    <?php for ($i = 0; $i < $_fullStars; $i++): ?>&#9733;<?php endfor; ?>
    <?php for ($i = 0; $i < $_emptyStars; $i++): ?>&#9734;<?php endfor; ?>
</span>
<span class="small fw-semibold ms-1"><?= number_format($_rating, 1) ?></span>
