<div class="container">
  <div class="page-header page-top">
    <div class="row">
      <div class="col-lg-12">
        <div class="bs-component">
            <div class="well">検索結果：{if $iCount > 0}{$iCount}{else}0{/if}件　検索キーワード：{if $arrForm["keyword"]}{$arrForm["keyword"]}{else}指定なし{/if}</div>
            
            {if $arrResult}
            {foreach from=$arrResult item=arrData}
            <div class="col-lg-6">
                <div class="panel {$arrData["d_order_sheet_CategoryColorClass"]}">
                  <div class="panel-heading">
                      <h3 class="panel-title"><a data-toggle="modal" data-target="#detail" data-recipient="{$arrData["d_order_sheet_OrderSheetID"]}" href="javascript: void(0)">{$arrData["d_order_sheet_Title"]}</a></h3>
                  </div>
                  <div class="panel-body">
                    <div class="col-lg-3 order-left photo" style="padding: 0 !important;">
                      {if $arrData["d_order_sheet_ImageFileName1"] != ""}
                      <img src="{$smarty.const.SHEET_IMG_URL}{$arrData["d_order_sheet_ImageFileName1"]}">
                      {else}
                      <img src="{$smarty.const.SHEET_IMG_URL}noimage.png">
                      {/if}
                    </div>
                    <div class="col-lg-9 text-overflow with-height-limit order-right" id="{$arrData["d_order_sheet_OrderSheetID"]}">
                      <p class="sheet-text">{$arrData["d_order_sheet_Contents"]}</p>
                    </div>
                    <div class="order-under border-{$arrData["d_order_sheet_CategoryColorClass"]}" id="sp">
                        <span class="glyphicon glyphicon-user" aria-hidden="true"></span> {if $arrData["d_order_sheet_CustomerName"] != ""}<a href="{$smarty.const.URL}/customer/{$arrData["d_order_sheet_CustomerID"]}">{$arrData["d_order_sheet_CustomerName"]}</a>{else}未登録ユーザー{/if}　<span class="glyphicon glyphicon-time" aria-hidden="true"></span> {$arrData["d_order_sheet_CreatedTime"]|date_format:"%Y&#24180;%m&#26376;%d&#26085; %H:%M"}</a>
                    </div>
                    <div class="order-under border-{$arrData["d_order_sheet_CategoryColorClass"]}" id="pc">
                        <span class="glyphicon glyphicon-time" aria-hidden="true"></span>{$arrData["d_order_sheet_CreatedTime"]|date_format:"%Y&#24180;%m&#26376;%d&#26085; %H:%M"}&nbsp;
                        <span class="glyphicon glyphicon-user" aria-hidden="true"></span> {if $arrData["d_order_sheet_CustomerName"] != ""}<a href="{$smarty.const.URL}/customer/{$arrData["d_order_sheet_CustomerID"]}">{$arrData["d_order_sheet_CustomerName"]}</a>{else}未登録ユーザー{/if}<br>
                    </div>
                  </div>
                </div>
            </div>
            {/foreach}
            {else}
                まだ投稿がありません<br><br><br><br><br><br><br><br><br><br><br>
            {/if}
        </div>
      </div>
      {if $arrResult}
      <div class="col-lg-12">
        <div class="bs-component">
            <div class="well">検索結果：{$iCount}件　検索キーワード：{if $arrForm["keyword"]}{$arrForm["keyword"]}{else}指定なし{/if}</div>
        </div>
      </div>
      {/if}
    </div>
  </div>
</div>