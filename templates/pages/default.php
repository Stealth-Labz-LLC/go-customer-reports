<?php
$pageTitle = ($page->meta_title ?? $page->title) . ' | ' . $site->name;
$metaDescription = $page->meta_description ?? '';
ob_start();
?>

<!-- Page Hero -->
<section class="hero-section-simple text-white">
    <div class="container-xl">
        <span class="section-eyebrow"><i class="fas fa-file-alt me-1"></i> <?= htmlspecialchars($page->title) ?></span>
        <h1 class="fw-bold mb-0"><?= htmlspecialchars($page->title) ?></h1>
    </div>
</section>

<div class="container-xl py-5">
    <div class="page-content article-content">
        <?= $page->content ?>
    </div>
</div>

<?php
$__content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
