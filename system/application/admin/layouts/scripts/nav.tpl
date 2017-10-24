    <header>
      <div id="pagetop"></div>
      <div id="header-block">
        <div class="header-m">
          <div style="text-align: left;padding-top: 10px;padding-left: 10px;padding-bottom: 7px;width: 400px;">
              <a href="{$smarty.const.ADMIN_URL}"><img src="{$smarty.const.ADMIN_IMG_DIR}common/title_search.gif" alt="" width="378" height="26"></a>
          </div>
          
{*          <div id="globalnavi">
            <nav>
              <ul id="nav" class="clearfix">
              {foreach from=$arrMenu key=key1 item=value1 name=loop}
              {assign var="zeroPadding" value=$smarty.foreach.loop.iteration|string_format:"%02d"}
                <li class="menu-li">
                  <a href="{$value1["middleMenu"][0]["m_system_function_detail1_URL"]}" id="mmenu{$smarty.foreach.loop.iteration}" onmouseover="MM_swapImage('gl_navi{$zeroPadding}','','{$smarty.const.ADMIN_IMG_DIR}navi/gl_navi{$zeroPadding}_on.png',1);" onmouseout="MM_swapImgRestore();">
                    <img src="{$smarty.const.ADMIN_IMG_DIR}navi/gl_navi{$zeroPadding}_{$arrGlobalNavPos[{$smarty.foreach.loop.index}]}.png" width="130" height="40" alt="{$value1["m_system_function_Name"]}" name="gl_navi{$zeroPadding}" class="imgover"></a>
                  <ul class="smenu-ul" style="visibility: hidden; display: block;">
                  {foreach from=$value1["middleMenu"] key=key2 item=value2}
                    <li class="smenu-li">
                        <a href="{$value2["m_system_function_detail1_URL"]}">{$value2["m_system_function_detail1_Name"]}</a>
                    </li>
                  {/foreach}
                  </ul>
                </li>      
              {/foreach}
              </ul>
            </nav>
          </div>*}
        </div>
        <div class="header-r">
          <div class="innerbox">
            <p>ログイン：　{$smarty.session.Admin.Name}　様</p>
            <input type="button" class="btn-logout" onclick="execLogout(document.forms[0]);">
          </div>
       </div>
     </div>
    </header>
    
