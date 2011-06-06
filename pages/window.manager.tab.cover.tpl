<table cellspacing="1" cellpadding="1" border="0">
{foreach from=$itemCovers item=i key=key}
<tr>
	<td class="tblb" valign="top" nowrap="nowrap">{$i.type}</td>
	{if $i.id eq ''}
	<td><input type="file" name="{$key}" value="{$i.type}" size="25" class="input"/></td>
	<td>&nbsp;</td>
	{else}
	<td>{$i.file|lower}</td>
	<td><img src="images/thrashcan.gif" style="vertical-align:middle" onclick="deleteCover({$i.id},{$itemId})" alt="delete cover" border="0"/> <i>({$i.size})</i></td>
	{/if}
</tr>
{/foreach}
</table>
