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
					<td><a href="?page=private&amp;o=wishlist">({$translate.wishlist.onlist})</a></td>
				{else}
					<td>&nbsp;</td>
					<td><a href="#" onclick="addtowishlist({$itemId})">{$translate.wishlist.add}</a></td>
				{/if}
			</tr>
			{/if}
			{if $isOwner || $isAdmin}
			<tr>
				<td>&nbsp;</td>
				<td><a href="#" onclick="loadManager({$itemId})">{$translate.movie.change}</a></td>
			</tr>
			{/if}
			{if $isAuthenticated || $isOwner}
			<tr>
				<td>&nbsp;</td>
				<td>
				{if $itemSeen}
					<a href="#"><img src="images/mark_seen.gif" alt="{$translate.seen.notseenitclick}" border="0" style="padding-right:5px" onclick="markSeen({$itemId}, 0)"/></a>{$translate.seen.seenit}
				{else}
					<a href="#"><img src="images/mark_unseen.gif" alt="{$translate.seen.seenitclick}" border="0" style="padding-right:5px" onclick="markSeen({$itemId}, 1)"/></a>{$translate.seen.notseenit}
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
		{$sourcePlot|nl2br}
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
	{if is_array($itemCopies) && count($itemCopies)>0}
		{foreach from=$itemCopies item=i}
		
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
				<td nowrap="nowrap">{$translate.dvd.aspect}>:</td>
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
	
	</div>


	{if is_array($itemMetadata) && count($itemMetadata)>0}
		<div id="metadata">
		<h2>{$translate.metadata.my}</h2>
		<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td width="20%">{$translate.movie.media}</td>
			<td>{$translate.metadata.type}</td>
			<td>{$translate.metadata.value}</td>
		</tr>
		{foreach from=$itemMetadata item=i}		
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
	<h2>{$translate.comments.comments} (<a href="javascript:show('newcomment')">{$translate.comments.add}</a>)</h2>
	</div>

	<div id="newcomment" style="padding-left:15px;visibility:hidden;display:none">
	{if !$isAuthenticated}	
		<span class="bold">{$translate.comments.error}</span>
	{else}

		<span class="bold">{$translate.comments.type}:</span>
		<form name="newcomment" method="post" action="exec_form.php?action=addcomment">
		<input type="hidden" name="vcd_id" value="{$itemId}"/>
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
		
		<li>{$i.owner} ({$i.date}) 
		{if $i.private}
			(<i>Private comment</i>)
		{/if}
		{if $i.isOwner}
			<a href="#" onclick="location.href='exec_query.php?action=delComment&amp;cid={$i.id}'">
			<img src="images/icon_del.gif" alt="Delete comment" align="absmiddle" border="0"/></a>
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

	<div id="imdblinks">
	{$sourceLinks}
	</div>

	<div id="similar">
	{if is_array($itemSimilar) && count($itemSimilar)}
		<h2>{$translate.movie.similar}</h2>
		<form name="sim" action="get">
			{html_options id=similar name=similar options=$itemSimilar onchange="goSimilar(this.form)"}
		</form>
	{/if}
	</div>
	</td>
</tr>
</table>
<script src="includes/js/lytebox.js" type="text/javascript"></script>