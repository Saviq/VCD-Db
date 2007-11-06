<h1>{$translate.menu.wishlistpublic}</h1>

{foreach from=$itemWishLists item=i name=lists}
<br/>
<div class="bold" style="padding-left:15px">{$i.username} ({$i.fullname})</div>
<ol style="margin-top:5px;margin-left:10px">
{foreach from=$i.items item=j key=id name=movies}
<li class="{$j.style}" title="{$j.text}"><a href="?page=cd&amp;vcd_id={$id}">{$j.title}</a></li>
{/foreach}
</ol>
{/foreach}