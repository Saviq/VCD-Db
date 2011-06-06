{if $noItemData} 
<p>
	{$translate.misc.nocats}
</p>
{else}
<form name="customkeys" method="post" action="{$smarty.server.SCRIPT_NAME}?page=movies&amp;do=seen&amp;index={$itemPage}">
{assign var='base' value=$smarty.server.SCRIPT_NAME}
<table cellpadding="1" cellspacing="1" border="0" width="100%" class="tblsmall">
<tr>
	<td class="bold" width="65%">{$translate.movie.title}</td>
	<td class="bold">{$translate.movie.media}</td>
	<td class="bold" width="5%">{$translate.misc.seen}</td>
</tr>
{foreach from=$keyList item=i key=k}
{if $i.checked}
	{assign var='checked' value=' checked="checked"'}
{else}
	{assign var='checked' value=''}
{/if}
<tr>
	<td>{$i.title|escape}</td>
	<td nowrap="nowrap">{$i.mediatype}</td>
	<td align="right"><input type="checkbox" class="nof" value="1" name="k[{$k}]"{$checked}/></td>
</tr>
{/foreach}
<tr>
	<td>{html_options id=pagelist name=pagelist options=$pagesList selected=$smarty.get.index onchange="location.href='$base?page=movies&amp;do=seen&amp;index='+this.value"}</td>
	<td colspan="2" align="right"><input type="submit" class="inp" name="save" value="{$itemBtnSave}"/></td>
</tr>
</table>
<input type="hidden" name="currentIds" value="{$currentList}"/>
</form>
{/if}