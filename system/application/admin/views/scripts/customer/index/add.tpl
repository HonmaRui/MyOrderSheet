<section>
    <div id="main-block">
        <div class="pagetitle clearfix">
            <h1>{$stPageTitle}</h1>
        </div>

        <section>
            <div class="mainbox">
                <form id="customer_add_form" name="customer_add_form" method="POST" action="{$smarty.const.ADMIN_URL}customer/{if $bIsEdit}edit/{$iCustomerID}{else}add{/if}" enctype="multipart/form-data">
                    <input type="hidden" name="mode" value="">
                    <input type="hidden" name="zip" value="">
                    <input type="hidden" name="type" value="">
                    <input type="hidden" name="delID" value="">
                    <input type="hidden" name="csrf" value="{$stCsrf}">
                    <input type="hidden" name="csrfdelete" value="{$stCsrfDelete}">
                    <div class="outbox-col2">
                        <div class="contebox">
                            <div class="formtitle">
                                <div class="formtitle-l">
                                    <h2>{$stPageTitle}</h2>
                                </div>
                                {if $bIsEdit}
                                    <div class="formtitle-r">
                                        {html_options name="arrDeleteMenu" options=$arrDeleteMenu}
                                        <input type="button" class="btn-small" onClick="deleteCustomer('{$iCustomerID}')" value="実行">
                                    </div>
                                {/if}
                            </div>
                            <table class="form-table table-v">
                                {if $bIsEdit}
                                    <tr>
                                        <th class="w20">顧客ID</th>
                                        <td class="w73">{$iCustomerID}</td>
                                    </tr>
                                {/if}
                                <tr>
                                    <th class="w20">電話番号</th>
                                    <td class="w73">
                                        {assign var="key" value="d_customer_TelNo"}
                                        {if $arrErrorMessage[$key] != ""}
                                            <div class="error-mess"><span class="error-text">{$arrErrorMessage[$key]}</span></div>
                                            {/if}
                                        <input type="text" class="w30{if $arrErrorMessage[$key] != ""} required not-input{/if}" name="{$key}" value="{$arrForm[$key]}"><span class="text-limit">(数字)</span>
                                    </td>                        
                                </tr>
                                <tr>
                                    <th class="w20">郵便番号<span class="req"><img src="{$smarty.const.ADMIN_IMG_DIR}common/icon05.png" width="34" height="17" alt="必須項目"></span></th>
                                    <td class="w73">
                                        {assign var="key" value="d_customer_Zip"}
                                        {if $arrErrorMessage[$key] != ""}
                                            <div class="error-mess"><span class="error-text">{$arrErrorMessage[$key]}</span></div>
                                            {/if}
                                        <input type="text" onKeyUp="AjaxZip3.zip2addr(this,'','d_customer_PrefCode','d_customer_Address1');" class="w30 required{if $arrErrorMessage[$key] != ""} not-input{/if}" name="{$key}" id="{$key}" value="{$arrForm[$key]}"><span class="text-limit">(数字)</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="w20">住所1<span class="req"><img src="{$smarty.const.ADMIN_IMG_DIR}common/icon05.png" width="34" height="17" alt="必須項目"></span></th>
                                    <td class="w73">
                                        {assign var="key" value="d_customer_PrefCode"}
                                        {if $arrErrorMessage[$key] != ""}
                                            <div class="error-mess"><span class="error-text">{$arrErrorMessage[$key]}</span></div>
                                            {/if}
                                        <select name="{$key}" {if $arrErrorMessage[$key] != ""}class="required not-input"{/if}>
                                            <option value="">都道府県を選択</option>
                                            {html_options options=$arrPref selected=$arrForm[$key]}
                                        </select>
                                        {assign var="key" value="d_customer_Address1"}
                                        {if $arrErrorMessage[$key] != ""}
                                            <div class="error-mess"><span class="error-text">{$arrErrorMessage[$key]}</span></div>
                                            {/if}
                                        <input type="text" class="w70 required{if $arrErrorMessage[$key] != ""} not-input{/if}" name="{$key}" value="{$arrForm[$key]}"><span class="text-limit">(120文字)</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="w20">住所2</th>
                                    <td class="w73">
                                        {assign var="key" value="d_customer_Address2"}
                                        {if $arrErrorMessage[$key] != ""}
                                            <div class="error-mess"><span class="error-text">{$arrErrorMessage[$key]}</span></div>
                                            {/if}
                                        <input type="text" class="w87{if $arrErrorMessage[$key] != ""} required not-input{/if}" name="{$key}" value="{$arrForm[$key]}"><span class="text-limit">(120文字)</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="w20">会社名</th>
                                    <td class="w73">
                                        {assign var="key" value="d_customer_CompanyName"}
                                        {if $arrErrorMessage[$key] != ""}
                                            <div class="error-mess"><span class="error-text">{$arrErrorMessage[$key]}</span></div>
                                            {/if}
                                        <input type="text" class="w87{if $arrErrorMessage[$key] != ""} required not-input{/if}" name="{$key}" value="{$arrForm[$key]}"><span class="text-limit">(60文字)</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="w20">部署名</th>
                                    <td class="w73">
                                        {assign var="key" value="d_customer_DepartmentName"}
                                        {if $arrErrorMessage[$key] != ""}
                                            <div class="error-mess"><span class="error-text">{$arrErrorMessage[$key]}</span></div>
                                            {/if}
                                        <input type="text" class="w87{if $arrErrorMessage[$key] != ""} required not-input{/if}" name="{$key}" value="{$arrForm[$key]}"><span class="text-limit">(60文字)</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="w20">メールアドレス</th>
                                    <td class="w73">
                                        {assign var="key" value="d_customer_EmailAddress"}
                                        {if $arrErrorMessage[$key] != ""}
                                            <div class="error-mess"><span class="error-text">{$arrErrorMessage[$key]}</span></div>
                                            {/if}
                                        <input type="text" class="w87{if $arrErrorMessage[$key] != ""} required not-input{/if}" name="{$key}" value="{$arrForm[$key]}"><span class="text-limit">(100文字)</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="w20">顧客名<span class="req"><img src="{$smarty.const.ADMIN_IMG_DIR}common/icon05.png" width="34" height="17" alt="必須項目"></span></th>
                                    <td class="w73">
                                        {assign var="key" value="d_customer_Name"}
                                        {if $arrErrorMessage[$key] != ""}
                                            <div class="error-mess"><span class="error-text">{$arrErrorMessage[$key]}</span></div>
                                            {/if}
                                        <input type="text" class="w87 required{if $arrErrorMessage[$key] != ""} not-input{/if}" name="{$key}" value="{$arrForm[$key]}"><span class="text-limit">(60文字)</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="w20">顧客名カナ</th>
                                    <td class="w73">
                                        {assign var="key" value="d_customer_NameKana"}
                                        {if $arrErrorMessage[$key] != ""}
                                            <div class="error-mess"><span class="error-text">{$arrErrorMessage[$key]}</span></div>
                                            {/if}
                                        <input type="text" class="w87{if $arrErrorMessage[$key] != ""} required not-input{/if}" name="{$key}" value="{$arrForm[$key]}"><span class="text-limit">(60文字)</span>
                                    </td>
                                </tr>
                                <tr>                          
                                    <th class="w20">職業</th>
                                    <td class="w73">
                                        {assign var="key" value="d_customer_JobID"}
                                        {html_options name=$key options=$arrJob selected=$arrForm[$key]}
                                    </td>                        
                                </tr>
                                <tr>                          
                                    <th class="w20">パスワード<span class="req"><img src="{$smarty.const.ADMIN_IMG_DIR}common/icon05.png" width="34" height="17" alt="必須項目"></span></th>
                                    <td class="w73">
                                        {assign var="key" value="d_customer_Password"}
                                        {if $arrErrorMessage[$key] != ""}
                                            <div class="error-mess"><span class="error-text">{$arrErrorMessage[$key]}</span></div>
                                            {/if}
                                        <input type="password" class="w80{if $arrErrorMessage[$key] != ""} required not-input{/if}" name="{$key}" value="{$arrForm[$key]}">
                                    </td>                     
                                </tr>
                                <tr>                          
                                    <th class="w20">顧客ランク</th>
                                    <td class="w73">
                                        {assign var="key" value="d_customer_CustomerRankID"}
                                        {html_options name=$key options=$arrRank selected=$arrForm[$key]}
                                    </td>                     
                                </tr>
                                <tr>                          
                                    <th class="w20">退会</th>
                                    <td class="w73">
                                        {assign var="key" value="d_customer_SignedOut"}
                                        <input type="checkbox" name="{$key}" value="1"{if $arrForm[$key] == 1} checked{/if}>退会済み(チェックをはずすと退会状態を解除できます)
                                    </td>                     
                                </tr>
                                <tr>
                                    <th class="w20">備考</th>
                                    <td class="w73">
                                        {assign var="key" value="d_customer_Remarks"}
                                        {if $arrErrorMessage[$key] != ""}
                                            <div class="error-mess"><span class="error-text">{$arrErrorMessage[$key]}</span></div>
                                            {/if}
                                        <textarea rows="5" class="w100{if $arrErrorMessage[$key] != ""} required not-input{/if}" name="{$key}">{$arrForm[$key]}</textarea>
                                    </td>
                                </tr>
                                {if $bIsEdit}
                                    <tr>
                                        <th class="w20">登録更新情報</th>
                                        <td class="w73">
                                            新規登録： {$arrForm["d_customer_CreatedTime"]} [{if $arrForm["d_customer_CreatedByID"] != 0}{$arrMember[$arrForm["d_customer_CreatedByID"]]}{else}システム{/if}]<br>
                                            最終更新： {$arrForm["d_customer_UpdatedTime"]} [{if $arrForm["d_customer_UpdatedByID"] != 0}{$arrMember[$arrForm["d_customer_UpdatedByID"]]}{else}システム{/if}]
                                            <input type="hidden" name="d_customer_CreatedTime" value="{$arrForm["d_customer_CreatedTime"]}">
                                            <input type="hidden" name="d_customer_UpdatedTime" value="{$arrForm["d_customer_UpdatedTime"]}">
                                            <input type="hidden" name="d_customer_CreatedByID" value="{$arrForm["d_customer_CreatedByID"]}">
                                            <input type="hidden" name="d_customer_UpdatedByID" value="{$arrForm["d_customer_UpdatedByID"]}">
                                        </td>
                                    </tr>
                                {/if}
                            </table>
                        </div>
                    </div>
                    <div class="ac">
                        {if $bIsEdit}
                            <input type="button" id="back" class="btn-gray btn-save" value="検索結果へ戻る">
                        {/if}
                        <input type="button" class="btn-gray btn-save ml10" value="上記の内容で保存する" id="create">
                    </div>
                    <div class="outbox-col2 mt10">
                        <div class="contebox">
                            <div class="formtitle clearfix"><h2>購入履歴</h2></div>
                            <div class="list">
                                {if count($arrOrder) > 0}
                                    <table id="" class="form-table table-h">
                                        <thead>
                                            <tr>
                                                <th class="w30">受注ID</th>
                                                <th class="w30">受注日</th>
                                                <th class="w30">対応状況</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {foreach from=$arrOrder item=arrData}
                                                <tr>
                                                    <td class="ac"><a href="{$smarty.const.ADMIN_URL}order/edit-order/{$arrData["d_order_OrderMngID"]}/{$arrData["d_order_OrderID"]}">{$arrData["d_order_OrderID"]}</a></td>
                                                    <td class="ac">{$arrData["d_order_CreatedTime"]|date_format:"%Y/%m/%d %H:%M"}</td>
                                                    <td class="ac">{$arrStatus[$arrData["d_order_Status"]]}</td>
                                                </tr>
                                            {/foreach}
                                        </tbody>
                                    </table>
                                {else}
                                    注文履歴はありません。
                                {/if}
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </section>

    </div><!-- #main-block end -->
</section>
<script>
    $(function () {
        // 検索結果へ戻る
        $('#back').click(function () {
            var em = document.customer_add_form;
            em.mode.value = 'searchBack';
            em.action = '{$smarty.const.ADMIN_URL}customer';
            em.submit();
        });

        // 保存
        $('#create').click(function () {
            var em = document.customer_add_form;
            em.mode.value = 'add';
            loading();
            em.submit();
        });
    });

    // 顧客データ削除
    function deleteCustomer(customerID) {
    var MenuSelect = $('select[name="arrDeleteMenu"]').val();
            if (MenuSelect == 1) {
    {if $bIsDeleteConfirm == true}
    if (confirm("{$stConfirmMessage}")) {
    {else}
    if (confirm("顧客ID[" + customerID + "]を削除します。よろしいですか？")) {
    {/if}
    var em = document.customer_add_form;
            em.action = '{$smarty.const.ADMIN_URL}customer/delete';
            em.delID.value = customerID;
            em.mode.value = 'delete';
            loading();
            em.submit();
    }
    }
    }
</script>