<table cellspacing="1" cellpadding="1" border="0">
{foreach from=$itemCovers item=i key=key}
<tr>
	<td class="tblb" valign="top">{$i.type}</td>
	<td><input type="text" name="{$i.type}" size="24" class="input" value="{$i.file}"/></td>
	<td><input type="file" name="{$key}" value="{$i.type}" size="10" class="input"/></td>
	{if $i.id != ''}
	<td><img src="images/thrashcan.gif" style="vertical-align: middle" onclick="deleteCover({$i.id},{$itemId})" alt="delete cover" border="0"/> <i>({$i.size})</i></td>
	{else}
	<td>&nbsp;</td>
	{/if}
</tr>
{/foreach}
</table>
