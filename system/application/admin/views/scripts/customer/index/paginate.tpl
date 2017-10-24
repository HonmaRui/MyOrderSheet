            <div class="pagenate-box clearfix">
              <div class="pagenate clearfix">
              <!-- 前へ -->
              {if isset($objPaginateInfo->previous)}
                <a title="{$page}" class="page_prev prev" href="javascript:void(0);" onclick="execPageChange({$objPaginateInfo->previous}, {$iPageLimit});">&laquo;</a>
             {/if}
              <!-- ページ番号リンク -->
            {foreach from=$objPaginateInfo->pagesInRange item=page}
              {if $page != $objPaginateInfo->current}
                <a title="{$page}" class="page page-num" href="javascript:void(0);" onclick="execPageChange({$page}, {$iPageLimit});">{$page}</a>
              {else}
                <a href="javascript:void(0);" onclick="execPageChange({$objPaginateInfo->current}, {$iPageLimit});" class="page page-num active">{$page}</a>
              {/if}
            {/foreach}
              <!-- 次へ -->
              {if isset( $objPaginateInfo->next)}
                <a title="{$page}" class="page_next next" href="javascript:void(0);" onclick="execPageChange({$objPaginateInfo->next}, {$iPageLimit});">&raquo;</a>
              {/if}    
              </div>
            </div>