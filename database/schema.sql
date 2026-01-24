-- Customer Reports Content Database Schema
-- Run this on your cPanel MySQL database

-- Sites table (for future multi-domain support)
CREATE TABLE IF NOT EXISTS content_sites (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    domain VARCHAR(255) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    tagline VARCHAR(255) NULL,
    niche VARCHAR(100) NULL,
    gtm_container_id VARCHAR(50) NULL,
    config JSON NOT NULL,
    meta_defaults JSON NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_domain (domain),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Categories
CREATE TABLE IF NOT EXISTS content_categories (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    site_id BIGINT UNSIGNED NOT NULL,
    slug VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    parent_id BIGINT UNSIGNED NULL,
    meta_title VARCHAR(255) NULL,
    meta_description TEXT NULL,
    featured_image VARCHAR(500) NULL,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY site_slug (site_id, slug),
    FOREIGN KEY (site_id) REFERENCES content_sites(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_id) REFERENCES content_categories(id) ON DELETE SET NULL,
    INDEX idx_parent (parent_id),
    INDEX idx_sort (sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Articles
CREATE TABLE IF NOT EXISTS content_articles (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    site_id BIGINT UNSIGNED NOT NULL,
    slug VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    excerpt TEXT NULL,
    content LONGTEXT NOT NULL,
    featured_image VARCHAR(500) NULL,
    meta_title VARCHAR(255) NULL,
    meta_description TEXT NULL,
    schema_markup JSON NULL,
    status ENUM('draft', 'published', 'scheduled', 'archived') DEFAULT 'draft',
    published_at TIMESTAMP NULL,
    author_name VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY site_slug (site_id, slug),
    FOREIGN KEY (site_id) REFERENCES content_sites(id) ON DELETE CASCADE,
    INDEX idx_status (status),
    INDEX idx_published (published_at),
    FULLTEXT idx_search (title, content)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Reviews
CREATE TABLE IF NOT EXISTS content_reviews (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    site_id BIGINT UNSIGNED NOT NULL,
    slug VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    brand VARCHAR(255) NULL,
    short_description TEXT NULL,
    featured_image VARCHAR(500) NULL,
    price VARCHAR(100) NULL,
    price_note VARCHAR(255) NULL,
    affiliate_url VARCHAR(500) NULL,
    cta_text VARCHAR(100) DEFAULT 'Check Availability',
    rating_overall DECIMAL(2,1) NULL,
    rating_ingredients DECIMAL(2,1) NULL,
    rating_value DECIMAL(2,1) NULL,
    rating_effectiveness DECIMAL(2,1) NULL,
    rating_customer_experience DECIMAL(2,1) NULL,
    pros JSON NULL,
    cons JSON NULL,
    specifications JSON NULL,
    content LONGTEXT NOT NULL,
    meta_title VARCHAR(255) NULL,
    meta_description TEXT NULL,
    schema_markup JSON NULL,
    status ENUM('draft', 'published', 'scheduled', 'archived') DEFAULT 'draft',
    published_at TIMESTAMP NULL,
    author_name VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY site_slug (site_id, slug),
    FOREIGN KEY (site_id) REFERENCES content_sites(id) ON DELETE CASCADE,
    INDEX idx_status (status),
    INDEX idx_rating (rating_overall),
    FULLTEXT idx_search (name, brand, content)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listicles
CREATE TABLE IF NOT EXISTS content_listicles (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    site_id BIGINT UNSIGNED NOT NULL,
    slug VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    excerpt TEXT NULL,
    introduction LONGTEXT NULL,
    conclusion LONGTEXT NULL,
    featured_image VARCHAR(500) NULL,
    items JSON NOT NULL,
    meta_title VARCHAR(255) NULL,
    meta_description TEXT NULL,
    schema_markup JSON NULL,
    status ENUM('draft', 'published', 'scheduled', 'archived') DEFAULT 'draft',
    published_at TIMESTAMP NULL,
    author_name VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY site_slug (site_id, slug),
    FOREIGN KEY (site_id) REFERENCES content_sites(id) ON DELETE CASCADE,
    INDEX idx_status (status),
    FULLTEXT idx_search (title, introduction, conclusion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Pages
CREATE TABLE IF NOT EXISTS content_pages (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    site_id BIGINT UNSIGNED NOT NULL,
    slug VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    content LONGTEXT NULL,
    template VARCHAR(100) DEFAULT 'default',
    config JSON NULL,
    meta_title VARCHAR(255) NULL,
    meta_description TEXT NULL,
    status ENUM('draft', 'published') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY site_slug (site_id, slug),
    FOREIGN KEY (site_id) REFERENCES content_sites(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Pivot: Article <-> Category
CREATE TABLE IF NOT EXISTS content_article_category (
    article_id BIGINT UNSIGNED NOT NULL,
    category_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (article_id, category_id),
    FOREIGN KEY (article_id) REFERENCES content_articles(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES content_categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Pivot: Review <-> Category
CREATE TABLE IF NOT EXISTS content_review_category (
    review_id BIGINT UNSIGNED NOT NULL,
    category_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (review_id, category_id),
    FOREIGN KEY (review_id) REFERENCES content_reviews(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES content_categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Pivot: Listicle <-> Category
CREATE TABLE IF NOT EXISTS content_listicle_category (
    listicle_id BIGINT UNSIGNED NOT NULL,
    category_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (listicle_id, category_id),
    FOREIGN KEY (listicle_id) REFERENCES content_listicles(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES content_categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed: customer-reports.org site
INSERT INTO content_sites (domain, name, tagline, niche, gtm_container_id, config) VALUES
('customer-reports.org', 'Customer Reports', 'Honest Reviews You Can Trust', 'reviews', 'GTM-5NRH7CBL', '{"theme_variant":"modern","header_style":"left","sidebar":"right","font_heading":"Merriweather","font_body":"Source Sans Pro","colors":{"primary":"#1e40af","secondary":"#3b82f6","accent":"#f59e0b","background":"#ffffff","text":"#1f2937"}}')
ON DUPLICATE KEY UPDATE name = VALUES(name);
