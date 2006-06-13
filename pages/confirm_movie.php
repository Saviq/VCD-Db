<? 
	global $language;
	$SettingsClass = VCDClassFactory::getInstance('vcd_settings');
?>

<form name="imdbfetcher" action="exec_form.php?action=moviefetch" method="post">
<input type="hidden" name="imdb" value="<?=$fetchedObj->getIMDB()?>"/>
<input type="hidden" name="image" value="<?=$fetchedObj->getImage()?>"/>
<table cellspacing="0" cellpadding="0" width="100%" border="0" class="displist">
<tr>
	<td class="header"><?=$language->show('M_INFO')?></td>
	<td class="header"><?=$language->show('M_DETAILS')?></td>
</tr>
<tr>
	<td valign="top" width="65%">
	<!-- Begin IMDB info-->
	<table cellpadding="0" cellpadding="0" border="0" class="plain" width="100%">
	<tr>
		<td rowspan="6" width="105"><img src="<? if (is_null($fetchedObj->getImage())) { print 'images/noimage.gif'; } else { print TEMP_FOLDER.$fetchedObj->getImage(); }?>" border="0" class="imgx"/></td>
		<td width="30%"><?=$language->show('M_TITLE')?>:</td>
		<td width="50%"><input type="text" name="title" value="<?=VCDUtils::titleFormat($fetchedObj->getTitle())?>" size="28"></td>
	</tr>
	<tr>
		<td>IMDB <?=$language->show('M_TITLE')?>:</td>
		<td><input type="text" name="imdbtitle" value="<?=$fetchedObj->getTitle()?>" size="28"></td>
	</tr>
	<tr>
		<td><?=$language->show('M_ALTTITLE')?>:</td>
		<td><input type="text" name="alttitle" value="<?=$fetchedObj->getAltTitle()?>" size="28"></td>
	</tr>
	<tr>
		<td><?=$language->show('M_YEAR')?>:</td>
		<td><input type="text" name="year" value="<?=$fetchedObj->getYear()?>" size="4"></td>
	</tr>
	<tr>
		<td><?=$language->show('M_GRADE')?>:</td>
		<td><input type="text" name="rating" value="<?=$fetchedObj->getRating()?>" size="2"> stars</td>
	</tr>
	<tr>
		<td><?=$language->show('M_RUNTIME')?>:</td>
		<td><input type="text" name="Runtime" value="<?=$fetchedObj->getRuntime()?>" size="4"> minutes</td>
	</tr>
	<tr>
		<td><?=$language->show('M_DIRECTOR')?>: </td>
		<td colspan="2"><input type="text" name="director" value="<?=$fetchedObj->getDirector()?>" size="25"></td>
	</tr>
	<tr>
		<td><?=$language->show('M_COUNTRY')?>:</td>
		<td colspan="2"><input type="text" name="Country" value="<?=VCDUtils::split($fetchedObj->getCountry(), ", ");?>" size="40"></td>
	</tr>
	<tr>
		<td nowrap>IMDB <?=$language->show('M_CATEGORY')?>: </td>
		<td colspan="2"><input type="text" name="categories" value="<?=VCDUtils::split($fetchedObj->getGenre(), ", ");?>" size="40"></td>
	</tr>
	<tr>
		<td colspan="3"><?=$language->show('M_PLOT')?>:<br/>
		<textarea cols="55" rows="5" name="plot"><?=stripslashes($fetchedObj->getPlot())?></textarea></td>
	</tr>
	<tr>
		<td colspan="3"><?=$language->show('M_ACTORS')?>:<br/>
		
		<!-- IMDB Cast -->
<textarea cols="55" rows="16" name="cast"><? 
if(is_array($fetchedObj->getCast(false))) {
	foreach ($fetchedObj->getCast(false) as $actor) {
		print stripslashes(trim($actor)) . "\n";
	}
}
?></textarea>
<!-- End IMDB Cast -->
		
		</td>
	</tr>
	</table>
	
	
	<!-- End IMDB info -->
	
	</td>
	<td valign="top" width="35%">
	<!-- My copy -->
	
	
	<table cellspacing="1" cellpadding="1" width="100%" class="plain">
	<tr>
		<td class="strong" nowrap><?=$language->show('M_MEDIATYPE')?>:</td>
		<td>
		<? 
		print "<select name=\"mediatype\" size=\"1\">";
		print "<option value=\"null\">".$language->show('X_SELECT')."</option>";
		foreach ($SettingsClass->getAllMediatypes() as $mediaTypeObj) {
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
		<td><?=$language->show('M_CATEGORY')?>:</td>
		<td>
		<? 
		// Try to find the first category from IMDB and mark it default for conveniance
		$sid = -1;
		$items = $fetchedObj->getGenre();
		if (!is_array($items)) {
			$items = explode(",", $items);
		}
		
		if (is_array($items)) {
			$scat = $items[0];
			$sid = $SettingsClass->getCategoryIDByName($scat, true);
		} 
		
		// Get the localized category list
		$arrCategories = getLocalizedCategories();
		
		print "<select name=\"category\" size=\"1\">";
		print "<option value=\"null\">".$language->show('X_SELECT')."</option>";
		foreach ($arrCategories as $catArray) {
			if ($sid != -1 && is_numeric($sid) && $sid == $catArray['id']) {
				print "<option value=\"".$catArray['id']."\" selected>".$catArray['name']."</option>";
			} else {
				print "<option value=\"".$catArray['id']."\">".$catArray['name']."</option>";
			}
			
		}
		print "</select>"; ?>
		</td>	
	</tr>
	<tr>
		<td>CD's:</td>
		<td><select name="cds"><option value="null"><?=$language->show('X_SELECT')?></option>
		<? for($i=1;$i<7;$i++){print "<option value=\"$i\">$i</option>";} ?>
		</select></td>
	</tr>
	<tr>
		<td valign="top"><?=$language->show('M_COMMENT')?>:</td>
		<td><textarea cols="15" rows="5" name="comment"></textarea></td>
	</tr>
	<tr>
		<td valign="top" colspan="2"><?=$language->show('M_PRIVATE')?>: <input type="checkbox" class="nof" value="private" name="private"/></td>
	</tr>
	</table>
	<br/>
	<input type="submit" value="<?=$language->show('X_CONFIRM')?>" class="buttontext" onclick="return val_IMDB(this.form)"/>
	
	
	
	<!-- End My copy -->
	</td>
</tr>
</table>



</form>