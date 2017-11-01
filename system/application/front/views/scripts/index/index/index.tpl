<div class="container">
  <div class="page-header page-top">
    <div class="row">
      <div class="col-lg-12">
        <div class="bs-component">
          {if !$arrNewOrderSheet}
          <div class="jumbotron white">
            <h1 class="font1">最高のオーダーをあなたに</h1>
            <p class="font2 fs1em">スターバックス・コーヒー、サブウェイ、二郎系ラーメン...好みのトッピング・オーダーを選択する機会は増えましたが、いつも「ふつう」や「お店のおすすめ」を選んでしまってはいませんか？<br>そんなあなたに マイオーダーシート は最高のオーダーをご紹介致します！</p>
            <p><a class="btn btn-warning btn-lg" href="{$smarty.const.URL}/ordersheet"><span class="glyphicon glyphicon-circle-arrow-right" aria-hidden="true"></span> 人気オーダーシートを見る</a></p>
          </div>
          {else}
          <div class="jumbotron" style="background: none;padding-top: 0;">
            <div class="col-lg-6">
            <div class="panel {$arrNewOrderSheet["d_order_sheet_CategoryColorClass"]}">
              <div class="panel-heading">
                  <h3 class="panel-title"><a data-toggle="modal" data-target="#detail" data-recipient="{$arrNewOrderSheet["d_order_sheet_OrderSheetID"]}" href="javascript: void(0)">{$arrNewOrderSheet["d_order_sheet_Title"]}</a></h3>
              </div>
              <div class="panel-body">
                <div class="col-lg-3 order-left photo" style="padding: 0 !important;">
                  {if $arrNewOrderSheet["d_order_sheet_ImageFileName1"] != ""}
                  <img src="{$smarty.const.SHEET_IMG_URL}{$arrNewOrderSheet["d_order_sheet_ImageFileName1"]}">
                  {else}
                  <img src="{$smarty.const.SHEET_IMG_URL}noimage.png">
                  {/if}
                </div>
                <div class="col-lg-9 text-overflow with-height-limit order-right" id="{$arrNewOrderSheet["d_order_sheet_OrderSheetID"]}">
                  <p class="sheet-text">{$arrNewOrderSheet["d_order_sheet_Contents"]}</p>
                </div>
                <div class="order-under border-{$arrNewOrderSheet["d_order_sheet_CategoryColorClass"]}" id="sp">
                    <span class="glyphicon glyphicon-user" aria-hidden="true"></span> {if $bIsLogin}<a href="{$smarty.const.URL}/customer/{$arrNewOrderSheet["d_order_sheet_CustomerID"]}">{$arrNewOrderSheet["d_order_sheet_CustomerName"]}</a>{else}未登録ユーザー{/if}　<span class="glyphicon glyphicon-time" aria-hidden="true"></span> {$arrNewOrderSheet["d_order_sheet_CreatedTime"]|date_format:"%Y&#24180;%m&#26376;%d&#26085; %H:%M"}
                    <div class="order-under border-{$arrNewOrderSheet["d_order_sheet_CategoryColorClass"]}" id="pc">
                        <span class="glyphicon glyphicon-time" aria-hidden="true"></span>{$arrNewOrderSheet["d_order_sheet_CreatedTime"]|date_format:"%Y&#24180;%m&#26376;%d&#26085; %H:%M"}&nbsp;
                        <span class="glyphicon glyphicon-user" aria-hidden="true"></span> {if $bIsLogin}<a href="{$smarty.const.URL}/customer/{$arrNewOrderSheet["d_order_sheet_CustomerID"]}">{$arrNewOrderSheet["d_order_sheet_CustomerName"]}</a>{else}未登録ユーザー{/if}<br>
                    </div>
                </div>
              </div>
            </div>
            </div>
            <div class="col-lg-6">
                <legend><h2>投稿完了しました</h2></legend>
                <div class="alert alert-dismissible alert-success">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    オーダーシート「<a data-toggle="modal" data-target="#detail" data-recipient="{$arrNewOrderSheet["d_order_sheet_OrderSheetID"]}" href="javascript: void(0)" class="alert-link">{$arrNewOrderSheet["d_order_sheet_Title"]}</a>」を公開しました。
                </div>
                {if !$bIsLogin}
                <div class="alert alert-dismissible alert-warning">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>会員登録が完了していません</strong><br>会員登録をすると過去にあなたが投稿したオーダーシートがマイページから確認できるようになります。<a href="{$smarty.const.URL}/entry" class="alert-link">会員登録はこちら</a>
                </div>
                {/if}
            </div>
          </div>
          {/if}
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div id="add" style="position: relative;top: -70px;"></div>
    <div class="col-lg-6">
      <h2><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> オーダーシート作成</h2>
        <div class="well bs-component">
          <form class="form-horizontal" action="{$smarty.const.URL}/" method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf" value="{$stCsrf}">
            <input type="hidden" name="mode" value="add">
            <fieldset>
                {if !$bIsLogin}
                <p><a href="{$smarty.const.URL}/login">会員登録が済んでいる場合はログインしてください</a></p>
                <p><a href="{$smarty.const.URL}/entry">新規会員登録はこちら</a></p>
                {/if}
              <div class="form-group">
                <label for="select" class="col-lg-3 control-label">カテゴリー</label>
                <div class="col-lg-9">
                  {assign var="key" value="d_order_sheet_CategoryID"}
                  <select class="form-control" id="select" name="{$key}">
                    {html_options options=$arrCategory selected=$arrForm[$key]}
                  </select>
                </div>
              </div>
              <div class="form-group">
                  <label for="textArea" class="col-lg-3 control-label" id="no-pc">タイトル<br><span class="text-danger"><small>(最大50文字)</small></span></label>
                  <label for="textArea" class="col-lg-3 control-label" id="yes-pc">タイトル&nbsp;&nbsp;<span class="text-danger"><small>(最大50文字)</small></span></label>
                <div class="col-lg-9">
                  {assign var="key" value="d_order_sheet_Title"}
                  <input name="{$key}" value="{$arrForm[$key]}" class="form-control" placeholder="タイトルを入力してください" maxlength="50">
                </div>
              </div>
              <div class="form-group">
                <label for="textArea" class="col-lg-3 control-label" id="no-pc">オーダー内容<br><span class="text-danger"><small>(最大200文字)</small></span></label>
                <label for="textArea" class="col-lg-3 control-label" id="yes-pc">オーダー内容&nbsp;&nbsp;<span class="text-danger"><small>(最大200文字)</small></span></label>
                <div class="col-lg-9">
                  {assign var="key" value="d_order_sheet_Contents"}
                  <textarea name="{$key}" id="{$key}" class="form-control" rows="5" id="textArea" placeholder="オーダー内容を入力してください" maxlength="200">{$arrForm[$key]}</textarea>
                </div>
              </div>
              <div class="form-group">
                <label for="InputFile" class="col-lg-3 control-label" id="no-pc"><span class="glyphicon glyphicon-camera" aria-hidden="true"></span> 画像<br><span class="text-danger"><small>(最大2MB jpg,png)</small></span></label>
                <label for="InputFile" class="col-lg-3 control-label" id="yes-pc"><span class="glyphicon glyphicon-camera" aria-hidden="true"></span> 画像&nbsp;&nbsp;<span class="text-danger"><small>(最大2MB jpg,png)</small></span></label>
                <div class="col-lg-9">
                  {assign var="key" value="d_order_sheet_ImageFileName1"}
                  <input type="file" name="{$key}" id="InputFile" accept=".jpg,.png,image/jpeg,image/png">
                </div>
              </div>
              <div class="form-group">
                <div class="" style="text-align: center;">
                  <button type="submit" class="btn btn-primary btn-lg" style="width: 280px;" id="add-button">オーダーシートを登録</button>
                </div>
              </div>
            </fieldset>
          </form>
        </div>
    </div>
    <div class="col-lg-6">
      <h2 id="nav-tabs"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> みんなのオーダーシート</h2>
      <div class="bs-component">
        <ul class="nav nav-tabs" id="order-tab">
          <li class="active"><a href="#popular" data-toggle="tab" aria-expanded="true">人気オーダー</a></li>
          <li class=""><a href="#new" data-toggle="tab" aria-expanded="false">新着オーダー</a></li>
          <li class="dropdown" id="sp">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
              カテゴリ別オーダーの一覧 <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
              {foreach from=$arrCategory item=v key=k}
              <li><a href="#category{$k}" data-toggle="tab">{$v}</a></li>
              {/foreach}
            </ul>
          </li>
        </ul>
        <div id="myTabContent" class="tab-content">
          {*人気オーダー*}
          <div class="tab-pane fade active in" id="popular">
            {if $arrNewOrder}
            {foreach from=$arrNewOrder item=arrData}
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
                まだ投稿がありません
            {/if}
          </div>

                
          {*新着オーダー*}
          <div class="tab-pane fade" id="new">
            {if $arrNewOrder}
            {foreach from=$arrNewOrder item=arrData}
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
                まだ投稿がありません
            {/if}
          </div>
            
                
          {*カテゴリ別オーダー一覧*}
          {foreach from=$arrCategoryOrder item=arrData1 key=k}
          <div class="tab-pane fade" id="{$k}">
            {if $arrData1}
            {foreach from=$arrData1 item=arrData}
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
                このカテゴリはまだ投稿がありません
            {/if}
          </div>
          {/foreach}
        </div>
      </div>
    </div>
  </div>
</div>
