<?php
/**
 * Main Layout Template
 * All pages are wrapped in this layout
 * Expects: $site, $pageTitle (optional), $metaDescription (optional)
 */
$config = $site->config ?? [];
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
    <link href="https://fonts.googleapis.com/css2?family=<?= urlencode($config['font_heading'] ?? 'Merriweather') ?>:wght@400;600;700&family=<?= urlencode($config['font_body'] ?? 'Source Sans Pro') ?>:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --color-primary: <?= $config['colors']['primary'] ?? '#1e40af' ?>;
            --color-secondary: <?= $config['colors']['secondary'] ?? '#3b82f6' ?>;
            --color-accent: <?= $config['colors']['accent'] ?? '#f59e0b' ?>;
            --color-background: <?= $config['colors']['background'] ?? '#ffffff' ?>;
            --color-text: <?= $config['colors']['text'] ?? '#1f2937' ?>;
            --font-heading: '<?= $config['font_heading'] ?? 'Merriweather' ?>', serif;
            --font-body: '<?= $config['font_body'] ?? 'Source Sans Pro' ?>', sans-serif;
        }
        body { font-family: var(--font-body); background: var(--color-background); color: var(--color-text); }
        h1, h2, h3, h4, h5, h6 { font-family: var(--font-heading); }
        a { color: var(--color-primary); }
        a:hover { color: var(--color-secondary); }
        .btn-primary { background-color: var(--color-primary); border-color: var(--color-primary); }
        .btn-primary:hover { background-color: var(--color-secondary); border-color: var(--color-secondary); }
        .text-accent { color: var(--color-accent); }
        .card { border: none; box-shadow: 0 1px 3px rgba(0,0,0,0.1); transition: box-shadow 0.2s; }
        .card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
    </style>

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

    <main class="py-4">
        <?= $__content ?? '' ?>
    </main>

    <?php require __DIR__ . '/../partials/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
