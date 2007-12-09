<div id="toplogo" onclick="location.href='{$config.home}'"></div>
<div id="hdr" align="center" onclick="location.href='{$config.home}'"></div>
<div id="bar">
{if $isAuthenticated}
	<a href="?page=settings">{$pageUsername}</a>
	{if $isAdmin}
		| <a href="#" onclick="openAdminConsole();return false">{$translate.menu.controlpanel}</a>
	{/if}
	| <a href="?do=logout">{$translate.menu.logout}</a>
{/if}

{if !$isAuthenticated && $canRegister} 
	<a href="?page=register">{$translate.menu.register}</a>
{/if}
| <a href="?page=detailed_search">{$translate.search.extended}</a> |
{* Generate the language selection, hide if only 1 language is available *}
{if count($languageList) > 1}
<div id="lang">
<form name="vcdlang" method="post" action="{$smarty.server.SCRIPT_NAME}">
{html_options name=lang options=$languageList selected=$selectedLanguage onchange="document.vcdlang.submit()" class="inp"}
</form>
</div>
{/if}
</div>
