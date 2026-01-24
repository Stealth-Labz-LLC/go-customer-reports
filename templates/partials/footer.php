<footer class="mt-5 py-5" style="background-color: var(--color-text); color: var(--color-background);">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold mb-3"><?= htmlspecialchars($site->name) ?></h5>
                <p class="opacity-75 small"><?= htmlspecialchars($site->tagline ?? '') ?></p>
            </div>
            <div class="col-md-2 mb-4">
                <h6 class="fw-bold mb-3">Content</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="/articles" class="text-decoration-none opacity-75" style="color: inherit;">Articles</a></li>
                    <li class="mb-2"><a href="/reviews" class="text-decoration-none opacity-75" style="color: inherit;">Reviews</a></li>
                </ul>
            </div>
            <div class="col-md-2 mb-4">
                <h6 class="fw-bold mb-3">Legal</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="/about" class="text-decoration-none opacity-75" style="color: inherit;">About</a></li>
                    <li class="mb-2"><a href="/contact" class="text-decoration-none opacity-75" style="color: inherit;">Contact</a></li>
                    <li class="mb-2"><a href="/terms" class="text-decoration-none opacity-75" style="color: inherit;">Terms</a></li>
                    <li class="mb-2"><a href="/privacy" class="text-decoration-none opacity-75" style="color: inherit;">Privacy</a></li>
                </ul>
            </div>
        </div>
        <div class="border-top pt-4 mt-3 text-center small opacity-50" style="border-color: rgba(255,255,255,0.2) !important;">
            &copy; <?= date('Y') ?> <?= htmlspecialchars($site->name) ?>. All rights reserved.
        </div>
    </div>
</footer>
