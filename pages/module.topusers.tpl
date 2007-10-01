<div class="topic">{$translate.menu.topusers}</div>
{if is_array($topuserList) && count($topuserList)>0} 
	<ul>
	{foreach from=$topuserList item=i}
		<li>{$i.name} ({$i.count})</li>
	{/foreach}
	</ul>
{else}
	<ul><li>{$translate.misc.nousers}</li></ul>
{/if}
