<div id="r-col">

{foreach from=$toptenLists item=i name=list}
{$i.name}<br/>
{foreach from=$i.items item=title key=id name=items}
<a href="?page=cd&amp;vcd_id={$id}" title="{$title}">{$title|truncate:27:" .."}</a><br/>
{/foreach}
{/foreach}

</div>