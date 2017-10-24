<div id="main_block-pop">
  <div class="mainbox">
    <section>          
      <div class="contebox">
        <div class="formtitle"><h2>メール設定</h2></div>
          <form id="mail_form" name="mail_form" method="POST" action="{$smarty.const.ADMIN_URL}basis/mail/preview-popup#" enctype="multipart/form-data">
          <input type="hidden" name="stFormData" value="{$stFormData}">
          <table class="form-table table-v">
            <tr>
              <th class="w15 p10">
                件名
              </th>
              <td class="p10">
                {assign var="key1" value="d_mail_setting_Title"}
                {$arrResult[$key1]}
              </td>
            </tr>
            <tr>
              <th class="w15 p10">
                本文
              </th>
              <td class="p10">
                {assign var="key1" value="d_mail_setting_Content"}
                {$arrResult[$key1]}
              </td>
            </tr>
          </table>
          </form>            
      </div>
                  
      <div class="center"><input type="button" value="閉じる" onClick="execClose();"></div>

    </section>
  </div><!-- .mainbox end -->
</div><!-- #main_block end -->
    
    
<script type="text/javascript">
    // 閉じる
    function execClose() {
        window.close();
    }
</script>
