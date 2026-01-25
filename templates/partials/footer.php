<footer class="cr-footer">
    <!-- Back to Top -->
    <div class="cr-back-to-top">
        <a href="#top" class="cr-back-to-top-btn">Back to Top</a>
    </div>

    <div class="container">
        <div class="cr-footer-content">
            <div class="cr-footer-logo">
                <i class="fas fa-chart-bar"></i>
                <span><?= htmlspecialchars($site->name) ?></span>
            </div>
            <p class="cr-footer-tagline"><?= htmlspecialchars($site->tagline ?? 'Honest Reviews You Can Trust') ?></p>

            <nav class="cr-footer-legal">
                <a href="/contact">Contact</a>
                <span>|</span>
                <a href="/privacy">Privacy Policy</a>
                <span>|</span>
                <a href="/terms">Terms and Conditions</a>
            </nav>

            <div class="cr-footer-copyright">
                &copy; <?= date('Y') ?> <?= htmlspecialchars($site->name) ?>. All Rights Reserved.
            </div>
        </div>
    </div>
</footer>
