<?php

declare(strict_types=1);

// Контроллер главной: вывод категорий и последних постов по категориям.

namespace App\Controller;

use App\Core\View;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;

class HomeController
{
    public function __construct(
        private View $view,
        private CategoryRepository $categories,
        private ArticleRepository $articles
    )
    {
    }

    public function index(): void
    {
        $categories = $this->categories->findAllHavingArticles();
        foreach ($categories as $i => $category) {
            $categories[$i]['articles'] = $this->articles->findLatestByCategory((int) $category['id'], 3);
        }

        $this->view->render('pages/home.tpl', [
            'categories' => $categories,
        ]);
    }
}
