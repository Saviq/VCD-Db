


{if $isAuthenticated && is_array($frontpageFeeds) && count($frontpageFeeds)>0}

<table border="0">
{foreach from=$frontpageFeeds item=i name=rss key=id}
{if $smarty.foreach.rss.first or ($smarty.foreach.rss.index % 2 == 0)}
<tr>
{/if}

<td valign="top" width="50%">
{**  Display the rss **}

 <h1><em><a href="{$i.link|escape}" target="_blank" title="{$i.title}">{$i.title}</a></em></h1>
 {$i.description}

 <ul id="rss{$id}">
 {if is_array($i.items) && count($i.items)>0}
 	
	<script type="text/javascript">
	var h{$id} = Array();
	{foreach from=$i.items item=j key=k}h{$id}[{$k}] = ['{$j.hover}'];{/foreach}
	</script>
 
 	{foreach from=$i.items item=j name=item}
		<li><a href="{$j.link}" target="_blank" onmouseover="TextTip(h{$id}[{$smarty.foreach.item.index}])">{$j.title}</a></li>
	{/foreach}
{elseif $i.items eq 'notInCache'}
	<script type="text/javascript">invokeRss({$id});</script>
{/if}
</ul>
</td>


{if $smarty.foreach.rss.last or ($smarty.foreach.rss.index % 2 != 0)}
</tr>
{/if}
{/foreach}
</table>

{else}
{** Show statistics for un-authenticated users **}

<div align="center">
<img src="images/logotest.gif" width="187" align="middle" height="118" alt="" border="0"/>

<table cellspacing="1" cellpadding="1" border="0" class="statsTable" style="width:230px">
<tr>
	<td class="stata" colspan="2">{$translate.statistics.top_movies}</td>
</tr>
<tr>
	<td align="left">{$translate.statistics.total}</td>
	<td align="right">{$statsMovieCount}</td>
</tr>
<tr>
	<td align="left">{$translate.statistics.today}</td>
	<td align="right">{$statsMovieCountToday}</td>
</tr>
<tr>
	<td align="left">{$translate.statistics.week}</td>
	<td align="right">{$statsMovieCountWeek}</td>
</tr>
<tr>
	<td align="left">{$translate.statistics.month}</td>
	<td align="right">{$statsMovieCountMonth}</td>
</tr>
{if is_array($statsTopCategories) && count($statsTopCategories)>0}
<tr>
	<td class="stata" colspan="2">{$translate.statistics.top_cats}</td>
</tr>
{foreach from=$statsTopCategories item=i}
<tr>
	<td align="left"><a href="?page=category&amp;category_id={$i.id}">{$i.name}</a></td>
	<td align="right">{$i.count}</td>
</tr>
{/foreach}
{/if}
{if is_array($statsTopCurrentCategories) && count($statsTopCurrentCategories)>0}
<tr>
	<td class="stata" colspan="2">{$translate.statistics.top_act}</td>
</tr>
{foreach from=$statsTopCurrentCategories item=i}
<tr>
	<td align="left"><a href="?page=category&amp;category_id={$i.id}">{$i.name}</a></td>
	<td align="right">{$i.count}</td>
</tr>
{/foreach}
{/if}
<tr>
	<td colspan="2" class="stata">{$translate.statistics.top_covers}</td>
</tr>
<tr>
	<td align="left">{$translate.statistics.total}</td>
	<td align="right">{$statsCoverCount}</td>
</tr>
<tr>
	<td align="left">{$translate.statistics.week}</td>
	<td align="right">{$statsCoverCountWeek}</td>
</tr>
<tr>
	<td align="left">{$translate.statistics.month}</td>
	<td align="right">{$statsCoverCountMonth}</td>
</tr>
</table>
</div>

<br/><br/>

{/if}


<script language="javascript" type="text/javascript" src="includes/js/wz_tooltip.js"></script>