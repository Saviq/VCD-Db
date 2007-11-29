<table width="100%" border="0" cellspacing="0" cellpadding="0" class="displist">
<tr>
	<td width="65%" class="header">{$translate.movie.movie}</td>
	<td width="35%" class="header">{$translate.movie.actors}</td>
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
		{/if}
		</td>
		<td valign="top">
			<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
				<td colspan="2"><strong>{$itemTitle}</strong></td>
			</tr>
			<tr>
				<td width="30%">{$translate.movie.category}:</td>
				<td>{if isset($itemCategoryName)}
				<a href="?page=category&amp;category_id={$itemCategoryId}">{$itemCategoryName}</a>
				{/if}
				</td>
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
				<td>{$itemSourceSiteLogo}</td>
				<td>{$itemRating}</td>
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
			{if $isAuthenticated && isset($itemSeen)}
			<tr>
				<td>&nbsp;</td>
				<td>
				{if $itemSeen}
					<a href="#"><img src="images/icons/cd_delete.png" alt="{$translate.seen.notseenitclick}" border="0" style="padding-right:5px" onclick="markSeen({$itemId},0);return false;"/></a>{$translate.seen.seenit}
				{else}
					<a href="#"><img src="images/icons/cd_add.png" alt="{$translate.seen.seenitclick}" border="0" style="padding-right:5px" onclick="markSeen({$itemId},1);return false;"/></a>{$translate.seen.notseenit}
				{/if}
				</td>
			</tr>
			{/if}
			</table>


		</td>
	</tr>
	</table>


	<div id="imdbinfo">
	<h2>{$translate.movie.from} IMDB:</h2>
	{if $itemSource}
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="normal">
	<tr>
		<td>{$translate.movie.title}</td>
		<td>{$sourceTitle}</td>
	</tr>
	{if $sourceAltTitle}
	<tr>
		<td valign="top">{$translate.movie.alttitle}</td>
		<td>{$sourceAltTitle}</td>
	</tr>
	{/if}
	<tr>
		<td>IMDB {$translate.movie.grade}:</td>
		<td>{$sourceGrade}</td>
	</tr>
	<tr>
		<td>{$translate.movie.director}:</td>
		<td>{$sourceDirector}</td>
	</tr>
	<tr>
		<td nowrap="nowrap">{$translate.movie.country}:</td>
		<td>{$sourceCountries}</td>
	</tr>
	<tr>
		<td valign="top" nowrap="nowrap">IMDB {$translate.movie.category}:</td>
		<td>{$sourceCategoryList}</td>
	</tr>
	<tr>
		<td>{$translate.movie.runtime}:</td>
		<td>{$sourceRuntime} {$translate.movie.minutes}</td>
	</tr>
	</table>
	{else}
		<ul><li>{$translate.imdb.not}</li></ul>
	{/if}
	</div>


	<div id="imdbplot">
	<h2>{$translate.movie.plot}:</h2>
	{if $itemSource}
	<p class="plottext">
	{if strlen($sourcePlot) > 250} 
	<span id="plotbegin">{$sourcePlot|nl2br|truncate:250:" ..."}<br/><a href="#" onclick="hide('plotbegin');show('plotcomplete');return false;">{$translate.misc.showmore} &gt;&gt;</a></span>
	<span id="plotcomplete" style="visibility:hidden;display:none">{$sourcePlot|nl2br}<br/><a href="#" onclick="hide('plotcomplete');show('plotbegin');return false;">&lt;&lt; {$translate.misc.showless}</a></span>
	{else}
	{$sourcePlot|nl2br}
	{/if}
	</p>
	{else}
		<ul><li>{$translate.movie.noplot}</li></ul>
	{/if}
	</div>

	
	<div id="covers">
	<h2>{$translate.movie.covers}</h2>
	<ul>
	{if is_array($itemCovers) && count($itemCovers)>0}
		{foreach from=$itemCovers item=i}
		<li><a href="{$i.link|escape}" title="{$i.title}: {$i.covertype}" rel="lytebox[{$itemId}]">{$i.covertype}</a> <i>({$i.size})</i></li>
		{/foreach}	
	{else}
		<li>{$translate.movie.nocovers}</li>
	{/if}
	</ul>
	</div>

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
			{if $i.subs neq ''}
			<tr>
				<td nowrap="nowrap" valign="top">{$translate.dvd.subtitles}:</td>
				<td valign="top">{$i.subs}</td>
			</tr>
			{/if}
			{if $i.lang neq ''}
			<tr>
				<td nowrap="nowrap" valign="top">{$translate.dvd.languages}:</td>
				<td valign="top">{$i.lang}</td>
			</tr>
			{/if}
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
			<td>{$i.dvdspecs}</td>
			<td>{$i.nfo}</td>
			<td>{$i.cdcount}</td>
			<td>{$i.date|date_format:$config.date}</td>
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


	<div id="comments">
	<h2>{$translate.comments.comments} (<a href="#" onclick="javascript:show('newcomment');return false">{$translate.comments.add}</a>)</h2>
	</div>

	<div id="newcomment" style="padding-left:15px;visibility:hidden;display:none">
	{if !$isAuthenticated}	
		<span class="bold">{$translate.comments.error}</span>
	{else}

		<span class="bold">{$translate.comments.type}:</span>
		<form name="comment" method="post" action="{$smarty.server.SCRIPT_NAME}?page=cd&amp;action=addcomment&amp;vcd_id={$itemId}">
		<table cellpadding="0" cellspacing="0" border="0" class="plain">
		<tr>
			<td valign="top">{$translate.comments.your}:</td>
			<td><textarea name="comment" rows="4" cols="30"></textarea></td>
		</tr>
		<tr>
			<td>{$translate.movie.private}:</td>
			<td><input type="checkbox" name="private" class="nof" value="private"/>
			&nbsp;&nbsp;<input type="submit" value="{$translate.comments.post}"/>
			</td>
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
		
		<li>{$i.owner} ({$i.date|date_format:$config.date})
		{if $i.private}
			(<i>Private comment</i>)
		{/if}
		{if $i.isOwner}
			<a href="#" onclick="deleteComment({$itemId},{$i.id});return false">
			<img src="images/icon_del.gif" alt="{$translate.js.delete}" border="0" style="vertical-align: middle;display: inline-block;"/></a>
		{/if}
	   <br/><i style="padding-left:3px;display:block">{$i.comment|nl2br}</i></li>
		{/foreach}
		</ul>
	{else}
		<ul><li>{$translate.comments.none}</li></ul>
	{/if}
	


	</td>
	<td valign="top">
	<div id="cast">
	{if $itemSource}
		{$sourceActors}
	{else}
		{$translate.movie.noactors}
	{/if}
	</div>

	{if $showImdbLinks}
	<div id="imdblinks">
		<h2>{$translate.imdb.links}</h2>
		<ul>
			<li><a href="http://www.imdb.com/Title?{$itemExternalId}" target="_blank">{$translate.imdb.details}</a></li>
			<li><a href="http://www.imdb.com/Plot?{$itemExternalId}" target="_blank">{$translate.imdb.plot}</a></li>
			<li><a href="http://www.imdb.com/Gallery?{$itemExternalId}" target="_blank">{$translate.imdb.gallery}</a></li>
			<li><a href="http://www.imdb.com/Trailers?{$itemExternalId}" target="_blank">{$translate.imdb.trailers}</a></li>
		</ul>
	</div>
	{/if}

	<div id="similar">
	{if is_array($itemSimilar) && count($itemSimilar)}
	{assign var='base' value=$smarty.server.SCRIPT_NAME}
		<h2>{$translate.movie.similar}</h2>
		<form name="sim" action="get">
			{html_options name=similar options=$itemSimilar|truncate:30:".." onchange="location.href='$base?page=cd&amp;vcd_id='+this.value"}
		</form>
	{/if}
	</div>
	</td>
</tr>
</table>
<script language="JavaScript" type="text/javascript" src="includes/js/wz_tooltip.js"></script>