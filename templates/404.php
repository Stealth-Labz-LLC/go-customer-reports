<?php
$pageTitle = 'Page Not Found | ' . $site->name;
$metaDescription = '';
ob_start();
?>

<div class="cr-error-page">
    <div class="container">
        <div class="cr-error-content">
            <div class="cr-error-code">404</div>
            <h1 class="cr-error-title">Page Not Found</h1>
            <p class="cr-error-message">The page you're looking for doesn't exist or has been moved.</p>
            <div class="cr-error-actions">
                <a href="<?= BASE_URL ?>/" class="cr-btn cr-btn-primary">Back to Home</a>
                <a href="<?= BASE_URL ?>/categories" class="cr-btn cr-btn-outline">Browse Categories</a>
            </div>
        </div>
    </div>
</div>

<?php
$__content = ob_get_clean();
require __DIR__ . '/layouts/app.php';
