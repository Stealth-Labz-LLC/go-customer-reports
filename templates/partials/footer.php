<!-- Footer CTA -->
<section class="footer-cta">
    <div class="container-xl">
        <div class="row align-items-center g-4">
            <div class="col-lg-6">
                <h2 class="h4 fw-bold text-white mb-3">Get Smarter Buying Decisions — Free</h2>
                <p class="footer-cta-sub mb-4">Join thousands of readers who rely on our expert analysis. We'll send you our best picks, new reviews, and buying guides every week.</p>
                <ul class="footer-cta-perks">
                    <li><i class="fas fa-check-circle"></i> Weekly top picks from <?= number_format($totalArticles ?? 0) ?>+ expert articles</li>
                    <li><i class="fas fa-check-circle"></i> Early access to new product ratings &amp; reviews</li>
                    <li><i class="fas fa-check-circle"></i> AI-powered buying recommendations</li>
                </ul>
            </div>
            <div class="col-lg-5 offset-lg-1">
                <div class="footer-cta-form-panel">
                    <form id="newsletterForm">
                        <div class="mb-3">
                            <input type="text" name="name" class="form-control form-control-lg" placeholder="Your name" required>
                        </div>
                        <div class="mb-3">
                            <input type="email" name="email" class="form-control form-control-lg" placeholder="Your email" required>
                        </div>
                        <button type="submit" class="btn btn-amber btn-lg w-100 fw-bold">Subscribe — It's Free <i class="fas fa-arrow-right ms-2"></i></button>
                    </form>
                    <div id="newsletterMsg" class="small mt-2" style="display:none;"></div>
                    <p class="footer-cta-privacy">No spam, ever. Unsubscribe anytime. <a href="<?= BASE_URL ?>/privacy">Privacy Policy</a></p>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="bg-dark text-white pt-5 pb-3">
    <div class="container-xl">
        <div class="row g-4 mb-4">
            <!-- Brand & About -->
            <div class="col-lg-4">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-chart-bar me-2"></i><?= htmlspecialchars($site->name) ?>
                </h5>
                <p class="text-white-50 small"><?= htmlspecialchars($site->tagline ?? 'Honest Reviews You Can Trust') ?></p>
                <p class="text-white-50 small mb-0">Expert reviews and unbiased recommendations to help you make confident buying decisions.</p>
            </div>

            <!-- Quick Links -->
            <div class="col-6 col-lg-2">
                <h6 class="fw-bold mb-3">Explore</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="<?= BASE_URL ?>/" class="text-white-50 text-decoration-none">Home</a></li>
                    <li class="mb-2"><a href="<?= BASE_URL ?>/categories" class="text-white-50 text-decoration-none">Categories</a></li>
                    <li class="mb-2"><a href="<?= BASE_URL ?>/reviews" class="text-white-50 text-decoration-none">Reviews</a></li>
                    <li class="mb-2"><a href="<?= BASE_URL ?>/search" class="text-white-50 text-decoration-none">Search</a></li>
                    <li class="mb-2"><a href="<?= BASE_URL ?>/about-us" class="text-white-50 text-decoration-none">About Us</a></li>
                </ul>
            </div>

            <!-- Legal Links -->
            <div class="col-6 col-lg-2">
                <h6 class="fw-bold mb-3">Legal</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="<?= BASE_URL ?>/privacy" class="text-white-50 text-decoration-none">Privacy Policy</a></li>
                    <li class="mb-2"><a href="<?= BASE_URL ?>/terms" class="text-white-50 text-decoration-none">Terms &amp; Conditions</a></li>
                </ul>
            </div>

            <!-- Trust Signals -->
            <div class="col-lg-4">
                <h6 class="fw-bold mb-3">Why Trust Us</h6>
                <div class="d-flex flex-column gap-2">
                    <div class="footer-trust-item">
                        <i class="fas fa-shield-alt text-teal"></i>
                        <span class="text-white-50 small">Independent, unbiased reviews — no pay-to-play</span>
                    </div>
                    <div class="footer-trust-item">
                        <i class="fas fa-brain text-teal"></i>
                        <span class="text-white-50 small">AI-powered analysis across <?= number_format($totalArticles ?? 0) ?>+ data points</span>
                    </div>
                    <div class="footer-trust-item">
                        <i class="fas fa-user-check text-teal"></i>
                        <span class="text-white-50 small">Expert-verified by subject-matter specialists</span>
                    </div>
                    <div class="footer-trust-item">
                        <i class="fas fa-star text-amber"></i>
                        <span class="text-white-50 small"><?= number_format($totalReviews ?? 0) ?>+ product reviews and counting</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <hr class="border-secondary">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <span class="text-white-50 small">&copy; <?= date('Y') ?> <?= htmlspecialchars($site->name) ?>. All Rights Reserved.</span>
            <a href="#" class="text-white-50 text-decoration-none small" onclick="window.scrollTo({top:0,behavior:'smooth'});return false;">Back to top <i class="fas fa-arrow-up"></i></a>
        </div>
    </div>
</footer>

<!-- Newsletter JS -->
<script>
document.getElementById('newsletterForm').addEventListener('submit', function(e) {
    e.preventDefault();
    var form = this;
    var msg = document.getElementById('newsletterMsg');
    var name = form.querySelector('[name="name"]').value.trim();
    var email = form.querySelector('[name="email"]').value.trim();

    if (!name || !email) {
        msg.style.display = 'block';
        msg.className = 'small text-danger';
        msg.textContent = 'Please enter your name and email.';
        return;
    }

    var btn = form.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    var formData = new FormData();
    formData.append('name', name);
    formData.append('email', email);
    formData.append('phone', '');
    formData.append('campaign', 'newsletter');
    formData.append('source', 'footer_cta');
    formData.append('consent', '1');

    fetch('<?= BASE_URL ?>/api/submit.php', {
        method: 'POST',
        body: formData
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        msg.style.display = 'block';
        msg.className = 'small text-success';
        msg.textContent = 'Thanks for subscribing! Check your inbox.';
        form.reset();
    })
    .catch(function() {
        msg.style.display = 'block';
        msg.className = 'small text-success';
        msg.textContent = 'Thanks for subscribing!';
        form.reset();
    })
    .finally(function() {
        btn.disabled = false;
        btn.innerHTML = 'Subscribe — It\'s Free <i class="fas fa-arrow-right ms-2"></i>';
    });
});
</script>
