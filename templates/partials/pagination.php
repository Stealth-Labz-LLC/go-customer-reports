<?php
/**
 * Reusable pagination component
 * Expects: $currentPage, $totalPages, $baseUrl (URL without page param)
 */
if ($totalPages <= 1) return;

$range = 2; // pages to show on each side of current
$showFirst = $currentPage > $range + 1;
$showLast = $currentPage < $totalPages - $range;
?>
<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
        <?php if ($currentPage > 1): ?>
        <li class="page-item">
            <a class="page-link" href="<?= htmlspecialchars($baseUrl . '&page=' . ($currentPage - 1)) ?>">&laquo; Prev</a>
        </li>
        <?php else: ?>
        <li class="page-item disabled"><span class="page-link">&laquo; Prev</span></li>
        <?php endif; ?>

        <?php if ($showFirst): ?>
        <li class="page-item"><a class="page-link" href="<?= htmlspecialchars($baseUrl . '&page=1') ?>">1</a></li>
        <?php if ($currentPage > $range + 2): ?>
        <li class="page-item disabled"><span class="page-link">&hellip;</span></li>
        <?php endif; ?>
        <?php endif; ?>

        <?php for ($i = max(1, $currentPage - $range); $i <= min($totalPages, $currentPage + $range); $i++): ?>
        <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
            <a class="page-link" href="<?= htmlspecialchars($baseUrl . '&page=' . $i) ?>"><?= $i ?></a>
        </li>
        <?php endfor; ?>

        <?php if ($showLast): ?>
        <?php if ($currentPage < $totalPages - $range - 1): ?>
        <li class="page-item disabled"><span class="page-link">&hellip;</span></li>
        <?php endif; ?>
        <li class="page-item"><a class="page-link" href="<?= htmlspecialchars($baseUrl . '&page=' . $totalPages) ?>"><?= $totalPages ?></a></li>
        <?php endif; ?>

        <?php if ($currentPage < $totalPages): ?>
        <li class="page-item">
            <a class="page-link" href="<?= htmlspecialchars($baseUrl . '&page=' . ($currentPage + 1)) ?>">Next &raquo;</a>
        </li>
        <?php else: ?>
        <li class="page-item disabled"><span class="page-link">Next &raquo;</span></li>
        <?php endif; ?>
    </ul>
</nav>
