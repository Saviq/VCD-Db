
{if is_array($fetchList) && count($fetchList)>0} 

	<ul>
	{foreach from=$fetchList item=i}
	<li><a href="{$i.fetchlink}">{$i.title}</a> 
	{if $i.year} ({$i.year}) {/if}
	<a href="{$i.sourcelink}" target="_blank">[info]</a>
	</li>
	{/foreach}
	</ul>

{else}

No results

{/if}
