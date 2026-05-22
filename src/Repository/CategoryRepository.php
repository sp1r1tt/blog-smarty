<?php

declare(strict_types=1);

// Репозиторий категорий: SQL-запросы к таблице categories.

namespace App\Repository;

use PDO;

class CategoryRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id, name, description FROM categories WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();

        return $row ?: null;
    }

    public function findAllHavingArticles(): array
    {
        $sql = "
            SELECT c.id, c.name, c.description
            FROM categories c
            WHERE EXISTS (
                SELECT 1
                FROM article_category ac
                WHERE ac.category_id = c.id
            )
            ORDER BY c.name ASC
        ";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll();
    }
}
