<?php
$pageTitle = ($listicle->meta_title ?? $listicle->title) . ' | ' . $site->name;
$metaDescription = $listicle->meta_description ?? ($listicle->excerpt ?? '');
$ogImage = $listicle->featured_image ?? null;
$ogType = 'article';

// Build ItemList schema for listicle
$itemListElements = [];
if (!empty($listicle->items)) {
    foreach ($listicle->items as $index => $item) {
        $listItem = [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'item' => [
                '@type' => 'Product',
                'name' => $item['name'] ?? '',
            ]
        ];
        if (!empty($item['rating'])) {
            $listItem['item']['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => number_format(floatval($item['rating']), 1),
                'bestRating' => '10',
                'worstRating' => '1',
                'ratingCount' => '1'
            ];
        }
        if (!empty($item['image']) || !empty($item['product_image'])) {
            $listItem['item']['image'] = $item['product_image'] ?? $item['image'];
        }
        if (!empty($item['affiliate_url'])) {
            $listItem['item']['offers'] = [
                '@type' => 'Offer',
                'url' => $item['affiliate_url'],
                'availability' => 'https://schema.org/InStock'
            ];
        }
        $itemListElements[] = $listItem;
    }
}

$schemaData = [
    '@context' => 'https://schema.org',
    '@type' => 'ItemList',
    'name' => $listicle->title,
    'description' => $listicle->excerpt ?? $listicle->meta_description ?? '',
    'numberOfItems' => count($listicle->items ?? []),
    'itemListElement' => $itemListElements
];

ob_start();
?>

<!-- Schema.org ItemList structured data -->
<script type="application/ld+json">
<?= json_encode($schemaData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?>
</script>

<?php if (!empty($breadcrumbs)): ?>
    <?php include __DIR__ . '/../partials/breadcrumbs.php'; ?>
<?php endif; ?>

<!-- Listicle Header -->
<section class="bg-dark text-white py-5">
    <div class="container-xl">
        <h1 class="fw-bold mb-2"><?= htmlspecialchars($listicle->title) ?></h1>
        <?php if (!empty($listicle->excerpt)): ?>
        <p class="lead text-white-50 mb-3"><?= htmlspecialchars($listicle->excerpt) ?></p>
        <?php endif; ?>
        <div class="d-flex flex-wrap align-items-center gap-3 small">
            <?php if ($listicle->published_at): ?>
            <span class="text-white-50"><i class="far fa-calendar-alt me-1"></i> Updated <?= date('F Y', strtotime($listicle->published_at)) ?></span>
            <?php endif; ?>
            <a href="#advertiserDisclosureModal" data-bs-toggle="modal" class="text-white-50 text-decoration-none"><i class="fas fa-info-circle me-1"></i> Advertiser disclosure</a>
            <a href="#aboutRankingsModal" data-bs-toggle="modal" class="text-white-50 text-decoration-none"><i class="fas fa-chart-bar me-1"></i> About our rankings</a>
        </div>
    </div>
</section>

<div class="container-xl py-4">
    <div class="row">
        <!-- LEFT COLUMN - Product Cards -->
        <div class="col-lg-9">
            <!-- Introduction -->
            <?php if (!empty($listicle->introduction)): ?>
            <div class="mb-4 article-content">
                <?= $listicle->introduction ?>
            </div>
            <?php endif; ?>

            <!-- Product Cards -->
            <?php if (!empty($listicle->items)): ?>
            <?php foreach ($listicle->items as $index => $item): ?>
            <?php $rank = $item['rank'] ?? ($index + 1); ?>
            <div class="card mb-4 border-0 shadow-sm overflow-hidden listicle-item-card">
                <div class="card-body p-0">
                    <!-- Badge Banner -->
                    <?php if (!empty($item['badge'])): ?>
                    <div class="bg-success text-white py-1 px-3 small fw-bold">
                        <i class="fas fa-award me-1"></i> <?= htmlspecialchars($item['badge']) ?>
                    </div>
                    <?php endif; ?>

                    <div class="p-3 p-md-4">
                        <div class="row align-items-center">
                            <!-- Rank + Logo -->
                            <div class="col-auto text-center">
                                <div class="listicle-rank"><?= $rank ?></div>
                                <?php if (!empty($item['brand_logo'])): ?>
                                <img src="<?= htmlspecialchars($item['brand_logo']) ?>" alt="<?= htmlspecialchars($item['name'] ?? '') ?>" class="mt-2 rounded" style="max-width:80px;max-height:60px;object-fit:contain;">
                                <?php elseif (!empty($item['image'])): ?>
                                <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name'] ?? '') ?>" class="mt-2 rounded" style="max-width:80px;max-height:60px;object-fit:contain;">
                                <?php endif; ?>
                            </div>

                            <!-- Name + Rating + Features -->
                            <div class="col">
                                <!-- Rating -->
                                <?php if (!empty($item['rating'])): ?>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-success fs-6"><?= number_format($item['rating'], 1) ?></span>
                                    <span class="text-warning">
                                        <?php
                                        $r = floatval($item['rating']);
                                        $full = round($r / 2);
                                        for ($i = 1; $i <= 5; $i++):
                                        ?><i class="fas fa-star<?= $i <= $full ? '' : ' text-muted opacity-25' ?>"></i><?php endfor; ?>
                                    </span>
                                </div>
                                <?php endif; ?>

                                <h3 class="h5 fw-bold mb-2"><?= htmlspecialchars($item['name'] ?? '') ?></h3>

                                <?php if (!empty($item['savings'])): ?>
                                <span class="badge bg-warning text-dark mb-2"><i class="fas fa-tag me-1"></i> Save <?= htmlspecialchars($item['savings']) ?></span>
                                <?php endif; ?>

                                <!-- Feature Bullets -->
                                <?php if (!empty($item['features']) && is_array($item['features'])): ?>
                                <ul class="list-unstyled small text-muted mb-0">
                                    <?php foreach ($item['features'] as $feature): ?>
                                    <li class="mb-1"><i class="fas fa-check text-success me-1"></i> <?= htmlspecialchars($feature) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php endif; ?>

                                <?php if (!empty($item['trust_signal'])): ?>
                                <div class="text-muted small mt-2"><i class="fas fa-shield-alt me-1"></i> <?= htmlspecialchars($item['trust_signal']) ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Product Image -->
                            <?php if (!empty($item['product_image'])): ?>
                            <div class="col-auto d-none d-md-block">
                                <?php if (!empty($item['affiliate_url'])): ?>
                                <a href="<?= htmlspecialchars($item['affiliate_url']) ?>" target="_blank" rel="nofollow sponsored">
                                <?php endif; ?>
                                    <img src="<?= htmlspecialchars($item['product_image']) ?>" alt="<?= htmlspecialchars($item['name'] ?? '') ?>" class="rounded" style="max-width:120px;max-height:100px;object-fit:contain;">
                                <?php if (!empty($item['affiliate_url'])): ?>
                                </a>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>

                            <!-- CTA -->
                            <div class="col-12 col-md-auto mt-3 mt-md-0 text-center">
                                <?php if (!empty($item['affiliate_url'])): ?>
                                <a href="<?= htmlspecialchars($item['affiliate_url']) ?>" target="_blank" rel="nofollow sponsored" class="btn btn-success fw-bold px-4">
                                    <?= htmlspecialchars($item['cta_text'] ?? 'Check Price') ?> <i class="fas fa-external-link-alt ms-1"></i>
                                </a>
                                <div class="mt-1">
                                    <a href="<?= htmlspecialchars($item['affiliate_url']) ?>" target="_blank" rel="nofollow sponsored" class="text-success text-decoration-none small">Read Full Review</a>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

            <!-- Comparison Grid -->
            <?php if (count($listicle->items) >= 2): ?>
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-dark text-white fw-bold">
                    <i class="fas fa-columns me-2"></i> Compare Features
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th></th>
                                    <?php foreach (array_slice($listicle->items, 0, 5) as $idx => $item): ?>
                                    <th class="text-center small">
                                        <span class="badge bg-dark rounded-pill me-1"><?= $idx + 1 ?></span>
                                        <?= htmlspecialchars($item['name'] ?? '') ?>
                                        <?php if (!empty($item['product_image']) || !empty($item['brand_logo'])): ?>
                                        <br><img src="<?= htmlspecialchars($item['product_image'] ?? $item['brand_logo'] ?? '') ?>" alt="" style="max-width:50px;max-height:35px;object-fit:contain;" class="mt-1">
                                        <?php endif; ?>
                                    </th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fw-bold small">Rating</td>
                                    <?php foreach (array_slice($listicle->items, 0, 5) as $item): ?>
                                    <td class="text-center"><?= !empty($item['rating']) ? '<span class="badge bg-success">' . number_format($item['rating'], 1) . '</span>' : '-' ?></td>
                                    <?php endforeach; ?>
                                </tr>
                                <?php
                                $specKeys = [];
                                foreach ($listicle->items as $item) {
                                    if (!empty($item['specs']) && is_array($item['specs'])) {
                                        $specKeys = array_keys($item['specs']);
                                        break;
                                    }
                                }
                                foreach (array_slice($specKeys, 0, 4) as $specKey):
                                ?>
                                <tr>
                                    <td class="fw-bold small"><?= htmlspecialchars($specKey) ?></td>
                                    <?php foreach (array_slice($listicle->items, 0, 5) as $item): ?>
                                    <td class="text-center small"><?= htmlspecialchars($item['specs'][$specKey] ?? '-') ?></td>
                                    <?php endforeach; ?>
                                </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td></td>
                                    <?php foreach (array_slice($listicle->items, 0, 5) as $item): ?>
                                    <td class="text-center">
                                        <?php if (!empty($item['affiliate_url'])): ?>
                                        <a href="<?= htmlspecialchars($item['affiliate_url']) ?>" target="_blank" rel="nofollow sponsored" class="btn btn-success btn-sm">Check Price</a>
                                        <?php endif; ?>
                                    </td>
                                    <?php endforeach; ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php endif; ?>

            <!-- Research Stats -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="card border-0 bg-light text-center p-3">
                        <i class="fas fa-box fa-lg text-success mb-2"></i>
                        <div class="h4 fw-bold mb-0"><?= count($listicle->items ?? []) ?></div>
                        <div class="text-muted small">Models Evaluated</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 bg-light text-center p-3">
                        <i class="fas fa-list-ul fa-lg text-success mb-2"></i>
                        <div class="h4 fw-bold mb-0">5</div>
                        <div class="text-muted small">Topics Considered</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 bg-light text-center p-3">
                        <i class="fas fa-clock fa-lg text-success mb-2"></i>
                        <div class="h4 fw-bold mb-0">15+</div>
                        <div class="text-muted small">Hours of Research</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 bg-light text-center p-3">
                        <i class="fas fa-shopping-cart fa-lg text-success mb-2"></i>
                        <div class="h4 fw-bold mb-0">1,000+</div>
                        <div class="text-muted small">Purchases Analyzed</div>
                    </div>
                </div>
            </div>

            <!-- Buyer's Guide -->
            <?php if (!empty($listicle->buyers_guide)): ?>
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-bold"><i class="fas fa-book me-2 text-success"></i> Buyer's Guide</div>
                <div class="card-body article-content">
                    <?= $listicle->buyers_guide ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- FAQs -->
            <?php if (!empty($listicle->faqs)): ?>
            <?php $faqs = is_string($listicle->faqs) ? json_decode($listicle->faqs, true) : $listicle->faqs; ?>
            <?php if (!empty($faqs) && is_array($faqs)): ?>
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-bold"><i class="fas fa-question-circle me-2 text-success"></i> Frequently Asked Questions</div>
                <div class="card-body p-0">
                    <div class="accordion accordion-flush" id="faqAccordion">
                        <?php foreach ($faqs as $fi => $faq): ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq<?= $fi ?>">
                                    <?= htmlspecialchars($faq['question'] ?? '') ?>
                                </button>
                            </h2>
                            <div id="faq<?= $fi ?>" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    <?= htmlspecialchars($faq['answer'] ?? '') ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <?php endif; ?>

            <!-- About Us -->
            <div class="card border-0 bg-light mb-4">
                <div class="card-body">
                    <h3 class="h5 fw-bold">About <?= htmlspecialchars($site->name) ?></h3>
                    <p class="text-muted mb-0"><?= htmlspecialchars($site->name) ?> helps consumers make informed purchasing decisions. We analyze thousands of products, compare features and specifications, and provide unbiased recommendations to help you find the best products for your needs. Our team of experts spends hours researching and testing products so you don't have to.</p>
                </div>
            </div>

            <!-- Conclusion -->
            <?php if (!empty($listicle->conclusion)): ?>
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-bold"><i class="fas fa-flag-checkered me-2 text-success"></i> Final Thoughts</div>
                <div class="card-body article-content">
                    <?= $listicle->conclusion ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- RIGHT COLUMN - Sidebar -->
        <div class="col-lg-3">
            <?php require __DIR__ . '/../partials/listicle-sidebar.php'; ?>
        </div>
    </div>
</div>

<!-- Advertiser Disclosure Modal -->
<div class="modal fade" id="advertiserDisclosureModal" tabindex="-1" aria-labelledby="advertiserDisclosureModalTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="advertiserDisclosureModalTitle">Advertiser Disclosure</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><?= htmlspecialchars($site->name) ?> is an independent, advertising-supported comparison website. Products and services that appear on <?= htmlspecialchars($site->name) ?> are from companies from which <?= htmlspecialchars($site->name) ?> receives compensation. As an Amazon Associate, we earn from qualifying purchases. This compensation may impact the location and order in which these products appear. <?= htmlspecialchars($site->name) ?> takes into consideration a number of proprietary rules to determine how and where products appear on the site. <?= htmlspecialchars($site->name) ?> does not include the entire universe of available product choices.</p>
            </div>
        </div>
    </div>
</div>

<!-- About Our Rankings Modal -->
<div class="modal fade" id="aboutRankingsModal" tabindex="-1" aria-labelledby="aboutRankingsModalTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="aboutRankingsModalTitle">About Our Rankings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Our editors review products objectively based on the features offered to consumers, the price and delivery options, how a product compares with other products in its category, and other factors. The ratings are based on the expert opinion of our editors and on underlying technology that analyzes decisions made by similar users to provide individual, targeted recommendations to each person who visits our site.</p>
            </div>
        </div>
    </div>
</div>

<?php
$__content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
