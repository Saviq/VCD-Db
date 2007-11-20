<div class="flow" align="left">
<table cellpadding="1" cellspacing="1" border="0" id="tblmetadata">
{foreach from=$itemMetadataList item=i key=key name=metalist}
<tr>
	<td colspan="2" class="bold">{$i.name}</td>
</tr>
{foreach from=$i.metadata item=j name=meta key=typeId}
{assign var='deleteLink' value='&nbsp;'}
<tr>
	<td style="padding-left:15px">{$j.name}</td>
	<td nowrap="nowrap">
	{if $j.name eq 'nfo' && $j.value eq ''}
		<input type="file" name="{$j.htmlid}" size="26" class="input"/>
	{else}
		<input type="text" value="{$j.value}" size="40" name="{$j.htmlid}" id="{$j.htmlid}" class="input"/> 
	{/if}
	{if $j.delete}
		<img src="images/thrashcan.gif" border="0" onclick="deleteMeta({$j.id},{$itemId})" style="vertical-align:middle"/>
	{/if}
	{if $j.name eq 'filelocation'}
		<img src="images/icons/folder_go.png" border="0" style="vertical-align:middle" title="Browse for file" onclick="filebrowse('file', '{$j.htmlid}')"/>
		{if $j.value neq ''}
		<img src="images/icons/add.png" border="0" style="vertical-align:middle" title="Add playfile" onclick="addFileLocation(this,{$smarty.foreach.metalist.index},{$smarty.foreach.meta.iteration},{$smarty.foreach.meta.total},{$key},{$typeId});return false;"/>
		{/if}
	{/if}
	</td>
</tr>
{/foreach}
{/foreach}
</table>
</div>

