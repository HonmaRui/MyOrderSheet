<section>
    <div id="main-block">
        <div class="pagetitle clearfix">
            <h1>{$stPageTitle}</h1>
        </div>

        <section>
            <div class="mainbox">
                <form id="member_add_form" name="member_add_form" method="POST" action="{$smarty.const.ADMIN_URL}basis/member">
                    <input type="hidden" name="csrf" value="{$stCsrf}">
                    <input type="hidden" name="editMemberID" value="">
                    <input type="hidden" name="editMemberName" value="">
                    <input type="hidden" name="delMemberID" value="">
                    <input type="hidden" name="srcMemberID" value="">
                    <input type="hidden" name="dstMemberID" value="">
                    <input type="hidden" name="moveMemberID" value="">
                    <input type="hidden" name="updateMemberID" value="">
                    <input type="hidden" name="posnum" value="">
                    <input type="hidden" name="mode" value="">
                    <div class="contebox">
                        {if count($arrResult) >  0}
                            <div class="list">
                                <table class="form-table table-h" id="tbl1">
                                    <tr>
                                        <th class="">担当者ID</th>
                                        <th class="w14">権限</th>
                                        <th class="w14">担当者名</th>
                                        <th class="w14">所属</th>
                                        <th class="w17">有効/無効</th>
                                        <th class="">編集</th>
                                        <th class="">削除</th>
                                        <th class="w24">表示順</th>
                                    </tr>
                                    {foreach from=$arrResult item=arrData name=member}
                                        <tr {if $arrData["d_system_member_Run"] == "2"}class="gray-table"{/if}>
                                            <td class="">{$arrData["d_system_member_SystemMemberID"]}</td>
                                            <td class="">{$arrAuthority[$arrData["d_system_member_Authority"]]}</td>
                                            <td class="">{$arrData["d_system_member_Name"]}</td>
                                            <td class="">{$arrData["d_system_member_Department"]}</td>
                                            <td class="">
                                                {assign var="key" value="d_system_member_Run"}
                                                {html_radios name=$key|cat:$arrData.d_system_member_SystemMemberID options=$arrRun separator=' ' selected=$arrData["d_system_member_Run"] onchange="changeRun('{$arrData.d_system_member_SystemMemberID}');"}
                                            </td>
                                            <td class="">
                                                <input type="button" class="btn-small" value="編集" onClick="editMember('{$arrData["d_system_member_SystemMemberID"]}');">
                                            </td>
                                            <td class="">
                                                {if $arrData["d_system_member_SystemMemberID"] != $iSystemMemberID}
                                                <input type="button" class="btn-small" value="削除" onClick="deleteMember({$arrData["d_system_member_SystemMemberID"]});">
                                                {/if}
                                            </td>
                                            <td class="">
                                                {if count($arrResult) == 1}--
                                                {else}
                                                    {assign var="key" value="posnum"|cat:$smarty.foreach.member.index}
                                                    {if $arrErrorMessage[$arrData["d_system_member_SystemMemberID"]][$key] != ""}
                                                        <div class="error-mess"><span class="error-text">{$arrErrorMessage[$arrData["d_system_member_SystemMemberID"]][$key]}</span></div>
                                                        {/if}
                                                        {if $smarty.foreach.member.first}
                                                        <input class="upBtn" type="button" value="下へ" onClick="changeOrder({$arrData["d_system_member_SystemMemberID"]}, {$arrData["nextMemberID"]});">
                                                    {elseif $smarty.foreach.member.last}
                                                        <input class="downBtn" type="button" value="上へ" onClick="changeOrder({$arrData["d_system_member_SystemMemberID"]}, {$arrData["beforeMemberID"]});">
                                                    {else}
                                                        <input class="downBtn" type="button" value="上へ" onClick="changeOrder({$arrData["d_system_member_SystemMemberID"]}, {$arrData["beforeMemberID"]});">
                                                        <input class="upBtn" type="button" value="下へ" onClick="changeOrder({$arrData["d_system_member_SystemMemberID"]}, {$arrData["nextMemberID"]});">
                                                    {/if}
                                                    <input class="posNumTb {if $arrErrorMessage[$arrData["d_system_member_SystemMemberID"]][$key] != ""}required not-input{/if}" type="text" name="{$key}" id="{$key}" value="{$arrForm[$key]}">
                                                    <input class="moveBtn" type="button" value="番目へ移動" onClick="moveOrder({$arrData["d_system_member_SystemMemberID"]}, {$smarty.foreach.member.index});">
                                                {/if}
                                            </td>
                                        </tr>
                                    {/foreach}
                                </table>
                            {else}
                                <div class="list-red">担当者が登録されていません。</div>
                            {/if}
                        </div>
                    </div>
                    <div class="ac mt10">
                        <input type="button" class="btn-gray btn-save ml10" value="担当者新規登録" onClick="addMember();">
                    </div>
                </form>

            </div>
        </section>

    </div><!-- #main-block end -->
</section>
<script>
    
    function editMember(editMemberID) {
        var em = document.member_add_form;
        em.mode.value = 'edit';
        em.editMemberID.value = editMemberID;
        submitToPopupWindow('member_add_form', '{$smarty.const.ADMIN_URL}basis/add-popup', '950', '560');
        em.editMemberID.value = "";
    }
    
    function addMember() {
        var em = document.member_add_form;
        em.mode.value = 'add';
        submitToPopupWindow('member_add_form', '{$smarty.const.ADMIN_URL}basis/add-popup', '950', '560');
    }

    function deleteMember(delMemberID) {
        if (confirm('一度削除したデータは元に戻せません。\n削除してもよろしいですか？')) {
            var em = document.member_add_form;
            em.mode.value = 'del';
            em.delMemberID.value = delMemberID;
            em.action = '{$smarty.const.ADMIN_URL}basis/member';
            loading();
            em.submit();
        }
    }

    function changeOrder(srcMemberID, dstMemberID) {
        var em = document.member_add_form;
        em.mode.value = 'order';
        em.srcMemberID.value = srcMemberID;
        em.dstMemberID.value = dstMemberID;
        em.action = '{$smarty.const.ADMIN_URL}basis/member';
        loading();
        em.submit();
    }

    function moveOrder(moveMemberID, count) {
        var em = document.member_add_form;
        em.mode.value = 'move';
        em.moveMemberID.value = moveMemberID;
        em.posnum.value = count;
        em.action = '{$smarty.const.ADMIN_URL}basis/member';
        loading();
        em.submit();
    }
    
    function changeRun(systemMemberID) {
        var em = document.member_add_form;
        em.mode.value = 'update';
        em.updateMemberID.value = systemMemberID;
        em.action = '{$smarty.const.ADMIN_URL}basis/member';
        loading();
        em.submit();
    }
</script>