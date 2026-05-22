{* Smarty: страница статьи (полный текст + блок похожих статей) *}
{extends file='layouts/base.tpl'}

{block name='content'}
{if !$article.content && $article.title == 'Статья не найдена'}
    <h1 class="article-page__title">{$article.title}</h1>
{else}
<article class="article-page">
    <h1 class="article-page__title">{$article.title}</h1>

    <div class="article-page__meta">
        <span>Просмотры: {$article.views}</span>
    </div>

    <div class="article-page__image">
        <img src="{$article.image}" alt="{$article.title}">
    </div>

    <p class="article-page__desc">{$article.description}</p>
    <div class="article-page__content">{$article.content nofilter}</div>
</article>

<section class="similar">
    <div class="similar__head">
        <h2>Похожие статьи</h2>
    </div>
    <div class="articles-grid">
        {if !$similar_articles}
            <p>Похожих статей пока нет.</p>
        {else}
        {foreach $similar_articles as $a}
            {include file='partials/article-card.tpl' article=$a}
        {/foreach}
        {/if}
    </div>
</section>
{/if}
{/block}
