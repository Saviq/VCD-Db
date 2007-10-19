<h1>Add new movie manually</h1>

<form name="manual" action="?page=add_manually&amp;action=add" method="post" enctype="multipart/form-data">
<table cellspacing="1" cellpadding="1" border="0" width="100%" class="displist">
<tr>
	<td width="20%">{$translate.movie.title}:</td>
	<td width="30%"><input type="text" name="title" size="35"/></td>
	<td rowspan="8" valign="top">Thumbnail: <input type="file" name="userfile" value="userfile"/></td>
</tr>
<tr>
	<td nowrap="nowrap">{$translate.movie.year}:</td>
	<td>{html_options id=year name=year options=$yearList}</td>
</tr>
	<tr>
		<td class="strong" nowrap="nowrap">{$translate.movie.mediatype}:</td>
		<td>{html_options id=mediatype name=mediatype options=$mediatypeList}</td>
	</tr>
	<tr>
		<td>{$translate.movie.category}:</td>
		<td>{html_options id=category name=category options=$itemCategoryList selected=$selectedCategory class="plain"}</td>	
	</tr>
	<tr>
		<td>CD's:</td>
		<td>{html_options id=cds name=cds options=$cdList}</td>
	</tr>
	<tr>
		<td valign="top">{$translate.movie.comment}:</td>
		<td><textarea cols="25" rows="5" name="comment"></textarea></td>
	</tr>
	<tr>
		<td valign="top" colspan="2">{$translate.movie.private}: <input type="checkbox" class="nof" value="private" name="private"/></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td align="right"><input type="submit" onclick="return checkManually(this.form)" value="{$translate.menu.submit}"/></td>
	</tr>
</table>
</form>