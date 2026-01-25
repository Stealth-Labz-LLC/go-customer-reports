-- Migration: WordPress Casino Posts → content_reviews
-- Migrates 146 "casino" custom post type entries (product reviews for skincare/beauty)
-- to the content_reviews table
--
-- Run these queries in order on your WordPress/target database

-- ============================================
-- STEP 1: Basic Post Data + All Custom Fields
-- ============================================
INSERT INTO content_reviews (
    site_id,
    slug,
    name,
    short_description,
    content,
    affiliate_url,
    cta_text,
    rating_overall,
    rating_ingredients,
    rating_value,
    rating_effectiveness,
    rating_customer_experience,
    status,
    published_at,
    author_name,
    created_at,
    updated_at
)
SELECT
    1 AS site_id,
    p.post_name AS slug,
    p.post_title AS name,
    -- Short description (strip HTML tags for clean text)
    (SELECT REGEXP_REPLACE(meta_value, '<[^>]*>', '')
     FROM wp_postmeta
     WHERE post_id = p.ID AND meta_key = 'casino_short_desc'
     LIMIT 1) AS short_description,
    p.post_content AS content,
    -- Affiliate URL
    (SELECT meta_value FROM wp_postmeta WHERE post_id = p.ID AND meta_key = 'casino_external_link' LIMIT 1) AS affiliate_url,
    -- CTA Button Text
    COALESCE(
        (SELECT meta_value FROM wp_postmeta WHERE post_id = p.ID AND meta_key = 'casino_button_title' LIMIT 1),
        'Check Availability'
    ) AS cta_text,
    -- Ratings
    (SELECT CAST(meta_value AS DECIMAL(2,1)) FROM wp_postmeta WHERE post_id = p.ID AND meta_key = 'casino_overall_rating' LIMIT 1) AS rating_overall,
    (SELECT CAST(meta_value AS DECIMAL(2,1)) FROM wp_postmeta WHERE post_id = p.ID AND meta_key = 'casino_rating_trust' LIMIT 1) AS rating_ingredients,
    (SELECT CAST(meta_value AS DECIMAL(2,1)) FROM wp_postmeta WHERE post_id = p.ID AND meta_key = 'casino_rating_bonus' LIMIT 1) AS rating_value,
    (SELECT CAST(meta_value AS DECIMAL(2,1)) FROM wp_postmeta WHERE post_id = p.ID AND meta_key = 'casino_rating_games' LIMIT 1) AS rating_effectiveness,
    (SELECT CAST(meta_value AS DECIMAL(2,1)) FROM wp_postmeta WHERE post_id = p.ID AND meta_key = 'casino_rating_customer' LIMIT 1) AS rating_customer_experience,
    -- Status
    CASE
        WHEN p.post_status = 'publish' THEN 'published'
        WHEN p.post_status = 'draft' THEN 'draft'
        WHEN p.post_status = 'future' THEN 'scheduled'
        ELSE 'draft'
    END AS status,
    CASE WHEN p.post_status = 'publish' THEN p.post_date ELSE NULL END AS published_at,
    u.display_name AS author_name,
    p.post_date AS created_at,
    p.post_modified AS updated_at
FROM wp_posts p
LEFT JOIN wp_users u ON p.post_author = u.ID
WHERE p.post_type = 'casino'
  AND p.post_status IN ('publish', 'draft', 'future')
ON DUPLICATE KEY UPDATE
    name = VALUES(name),
    short_description = VALUES(short_description),
    content = VALUES(content),
    affiliate_url = VALUES(affiliate_url),
    cta_text = VALUES(cta_text),
    rating_overall = VALUES(rating_overall),
    rating_ingredients = VALUES(rating_ingredients),
    rating_value = VALUES(rating_value),
    rating_effectiveness = VALUES(rating_effectiveness),
    rating_customer_experience = VALUES(rating_customer_experience),
    status = VALUES(status),
    published_at = VALUES(published_at),
    updated_at = VALUES(updated_at);


-- ============================================
-- STEP 2: Featured Images
-- ============================================
-- Resolves _thumbnail_id to actual image URLs
UPDATE content_reviews cr
JOIN (
    SELECT
        p.post_name AS slug,
        CONCAT(
            'https://customer-reports.org/wp-content/uploads/',
            am.meta_value
        ) AS featured_image
    FROM wp_posts p
    JOIN wp_postmeta pm ON p.ID = pm.post_id AND pm.meta_key = '_thumbnail_id'
    JOIN wp_postmeta am ON pm.meta_value = am.post_id AND am.meta_key = '_wp_attached_file'
    WHERE p.post_type = 'casino'
) img ON cr.slug = img.slug
SET cr.featured_image = img.featured_image
WHERE cr.site_id = 1;


-- ============================================
-- STEP 3: Extract Price from casino_terms_desc
-- ============================================
-- The casino_terms_desc contains HTML like: "<p>Starting at<br /><strong>$99.43</strong>..."
-- This extracts the price value
UPDATE content_reviews cr
JOIN (
    SELECT
        p.post_name AS slug,
        -- Extract price pattern like $XX.XX or $XXX.XX
        REGEXP_SUBSTR(pm.meta_value, '\\$[0-9]+\\.?[0-9]*') AS price
    FROM wp_posts p
    JOIN wp_postmeta pm ON p.ID = pm.post_id AND pm.meta_key = 'casino_terms_desc'
    WHERE p.post_type = 'casino'
      AND pm.meta_value LIKE '%$%'
) pricing ON cr.slug = pricing.slug
SET cr.price = pricing.price
WHERE cr.site_id = 1
  AND pricing.price IS NOT NULL;


-- ============================================
-- STEP 4: Yoast SEO Meta Data
-- ============================================
UPDATE content_reviews cr
JOIN (
    SELECT
        p.post_name AS slug,
        MAX(CASE WHEN pm.meta_key = '_yoast_wpseo_title' THEN pm.meta_value END) AS meta_title,
        MAX(CASE WHEN pm.meta_key = '_yoast_wpseo_metadesc' THEN pm.meta_value END) AS meta_description
    FROM wp_posts p
    JOIN wp_postmeta pm ON p.ID = pm.post_id
    WHERE p.post_type = 'casino'
      AND pm.meta_key IN ('_yoast_wpseo_title', '_yoast_wpseo_metadesc')
    GROUP BY p.post_name
) seo ON cr.slug = seo.slug
SET
    cr.meta_title = NULLIF(seo.meta_title, ''),
    cr.meta_description = NULLIF(seo.meta_description, '')
WHERE cr.site_id = 1;


-- ============================================
-- VERIFICATION QUERIES
-- ============================================

-- Check migration summary
SELECT
    COUNT(*) as total_reviews,
    SUM(CASE WHEN featured_image IS NOT NULL THEN 1 ELSE 0 END) as with_images,
    SUM(CASE WHEN meta_title IS NOT NULL THEN 1 ELSE 0 END) as with_meta_title,
    SUM(CASE WHEN rating_overall IS NOT NULL THEN 1 ELSE 0 END) as with_ratings,
    SUM(CASE WHEN affiliate_url IS NOT NULL THEN 1 ELSE 0 END) as with_affiliate_urls,
    SUM(CASE WHEN price IS NOT NULL THEN 1 ELSE 0 END) as with_prices,
    SUM(CASE WHEN short_description IS NOT NULL THEN 1 ELSE 0 END) as with_descriptions
FROM content_reviews
WHERE site_id = 1;

-- Sample of migrated reviews
SELECT
    id,
    slug,
    name,
    rating_overall,
    price,
    LEFT(affiliate_url, 50) as affiliate_url,
    cta_text,
    status
FROM content_reviews
WHERE site_id = 1
ORDER BY published_at DESC
LIMIT 20;

-- Check rating distribution
SELECT
    rating_overall,
    COUNT(*) as count
FROM content_reviews
WHERE site_id = 1
GROUP BY rating_overall
ORDER BY rating_overall DESC;
