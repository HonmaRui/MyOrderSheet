<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/ja_JP/sdk.js#xfbml=1&version=v2.10';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<footer class="small">
  <div class="social-button">
    <ul>
      <li><iframe id="twitter-widget-1" scrolling="no" frameborder="0" allowtransparency="true" class="twitter-share-button twitter-share-button-rendered twitter-tweet-button" title="Twitter Tweet Button" src="http://platform.twitter.com/widgets/tweet_button.82c7dfc5ca6196724781971f8af5eca4.ja.html#dnt=true&amp;id=twitter-widget-1&amp;lang=ja&amp;original_referer={$smarty.const.URL}&amp;size=m&amp;text={$smarty.const.SITE_TITLE}&amp;time=1508802188564&amp;type=share&amp;url={$smarty.const.URL}" style="position: static; visibility: visible; width: 75px; height: 20px;"></iframe></li>
      <li><div class="fb-like" data-href="{$smarty.const.URL}" data-layout="button_count" data-action="like" data-size="small" data-show-faces="true" data-share="false"></div></li>
      <li><div class="g-plusone" data-size="tall"></div></li>
    </ul>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-xs-12 text-center copyright">
        © Honma Rui
      </div>
    </div>
  </div>
</footer>
      
<!-- モーダル・ダイアログ -->
<div class="modal fade" id="detail" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <h4 id="modal-title"></h4>
                <div class="col-lg-3 order-left photo" style="padding: 0 !important;" id="modal-image"></div>
                <div class="col-lg-9 text-overflow order-right" id="modal-text"></div>
                <div class="order-under" id="sp-data"></div>
                <div class="order-under" id="pc-data"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
            </div>
        </div>
    </div>
</div>

{literal}
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
<script src="https://apis.google.com/js/platform.js" async defer>{lang: 'ja'}</script>
{/literal}
<script>
    
var URL = '{$smarty.const.URL}';
var IMG_URL = '{$smarty.const.SHEET_IMG_DIR}';
    
jQuery(function($) {
    $('.with-height-limit').each(function(index, node) {
        var $outer = $(this);
        var id = $(this).attr("id");
        $outer.wrapInner('<div />');
        var $inner = $outer.children();
        var outerHeight = $outer.height();
        var innerHeight = $inner.height();
        if (innerHeight > outerHeight) {
            $('<a />', {
                "class": 'ellipsis',
                text: '続きを読む',
                href: 'javascript: void(0)',
                "data-toggle": 'modal',
                "data-target": '#detail',
                "data-recipient": id,
            }).appendTo($outer);
        }
    });
    
    $("#order-tab").click(function(){
        setTimeout(function(){
            $('.with-height-limit').each(function(index, node) {
                var $outer = $(this);
                var id = $(this).attr("id");
                $outer.wrapInner('<div />');
                var $inner = $outer.children();
                var outerHeight = $outer.height();
                var innerHeight = $inner.height();
                if (innerHeight > outerHeight) {
                    $('<a />', {
                        "class": 'ellipsis',
                        text: '続きを読む',
                        href: 'javascript: void(0)',
                        "data-toggle": 'modal',
                        "data-target": '#detail',
                        "data-recipient": id,
                    }).appendTo($outer);
                    
                }
            });
        },1000);
    });
    
    // タイトル入力後
    $('input[name="d_order_sheet_Title"]').blur(function() {
        checkInput();
    });
    // 内容入力後
    $('#d_order_sheet_Contents').blur(function() {
        checkInput();
    });
    // 画像設定後
    $('#InputFile').bind('change', function() {
        checkInput();
    });
});

$('#detail').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var recipient = button.data('recipient');
    
    getSheetInfo(recipient);
});

function checkInput() {
    var Title = $('input[name="d_order_sheet_Title"]').val();
    var Text = $('#d_order_sheet_Contents').val();
    if (document.getElementById('InputFile').files[0]) {
        var imgSize1 = document.getElementById('InputFile').files[0].size;
        var imgType1 = document.getElementById('InputFile').files[0].type;
    } else {
        var imgSize1 = 1;
        var imgType1 = "jpg";
    }
    var SubmitButton = document.getElementById('add-button');
    
    if (Title != "" && Text != "" && imgSize1 < 2100000 && (imgType1 == "jpg" || imgType1 == "image/jpeg" || imgType1 == "image/pjpeg" || imgType1 == "png" || imgType1 == "image/png")) {
        SubmitButton.disabled = false;
    } else {
        SubmitButton.disabled = true;
    }
}

checkInput();
</script>
</body>
</html>