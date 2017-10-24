{if $layout->header_tpl}
{include file=$layout->header_tpl}
{/if}
{if $layout->nav_tpl}
{include file=$layout->nav_tpl}
{/if}
{if $layout->sidemenu_tpl}
{include file=$layout->sidemenu_tpl}
{/if}
{$content}
{if $layout->footer_tpl}
{include file=$layout->footer_tpl}
{/if}
