<!DOCTYPE html>
<html lang="ja"  id="login-container">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
    <meta name="format-detection" content="telephone=no">
    <meta name="copyright" content="">
    <title>【管理画面】MyOrderSheet</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="robots" content="noindex,nofollow">
    <script>
    <!--
        currentdir_path = './MyOrderSheet/';
    //-->
    </script>
    <link href="favicon.ico" rel="SHORTCUT ICON" />
  </head>
  <body>
<script type="text/javascript">
function execLogin() {
    var element = document.validate_form;
    var LoginID = document.getElementById('LoginID').value;
    var Password = document.getElementById('Password').value;
    element.loginID.value = LoginID;
    element.password.value = Password;
    element.action = "{$smarty.const.ADMIN_URL}judgement";
    element.submit();
}
</script>
    <div>
    <div class="login">
    <div class="login-inner">
      <img class="login-logo" src="{$smarty.const.ADMIN_IMG_DIR}common/title_search.gif" width="378" height="26" alt="" />
        <form id="validate_form" name="validate_form" method="POST" action="{$smarty.const.ADMIN_URL}judgement">
          <table class="login-table">
            <tr>
               <td colspan=3>
                {if $arrErrMessage != ""}
                  <div class="error-mess" style="margin-left:80px;">{$arrErrMessage}</div>
		{else}
		&nbsp;
                {/if}
               </td>
            </tr>
            <tr>
              <td class="login-tdstyle1">
                ID
              </td>
              <td class="login-tdstyle4" colspan="2">
                {assign var="key1" value="d_system_member_LoginID"}
                <input type="text" class="boxstyle{if $arrErrMessage != ""} bg-red{/if}" name="loginID" value="" size="40">
              </td>
            </tr>
            <tr>
              <td class="login-tdstyle1">
                Password
              </td>
              <td class="login-tdstyle2">
                {assign var="key1" value="d_system_member_Password"}
                <input type="password" class="boxstyle{if $arrErrMessage != ""} bg-red{/if}" name="password" value="" size="40">
              </td>
              <td class="login-tdstyle3">
                <input type="submit" value="Login">
              </td>
            </tr>
          </table>
        </form>
    </div>
    </div>
  </div>

  </body>
</html><!-- #login-container end -->
