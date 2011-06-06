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
		<table cellspacing="0" cellpadding="0" border="0" width="100%" class="displist">
		<tr>
			<td width="15%" class="header">{$translate.misc.order}:</td>
			<td width="15%" class="header"><a href="?page=category&amp;category_id={$categoryId}&amp;batch={$categoryPage}&amp;sort={if $movieCategorySort neq 'id_a'}id_a{else}id_d{/if}">ID</a>{if $movieCategorySort eq 'id_d'}&nbsp;<img style="vertical-align: middle;" src="images/icons/bullet_arrow_down.png">{elseif $movieCategorySort eq 'id_a'}&nbsp;<img style="vertical-align: middle;" src="images/icons/bullet_arrow_up.png-">{/if}</td>
			<td width="15%" class="header"><a href="?page=category&amp;category_id={$categoryId}&amp;batch={$categoryPage}&amp;sort={if $movieCategorySort neq 'title_a'}title_a{else}title_d{/if}">{$translate.movie.title}</a>{if $movieCategorySort eq 'title_d'}&nbsp;<img style="vertical-align: middle;" src="images/icons/bullet_arrow_down.png">{elseif $movieCategorySort eq 'title_a'}&nbsp;<img style="vertical-align: middle;" src="images/icons/bullet_arrow_up.png">{/if}</td>
			<td width="15%" class="header"><a href="?page=category&amp;category_id={$categoryId}&amp;batch={$categoryPage}&amp;sort={if $movieCategorySort neq 'year_a'}year_a{else}year_d{/if}">{$translate.movie.year}</a>{if $movieCategorySort eq 'year_d'}&nbsp;<img style="vertical-align: middle;" src="images/icons/bullet_arrow_down.png">{elseif $movieCategorySort eq 'year_a'}&nbsp;<img style="vertical-align: middle;" src="images/icons/bullet_arrow_up.png">{/if}</td>
			<td class="header">&nbsp;</td>
		</tr>
		</table>	
		<div id="actorimages">
		{foreach from=$movieCategoryList item=i}{$i}{/foreach}
		</div>

	{else}
	
		<table cellspacing="0" cellpadding="0" border="0" width="100%" class="displist">
		<tr>
			<td class="header"><a href="?page=category&amp;category_id={$categoryId}&amp;batch={$categoryPage}&amp;sort={if $movieCategorySort neq 'id_a'}id_a{else}id_d{/if}">ID</a>{if $movieCategorySort eq 'id_d'}&nbsp;<img style="vertical-align: middle;" src="images/icons/bullet_arrow_down.png">{elseif $movieCategorySort eq 'id_a'}&nbsp;<img style="vertical-align: middle;" src="images/icons/bullet_arrow_up.png">{/if}</td>
			<td class="header"><a href="?page=category&amp;category_id={$categoryId}&amp;batch={$categoryPage}&amp;sort={if $movieCategorySort neq 'title_a'}title_a{else}title_d{/if}">{$translate.movie.title}</a>{if $movieCategorySort eq 'title_d'}&nbsp;<img style="vertical-align: middle;" src="images/icons/bullet_arrow_down.png">{elseif $movieCategorySort eq 'title_a'}&nbsp;<img style="vertical-align: middle;" src="images/icons/bullet_arrow_up.png">{/if}</td>
			<td class="header"><a href="?page=category&amp;category_id={$categoryId}&amp;batch={$categoryPage}&amp;sort={if $movieCategorySort neq 'year_a'}year_a{else}year_d{/if}">{$translate.movie.year}</a>{if $movieCategorySort eq 'year_d'}&nbsp;<img style="vertical-align: middle;" src="images/icons/bullet_arrow_down.png">{elseif $movieCategorySort eq 'year_a'}&nbsp;<img style="vertical-align: middle;" src="images/icons/bullet_arrow_up.png">{/if}</td>
			<td class="header">{$translate.movie.mediatype}</td>
		</tr>
		{foreach from=$movieCategoryList item=i}
		<tr>
           <td nowrap="nowrap" width="6%">{$i.id}</td>
		   <td width="64%"><a href="?page=cd&amp;vcd_id={$i.id}">{$i.title|escape}</a></td>
	       <td nowrap="nowrap" width="15%">{$i.year}</td>
           <td nowrap="nowrap">{$i.mediatypes}</td>
	   </tr>
		{/foreach}
		</table>
	{/if}


{else}

	No movies found in category

{/if}

