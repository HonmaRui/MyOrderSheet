<div id="two_maincolumn">
    <div id="undercolumn">
        <div id="undercolumn_entry">
            <h2 class="title">
                <img src="{$smarty.const.FRONT_IMG_SSL}entry/title.jpg" width="580" height="40" alt="会員登録" />
            </h2>
            <p>プロユーザー登録をご希望のお客様は、下記のフォームに必要事項を入力のうえ、送信してください。<br>
                <span class="attention">※</span>は必須項目です。</p>
            <form name="regist_form" id="regist_form" method="post" action="{$smarty.const.SSL_URL}/entry/regist">
                <input type="hidden" name="mode" value="regist">
                <input type="hidden" name="csrf" value="{$stCsrf}">
                <table summary="会員登録フォーム">
                    <tr>
                        <th>会社名<span class="attention">※</span></th>
                        <td>
                            {assign var="key" value="d_customer_CompanyName"}
                            {if $arrErrorMessage[$key] != ""}<div class="attention">{$arrErrorMessage[$key]}</div>{/if}
                            <input type="text" name="{$key}" value="{$arrForm[$key]}" maxlength="50" style="ime-mode: active;" size="15" class="box120{if $arrErrorMessage[$key] != ""} input-error{/if}" />
                        </td>
                    </tr>
                    <tr>
                        <th>部署名</th>
                        <td>
                            {assign var="key" value="d_customer_DepartmentName"}
                            <input type="text" name="{$key}" value="{$arrForm[$key]}" maxlength="50" style="ime-mode: active;" size="15" class="box120" />
                        </td>
                    </tr>
                    <tr>
                        <th>氏名<span class="attention">※</span></th>
                        <td>
                            {assign var="key" value="d_customer_Name"}
                            {if $arrErrorMessage[$key] != ""}<div class="attention">{$arrErrorMessage[$key]}</div>{/if}
                            <input type="text" name="{$key}" value="{$arrForm[$key]}" maxlength="50" style="ime-mode: active;" size="15" class="box120{if $arrErrorMessage[$key] != ""} input-error{/if}" />
                        </td>
                    </tr>
                    <tr>
                        <th>氏名（フリガナ）<span class="attention">※</span></th>
                        <td>
                            {assign var="key" value="d_customer_NameKana"}
                            {if $arrErrorMessage[$key] != ""}<div class="attention">{$arrErrorMessage[$key]}</div>{/if}
                            <input type="text" name="{$key}" value="{$arrForm[$key]}" maxlength="50" style="ime-mode: active;" size="15" class="box120{if $arrErrorMessage[$key] != ""} input-error{/if}" />
                        </td>
                    </tr>
                    <tr>
                        <th>郵便番号<span class="attention">※</span></th>
                        <td>
                            {assign var="key" value="d_customer_Zip"}
                            {if $arrErrorMessage[$key] != ""}<div class="attention">{$arrErrorMessage[$key]}</div>{/if}
                            〒&nbsp;<input type="text" name="{$key}" size="10" maxlength="8" onKeyUp="AjaxZip3.zip2addr(this,'','d_customer_PrefCode','d_customer_Address1');" class="box120{if $arrErrorMessage[$key] != ""} input-error{/if}">
                            <a href="http://search.post.japanpost.jp/zipcode/" target="_blank"><span class="fs12">&nbsp;郵便番号検索</span></a>
                            <p class="mini"><em>ハイフンは含めずに入力してください。</em></p>
                        </td>
                    </tr>
                    <tr>
                        <th>住所<span class="attention">※</span></th>
                        <td>
                            {assign var="key" value="d_customer_PrefCode"}
                            {if $arrErrorMessage[$key] != ""}<div class="attention">{$arrErrorMessage[$key]}</div>{/if}
                            <select name="{$key}" class="{if $arrErrorMessage[$key] != ""}input-error{/if}">
                                <option value="">都道府県を選択</option>
                                {html_options options=$arrPref selected=$arrForm[$key]}
                            </select>
                            {assign var="key" value="d_customer_Address1"}
                            {if $arrErrorMessage[$key] != ""}<div class="attention">{$arrErrorMessage[$key]}</div>{/if}
                            <p class="mini"><input type="text" name="{$key}" value="{$arrForm[$key]}" size="60" class="box300{if $arrErrorMessage[$key] != ""} input-error{/if}" style="ime-mode: active;" /><br />
                                市区町村名（例：目黒区上目黒）</p>
                            {assign var="key" value="d_customer_Address2"}
                            {if $arrErrorMessage[$key] != ""}<div class="attention">{$arrErrorMessage[$key]}</div>{/if}
                            <p class="mini"><input type="text" name="{$key}" value="{$arrForm[$key]}" size="60" class="box300{if $arrErrorMessage[$key] != ""} input-error{/if}" style="ime-mode: active;" /><br />
                                番地・ビル名（例：1-26-9 中目黒オークラビル 4F）</p>
                            <p class="mini"><em>住所は2つに分けてご記入いただけます。マンション名は必ず記入してください。</em></p>
                        </td>
                    </tr>
                    <tr>
                        <th>電話番号<span class="attention">※</span></th>
                        <td>
                            {assign var="key" value="d_customer_TelNo"}
                            {if $arrErrorMessage[$key] != ""}<div class="attention">{$arrErrorMessage[$key]}</div>{/if}
                            <input type="text" name="{$key}" value="{$arrForm[$key]}" maxlength="11" size="11" style="ime-mode: disabled;" class="box120{if $arrErrorMessage[$key] != ""} input-error{/if}" />
                            <p class="mini"><em>ハイフンは含めずに入力してください。</em></p>
                        </td>
                    </tr>
                    <tr>
                        <th>業種<span class="attention">※</span></th>
                        <td>
                            {assign var="key" value="d_customer_JobID"}
                            <select name="{$key}">
                                {html_options options=$arrJob selected=$arrForm[$key]}
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>メールアドレス<span class="attention">※</span></th>
                        <td>
                            {assign var="key" value="d_customer_EmailAddress"}
                            {if $arrErrorMessage[$key] != ""}<div class="attention">{$arrErrorMessage[$key]}</div>{/if}
                            <input type="text" name="{$key}" value="{$arrForm[$key]}" style="ime-mode: disabled;" maxlength="200" size="40" class="box300{if $arrErrorMessage[$key] != ""} input-error{/if}" /><br />
                            {assign var="key" value="d_customer_EmailAddress-confirm"}
                            {if $arrErrorMessage[$key] != ""}<div class="attention">{$arrErrorMessage[$key]}</div>{/if}
                            <input type="text" name="{$key}" value="{$arrForm[$key]}" style="ime-mode: disabled;" maxlength="200" size="40" class="box300{if $arrErrorMessage[$key] != ""} input-error{/if}" /><br />
                            <p class="mini"><em>確認のため2度入力してください。</em></p>
                        </td>
                    </tr>
                    <tr>
                        <th>希望するパスワード<span class="attention">※</span><br />
                            <span class="mini">パスワードはカットサンプル請求時に必要です</span></th>
                        <td>
                            {assign var="key" value="d_customer_Password"}
                            {if $arrErrorMessage[$key] != ""}<div class="attention">{$arrErrorMessage[$key]}</div>{/if}
                            <input type="password" name="{$key}" value="" maxlength="10" style="" size="15" class="box120{if $arrErrorMessage[$key] != ""} input-error{/if}" />
                            <p><em>半角英数字4～10文字でお願いします。（記号不可）</em></p>
                            {assign var="key" value="d_customer_Password-confirm"}
                            {if $arrErrorMessage[$key] != ""}<div class="attention">{$arrErrorMessage[$key]}</div>{/if}
                            <input type="password" name="{$key}" value="" maxlength="10" style="" size="15" class="box120{if $arrErrorMessage[$key] != ""} input-error{/if}" />
                            <p><em>確認のために2度入力してください。</em></p>
                        </td>
                    </tr>
                </table>

                <div class="tblareabtn">
                    <input type="image" onmouseover="chgImgImageSubmit('{$smarty.const.FRONT_IMG_SSL}common/b_confirm_on.gif', this)" onmouseout="chgImgImageSubmit('{$smarty.const.FRONT_IMG_SSL}common/b_confirm.gif', this)" src="{$smarty.const.FRONT_IMG_SSL}common/b_confirm.gif" class="box150" alt="確認ページへ" name="confirm" id="confirm" />
                </div>
            </form>
        </div>
    </div>
</div>
                
<script>
$(function() {

    {if $arrForm["d_customer_Zip"] != ""}
    // 住所保持
    $('input[name="d_customer_Zip"]').val('{$arrForm["d_customer_Zip"]}');
    {/if}

});
</script>