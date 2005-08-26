<? 
	$s_category = "";
	$s_mediatype = "";
	$s_owner = "";
	$s_meth = "";

	if (sizeof($_POST) > 0)	 {
		$s_category = $_POST['category'];
		$s_mediatype = $_POST['mediatype'];
		$s_owner = $_POST['owner'];
		$s_meth = $_POST['method'];
	}
	
	
	/**
		Check if current DB is MySQL version prior to 4.1,
		since they don't support subqueries we have to disable the 
		user diff page :(
	*/
	$dbCompatible = true;
	$conn = new Connection();
	$sql_type = $conn->getSQLType();
	$sql_info = $conn->getServerInfo();
	unset($conn);
	if (strcmp($sql_type, 'mysql') == 0) {
		if (is_array($sql_info) && isset($sql_info['version'])) {
			$version = $sql_info['version'];
			if ($version < 4.1) {
				print "Sorry, but your underlying MySQL database (v.$version) is prior to version 4.1,
					   only MySQL 4.1 and up support subqueries, and these functions require subquery support.";
				$dbCompatible = false;
			}
		}
	}
	
if ($dbCompatible) {
?>

<form name="discjoin" method="post" action="./?page=private&amp;o=movies&amp;do=diff&amp;show=results">
<span class="bold" style="padding-left:7px">
	<?= $language->show('MY_JOINMOVIES') ?>
</span>
<table cellspacing="0" cellpadding="0" border="0" width="100%" class="list">
<tr>
	<td>1) <?= $language->show('MY_JOINSUSER') ?></td>
	<td><? 
		$USERClass = $ClassFactory->getInstance('vcd_user');
		print "<select name=\"owner\" size=\"1\">";
		print "<option value=\"null\">".$language->show('X_SELECT')."</option>";
		foreach ($USERClass->getActiveUsers() as $userObj) {
			
			$sel = "";
			if ($userObj->getUserID() == $s_owner)  {
				$sel = " selected";
			}
			
			print "<option value=\"".$userObj->getUserID()."\" ".$sel.">".$userObj->getUsername()."</option>";
		}
		print "</select>";
	?></td>
</tr>
<tr>
	<td>2) <?= $language->show('MY_JOINSMEDIA') ?></td>
	<td><? 
		print "<select name=\"mediatype\" size=\"1\">";
		print "<option value=\"null\">".$language->show('X_ANY')."</option>";
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
	<td>3) <?= $language->show('MY_JOINSCAT') ?></td>
	<td><? 
		print "<select name=\"category\" size=\"1\">";
		print "<option value=\"null\">".$language->show('X_ANY')."</option>";
		foreach ($SETTINGSClass->getMovieCategoriesInUse() as $categoryObj) {
			
			$sel = "";
			if ($categoryObj->getID() == $s_category)  {
				$sel = " selected";
			}
				
			
			print "<option value=\"".$categoryObj->getID()."\" ".$sel.">".$categoryObj->getName()."</option>";
		}
		print "</select>"; ?></td>
</tr>
<tr>
	<td>4) <?= $language->show('MY_JOINSTYPE') ?></td>
	<td>
	<select name="method">
		<option value="null"><?=$language->show('X_SELECT')?></option>
		<option value="1" <?if ($s_meth == 1) { print "selected";}?>><?=$language->show('MY_J1')?></option>
		<option value="2" <?if ($s_meth == 2) { print "selected";}?>><?=$language->show('MY_J2')?></option>
		<option value="3" <?if ($s_meth == 3) { print "selected";}?>><?=$language->show('MY_J3')?></option>
	</select>
	
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><input type="submit" value="<?=$language->show('MY_JOINSHOW')?>"/></td>
</tr>
</table>
</form>


<? } ?>