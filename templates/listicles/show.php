<?php
$pageTitle = ($listicle->meta_title ?? $listicle->title) . ' | ' . $site->name;
$metaDescription = $listicle->meta_description ?? ($listicle->excerpt ?? '');
ob_start();
?>

<!-- Top Navigation Bar -->
<div class="cr-navb">
    <div class="container">
        <div class="cr-navb-wrapper">
            <span class="cr-navb-header">
                <span>Categories</span>
                <i class="fas fa-angle-down"></i>
            </span>
            <div class="cr-navb-list">
                <a href="/" class="cr-navb-item">Best Overall</a>
                <?php
                $categories = \App\Models\Category::topLevel($site->id);
                foreach (array_slice($categories, 0, 4) as $cat):
                ?>
                <a href="/category/<?= htmlspecialchars($cat->slug) ?>" class="cr-navb-item"><?= htmlspecialchars($cat->name) ?></a>
                <?php endforeach; ?>
            </div>
            <div class="cr-navb-menu">
                <a href="/articles">Articles</a>
                <a href="/reviews">Reviews</a>
            </div>
        </div>
    </div>
</div>

<!-- Listicle Header -->
<section class="cr-listicle-header">
    <div class="container">
        <h1><?= htmlspecialchars($listicle->title) ?></h1>
        <?php if (!empty($listicle->excerpt)): ?>
        <p class="lead"><?= htmlspecialchars($listicle->excerpt) ?></p>
        <?php endif; ?>
        <div class="cr-header-meta">
            <?php if ($listicle->published_at): ?>
            <span class="updated-date">Updated <?= date('F Y', strtotime($listicle->published_at)) ?></span>
            <?php endif; ?>
            <div class="cr-disclosure-links">
                <a href="#advertiserDisclosureModal" data-bs-toggle="modal">Advertiser disclosure</a>
                <a href="#aboutRankingsModal" data-bs-toggle="modal">About our rankings</a>
            </div>
        </div>
    </div>
</section>

<div class="container py-4">
    <div class="row">
        <!-- LEFT COLUMN - Product Cards (col-lg-9) -->
        <div class="col-lg-9 col-md-12">
            <!-- Introduction -->
            <?php if (!empty($listicle->introduction)): ?>
            <div class="cr-listicle-intro">
                <?= $listicle->introduction ?>
            </div>
            <?php endif; ?>

            <!-- Product Cards -->
            <?php if (!empty($listicle->items)): ?>
            <div class="cr-listicle-items">
                <?php foreach ($listicle->items as $index => $item): ?>
                <?php
                $rank = $item['rank'] ?? ($index + 1);
                $itemSlug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $item['name'] ?? 'item-' . $index));
                ?>
                <div class="cr-item-card" data-rank="<?= $rank ?>" data-slug="<?= htmlspecialchars($itemSlug) ?>" data-index="<?= $index ?>">
                    <!-- Ordinal Badge -->
                    <div class="cr-ordinal"><?= $rank ?></div>

                    <!-- Banner Badge -->
                    <?php if (!empty($item['badge'])): ?>
                    <div class="cr-badge-banner">
                        <span><?= htmlspecialchars($item['badge']) ?></span>
                    </div>
                    <?php endif; ?>

                    <!-- Savings Badge -->
                    <?php if (!empty($item['savings'])): ?>
                    <div class="cr-savings-badge">
                        <span>Save <?= htmlspecialchars($item['savings']) ?></span>
                    </div>
                    <?php endif; ?>

                    <!-- Card Content Row -->
                    <div class="cr-item-row">
                        <!-- Left: Brand Logo -->
                        <div class="cr-logo-col">
                            <?php if (!empty($item['brand_logo'])): ?>
                            <img src="<?= htmlspecialchars($item['brand_logo']) ?>" alt="<?= htmlspecialchars($item['name'] ?? '') ?>" class="cr-brand-logo">
                            <?php elseif (!empty($item['image'])): ?>
                            <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name'] ?? '') ?>" class="cr-brand-logo">
                            <?php else: ?>
                            <div class="cr-brand-logo cr-logo-placeholder">
                                <i class="fas fa-box"></i>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Center: Rating + Name + Features -->
                        <div class="cr-content-col">
                            <!-- Rating Section -->
                            <?php if (!empty($item['rating'])): ?>
                            <div class="cr-rating-row">
                                <span class="cr-rating-num"><?= number_format($item['rating'], 1) ?></span>
                                <span class="cr-rating-stars">
                                    <?php
                                    $rating = floatval($item['rating']);
                                    $fullStars = round($rating / 2);
                                    for ($i = 1; $i <= 5; $i++):
                                    ?>
                                    <i class="fas fa-star<?= $i <= $fullStars ? ' filled' : '' ?>"></i>
                                    <?php endfor; ?>
                                </span>
                            </div>
                            <?php endif; ?>

                            <!-- Product Name -->
                            <div class="cr-product-details">
                                <strong><?= htmlspecialchars($item['name'] ?? '') ?></strong>

                                <!-- Feature Bullets -->
                                <?php if (!empty($item['features']) && is_array($item['features'])): ?>
                                <ul>
                                    <?php foreach ($item['features'] as $feature): ?>
                                    <li><?= htmlspecialchars($feature) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php endif; ?>
                            </div>

                            <!-- Read Full Review Link -->
                            <?php if (!empty($item['affiliate_url'])): ?>
                            <div class="cr-review-link">
                                <a href="<?= htmlspecialchars($item['affiliate_url']) ?>" target="_blank" rel="nofollow sponsored">Read Full Review</a>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Right: CTA Button + Trust Signal -->
                        <div class="cr-cta-col">
                            <?php if (!empty($item['affiliate_url'])): ?>
                            <a href="<?= htmlspecialchars($item['affiliate_url']) ?>" target="_blank" rel="nofollow sponsored" class="cr-cta-button">
                                <?= htmlspecialchars($item['cta_text'] ?? 'Check Price') ?>
                            </a>
                            <?php endif; ?>

                            <!-- Trust Signal -->
                            <?php if (!empty($item['trust_signal'])): ?>
                            <div class="cr-trust-signal">
                                <?= htmlspecialchars($item['trust_signal']) ?>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Far Right: Product Image -->
                        <?php if (!empty($item['product_image'])): ?>
                        <div class="cr-product-image-col">
                            <?php if (!empty($item['affiliate_url'])): ?>
                            <a href="<?= htmlspecialchars($item['affiliate_url']) ?>" target="_blank" rel="nofollow sponsored">
                            <?php endif; ?>
                                <img src="<?= htmlspecialchars($item['product_image']) ?>" alt="<?= htmlspecialchars($item['name'] ?? '') ?> Preview" class="cr-product-image">
                            <?php if (!empty($item['affiliate_url'])): ?>
                            </a>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Comparison Grid -->
            <?php if (count($listicle->items) >= 2): ?>
            <div class="cr-comparison-grid">
                <h2>Compare Features</h2>
                <div class="cr-grid-wrapper">
                    <div class="cr-grid-scroll">
                        <table class="cr-grid-table">
                            <thead>
                                <tr>
                                    <th class="cr-grid-label"></th>
                                    <?php foreach (array_slice($listicle->items, 0, 5) as $idx => $item): ?>
                                    <th class="cr-grid-product">
                                        <span class="cr-grid-rank"><?= $idx + 1 ?>.</span>
                                        <?= htmlspecialchars($item['name'] ?? '') ?>
                                        <?php if (!empty($item['product_image']) || !empty($item['brand_logo'])): ?>
                                        <img src="<?= htmlspecialchars($item['product_image'] ?? $item['brand_logo'] ?? '') ?>" alt="" class="cr-grid-thumb">
                                        <?php endif; ?>
                                    </th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="cr-grid-label">Rating</td>
                                    <?php foreach (array_slice($listicle->items, 0, 5) as $item): ?>
                                    <td><?= !empty($item['rating']) ? number_format($item['rating'], 1) : '-' ?></td>
                                    <?php endforeach; ?>
                                </tr>
                                <?php
                                // Get spec keys from first item that has specs
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
                                    <td class="cr-grid-label"><?= htmlspecialchars($specKey) ?></td>
                                    <?php foreach (array_slice($listicle->items, 0, 5) as $item): ?>
                                    <td><?= htmlspecialchars($item['specs'][$specKey] ?? '-') ?></td>
                                    <?php endforeach; ?>
                                </tr>
                                <?php endforeach; ?>
                                <tr class="cr-grid-cta-row">
                                    <td class="cr-grid-label"></td>
                                    <?php foreach (array_slice($listicle->items, 0, 5) as $item): ?>
                                    <td>
                                        <?php if (!empty($item['affiliate_url'])): ?>
                                        <a href="<?= htmlspecialchars($item['affiliate_url']) ?>" target="_blank" rel="nofollow sponsored" class="cr-grid-cta">
                                            Check Price
                                        </a>
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

            <!-- About Our Rankings Stats -->
            <div class="cr-rankings-stats">
                <div class="cr-stat-box">
                    <div class="stat-icon"><i class="fas fa-box"></i></div>
                    <span class="stat-number"><?= count($listicle->items ?? []) ?></span>
                    <span class="stat-label">Models Evaluated</span>
                </div>
                <div class="cr-stat-box">
                    <div class="stat-icon"><i class="fas fa-list-ul"></i></div>
                    <span class="stat-number">5</span>
                    <span class="stat-label">Topics Considered</span>
                </div>
                <div class="cr-stat-box">
                    <div class="stat-icon"><i class="fas fa-clock"></i></div>
                    <span class="stat-number">15+</span>
                    <span class="stat-label">Hours of Research</span>
                </div>
                <div class="cr-stat-box">
                    <div class="stat-icon"><i class="fas fa-shopping-cart"></i></div>
                    <span class="stat-number">1,000+</span>
                    <span class="stat-label">Purchases Analyzed</span>
                </div>
            </div>

            <!-- Buyer's Guide -->
            <?php if (!empty($listicle->buyers_guide)): ?>
            <div class="cr-buyers-guide">
                <h2>Buyer's Guide</h2>
                <div class="cr-buyers-guide-content">
                    <?= $listicle->buyers_guide ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- FAQs -->
            <?php if (!empty($listicle->faqs)): ?>
            <?php $faqs = is_string($listicle->faqs) ? json_decode($listicle->faqs, true) : $listicle->faqs; ?>
            <?php if (!empty($faqs) && is_array($faqs)): ?>
            <div class="cr-faqs">
                <h2>Frequently Asked Questions</h2>
                <?php foreach ($faqs as $faq): ?>
                <div class="cr-faq-item">
                    <div class="cr-faq-question"><?= htmlspecialchars($faq['question'] ?? '') ?></div>
                    <div class="cr-faq-answer"><?= htmlspecialchars($faq['answer'] ?? '') ?></div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <?php endif; ?>

            <!-- About Us Section -->
            <div class="cr-about-section">
                <h2>About <?= htmlspecialchars($site->name) ?></h2>
                <p><?= htmlspecialchars($site->name) ?> helps consumers make informed purchasing decisions. We analyze thousands of products, compare features and specifications, and provide unbiased recommendations to help you find the best products for your needs. Our team of experts spends hours researching and testing products so you don't have to.</p>
            </div>

            <!-- Conclusion -->
            <?php if (!empty($listicle->conclusion)): ?>
            <div class="cr-listicle-conclusion">
                <h2>Final Thoughts</h2>
                <?= $listicle->conclusion ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- RIGHT COLUMN - Sidebar (col-lg-3) -->
        <div class="col-lg-3 col-md-12">
            <div class="cr-sidebar">
                <?php require __DIR__ . '/../partials/listicle-sidebar.php'; ?>
            </div>
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
