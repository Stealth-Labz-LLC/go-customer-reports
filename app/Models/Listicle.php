<?php

namespace App\Models;

use App\Core\Database;

class Listicle
{
    public static function findBySlug(int $siteId, string $slug): ?object
    {
        $db = Database::getInstance();
        return $db->fetchOne(
            "SELECT * FROM content_listicles WHERE site_id = ? AND slug = ? AND status = 'published'",
            [$siteId, $slug]
        );
    }

    public static function latest(int $siteId, int $limit = 12, int $offset = 0): array
    {
        $db = Database::getInstance();
        return $db->fetchAll(
            "SELECT l.*, c.slug as category_slug, c.name as category_name
             FROM content_listicles l
             LEFT JOIN content_categories c ON l.primary_category_id = c.id
             WHERE l.site_id = ? AND l.status = 'published'
             ORDER BY l.published_at DESC LIMIT ? OFFSET ?",
            [$siteId, $limit, $offset]
        );
    }

    public static function count(int $siteId): int
    {
        $db = Database::getInstance();
        $result = $db->fetchOne(
            "SELECT COUNT(*) as total FROM content_listicles WHERE site_id = ? AND status = 'published'",
            [$siteId]
        );
        return (int) ($result->total ?? 0);
    }

    public static function byCategory(int $siteId, int $categoryId, int $limit = 12): array
    {
        $db = Database::getInstance();
        return $db->fetchAll(
            "SELECT l.*, c.slug as category_slug, c.name as category_name
             FROM content_listicles l
             LEFT JOIN content_categories c ON l.primary_category_id = c.id
             JOIN content_listicle_category lc ON l.id = lc.listicle_id
             WHERE l.site_id = ? AND lc.category_id = ? AND l.status = 'published'
             ORDER BY l.published_at DESC LIMIT ?",
            [$siteId, $categoryId, $limit]
        );
    }

    public static function search(int $siteId, string $query, ?int $categoryId = null, int $limit = 24, int $offset = 0): array
    {
        $db = Database::getInstance();

        if ($categoryId) {
            return $db->fetchAll(
                "SELECT l.*, c.slug as category_slug, c.name as category_name,
                        MATCH(l.title, l.excerpt) AGAINST(? IN NATURAL LANGUAGE MODE) as relevance
                 FROM content_listicles l
                 LEFT JOIN content_categories c ON l.primary_category_id = c.id
                 JOIN content_listicle_category lc ON l.id = lc.listicle_id
                 WHERE l.site_id = ? AND lc.category_id = ? AND l.status = 'published'
                   AND MATCH(l.title, l.excerpt) AGAINST(? IN NATURAL LANGUAGE MODE)
                 ORDER BY relevance DESC LIMIT ? OFFSET ?",
                [$query, $siteId, $categoryId, $query, $limit, $offset]
            );
        }

        return $db->fetchAll(
            "SELECT l.*, c.slug as category_slug, c.name as category_name,
                    MATCH(l.title, l.excerpt) AGAINST(? IN NATURAL LANGUAGE MODE) as relevance
             FROM content_listicles l
             LEFT JOIN content_categories c ON l.primary_category_id = c.id
             WHERE l.site_id = ? AND l.status = 'published'
               AND MATCH(l.title, l.excerpt) AGAINST(? IN NATURAL LANGUAGE MODE)
             ORDER BY relevance DESC LIMIT ? OFFSET ?",
            [$query, $siteId, $query, $limit, $offset]
        );
    }

    public static function searchCount(int $siteId, string $query, ?int $categoryId = null): int
    {
        $db = Database::getInstance();

        if ($categoryId) {
            $result = $db->fetchOne(
                "SELECT COUNT(*) as total
                 FROM content_listicles l
                 JOIN content_listicle_category lc ON l.id = lc.listicle_id
                 WHERE l.site_id = ? AND lc.category_id = ? AND l.status = 'published'
                   AND MATCH(l.title, l.excerpt) AGAINST(? IN NATURAL LANGUAGE MODE)",
                [$siteId, $categoryId, $query]
            );
        } else {
            $result = $db->fetchOne(
                "SELECT COUNT(*) as total
                 FROM content_listicles l
                 WHERE l.site_id = ? AND l.status = 'published'
                   AND MATCH(l.title, l.excerpt) AGAINST(? IN NATURAL LANGUAGE MODE)",
                [$siteId, $query]
            );
        }

        return (int) ($result->total ?? 0);
    }
}
