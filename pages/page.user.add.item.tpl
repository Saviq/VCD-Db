<h1>{$sourceSiteName} - {$smarty.post.searchTitle} ...</h1>

{** Display the search results ... if any **}
{if is_array($fetchList) && count($fetchList)>0} 

	<ul>
	{foreach from=$fetchList item=i}
	<li><a href="{$i.fetchlink}">{$i.title}</a> 
	{if $i.year} ({$i.year}) {/if}
	<a href="{$i.sourcelink}" target="_blank">[info]</a>
	</li>
	{/foreach}
	</ul>

	
{** We have an item **}
{elseif $isFetched}
	
	{if $itemAdult}
		{include file='page.user.add.confirm.adult.tpl'}
	{else} 
		{include file='page.user.add.confirm.tpl'}
	{/if}


{** Search returned no results **}
{else}

No results

{/if}
