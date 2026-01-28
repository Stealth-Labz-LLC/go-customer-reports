<?php
$pageTitle = 'Page Not Found | ' . $site->name;
$metaDescription = '';
ob_start();
?>

<div class="container-xl py-5 text-center">
    <div class="display-1 fw-bold text-muted mb-3">404</div>
    <h1 class="h3">Page Not Found</h1>
    <p class="text-muted mb-4">The page you're looking for doesn't exist or has been moved.</p>
    <div class="d-flex justify-content-center gap-3">
        <a href="<?= BASE_URL ?>/" class="btn btn-success">Back to Home</a>
        <a href="<?= BASE_URL ?>/categories" class="btn btn-outline-success">Browse Categories</a>
    </div>
</div>

<?php
$__content = ob_get_clean();
require __DIR__ . '/layouts/app.php';
