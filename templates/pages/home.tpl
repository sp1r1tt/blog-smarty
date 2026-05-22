{* Smarty: главная страница (категории + 3 последних статьи в каждой) *}
{extends file='layouts/base.tpl'}

{block name='content'}
{if !$categories}
    {if $db_unavailable|default:false}
        <p>База данных недоступна. Запустите MySQL (или Docker) и примените schema.sql, затем выполните seed.php.</p>
    {else}
        <p>Пока нет данных. Создайте таблицы и выполните сидинг.</p>
    {/if}
{else}
{foreach $categories as $category}
    <section class="category-section">
        <div class="category-section__head">
            <div>
                <h2 class="category-section__title">{$category.name}</h2>
            </div>
            <a class="category-section__all" href="/category?id={$category.id}">View all</a>
        </div>

        <div class="articles-grid">
            {foreach $category.articles as $article}
                {include file='partials/article-card.tpl' article=$article}
            {/foreach}
        </div>
    </section>
{/foreach}
{/if}
{/block}
