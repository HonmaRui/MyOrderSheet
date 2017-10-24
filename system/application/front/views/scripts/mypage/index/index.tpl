<div id="one_maincolumn">
    <div id="mypagecolumn">
        {include file='./header-info.tpl'}
        <div id="mycontentsarea">
            <form name="history_form" method="post" action="{$smarty.const.SSL_URL}/mypage/">
                <input type="hidden" name="page" value="" />
                <input type="hidden" name="limit" value="10" />
                <h3><img src="{$smarty.const.FRONT_IMG_SSL}mypage/subtitle01.gif" width="515" height="32" alt="サンプル請求履歴一覧" /></h3>
                {if $iOrderCount == 0}
                    <p>カットサンプル請求履歴はありません。</p>
                {else}
                    <p>{$iOrderCount}件のカットサンプル請求履歴があります。</p>
                    {if $iOrderCount > 10}{include file='./paginate.tpl'}{/if}
                    <table summary="サンプル請求履歴">
                        <tr>
                            <th>ご請求日</th>
                            <th>詳細</th>
                        </tr>
                        {foreach from=$arrData item=arrOrder}
                            <tr>
                                <td>{$arrOrder["d_order_CreatedTime"]|date_format:"%Y/%m/%d %H:%M"}</td>
                                <td class="centertd"><a href="{$smarty.const.SSL_URL}/mypage/detail/{$arrOrder["d_order_OrderID"]}">詳細</a></td>
                            </tr>
                        {/foreach}
                    </table>
                {/if}
            </form>
        </div>
    </div>        
</div>
<script>
function execPageChange(pageNumber, pageLimit) {
    var em = document.history_form;
    em.action = "{$smarty.const.SSL_URL}/mypage?page=" + pageNumber + "&limit=" + pageLimit + "";
    em.submit();
}
</script>