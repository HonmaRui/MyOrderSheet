<section>
    <div id="main-block">
        <div class="pagetitle clearfix">
            <h1>{$stPageTitle}</h1>
        </div>

        <section>
            <div class="mainbox">
                <form id="customer_search_form" name="customer_search_form" method="POST" action="{$smarty.const.ADMIN_URL}customer/" enctype="multipart/form-data">
                    <input type="hidden" name="mode" value="">
                    <input type="hidden" name="page" value="">
                    <input type="hidden" name="limit" value="">
                    <div class="contebox">
                        <table class="form-table table-v">
                            <tr>
                                <td class="search-index" colspan="4"><h3><img src="{$smarty.const.ADMIN_IMG_DIR}common/icon06.png" class="head"> 検索条件</h3></td>
                            </tr>
                            <tr class="readonly">
                                <th class="w15">顧客ID</th>
                                <td class="w35">
                                    {assign var="key" value="d_customer_CustomerID"}
                                    <input type="text" class="w20" name="{$key}" value="{$arrForm[$key]}">
                                </td>
                            </tr>
                            <tr>
                                <th class="w15">顧客名</th>
                                <td class="w35">
                                    {assign var="key" value="d_customer_Name"}
                                    <input type="text" class="w80" name="{$key}" value="{$arrForm[$key]}">
                                </td>
                            </tr>
                            <tr>
                                <th class="w15">顧客名カナ</th>
                                <td class="w35">
                                    {assign var="key" value="d_customer_NameKana"}
                                    <input type="text" class="w80" name="{$key}" value="{$arrForm[$key]}">
                                </td>
                            </tr>
                            <tr>
                                <th class="w15">会社名</th>
                                <td class="w35">
                                    {assign var="key" value="d_customer_CompanyName"}
                                    <input type="text" class="w80" name="{$key}" value="{$arrForm[$key]}">
                                </td>
                            </tr>
                            <tr>
                                <th class="w15">都道府県・住所</th>
                                <td colspan="3">
                                    {assign var="key" value="d_customer_PrefCode"}
                                    <select name="{$key}">
                                        <option value="">都道府県を選択</option>
                                        {html_options options=$arrPref selected=$arrForm[$key]}
                                    </select>
                                    {assign var="key" value="customerAddress"}
                                    <input type="text" class="w60" name="{$key}" value="{$arrForm[$key]}">
                                </td>
                            </tr>
                            <tr>
                                <th class="w15">電話番号</th>
                                <td class="w35">
                                    {assign var="key" value="d_customer_TelNo"}
                                    <input type="text" class="w50" name="{$key}" value="{$arrForm[$key]}">
                                </td>
                            </tr>
                            <tr class="readonly">
                                <th class="w15">メールアドレス</th>
                                <td class="w35">
                                    {assign var="key" value="d_customer_EmailAddress"}
                                    <input type="text" class="w70" name="{$key}" value="{$arrForm[$key]}">
                                </td>
                            </tr>                                                    
                            <tr class="readonly">
                                <th class="w15">職業</th>
                                <td class="w35">
                                    {assign var="key" value="d_customer_JobID"}
                                    {html_checkboxes name=$key options=$arrJob separator=' ' selected=$arrForm[$key]}
                                </td>
                            </tr>                                                    
                            <tr class="readonly">
                                <th class="w15">顧客ランク</th>
                                <td class="w35">
                                    {assign var="key" value="d_customer_CustomerRankID"}
                                    {html_checkboxes name=$key options=$arrRank separator=' ' selected=$arrForm[$key]}
                                </td>
                            </tr>                                                    
                            <tr class="readonly">
                                <th class="w15">退会者を含む</th>
                                <td class="w35">
                                    {assign var="key" value="d_customer_SignedOut"}
                                    {html_checkboxes name=$key options=$arrDel separator=' ' selected=$arrForm[$key]}
                                </td>
                            </tr>                                                    
                            <tr class="readonly">
                                <th class="w15">登録日</th>
                                <td colspan="3">
                                    {assign var="key" value="d_customer_CreatedTimeStart"}
                                    {if $arrErrorMessage[$key] != ""}
                                        <div class="error-mess"><span class="error-text">{$arrErrorMessage[$key]}</span></div>
                                        {/if}
                                        {assign var="key" value="d_customer_CreatedTimeEnd"}
                                        {if $arrErrorMessage[$key] != ""}
                                        <div class="error-mess"><span class="error-text">{$arrErrorMessage[$key]}</span></div>
                                        {/if}
                                        {assign var="key" value="d_customer_CreatedTime"}
                                        {if $arrErrorMessage[$key] != ""}
                                        <div class="error-mess"><span class="error-text">{$arrErrorMessage[$key]}</span></div>
                                        {/if}

                                    {if $arrErrorMessage["d_customer_CreatedTimeStart"] != "" || $arrErrorMessage["d_customer_CreatedTime"] != ""}
                                        {assign var="d_customer_CreatedTimeError" value="1"}
                                    {else}
                                        {assign var="d_customer_CreatedTimeError" value="0"}
                                    {/if}

                                    {if $d_customer_CreatedTimeError == "1"}
                                        {html_select_date time=$createFromDate prefix="create_from_" start_year=$smarty.const.CALENDAR_START_YEAR end_year=$smarty.const.CALENDAR_END_YEAR field_order="Y" field_separator="" year_empty="--" id="create_from_y" all_extra='class="required not-input"'}年 
                                        {html_select_date time=$createFromDate prefix="create_from_" month_format=$smarty.const.SMARTY_MONTH_FORMAT month_value_format=$smarty.const.SMARTY_MONTH_FORMAT field_order="M" field_separator="" month_empty="--" id="create_from_m" all_extra='class="required not-input"'}月 
                                        {html_select_date time=$createFromDate prefix="create_from_" day_format="%2d" field_order="D" field_separator="" day_empty="--" id="create_from_d" all_extra='class="required not-input"'}日
                                    {else}
                                        {html_select_date time=$createFromDate prefix="create_from_" start_year=$smarty.const.CALENDAR_START_YEAR end_year=$smarty.const.CALENDAR_END_YEAR field_order="Y" field_separator="" year_empty="--" id="create_from_y"}年 
                                        {html_select_date time=$createFromDate prefix="create_from_" month_format=$smarty.const.SMARTY_MONTH_FORMAT month_value_format=$smarty.const.SMARTY_MONTH_FORMAT field_order="M" field_separator="" month_empty="--" id="create_from_m"}月 
                                        {html_select_date time=$createFromDate prefix="create_from_" day_format="%2d" field_order="D" field_separator="" day_empty="--" id="create_from_d"}日
                                    {/if}
                                    <input type="text" id="create_from_datepicker" name="create_from_datepicker" style="display: none;"> ～

                                    {if $arrErrorMessage["d_customer_CreatedTimeEnd"] != "" || $arrErrorMessage["d_customer_CreatedTime"] != ""}
                                        {assign var="d_customer_CreatedTimeError" value="1"}
                                    {else}
                                        {assign var="d_customer_CreatedTimeError" value="0"}
                                    {/if}

                                    {if $d_customer_CreatedTimeError == "1"}
                                        {html_select_date time=$createToDate prefix="create_to_" start_year=$smarty.const.CALENDAR_START_YEAR end_year=$smarty.const.CALENDAR_END_YEAR field_order="Y" field_separator="" year_empty="--" id="create_to_y" all_extra='class="required not-input"'}年 
                                        {html_select_date time=$createToDate prefix="create_to_" month_format=$smarty.const.SMARTY_MONTH_FORMAT month_value_format=$smarty.const.SMARTY_MONTH_FORMAT field_order="M" field_separator="" month_empty="--" id="create_to_m" all_extra='class="required not-input"'}月 
                                        {html_select_date time=$createToDate prefix="create_to_" day_format="%2d" field_order="D" field_separator="" day_empty="--" id="create_to_d" all_extra='class="required not-input"'}日
                                    {else}
                                        {html_select_date time=$createToDate prefix="create_to_" start_year=$smarty.const.CALENDAR_START_YEAR end_year=$smarty.const.CALENDAR_END_YEAR field_order="Y" field_separator="" year_empty="--" id="create_to_y"}年 
                                        {html_select_date time=$createToDate prefix="create_to_" month_format=$smarty.const.SMARTY_MONTH_FORMAT month_value_format=$smarty.const.SMARTY_MONTH_FORMAT field_order="M" field_separator="" month_empty="--" id="create_to_m"}月 
                                        {html_select_date time=$createToDate prefix="create_to_" day_format="%2d" field_order="D" field_separator="" day_empty="--" id="create_to_d"}日
                                    {/if}
                                    <input type="text" id="create_to_datepicker" name="create_to_datepicker" style="display: none;">
                                </td>
                            </tr>
                            <tr class="readonly">
                                <th class="w15">更新日</th>
                                <td colspan="3">
                                    {assign var="key" value="d_customer_UpdatedTimeStart"}
                                    {if $arrErrorMessage[$key] != ""}
                                        <div class="error-mess"><span class="error-text">{$arrErrorMessage[$key]}</span></div>
                                        {/if}
                                        {assign var="key" value="d_customer_UpdatedTimeEnd"}
                                        {if $arrErrorMessage[$key] != ""}
                                        <div class="error-mess"><span class="error-text">{$arrErrorMessage[$key]}</span></div>
                                        {/if}
                                        {assign var="key" value="d_customer_UpdatedTime"}
                                        {if $arrErrorMessage[$key] != ""}
                                        <div class="error-mess"><span class="error-text">{$arrErrorMessage[$key]}</span></div>
                                        {/if}

                                    {if $arrErrorMessage["d_customer_UpdatedTimeStart"] != "" || $arrErrorMessage["d_customer_UpdatedTime"] != ""}
                                        {assign var="d_customer_UpdatedTimeError" value="1"}
                                    {else}
                                        {assign var="d_customer_UpdatedTimeError" value="0"}
                                    {/if}

                                    {if $d_customer_UpdatedTimeError == "1"}
                                        {html_select_date time=$updateFromDate prefix="update_from_" start_year=$smarty.const.CALENDAR_START_YEAR end_year=$smarty.const.CALENDAR_END_YEAR field_order="Y" field_separator="" year_empty="--" id="update_from_y" all_extra='class="required not-input"'}年 
                                        {html_select_date time=$updateFromDate prefix="update_from_" month_format=$smarty.const.SMARTY_MONTH_FORMAT month_value_format=$smarty.const.SMARTY_MONTH_FORMAT field_order="M" field_separator="" month_empty="--" id="update_from_m" all_extra='class="required not-input"'}月 
                                        {html_select_date time=$updateFromDate prefix="update_from_" day_format="%2d" field_order="D" field_separator="" day_empty="--" id="update_from_d" all_extra='class="required not-input"'}日
                                    {else}
                                        {html_select_date time=$updateFromDate prefix="update_from_" start_year=$smarty.const.CALENDAR_START_YEAR end_year=$smarty.const.CALENDAR_END_YEAR field_order="Y" field_separator="" year_empty="--" id="update_from_y"}年 
                                        {html_select_date time=$updateFromDate prefix="update_from_" month_format=$smarty.const.SMARTY_MONTH_FORMAT month_value_format=$smarty.const.SMARTY_MONTH_FORMAT field_order="M" field_separator="" month_empty="--" id="update_from_m"}月 
                                        {html_select_date time=$updateFromDate prefix="update_from_" day_format="%2d" field_order="D" field_separator="" day_empty="--" id="update_from_d"}日
                                    {/if}
                                    <input type="text" id="update_from_datepicker" name="update_from_datepicker" style="display: none;"> ～

                                    {if $arrErrorMessage["d_customer_UpdatedTimeEnd"] != "" || $arrErrorMessage["d_customer_UpdatedTime"] != ""}
                                        {assign var="d_customer_UpdatedTimeError" value="1"}
                                    {else}
                                        {assign var="d_customer_UpdatedTimeError" value="0"}
                                    {/if}

                                    {if $d_customer_UpdatedTimeError == "1"}
                                        {html_select_date time=$updateToDate prefix="update_to_" start_year=$smarty.const.CALENDAR_START_YEAR end_year=$smarty.const.CALENDAR_END_YEAR field_order="Y" field_separator="" year_empty="--" id="update_to_y" all_extra='class="required not-input"'}年 
                                        {html_select_date time=$updateToDate prefix="update_to_" month_format=$smarty.const.SMARTY_MONTH_FORMAT month_value_format=$smarty.const.SMARTY_MONTH_FORMAT field_order="M" field_separator="" month_empty="--" id="update_to_m" all_extra='class="required not-input"'}月 
                                        {html_select_date time=$updateToDate prefix="update_to_" day_format="%2d" field_order="D" field_separator="" day_empty="--" id="update_to_d" all_extra='class="required not-input"'}日
                                    {else}
                                        {html_select_date time=$updateToDate prefix="update_to_" start_year=$smarty.const.CALENDAR_START_YEAR end_year=$smarty.const.CALENDAR_END_YEAR field_order="Y" field_separator="" year_empty="--" id="update_to_y"}年 
                                        {html_select_date time=$updateToDate prefix="update_to_" month_format=$smarty.const.SMARTY_MONTH_FORMAT month_value_format=$smarty.const.SMARTY_MONTH_FORMAT field_order="M" field_separator="" month_empty="--" id="update_to_m"}月 
                                        {html_select_date time=$updateToDate prefix="update_to_" day_format="%2d" field_order="D" field_separator="" day_empty="--" id="update_to_d"}日
                                    {/if}
                                    <input type="text" id="update_to_datepicker" name="update_to_datepicker" style="display: none;">
                                </td>
                            </tr>                                      
                        </table>
                        <div class="search-btn-box">
                            <span class="mr10">
                                検索結果表示
                                <select id="pageLimit" name="displayNumber">
                                  {html_options options=$arrPageLimit selected=$iPageLimit}
                                </select>件
                            </span>
                            <input type="button" class="btn-gray mr10" id="searchBtn" value="検索開始">
                            <input type="button" class="btn-gray" id="clearBtn" value="クリアする">
                        </div>
                    </div>
                    {if $arrForm["mode"] == "search"}
                    <div class="contebox clearfix">
                        <div class="formtitle clearfix"><h2>顧客検索結果</h2></div>
                        <div class="search-result">
                            <div class="clearfix">
                                <p class="result-num left" id="searchResultBlock">検索結果一覧<span class="cl-yellow">{$iCount}件</span>が該当しました。</p>
                                {if count($arrResult) > 0}
                                <div class="result-btnbox">
                                <span class="bold">一覧表・CSV出力設定</span>
                                {html_radios name="downloadConfig" options=$arrDownLoadConfig separator=' ' selected=1}
                                {html_options name="downloadSelect" options=$arrDownLoad id="downloadSelect"}
                                <button type="button" id="csvdownloadBtn" class="btn-blk" onclick="download();">DOWNLOAD</button>
                                </div>
                            </div>
                            {include file='./paginate.tpl'}
                        </div>
                        <div class="">
                        <table class="form-table table-h">
                            <thead>
                                <tr>
                                    <th class="w6" rowspan="2">顧客ID</th>
                                    <th class="w20">顧客名(カナ)</th>
                                    <th class="w5" rowspan="2">顧客ランク</th>
                                    <th class="w12">電話番号</th>
                                    <th class="w3" rowspan="2">受注</th>
                                    <th class="w3" rowspan="2">編集</th>
                                    <th class="w5" rowspan="2"><label>全選択<br>全解除<br><input type="checkbox" name="allCheck" class="allCheck"></label></th>
                                </tr>
                                <tr>
                                    <th class="w20">会社名 / 部署名</th>
                                    <th class="w15">メールアドレス</th>
                                </tr>
                            </thead>
                            <tbody>
                                {foreach from=$arrResult item=arrCustomer name=loop}
                                <tr{if $arrCustomer["d_customer_SignedOut"] == 1} style="background-color: #bfbfbf !important;"{/if}>
                                    <td class="w6" rowspan="2">{$arrCustomer["d_customer_CustomerID"]}</td>
                                    <td class="w20">{$arrCustomer["d_customer_Name"]} ({$arrCustomer["d_customer_NameKana"]})</td>
                                    <td class="w5" rowspan="2">{$arrRankDisp[$arrCustomer["d_customer_CustomerRankID"]]}</td>
                                    <td class="w12 al">{$arrCustomer["d_customer_TelNo"]}</td>
                                    <td class="w3" rowspan="2"><input type="button" class="btn-small" value="受注" onClick="create_order('{$arrCustomer["d_customer_CustomerID"]}');"></td>
                                    <td class="w3" rowspan="2"><input type="button" class="btn-small" value="編集" onClick="window.location.href = '{$smarty.const.ADMIN_URL}customer/edit/{$arrCustomer["d_customer_CustomerID"]}';"></td>
                                    <td class="w5" rowspan="2"><input type="checkbox" class="check" name="check_{$arrCustomer["d_customer_CustomerID"]}" id="check_{$smarty.foreach.loop.index}"></td>
                                </tr>
                                <tr{if $arrCustomer["d_customer_SignedOut"] == 1} style="background-color: #bfbfbf !important;"{/if}>
                                    <td class="w20 al">{$arrCustomer["d_customer_CompanyName"]}{if $arrCustomer["d_customer_DepartmentName"] != ""} / {$arrCustomer["d_customer_DepartmentName"]}{/if}</td>
                                    <td class="w15 al">{$arrCustomer["d_customer_EmailAddress"]}</td>
                                </tr>
                                {/foreach}
                            </tbody>
                        </table>
                        </div><!-- .order end -->
                        {include file='./paginate.tpl'}
                    </div><!-- .contebox.result-box end -->
                    {/if}
                    {/if}
                </form>

            </div><!-- .mainbox end -->
        </section>

    </div><!-- #main-block end -->
</section>
<script>
    $(function () {
        // 全て選択・解除ボタン
        $(".allCheck").on("click", function () {
            $(".check").prop("checked", $(this).prop("checked"));
        });
        
        // 検索ボタン
        $('#searchBtn').click(function() {
            var em = document.customer_search_form;
            em.mode.value = 'search';
            var location = '{$smarty.const.ADMIN_URL}customer';
            em.action = location + '?page=1&limit=' + $('#pageLimit').val() + '#searchResultBlock';
            loading();
            em.submit();
        });
        
        // クリアする
        $('#clearBtn').click(function() {
            if(confirm('すべての検索条件をクリアしますか？')) {
                $(this.form).find(".contebox .form-table textarea, .contebox .form-table :text, .contebox .form-table select").val("").end().find(":checked").prop("checked", false);
            }
        });

        //カレンダー
        $("#create_from_datepicker,#create_to_datepicker,#update_from_datepicker,#update_to_datepicker").datepicker({
            minDate:new Date({$smarty.const.CALENDAR_START_YEAR}, 1 - 1, 1),
            maxDate:new Date({$smarty.const.CALENDAR_END_YEAR}, 12 - 1, 31),
            showOn: 'button',
            buttonImageOnly: true,
            buttonImage: '{$smarty.const.ADMIN_IMG_DIR}common/icon_calender.png',
            numberOfMonths: [1, 2],
            showButtonPanel: true,
            dateFormat: 'yy/mm/dd',
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
                        return [false, 'holiday'];
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

        // 登録日from
        $('#create_from_datepicker').bind("change", function () {
            setDatePulldown($(this).val(), "#create_from");
        });
        $('#create_from_y').bind("change", function () {
            setDateHidden('#create_from');
        });
        $('#create_from_m').bind("change", function () {
            setDateHidden('#create_from');
        });
        $('#create_from_d').bind("change", function () {
            setDateHidden('#create_from');
        });
        // ページ読み込み時に1回changeイベントを呼び出す
        $('#create_from_y, #create_from_m, #create_from_d').trigger("change");

        // 登録日to
        $('#create_to_datepicker').bind("change", function () {
            setDatePulldown($(this).val(), "#create_to");
        });
        $('#create_to_y').bind("change", function () {
            setDateHidden('#create_to');
        });
        $('#create_to_m').bind("change", function () {
            setDateHidden('#create_to');
        });
        $('#create_to_d').bind("change", function () {
            setDateHidden('#create_to');
        });
        // ページ読み込み時に1回changeイベントを呼び出す
        $('#create_to_y, #create_to_m, #create_to_d').trigger("change");

        // 更新日from
        $('#update_from_datepicker').bind("change", function () {
            setDatePulldown($(this).val(), "#update_from");
        });
        $('#update_from_y').bind("change", function () {
            setDateHidden('#update_from');
        });
        $('#update_from_m').bind("change", function () {
            setDateHidden('#update_from');
        });
        $('#update_from_d').bind("change", function () {
            setDateHidden('#update_from');
        });
        // ページ読み込み時に1回changeイベントを呼び出す
        $('#update_from_y, #update_from_m, #update_from_d').trigger("change");

        // 更新日to
        $('#update_to_datepicker').bind("change", function () {
            setDatePulldown($(this).val(), "#update_to");
        });
        $('#update_to_y').bind("change", function () {
            setDateHidden('#update_to');
        });
        $('#update_to_m').bind("change", function () {
            setDateHidden('#update_to');
        });
        $('#update_to_d').bind("change", function () {
            setDateHidden('#update_to');
        });
        // ページ読み込み時に1回changeイベントを呼び出す
        $('#update_to_y, #update_to_m, #update_to_d').trigger("change");
    });
    
    function execPageChange(pageNumber, pageLimit) {
    var em = document.customer_search_form;
    em.mode.value = 'search';
    var location = '{$smarty.const.ADMIN_URL}customer';
    em.action = location + '?page=' + pageNumber + '&limit=' + pageLimit + '#searchResultBlock';
    em.submit();
    }
    
    {if count($arrResult) > 0}
    function download() {
    var selected = document.getElementById("downloadSelect").value;
        if (selected != "") {
            if (selected == {constant("Application_Model_Customer::DOWNLOAD_CUSTOMER_CSV_CHECK")}) {
                // チェックされた顧客データがあるか調べる
                var bChecked = false;
                for (var i = 0;i <= {$smarty.foreach.loop.index};i++) {
                    var checkResult = document.getElementById("check_" + i).checked;
                    if (checkResult === true) {
                        bChecked = true;
                    }
                }
                if (bChecked === false) {
                    // チェック0だったらアラートを出して終了
                    alert("チェックされた顧客データがありません。");
                    return false;
                }
            }
            var em = document.customer_search_form;
            em.mode.value = 'download';
            em.action = '{$smarty.const.ADMIN_URL}customer';
            loading();
            em.submit();
            waitingProcess();
        }
    }
    {/if}
    
    // 受注作成
    var popWinObj;
    function create_order(iCustomer) {
        var keys = ['d_order_CustomerID', 'mode'];
        var values = [iCustomer, 'set_customer'];

        $('#new_form_id').remove();
        if ((popWinObj) && (!popWinObj.closed)) {
            popWinObj.close();
        }
        popWinObj = window.open("about:blank", 'new_tab');
        var html = '<form method="post" action="{$smarty.const.ADMIN_URL}order/add" id="new_form_id" target="new_tab" style="display: none;">';
        for (var cnt = 0; cnt< keys.length; cnt++) {
             html += '<input type="hidden" name="'+ keys[cnt]+'" value="'+ values[cnt]+'" >';
        }
        html += '</form>';
        $("body").append(html);
        $('#new_form_id').submit();
        $('#new_form_id').remove();
    }
    
</script>
