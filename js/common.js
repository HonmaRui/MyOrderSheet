function getSheetInfo(ordersheetID) {

  var data = {ordersheetID: ordersheetID};
  
  $.ajax({
    type: "POST",
    async: false,
    url: "/MyOrderSheet/get-sheet-info",
    data: data,
    dataType: 'json',
    beforeSend: function(){
    },
    success: function(data){
        set_detail(data);
    },
    error: function(jqXHR, textStatus, errorThrown) {
    }
  });
}

function set_detail(my_JSON) {
    
    // タイトル
    document.getElementById("modal-title").innerHTML = my_JSON.d_order_sheet_Title;
    
    // 画像
    if (my_JSON.d_order_sheet_ImageFileName1 != "" && my_JSON.d_order_sheet_ImageFileName1 != null) {
        document.getElementById("modal-image").innerHTML =  '<img src="' + IMG_URL + my_JSON.d_order_sheet_ImageFileName1 + '">';
    } else {
        document.getElementById("modal-image").innerHTML =  '<img src="' + IMG_URL + 'noimage.png">';
    }
    
    // テキスト
    document.getElementById("modal-text").innerHTML = '<p class="sheet-text">' + my_JSON.d_order_sheet_Contents + '</p>';
    
    /////// PC用
    // ユーザー名・日付・いいね
    var replaceString = '<span class="glyphicon glyphicon-user" aria-hidden="true"></span> ';
    
    if (my_JSON.d_order_sheet_CustomerName != "") {
        replaceString += '<a href="' + URL + '/customer/' + my_JSON.d_order_sheet_CustomerID + '">' + my_JSON.d_order_sheet_CustomerName + '</a> ';
    } else {
        replaceString += '未登録ユーザー ';
    }
    
    replaceString += '<span class="glyphicon glyphicon-time" aria-hidden="true"></span> ' + my_JSON.d_order_sheet_CreatedTime + '　';
    
//    replaceString += '<span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> <a href="/">いいね！(20)</a>';

    document.getElementById("sp-data").innerHTML = replaceString;
    
    /////// SP用
    // ユーザー名・日付・いいね
    var replaceString = '<span class="glyphicon glyphicon-user" aria-hidden="true"></span> ';
    
    if (my_JSON.d_order_sheet_CustomerName != "") {
        replaceString += '<a href="' + URL + '/customer/' + my_JSON.d_order_sheet_CustomerID + '">' + my_JSON.d_order_sheet_CustomerName + '</a><br>';
    } else {
        replaceString += '未登録ユーザー<br>';
    }
    
    replaceString += '<span class="glyphicon glyphicon-time" aria-hidden="true"></span> ' + my_JSON.d_order_sheet_CreatedTime + '<br>';
    
//    replaceString += '<span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> <a href="/">いいね！(20)</a>';

    document.getElementById("pc-data").innerHTML = replaceString;

}