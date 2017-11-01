<div class="container">
  <div class="page-header page-top">
    <div class="row">
      <div class="col-lg-12">
        <div class="bs-component">
          <div class="jumbotron white main3">
            <div class="page-header col-lg-6">
              <h1 id="type">Entry</h1>
            </div>
            <form class="form-horizontal" name="entry_form" id="entry_form" method="post" action="{$smarty.const.URL}/entry">
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
                    <input name="{$key}" value="{$arrForm[$key]}" type="text" class="form-control" id="inputName" placeholder="ニックネーム" maxlength="10">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail" class="col-lg-4 control-label">メールアドレス<br><span class="text-danger"><small>(最大100文字)</small></span></label>
                  <div class="col-lg-8">
                    {assign var="key" value="d_customer_EmailAddress"}
                    <input name="{$key}" value="{$arrForm[$key]}" type="text" class="form-control" id="inputEmail" placeholder="メールアドレス" maxlength="100">
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
                    <button id="entryBtn" type="button" class="btn btn-primary btn-lg pc_mb20" style="width: 240px;">確認画面へ</button>
                  </div>
                </div>
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
        // ログインボタン
        $('#entryBtn').click(function () {
            setMode('entry');
        });
    });

    function setMode(mode) {
        var em = document.entry_form;
        em.mode.value = mode;
        em.action = "{$smarty.const.URL}/entry";

        // 必須項目チェック
        if (em.d_customer_EmailAddress.value == "" || em.d_customer_Password.value == "" || em.d_customer_Name.value == "") {
            alert("ニックネーム、メールアドレス、パスワードを全て入力して下さい。");
            return false;
        }

        em.submit();
    }
</script>