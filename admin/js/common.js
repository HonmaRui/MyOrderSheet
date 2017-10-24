/*
 * 概要: ログアウトする
 *
 * 引数:   form     : 呼び出し元のフォーム名
 * 戻り値: なし
 *
 */
function execLogout(form) {
    var element = form;
    element.mode.value = 'logout';
    element.action = '/MyOrderSheet/admin/logout';
    element.submit();
}

function initOnload() {
	if (!document.getElementById) return
	
	var aImages = document.getElementsByTagName('img');
	var source = '';

	var fsw=0;

	for (var i = 0; i < aImages.length; i++) {		
		if (aImages[i].className == 'imgover') {
			var src = aImages[i].getAttribute('src');
			var ftype = src.substring(src.lastIndexOf('.'), src.length);
			var hsrc = src.replace(ftype, '_o'+ftype);

			if (fsw != 0) {
				source += ',';
			}
			source +=  "'"+ hsrc + "'";
			fsw += 1;
		}
	}
	MM_preloadImages(source);
}

// -- ロールオーバー、ロールアウト時の画像切換 --
////////////
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
////////////

function show_loading() {
    $.blockUI({
        message: '只今処理中です',
        css: {
            borderRadius: '10px',
            padding: '10px',
            backgroundColor: '#fff',
            width: '200px',
            margin: '0 auto',
            opacity: .9,
            color: '#000'
        },
        overlayCSS: {
            backgroundColor: '#000',
            opacity: 0.5
        }
    });
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

function loading() {
    $.blockUI({
        message: '只今処理中です',
        css: {
            borderRadius: '10px',
            padding: '10px',
            backgroundColor: '#fff',
            width: '200px',
            margin: '0 auto',
            opacity: .9,
            color: '#000'
        },
        overlayCSS: {
            backgroundColor: '#000',
            opacity: 0.5
        }
    });
}  

function loadfinished() {
    $.unblockUI();
}

function waitingProcess() {
    var timer_id = setInterval(function () {

        $.ajax({
            type: "POST",
            url: "{$smarty.const.ADMIN_URL}system/waiting-process-for-ajax",
            data: null,
            dataType: 'json',
            beforeSend: function(){
            },
            success: function(data){
                clearInterval(timer_id);
                loadfinished();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                clearInterval(timer_id);
                loadfinished();
            }
        });
    } , 1000);        
}

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
