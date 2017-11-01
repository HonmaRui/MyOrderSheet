<div class="container">
  <div class="page-header page-top">
    <div class="row">
      <div class="col-lg-12">
        <div class="bs-component">
          <div class="jumbotron white main2">
            <div class="page-header col-lg-6">
              <h1 id="type">Login</h1>
            </div>
            <form name="login_form" id="login_form" class="form-horizontal" method="post" action="{$smarty.const.URL}/login" enctype="multipart/form-data">
              <input type="hidden" name="mode" value="">
              <fieldset>
                {if $stErrorMessage}
                <div class="alert alert-dismissible alert-danger">{$stErrorMessage}</div>
                {/if}
                <div class="form-group{if $stErrorMessage} has-error{/if}">
                  <label for="inputEmail" class="col-lg-4 control-label">メールアドレス</label>
                  <div class="col-lg-8">
                    {assign var="key" value="d_customer_EmailAddress"}
                    <input name="{$key}" value="{$arrForm[$key]}" type="text" class="form-control" id="inputEmail" placeholder="メールアドレス">
                  </div>
                </div>
                <div class="form-group{if $stErrorMessage} has-error{/if}">
                  <label for="inputPassword" class="col-lg-4 control-label">パスワード</label>
                  <div class="col-lg-8">
                    {assign var="key" value="d_customer_Password"}
                    <input name="{$key}" type="password" class="form-control" id="inputPassword" placeholder="パスワード">
                  </div>
                </div><br>
                <div class="form-group">
                  <div class="" style="text-align: center;">
                    <button type="button" id="loginBtn" class="btn btn-primary btn-lg pc_mb20" style="width: 240px;">ログイン</button><br><br>
                    <a href="{$smarty.const.URL}/entry" class="btn btn-default">新規登録はこちら</a>
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
        $('#loginBtn').click(function () {
            setModeForSide('login');
        });
    });

    function setModeForSide(mode) {
        var em = document.login_form;
        em.mode.value = mode;
        em.action = "{$smarty.const.URL}/login";

        // 必須項目チェック
        if (em.d_customer_EmailAddress.value == "" || em.d_customer_Password.value == "") {
            alert("メールアドレス/パスワードを入力して下さい。");
            return false;
        }

        em.submit();
    }
</script>