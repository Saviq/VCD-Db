<h2>{$translate.pornstar.pornstars}</h2>

{if is_array($alphabet) && count($alphabet)>0} 

<div align="center">
{foreach from=$alphabet item=letter}<a href="?page=pornstars&amp;view={$view}&amp;l={$letter}&amp;viewmode={$mode}">{$letter}</a><img src="images/dot.gif" border="0" hspace="5" vspace="2"/>{/foreach}
</div>

{else}
	No Pornstars found in database.
{/if}


<hr/>
{if $smarty.get.l neq ''}
<div align="center">
	<span class="bold">{$pornstarCount} {$translate.pornstar.pornstars}</span> {$translate.pornstar.begin} <strong>{$selectedLetter}</strong>
	(<a href="?page=pornstars&amp;view={$view}&amp;l={$selectedLetter}&amp;viewmode=text">{$translate.movie.textview}</a> / 
	<a href="?page=pornstars&amp;view={$view}&amp;l={$selectedLetter}&amp;viewmode=img">{$translate.movie.imageview}</a>)
</div>
{/if}

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
		<td>{$pornstar.homepage|default:'&nbsp;'}</td>
		<td>{$pornstar.count}</td>
	</tr>
	{/foreach}
	</table>

{/if}
