<form name="customkeys" method="post" action="{$smarty.server.SCRIPT_NAME}?page=movies&amp;do=keys&amp;index={$itemPage}">
{assign var='base' value=$smarty.server.SCRIPT_NAME}
<table cellpadding="1" cellspacing="1" border="0" width="100%" class="tblsmall">
<tr>
	<td class="bold" width="65%">{$translate.movie.title}</td>
	<td class="bold">{$translate.movie.media}</td>
	<td class="bold" width="5%">{$translate.misc.key}</td>
</tr>
{foreach from=$keyList item=i key=k}
<tr>
	<td>{$i.title|escape}</td>
	<td nowrap="nowrap">{$i.mediatype}</td>
	<td align="right"><input type="text" size="3" value="{$i.key}" class="inp" name="k[{$k}]"/></td>
</tr>
{/foreach}
<tr>
	<td>{html_options id=pagelist name=pagelist options=$pagesList selected=$smarty.get.index onchange="location.href='$base?page=movies&amp;do=keys&amp;index='+this.value"}</td>
	<td colspan="2" align="right"><input type="submit" class="inp" name="save" value="{$itemBtnSave}"/></td>
</tr>
</table>
</form>