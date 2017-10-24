/*
 * 概要: ポップアップウィンドウを表示する
 *
 * 引数:   string fromFormID     : メインウィンドウのフォーム名
 *         string toWindowURL    : ポップアップウィンドウで表示するURL
 *         int width             : ポップアップウィンドウの表示サイズ(幅)
 *         int height            : ポップアップウィンドウの表示サイズ(高さ)
 * 戻り値: なし
 *
 */
/*
function submitToPopupWindow(fromFormID, toWindowURL, width, height) {
    window.open("about:blank", "Popup", "width = " + width + ", height = " + height + ", scrollbars=yes");
    window.name = "parent";
    document[fromFormID].action = toWindowURL;
    document[fromFormID].target = "Popup";
    document[fromFormID].method = "POST";
    document[fromFormID].submit();
    // Actionとモード値を戻す
    document[fromFormID].target = "";
    document[fromFormID].action = "";
} 
*/    

function makeHidden(name, value, formname){
    var em = document.createElement('input');
    em.type = 'hidden';
    em.name = name;
    em.value = value;
    em.id = name;
    if (formname) {
        document.forms[formname].appendChild(em);
    } else {
        document.forms[0].appendChild(em);
    }
}

// Datepickerの日付がクリックされた時にプルダウンを変更させる関数
function setDatePulldown(date, option) {
    if (date.length == 0) {
        $(option + "_y").val('');
        $(option + "_m").val('');
        $(option + "_d").val('');
        return;
    }
    
    var dates = date.split('/');
    $(option + "_y").val(Number(dates[0]));
    $(option + "_m").val(Number(dates[1]));
    $(option + "_d").val(Number(dates[2]));
}

// プルダウンが変更されたときに日付をDatepickerに反映させる関数
function setDateHidden(option) {
    if ($(option + "_y").val() == '--' || $(option + "_m").val() == '--' || $(option + "#_d").val() == '--') {
        return;
    }
    var year = parseInt($(option + "_y").val());
    var month = parseInt($(option + "_m").val());
    var day = parseInt($(option + "_d").val());
    var newdate = year + "/" + month + "/" + day;
    $(option + '_datepicker').val(newdate);
}

/*
 * 概要: クリックした項目をメインウィンドウのセレクトボックスに送る関数
 *
 * 引数:   string sendID         : メインウィンドウに送信する値
 *         string popupFormID    : ポップアップウィンドウでチェックしたの表示名用のname(prefix)
 *         string mainRecvFormID : メインウィンドウのフォームに表示名をセットする要素のID
 *         string mainRecvHdnID  : メインウィンドウのフォームにIDをセットするhidden要素のID
 * 戻り値: bool                  : true/false
 *
 */
function sendClickElementToMainWindow(sendID, popupFormID, mainRecvFormID, mainRecvHdnID) {
    
    if(!window.opener || window.opener.closed) {
        window.alert('メインウィンドウが存在しません');
        return false;
    }

    // ポップアップウィンドウの表示名を、メインウィンドウに表示する
    var text = $('input[name="' + popupFormID + sendID + '"]').val();
    window.opener.$('#' + mainRecvFormID).val(text);

    // メインウィンドウに既に追加されている表示名、IDのhidden要素を破棄する
    window.opener.$('input[name="' + mainRecvFormID + '"]:hidden').remove();
    window.opener.$('input[name="' + mainRecvHdnID + '"]:hidden').remove();

    // メインウィンドウに表示名、IDのhidden要素を追加する
    var hdnEm = '<input type="hidden" name="' + mainRecvHdnID + '" class="' + mainRecvHdnID + '" value="' + sendID + '">';
    window.opener.document.getElementById('hdn').innerHTML += hdnEm;
    
    return true;
}

/*
 * 概要: クリックした項目をメインウィンドウのセレクトボックスに送る関数(おすすめ商品用)
 *
 * 引数:   string productCodeID         : メインウィンドウの商品コード挿入先のid属性値
 *         string productCode           : メインウィンドウに挿入する商品コード
 *         string productNameID         : メインウィンドウの商品名挿入先のid属性値
 *         string productName           : メインウィンドウに挿入する商品名
 *         string productImageID        : メインウィンドウの商品一覧画像挿入先のid属性値
 *         string productImage          : メインウィンドウに挿入する商品一覧画像
 *         string hdnName               : メインウィンドウに挿入するhidden属性のname(対象商品規格ID用)
 *         string hdnValue              : メインウィンドウに挿入するhidden属性の値(対象商品規格ID用)
 * 戻り値: 
 *
 */
function sendClickRecommendToMainWindow(productCodeID, productCode, productNameID, productName, productImageID, productImage, hdnName, hdnValue) {
    
    if(!window.opener || window.opener.closed) {
        window.alert('メインウィンドウが存在しません');
        return false;
    }

    // メインウィンドウに商品コード、商品名、画像を設定する。
    window.opener.$('span[id="' + productCodeID +'"]').text(productCode);
    window.opener.$('span[id="' + productNameID +'"]').text(productName);
    window.opener.$('img[id="' + productImageID +'"]').attr("src", '/upload_images/' + productImage);

    var hdnEm = '<input type="hidden" name="' + hdnName + '" value="' + hdnValue + '">';
    window.opener.document.getElementById('hdn').innerHTML += hdnEm;
    
    return true;
}

/*
 * 概要: チェックした項目をメインウィンドウのセレクトボックスに送る関数
 *
 * 引数:   string fromClass      : ポップアップウィンドウでチェックしたCLASS名
 *         string fromNamePrefix : ポップアップウィンドウでチェックしたの表示名用のname(prefix)
 *         string toID           : メインウィンドウのフォームのセレクトボックスのID名
 *         bool   recvDupFlg     : 重複送信フラグ(true:重複可、false:重複不可)
 * 戻り値: bool                  : true/false
 *
 */
function sendCheckElementToMainWindow(fromClass, fromNamePrefix, toID, recvDupFlg) {

    if(!window.opener || window.opener.closed) {
        window.alert('メインウィンドウが存在しません');
        return false;
    }

    // 送信元チェック項目リストを作成する
    var sendID = [];
    $('.' + fromClass + ':checked').each(function(){
        sendID.push($(this).val());
    });

    // メインウィンドウに既に追加されているセレクトボックスのリストを取得する
    window.opener.$('select[id="' + toID + '"] option').each(function() {
        $(this).attr('selected', 'selected');
    });
    var toList = window.opener.$('select[id="' + toID + '"]').val();
    window.opener.$('#' + toID).children().removeAttr('selected');

    try {
        // メインウィンドウのセレクトボックスに追加
        for (var i = 0; i < sendID.length; i++) {
            if (toList !== null && $.inArray(sendID[i], toList) >= 0 && recvDupFlg === false) {
                // 追加済みの項目はスキップする(二重追加防止)
                continue;
            }
            var text = $('input[name="' + fromNamePrefix + sendID[i] + '"]').val();
            // ↓IE11の場合、HierarchyRequestError
            //window.opener.$('#' + toID).append($('<option>').html(text).val(sendID[i]));

            var addEm = '<option value="' + sendID[i] + '">' + text +'</option>';
            window.opener.document.getElementById(toID).innerHTML += addEm;
            var hdnEm = '<input type="hidden" name="' + fromNamePrefix + '[]' + '" class="' + fromNamePrefix + sendID[i] + '" value="' + text + '">';
            window.opener.document.getElementById('hdn').innerHTML += hdnEm;

        }
    } catch(e){
        console.log(e);
        alert(e.message);
    }
    
    return true;
}

/*
 * 概要: 顧客項目をメインウィンドウに送る関数
 *
 * 引数:
 * 戻り値: bool                  : true/false
 *
 */
function sendCustomerToMainWindow(ID, point, firstName, lastName, kana, companyName, emailAddress, telNo, faxNo, zip, prefcode, citycode, address1, address2, bSubmit, normalpoint, normaldate, limitedpoint,limiteddate) {
    
    if(!window.opener || window.opener.closed) {
        window.alert('メインウィンドウが存在しません');
        return false;
    }
    
    // メインウィンドウに値を設定する。
    window.opener.$('span[id="d_customer_CustomerID"]').text(ID);
    window.opener.$('input[name="d_customer_CustomerID"]:hidden').val(ID);

    window.opener.$('span[id="d_customer_TotalPoint"]').text(point);
    window.opener.$('input[name="d_customer_TotalPoint"]:hidden').val(point);
    
    window.opener.$('input[name="d_customer_FirstName"]').val(firstName);
    window.opener.$('input[name="d_customer_LastName"]').val(lastName);
    
    window.opener.$('input[name="d_customer_Kana"]').val(kana);
    window.opener.$('input[name="d_customer_CompanyName"]').val(companyName);
    window.opener.$('input[name="d_customer_EmailAddress"]').val(emailAddress);
    window.opener.$('input[name="d_customer_TelNo"]').val(telNo);
    window.opener.$('input[name="d_customer_FaxNo"]').val(faxNo);
    window.opener.$('input[name="d_customer_Zip"]').val(zip);
    window.opener.$('select[name="d_customer_PrefCode"]').val(prefcode);
    window.opener.$('input[name="d_customer_CityCode"]:hidden').val(citycode);
    window.opener.$('input[name="d_customer_Address1"]').val(address1);
    window.opener.$('input[name="d_customer_Address2"]').val(address2);
    
    window.opener.$('span[id="d_customer_Point"]').text(normalpoint);
    window.opener.$('input[name="d_customer_Point"]:hidden').val(normalpoint);
    window.opener.$('span[id="d_customer_PointDateTo"]').text(normaldate);
    window.opener.$('input[name="d_customer_PointDateTo"]:hidden').val(normaldate);
    
    window.opener.$('span[id="d_customer_LimitedPoint"]').text(limitedpoint);
    window.opener.$('input[name="d_customer_LimitedPoint"]:hidden').val(limitedpoint);
    window.opener.$('span[id="d_customer_LimitedPointDateTo"]').text(limiteddate);
    window.opener.$('input[name="d_customer_LimitedPointDateTo"]:hidden').val(limiteddate);
    
    if (bSubmit == 'true') {
        window.opener.customerSelect();
        window.close();
    }
    
    return true;
}

/*
 * 概要: 郵便番号項目をメインウィンドウに送る関数
 *
 * 引数:
 * 戻り値: bool                  : true/false
 *
 */
function sendZipToMainWindow(field, prefcode, citycode, citytownname) {
    
    if(!window.opener || window.opener.closed) {
        window.alert('メインウィンドウが存在しません');
        return false;
    }

    // メインウィンドウに値を設定する。
    window.opener.$('select[name="' + field + '_PrefCode"]').val(prefcode);
    window.opener.$('input[name="' + field + '_CityCode"]:hidden').val(citycode);
    window.opener.$('input[name="' + field + '_Address1"]').val(citytownname);
    
    window.close();
    return true;
}

/*
 * 概要: 郵便番号項目をメインウィンドウに送る関数
 *
 * 引数:
 * 戻り値: bool                  : true/false
 *
 */
function sendZipToMainWindowForFrontSender(field, prefcode, citycode, citytownname) {
    
    if(!window.opener || window.opener.closed) {
        window.alert('メインウィンドウが存在しません');
        return false;
    }

    // メインウィンドウに値を設定する。
    window.opener.$('select[name="' + field + 'PrefCode"]').val(prefcode);
    window.opener.$('input[name="' + field + 'CityCode"]:hidden').val(citycode);
    window.opener.$('input[name="' + field + 'Address1"]').val(citytownname);
    
    window.close();
    return true;
}

/*
 * 概要: 郵便番号項目（配列）をメインウィンドウに送る関数
 *
 * 引数:
 * 戻り値: bool                  : true/false
 *
 */
function sendZipArrayToMainWindow(field, prefcode, citycode, citytownname) {
    
    if(!window.opener || window.opener.closed) {
        window.alert('メインウィンドウが存在しません');
        return false;
    }

    // メインウィンドウに値を設定する。
    window.opener.$('select[name="' + field + 'PrefCode]"]').val(prefcode);
    window.opener.$('input[name="' + field + 'CityCode]"]:hidden').val(citycode);
    window.opener.$('input[name="' + field + 'Address1]"]').val(citytownname);
    
    window.close();
    return true;
}

/*
function sendSingleProductForOrder(productClassID, orderKey, isEdit, delivKey) {
    
    if(!window.opener || window.opener.closed) {
        window.alert('メインウィンドウが存在しません');
        return false;
    }

    // メインウィンドウに商品コード、商品名、画像を設定する。
    window.opener.$('input[name="addProductClassID"]:hidden').val(productClassID);
    window.opener.$('input[name="addOrderKey"]:hidden').val(orderKey);

    if (isEdit == 'false') {
        window.opener.productSelect(delivKey);
    } else {
        window.opener.productEdit(delivKey);
    }
    return true;
}
*/

/*
 * 概要: 商品検索結果を受注編集画面に送る関数
 *       受注編集画面の「商品を検索して追加」から呼ばれる
 *
 * 引数:   string   fromClass                       : 商品選択チェックボックスのclass名
 * 戻り値: bool                                     : true/false
 *
 */
function addMultiProductForOrderMainWindow(fromClass) {
    
    if (!window.opener || window.opener.closed) {
        window.alert('メインウィンドウが存在しません');
        return false;
    }

    // 商品追加の場合は、検索結果から複数選択が可能なため、送信元チェック項目リストを作成する
    var sendID = [];
    $('.' + fromClass + ':checked').each(function(){
        sendID.push($(this).val());
    });
    
    try {
        // メインウィンドウのデータ渡し用セレクトボックス(非表示)に選択された商品規格IDを追加
        for (var i = 0; i < sendID.length; i++) {
            var addEm = '<option value="' + sendID[i] + '">' + sendID[i] +'</option>';
            window.opener.document.getElementById('addProductFromPopup').innerHTML += addEm;
        }
        window.opener.add_product_from_popup();
        window.close();
        return true;
        
    } catch(e){
        console.log(e);
        alert(e.message);
    }
}

/*
 * 概要: 商品検索結果を商品登録画面に送る関数
 *      商品検索ポップアップの「チェックした商品の追加」から呼ばれる
 *
 * 引数:   string   fromClass                       : 商品選択チェックボックスのclass名
 * 戻り値: bool                                     : true/false
 *
 */
function addMultiSetProductForMainWindow(fromClass) {
    
    if (!window.opener || window.opener.closed) {
        window.alert('メインウィンドウが存在しません');
        return false;
    }

    // 商品追加の場合は、検索結果から複数選択が可能なため、送信元チェック項目リストを作成する
    var sendID = [];
    $('.' + fromClass + ':checked').each(function(){
        sendID.push($(this).val());
    });
    
    try {
        // メインウィンドウのデータ渡し用セレクトボックス(非表示)に選択された商品規格IDを追加
        for (var i = 0; i < sendID.length; i++) {
            // ↓IE11の場合、HierarchyRequestError
            //window.opener.$('#' + toID).append($('<option>').html(text).val(sendID[i]));
            var addEm = '<option value="' + sendID[i] + '">' + sendID[i] +'</option>';
            window.opener.document.getElementById('addProductFromPopup').innerHTML += addEm;
        }
        window.opener.add_set_product_from_popup();
//        window.close();
        return true;
        
    } catch(e){
        console.log(e);
        alert(e.message);
    }}

/*
 * 概要: 商品検索結果を受注編集画面に送る関数
 *
 * 引数:
 * 戻り値: bool                  : true/false
 *
 */
function addSingleProductForOrderMainWindow(d_product_class_ProductClassID) {
     if(!window.opener || window.opener.closed) {
        window.alert('メインウィンドウが存在しません');
        return false;
    }

    try {
        // メインウィンドウのセレクトボックスに追加
        // ↓IE11の場合、HierarchyRequestError
        //window.opener.$('#' + toID).append($('<option>').html(text).val(sendID[i]));
        var addEm = '<option value="' + d_product_class_ProductClassID + '">' + d_product_class_ProductClassID +'</option>';
        window.opener.document.getElementById('addProductFromPopup').innerHTML += addEm;
//        window.close();
        window.opener.add_product_from_popup();

        return true;
        
    } catch(e){
        console.log(e);
        alert(e.message);
    }
}

/*
 * 概要: 商品検索結果を商品登録画面に送る関数
 *
 * 引数:
 * 戻り値: bool                  : true/false
 *
 */
function addSingleSetProductForMainWindow(d_product_class_ProductClassID) {
     if(!window.opener || window.opener.closed) {
        window.alert('メインウィンドウが存在しません');
        return false;
    }

    try {
        // メインウィンドウのセレクトボックスに追加
        // ↓IE11の場合、HierarchyRequestError
        //window.opener.$('#' + toID).append($('<option>').html(text).val(sendID[i]));
        var addEm = '<option value="' + d_product_class_ProductClassID + '">' + d_product_class_ProductClassID +'</option>';
        window.opener.document.getElementById('addProductFromPopup').innerHTML += addEm;
//        window.close();
        window.opener.add_set_product_from_popup();

        return true;
        
    } catch(e){
        console.log(e);
        alert(e.message);
    }
}

/*
 * 概要: 商品検索結果を商品登録画面に送る関数(顧客別単価ページ専用)
 *
 * 引数:
 * 戻り値: bool                  : true/false
 *
 */
function addSingleProductForMainWindow(d_product_class_ProductClassID) {
     if(!window.opener || window.opener.closed) {
        window.alert('メインウィンドウが存在しません');
        return false;
    }

    try {
        // メインウィンドウのセレクトボックスに追加
        var addEm = '<option value="' + d_product_class_ProductClassID + '">' + d_product_class_ProductClassID +'</option>';
        window.opener.document.getElementById('addProductFromPopup').innerHTML += addEm;
        window.opener.add_product_from_popup();

        return true;
        
    } catch(e){
        console.log(e);
        alert(e.message);
    }
}

/*
 * 概要: 商品検索結果を受注編集画面に送る関数
 *       受注編集画面の「変更」から呼ばれる
 *
 * 引数:   string   productClassID                  : 商品規格ID
 * 戻り値: bool                                     : true/false
 *
 */
function changeProductForOrderMainWindow(productClassID) {
    
    if (!window.opener || window.opener.closed) {
        window.alert('メインウィンドウが存在しません');
        return false;
    }

    try {
        var addEm = '<option value="' + productClassID + '">' + productClassID +'</option>';
        window.opener.document.getElementById('addProductFromPopup').innerHTML += addEm;
        window.opener.change_product_from_popup();
        window.close();
        return true;
        
    } catch(e){
        console.log(e);
        alert(e.message);
    }
}