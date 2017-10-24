<section>
    <div class="pagetitle clearfix">
        <h1>{$stPageTitle}</h1>
    </div>

    <div id="main_block">
        <div class="mainbox">
            <section>          
                <form id="mailhistory_search_form" name="mailhistory_search_form" method="POST" action="{$smarty.const.ADMIN_URL}basis/mail_history">
                    <input type="hidden" name="mode" value="search">
                    <input type="hidden" name="page" value="">
                    <input type="hidden" name="limit" value="">
                    <input type="hidden" name="mailhistoryid" value="">
                    <div class="contebox">
                        <table class="form-table table-v">
                            <tr>
                                <th class="w15">
                                    メール種類
                                </th>
                                {assign var="key" value="d_mail_history_TemplateID"}
                                <td class="tdstyle1" colspan="3">
                                    <select id="{$key}" name="{$key}">
                                        <option value="">選択してください</option>
                                        {html_options options=$arrTemplate selected=$arrForm[$key]}
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th class="w15">
                                    顧客ID
                                </th>
                                {assign var="key" value="d_mail_history_CustomerID"}
                                <td class="w35 v-tdborder">
                                    <input type="text" name="{$key}" value="{$arrForm[$key]}" class="w50" size="50">
                                </td>
                                <th class="w15">
                                    顧客名
                                </th>
                                {assign var="key" value="d_mail_history_CustomerName"}
                                <td class="w35">
                                    <input type="text" name="{$key}" value="{$arrForm[$key]}" class="w50" maxlength="100" size="50">
                                </td>
                            </tr>
                            <tr>
                                <th class="w15">
                                    メールタイトル
                                </th>
                                {assign var="key" value="d_mail_history_Title"}
                                <td class="tdstyle1" colspan="3">
                                    <input type="text" name="{$key}" value="{$arrForm[$key]}" class="w70" maxlength="200" size="100">
                                </td>
                            </tr>
                            <tr>
                                <th class="w15">
                                    配信日
                                </th>
                                <td class="tdstyle1" colspan="3">
                                    {assign var="key" value="d_mail_history_SendDateStart"}
                                    {if $arrErrorMessage[$key] != ""}
                                        <div class="error-mess"><span class="error-text">{$arrErrorMessage[$key]}</span></div>
                                        {/if}
                                        {assign var="key" value="d_mail_history_SendDateEnd"}
                                        {if $arrErrorMessage[$key] != ""}
                                        <div class="error-mess"><span class="error-text">{$arrErrorMessage[$key]}</span></div>
                                        {/if}
                                        {assign var="key" value="d_mail_history_SendDate"}
                                        {if $arrErrorMessage[$key] != ""}
                                        <div class="error-mess"><span class="error-text">{$arrErrorMessage[$key]}</span></div>
                                        {/if}

                                    {if $arrErrorMessage["d_mail_history_SendDateStart"] != "" || $arrErrorMessage["d_mail_history_SendDate"] != ""}
                                        {assign var="d_mail_history_SendDateError" value="1"}
                                    {else}
                                        {assign var="d_mail_history_SendDateError" value="0"}
                                    {/if}

                                    {if $d_mail_history_SendDateError == "1"}
                                        {html_select_date time=$postFromDate prefix="post_from_" start_year=$smarty.const.CALENDAR_START_YEAR end_year=$smarty.const.CALENDAR_END_YEAR field_order="Y" field_separator="" year_empty="--" id="post_from_y" all_extra='class="required not-input"'}年 
                                        {html_select_date time=$postFromDate prefix="post_from_" month_format=$smarty.const.SMARTY_MONTH_FORMAT month_value_format=$smarty.const.SMARTY_MONTH_FORMAT field_order="M" field_separator="" month_empty="--" id="post_from_m" all_extra='class="required not-input"'}月 
                                        {html_select_date time=$postFromDate prefix="post_from_" day_format="%2d" field_order="D" field_separator="" day_empty="--" id="post_from_d" all_extra='class="required not-input"'}日
                                    {else}
                                        {html_select_date time=$postFromDate prefix="post_from_" start_year=$smarty.const.CALENDAR_START_YEAR end_year=$smarty.const.CALENDAR_END_YEAR field_order="Y" field_separator="" year_empty="--" id="post_from_y"}年 
                                        {html_select_date time=$postFromDate prefix="post_from_" month_format=$smarty.const.SMARTY_MONTH_FORMAT month_value_format=$smarty.const.SMARTY_MONTH_FORMAT field_order="M" field_separator="" month_empty="--" id="post_from_m"}月 
                                        {html_select_date time=$postFromDate prefix="post_from_" day_format="%2d" field_order="D" field_separator="" day_empty="--" id="post_from_d"}日
                                    {/if}
                                    <input type="text" id="post_from_datepicker" name="post_from_datepicker" style="display: none;"> ～

                                    {if $arrErrorMessage["d_mail_history_SendDateEnd"] != "" || $arrErrorMessage["d_mail_history_SendDate"] != ""}
                                        {assign var="d_mail_history_SendDateError" value="1"}
                                    {else}
                                        {assign var="d_mail_history_SendDateError" value="0"}
                                    {/if}

                                    {if $d_mail_history_SendDateError == "1"}
                                        {html_select_date time=$postToDate prefix="post_to_" start_year=$smarty.const.CALENDAR_START_YEAR end_year=$smarty.const.CALENDAR_END_YEAR field_order="Y" field_separator="" year_empty="--" id="post_to_y" all_extra='class="required not-input"'}年 
                                        {html_select_date time=$postToDate prefix="post_to_" month_format=$smarty.const.SMARTY_MONTH_FORMAT month_value_format=$smarty.const.SMARTY_MONTH_FORMAT field_order="M" field_separator="" month_empty="--" id="post_to_m" all_extra='class="required not-input"'}月 
                                        {html_select_date time=$postToDate prefix="post_to_" day_format="%2d" field_order="D" field_separator="" day_empty="--" id="post_to_d" all_extra='class="required not-input"'}日
                                    {else}
                                        {html_select_date time=$postToDate prefix="post_to_" start_year=$smarty.const.CALENDAR_START_YEAR end_year=$smarty.const.CALENDAR_END_YEAR field_order="Y" field_separator="" year_empty="--" id="post_to_y"}年 
                                        {html_select_date time=$postToDate prefix="post_to_" month_format=$smarty.const.SMARTY_MONTH_FORMAT month_value_format=$smarty.const.SMARTY_MONTH_FORMAT field_order="M" field_separator="" month_empty="--" id="post_to_m"}月 
                                        {html_select_date time=$postToDate prefix="post_to_" day_format="%2d" field_order="D" field_separator="" day_empty="--" id="post_to_d"}日
                                    {/if}
                                    <input type="text" id="post_to_datepicker" name="post_to_datepicker" style="display: none;">
                                </td>
                            </tr>
                        </table>
                        <div class="search-btn-box">
                            <span class="mr10">
                                検索結果表示
                                <select id="pageLimit" name="pageLimit">
                                    {html_options options=$arrPageLimit selected=$iPageLimit}
                                </select>件
                            </span>
                            <input type="button" value="検索開始" id="searchBtn" class="btn-gray mr10">
                            <input type="button" value="クリアする" id="clearBtn" class="btn-gray">
                        </div>
                    </div>
                    {if $arrForm["mode"] == "search"}
                    <div class="contebox">
                        <div class="titlebox">
                            <div class="formtitle"><h2>メール履歴検索結果</h2></div>
                        </div>
                        <div class="clearfix">
                            <div class="pagenate-l">
                                <p class="result-num">検索結果一覧<span class="cl-yellow">{$iCount}件</span>が該当しました。</p>
                            </div>
                        </div>
                        {if count($arrResult) > 0}
                            <div class="pagenate-box clearfix">
                                {include file='./paginate.tpl'}
                            </div>  
                            <table class="form-table table-h">
                                <tr>
                                    <th class="w10" rowspan="2">
                                        ID
                                    </th>
                                    <th class="w25 " rowspan="2">
                                        メール種類
                                    </th>
                                    <th class="w15">
                                        顧客ID
                                    </th>
                                    <th class="w35" rowspan="2">
                                        メールタイトル
                                    </th>
                                    <th class="w10" rowspan="2">
                                        配信日
                                    </th>
                                    <th class="w5" rowspan="2">
                                        確認
                                    </th>
                                </tr>
                                <tr>
                                    <th class="w15">
                                        顧客名
                                    </th>
                                </tr>
                                {foreach from=$arrResult item=arrData name=loop}
                                    <tr>
                                        <td class="w10" rowspan="2">
                                            {$arrData["d_mail_history_MailHistoryID"]}
                                        </td>
                                        <td class="w25 al" rowspan="2">
                                        {if $arrTemplate[$arrData["d_mail_history_TemplateID"]] != ""}{$arrTemplate[$arrData["d_mail_history_TemplateID"]]}{else}指定なし{/if}
                                    </td>
                                    <td class="w15">
                                        {$arrData["d_mail_history_CustomerID"]}
                                    </td>
                                    <td class="w35 al" rowspan="2">
                                        {$arrData["d_mail_history_Title"]}  
                                    </td>
                                    <td class="w10" rowspan="2">
                                        {$arrData["d_mail_history_SendDate"]|date_format:"%Y/%m/%d<br/>%H:%M:%S"}
                                    </td>
                                    <td class="w5" rowspan="2">                    
                                        {assign var="key" value="d_mail_history_MailHistoryID"}
                                        <input type="button" value="確認" class="btn-small" onclick="confirmContent({$arrData[$key]});">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="w15">
                                        {$arrData["d_mail_history_CustomerName"]}
                                    </td>
                                </tr>
                            {/foreach}
                            </table>
                            <div class="pagenate-box clearfix">
                                {include file='./paginate.tpl'}
                            </div>
                        </div>            
                        {/if}
                    {/if}
                </form>
            </section>
        </div><!-- .mainbox end -->
    </div><!-- #main_block end -->
</section>

<script>
    $(function () {
        $("#post_from_datepicker, #post_to_datepicker").datepicker({
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
        $('#post_from_datepicker').bind("change", function () {
            setDatePulldown($(this).val(), "#post_from");
        });
        $('#post_from_y').bind("change", function () {
            setDateHidden('#post_from');
        });
        $('#post_from_m').bind("change", function () {
            setDateHidden('#post_from');
        });
        $('#post_from_d').bind("change", function () {
            setDateHidden('#post_from');
        });
        // ページ読み込み時に1回changeイベントを呼び出す
        $('#post_from_y, #post_from_m, #post_from_d').trigger("change");

        // 投稿日to
        $('#post_to_datepicker').bind("change", function () {
            setDatePulldown($(this).val(), "#post_to");
        });
        $('#post_to_y').bind("change", function () {
            setDateHidden('#post_to');
        });
        $('#post_to_m').bind("change", function () {
            setDateHidden('#post_to');
        });
        $('#post_to_d').bind("change", function () {
            setDateHidden('#post_to');
        });
        // ページ読み込み時に1回changeイベントを呼び出す
        $('#post_to_y, #post_to_m, #post_to_d').trigger("change");

        // 検索実行ボタン
        $('#searchBtn').click(function () {
            var em = document.mailhistory_search_form;
            em.mode.value = "search";
            em.action = "{$smarty.const.ADMIN_URL}basis/mail_history?page=1&limit=" + $("#pageLimit").val();
            loading();
            em.submit();
        });

        // クリアする
        $('#clearBtn').click(function () {
            if (confirm('すべての検索条件をクリアしますか？')) {
                $(this.form).find(".contebox .form-table :text, .contebox .form-table select").val("");
            }
        });

    });

    function execPageChange(pageNumber, pageLimit) {
        var em = document.mailhistory_search_form;
        em.mode.value = "search";
        em.action = "{$smarty.const.ADMIN_URL}basis/mail_history?page=" + pageNumber + "&limit=" + pageLimit + "";
        loading();
        em.submit();
    }
// プレビュー表示
    function confirmContent(MailHistoryID) {
        var em = document.mailhistory_search_form;
        em.mode.value = '';
        em.mailhistoryid.value = MailHistoryID;
        submitToPopupWindow('mailhistory_search_form', '{$smarty.const.ADMIN_URL}basis/mail_history/confirm-popup', '950', '600');
    }
</script>
