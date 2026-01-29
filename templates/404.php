<?php
$pageTitle = 'Page Not Found | ' . $site->name;
$metaDescription = '';
ob_start();
?>

<!-- 404 Hero -->
<section class="hero-section-simple text-white">
    <div class="container-xl text-center">
        <div class="display-1 fw-bold mb-3">404</div>
        <h1 class="h3 mb-3">Page Not Found</h1>
        <p class="text-white-50 mb-4">The page you're looking for doesn't exist or has been moved.</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="<?= BASE_URL ?>/" class="btn btn-success">Back to Home</a>
            <a href="<?= BASE_URL ?>/categories" class="btn btn-outline-light">Browse Categories</a>
        </div>
    </div>
</section>

<?php
$__content = ob_get_clean();
require __DIR__ . '/layouts/app.php';
