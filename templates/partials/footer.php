<footer class="cr-footer">
    <div class="container">
        <div class="cr-footer-icon">
            <i class="fas fa-shield-alt"></i>
        </div>
        <div class="cr-footer-logo"><?= htmlspecialchars($site->name) ?></div>
        <p class="mb-0" style="font-size: 0.9rem; opacity: 0.7;"><?= htmlspecialchars($site->tagline ?? '') ?></p>
        <div class="cr-footer-links">
            <a href="/articles">Articles</a>
            <a href="/reviews">Reviews</a>
            <a href="/about">About</a>
            <a href="/contact">Contact</a>
            <a href="/terms">Terms</a>
            <a href="/privacy">Privacy</a>
        </div>
        <div class="cr-footer-copyright">
            &copy; Copyright <?= date('Y') ?> <?= htmlspecialchars($site->name) ?> | All Rights Reserved.
        </div>
    </div>
</footer>
