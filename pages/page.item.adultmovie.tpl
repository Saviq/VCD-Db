<table width="100%" border="0" cellspacing="0" cellpadding="0" class="displist">
<tr>
	<td width="75%" class="header">{$translate.movie.movie}</td>
	<td width="25%" class="header">{$translate.movie.info}</td>
</tr>
<tr>
	<td valign="top">
	<!-- Info table -->
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" width="10%">
		{if isset($itemThumbnail)}
			{$itemThumbnail}
		{else}
			<div class="poster">No poster available</div>
		{/if}</td>
		<td valign="top">
			<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
				<td colspan="2" id="mTitle"><strong>{$itemTitle|escape}</strong></td>
			</tr>
			<tr>
				<td width="30%">{$translate.movie.category}:</td>
				<td>{if isset($itemCategoryName)}
				<a href="?page=category&amp;category_id={$itemCategoryId}">{$itemCategoryName}</a>
				{/if}</td>
			</tr>
			<tr>
				<td nowrap="nowrap">{$translate.movie.year}:</td>
				<td>{$itemYear}</td>
			</tr>
			<tr>
				<td>{$translate.movie.copies}:</td>
				<td>{$itemCopyCount}</td>
			</tr>
			<tr>
				<td>{$translate.movie.screenshots}</td>
				<td>
				{if $itemScreenshots}
					<a href="#" onclick="ShowScreenshots({$itemId});return false">{$translate.movie.show}</a>
				{else}
					{$translate.movie.noscreens}
				{/if}
				</td>
			</tr>
			{if $isAuthenticated}
			<tr>
				{if $isOnWishList}
					<td>&nbsp;</td>
					<td><a href="?page=wishlist">({$translate.wishlist.onlist})</a></td>
				{else}
					<td>&nbsp;</td>
					<td><a href="#" onclick="addtowishlist({$itemId});return false;">{$translate.wishlist.add}</a></td>
				{/if}
			</tr>
			{/if}
			{if $isOwner || $isAdmin}
			<tr>
				<td>&nbsp;</td>
				<td><a href="#" onclick="loadManager({$itemId});return false">{$translate.movie.change}</a></td>
			</tr>
			{/if}
			{if $isAuthenticated}
			<tr>
				<td>&nbsp;</td>
				<td>
				{if $itemSeen}
					<a href="#"><img src="images/mark_seen.gif" alt="{$translate.seen.notseenitclick}" border="0" style="padding-right:5px" onclick="markSeen({$itemId}, 0);return false;"/></a>{$translate.seen.seenit}
				{else}
					<a href="#"><img src="images/mark_unseen.gif" alt="{$translate.seen.seenitclick}" border="0" style="padding-right:5px" onclick="markSeen({$itemId}, 1);return false;"/></a>{$translate.seen.notseenit}
				{/if}
				</td>
			</tr>
			{/if}
			<tr>
				<td colspan="2">{$itemSourceSiteLogo}</td>
			</tr>
			</table>


		</td>
	</tr>
	</table>
	{if $smarty.get.screens}
	<h2>Screenshots</h2>
	<iframe id="screenshots" width="100%" height="460" src="screens.php?s_id={$itemId}" frameborder="0" scrolling="no"></iframe>
	{/if}
		


	<h2>{$translate.movie.actors}</h2>
	<div id="actorimages" style="padding-left:10px;">
		{foreach from=$itemPornstars item=i key=id}
			<a href="?page=pornstar&amp;pornstar_id={$i.id}">{$i.img}</a>
		{/foreach}
	</div>
	<br/>

	<div id="copies">
	<h2>{$translate.movie.available}:</h2>
		{if is_array($itemLayers) && count($itemLayers)>0}
	
		{** These are the onmouseover layers **}
		{foreach from=$itemLayers item=i}
		
			<div id="{$i.layer}" class="dvdetails">
			<table width="280" cellpadding="1" cellspacing="1" border="0" class="dvdspecs">
			<tr>
				<td nowrap="nowrap" width="15%">{$translate.dvd.region}:</td>
				<td>{$i.region}</td>
			</tr>
			<tr>
				<td nowrap="nowrap">{$translate.dvd.format}:</td>
				<td>{$i.format}</td>
			</tr>
			<tr>
				<td nowrap="nowrap">{$translate.dvd.aspect}:</td>
				<td>{$i.aspect}</td>
			</tr>
			<tr>
				<td nowrap="nowrap" valign="top">{$translate.dvd.audio}:</td>
				<td valign="top">{$i.audio}</td>
			</tr>
			<tr>
				<td nowrap="nowrap" valign="top">{$translate.dvd.subtitles}:</td>
				<td valign="top">{$i.subs}</td>
			</tr>
			</table>
			</div>
		
		
		{/foreach}
	{/if}
	
	{if is_array($itemCopies) && count($itemCopies)>0}
		{** This is the itemcopy table **}
		
		<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td>{$translate.movie.media}</td>
			<td width="1%">&nbsp;</td>
			<td width="1%">&nbsp;</td>
			<td>{$translate.movie.num}</td>
			<td>{$translate.movie.date}</td>
			<td>{$translate.movie.owner}</td>
		</tr>
		{foreach from=$itemCopies item=i}
		<tr>
			<td>{$i.mediatype}</td>
			<td align="center">{$i.dvdspecs}</td>
			<td align="center">{$i.nfo}</td>
			<td>{$i.cdcount}</td>
			<td>{$i.date|date_format:"%d/%m/%Y"}</td>
			<td>{$i.owner}</td>
		</tr>
		{/foreach}
		</table>
	{/if}
	</div>


	{if is_array($itemMetadata) && count($itemMetadata)>0}
		<div id="metadata">
		<h2>{$translate.metadata.my}</h2>
		<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td width="25%">{$translate.movie.media}</td>
			<td>{$translate.metadata.type}</td>
			<td>{$translate.metadata.value}</td>
		</tr>
		{foreach from=$itemMetadata item=i key=k}		
		<tr>
			<td>{$i.medianame}</td>
			<td title="{$i.desc}">{$i.name}</td>
			<td>{$i.text}</td>
		</tr>
		{/foreach}
		</table>
		</div>
	{/if}
	
	
	
	</td>
	<td valign="top" style="background-color:white">
	<h2>Studio</h2>
	<ul>
		{if $itemStudioId}
			<li><a href="?page=adultcategory&amp;studio_id={$itemStudioId}">{$itemStudioName}</a></li>
		{else}
			<li>No studio information.</li>
		{/if}
	</ul>
	
	<br/>

	<h2>{$translate.dvdempire.subcat}</h2>
	<ul>
		{foreach from=$itemAdultCategories item=name key=id}
			<li><a href="?page=adultcategory&amp;category_id={$id}">{$name}</a></li>
		{/foreach}
	</ul>
	<br/>

	<h2>{$translate.movie.covers}</h2>
	{if is_array($itemCovers) && count($itemCovers)>0}
		<ul>
		{foreach from=$itemCovers item=i}
		<li><a href="{$i.link|escape}" title="{$i.title}: {$i.covertype}" rel="lytebox[{$itemId}]">{$i.covertype}</a></li>
		{/foreach}	
		</ul>
	{else}
		<ul><li>{$translate.movie.nocovers}</li></ul>
	{/if}


	<br/>
	
	<h2>{$translate.movie.actors}</h2>
	<div id="actorlist">
	<ul>
		{foreach from=$itemPornstars item=i}
			<li><a href="?page=pornstar&amp;pornstar_id={$i.id}">{$i.name}</a></li>
		{/foreach}
	</ul>
	</div>
	<br/>
	
	<div id="similar">
	{if is_array($itemSimilar) && count($itemSimilar)}
		<h2>{$translate.movie.similar}</h2>
		<form name="sim" action="get">
			{html_options name=similar options=$itemSimilar|truncate:26:".." onchange="goSimilar(this.form)"}
		</form>
		<br/>
	{/if}
	</div>
	
	
	<div id="comments">
	<h2>{$translate.comments.comments} (<a href="#" onclick="javascript:show('newcomment');return false">{$translate.misc.new}</a>)</h2>
	</div>

	<div id="newcomment" style="left:665px;visibility:hidden;display:none;position:absolute">
	{if !$isAuthenticated}	
		<span class="bold">{$translate.comments.error}</span>
	{else}
		<form name="addcomment" method="post" action="{$smarty.server.SCRIPT_NAME}?page=cd&amp;action=addcomment&amp;vcd_id={$itemId}">
		<input type="hidden" name="vcd_id" value="{$itemId}"/>
		<table cellpadding="0" cellspacing="0" border="0" class="plain">
		<tr>
			<td colspan="2"><textarea name="comment" rows="4" cols="20"></textarea></td>
		</tr>
		<tr>
			<td>{$translate.movie.private}:</td>
			<td><input type="checkbox" name="private" class="nof" value="private"/></td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" value="{$translate.comments.post}"/></td>
		</tr>
		</table>
		</form>
	{/if}
		
	<br/>
	</div>

	{* Display existing comments *}
	{if is_array($itemComments) && count($itemComments)>0}
		<ul>
		{foreach from=$itemComments item=i}
		
		<li>{$i.owner} ({$i.date}) 
		{if $i.private}
			(<i>Private comment</i>)
		{/if}
		{if $i.isOwner}
			<a href="#" onclick="deleteComment({$itemId},{$i.id});return false">
			<img src="images/icon_del.gif" alt="Delete comment" style="vertical-align: middle;display: inline-block;" border="0"/></a>
		{/if}
	   <br/><i style="padding-left:3px;display:block">{$i.comment|nl2br}</i></li>
		{/foreach}
		</ul>
	{else}
		<ul><li>{$translate.comments.none}</li></ul>
	{/if}
	
	

	</td>
</tr>
</table>
<script language="JavaScript" type="text/javascript" src="includes/js/wz_tooltip.js"></script>