<h1>{$translate.movie.bycat}</h1>

{if is_array($movieCategoryList) && count($movieCategoryList) > 0} 

	&nbsp;<span class="bold">{$translate.movie.currcat}</span>&nbsp; ({$movieCategoryCount} {$translate.misc.movies}) 
	
	{if $imageMode}
		(<a href="?page=category&amp;category_id={$categoryId}&amp;batch={$categoryPage}&amp;viewmode=text">{$translate.movie.textview}</a> / {$translate.movie.imageview})
	{else}
		{$translate.movie.textview} / (<a href="?page=category&amp;category_id={$categoryId}&amp;batch={$categoryPage}&amp;viewmode=img">{$translate.movie.imageview}</a>)
	{/if}
	{if $isAuthenticated}
		{if $smarty.session.mine}
			{assign var='mineChecked' value='checked="checked"'}
		{/if}
	
	| <input type="checkbox" class="nof" onclick="showonlymine({$categoryId})" {$mineChecked}/> {$translate.movie.mineonly}
	{/if}

	{$categoryPager}
	
	{if $imageMode}
		<hr/>
		<div id="actorimages">
		{foreach from=$movieCategoryList item=i}{$i}{/foreach}
		</div>

	{else}
	
		<table cellspacing="0" cellpadding="0" border="0" width="100%" class="displist">
		<tr>
			<td class="header">{$translate.movie.title}</td>
			<td nowrap="nowrap" class="header">{$translate.movie.year}</td>
			<td class="header" nowrap="nowrap">{$translate.movie.mediatype}</td>
		</tr>
		{foreach from=$movieCategoryList item=i}
		<tr>
		   <td width="70%"><a href="?page=cd&amp;vcd_id={$i.id}">{$i.title|escape}</a></td>
	       <td nowrap="nowrap">{$i.year}</td>
           <td nowrap="nowrap">{$i.mediatypes}</td>
	   </tr>
		{/foreach}
		</table>
	{/if}


{else}

	No movies found in category

{/if}

