<header class="sticky-top">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-2">
        <div class="container-xl">
            <a class="navbar-brand" href="<?= BASE_URL ?>/">
                <img src="<?= BASE_URL ?>/images/logo.svg" alt="<?= htmlspecialchars($site->name) ?>" height="32" style="filter: brightness(0) invert(1);">
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto me-3">
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/articles">Articles</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/reviews">Reviews</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/categories">Categories</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/about-us">About</a></li>
                </ul>
                <?php if (empty($hideHeaderSearch)): ?>
                <form action="<?= BASE_URL ?>/search" method="GET" class="d-flex">
                    <div class="input-group input-group-sm">
                        <input type="search" name="q" class="form-control bg-secondary bg-opacity-50 border-0 text-white" placeholder="Search..." aria-label="Search">
                        <button type="submit" class="btn btn-success"><i class="fas fa-search"></i></button>
                    </div>
                </form>
                <?php else: ?>
                <a href="<?= BASE_URL ?>/search" class="btn btn-outline-light btn-sm"><i class="fas fa-search"></i></a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</header>
