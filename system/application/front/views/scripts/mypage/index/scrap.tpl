<div id="one_maincolumn">
    <div id="mypagecolumn">
        {include file='./header-info.tpl'}
        <div id="mycontentsarea">
            <form name="form1" method="post" action="/mypage/index.php">
                <input type="hidden" name="order_id" value="" />
                <input type="hidden" name="pageno" value="" />
                <h3><img src="{$smarty.const.FRONT_IMG_SSL}mypage/subtitle06.gif" width="515" height="32" alt="" /></h3>
                <p>{$stTest}</p>
            </form>
        </div>
    </div>        
</div>
