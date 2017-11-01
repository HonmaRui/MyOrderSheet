<div class="container">
  <div class="col-lg-9" style="margin: 0 auto;float: none;">
    <h2 id="nav-tabs"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> {$arrCustomer["d_customer_Name"]} さんの情報</h2>
    <p><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> 投稿したオーダーシート： {if $iOrderCount > 0}{$iOrderCount}{else}0{/if}枚</p>
    <div class="bs-component">
      <div id="myTabContent" class="tab-content">
        <div class="tab-pane fade active in" id="all">
            <div class="col-lg-3">
                <div class="bs-component">
                  <ul class="nav nav-pills nav-stacked">
                    <li class="active"><a href="#">投稿一覧</a></li>
                  </ul>
                </div>
                <br>
            </div>
            <div class="col-lg-9">
            {if $arrOrderSheet}
            {foreach from=$arrOrderSheet item=arrData}
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
            {/foreach}
            {else}
                <br>&nbsp;&nbsp;&nbsp;まだ投稿がありません<br><br><br><br><br><br><br><br><br>
            {/if}
            </div>
        </div>
      </div>
    </div>
  </div>
</div>