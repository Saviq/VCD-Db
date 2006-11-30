<h1>Add new movie manually</h1>

<form name="manual" action="exec_form.php?action=add_manually" method="POST" enctype="multipart/form-data">
<table cellspacing="1" cellpadding="1" border="0" width="100%" class="displist">
<tr>
	<td width="20%"><?=VCDLanguage::translate('movie.title')?>:</td>
	<td width="30%"><input type="text" name="title" size="35"/></td>
	<td rowspan="8" valign="top">Thumbnail: <input type="file" name="userfile" value="userfile"/></td>
</tr>
<tr>
	<td nowrap="nowrap"><?=VCDLanguage::translate('movie.year')?>:</td>
	<td><select name="year"> 
		<?
		for ($i = date("Y"); $i >= 1900; $i--) {
			print "<option value=\"$i\">$i</option>";
		}
	?>
	</select></td>
</tr>
	<tr>
		<td class="strong" nowrap="nowrap"><?=VCDLanguage::translate('movie.mediatype')?>:</td>
		<td>
		<? 
		print "<select name=\"mediatype\" size=\"1\">";
		print "<option value=\"null\">".VCDLanguage::translate('misc.select')."</option>";
		foreach (SettingsServices::getAllMediatypes() as $mediaTypeObj) {
			print "<option value=\"".$mediaTypeObj->getmediaTypeID()."\">".$mediaTypeObj->getDetailedName()."</option>";
			if ($mediaTypeObj->getChildrenCount() > 0) {
				foreach ($mediaTypeObj->getChildren() as $childObj) { 
					print "<option value=\"".$childObj->getmediaTypeID()."\">&nbsp;&nbsp;".$childObj->getDetailedName()."</option>";
				}
			}
			
		}
		print "</select>"; ?>
		</td>
	</tr>
	<tr>
		<td><?=VCDLanguage::translate('movie.category')?>:</td>
		<td>
		<? 
				
		// Get the localized category list
		$arrCategories = getLocalizedCategories();
		
		print "<select name=\"category\" size=\"1\">";
		print "<option value=\"null\">".VCDLanguage::translate('misc.select')."</option>";
		foreach ($arrCategories as $catArray) {
			print "<option value=\"".$catArray['id']."\">".$catArray['name']."</option>";
		}
		print "</select>"; ?>
		</td>	
	</tr>
	<tr>
		<td>CD's:</td>
		<td><select name="cds"><option value="null"><?=VCDLanguage::translate('misc.select')?></option>
		<? for($i=1;$i<7;$i++){print "<option value=\"$i\">$i</option>";} ?>
		</select></td>
	</tr>
	<tr>
		<td valign="top"><?=VCDLanguage::translate('movie.comment')?>:</td>
		<td><textarea cols="25" rows="5" name="comment"></textarea></td>
	</tr>
	<tr>
		<td valign="top" colspan="2"><?=VCDLanguage::translate('movie.private')?>: <input type="checkbox" class="nof" value="private" name="private"/></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td align="right"><input type="submit" onclick="return checkManually(this.form)" value="<?=VCDLanguage::translate('menu.submit')?>"/></td>
	</tr>
</table>
</form>