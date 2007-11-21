{** Display the search results ... if any **}
{if is_array($fetchList) && count($fetchList)>0} 

	<h1>{$sourceSiteName} - {$smarty.post.searchTitle} ...</h1>
	
	<ul>
	{foreach from=$fetchList item=i}
	<li><a href="{$i.fetchlink}">{$i.title}</a> 
	{if $i.year} ({$i.year}) {/if}
	<a href="{$i.sourcelink}" target="_blank">[info]</a>
	</li>
	{/foreach}
	</ul>


{** Show the files in the xml import file **}
{elseif $smarty.get.source eq 'xml'}
	
	{include file='page.user.add.xml.tpl'}

{** We have an item **}
{elseif $isFetched}
	
	{if $itemAdult}
		{include file='page.user.add.confirm.adult.tpl'}
	{else} 
		{include file='page.user.add.confirm.tpl'}
	{/if}


{** Search returned no results **}
{else}
<h1>{$sourceSiteName} - {$smarty.post.searchTitle} ...</h1>

<p>
	{$translate.search.noresult}
</p>

{/if}
