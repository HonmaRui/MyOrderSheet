<div id="two_maincolumn">
    ﻿<div id="undercolumn">
        <div id="undercolumn_entry">
            <h2 class="title">
                <img src="{$smarty.const.FRONT_IMG_SSL}entry/agree_title.jpg" width="580" height="40" alt="ご利用規約" />
            </h2>
            <p><em>【重要】 プロユーザー登録をされる前に、下記ご利用規約をよくお読みください。</em><br />
                マナトレーディングでは、お仕事でご利用のお客様にプロユーザー向けサービスをご用意しております。<br />
                プロユーザー登録をいただきますと、メールアドレスとパスワードでログインすることで web からのカットサンプルのご請求も可能となります。<br />
                サービス内容につきましては順次拡大していく予定ですので、下記ご利用規約をよくお読みのうえ ご登録をいただけますようお願いいたします。</p>
            <textarea name="textfield" class="area470"  cols="80" rows="30" readonly="readonly">{$stMemberShip}</textarea>
            <form name="agree_form" id="agree_form" method="post" action="{$smarty.const.SSL_URL}/entry">
                <input type="hidden" name="mode" value="">
                {if !$bIsLogin}
                    <div class="tblareabtn">
                        <a href="{$smarty.const.SSL_URL}" onmouseover="chgImg('{$smarty.const.FRONT_IMG_SSL}entry/b_noagree_on.gif', 'b_noagree');" onmouseout="chgImg('{$smarty.const.FRONT_IMG_SSL}entry/b_noagree.gif', 'b_noagree');">
                            <img src="{$smarty.const.FRONT_IMG_SSL}entry/b_noagree.gif" width="180" height="30" alt="同意しない" border="0" name="b_noagree" />
                        </a>&nbsp;
                        <a href="javascript:void(0)" onmouseover="chgImg('{$smarty.const.FRONT_IMG_SSL}entry/b_agree_on.gif', 'b_agree');" onmouseout="chgImg('{$smarty.const.FRONT_IMG_SSL}entry/b_agree.gif', 'b_agree');">
                            <img src="{$smarty.const.FRONT_IMG_SSL}entry/b_agree.gif" width="180" height="30" alt="規約に同意して会員登録" border="0" name="b_agree" id="agree"/>
                        </a>
                    </div>
                {/if}
            </form>
        </div>
    </div>        
</div>
<script>
$(function() {

    // 同意ボタン
    $('#agree').click(function() {
        setMode('agree');
    });

});

function setMode(mode) {
    var em = document.agree_form;
    em.mode.value = mode;
    em.submit();
}
    
</script>