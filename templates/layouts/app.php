<?php
/**
 * Main Layout Template
 * All pages are wrapped in this layout
 * Expects: $site, $pageTitle (optional), $metaDescription (optional)
 */
$pageTitle = $pageTitle ?? $site->name;
$metaDescription = $metaDescription ?? ($site->tagline ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <meta name="description" content="<?= htmlspecialchars($metaDescription) ?>">

    <meta property="og:title" content="<?= htmlspecialchars($pageTitle) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($metaDescription) ?>">
    <meta property="og:site_name" content="<?= htmlspecialchars($site->name) ?>">
    <link rel="canonical" href="https://<?= htmlspecialchars($site->domain) ?><?= $_SERVER['REQUEST_URI'] ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">

    <?php if ($site->gtm_container_id): ?>
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','<?= htmlspecialchars($site->gtm_container_id) ?>');</script>
    <?php endif; ?>
</head>
<body>
    <?php if ($site->gtm_container_id): ?>
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?= htmlspecialchars($site->gtm_container_id) ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <?php endif; ?>

    <?php require __DIR__ . '/../partials/header.php'; ?>

    <main>
        <?= $__content ?? '' ?>
    </main>

    <?php require __DIR__ . '/../partials/footer.php'; ?>

    <!-- Cookie Consent Banner -->
    <div id="cookieBanner" class="cr-cookie-banner" style="display: none;">
        <div class="container">
            <div class="cr-cookie-content">
                <p>We use cookies to enhance your browsing experience and analyze site traffic. By continuing to use this site, you consent to our use of cookies.</p>
                <button type="button" class="cr-cookie-btn" onclick="acceptCookies()">OK</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Cookie Banner Logic
    (function() {
        if (!localStorage.getItem('cookiesAccepted')) {
            document.getElementById('cookieBanner').style.display = 'block';
        }
    })();
    function acceptCookies() {
        localStorage.setItem('cookiesAccepted', 'true');
        document.getElementById('cookieBanner').style.display = 'none';
    }
    </script>
</body>
</html>
