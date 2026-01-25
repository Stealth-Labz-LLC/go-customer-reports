-- Migration: WordPress Casino Posts → content_reviews
-- This migrates the 146 "casino" custom post type entries (which are actually product reviews)
-- to the content_reviews table

-- Step 1: Basic migration of post data
-- Run this first to get all reviews into the system
INSERT INTO content_reviews (
    site_id,
    slug,
    name,
    short_description,
    content,
    status,
    published_at,
    author_name,
    created_at,
    updated_at
)
SELECT
    1 AS site_id,  -- Assuming site_id 1 for customer-reports.org
    p.post_name AS slug,
    p.post_title AS name,
    NULLIF(p.post_excerpt, '') AS short_description,
    p.post_content AS content,
    CASE
        WHEN p.post_status = 'publish' THEN 'published'
        WHEN p.post_status = 'draft' THEN 'draft'
        WHEN p.post_status = 'future' THEN 'scheduled'
        ELSE 'draft'
    END AS status,
    CASE
        WHEN p.post_status = 'publish' THEN p.post_date
        ELSE NULL
    END AS published_at,
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
    status = VALUES(status),
    published_at = VALUES(published_at),
    updated_at = VALUES(updated_at);

-- Step 2: Update featured images
-- This joins through the attachment metadata to get the actual image URLs
UPDATE content_reviews cr
JOIN (
    SELECT
        p.post_name AS slug,
        CONCAT(
            (SELECT option_value FROM wp_options WHERE option_name = 'siteurl'),
            '/wp-content/uploads/',
            am.meta_value
        ) AS featured_image
    FROM wp_posts p
    JOIN wp_postmeta pm ON p.ID = pm.post_id AND pm.meta_key = '_thumbnail_id'
    JOIN wp_postmeta am ON pm.meta_value = am.post_id AND am.meta_key = '_wp_attached_file'
    WHERE p.post_type = 'casino'
) img ON cr.slug = img.slug
SET cr.featured_image = img.featured_image
WHERE cr.site_id = 1;

-- Step 3: Yoast SEO Meta Data
-- Import meta titles and descriptions from Yoast
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
-- CUSTOM FIELDS MAPPING (NEEDS VERIFICATION)
-- ============================================
-- The following queries need to be updated based on your actual meta_key names.
-- Run this query first to see what custom fields exist:
--
-- SELECT DISTINCT pm.meta_key, LEFT(pm.meta_value, 100) as sample_value
-- FROM wp_postmeta pm
-- JOIN wp_posts p ON pm.post_id = p.ID
-- WHERE p.post_type = 'casino'
--   AND pm.meta_key NOT LIKE '\_%'
--   AND pm.meta_key NOT LIKE 'rank_math%'
--   AND pm.meta_key NOT LIKE '_yoast%'
-- ORDER BY pm.meta_key;

-- Once you identify the meta keys, update these placeholders:

-- Example: Update ratings (replace 'your_rating_key' with actual meta_key)
/*
UPDATE content_reviews cr
JOIN (
    SELECT
        p.post_name AS slug,
        MAX(CASE WHEN pm.meta_key = 'overall_rating' THEN pm.meta_value END) AS rating_overall,
        MAX(CASE WHEN pm.meta_key = 'ingredients_rating' THEN pm.meta_value END) AS rating_ingredients,
        MAX(CASE WHEN pm.meta_key = 'value_rating' THEN pm.meta_value END) AS rating_value,
        MAX(CASE WHEN pm.meta_key = 'effectiveness_rating' THEN pm.meta_value END) AS rating_effectiveness,
        MAX(CASE WHEN pm.meta_key = 'experience_rating' THEN pm.meta_value END) AS rating_customer_experience
    FROM wp_posts p
    JOIN wp_postmeta pm ON p.ID = pm.post_id
    WHERE p.post_type = 'casino'
    GROUP BY p.post_name
) ratings ON cr.slug = ratings.slug
SET
    cr.rating_overall = ratings.rating_overall,
    cr.rating_ingredients = ratings.rating_ingredients,
    cr.rating_value = ratings.rating_value,
    cr.rating_effectiveness = ratings.rating_effectiveness,
    cr.rating_customer_experience = ratings.rating_customer_experience
WHERE cr.site_id = 1;
*/

-- Example: Update affiliate URLs and prices
/*
UPDATE content_reviews cr
JOIN (
    SELECT
        p.post_name AS slug,
        MAX(CASE WHEN pm.meta_key = 'affiliate_link' THEN pm.meta_value END) AS affiliate_url,
        MAX(CASE WHEN pm.meta_key = 'product_price' THEN pm.meta_value END) AS price,
        MAX(CASE WHEN pm.meta_key = 'brand_name' THEN pm.meta_value END) AS brand
    FROM wp_posts p
    JOIN wp_postmeta pm ON p.ID = pm.post_id
    WHERE p.post_type = 'casino'
    GROUP BY p.post_name
) meta ON cr.slug = meta.slug
SET
    cr.affiliate_url = meta.affiliate_url,
    cr.price = meta.price,
    cr.brand = meta.brand
WHERE cr.site_id = 1;
*/

-- ============================================
-- VERIFICATION QUERIES
-- ============================================

-- Check migration results
SELECT
    COUNT(*) as total_reviews,
    SUM(CASE WHEN featured_image IS NOT NULL THEN 1 ELSE 0 END) as with_images,
    SUM(CASE WHEN meta_title IS NOT NULL THEN 1 ELSE 0 END) as with_meta_title,
    SUM(CASE WHEN rating_overall IS NOT NULL THEN 1 ELSE 0 END) as with_ratings,
    SUM(CASE WHEN affiliate_url IS NOT NULL THEN 1 ELSE 0 END) as with_affiliate_urls
FROM content_reviews
WHERE site_id = 1;

-- List all migrated reviews
SELECT id, slug, name, status, rating_overall, published_at
FROM content_reviews
WHERE site_id = 1
ORDER BY published_at DESC;
