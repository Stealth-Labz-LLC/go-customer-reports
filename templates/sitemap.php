<?= '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>https://<?= htmlspecialchars($site->domain) ?></loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
<?php foreach ($articles as $item): ?>
    <url>
        <loc>https://<?= htmlspecialchars($site->domain) ?>/articles/<?= htmlspecialchars($item->slug) ?></loc>
        <lastmod><?= date('c', strtotime($item->updated_at)) ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
<?php endforeach; ?>
<?php foreach ($reviews as $item): ?>
    <url>
        <loc>https://<?= htmlspecialchars($site->domain) ?>/reviews/<?= htmlspecialchars($item->slug) ?></loc>
        <lastmod><?= date('c', strtotime($item->updated_at)) ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
<?php endforeach; ?>
<?php foreach ($listicles as $item): ?>
    <url>
        <loc>https://<?= htmlspecialchars($site->domain) ?>/best-<?= htmlspecialchars($item->slug) ?></loc>
        <lastmod><?= date('c', strtotime($item->updated_at)) ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
<?php endforeach; ?>
<?php foreach ($categories as $item): ?>
    <url>
        <loc>https://<?= htmlspecialchars($site->domain) ?>/category/<?= htmlspecialchars($item->slug) ?></loc>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
<?php endforeach; ?>
</urlset>
