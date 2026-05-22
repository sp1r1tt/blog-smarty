# Smarty PHP Blog

Небольшой учебный проект блога: категории + статьи.

## Стек
- PHP 8+
- MySQL 8
- Smarty
- Docker (опционально)

## Структура данных
- `categories`: `name`, `description`
- `articles`: `image`, `title`, `description`, `content`, `views`, `created_at`
- `article_category`: связь many-to-many (статья ↔ категории)

Схема: `scripts/schema.sql`.

## Страницы
- `/` — главная: выводит категории, в которых есть статьи, и по каждой категории показывает 3 последних статьи по `created_at`.
- `/category?id=...&sort=date|views&page=...` — страница категории: список статей + сортировка + пагинация.
- `/article?id=...` — страница статьи: полный контент + просмотры + блок из 3 похожих статей.

## Как работает вывод данных (кратко)
- Точка входа: `public/index.php` (front controller).
- Рендер: `src/Core/View.php` (Smarty).
- Контроллеры: `src/Controller/*`.
- SQL: `src/Repository/*`.

Пагинация на категории:
- `ArticleRepository::countByCategory()` считает всего статей.
- `ArticleRepository::findByCategory()` использует `LIMIT offset, per_page`.
- Навигация: `templates/partials/pagination.tpl`.

## Запуск через Docker (рекомендуется)
Файлы: `Dockerfile`, `docker-compose.yml`.

1) Поднять контейнеры:
```bash
docker compose up --build -d
```

2) Сидинг:
```bash
docker compose exec app php scripts/seed.php
```

3) Открыть сайт:
- http://localhost:8080/

Если вы меняли `scripts/schema.sql`, пересоздайте volume:
```bash
docker compose down -v
docker compose up --build -d
docker compose exec app php scripts/seed.php
```

## Локальный запуск (без Docker)
Вариант для разработки без Apache/Nginx:
```bash
php -S localhost:8000 -t public public/router.php
```

Сидинг локально требует настроек подключения к MySQL:
- можно создать `.env` по образцу `.env.example`
- или задать переменные окружения `DB_HOST/DB_PORT/DB_NAME/DB_USER/DB_PASS`

## Сидинг
- `scripts/seed.php` добавляет тестовые категории/статьи и связи many-to-many.

Добавлена поддержка .env , чтобы при локальном запуске не писать каждый раз 
```powershell
$env:DB_HOST="127.0.0.1"
$env:DB_PORT="3306"
$env:DB_NAME="blog"
$env:DB_USER="root"
$env:DB_PASS="root"
php scripts/seed.php
```

Скопируйте .env.example → .env и заполните под себя:
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=blog
DB_USER=root
DB_PASS=root
