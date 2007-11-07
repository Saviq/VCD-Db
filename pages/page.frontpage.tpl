


{if $isAuthenticated && is_array($frontpageFeeds) && count($frontpageFeeds)>0}

<table border="0">
{foreach from=$frontpageFeeds item=i name=rss key=id}
{if $smarty.foreach.rss.first or ($smarty.foreach.rss.index % 2 == 0)}
<tr>
{/if}

<td valign="top" width="50%">
{**  Display the rss **}

 <h1><em><a href="{$i.link}" target="_blank" title="{$i.title}">{$i.title}</a></em></h1>
 {$i.description}

 <ul id="rss{$id}">
 {if is_array($i.items) && count($i.items)>0}
 	
	<script>
	var h{$id} = Array();
	{foreach from=$i.items item=j key=k}h{$id}[{$k}] = ['{$j.hover}'];{/foreach}
	</script>
 
 	{foreach from=$i.items item=j name=item}
		<li><a href="{$j.link}" target="_blank" onmouseover="TextTip(h{$id}[{$smarty.foreach.item.index}])">{$j.title}</a></li>
	{/foreach}
{elseif $i.items eq 'notInCache'}
	<script>invokeRss({$id});</script>
{/if}
</ul>
</td>


{if $smarty.foreach.rss.last or ($smarty.foreach.rss.index % 2 != 0)}
</tr>
{/if}
{/foreach}
</table>


{/if}


<script language="JavaScript" type="text/javascript" src="includes/js/wz_tooltip.js"></script>