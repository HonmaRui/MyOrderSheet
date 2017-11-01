<div class="container">
  <div class="page-header page-top">
    <div class="row">
      <div class="col-lg-9" style="margin: 0 auto;float: none;">
        <div class="bs-component">
          <div class="jumbotron" style="background: none;">
            <p>下記の内容で登録します。</p>
            <form name="confirm_form" id="confirm_form" method="post" action="{$smarty.const.URL}/entry/confirm">
                <input type="hidden" name="mode" value="confirm">
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
                    <a href="javascript: void(0)" class="btn btn-primary" id="send" style="width: 180px;">登録完了</a>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
$(function() {
    // 登録ボタン
    $('#send').click(function() {
        setMode('confirm');
    });
    // 戻るボタン
    $('#back').click(function() {
        setMode('back');
    });
});

function setMode(mode) {
    var em = document.confirm_form;
    em.mode.value = mode;
    if (mode == "back") {
        em.action = "{$smarty.const.URL}/entry/";
    }
    em.submit();
}
    
</script>