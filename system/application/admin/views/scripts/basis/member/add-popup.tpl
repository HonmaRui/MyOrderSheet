<section>
    <div id="main-block">
        <div class="pagetitle clearfix">
            <h1>{$stPageTitle}</h1>
        </div>

        <section>
            <div class="mainbox">
                <form id="member_create_form" name="member_create_form" method="POST" action="{$smarty.const.ADMIN_URL}basis/add-popup" enctype="multipart/form-data">
                    <input type="hidden" name="stFormData" value="{$stFormData}">
                    <input type="hidden" name="editMemberID" value="{$iMemberID}">
                    <input type="hidden" name="mode" value="{$stMode}">
                    <input type="hidden" name="csrf" value="{$stCsrf}">
                    <div class="contebox">
                        <div class="formtitle"><h2>{$stPageTitle}</h2></div>
                        <table class="form-table table-v" id="tbl1">                 
                            <tr>
                                <th class="">担当者名<span class="req"><img src="{$smarty.const.ADMIN_IMG_DIR}common/icon05.png" width="34" height="17" alt="必須項目"></span></th>
                                <td class="">
                                    {assign var="key" value="d_system_member_Name"}
                                    {if $arrErrorMessage[$key] != ""}
                                        <div class="error-mess"><span class="error-text">{$arrErrorMessage[$key]}</span></div>
                                    {/if}
                                    <input type="text" name="{$key}" value="{$arrForm[$key]}" class="w80 required {if $arrErrorMessage[$key] != ""}not-input{/if}"><span class="text-limit">(20文字)</span>
                                </td>
                            </tr>
                            <tr>
                                <th class="">所属</th>
                                <td class="">
                                    {assign var="key" value="d_system_member_Department"}
                                    <input type="text" class="w80" name="{$key}" value="{$arrForm[$key]}">
                                </td>
                            </tr>
                            <tr>
                                <th class="">ログインID<span class="req"><img src="{$smarty.const.ADMIN_IMG_DIR}common/icon05.png" width="34" height="17" alt="必須項目"></span></th>
                                <td class="">
                                    {assign var="key" value="d_system_member_LoginID"}
                                    {if $arrErrorMessage[$key] != ""}
                                        <div class="error-mess"><span class="error-text">{$arrErrorMessage[$key]}</span></div>
                                    {/if}
                                    <input type="text" name="{$key}" value="{$arrForm[$key]}" class="w80 required {if $arrErrorMessage[$key] != ""}not-input{/if}"><span class="text-limit">(50文字)</span>
                                </td>
                            </tr>
                            <tr>
                                <th class="">パスワード<span class="req"><img src="{$smarty.const.ADMIN_IMG_DIR}common/icon05.png" width="34" height="17" alt="必須項目"></span></th>
                                <td class="">
                                    {assign var="key" value="d_system_member_Password"}
                                    {if $arrErrorMessage[$key] != ""}
                                        <div class="error-mess"><span class="error-text">{$arrErrorMessage[$key]}</span></div>
                                    {/if}
                                    <input type="password" name="{$key}" value="{$arrForm[$key]}" class="w80 required {if $arrErrorMessage[$key] != ""}not-input{/if}"><span class="text-limit">(100文字)</span>
                                </td>
                            </tr>
                            <tr>
                                <th class="">権限<span class="req"><img src="{$smarty.const.ADMIN_IMG_DIR}common/icon05.png" width="34" height="17" alt="必須項目"></span></th>
                                <td class="">
                                    {assign var="key" value="d_system_member_Authority"}
                                    {if $arrErrorMessage[$key] != ""}
                                        <div class="error-mess"><span class="error-text">{$arrErrorMessage[$key]}</span></div>
                                        {html_options name=$key options=$arrSystemAuthority selected=$arrForm[$key] class="required not-input"}
                                    {else}
                                        {html_options name=$key options=$arrSystemAuthority selected=$arrForm[$key]}
                                    {/if}
                                </td>
                            </tr>                                
                        </table>
                    </div>
                    <div class="ac">
                        <input type="button" class="btn-gray btn-save" value="上記の内容で保存する" id="create">
                    </div>
                </form>
            </div>
        </section>

    </div><!-- #main-block end -->
</section>
<script>
    $(function () {
        $('#create').click(function () {
            setMode('create');
        });
    });

    function setMode(mode) {
        var em = document.member_create_form;
        em.mode.value = mode;
        loading();
        em.submit();
    }
</script>