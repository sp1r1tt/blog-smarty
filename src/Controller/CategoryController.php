<?php

declare(strict_types=1);

// Контроллер категории: вывод списка статей с сортировкой и пагинацией.

namespace App\Controller;

use App\Core\View;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;

class CategoryController
{
    public function __construct(
        private View $view,
        private CategoryRepository $categories,
        private ArticleRepository $articles
    )
    {
    }

    public function show(int $categoryId, string $sortKey, int $page): void
    {
        $category = $this->categories->findById($categoryId);
        if ($category === null) {
            http_response_code(404);
            $this->view->render('pages/category.tpl', [
                'category' => ['id' => $categoryId, 'name' => 'Категория не найдена', 'description' => ''],
                'articles' => [],
                'pagination' => ['page' => 1, 'total_pages' => 1, 'base_url' => '/category?id=' . $categoryId . '&sort=' . $sortKey],
            ]);
            return;
        }

        $perPage = 6;
        $total = $this->articles->countByCategory($categoryId);
        $totalPages = max(1, (int) ceil($total / $perPage));
        $page = min(max(1, $page), $totalPages);
        $articles = $this->articles->findByCategory($categoryId, $sortKey, $page, $perPage);

        $this->view->render('pages/category.tpl', [
            'category' => $category,
            'articles' => $articles,
            'pagination' => [
                'page' => $page,
                'total_pages' => $totalPages,
                'base_url' => '/category?id=' . (int) $category['id'] . '&sort=' . $sortKey,
            ],
        ]);
    }
}
