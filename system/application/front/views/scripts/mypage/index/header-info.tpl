<h2 class="title"><img src="{$smarty.const.FRONT_IMG_SSL}mypage/title.jpg" width="700" height="40" alt="MYページ" /></h2>
<div id="mynavarea">
    <ul class="button_like">
        <li>
            {if $stCurrentController == "index"}
                <a href="{$smarty.const.SSL_URL}/mypage">
                    <img src="{$smarty.const.FRONT_IMG_SSL}mypage/navi01_on.jpg" width="170" height="30" alt="購入履歴一覧" border="0" name="m_navi01" />
                </a>
            {else}
                <a href="{$smarty.const.SSL_URL}/mypage" onmouseover="chgImg('{$smarty.const.FRONT_IMG_SSL}mypage/navi01_on.jpg', 'm_navi01');" onmouseout="chgImg('{$smarty.const.FRONT_IMG_SSL}mypage/navi01.jpg', 'm_navi01');">
                    <img src="{$smarty.const.FRONT_IMG_SSL}mypage/navi01.jpg" width="170" height="30" alt="購入履歴一覧" border="0" name="m_navi01" />
                </a>
            {/if}
        </li>
        <li>
            {if $stCurrentController == "scrap"}
                <a href="{$smarty.const.SSL_URL}/mypage/scrap">
                    <img src="{$smarty.const.FRONT_IMG_SSL}mypage/navi05_on.jpg" width="170" height="30" alt="スクラップブック" border="0" name="m_navi05" />
                </a>
            {else}
                <a href="{$smarty.const.SSL_URL}/mypage/scrap" onmouseover="chgImg('{$smarty.const.FRONT_IMG_SSL}mypage/navi05_on.jpg', 'm_navi05');" onmouseout="chgImg('{$smarty.const.FRONT_IMG_SSL}mypage/navi05.jpg', 'm_navi05');">
                    <img src="{$smarty.const.FRONT_IMG_SSL}mypage/navi05.jpg" width="170" height="30" alt="スクラップブック" border="0" name="m_navi05" />
                </a>
            {/if}
        </li>
        <li>
            {if $stCurrentController == "change"}
                <a href="{$smarty.const.SSL_URL}/mypage/change">
                    <img src="{$smarty.const.FRONT_IMG_SSL}mypage/navi02_on.jpg" width="170" height="30" alt="会員登録内容変更" border="0" name="m_navi02" />
                </a>
            {else}
                <a href="{$smarty.const.SSL_URL}/mypage/change" onmouseover="chgImg('{$smarty.const.FRONT_IMG_SSL}mypage/navi02_on.jpg', 'm_navi02');" onmouseout="chgImg('{$smarty.const.FRONT_IMG_SSL}mypage/navi02.jpg', 'm_navi02');">
                    <img src="{$smarty.const.FRONT_IMG_SSL}mypage/navi02.jpg" width="170" height="30" alt="会員登録内容変更" border="0" name="m_navi02" />
                </a>
            {/if}
        </li>
        <li>
            {if $stCurrentController == "delivery"}
                <a href="{$smarty.const.SSL_URL}/mypage/delivery">
                    <img src="{$smarty.const.FRONT_IMG_SSL}mypage/navi03_on.jpg" width="170" height="30" alt="お届け先追加・変更" border="0" name="m_navi03" />
                </a>
            {else}
                <a href="{$smarty.const.SSL_URL}/mypage/delivery" onmouseover="chgImg('{$smarty.const.FRONT_IMG_SSL}mypage/navi03_on.jpg', 'm_navi03');" onmouseout="chgImg('{$smarty.const.FRONT_IMG_SSL}mypage/navi03.jpg', 'm_navi03');">
                    <img src="{$smarty.const.FRONT_IMG_SSL}mypage/navi03.jpg" width="170" height="30" alt="お届け先追加・変更" border="0" name="m_navi03" />
                </a>
            {/if}
        </li>
        <li>
            {if $stCurrentController == "refusal"}
                <a href="{$smarty.const.SSL_URL}/mypage/refusal">
                    <img src="{$smarty.const.FRONT_IMG_SSL}mypage/navi04_on.jpg" width="170" height="30" alt="退会手続き" border="0" name="m_navi01" />
                </a>
            {else}
                <a href="{$smarty.const.SSL_URL}/mypage/refusal" onmouseover="chgImg('{$smarty.const.FRONT_IMG_SSL}mypage/navi04_on.jpg', 'm_navi04');" onmouseout="chgImg('{$smarty.const.FRONT_IMG_SSL}mypage/navi04.jpg', 'm_navi04');">
                    <img src="{$smarty.const.FRONT_IMG_SSL}mypage/navi04.jpg" width="170" height="30" alt="退会手続き" border="0" name="m_navi04" />
                </a>
            {/if}
        </li>
    </ul>
    <ul>
        {if $bIsLogin}
            <li>ようこそ <br />
                {$stCustomerCompanyName} {$stCustomerName}様
            </li>
        {/if}
    </ul>
</div>