<?php

declare(strict_types=1);

// Контроллер статьи: вывод полной статьи и блока похожих материалов.

namespace App\Controller;

use App\Core\View;
use App\Repository\ArticleRepository;

class ArticleController
{
    public function __construct(private View $view, private ArticleRepository $articles)
    {
    }

    public function show(int $articleId): void
    {
        $article = $this->articles->findById($articleId);
        if ($article === null) {
            http_response_code(404);
            $this->view->render('pages/article.tpl', [
                'article' => [
                    'id' => $articleId,
                    'title' => 'Статья не найдена',
                    'image' => '',
                    'description' => '',
                    'content' => '',
                    'views' => 0,
                ],
                'similar_articles' => [],
            ]);
            return;
        }

        $this->articles->incrementViews($articleId);
        $article['views'] = (int) $article['views'] + 1;

        $similarArticles = $this->articles->findSimilar($articleId, 3);

        $this->view->render('pages/article.tpl', [
            'article' => $article,
            'similar_articles' => $similarArticles,
        ]);
    }
}
