<div>
    <!-- 前へ -->
    {if isset($objPaginateInfo->previous)}
        <a title="{$page}" class="page_prev prev" href="javascript:void(0);" onclick="execPageChange({$objPaginateInfo->previous}, {$iPageLimit});"><<前へ</a>
    {/if}
    <!-- ページ番号リンク -->
    {foreach from=$objPaginateInfo->pagesInRange item=page name=loop}
        {if $smarty.foreach.loop.index != 0} | {/if}
        {if $page != $objPaginateInfo->current}
            <a title="{$page}" href="javascript:void(0);" onclick="execPageChange({$page}, {$iPageLimit});">{$page}</a>
        {else}
            <strong>{$page}</strong>
        {/if}
    {/foreach}
    <!-- 次へ -->
    {if isset( $objPaginateInfo->next)}
        <a title="{$page}" href="javascript:void(0);" onclick="execPageChange({$objPaginateInfo->next}, {$iPageLimit});">次へ>></a>
    {/if}
</div>