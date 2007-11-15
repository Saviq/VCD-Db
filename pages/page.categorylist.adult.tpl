{if $byCategory}
	{assign var='selType' value='category_id'}
	<h1>Adult films by category</h1>
{else}
	{assign var='selType' value='studio_id'}
	<h1>Adult films by studio</h1>
{/if}




{assign var='base' value=$smarty.server.SCRIPT_NAME}
&nbsp;<span class="bold">{$viewTitle}</span>&nbsp;
{html_options id=category name=category options=$currentList selected=$selectedListItem onchange="location.href='$base?page=adultcategory&amp;$selType='+this.value " }

{if $imageMode}
	(<a href="?page=adultcategory&amp;{$viewType}={$categoryId}&amp;batch={$categoryPage}&amp;viewmode=text">{$translate.movie.textview}</a> / {$translate.movie.imageview})
{else}
	{$translate.movie.textview} / (<a href="?page=adultcategory&amp;{$viewType}={$categoryId}&amp;batch={$categoryPage}&amp;viewmode=img">{$translate.movie.imageview}</a>)
{/if}
&nbsp; ({$movieCategoryCount} {$translate.misc.movies}) 


{$categoryPager}

{if $imageMode}	

<hr/>
<div id="actorimages">
	{foreach from=$movieList item=i}{$i}{/foreach}
</div>

{else}

<table cellspacing="0" cellpadding="0" border="0" width="100%" class="displist">
<tr>
	<td class="header">{$translate.movie.title}</td>
	<td class="header" nowrap="nowrap">{$translate.movie.year}</td>
	<td class="header">Screens</td>
</tr>
{foreach from=$movieList item=i}
<tr>
	<td width="80%" id="m{$i.id}"><a href="?page=cd&amp;vcd_id={$i.id}">{$i.title|escape}</a></td>
	<td nowrap="nowrap">{$i.year}</td>
	<td nowrap="nowrap" align="center">
	{if $i.screens}
	<a href="#" onclick="ShowScreenshots({$i.id});return false" title="{$translate.movie.show}"><img src="images/icons/pictures.png" alt="{$translate.movie.show}" border="0"/></a>
	{else}
	&nbsp;	
	{/if}
	</td>
</tr>
{/foreach}
</table>

{/if}