<?php

declare(strict_types=1);

// Репозиторий статей: SQL-запросы к таблицам articles и связке article_category.

namespace App\Repository;

use PDO;

class ArticleRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function findByCategory(int $categoryId, string $sortKey, int $page, int $perPage): array
    {
        $allowedSort = [
            'date' => 'a.created_at DESC',
            'views' => 'a.views DESC',
        ];

        $orderBy = $allowedSort[$sortKey] ?? $allowedSort['date'];
        $page = max(1, $page);
        $perPage = max(1, $perPage);
        $offset = ($page - 1) * $perPage;

        $sql = "
            SELECT a.id, a.image, a.title, a.description, a.views, a.created_at
            FROM articles a
            INNER JOIN article_category ac ON ac.article_id = a.id
            WHERE ac.category_id = :category_id
            ORDER BY {$orderBy}
            LIMIT :offset, :per_page
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':per_page', $perPage, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function countByCategory(int $categoryId): int
    {
        $sql = "
            SELECT COUNT(*) AS cnt
            FROM articles a
            INNER JOIN article_category ac ON ac.article_id = a.id
            WHERE ac.category_id = :category_id
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();

        return (int) ($row['cnt'] ?? 0);
    }

    public function findLatestByCategory(int $categoryId, int $limit): array
    {
        $sql = "
            SELECT a.id, a.image, a.title, a.description, a.views, a.created_at
            FROM articles a
            INNER JOIN article_category ac ON ac.article_id = a.id
            WHERE ac.category_id = :category_id
            ORDER BY a.created_at DESC
            LIMIT :limit
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', max(1, $limit), PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, image, title, description, content, views, created_at FROM articles WHERE id = :id'
        );
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();

        return $row ?: null;
    }

    public function incrementViews(int $id): void
    {
        $stmt = $this->pdo->prepare('UPDATE articles SET views = views + 1 WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function findSimilar(int $articleId, int $limit): array
    {
        $sql = "
            SELECT DISTINCT a2.id, a2.image, a2.title, a2.description, a2.views, a2.created_at
            FROM article_category ac1
            INNER JOIN article_category ac2 ON ac2.category_id = ac1.category_id
            INNER JOIN articles a2 ON a2.id = ac2.article_id
            WHERE ac1.article_id = :article_id
              AND a2.id <> :article_id
            ORDER BY a2.created_at DESC
            LIMIT :limit
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':article_id', $articleId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', max(1, $limit), PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
