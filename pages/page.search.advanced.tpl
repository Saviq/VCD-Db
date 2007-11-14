<h1>{$translate.search.extended}</h1>

<form name="advanced_search" method="post" action="{$smarty.server.SCRIPT_NAME}?page=detailed_search&amp;action=search">
<table class="displist" cellpadding="1" cellspacing="0" width="100%">
<tr>
	<td width="40%">{$translate.movie.title} {$translate.misc.contains}:</td>
	<td><input type="text" name="title" value="{$searchTitle}" /></td>
</tr>
<tr>
	<td>{$translate.movie.category}:</td>
	<td>{html_options name=category options=$searchCategoryList selected=$selectedCategory}</td>
</tr>
<tr>
	<td>{$translate.movie.year}:</td>
	<td>{html_options name=year options=$searchYearList selected=$selectedYear}</td>
</tr>
<tr>
	<td>{$translate.movie.media}:</td>
	<td>{html_options name=mediatype options=$searchMediatypeList selected=$selectedMediatype}</td>
</tr>
<tr>
	<td>{$translate.movie.owner}:</td>
	<td>{html_options name=owner options=$searchOwnerList selected=$selectedOwner}</td>
</tr>
<tr>
	<td>{$translate.misc.grade}:</td>
	<td>{html_options name=grade options=$searchGradeList selected=$selectedGrade}</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><input type="submit" value="{$translate.search.search}" onclick="return checkAdvanced(this.form)"/></td>
</tr>
</table>
</form>


{if is_array($searchResults) && count($searchResults)>0}


<table cellspacing="1" cellpadding="1" border="0" class="displist" width="100%"> 
<tr>
	<td class="header">{$translate.movie.title}</td>
	<td class="header">{$translate.movie.category}</td>
	<td class="header" nowrap="nowrap">{$translate.movie.year}</td>
	<td class="header">{$translate.movie.media}</td>
	<td class="header">{$translate.movie.grade}</td>
</tr>
{foreach from=$searchResults item=i}
<tr>
	<td><a href="?page=cd&amp;vcd_id={$i.id}">{$i.title}</a></td>
	<td>{$i.category}</td>
	<td>{$i.year}</td>
	<td>{$i.media_type}</td>
	<td>{$i.rating|default:'&nbsp;'}</td>
</tr>		
{/foreach}

</table>


{elseif $smarty.get.s}

	<p class="bold">{$translate.search.noresult}</p>

{/if}