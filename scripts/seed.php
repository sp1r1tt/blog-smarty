<?php

declare(strict_types=1);

// Сидинг: наполнение таблиц тестовыми категориями/статьями и связями many-to-many.

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/src/Core/Database.php';

$root = dirname(__DIR__);
$envPath = $root . '/.env';
if (!is_file($envPath) && (getenv('DB_PASS') === false || getenv('DB_PASS') === '')) {
    fwrite(STDERR, "Не найден файл .env и не задана переменная DB_PASS. Создайте .env по образцу .env.example или задайте DB_* переменные окружения.\n");
}

$pdoConfig = require dirname(__DIR__) . '/config/database.php';
$pdo = null;
$attempts = 20;
for ($i = 1; $i <= $attempts; $i++) {
    try {
        $pdo = \App\Core\Database::connect($pdoConfig);
        break;
    } catch (\PDOException $e) {
        if ($i === $attempts) {
            throw $e;
        }
        sleep(2);
    }
}

// TRUNCATE в MySQL делает implicit commit, поэтому чистим таблицы ДО транзакции вставок.
try {
    $pdo->exec('SET FOREIGN_KEY_CHECKS=0');
    $pdo->exec('TRUNCATE TABLE article_category');
    $pdo->exec('TRUNCATE TABLE articles');
    $pdo->exec('TRUNCATE TABLE categories');
    $pdo->exec('SET FOREIGN_KEY_CHECKS=1');
} catch (\Throwable $e) {
    $pdo->exec('SET FOREIGN_KEY_CHECKS=1');
    throw $e;
}

$pdo->beginTransaction();

try {
    $stmtCategory = $pdo->prepare(
        'INSERT INTO categories (name, description) VALUES (:name, :description)'
    );

    $stmtArticle = $pdo->prepare(
        'INSERT INTO articles (image, title, description, content, views, created_at)
         VALUES (:image, :title, :description, :content, :views, :created_at)'
    );

    $stmtRelation = $pdo->prepare(
        'INSERT INTO article_category (article_id, category_id)
         VALUES (:article_id, :category_id)'
    );

    $categories = [
        ['name' => 'Category 1', 'description' => 'Описание категории 1'],
        ['name' => 'Category 2', 'description' => 'Описание категории 2'],
        ['name' => 'Category 3', 'description' => 'Описание категории 3'],
        ['name' => 'Category 4', 'description' => 'Описание категории 4'],
    ];

    $categoryIds = [];
    foreach ($categories as $c) {
        $stmtCategory->execute([
            ':name' => $c['name'],
            ':description' => $c['description'],
        ]);
        $categoryIds[] = (int) $pdo->lastInsertId();
    }

    $now = new DateTimeImmutable('now');
    $articleIds = [];
    for ($i = 1; $i <= 18; $i++) {
        $createdAt = $now->sub(new DateInterval('P' . (18 - $i) . 'D'))->format('Y-m-d H:i:s');
        $stmtArticle->execute([
            ':image' => 'https://picsum.photos/seed/article-' . $i . '/600/400',
            ':title' => 'Статья ' . $i,
            ':description' => 'Короткое описание статьи ' . $i,
            ':content' => '<p>Это тестовый контент статьи ' . $i . '.</p><p>В реальном проекте здесь будет полноценный текст.</p>',
            ':views' => random_int(0, 250),
            ':created_at' => $createdAt,
        ]);
        $articleIds[] = (int) $pdo->lastInsertId();
    }

    foreach ($articleIds as $idx => $articleId) {
        $primaryCategoryId = $categoryIds[$idx % count($categoryIds)];
        $stmtRelation->execute([
            ':article_id' => $articleId,
            ':category_id' => $primaryCategoryId,
        ]);

        if (($idx % 3) === 0) {
            $secondaryCategoryId = $categoryIds[($idx + 1) % count($categoryIds)];
            if ($secondaryCategoryId !== $primaryCategoryId) {
                $stmtRelation->execute([
                    ':article_id' => $articleId,
                    ':category_id' => $secondaryCategoryId,
                ]);
            }
        }
    }

    $pdo->commit();
} catch (\Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    throw $e;
}
