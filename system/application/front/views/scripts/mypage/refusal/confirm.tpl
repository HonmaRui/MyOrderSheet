<div id="one_maincolumn">
    <div id="mypagecolumn">
        {include file='../index/header-info.tpl'}
        <div id="mycontentsarea">
            <h3><img src="{$smarty.const.FRONT_IMG_SSL}mypage/subtitle04.gif" width="515" height="32" alt="退会手続き"></h3>
            <form name="form" method="post" action="{$smarty.const.SSL_URL}/mypage/refusal/confirm">
                <input type="hidden" name="csrf" value="{$stCsrf}">
                <input type="hidden" name="mode" value="confirm">
                <div id="completetext">
                    <p>退会手続きを実行してもよろしいでしょうか？</p>
                    <div class="tblareabtn">
                        <a href="javascript:history.back()" onmouseover="chgImg('{$smarty.const.FRONT_IMG_SSL}mypage/b_no_on.gif', 'refusal_no');" onmouseout="chgImg('{$smarty.const.FRONT_IMG_SSL}mypage/b_no.gif', 'refusal_no');"><img src="{$smarty.const.FRONT_IMG_SSL}mypage/b_no.gif" width="180" height="30" alt="いいえ、退会しません" name="refusal_no" id="refusal_no"></a>&nbsp;
                        <input type="image" onmouseover="chgImgImageSubmit('{$smarty.const.FRONT_IMG_SSL}mypage/b_yes_on.gif', this);" onmouseout="chgImgImageSubmit('{$smarty.const.FRONT_IMG_SSL}mypage/b_yes.gif', this);" src="{$smarty.const.FRONT_IMG_SSL}mypage/b_yes.gif" class="box180" alt="はい、退会します" name="refusal_yes" id="refusal_yes">
                    </div>
                    <p class="mini"><em>※退会手続きが完了した時点で、現在保存されている請求履歴や、お届け先等の情報はすべてなくなりますのでご注意ください。</em></p>
                </div>
            </form>
        </div>
    </div>        
</div>
