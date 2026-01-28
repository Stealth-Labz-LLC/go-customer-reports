<header class="cr-header">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="<?= BASE_URL ?>/">
                <img src="<?= BASE_URL ?>/images/logo.svg" alt="<?= htmlspecialchars($site->name) ?>" height="32" class="cr-logo-inverted">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/categories">Categories</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/about-us">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/contact">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>
</header>
