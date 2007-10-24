<h2>{$translate.dvdempire.info}</h2>

<form name="empire_comfirm" action="{$smarty.server.SCRIPT_NAME}?page=add&amp;action=addadultmovie" method="post">
<input type="hidden" name="thumbnail" value="{$itemThumb}"/>
<table cellspacing="1" cellpadding="1" border="0" width="100%" class="list">
<tr>
	<td valign="top" width="16%"><h2>Thumbnail</h2>{$itemThumbnail}</td>
	<td valign="top" width="42%"><h2>{$translate.dvdempire.desc}</h2>

		<table cellspacing="1" cellpadding="1" border="0" width="100%">
			<tr>
				<td class="tblb">{$translate.movie.title}:</td>
				<td><input type="text" value="{$itemTitle}" name="title" class="input" size="30"/></td>
			</tr>
			<tr>
				<td class="tblb">Studio:</td>
				<td>{html_options id=studio name=studio options=$studioList selected=$selectedStudio style="width:190px;"}</td>
			</tr>
			<tr>
				<td class="tblb">{$translate.movie.year}:</td>
				<td><input type="text" value="{$itemYear}" name="year" class="input"/></td>
			</tr>
			<tr>
				<td class="tblb" nowrap="nowrap">ID:</td>
				<td><input type="text" value="{$itemId}" name="id" class="input" readonly="readonly"/></td>
			</tr>
			<tr>
				<td colspan="2">
				<div class="tblb">{$translate.movie.comment}:</div>
				<textarea name="comment" cols="36" rows="4" class="input"></textarea>
				<br/>
				{$translate.movie.private}: <input type="checkbox" class="nof" value="private" name="private"/>
				</td>
			</tr>
		</table>

	 </td>
	<td valign="top" width="42%"><h2>{$translate.movie.details}</h2>
		<table>
		<tr>
			<td class="tblb">{$translate.movie.category}:</td>
			<td>{html_options id=category name=category options=$itemCategoryList selected=$selectedCategory class="plain"}</td>
		</tr>
		<tr>
			<td class="tblb">{$translate.movie.mediatype}:</td>
			<td>{html_options id=mediatype name=mediatype options=$mediatypeList class="plain"}</td>
		</tr>
		<tr>
			<td class="tblb">{$translate.movie.num}:</td>
			<td>{html_options id=cds name=cds options=$cdList class="plain"}</td>
		</tr>
		<tr>
			<td colspan="2" class="tblb">{$translate.dvdempire.subcat}:<br/>
			<input type="hidden" name="id_list" id="id_list"/>
			<table cellspacing="0" cellpadding="2" border="0">
			<tr>
				<td>{html_options id=available name=available options=$subcatsAvailableList size="8" style="width:100px;" onDblClick="moveOver(this.form, 'available', 'choiceBox');" class="plain"}</td>
				<td>
					<input type="button" value="&gt;&gt;" onclick="moveOver(this.form, 'available', 'choiceBox');" class="input" style="margin-bottom:5px;width:25px;"/>
					<br/>
					<input type="button" value="&lt;&lt;" onclick="removeMe(this.form, 'available', 'choiceBox');" class="input" style="width:25px;"/>
				</td>
				<td>{html_options id=choiceBox multiple="multiple"  name=choiceBox options=$subcatsSelectedList size="8" style="width:100px;" onDblClick="moveOver(this.form, 'available', 'choiceBox');" class="plain"}</td>
			</tr>
			</table>

			</td>
		</tr>
		</table>


	</td>
</tr>
<tr>
	<td colspan="3"><h2>{$translate.dvdempire.details}</h2></td>
</tr>
<tr>
	<td colspan="3">
		<table cellspacing="1" cellpadding="1" border="0" width="100%">
		<tr>
			<td valign="top" width="40%"><h2>{$translate.dvdempire.stars}</h2>
			{if is_array($itemActors) && count($itemActors)>0}
			<ul>
			{foreach from=$itemActors item=i}
			
			{if $i.exists}
			<li class="green"><input type="checkbox" name="pornstars[]" value="{$i.id}" checked="checked" class="nof"/>
			&nbsp;<a href="?page=pornstar&amp;pornstar_id={$i.id}" target="_blank">{$i.name}</a></li>
			{else}
			<li class="red"><input type="checkbox" name="pornstars_new[]" value="{$i.name}" class="nof"/>&nbsp;{$i.name}</li>
			{/if}
			{/foreach}
			</ul>
			{/if}
			</td>
			<td valign="top" width="35%">
			<h2>{$translate.misc.attention}</h2>
			{$translate.dvdempire.notice}
			</td>
			<td valign="top" width="25%"><h2>{$translate.dvdempire.fetch}</h2>
			<input type="checkbox" name="imagefetch[]" value="VCD Front Cover" checked="checked" class="nof"/>Front Cover<br/>
			<input type="checkbox" name="imagefetch[]" value="VCD Back Cover" checked="checked" class="nof"/>Back Cover<br/>
			{if $itemScreenshotCount > 0} 
				<input type="checkbox" name="imagefetch[]" value="screenshots" checked="checked" class="nof"/>Screenshots ({$itemScreenshotCount})
				<br/>
				<input type="hidden" name="screenshotcount" value="{$itemScreenshotCount}"/>
			{else}
				<input type="checkbox" name="imagefetch[]" value="screenshots" disabled="disabled" class="nof"/>Screenshots<br/>
			{/if}
			</td>
		</tr>
		</table>

	</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td align="right"><input type="submit" value="{$translate.misc.confirm}" class="buttontext" onclick="return val_Empire(this.form)"/></td>
</tr>
</table>
</form>
<br/>
