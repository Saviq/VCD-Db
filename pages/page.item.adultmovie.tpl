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
		<td valign="top" width="10%">thumbnail</td>
		<td valign="top">
			<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
				<td colspan="2"><strong>title</strong></td>
			</tr>
			<tr>
				<td width="30%">{$translate.movie.category}:</td>
				<td>Category</td>
			</tr>
			<tr>
				<td nowrap="nowrap">{$translate.movie.year}:</td>
				<td>year</td>
			</tr>
			<tr>
				<td>{$translate.movie.copies}:</td>
				<td>copies</td>
			</tr>
			<tr>
				<td>{$translate.movie.screenshots}</td>
				<td>screenshot logic here</td>
			</tr>
			<tr>
			{if $isAuthenticated}
			<tr>
				{if $isOnWishList}
					<td>&nbsp;</td>
					<td><a href="?page=wishlist">({$translate.wishlist.onlist})</a></td>
				{else}
					<td>&nbsp;</td>
					<td><a href="#" onclick="addtowishlist({$itemId})">{$translate.wishlist.add}</a></td>
				{/if}
			</tr>
			{/if}
			{if $isOwner || $isAdmin}
			<tr>
				<td>&nbsp;</td>
				<td><a href="#" onclick="loadManager({$itemId});return false">{$translate.movie.change}</a></td>
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
			
			<tr>
				<td>&nbsp;</td>
				<td>Play button</td>
			</tr>
			<tr>
				<td colspan="2">Source Logo</td>
			</tr>
			</table>


		</td>
	</tr>
	</table>

	Screenshots enabled logic ..



	<h2>{$translate.movie.actors}</h2>
	<div id="actorimages" style="padding-left:10px;">
		pornstar image list
	</div>
	<br/>

	<div id="copies">
	<h2>{$translate.movie.available}:</h2>
	Copies logic
	</div>

	<br/>

	<h2>{$translate.movie.covers}</h2>
	Cover list



	</td>
	<td valign="top" style="background-color:white">
	<h2>Studio</h2>
	studio logic
	</ul>
	<br/><br/>

	<h2>{$translate.dvdempire.subcat}</h2>
	<ul>
	<li>subcategories</li>
	</ul>
	<br/><br/>

	<h2>{$translate.movie.covers}</h2>
	cover lsit


	<br/><br/>
	
	<h2>{$translate.movie.actors}</h2>
	<div id="actorlist">
	<ul>
		pornstar names
	</ul>
	</div>
	<br/>
	<div id="similar">Similar logic
	</div>
	<div id="comments">
	Comment list
	</div>
	
	

	</td>
</tr>
</table>

