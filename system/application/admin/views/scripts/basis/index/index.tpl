<section>
    <div id="main-block">
        <div class="pagetitle clearfix">
            <h1>{$stPageTitle}</h1>
        </div>

        <section>
            <div class="mainbox">
                <form id="baseinfo_form" name="baseinfo_form" method="POST" action="{$smarty.const.ADMIN_URL}basis" enctype="multipart/form-data">
                    <input type="hidden" name="mode" value="">
                    <input type="hidden" name="stFormData" value="{$stFormData}">
                    <input type="hidden" name="csrf" value="{$stCsrf}">
                    <div class="outbox-col2">
                        <div class="contebox">
                            <table class="form-table table-v b-table">
                                <tr>
                                    <th>郵便番号<span class="req"><img src="{$smarty.const.ADMIN_IMG_DIR}common/icon05.png" width="34" height="17" alt="必須項目"></span></th>
                                    <td colspan="3">
                                        {assign var="key" value="d_baseinfo_Zip"}
                                        {if $arrErrorMessage[$key] != ""}
                                            <div class="error-mess"><span class="error-text">{$arrErrorMessage[$key]}</span></div>
                                            {/if}
                                        <input type="text" name="{$key}" class="w30 required {if $arrErrorMessage[$key] != ""}not-input{/if}" value="{$arrForm[$key]}"><span class="text-limit">(数字)</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>住所1<span class="req"><img src="{$smarty.const.ADMIN_IMG_DIR}common/icon05.png" width="34" height="17" alt="必須項目"></span></th>
                                    <td colspan="3">
                                        {assign var="key" value="d_baseinfo_PrefCode"}
                                        {if $arrErrorMessage[$key] != ""}
                                            <div class="error-mess"><span class="error-text">{$arrErrorMessage[$key]}</span></div>
                                            {/if}
                                        <select name="{$key}" class="{if $arrErrorMessage[$key] != ""}required not-input{/if}">
                                            <option value="">--選択--</option>
                                            {html_options options=$arrPref selected=$arrForm[$key]}
                                        </select>
                                        {assign var="key" value="d_baseinfo_Address1"}
                                        {if $arrErrorMessage[$key] != ""}
                                            <div class="error-mess"><span class="error-text">{$arrErrorMessage[$key]}</span></div>
                                            {/if}
                                        <input type="text" name="{$key}" class="w71 required {if $arrErrorMessage[$key] != ""}not-input{/if}" value="{$arrForm[$key]}"><span class="text-limit">(120文字)</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>住所2</th>
                                    <td colspan="3">
                                        {assign var="key" value="d_baseinfo_Address2"}
                                        {if $arrErrorMessage[$key] != ""}
                                            <div class="error-mess"><span class="error-text">{$arrErrorMessage[$key]}</span></div>
                                            {/if}
                                        <input type="text" name="{$key}" class="w83 {if $arrErrorMessage[$key] != ""}required not-input{/if}" value="{$arrForm[$key]}"><span class="text-limit">(120文字)</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>店名<span class="req"><img src="{$smarty.const.ADMIN_IMG_DIR}common/icon05.png" width="34" height="17" alt="必須項目"></span></th>
                                    <td colspan="3">
                                        {assign var="key" value="d_baseinfo_Name"}
                                        {if $arrErrorMessage[$key] != ""}
                                            <div class="error-mess"><span class="error-text">{$arrErrorMessage[$key]}</span></div>
                                            {/if}
                                        <input type="text" name="{$key}" class="w70 required {if $arrErrorMessage[$key] != ""}not-input{/if}" value="{$arrForm[$key]}"><span class="text-limit">(60文字)</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>受注情報受付メールアドレス</th>
                                    <td colspan="3">
                                        {assign var="key" value="d_baseinfo_MailAddress1"}
                                        {if $arrErrorMessage[$key] != ""}
                                            <div class="error-mess"><span class="error-text">{$arrErrorMessage[$key]}</span></div>
                                            {/if}
                                        <input type="text" name="{$key}" class="w70 {if $arrErrorMessage[$key] != ""}required not-input{/if}" value="{$arrForm[$key]}"><span class="text-limit">(100文字)</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>問い合わせ受付メールアドレス</th>
                                    <td colspan="3">
                                        {assign var="key" value="d_baseinfo_MailAddress2"}
                                        {if $arrErrorMessage[$key] != ""}
                                            <div class="error-mess"><span class="error-text">{$arrErrorMessage[$key]}</span></div>
                                            {/if}
                                        <input type="text" name="{$key}" class="w70 {if $arrErrorMessage[$key] != ""}required not-input{/if}" value="{$arrForm[$key]}"><span class="text-limit">(100文字)</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>送信エラー受付メールアドレス</th>
                                    <td colspan="3">
                                        {assign var="key" value="d_baseinfo_MailAddress3"}
                                        {if $arrErrorMessage[$key] != ""}
                                            <div class="error-mess"><span class="error-text">{$arrErrorMessage[$key]}</span></div>
                                            {/if}
                                        <input type="text" name="{$key}" class="w70 {if $arrErrorMessage[$key] != ""}required not-input{/if}" value="{$arrForm[$key]}"><span class="text-limit">(100文字)</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>メール送信元メールアドレス</th>
                                    <td colspan="3">
                                        {assign var="key" value="d_baseinfo_MailAddress4"}
                                        {if $arrErrorMessage[$key] != ""}
                                            <div class="error-mess"><span class="error-text">{$arrErrorMessage[$key]}</span></div>
                                            {/if}
                                        <input type="text" name="{$key}" class="w70 {if $arrErrorMessage[$key] != ""}required not-input{/if}" value="{$arrForm[$key]}"><span class="text-limit">(100文字)</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>メルマガ送信元メールアドレス</th>
                                    <td colspan="3">
                                        {assign var="key" value="d_baseinfo_MailAddress5"}
                                        {if $arrErrorMessage[$key] != ""}
                                            <div class="error-mess"><span class="error-text">{$arrErrorMessage[$key]}</span></div>
                                            {/if}
                                        <input type="text" name="{$key}" class="w70 {if $arrErrorMessage[$key] != ""}required not-input{/if}" value="{$arrForm[$key]}"><span class="text-limit">(100文字)</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>電話番号<span class="req"><img src="{$smarty.const.ADMIN_IMG_DIR}common/icon05.png" width="34" height="17" alt="必須項目"></span></th>
                                    <td>
                                        {assign var="key" value="d_baseinfo_TelNo"}
                                        {if $arrErrorMessage[$key] != ""}
                                            <div class="error-mess"><span class="error-text">{$arrErrorMessage[$key]}</span></div>
                                            {/if}
                                        <input type="text" name="{$key}" class="w30 required {if $arrErrorMessage[$key] != ""}not-input{/if}" value="{$arrForm[$key]}"><span class="text-limit">(数字)</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>FAX番号</th>
                                    <td>
                                        {assign var="key" value="d_baseinfo_FaxNo"}
                                        {if $arrErrorMessage[$key] != ""}
                                            <div class="error-mess"><span class="error-text">{$arrErrorMessage[$key]}</span></div>
                                            {/if}
                                        <input type="text" name="{$key}" class="w30 {if $arrErrorMessage[$key] != ""}required not-input{/if}" value="{$arrForm[$key]}"><span class="text-limit">(数字)</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="ac">
                        <input type="button" class="btn-gray ml10 btn-save" value="上記の内容で保存する" id="save">
                    </div>
                </form>
            </div>
        </section>
    </div>
</section>
<script>
    $(function () {
        // 保存
        $('#save').click(function () {
            setMode('save');
        });
    });

    function setMode(mode) {
        var em = document.baseinfo_form;
        em.mode.value = mode;
        em.action = '{$smarty.const.ADMIN_URL}basis';
        loading();
        em.submit();
    }
</script>