<footer class="bg-dark text-white pt-5 pb-3 mt-5">
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
                    <li class="mb-2"><a href="<?= BASE_URL ?>/search" class="text-white-50 text-decoration-none">Search</a></li>
                    <li class="mb-2"><a href="<?= BASE_URL ?>/about-us" class="text-white-50 text-decoration-none">About Us</a></li>
                </ul>
            </div>

            <!-- Legal Links -->
            <div class="col-6 col-lg-2">
                <h6 class="fw-bold mb-3">Legal</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="<?= BASE_URL ?>/contact" class="text-white-50 text-decoration-none">Contact</a></li>
                    <li class="mb-2"><a href="<?= BASE_URL ?>/privacy" class="text-white-50 text-decoration-none">Privacy Policy</a></li>
                    <li class="mb-2"><a href="<?= BASE_URL ?>/terms" class="text-white-50 text-decoration-none">Terms &amp; Conditions</a></li>
                </ul>
            </div>

            <!-- Newsletter Signup -->
            <div class="col-lg-4">
                <h6 class="fw-bold mb-3"><i class="fas fa-envelope me-2"></i>Stay Updated</h6>
                <p class="text-white-50 small">Get the latest reviews and buying guides delivered to your inbox.</p>
                <form id="newsletterForm" class="mb-2">
                    <div class="input-group input-group-sm mb-2">
                        <input type="text" name="name" class="form-control" placeholder="Your name" required>
                    </div>
                    <div class="input-group input-group-sm">
                        <input type="email" name="email" class="form-control" placeholder="Your email" required>
                        <button type="submit" class="btn btn-success"><i class="fas fa-paper-plane"></i> Subscribe</button>
                    </div>
                </form>
                <div id="newsletterMsg" class="small" style="display:none;"></div>
                <p class="text-white-50 small mt-1 mb-0" style="font-size:.7rem;">By subscribing you agree to our <a href="<?= BASE_URL ?>/privacy" class="text-white-50">Privacy Policy</a>. Unsubscribe anytime.</p>
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
    formData.append('source', 'footer_newsletter');
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
        btn.innerHTML = '<i class="fas fa-paper-plane"></i> Subscribe';
    });
});
</script>
