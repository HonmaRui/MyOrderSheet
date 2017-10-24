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
      <li><iframe src="https://ghbtns.com/github-btn.html?user=HonmaRui&amp;repo=MyOrderSheet&amp;type=star&amp;count=true" frameborder="0" scrolling="0" width="78px" height="20px"></iframe></li>
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

{literal}
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
<script src="https://apis.google.com/js/platform.js" async defer>{lang: 'ja'}</script>
{/literal}
<script type="text/javascript">
  $('.bs-component [data-toggle="popover"]').popover();
  $('.bs-component [data-toggle="tooltip"]').tooltip();
</script>
</body>
</html>