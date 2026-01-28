<?php
$pageTitle = ($page->meta_title ?? $page->title) . ' | ' . $site->name;
$metaDescription = $page->meta_description ?? '';
ob_start();
?>

<div class="container-xl">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="fw-bold mb-4"><?= htmlspecialchars($page->title) ?></h1>
            <div class="page-content">
                <?= $page->content ?>
            </div>
        </div>
    </div>
</div>

<?php
$__content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
