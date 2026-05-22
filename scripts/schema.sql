-- SQL-схема БД блога: categories, articles и таблица-связка many-to-many article_category.

CREATE TABLE categories (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE articles (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    image VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    content LONGTEXT NOT NULL,
    views INT UNSIGNED NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL,
    PRIMARY KEY (id),
    INDEX idx_articles_created_at (created_at),
    INDEX idx_articles_views (views)
) ENGINE=InnoDB;

CREATE TABLE article_category (
    article_id INT UNSIGNED NOT NULL,
    category_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (article_id, category_id),
    INDEX idx_article_category_category_id (category_id),
    CONSTRAINT fk_article_category_article
        FOREIGN KEY (article_id)
        REFERENCES articles (id)
        ON DELETE CASCADE,
    CONSTRAINT fk_article_category_category
        FOREIGN KEY (category_id)
        REFERENCES categories (id)
        ON DELETE CASCADE
) ENGINE=InnoDB;
