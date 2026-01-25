-- Discovery Queries for WordPress Casino Post Meta Fields
-- Run these on your WordPress database to identify custom field names

-- Query 1: Get ALL non-internal meta keys used by casino posts
-- This shows what custom fields your theme/ACF created
SELECT
    pm.meta_key,
    COUNT(*) as usage_count,
    LEFT(MAX(pm.meta_value), 100) as sample_value
FROM wp_postmeta pm
JOIN wp_posts p ON pm.post_id = p.ID
WHERE p.post_type = 'casino'
  AND pm.meta_key NOT LIKE '\_%'           -- Exclude internal WordPress fields
  AND pm.meta_key NOT LIKE 'rank_math%'    -- Exclude Rank Math SEO
  AND pm.meta_value != ''                  -- Exclude empty values
GROUP BY pm.meta_key
ORDER BY usage_count DESC, pm.meta_key;

-- Query 2: Get a full dump of ONE casino post's meta data
-- Replace 859 with any casino post ID from your database
SELECT
    pm.meta_key,
    pm.meta_value
FROM wp_postmeta pm
WHERE pm.post_id = 859
  AND pm.meta_value != ''
ORDER BY pm.meta_key;

-- Query 3: Find meta keys that might be ratings (look for numeric values 1-5)
SELECT DISTINCT
    pm.meta_key,
    pm.meta_value
FROM wp_postmeta pm
JOIN wp_posts p ON pm.post_id = p.ID
WHERE p.post_type = 'casino'
  AND pm.meta_value REGEXP '^[0-9]\.?[0-9]?$'
  AND CAST(pm.meta_value AS DECIMAL(2,1)) BETWEEN 0 AND 5
LIMIT 50;

-- Query 4: Find meta keys that might be URLs (affiliate links)
SELECT DISTINCT
    pm.meta_key,
    LEFT(pm.meta_value, 100) as sample_url
FROM wp_postmeta pm
JOIN wp_posts p ON pm.post_id = p.ID
WHERE p.post_type = 'casino'
  AND pm.meta_value LIKE 'http%'
LIMIT 50;

-- Query 5: Find meta keys that might be prices
SELECT DISTINCT
    pm.meta_key,
    pm.meta_value as sample_price
FROM wp_postmeta pm
JOIN wp_posts p ON pm.post_id = p.ID
WHERE p.post_type = 'casino'
  AND (pm.meta_value LIKE '$%' OR pm.meta_value REGEXP '^[0-9]+\.?[0-9]*$')
  AND LENGTH(pm.meta_value) < 20
LIMIT 50;

-- Query 6: List all casino posts with their IDs for reference
SELECT
    ID,
    post_title,
    post_name AS slug,
    post_status,
    post_date
FROM wp_posts
WHERE post_type = 'casino'
ORDER BY post_date DESC
LIMIT 20;
