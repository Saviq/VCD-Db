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
</div>