<div class="flow" align="left">

<div align="right">
	<input type="button" value="{$translate.manager.addact}" class="buttontext" title="{$translate.manager.addact}" onClick="addActors({$itemId})"/>
</div>

{if is_array($itemPornstars) && count($itemPornstars)>0}
	<table cellspacing="1" cellpadding="1" border="0">
	{foreach from=$itemPornstars item=name key=key}
	<tr>
		<td><li><a href="?page=pornstar&amp;pornstar_id={$key}" target="_blank">{$name}</a></li></td>
		<td>Pornstar links to search eingines come here</td>
	</tr>
	{/foreach}
	</table>
{else}
	{$translate.movie.noactors}
{/if}



</div>
