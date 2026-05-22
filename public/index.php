<?php

declare(strict_types=1);

// Точка входа веб-приложения: подключение зависимостей и простая маршрутизация URL → контроллер.

$root = dirname(__DIR__);

require $root . '/vendor/autoload.php';
require $root . '/src/Core/View.php';
require $root . '/src/Controller/HomeController.php';
require $root . '/src/Controller/CategoryController.php';
require $root . '/src/Controller/ArticleController.php';
require $root . '/src/Core/Database.php';
require $root . '/src/Repository/ArticleRepository.php';
require $root . '/src/Repository/CategoryRepository.php';

$smartyConfig = require $root . '/config/smarty.php';
$view = \App\Core\View::make($smartyConfig);

$pdoConfig = require $root . '/config/database.php';
try {
    $pdo = \App\Core\Database::connect($pdoConfig);
} catch (\PDOException $e) {
    http_response_code(503);
    $view->render('pages/home.tpl', [
        'categories' => [],
        'db_unavailable' => true,
    ]);
    exit;
}

$categoriesRepo = new \App\Repository\CategoryRepository($pdo);
$articlesRepo = new \App\Repository\ArticleRepository($pdo);

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';

switch ($uri) {
    case '/':
        $controller = new \App\Controller\HomeController($view, $categoriesRepo, $articlesRepo);
        $controller->index();
        break;

    case '/category':
        $id = (int) ($_GET['id'] ?? 0);
        $sortKey = (string) ($_GET['sort'] ?? 'date');
        $page = max(1, (int) ($_GET['page'] ?? 1));

        $controller = new \App\Controller\CategoryController($view, $categoriesRepo, $articlesRepo);
        $controller->show($id, $sortKey, $page);
        break;

    case '/article':
        $id = (int) ($_GET['id'] ?? 0);
        $controller = new \App\Controller\ArticleController($view, $articlesRepo);
        $controller->show($id);
        break;

    default:
        http_response_code(404);
        echo '404 Not Found';
}
