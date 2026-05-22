{* Smarty: навигация по страницам (pagination) *}
{if $pagination.total_pages > 1}
    <nav class="pagination">
        {if $pagination.page > 1}
            <a class="pagination__link" href="{$pagination.base_url}&page={$pagination.page - 1}">Назад</a>
        {/if}

        <span class="pagination__info">Страница {$pagination.page} из {$pagination.total_pages}</span>

        {if $pagination.page < $pagination.total_pages}
            <a class="pagination__link" href="{$pagination.base_url}&page={$pagination.page + 1}">Вперед</a>
        {/if}
    </nav>
{/if}
