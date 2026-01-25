<?php

namespace App\Models;

use App\Core\Database;

class Category
{
    public static function find(int $id): ?object
    {
        $db = Database::getInstance();
        return $db->fetchOne(
            "SELECT * FROM content_categories WHERE id = ?",
            [$id]
        );
    }

    public static function findBySlug(int $siteId, string $slug): ?object
    {
        $db = Database::getInstance();
        return $db->fetchOne(
            "SELECT * FROM content_categories WHERE site_id = ? AND slug = ?",
            [$siteId, $slug]
        );
    }

    public static function all(int $siteId): array
    {
        $db = Database::getInstance();
        return $db->fetchAll(
            "SELECT * FROM content_categories WHERE site_id = ? ORDER BY sort_order, name",
            [$siteId]
        );
    }

    public static function topLevel(int $siteId): array
    {
        $db = Database::getInstance();
        return $db->fetchAll(
            "SELECT * FROM content_categories WHERE site_id = ? AND parent_id IS NULL ORDER BY sort_order, name",
            [$siteId]
        );
    }

    public static function allWithCounts(int $siteId): array
    {
        $db = Database::getInstance();
        return $db->fetchAll(
            "SELECT c.*,
                (SELECT COUNT(*) FROM content_article_category ac WHERE ac.category_id = c.id) AS article_count,
                (SELECT COUNT(*) FROM content_review_category rc WHERE rc.category_id = c.id) AS review_count,
                (SELECT COUNT(*) FROM content_listicle_category lc WHERE lc.category_id = c.id) AS listicle_count
            FROM content_categories c
            WHERE c.site_id = ?
            ORDER BY c.sort_order, c.name",
            [$siteId]
        );
    }
}
