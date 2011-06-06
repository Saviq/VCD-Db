{if is_array($searchResults) && count($searchResults) > 0}

	<table cellspacing="0" cellpadding="0" border="0" width="100%" class="displist">
	<tr>
		<td class="header">{$translate.movie.title}</td>
		<td class="header">{$translate.movie.year}</td>
		<td class="header">{$translate.movie.media}</td>
	</tr>
	{foreach from=$searchResults item=i}
	<tr>
		<td><a href="?page=cd&amp;vcd_id={$i.id}">{$i.title}</a></td>
		<td>{$i.year}</td>
		<td>{$i.mediatypes}</td>
	</tr>
	{/foreach}
	</table>

{else}

	<h1>{$translate.search.search}</h1>
	<p>{$translate.search.noresult}</p>

{/if}


	
	
	
	



