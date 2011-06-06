<div class="topic">{$translate.menu.categories}</div> 
{if is_array($categoryList) && count($categoryList) > 0 }
{foreach from=$categoryList item=i}
<span class="{$i.css}"><a href="?page=category&amp;category_id={$i.id}" class="navx">{$i.name}</a></span>
{if $i.id == 0}<hr/>{/if}
{/foreach}
{else}
<ul><li>{$translate.misc.nocats}</li></ul>
{/if}
