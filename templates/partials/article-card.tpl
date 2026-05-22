{* Smarty: карточка статьи (картинка + заголовок + короткое описание + ссылка) *}
<article class="article-card">
    <div class="article-card__image">
        <img src="{$article.image}" alt="{$article.title}">
    </div>
    <h3 class="article-card__title">
        <a href="/article?id={$article.id}">{$article.title}</a>
    </h3>
    <p class="article-card__desc">{$article.description}</p>
    <a class="article-card__link" href="/article?id={$article.id}">Continue Reading</a>
</article>
