<div class="container">
  <div class="col-lg-9" style="margin: 0 auto;float: none;">
    <h2 id="nav-tabs"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> {$stCustomerName} さんのマイページ</h2>
    <p><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> 投稿したオーダーシート： {if $iOrderCount > 0}{$iOrderCount}{else}0{/if}枚</p>
    {if $bCompleted}
        <div class="alert alert-dismissible alert-success">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <br>
            会員情報の変更が完了しました。
            <br><br>
        </div>
    {/if}
    <div class="bs-component">
      <ul class="nav nav-tabs" id="sp">
        <li class="active"><a href="#all" data-toggle="tab" aria-expanded="true" id="allBtn">マイオーダーシート</a></li>
        <li class=""><a href="#change" data-toggle="tab" aria-expanded="true" id="changeBtn">会員情報変更</a></li>
        <li class=""><a href="#refusal" data-toggle="tab" aria-expanded="true" id="refusalBtn">退会</a></li>
      </ul>
      <ul class="nav nav-tabs" id="pc">
        <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            メニュー一覧 <span class="caret"></span>
          </a>
          <ul class="dropdown-menu">
            <li><a href="#all" data-toggle="tab" id="allBtn">マイオーダーシート</a></li>
            <li class="divider"></li>
            <li><a href="#change" data-toggle="tab" id="changeBtn">会員情報変更</a></li>
            <li class="divider"></li>
            <li><a href="#refusal" data-toggle="tab" id="refusalBtn">退会</a></li>
          </ul>
        </li>
      </ul>
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
        <div class="tab-pane fade" id="change">
            {if $stMode == "" || $stMode == "entry"}
            <br>
            <form class="form-horizontal" name="mypage_form" id="mypage_form" method="post" action="{$smarty.const.URL}/mypage">
              <input type="hidden" name="mode" value="">
              <input type="hidden" name="csrf" value="{$stCsrf}">
              <fieldset>
                {if $arrErrorMessage}
                <div class="alert alert-dismissible alert-danger">
                    {foreach from=$arrErrorMessage item=v}{$v}<br>{/foreach}
                </div>
                {/if}
                <div class="form-group">
                  <label for="inputName" class="col-lg-4 control-label">ニックネーム<br><span class="text-danger"><small>(最大10文字)</small></span></label>
                  <div class="col-lg-8">
                    {assign var="key" value="d_customer_Name"}
                    <input name="{$key}" value="{$arrCustomer[$key]}" type="text" class="form-control" id="inputName" placeholder="ニックネーム" maxlength="10">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail" class="col-lg-4 control-label">メールアドレス<br><span class="text-danger"><small>(最大100文字)</small></span></label>
                  <div class="col-lg-8">
                    {assign var="key" value="d_customer_EmailAddress"}
                    <input name="{$key}" value="{$arrCustomer[$key]}" type="text" class="form-control" id="inputEmail" placeholder="メールアドレス" maxlength="100">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputPassword" class="col-lg-4 control-label">パスワード<br><span class="text-danger"><small>(4～12文字・半角英数字)</small></span></label>
                  <div class="col-lg-8">
                    {assign var="key" value="d_customer_Password"}
                    <input name="{$key}" value="" type="password" class="form-control" id="inputPassword" placeholder="パスワード" maxlength="12">
                  </div>
                </div><br>
                <div class="form-group">
                  <div class="" style="text-align: center;">
                    <button id="confirmBtn" type="button" class="btn btn-primary btn-lg pc_mb20" style="width: 240px;">確認画面へ</button>
                  </div>
                </div>
                <br>
              </fieldset>
            </form>
            {else if $stMode == "confirm"}
            <div class="col-lg-9" style="margin: 0 auto;float: none;">
                <p>下記の内容で会員情報を変更します。</p>
                <form name="mypage_form" id="mypage_form" method="post" action="{$smarty.const.URL}/mypage">
                    <input type="hidden" name="mode" value="">
                    <input type="hidden" name="csrf" value="{$stCsrf}">
                    <input type="hidden" name="d_customer_Name" value="{$arrForm['d_customer_Name']}">
                    <input type="hidden" name="d_customer_EmailAddress" value="{$arrForm['d_customer_EmailAddress']}">
                    <table class="table table-striped table-hover">
                        <tbody>
                            <tr>
                                <td>ニックネーム</td>
                                <td>{$arrForm['d_customer_Name']}</td>
                            </tr>
                            <tr>
                                <td>メールアドレス</td>
                                <td>{$arrForm['d_customer_EmailAddress']}</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #ddd;">
                                <td>パスワード</td>
                                <td>****</td>
                            </tr>
                        </tbody>
                    </table>
                    <div style="text-align: center;">
                        <a href="javascript: void(0)" class="btn btn-default" id="back" style="width: 70px;">戻る</a>
                        <a href="javascript: void(0)" class="btn btn-primary" id="send" style="width: 180px;">変更完了</a>
                    </div>
                    <br>
                </form>
            </div>
            {/if}
        </div>
        <div class="tab-pane fade" id="refusal">
            <div class="col-lg-9" style="margin: 0 auto;float: none;">
                <br>
                <form class="form-horizontal" name="refusal_form" id="refusal_form" method="post" action="{$smarty.const.URL}/mypage">
                  <input type="hidden" name="mode" value="">
                  <input type="hidden" name="csrf" value="{$stCsrf}">
                  <fieldset>
                    <p>退会すると、会員情報および作成したオーダーシートは全て破棄されます。</p>
                    <br>
                    <div class="form-group">
                      <div class="" style="text-align: center;">
                        <button id="decideBtn" type="button" class="btn btn-default pc_mb20">退会する</button>
                      </div>
                    </div>
                    <br><br><br>
                  </fieldset>
                </form>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
    $(function () { 
        // 確認ボタン
        $('#confirmBtn').click(function () {
            setMode('entry');
        });
        // 変更完了ボタン
        $('#send').click(function() {
            setMode('confirm');
        });
        // 戻るボタン
        $('#back').click(function() {
            setMode('back');
        });
        // 退会ボタン
        $('#decideBtn').click(function() {
            if (confirm("破棄されたデータは復旧できません。退会してよろしいですか？")) {
                setModeForRefusal('refusal');
            }
        });
        
        {if $stMode == "entry" || $stMode == "confirm"}
            $("#changeBtn").click();
        {/if}
        
    });

    function setMode(mode) {
        var em = document.mypage_form;
        em.mode.value = mode;
        em.action = "{$smarty.const.URL}/mypage";

        {if $stMode == "" || $stMode == "entry"}
        // 必須項目チェック
        if (em.d_customer_EmailAddress.value == "" || em.d_customer_Password.value == "" || em.d_customer_Name.value == "") {
            alert("ニックネーム、メールアドレス、パスワードを全て入力して下さい。");
            return false;
        }
        {/if}

        em.submit();
    }
    
    function setModeForRefusal(mode) {
        var em = document.refusal_form;
        em.mode.value = mode;
        em.action = "{$smarty.const.URL}/mypage";

        em.submit();
    }
</script>