<?php
$pageTitle = 'Page Not Found | ' . $site->name;
$metaDescription = '';
ob_start();
?>

<div class="container text-center py-5">
    <h1 class="display-1 fw-bold text-muted">404</h1>
    <p class="fs-4 text-muted mb-4">Page not found</p>
    <a href="/" class="btn btn-primary">Back to Home</a>
</div>

<?php
$__content = ob_get_clean();
require __DIR__ . '/layouts/app.php';
