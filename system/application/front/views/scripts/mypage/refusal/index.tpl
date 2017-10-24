<div id="one_maincolumn">
    <div id="mypagecolumn">
        {include file='../index/header-info.tpl'}
        <div id="mycontentsarea">
            <h3><img src="{$smarty.const.FRONT_IMG_SSL}mypage/subtitle04.gif" width="515" height="32" alt="退会手続き" /></h3>
            <form name="form1" method="post" action="{$smarty.const.SSL_URL}/mypage/refusal">
                <input type="hidden" name="csrf" value="{$stCsrf}">
                <div id="completetext">
                    プロユーザーを退会された場合には、現在保存されている請求履歴や、お届け先などの情報は、すべて削除されますがよろしいでしょうか？
                    <div class="tblareabtn">
                        <input type="image" onmouseover="chgImgImageSubmit('{$smarty.const.FRONT_IMG_SSL}mypage/b_refuse_on.gif', this);" onmouseout="chgImgImageSubmit('{$smarty.const.FRONT_IMG_SSL}mypage/b_refuse.gif', this);" src="{$smarty.const.FRONT_IMG_SSL}mypage/b_refuse.gif" class="box180" alt="会員退会を行う" name="refusal" id="refusal" />
                    </div>
                    <p class="mini"><em>※退会手続きが完了した時点で、現在保存されている請求履歴や、お届け先等の情報はすべてなくなりますのでご注意ください。</em></p>
                </div>
            </form>
        </div>
    </div>        
</div>
