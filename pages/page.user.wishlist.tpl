<h1>{$translate.menu.wishlist}</h1>

{if is_array($wishList) && count($wishList) > 0}

<table cellspacing="0" cellpadding="0" border="0" width="100%" class="displist">
<tr>
	<td class="bold" width="92%">{$translate.movie.title}</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
</tr>
{foreach from=$wishList item=i}
	{if $i.mine > 0}
		{assign var='iown' value='<img src="images/mark_seen.gif" title="{$translate.wishlist.own}" alt="{$translate.wishlist.own}" border="0"/>'}
	{else} 
		{assign var='iown' value='&nbsp;'}
	{/if}
<tr>
	<td><a href="?page=cd&amp;vcd_id={$i.id}">{$i.title}</a></td>
	<td align="center">{$iown}</td>
	<td align="center"><a href="#" onclick="return false;"><img src="images/icon_del.gif" onclick="deleteFromWishlist({$i.id});return false;" border="0"/></a></td>
</tr>
{/foreach}
</table>

{else}

<p class="bold">{$translate.wishlist.empty}</p>

{/if}

