<h2>Pornstars</h2>

{if is_array($alphabet) && count($alphabet)>0} 

<div align="center">
{foreach from=$alphabet item=letter}<a href="?page=pornstars&amp;view={$view}&amp;l={$letter}&amp;viewmode={$mode}">{$letter}</a><img src="images/dot.gif" border="0" hspace="5" vspace="2"/>{/foreach}
</div>

{else}
	No Pornstars found in database.
{/if}


<hr/>
<div align="center">
	<span class="bold">666 pornstars</span> begin with letter {$selectedLetter} 
	(<a href="?page=pornstars&amp;view={$view}&amp;l={$selectedLetter}">Text view</a> / 
	<a href="?page=pornstars&amp;view={$view}&amp;l={$selectedLetter}&amp;viewmode=img">Image view</a>)
</div>


{* List of pornstars of chosen letter *}
{if $viewmode eq 'images'}

	<div id="actorimages">
	{foreach from=$pornstars item=i key=k}
		<a href="?page=pornstar&amp;pornstar_id={$k}" title="{$i.name}">{$i.image}</a>
	{/foreach}
	</div>

{elseif $viewmode eq 'list'}

	<table cellspacing="0" cellpadding="0" border="0" width="100%" class="displist">
	<tr>
		<td class="header" width="70%">{$translate.pornstar.name}</td>
		<td class="header">{$translate.pornstar.web}</td>
		<td class="header" nowrap="nowrap">{$translate.pornstar.moviecount}</td>
	</tr>
	{foreach from=$pornstars item=pornstar key=id}
	<tr>
		<td><a href="./?page=pornstar&amp;pornstar_id={$id}">{$pornstar.name}</a></td>
		<td>{$pornstar.homepage}</td>
		<td>{$pornstar.count}</td>
	</tr>
	{/foreach}
	</table>

{/if}
