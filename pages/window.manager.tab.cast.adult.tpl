<div class="flow" align="left">

<div align="right">
	<input type="button" value="{$translate.manager.addact}" class="buttontext" title="{$translate.manager.addact}" onClick="addActors({$itemId})"/>
</div>

{if is_array($itemPornstars) && count($itemPornstars)>0}
	<table cellspacing="1" cellpadding="1" border="0">
	{foreach from=$itemPornstars item=name key=key}
	<tr>
		<td><a href="?page=pornstar&amp;pornstar_id={$key}" target="_blank">{$name}</a></td>
		<td><a href="#" onclick="javascript:jumpTo('{$name}','excalibur');return false"><img src="images/excalibur.gif" border="0"/></a></td>
		<td><a href="#" onclick="javascript:jumpTo('{$name}','goliath');return false"><img src="images/gol.gif" border="0" alt="Search Goliath Films for {$name}"/></a></td>
		<td><a href="#" onclick="javascript:jumpTo('{$name}','searchextreme');return false"><img src="images/extreme.gif" border="0" alt="Search searchextreme.com for {$name}"/></a></td>
		<td><a href="#" onclick="javascript:jumpTo('{$name}','eurobabe');return false"><img src="images/eurobabe.gif" border="0" alt="Search eurobabeindex.com for {$name}"/></a></td>
		<td><a href="#" onclick="javascript:jumpTo('{$name}','google');return false"><img src="images/g.gif" border="0" alt="Search Google images for {$name}"/></a></td>
		<td><a href="#" onclick="javascript:changePornstar({$key});return false">[{$translate.misc.change}]</a></td>
		<td>&nbsp;&nbsp;<a href="#" onclick="del_actor($key,$itemId)">[{$translate.misc.delete}]</a></td>
	</tr>
	{/foreach}
	</table>
{else}
	{$translate.movie.noactors}
{/if}



</div>
