<section>
    <div id="main-block">
        <div class="pagetitle clearfix">
            <h1>{$stPageTitle}</h1>
        </div>

        <section>
            <div class="mainbox">
                <form id="news_form" name="news_form" method="POST" action="{$smarty.const.ADMIN_URL}basis/news" enctype="multipart/form-data">
                    <input type="hidden" name="csrf" value="{$stCsrf}">
                    <input type="hidden" name="editNewsID" value="">
                    <input type="hidden" name="delNewsID" value="">
                    <input type="hidden" name="srcNewsID" value="">
                    <input type="hidden" name="dstNewsID" value="">
                    <input type="hidden" name="moveNewsID" value="">
                    <input type="hidden" name="posnum" value="">
                    <input type="hidden" name="mode" value="">
                    <div class="outbox-col2">
                        <div class="contebox">
                            <div class="formtitle">
                                <div class="formtitle-l">
                                    <h2>{$stPageTitle}</h2>
                                </div>
                            </div>
                            <table class="form-table table-v">
                                <tr>
                                    <th class="w20">日付<span class="req"><img src="{$smarty.const.ADMIN_IMG_DIR}common/icon05.png" width="34" height="17" alt="必須項目"></span></th>
                                    <td class="w73">
                                        {assign var="key" value="d_contents_newinfo_Date"}
                                        {if $arrErrorMessage[$key] != ""}
                                            <div class="error-mess"><span class="error-text">{$arrErrorMessage[$key]}</span></div>
                                        {/if}

                                        {if $arrErrorMessage["d_contents_newinfo_Date"] != ""}
                                            {assign var="d_contents_newinfo_DateError" value="1"}
                                        {else}
                                            {assign var="d_contents_newinfo_DateError" value="0"}
                                        {/if}

                                        {if $d_contents_newinfo_DateError == "1"}
                                            {html_select_date time=$newsFromDate prefix="news_from_" start_year=$smarty.const.CALENDAR_START_YEAR end_year=$smarty.const.CALENDAR_END_YEAR field_order="Y" field_separator="" year_empty="--" id="news_from_y" all_extra='class="required not-input"'}年 
                                            {html_select_date time=$newsFromDate prefix="news_from_" month_format=$smarty.const.SMARTY_MONTH_FORMAT month_value_format=$smarty.const.SMARTY_MONTH_FORMAT field_order="M" field_separator="" month_empty="--" id="news_from_m" all_extra='class="required not-input"'}月 
                                            {html_select_date time=$newsFromDate prefix="news_from_" day_format="%2d" field_order="D" field_separator="" day_empty="--" id="news_from_d" all_extra='class="required not-input"'}日
                                        {else}
                                            {html_select_date time=$newsFromDate prefix="news_from_" start_year=$smarty.const.CALENDAR_START_YEAR end_year=$smarty.const.CALENDAR_END_YEAR field_order="Y" field_separator="" year_empty="--" id="news_from_y"}年 
                                            {html_select_date time=$newsFromDate prefix="news_from_" month_format=$smarty.const.SMARTY_MONTH_FORMAT month_value_format=$smarty.const.SMARTY_MONTH_FORMAT field_order="M" field_separator="" month_empty="--" id="news_from_m"}月 
                                            {html_select_date time=$newsFromDate prefix="news_from_" day_format="%2d" field_order="D" field_separator="" day_empty="--" id="news_from_d"}日
                                        {/if}
                                        <input type="text" id="news_from_datepicker" name="news_from_datepicker" style="display: none;">
                                    </td>                 
                                </tr>
                                <tr>
                                    <th class="w20">タイトル<span class="req"><img src="{$smarty.const.ADMIN_IMG_DIR}common/icon05.png" width="34" height="17" alt="必須項目"></span></th>
                                    <td class="w73">
                                        {assign var="key" value="d_contents_newinfo_Title"}
                                        {if $arrErrorMessage[$key] != ""}
                                            <div class="error-mess"><span class="error-text">{$arrErrorMessage[$key]}</span></div>
                                            {/if}
                                        <input type="text" class="w87{if $arrErrorMessage[$key] != ""} required not-input{/if}" name="{$key}" value="{$arrForm[$key]}"><span class="text-limit">(200文字)</span>
                                    </td>                        
                                </tr>
                                <tr>
                                    <th class="w20">本文<span class="req"><img src="{$smarty.const.ADMIN_IMG_DIR}common/icon05.png" width="34" height="17" alt="必須項目"></span></th>
                                    <td class="w73">
                                        {assign var="key" value="d_contents_newinfo_Text"}
                                        {if $arrErrorMessage[$key] != ""}
                                            <div class="error-mess"><span class="error-text">{$arrErrorMessage[$key]}</span></div>
                                            {/if}
                                        <textarea rows="5" class="w87{if $arrErrorMessage[$key] != ""} required not-input{/if}" name="{$key}">{$arrForm[$key]}</textarea><span class="text-limit">(4000文字)</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="ac">
                        <input type="button" class="btn-gray btn-save ml10" value="上記の内容で保存する" id="create" onClick="execSubmit({$arrForm['editNewsID']});">
                    </div>
                    <div class="outbox-col2 mt10">
                        <div class="contebox">
                            <div class="formtitle clearfix"><h2>登録済み新着情報一覧</h2></div>
                            <div class="">
                                {if count($arrResult) > 0}
                                    <table class="form-table table-h" id="tbl1">
                                        <tr>
                                            <th class="w10">日付</th>
                                            <th class="w40">タイトル</th>
                                            <th class="w10">編集</th>
                                            <th class="w10">削除</th>
                                            <th class="w30">表示順</th>
                                        </tr>
                                        {foreach from=$arrResult item=arrData name=news}
                                            <tr class="rows">
                                                <td class="list-td ac">{$arrData["d_contents_newinfo_Date"]|date_format:"%Y/%m/%d"}</td>
                                                <td class="list-td al">{$arrData["d_contents_newinfo_Title"]}</td>
                                                <td>
                                                    {if $arrForm["editNewsID"] == $arrData["d_contents_newinfo_ContentsNewinfoID"]}
                                                        編集中
                                                    {else}
                                                        <input type="button" class="btn-small" value="編集" onClick="changeNewsName('{$arrData["d_contents_newinfo_ContentsNewinfoID"]}');">
                                                    {/if}
                                                </td>
                                                <td>
                                                    <input type="button" class="btn-small" value="削除" onClick="deleteNews({$arrData["d_contents_newinfo_ContentsNewinfoID"]});">
                                                </td>
                                                <td>
                                                    {if count($arrResult) == 1}--
                                                    {else}
                                                        {assign var="key" value="posnum"|cat:$smarty.foreach.news.index}
                                                        {if $arrErrorMessage[$arrData["d_contents_newinfo_ContentsNewinfoID"]][$key] != ""}
                                                            <div class="error-mess"><span class="error-text">{$arrErrorMessage[$arrData["d_contents_newinfo_ContentsNewinfoID"]][$key]}</span></div>
                                                            {/if}
                                                            {if $smarty.foreach.news.first}
                                                            <input class="upBtn" type="button" value="下へ" onClick="changeOrder({$arrData["d_contents_newinfo_ContentsNewinfoID"]}, {$arrData["nextNewsID"]});">
                                                        {elseif $smarty.foreach.news.last}
                                                            <input class="downBtn" type="button" value="上へ" onClick="changeOrder({$arrData["d_contents_newinfo_ContentsNewinfoID"]}, {$arrData["beforeNewsID"]});">
                                                        {else}
                                                            <input class="downBtn" type="button" value="上へ" onClick="changeOrder({$arrData["d_contents_newinfo_ContentsNewinfoID"]}, {$arrData["beforeNewsID"]});">
                                                            <input class="upBtn" type="button" value="下へ" onClick="changeOrder({$arrData["d_contents_newinfo_ContentsNewinfoID"]}, {$arrData["nextNewsID"]});">
                                                        {/if}
                                                        <input class="posNumTb {if $arrErrorMessage[$arrData["d_contents_newinfo_ContentsNewinfoID"]][$key] != ""}required not-input{/if}" type="text" name="{$key}" id="{$key}" value="{$arrForm[$key]}">
                                                        <input class="moveBtn" type="button" value="番目へ移動" onClick="moveOrder({$arrData["d_contents_newinfo_ContentsNewinfoID"]}, {$smarty.foreach.news.index});">
                                                    {/if}
                                                </td>
                                            </tr>
                                        {/foreach}
                                    </table>
                                {else}
                                    <br>
                                    &nbsp;&nbsp;&nbsp;新着情報が未登録です。<br><br>
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
        $("#news_from_datepicker, #news_to_datepicker").datepicker({
            minDate: new Date({$smarty.const.CALENDAR_START_YEAR}, 1 - 1, 1),
            maxDate: new Date({$smarty.const.CALENDAR_END_YEAR}, 12 - 1, 31),
            showOn: 'button',
            buttonImageOnly: true,
            buttonImage: '{$smarty.const.ADMIN_IMG_DIR}common/datepicker_mark.png',
            numberOfMonths: [1, 2],
            showButtonPanel: true,
            beforeShow: function (input) {
                setTimeout(function () {
                    var buttonPane = $(input)
                            .datepicker("widget")
                            .find(".ui-datepicker-buttonpane");

                    var btn = $('<button class="ui-datepicker-current ui-state-default ui-priority-secondary ui-corner-all" type="button">クリア</button>');
                    btn
                            .unbind("click")
                            .bind("click", function () {
                                $.datepicker._clearDate(input);
                            });

                    btn.appendTo(buttonPane);
                }, 1);
            },
            beforeShowDay: function (date) {
                // 祝日を配列で確保
                var holidays = ['2014-05-05', '2014-05-06'];

                // 祝日の判定
                for (var i = 0; i < holidays.length; i++) {
                    var htime = Date.parse(holidays[i]);    // 祝日を 'YYYY-MM-DD' から time へ変換
                    var holiday = new Date();
                    holiday.setTime(htime);                 // 上記 time を Date へ設定

                    // 祝日
                    if (holiday.getYear() == date.getYear() &&
                            holiday.getMonth() == date.getMonth() &&
                            holiday.getDate() == date.getDate()) {
                        return [true, 'holiday'];
                    }
                }
                // 日曜日
                if (date.getDay() == 0) {
                    return [true, 'sunday'];
                }
                // 土曜日
                if (date.getDay() == 6) {
                    return [true, 'saturday'];
                }
                // 平日
                return [true, ''];
            }
        });

        // 投稿日from
        $('#news_from_datepicker').bind("change", function () {
            setDatePulldown($(this).val(), "#news_from");
        });
        $('#news_from_y').bind("change", function () {
            setDateHidden('#news_from');
        });
        $('#news_from_m').bind("change", function () {
            setDateHidden('#news_from');
        });
        $('#news_from_d').bind("change", function () {
            setDateHidden('#news_from');
        });
        // ページ読み込み時に1回changeイベントを呼び出す
        $('#news_from_y, #news_from_m, #news_from_d').trigger("change");
    });
    
    function changeNewsName(editNewsID) {
        var em = document.news_form;
        em.mode.value = 'edit';
        em.editNewsID.value = editNewsID;
        em.action = '{$smarty.const.ADMIN_URL}basis/news';
        loading();
        em.submit();
    }
    
    function execSubmit(editNewsID) {
        var em = document.news_form;
        if (typeof editNewsID === 'undefined') {
            em.mode.value = 'add';
        } else {
            em.mode.value = 'save';
            em.editNewsID.value = editNewsID;
        }
        em.action = '{$smarty.const.ADMIN_URL}basis/news';
        loading();
        em.submit();
    }
    
    function deleteNews(delNewsID) {
        if (confirm('一度削除したデータは元に戻せません。\n削除してもよろしいですか？')) {
            var em = document.news_form;
            em.mode.value = 'del';
            em.delNewsID.value = delNewsID;
            em.action = '{$smarty.const.ADMIN_URL}basis/news';
            loading();
            em.submit();
        }
    }
    
    function changeOrder(srcNewsID, dstNewsID) {
        var em = document.news_form;
        em.mode.value = 'order';
        em.srcNewsID.value = srcNewsID;
        em.dstNewsID.value = dstNewsID;
        em.action = '{$smarty.const.ADMIN_URL}basis/news';
        loading();
        em.submit();
    }
    
    function moveOrder(moveNewsID, count) {
        var em = document.news_form;
        em.mode.value = 'move';
        em.moveNewsID.value = moveNewsID;
        em.posnum.value = count;
        em.action = '{$smarty.const.ADMIN_URL}basis/news';
        loading();
        em.submit();
    }
</script>