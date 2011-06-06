{if $rightbarHidden}
<div id="r-col" style="display:none">
{else}
<div id="r-col">
{/if}
{if is_array($toptenLists) && count($toptenLists)>0}
<ul id="toplist">
{foreach from=$toptenLists item=i name=list}
<li class="tlistheader">10 {$i.name}</li>
{foreach from=$i.items item=title key=id name=items}
<li class="tlist"><a href="?page=cd&amp;vcd_id={$id}" title="{$title}">{$title|truncate:27:" .."}</a></li>
{/foreach}
{/foreach}
</ul>
{/if}

{if !$rightStatistics}
<table cellspacing="0" cellpadding="1" border="0" class="list" width="100%">
{if is_array($statsTopCategories) && count($statsTopCategories)>0}
<tr>
	<td class="header" colspan="2">{$translate.statistics.top_cats}</td>
</tr>
{foreach from=$statsTopCategories item=i}
<tr>
	<td><a href="?page=category&amp;category_id={$i.id}">{$i.name}</a></td>
	<td align="right">{$i.count}</td>
</tr>
{/foreach}
{/if}
{if is_array($statsTopCurrentCategories) && count($statsTopCurrentCategories)>0}
<tr>
	<td class="header" colspan="2">{$translate.statistics.top_act}</td>
</tr>
{foreach from=$statsTopCurrentCategories item=i}
<tr>
	<td><a href="?page=category&amp;category_id={$i.id}">{$i.name}</a></td>
	<td align="right">{$i.count}</td>
</tr>
{/foreach}
{/if}
</table>
{/if}

</div>