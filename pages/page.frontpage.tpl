


{if $isAuthenticated && is_array($frontpageFeeds) && count($frontpageFeeds)>0}

<table border="1">
{foreach from=$frontpageFeeds item=i name=rss}
{if $smarty.foreach.rss.first or ($smarty.foreach.rss.index % 2 == 0)}
<tr>
{/if}

<td>{$i.name}</td>


{if $smarty.foreach.rss.last or ($smarty.foreach.rss.index % 2 != 0)}
</tr>
{/if}
{/foreach}
</table>


{/if}
