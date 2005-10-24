<? 
	global $language;
	$USERClass = VCDClassFactory::getInstance("vcd_user");
	$SETTINGSClass = VCDClassFactory::getInstance("vcd_settings");
	$user = $_SESSION['user'];
	$status = "";
	
	/* Process the registration form */
	if (sizeof($_POST) > 0) {
		$user->setName($_POST['name']);
		$user->setEmail($_POST['email']);
		if (isset($_POST['password']) && strlen($_POST['password']) > 4) {
			if ($user->isDirectoryUser()) {
				VCDException::display('Password cannot be changed for Directory authenticated users.', true);
				exit();
				
			}
			$user->setPassword(md5($_POST['password']));
		}

		
		// Check for properties
		$user->flushProperties();	
		if (isset($_POST['property']) && is_array($_POST['property'])) {
			foreach ($_POST['property'] as $propID) {
				$user->addProperty($USERClass->getPropertyById($propID));
			}
		}

		
		if ($USERClass->updateUser($user)) {
			// update the user in session as well
			$_SESSION['user'] = $user;
			VCDUtils::setMessage("(User information updated)");
		} else {
			VCDUtils::setMessage("(Failed to update)");
		}
		
		
	}

/* 
	Display and process registration
*/
?>
<form name="user" method="POST" action="./?page=private&o=settings&action=update">
<h1><?=$language->show('MENU_SETTINGS')?></h1>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="displist">
<tr>
	<td width="35%"><?=$language->show('REGISTER_FULLNAME')?>:</td>
	<td><input type="text" name="name" value="<?=$user->getFullname()?>"/></td>
</tr>
<tr>
	<td><?=$language->show('LOGIN_USERNAME')?>:</td>
	<td><input type="text" name="username" readonly value="<?=$user->getUsername()?>"/></td>
</tr>
<tr>
	<td><?=$language->show('REGISTER_EMAIL')?>:</td>
	<td><input type="text" name="email" value="<?=$user->getEmail()?>"/></td>
</tr>
<tr>
	<td><?=$language->show('LOGIN_PASSWORD')?>:</td>
	<td><input type="password" name="password"/></td>
</tr>
<tr>
	<td colspan="2">(<?=$language->show('LOGIN_INFO')?>)<br/><br/></td>
</tr>
<? /* 
	Get all the custom user properties
   */
	$props = $USERClass->getAllProperties();
	$show_adult = (bool)$SETTINGSClass->getSettingsByKey('SITE_ADULT');
	
	foreach ($props as $propertyObj) {
		$checked = "";
		$viewfeed = "";
		if ($propertyObj->getpropertyName() == 'RSS' && $user->getPropertyByKey($propertyObj->getpropertyName())) {
			$viewfeed = "  <a href=\"rss/?rss=".$user->getUsername()."\">(".$language->show('SE_OWNFEED').")</a>";
		}
		
		if ($propertyObj->getpropertyName() == 'PLAYOPTION' && $user->getPropertyByKey($propertyObj->getpropertyName())) {
			$viewfeed = "  <a href=\"#\" onclick=\"adjustPlayer()\">(".$language->show('SE_PLAYER').")</a>";
		}
		
		if ($propertyObj->getpropertyName() == 'SHOW_ADULT' && !$show_adult) {
			
		} else {
		
			if ($user->getPropertyByKey($propertyObj->getpropertyName())) {
				$checked = "checked";
			}
			
			// Check if translation for property exists
			$langkey = "PRO_".$propertyObj->getpropertyName();
			$description = $language->show($langkey);
			if (strcmp($description, "undefined") == 0) {
				$description = $propertyObj->getpropertyDescription();
			}
			
			
			print "<tr>
						<td nowrap>".$description."</td>
						<td><input type=\"checkbox\" name=\"property[]\" class=\"nof\" value=\"".$propertyObj->getpropertyID()."\" $checked/>".$viewfeed."</td>
			       </tr>";
		}
	}
   
?>
<tr>
	<td><? print "<div class=\"info\">".VCDUtils::getMessage()."</div>"; ?></td>
	<td><input type="submit" value="Submit"/></td>
</tr>
</table>
</form>

<br/>
<? 
	
	$arrBorrowers = $SETTINGSClass->getBorrowersByUserID($user->getUserID());
	
	$bEdit = false;
	$bid = "";
	if (isset($_GET['edit']) && strcmp($_GET['edit'], "borrower") == 0) {
		$bEdit = true;
		$bid = $_GET['bid'];
		$currObj = $SETTINGSClass->getBorrowerByID($bid);
	}
	
	
	if (is_array($arrBorrowers) && sizeof($arrBorrowers) > 0) {
		print "<h2>".$language->show('MY_FRIENDS')."</h2>";
		
		print "<table cellpadding=\"1\" cellspacing=\"1\" width=\"100%\" border=\"0\">";
		print "<tr><td>";
		
		print "<form name=\"borrowForm\"><select name=\"borrowers\" size=1\">";
			print "<option value=\"null\">".$language->show('LOAN_SELECT')."</option>";
					foreach ($arrBorrowers as $obj) {
						$arr = $obj->getList();
						
						$selected = "";
						if ($arr['id'] == $bid) 
							$selected = "selected";
						
						print "<option value=\"".$arr['id']."\" $selected>".$arr['name']."</option>";
					}
					unset($arr);
			print "</select>";
		
		print "&nbsp;<input type=\"button\" value=\"".$language->show('X_EDIT')."\" onclick=\"changeBorrower(this.form)\">";
		print "<img src=\"images/icon_del.gif\" hspace=\"4\" alt=\"\" align=\"absmiddle\" onclick=\"deleteBorrower(this.form)\" border=\"0\"/></form>";
	
	}
	
	print "</td>";
	
	if ($bEdit && ($currObj instanceof borrowerObj)) {
		
		print "<td>";
		
		print "<form name=\"update_borrower\" action=\"exec_form.php?action=edit_borrower\" method=\"post\"><table cellpadding=0 cellspading=0 border=0 class=list>";
		print "<tr><td>".$language->show('LOAN_NAME').":</td><td><input type=\"text\" name=\"borrower_name\" value=\"".$currObj->getName()."\" readonly/></td>";
		print "<td>".$language->show('REGISTER_EMAIL').":</td><td><input type=\"text\" name=\"borrower_email\" value=\"".$currObj->getEmail()."\"/></td>";
		print "<td>&nbsp;</td><td><input type=\"submit\" value=\"".$language->show('X_UPDATE')."\" id=\"vista\" onclick=\"return val_borrower(this.form)\"/></td></tr>";
		print "</table><input type=\"hidden\" name=\"borrower_id\" value=\"".$currObj->getID()."\"/></form>";
		
		print "</td>";
	}
	
	
	print "</tr></table>";

?>

<h2><?=$language->show('RSS_TITLE')?></h2>
<?
	$feeds = $SETTINGSClass->getRssFeedsByUserId($user->getUserID());	
	if (sizeof($feeds) > 0) {
		print "<table cellspacing=\"1\" cellpadding=\"1\" border=\"0\" class=\"displist\" width=\"100%\">";
		foreach ($feeds as $rssfeed) {
			$pos = strpos($rssfeed['url'], "?rss=");
			if ($pos === false) { 
			    $img = "<img src=\"images/rsssite.gif\" hspace=\"4\" title=\"".$language->show('RSS_SITE')."\" border=\"0\"/>";
			} else {
				$img = "<img src=\"images/rssuser.gif\" hspace=\"4\" title=\"".$language->show('RSS_USER')."\" border=\"0\"/>";
			}

			
			print "<tr><td align=\"center\">".$img."</td><td width=\"95%\">".$rssfeed['name']."</td><td><a href=\"".$rssfeed['url']."\"><img src=\"images/rss.gif\" border=\"0\" alt=\"".$language->show('RSS_VIEW')."\"/></a></td><td><img src=\"images/icon_del.gif\" onclick=\"deleteFeed(".$rssfeed['id'].")\"/></td></tr>";
		}
		print "</table>";
	} else {
		print "<p>" .$language->show('RSS_NONE') . "</p>";
	}
?>
<p>
<input type="button" value="<?=$language->show('RSS_ADD')?>" onclick="addFeed()"/>
</p>

<h2><?=$language->show('SE_CUSTOM')?></h2>
<? 
	// Check for current values
	$uid = VCDUtils::getUserID();
	$metaObjA = $SETTINGSClass->getMetadata(0, $uid, 'frontstats');
	$metaObjB = $SETTINGSClass->getMetadata(0, $uid, 'frontbar');
	$metaObjC = $SETTINGSClass->getMetadata(0, $uid, 'frontrss');
	$isChecked = "checked=\"checked\"";
	$check1 = "";
	$check2 = "";
	$arrSelectedFeeds = array();
	if (is_array($metaObjA) && sizeof($metaObjA) == 1 && $metaObjA[0]->getMetadataValue() == 1) {
		$check1 = $isChecked;
	}
	if (is_array($metaObjB) && sizeof($metaObjB) == 1 && $metaObjB[0]->getMetadataValue() == 1) {
		$check2 = $isChecked;
	}
	if (is_array($metaObjC) && sizeof($metaObjC) == 1) {
		$strFeeds = $metaObjC[0]->getMetadataValue();
		$arrSelectedFeeds = split("#", $strFeeds);
	}
?>
<form name="choiceForm" method="post" action="exec_form.php?action=edit_frontpage">
<input type="hidden" name="id_list"/>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="displist">
<tr>
	<td width="40%"><?=$language->show('SE_SHOWSTAT')?></td>
	<td><input type="checkbox" <?=$check1?> name="stats" class="nof" value="yes"/></td>
</tr>
<tr>
	<td><?=$language->show('SE_SHOWSIDE')?></td>
	<td><input type="checkbox" <?=$check2?> name="sidebar" class="nof" value="yes"/></td>
</tr>
<tr>
	<td valign="top"><?=$language->show('SE_SELECTRSS')?></td>
	<td valign="top">
	<!-- Open rss selection  -->
	
	<table cellpadding="1" cellspacing="1">
	<tr>
		<td>
		<select name="available" size="5" style="width:300px;" onDblClick="moveOver(this.form)">
		<?
		$arrFeeds = $SETTINGSClass->getRssFeedsByUserId(0);
		foreach ($arrFeeds as $item) {
			if (!in_array($item['id'], $arrSelectedFeeds))
				print "<option value=\"".$item['id']."\">".$item['name']."</option>";
		}
		?> 
		</select>
		</td>
	</tr>
	<tr>
		<td align="center"><img src="images/move_down.gif" onclick="moveOver(document.choiceForm);" hspace="4" border="0"/><img src="images/move_up.gif" onclick="removeMe(document.choiceForm);" border="0"/></td>
	</tr>
	<tr>
		<td><select multiple name="choiceBox" style="width:300px;" size="5" class="input">
		<?
		foreach ($arrFeeds as $item) {
			if (is_array($arrSelectedFeeds) && in_array($item['id'], $arrSelectedFeeds))
				print "<option value=\"".$item['id']."\">".$item['name']."</option>";
		}
		unset($arrFeeds);
		unset($arrSelectedFeeds);
		?> 
		</select></td>
	</tr>
	</table>
	
	
	
	<!-- Close rss selection -->
	</td>
	
</tr>
<tr>
	<td>&nbsp;</td>
	<td><input type="submit" value="<?=$language->show('X_UPDATE')?>" onclick="checkFieldsRaw(this.form)"/></td>
</tr>
</table>
</form>
<br/>
<? 
	/* 
		We only display the ignore list if more than 1 active users 
		is using VCD-db.
	*/
	$CLASSUsers = VCDClassFactory::getInstance('vcd_user');
	if (sizeof($CLASSUsers->getActiveUsers()) > 1) {
?>

<h2>Ignore list</h2>
<form name="ignore" method="post" action="exec_form.php?action=update_ignorelist">
<input type="hidden" name="id_list"/>
<? 
	// Get current ignore list
	$ignorelist = array();
	$metaArr = $SETTINGSClass->getMetadata(0, VCDUtils::getUserID(), 'ignorelist');
	if (sizeof($metaArr) > 0) {
		$ignorelist = split("#", $metaArr[0]->getMetadataValue());
	}
	
?>
<table cellpadding="1" cellspacing="1" border="0" width="100%">
<tr>
	<td width="44%" valign="top"">Ignore all movies from the following users:</td>
	<td width="10%"><select name="available" size="5" style="width:100px;" onDblClick="moveOver(this.form)">
		<?
	
		
		$arrUsers = $CLASSUsers->getActiveUsers();
		foreach ($arrUsers as $userObj) {
			if (!in_array($userObj->getUserID(), $ignorelist)) {
				if ($userObj->getUserID() != VCDUtils::getUserID()) {
					print "<option value=\"".$userObj->getUserID()."\">".$userObj->getUserName()."</option>";
				}
			}
		}
		?> 
		</select></td>
	<td width="5%" align="center">
	<input type="button" value="&gt;&gt;" onclick="moveOver(this.form);" class="input" style="margin-bottom:5px;"/><br/>
	<input type="button" value="&lt;&lt;" onclick="removeMe(this.form);" class="input"/>
	</td>
	<td width="10%"><select multiple name="choiceBox" style="width:100px;" size="5" class="input">
		<?
		foreach ($arrUsers as $userObj) {
			if (in_array($userObj->getUserID(), $ignorelist)) {
				print "<option value=\"".$userObj->getUserID()."\">".$userObj->getUserName()."</option>";
			}
		}
		?> 
		</select></td>
	<td align="left" valign="bottom"><input type="submit" value="<?=$language->show('X_UPDATE')?>" onclick="checkFieldsRaw(this.form)"/></td>
</tr>
</table>
</form>
<br/>

<? } ?>