<section>
  <div id="main-block">
    <div class="pagetitle clearfix">
      <h1>{$stPageTitle}</h1>
    </div>
      
    <section>
      <div class="mainbox">
        <form id="mail_form" name="mail_form" method="POST" action="{$smarty.const.ADMIN_URL}basis/mail/">
        <div id="hdn">
          <input type="hidden" name="mailsettingid" value="">
          <input type="hidden" name="templateid" value="">
          <input type="hidden" name="mode" value="{$stMode}">
          <input type="hidden" name="stFormData" value="{$stFormData}">
          <input type="hidden" name="csrfupdate" value="{$stCsrfUpdate}">
          <input type="hidden" name="IsEdit" value="">
        </div>
        <div class="contebox">
          <table class="form-table table-v">
            {assign var="keyArr1" value="edit_mail"}
            <tr>                        
              <div class="red mb10 mt10">「内容」を誤って変更すると、メールが送信されなくなる場合がありますので、十分ご注意してください。</div>
            </tr>
            <tr>
              <th class="list-td al">テンプレート<span class="req"><img src="{$smarty.const.ADMIN_IMG_DIR}common/icon05.png" width="34" height="17" alt="必須項目"></span></th>
              <td class="mail-tdstyle">                      
                {assign var="key1" value="d_mail_setting_TemplateID"}
                <select id="{$key1}" name="{$keyArr1}[{$key1}]" onChange="changeTemplate();">
                  <option value="">選択してください</option>
                  {html_options options=$arrTemplate selected=$arrForm[$keyArr1][$key1]}
                </select>
              </td>
            </tr>
            <tr>
              <th class="list-td al">メールタイトル<span class="req"><img src="{$smarty.const.ADMIN_IMG_DIR}common/icon05.png" width="34" height="17" alt="必須項目"></span></th>
              <td class="mail-tdstyle">
                {assign var="key1" value="d_mail_setting_Title"}
                {if $arrErrorMessage[$key1] != ""}
                  <div class="error-mess">{$arrErrorMessage[$key1]}</div>
                {/if}
                <input type="text" name="{$keyArr1}[{$key1}]" value="{$arrForm[$keyArr1][$key1]}" maxlength="200" size="100" {if $arrErrorMessage[$key1] != ""}class="bg-red"{/if}>
                <span class="red">（上限200文字）</span>
              </td>
            </tr>
            <tr>
              <th class="list-td al">内容<span class="req"><img src="{$smarty.const.ADMIN_IMG_DIR}common/icon05.png" width="34" height="17" alt="必須項目"></span></th>
              <td class="w80">
                {assign var="key1" value="d_mail_setting_Content"}
                {if $arrErrorMessage[$key1] != ""}
                  <div class="error-mess">{$arrErrorMessage[$key1]}</div>
                {/if}
                <textarea id="{$key1}" name="{$keyArr1}[{$key1}]" cols="100" rows="25" maxlength="99999" {if $arrErrorMessage[$key1] != ""}class="bg-red"{/if}>{$arrForm[$keyArr1][$key1]}</textarea>
                <div class="mail-preview">
                  <span class="red">（上限99999文字）</span>
                  <input type="button" value="プレビュー" onclick="showPreview();">
                </div>
                <div class="textcount width80">
                  <div class="ar"><input type="button" value="文字数カウント" onclick="checkLength();"></div>
                  <div class="ar mt10">今までに入力したのは<input type="text" id="ContentLength" name="ContentLength" disabled="disabled" value="" size="6">文字です</div>
                </div>
              </td>
            </tr>
          </table>
        </div>
        {assign var="key1" value="d_mail_setting_MailSettingID"}
        {assign var="key2" value="d_mail_setting_TemplateID"}
        <input type="hidden" name="{$key1}" value="{$arrForm[$keyArr1][$key1]}">
        <input type="hidden" name="{$key2}" value="{$arrForm[$keyArr1][$key2]}">              
              
        <div class="ac m20">
          <input type="button" class="btn-gray btn-save" value="この内容で登録する" onClick="execEdit('{$arrForm[$keyArr1][$key1]}','{$arrForm[$keyArr1][$key2]}');" class="btn-confirm">
        </div>
              
        </form>
      </div><!-- #main-box end -->
    </section>
  </div><!-- #main-block end -->
</section>
            
<script type="text/javascript">

    // プレビュー表示
    function showPreview() {
        if ($('select[name="edit_mail[d_mail_setting_TemplateID]"]').val() != "") {
            var em = document.mail_form;
            em.mode.value = '';
            submitToPopupWindow('mail_form', '{$smarty.const.ADMIN_URL}basis/mail/preview-popup', '950', '600');
        } else {
            alert("テンプレートを選択してください。");
        }
    }
    
    // テンプレート変更
    function changeTemplate() {
    var TemplateID = document.getElementById("d_mail_setting_TemplateID").value; 
    var em = document.mail_form;
        em.mode.value = 'search';
        em.templateid.value = TemplateID;
        em.action = '{$smarty.const.ADMIN_URL}basis/mail';
        loading();
        em.submit();
    }

    // 更新
    function execEdit(MailSettingID, TemplateID) {
        var em = document.mail_form;
        if (MailSettingID == "" || TemplateID == "") {
            return false;
        }
        em.mode.value = 'update';
        em.IsEdit.value = true;
        em.mailsettingid.value = MailSettingID;
        em.templateid.value = TemplateID;
        em.action = '{$smarty.const.ADMIN_URL}basis/mail';
        loading();
        em.submit();
    }

    // 文字数確認
    function checkLength() {
        var Content = document.getElementById("d_mail_setting_Content").value;
        document.getElementById("ContentLength").value = Content.length;
    }

    // リロード
    function reload() {
        window.location.reload();
    }

</script>
