<table cellspacing="1" cellpadding="1" border="0">
{foreach from=$itemCovers item=i key=key}
<tr>
	<td class="tblb" valign="top">{$i.typename}</td>
	<td><input type="text" name="{$i.typename}" size="20" class="input" value="{$i.filename}"/></td>
	<td><input type="file" name="{$i.typeid}" value="{$i.typename}" size="10" class="input"/></td>
	<td>delete cover link</td>
</tr>
{/foreach}
</table>
