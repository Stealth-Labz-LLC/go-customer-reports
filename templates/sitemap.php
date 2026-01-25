<?= '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>https://<?= htmlspecialchars($site->domain) ?></loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>https://<?= htmlspecialchars($site->domain) ?>/categories</loc>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
<?php foreach ($categories as $item): ?>
    <url>
        <loc>https://<?= htmlspecialchars($site->domain) ?>/category/<?= htmlspecialchars($item->slug) ?></loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
<?php endforeach; ?>
<?php foreach ($articles as $item): ?>
<?php if (!empty($item->category_slug)): ?>
    <url>
        <loc>https://<?= htmlspecialchars($site->domain) ?>/category/<?= htmlspecialchars($item->category_slug) ?>/<?= htmlspecialchars($item->slug) ?></loc>
        <lastmod><?= date('c', strtotime($item->updated_at)) ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
<?php endif; ?>
<?php endforeach; ?>
<?php foreach ($reviews as $item): ?>
<?php if (!empty($item->category_slug)): ?>
    <url>
        <loc>https://<?= htmlspecialchars($site->domain) ?>/category/<?= htmlspecialchars($item->category_slug) ?>/reviews/<?= htmlspecialchars($item->slug) ?></loc>
        <lastmod><?= date('c', strtotime($item->updated_at)) ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
<?php endif; ?>
<?php endforeach; ?>
<?php foreach ($listicles as $item): ?>
<?php if (!empty($item->category_slug)): ?>
    <url>
        <loc>https://<?= htmlspecialchars($site->domain) ?>/category/<?= htmlspecialchars($item->category_slug) ?>/top/<?= htmlspecialchars($item->slug) ?></loc>
        <lastmod><?= date('c', strtotime($item->updated_at)) ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
<?php endif; ?>
<?php endforeach; ?>
</urlset>
