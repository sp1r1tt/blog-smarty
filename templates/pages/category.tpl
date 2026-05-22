{* Smarty: страница категории (список статей + сортировка + пагинация) *}
{extends file='layouts/base.tpl'}

{block name='content'}
<section class="category-page">
    <div class="category-page__head">
        <div>
            <h1 class="category-page__title">{$category.name}</h1>
            {if $category.description}
                <p class="category-page__desc">{$category.description}</p>
            {/if}
        </div>

        <div class="category-page__sort">
            <a href="/category?id={$category.id}&sort=date">По дате</a>
            <a href="/category?id={$category.id}&sort=views">По просмотрам</a>
        </div>
    </div>

    <div class="articles-grid">
        {if !$articles}
            <p>В этой категории пока нет статей.</p>
        {else}
        {foreach $articles as $article}
            {include file='partials/article-card.tpl' article=$article}
        {/foreach}
        {/if}
    </div>

    {include file='partials/pagination.tpl' pagination=$pagination}
</section>
{/block}
