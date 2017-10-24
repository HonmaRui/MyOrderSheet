<div id="one_maincolumn">
    <div id="mypagecolumn">
        {include file='../index/header-info.tpl'}
        <div id="mycontentsarea">
            <h2 class="title"><img src="{$smarty.const.FRONT_IMG_SSL}mypage/subtitle02.gif" width="515" height="32" alt="会員登録内容変更"></h2>
            <p>下記の内容で送信してもよろしいでしょうか？<br>
                よろしければ、一番下の「プロユーザー登録完了へ」ボタンをクリックしてください。</p>
            <form name="confirm_form" id="confirm_form" method="post" action="{$smarty.const.SSL_URL}/mypage/change/confirm">
                <input type="hidden" name="mode" value="confirm">
                <input type="hidden" name="csrf" value="{$stCsrf}">
                <input type="hidden" name="d_customer_CompanyName" value="{$arrForm['d_customer_CompanyName']}">
                <input type="hidden" name="d_customer_DepartmentName" value="{$arrForm['d_customer_DepartmentName']}">
                <input type="hidden" name="d_customer_Name" value="{$arrForm['d_customer_Name']}">
                <input type="hidden" name="d_customer_NameKana" value="{$arrForm['d_customer_NameKana']}">
                <input type="hidden" name="d_customer_Zip" value="{$arrForm['d_customer_Zip']}">
                <input type="hidden" name="d_customer_PrefCode" value="{$arrForm['d_customer_PrefCode']}">
                <input type="hidden" name="d_customer_Address1" value="{$arrForm['d_customer_Address1']}">
                <input type="hidden" name="d_customer_Address2" value="{$arrForm['d_customer_Address2']}">
                <input type="hidden" name="d_customer_TelNo" value="{$arrForm['d_customer_TelNo']}">
                <input type="hidden" name="d_customer_JobID" value="{$arrForm['d_customer_JobID']}">
                <input type="hidden" name="d_customer_EmailAddress" value="{$arrForm['d_customer_EmailAddress']}">
                <input type="hidden" name="d_customer_EmailAddress-confirm" value="{$arrForm['d_customer_EmailAddress-confirm']}">
                <table summary="入力内容確認" class="delivname">
                    <tbody>
                        <tr>
                            <th>会社名<span class="attention">※</span></th>
                            <td>{$arrForm['d_customer_CompanyName']}</td>
                        </tr>
                        <tr>
                            <th>部署名</th>
                            <td>{$arrForm['d_customer_DepartmentName']}</td>
                        </tr>
                        <tr>
                            <th>業種<span class="attention">※</span></th>
                            <td>{$arrJob[$arrForm['d_customer_JobID']]}</td>
                        </tr>
                        <tr>
                            <th>氏名<span class="attention">※</span></th>
                            <td>{$arrForm['d_customer_Name']}</td>
                        </tr>
                        <tr>
                            <th>氏名（フリガナ）<span class="attention">※</span></th>
                            <td>{$arrForm['d_customer_NameKana']}</td>
                        </tr>
                        <tr>
                            <th>郵便番号<span class="attention">※</span></th>
                            <td>〒{$arrForm['d_customer_Zip']|substr:0:3}-{$arrForm['d_customer_Zip']|substr:3:6}</td>
                        </tr>
                        <tr>
                            <th>住所<span class="attention">※</span></th>
                            <td>{$arrPref[$arrForm['d_customer_PrefCode']]}{$arrForm['d_customer_Address1']}{$arrForm['d_customer_Address2']}</td>
                        </tr>
                        <tr>
                            <th>電話番号<span class="attention">※</span></th>
                            <td>{$arrForm['d_customer_TelNo']}</td>
                        </tr>
                        <tr>
                            <th>メールアドレス<span class="attention">※</span></th>
                            <td>{$arrForm['d_customer_EmailAddress']}</td>
                        </tr>
                        <tr>
                            <th>希望するパスワード<span class="attention">※</span><br>
                                <span class="mini">パスワードはカットサンプル請求時に必要です</span>
                            </th>
                            <td>****</td>
                        </tr>
                    </tbody>
                </table>
                <div class="tblareabtn">
                    <a href="javascript:history.back()" onmouseover="chgImg('{$smarty.const.FRONT_IMG_SSL}common/b_back_on.gif', 'back')" onmouseout="chgImg('{$smarty.const.FRONT_IMG_SSL}common/b_back.gif', 'back')">
                        <img src="{$smarty.const.FRONT_IMG_SSL}common/b_back.gif" width="150" height="30" alt="戻る" border="0" name="back" id="back">
                    </a>&nbsp;
                    <input type="image" onmouseover="chgImgImageSubmit('{$smarty.const.FRONT_IMG_SSL}common/b_send_on.gif', this)" onmouseout="chgImgImageSubmit('{$smarty.const.FRONT_IMG_SSL}common/b_send.gif', this)" src="{$smarty.const.FRONT_IMG_SSL}common/b_send.gif" class="box150" alt="送信" border="0" name="send" id="send">
                </div>
            </form>
        </div>
    </div>
</div>
<script>
$(function() {

    // 登録ボタン
    $('#send').click(function() {
        setMode('confirm');
    });

});

function setMode(mode) {
    var em = document.confirm_form;
    em.mode.value = mode;
    em.submit();
}
    
</script>