<div id="one_maincolumn">
    <div id="mypagecolumn">
        {include file='../index/header-info.tpl'}
        <div id="mycontentsarea">
            <h3><img src="{$smarty.const.FRONT_IMG_SSL}mypage/subtitle04.gif" width="515" height="32" alt="退会手続き"></h3>
            <div id="completetext">
                <p class="changetext">プロユーザー退会手続きが完了いたしました。<br>
                    プロユーザー情報ページをご利用いただき誠にありがとうございました。<br>
                    またのご利用を心よりお待ち申し上げます。<br>
                    <br>
                    TEL：{$arrBaseInfo["d_baseinfo_TelNo"]} <br>
                    E-mail：<a href="mailto:{$arrBaseInfo['d_baseinfo_MailAddress2']}">{$arrBaseInfo['d_baseinfo_MailAddress2']}</a></p>
            </div>
        </div>
    </div>        
</div>
