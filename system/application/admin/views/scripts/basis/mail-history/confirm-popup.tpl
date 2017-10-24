    <div class="pagetitle clearfix">
      <h1>メール確認</h1>
    </div>    
    
    <div id="main_block">
      <div class="mainbox">
        <section>
          <div class="contebox">
            <form id="mailhistory_confirmform" name="mailhistory_confirmform" method="POST" action="{$smarty.const.ADMIN_URL}basis/mail_history/confirm-popup#" enctype="multipart/form-data">
            <input type="hidden" name="mailhistoryid" value="">
            <table class="form-table table-v">
              <tr>
                <th class="w20">
                  メール種類
                </th>
                <td class="w100">
                  {assign var="key1" value="d_mail_history_TemplateID"}
                  {if $arrTemplate[$arrResult[$key1]] != ""}{$arrTemplate[$arrResult[$key1]]}{else}指定なし{/if}
                </td>
              </tr>
              <tr>
                <th class="w20">
                  顧客名
                </th>
                <td class="w20">
                  {assign var="key1" value="d_mail_history_CustomerName"}
                  {$arrResult[$key1]}
                </td>
              </tr>
              <tr>
                <th class="w20">
                  タイトル
                </th>
                <td class="w20">
                  {assign var="key1" value="d_mail_history_Title"}
                  {$arrResult[$key1]}
                </td>
              </tr>
              <tr>
                <th class="w20">
                  配信日
                </th>
                <td class="w30">
                  {assign var="key1" value="d_mail_history_SendDate"}
                  {$arrResult[$key1]}
                </td>
              </tr>
              <tr>
                <th class="w20">
                  本文
                </th>
                <td>
                  {assign var="key1" value="d_mail_history_Content"}
                  {$arrResult[$key1]}
                </td>
              </tr>
            </table>            
          </div>
          <div class="center"><input type="button" value="閉じる" class="btn-gray" onClick="execClose();"></div>
        </section>
      </div><!-- .mainbox end -->
    </div><!-- #main_block end -->

<script type="text/javascript">
    // 閉じる
    function execClose() {
        window.close();
    }
</script>
