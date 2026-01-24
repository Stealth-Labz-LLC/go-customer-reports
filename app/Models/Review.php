<?php

namespace App\Models;

use App\Core\Database;

class Review
{
    public static function findBySlug(int $siteId, string $slug): ?object
    {
        $db = Database::getInstance();
        return $db->fetchOne(
            "SELECT * FROM content_reviews WHERE site_id = ? AND slug = ? AND status = 'published'",
            [$siteId, $slug]
        );
    }

    public static function latest(int $siteId, int $limit = 12, int $offset = 0): array
    {
        $db = Database::getInstance();
        return $db->fetchAll(
            "SELECT * FROM content_reviews WHERE site_id = ? AND status = 'published' ORDER BY published_at DESC LIMIT ? OFFSET ?",
            [$siteId, $limit, $offset]
        );
    }

    public static function byCategory(int $siteId, int $categoryId, int $limit = 12): array
    {
        $db = Database::getInstance();
        return $db->fetchAll(
            "SELECT r.* FROM content_reviews r
             JOIN content_review_category rc ON r.id = rc.review_id
             WHERE r.site_id = ? AND rc.category_id = ? AND r.status = 'published'
             ORDER BY r.published_at DESC LIMIT ?",
            [$siteId, $categoryId, $limit]
        );
    }

    public static function count(int $siteId): int
    {
        $db = Database::getInstance();
        $result = $db->fetchOne(
            "SELECT COUNT(*) as total FROM content_reviews WHERE site_id = ? AND status = 'published'",
            [$siteId]
        );
        return (int) ($result->total ?? 0);
    }

    public static function getCategories(int $reviewId): array
    {
        $db = Database::getInstance();
        return $db->fetchAll(
            "SELECT c.* FROM content_categories c
             JOIN content_review_category rc ON c.id = rc.category_id
             WHERE rc.review_id = ?",
            [$reviewId]
        );
    }
}
