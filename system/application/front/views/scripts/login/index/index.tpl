<h2>
    <img src="{$smarty.const.FRONT_IMG_SSL}side/title_login.jpg" width="166" height="35" alt="プロユーザー" />
</h2>
<div id="loginarea">
    <form name="login_form" id="login_form" method="post" action="{$smarty.const.SSL_URL}/login" enctype="multipart/form-data">
        <input type="hidden" name="mode" value="">
        <div id="login">
            {assign var="key" value="d_customer_EmailAddress"}
            <p><img src="{$smarty.const.FRONT_IMG_SSL}side/icon_mail.gif" width="70" height="18" alt="メールアドレス" /><input type="text" name="{$key}" class="box96" value="" style="ime-mode: disabled;"/></p>
                {assign var="key" value="d_customer_Password"}
            <p><img src="{$smarty.const.FRONT_IMG_SSL}side/icon_pw.gif" width="70" height="18" alt="パスワード" /><input type="password" name="{$key}" class="box96" /></p>
        </div>
        <p class="mini">
            <a href="{$smarty.const.SSL_URL}/reminder">パスワードを忘れた方はこちら</a>
        </p>
        <p class="mini">
            <a href="{$smarty.const.SSL_URL}/entry">プロユーザー登録はこちら</a>
        </p>
        <p>
            {assign var="key" value="login_memory"}
            <input type="checkbox" name="{$key}" value="1"  />
            <img src="{$smarty.const.FRONT_IMG_SSL}header/memory.gif" width="110" height="11" alt="記憶" />
        </p>
        <p class="btn">
            <a href="javascript:void(0)"><img onmouseover="chgImgImageSubmit('{$smarty.const.FRONT_IMG_SSL}side/button_login_on.gif', this)" onmouseout="chgImgImageSubmit('{$smarty.const.FRONT_IMG_SSL}side/button_login.gif', this)" src="{$smarty.const.FRONT_IMG_SSL}side/button_login.gif" class="box51" alt="ログイン" id="loginBtn"/></a>
        </p>
    </form>
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
        em.action = "{$smarty.const.SSL_URL}/login";

        // 必須項目チェック
        if (em.d_customer_EmailAddress.value == "" || em.d_customer_Password.value == "") {
            alert("メールアドレス/パスワードを入力して下さい。");
            return false;
        }

        em.submit();
    }
</script>