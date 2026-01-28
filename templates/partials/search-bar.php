<?php
/**
 * Reusable search bar component
 * Expects: $searchQuery (optional), $searchCategory (optional slug), $size (optional: 'lg' or default)
 */
$searchQuery = $searchQuery ?? '';
$searchCategory = $searchCategory ?? '';
$size = $size ?? '';
$inputClass = $size === 'lg' ? 'form-control form-control-lg' : 'form-control';
$btnClass = $size === 'lg' ? 'btn btn-success btn-lg' : 'btn btn-success';
?>
<form action="<?= BASE_URL ?>/search" method="GET" class="d-flex gap-2">
    <input type="search" name="q" class="<?= $inputClass ?>" placeholder="Search articles, reviews, guides..." value="<?= htmlspecialchars($searchQuery) ?>">
    <?php if ($searchCategory): ?>
    <input type="hidden" name="category" value="<?= htmlspecialchars($searchCategory) ?>">
    <?php endif; ?>
    <button type="submit" class="<?= $btnClass ?>"><i class="fas fa-search"></i> Search</button>
</form>
