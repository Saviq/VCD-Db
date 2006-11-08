<h1><?=VCDLanguage::translate('search.extended')?></h1>

<? 
	$s_title = "";
	$s_category = "";
	$s_year = "";
	$s_mediatype = "";
	$s_owner = "";
	$s_grade = "";


	if (sizeof($_POST) > 0)	 {
		$s_title = $_POST['title'];
		$s_category = $_POST['category'];
		$s_year = $_POST['year'];
		$s_mediatype = $_POST['mediatype'];
		$s_owner = $_POST['owner'];
		$s_grade = $_POST['grade'];
		
	}
?>

<form name="advanced_search" method="post" action="./index.php?page=detailed_search&action=search">
<table class="displist" cellpadding="1" cellspacing="0" width="100%">
<tr>
	<td width="40%"><?=VCDLanguage::translate('movie.title')?>  <?=VCDLanguage::translate('misc.contains')?>:</td>
	<td><input type="text" name="title" value="<?=$s_title?>" /></td>
</tr>
<tr>
	<td><?=VCDLanguage::translate('movie.category')?>:</td>
	<td><? 
	
		$catObjArr = getLocalizedCategories($SETTINGSClass->getMovieCategoriesInUse());
		$adultCatID = $SETTINGSClass->getCategoryIDByName('adult');
		$adultEnabled = VCDUtils::showAdultContent();
		
		print "<select name=\"category\" size=\"1\">";
		print "<option value=\"null\">".VCDLanguage::translate('misc.any')."</option>";
		foreach ($catObjArr as $categoryObj) {
						
			$sel = "";
			if ($categoryObj['id'] == $s_category)  {
				$sel = " selected";
			}
				
			if ($adultCatID == $categoryObj['id'] && !$adultEnabled) {continue;}
			
			print "<option value=\"".$categoryObj['id']."\" ".$sel.">".$categoryObj['name']."</option>";
			
		}
		print "</select>"; ?></td>
</tr>
<tr>
	<td><?=VCDLanguage::translate('movie.year')?>:</td>
	<td>
		<select name="year"> 
		<option value="null"><?=VCDLanguage::translate('misc.any')?></option>
		<?
		for ($i = date("Y"); $i > 1900; $i--) {
			
			$sel = "";
			if ($i == $s_year)  {
				$sel = " selected";
			}
			
			print "<option value=\"$i\" ".$sel.">$i</option>";
		}
	?>
	</select>
	</td>
</tr>
<tr>
	<td><?=VCDLanguage::translate('movie.media')?>:</td>
	<td><? 
		print "<select name=\"mediatype\" size=\"1\">";
		print "<option value=\"null\">".VCDLanguage::translate('misc.any')."</option>";
		foreach ($SETTINGSClass->getAllMediatypes() as $mediaTypeObj) {
			
			$sel = "";
			if ($mediaTypeObj->getmediaTypeID() == $s_mediatype)  {
				$sel = " selected";
			}
			
			print "<option value=\"".$mediaTypeObj->getmediaTypeID()."\" ".$sel.">".$mediaTypeObj->getDetailedName()."</option>";
			if ($mediaTypeObj->getChildrenCount() > 0) {
				foreach ($mediaTypeObj->getChildren() as $childObj) { 
					
					$sel = "";
					if ($childObj->getmediaTypeID() == $s_mediatype)  {
						$sel = " selected";
					}
					print "<option value=\"".$childObj->getmediaTypeID()."\" ".$sel.">&nbsp;&nbsp;".$childObj->getDetailedName()."</option>";
				}
			}
			
		}
		print "</select>"; ?></td>
</tr>
<tr>
	<td><?=VCDLanguage::translate('movie.owner')?>:</td>
	<td>
	<? 
		$USERClass = VCDClassFactory::getInstance('vcd_user');
		print "<select name=\"owner\" size=\"1\">";
		print "<option value=\"null\">".VCDLanguage::translate('misc.any')."</option>";
		foreach ($USERClass->getActiveUsers() as $userObj) {
			
			$sel = "";
			if ($userObj->getUserID() == $s_owner)  {
				$sel = " selected";
			}
			
			print "<option value=\"".$userObj->getUserID()."\" ".$sel.">".$userObj->getUsername()."</option>";
		}
		print "</select>";
	?>
	</td>
</tr>
<tr>
	<td><?=VCDLanguage::translate('misc.grade')?>:</td>
	<td>
		<select name="grade">
		<option value="null"><?=VCDLanguage::translate('misc.any')?></option>
		<?
			
		for ($i = 1; $i < 10; $i+=0.5) {
			
			$sel = "";
			if ($i == $s_grade)  {
				$sel = " selected";
			}
			
			print "<option value=\"$i\" ".$sel.">$i</option>";
		}
	?>
		</select>
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><input type="submit" value="<?=VCDLanguage::translate('search.search')?>" onclick="return checkAdvanced(this.form)"/></td>
</tr>
</table>
</form>


<? 
	if (isset($_GET['action']) && $_GET['action'] == 'search') {
		if (sizeof($_POST) > 0) {
		
			
			
			if ($s_title == '') {
				$s_title = null;
			} else {
				$s_title = str_replace("'","''", $s_title);
			}
			
			$VCDClass = VCDClassFactory::getInstance('vcd_movie');
			$resultArr = $VCDClass->advancedSearch($s_title, $s_category, $s_year, $s_mediatype, $s_owner, $s_grade);
			
			if (sizeof($resultArr) == 0) {
				print "<p class=\"bold\">".VCDLanguage::translate('search.noresult').".</p>";
			} else {
			
			?> 
			<table cellspacing="1" cellpadding="1" border="0" class="displist" width="100%"> 
			<tr>
				<td class="header"><?=VCDLanguage::translate('movie.title')?></td>
				<td class="header"><?=VCDLanguage::translate('movie.category')?></td>
				<td class="header" nowrap="nowrap"><?=VCDLanguage::translate('movie.year')?></td>
				<td class="header"><?=VCDLanguage::translate('movie.media')?></td>
				<td class="header"><?=VCDLanguage::translate('movie.grade')?></td>
			</tr>
			<?
			foreach ($resultArr as $item) {
				print "<tr>";
					print "<td><a href=\"./?page=cd&amp;vcd_id=".$item['id']."\">".$item['title']."</a></td>";
					print "<td>".$item['category']."</td>";
					print "<td>".$item['year']."</td>";
					print "<td nowrap>".$item['media_type']."</td>";
					print "<td>".$item['rating']."</td>";
				print "</tr>";
			}
			
			
			print "</table>";
			
			}
			
			
		} else {
			redirect('?page=detailed_search');
		}
	}

?>