<div id="one_maincolumn">
    <div id="mypagecolumn">
        {include file='./header-info.tpl'}
        <div id="mycontentsarea">
            <h3><img src="{$smarty.const.FRONT_IMG_SSL}mypage/subtitle05.gif" width="515" height="32" alt="サンプル請求履歴詳細"></h3>
            <p class="myconditionarea">
                <strong>ご請求日：&nbsp;</strong>{$arrOrder[0]["d_order_CreatedTime"]|date_format:"%Y/%m/%d %H:%M"}<br>
            </p>

            <table summary="サンプル請求商品詳細">
                <tbody>
                    <tr>
                        <th>商品名</th>
                        <th>枚数</th>
                    </tr>
                    {foreach from=$arrOrder item=arrOrderDetail}
                    <tr>
                        <td>
                            <a href="{$smarty.const.SSL_URL}/products/detail/{$arrOrderDetail['d_order_detail_ProductID']}">{$arrOrderDetail["d_order_detail_ProductName"]}</a>
                        </td>
                        <td align="center">{$arrOrderDetail["d_order_detail_Quantity"]}</td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>

            <table summary="お届け先" class="delivname">
                <thead>
                    <tr>
                        <th colspan="5">▼お届け先</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>会社名</th>
                        <td>{$arrOrder[0]["d_order_OrderDeliveryCompanyName"]}</td>
                    </tr>
                    <tr>
                        <th>部署名</th>
                        <td>{$arrOrder[0]["d_order_OrderDeliveryDepartmentName"]}</td>
                    </tr>
                    <tr>
                        <th>氏名</th>
                        <td>{$arrOrder[0]["d_order_OrderDeliveryName"]}</td>
                    </tr>
                    <tr>
                        <th>氏名（フリガナ）</th>
                        <td>{$arrOrder[0]["d_order_OrderDeliveryNameKana"]}</td>
                    </tr>
                    <tr>
                        <th>郵便番号</th>
                        <td>〒{$arrOrder[0]["d_order_OrderDeliveryZip"]}</td>
                    </tr>
                    <tr>
                        <th>住所</th>
                        <td>{$arrPref[$arrOrder[0]["d_order_OrderDeliveryPrefCode"]]}{$arrOrder[0]["d_order_OrderDeliveryAddress1"]}{$arrOrder[0]["d_order_OrderDeliveryAddress2"]}</td>
                    </tr>
                    <tr>
                        <th>電話番号</th>
                        <td>{$arrOrder[0]["d_order_OrderDeliveryTelNo"]}</td>
                    </tr>
                </tbody>
            </table>

            <div class="tblareabtn">
                <a href="javascript:history.back()" onmouseover="chgImg('{$smarty.const.FRONT_IMG_SSL}common/b_back_on.gif', 'change');" onmouseout="chgImg('{$smarty.const.FRONT_IMG_SSL}common/b_back.gif', 'change');"><img src="{$smarty.const.FRONT_IMG_SSL}common/b_back.gif" width="150" height="30" alt="戻る" name="change" id="change"></a>
            </div>
        </div>
    </div>     
</div>