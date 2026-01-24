<?php $headerStyle = $site->config['header_style'] ?? 'left'; ?>
<header class="border-bottom bg-white">
    <div class="container">
        <?php if ($headerStyle === 'centered'): ?>
        <div class="text-center py-4">
            <a href="/" class="text-decoration-none">
                <span class="fs-3 fw-bold" style="color: var(--color-primary)"><?= htmlspecialchars($site->name) ?></span>
            </a>
            <nav class="mt-3">
                <a href="/" class="me-3 text-decoration-none">Home</a>
                <a href="/articles" class="me-3 text-decoration-none">Articles</a>
                <a href="/reviews" class="me-3 text-decoration-none">Reviews</a>
                <a href="/about" class="me-3 text-decoration-none">About</a>
                <a href="/contact" class="text-decoration-none">Contact</a>
            </nav>
        </div>
        <?php else: ?>
        <div class="d-flex justify-content-between align-items-center py-3">
            <a href="/" class="text-decoration-none">
                <span class="fs-4 fw-bold" style="color: var(--color-primary)"><?= htmlspecialchars($site->name) ?></span>
            </a>
            <nav class="d-none d-md-flex gap-3">
                <a href="/" class="text-decoration-none">Home</a>
                <a href="/articles" class="text-decoration-none">Articles</a>
                <a href="/reviews" class="text-decoration-none">Reviews</a>
                <a href="/about" class="text-decoration-none">About</a>
                <a href="/contact" class="text-decoration-none">Contact</a>
            </nav>
        </div>
        <?php endif; ?>
    </div>
</header>
