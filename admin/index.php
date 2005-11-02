<?php
	require_once("../classes/includes_admin.php");
	require_once("functions/adminPageFunctions.php");
		
	if (!VCDAuthentication::isAdmin()) {
		VCDException::display("Only administrators have access here");
		print "<script>self.close();</script>";
		exit();
	}
	
	$WORKING_MODE = "";
	if (isset($_GET['mode']))
		$WORKING_MODE = $_GET['mode'];
	
	$LAYER_LABEL = "Add " .str_replace("_"," ",$CURRENT_PAGE);
	if (strcmp($WORKING_MODE, "edit") == 0) {
		$LAYER_LABEL = "Edit record";
	}
	
	if (strcmp($CURRENT_PAGE, "deleteRecord") == 0) {
		deleteRecord($_GET['recordID'],$_GET['recordType']);
	}
	
	if (strcmp($CURRENT_PAGE, "exportUserXML") == 0) {
		exportUserXML($_GET['recordID']);
	}
	
	if (strcmp($CURRENT_PAGE, "setDefaultRole") == 0) {
		setDefaultRole($_GET['recordID']);
	}
	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/strict.dtd">		 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>VCD :: Admin console</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="../includes/css/admin.css" type="text/css" media="all" />
<script src="../includes/js/admin.js" type="text/javascript"></script>

</head>
<body onload="window.focus()">

<table cellspacing=0 cellpadding=0 border=0 width="100%" align="center" id="admintable">
<tr>
	<td colspan="2" align="center" class="admintitle">VCD Admin web <? print str_replace("_"," ",$CURRENT_PAGE) ?></td>
</tr>
<tr>
	<td colspan="2">
		<div id="menubar" align="right">
        <ul>
          <li><a href="javascript:self.close()">Close</a></li>
          <li><a href="./?page=statistics">Statistics</a></li>
          <li><a href="./?page=log">Log</a></li>
          <li><a href="./?page=backup">Backup</a></li>
          <li><a href="./?page=import">Import</a></li>
          <li><a href="javascript:mailtest()">Test Mail settings</a></li>
          <li><a href="./?">Home</a></li>
        </ul>
      </div>
	</td>
</tr>
<tr>
	<td valign="top" nowrap class="navtd">
	<br/>
	<div id="sidebar1" class="sidebar">
	<div id="siteLinks" align="center">
	<ul>
    	<li><a href="./?page=allowed_types">Allowed cover types</a></li>
		<li><a href="./?page=cover_types">Cover types</a></li>
		<li><a href="./?page=languages">Languages</a></li>
		<li><a href="./?page=media_types">Media types</a></li>
		<li><a href="./?page=categories">Movie categories</a></li>
		<li><a href="./?page=sites">Source sites</a></li>
		<li><a href="./?page=users">Users</a></li>
		<li><a href="./?page=roles">User roles</a></li>
		<li><a href="./?page=properties">User properties</a></li>
		<li><a href="./?page=settings">Web settings</a></li>
		<li><a href="./?page=xmlfeeds">XML feeds</a></li>
		<li><hr style="height:1px;"/><a href="./?page=versioncheck">Check for new version</a></li>
		
		<? 
			$SETTINGSclass = VCDClassFactory::getInstance("vcd_settings");
			$showAdult = $SETTINGSclass->getSettingsByKey('SITE_ADULT');
			if ($showAdult) { ?>
			<li><hr style="height:1px;"/><a href="./?page=pornstars">Pornstars</a></li>
			<li><a href="./?page=porncategories">Porn categories</a></li>
			<li><a href="./?page=pornstudios">Porn studios</a></li>
			
			<? }?>
		
	</ul>
	<br/>
	 <div class="sideInfo">
          <h3>Site info:</h3>
          <p>
          Logged in as: <?=$_SESSION['user']->getUsername()?><br>
          Version: <?=VCDDB_VERSION?><br>
          OS: <?=PHP_OS?><br>
		  Host: <?=$_SERVER['SERVER_NAME']?><br>
          DB Type: <? $conn = new Connection(); echo $conn->getSQLType() ?><br>
          DB Host: <?=$conn->getSQLHost() ?>
          
          
          </p>
    </div>
	<p></p>
	<br/><br/>
	
	</div>
	</div>
	</td>
	
	<td valign="top" class="maintd">
	
	<? 

			if (showAddRecord($CURRENT_PAGE)) {
				?><h1><input type="button" class="fast" value="<?=$LAYER_LABEL?>" onClick="toggle('newObj');return false;""></h1><?
			}
	
	
			/*
				CASE Cover Types
			*/
			if ($CURRENT_PAGE == "cover_types") { 
			$CTClass = new vcd_cdcover();
			
			require("forms/addCoverType.php");	
		
	   		echo "<div class=\"content\">";	
			
			/**************************/
			/* Add Cover type */
			if (isset($_POST['save'])) {
				$data = array("",$_POST['name'],$_POST['description']);
				$obj = new cdcoverTypeObj($data);
				$CTClass->addCoverType($obj);
				
			}
			/* Update coverType */
			elseif (isset($_POST['update'])) {
				$data = array($_POST['id'],$_POST['name'], $_POST['description']);
				$obj = new cdcoverTypeObj($data);
				$CTClass->updateCoverType($obj);
				print "<script>location.href='./?page=".$CURRENT_PAGE."'</script>";
				exit();
			}
			/**************************/
			
			$covertypes = $CTClass->getAllCoverTypes();			
			$header = array("Type name","Description");
			printTableOpen();
			printRowHeader($header);
			foreach ($covertypes as $cdcoverTypeObj) {
				printTr();
				printRow($cdcoverTypeObj->getCoverTypeName());
				printRow($cdcoverTypeObj->getCoverTypeDescription());
				printEditRow($cdcoverTypeObj->getCoverTypeID(), $CURRENT_PAGE);
				printDeleteRow($cdcoverTypeObj->getCoverTypeID(), $CURRENT_PAGE, "Delete covertype?");
				
				printTr(false);
				
			}
			printTableClose();
			
			echo "</div>";
			
			}
			
			/*
				Case Users			
			*/
			if ($CURRENT_PAGE == "users") { 
				require("forms/addUser.php");	
				
	   		echo "<div class=\"content\">";	
			$USERclass = new vcd_user();
			
			$users = $USERclass->getAllUsers();
			
			if (isset($_POST['save'])) {
				$data = array("",$_POST['name'],$_POST['description']);
				$obj = new cdcoverTypeObj($data);
				$CTClass->addCoverType($obj);
				
			}
			
			$header = array("Full name","Username","Email","Group","Created","","","","");
			printTableOpen();
			printRowHeader($header);
			foreach ($users as $userObj) {

				printTr();
				printRow($userObj->getFullname());
				printRow($userObj->getUsername());
				printRow($userObj->getEmail());	
				printRow($userObj->getRoleName());
				printRow(substr($userObj->getDateCreated(),0,10));
				
				printCustomRow($userObj->getUserID(),$CURRENT_PAGE,"icon_user_purple","Change user role","changeRole");
				printCustomRow($userObj->getUserID(),$CURRENT_PAGE,"icon_change_pass","Reset password","changePassword");
				printCustomRow($userObj->getUserID(),$CURRENT_PAGE,"icon_del","Delete user","deleteUser");
				printCustomRow($userObj->getUserID(),$CURRENT_PAGE,"icon_xml","Export user data","exportUser");
					
				printTr(false);
				
			}
			printTableClose();
			
			
			echo "</div>";
			
			}
			
			
			
			
			
			
			/*
				Languages
			*/
			if ($CURRENT_PAGE == "languages") { 
			$langCLASS = new language(true);
			if (isset($_SESSION['vcdlang'])) {
				$language->load($_SESSION['vcdlang']);
			}
			
			$updateMode = false;
			$langSelectionUpdate = false;
			
			// Check if file is being updated
			if (isset($_POST['Update'])) {
				$updateMode = true;
				if (isset($_POST['langfile'])) {
					// Edit in raw mode
					$contents = $_POST['langfile'];
					print_r($contents);
					exit();
				} else {
					// Safe mode edit ..
					$valueArr = $_POST['values'];
					try {
						$langCLASS->updateLangueArray($_GET['recordID'], $valueArr);
					} catch (Exception $ex) {
						VCDException::display($ex);
					}
				}
			}
			
			
			
			$SETTINGSClass = new vcd_settings();
			
			if (isset($_POST['langupdate'])) {
				$newLangArr = $_POST['languages'];
				$strLangs = implode("#", $newLangArr);
				
				if (!is_array($newLangArr) || sizeof($newLangArr) == 0) {
					VCDException::display('At least one language must be selected.');
					print "<script>location.href='./?page=languages'</script>";
					exit();
				}
				
				// Update the language metadataObj in DB
				$arr = $SETTINGSClass->getMetadata(0, 0, 'languages');
				$metaObj = $arr[0];
				if ($metaObj instanceof metadataObj ) {
					$metaObj->setMetadataValue($strLangs);
					$SETTINGSClass->updateMetadata($metaObj);
					$langSelectionUpdate = true;
				} 
			}
			
			
			require("forms/edit_language.php");	
	   		echo "<div class=\"content\">";	
			
			$arrLanginfo = $langCLASS->getAvailableLanguages();
			
			$languages = $arrLanginfo['languages'];
	   		$tags = $arrLanginfo['tags'];
	   		$files = $arrLanginfo['files'];
	   		$i = 0;	
			
	   		
	   		// Check for language metadata
	   		$metaObj = null;
	   		$metaArr = $SETTINGSClass->getMetadata(0, 0, 'languages');
	   		if (is_array($metaArr) && sizeof($metaArr) == 1) { $metaObj = $metaArr[0]; }
	   		
	   		if (!$metaObj instanceof metadataObj ) {
	   			// Create the object for the first time ...
	   			$metaObj = new metadataObj(array('', 0, 0, 'languages', implode("#", $tags)));
	   			$SETTINGSClass->addMetadata($metaObj);
	   		} 
	   		
	   		$ArrAllowedLangs = explode("#", $metaObj->getMetadataValue());
	   		
	   		
	   		
	   		if (!$updateMode) {
	   			print "<form method=\"post\" name=\"available\">";	
	   		}
	   		
			$header = array("Language name","Tag","Filename","Available", "");
			printTableOpen('100%', 0, 0);
			printRowHeader($header);
			
			for ($i = 0; $i < sizeof($languages); $i++) {
				printTr();
				printRow($languages[$i]);
				printRow($tags[$i]);
				printRow($files[$i]);
				
				if (in_array($tags[$i], $ArrAllowedLangs)) {
					printRow("<input type=\"checkbox\" name=\"languages[]\" value=\"{$tags[$i]}\" checked=\"checked\">");
				} else {
					printRow("<input type=\"checkbox\" name=\"languages[]\" value=\"{$tags[$i]}\">");
				}
				
				
				printRow("");
				
				printEditRow($i, $CURRENT_PAGE);
				printTr(false);
			}
			
			printTableClose();
			
			if (!$updateMode) {
				
				$updateMessage = "";
				if ($langSelectionUpdate) {
					$updateMessage = "<span id=\"langmessage\" style=\"color:red\">(Selection updated)&nbsp;&nbsp;</span>";
					print "<script>setTimeout(\"toggle('langmessage')\",3000);</script>";
				}
				
				print "<div align=\"right\">{$updateMessage}<input type=\"submit\" value=\"Update\" name=\"langupdate\" class=\"button\"></div></form>";
			}
			
			
			echo "</div>";
			
			}
			
			
			
			
			
			
			
			
			
			/*
				Case Web Settings			
			*/
			if ($CURRENT_PAGE == "settings") { 
			$SETTINGSclass = new vcd_settings();
			require("forms/addSettings.php");	
			
			
			/****************/
			/* Add Settings */
			if (isset($_POST['save'])) {
				if (!isset($_POST['protect']))
					$protected = 0;
				else 
					$protected = 1;
					
				$data = array("",$_POST['key'],$_POST['value'],$_POST['description'],$protected, "");
				$obj = new settingsObj($data);
				$SETTINGSclass->addSettings($obj);
			}
			/****************/
			/* Update Settings */
			elseif (isset($_POST['update'])) {
				
				
				$obj = $SETTINGSclass->getSettingsByID($_POST['id']);
				
				if (!$obj->isProtected()) {
					if (isset($_POST['protect'])) {
						$obj->setProtected(true);
					}
				}
									
				$obj->setValue($_POST['value']);
				$obj->setDescription($_POST['description']);
								
				$SETTINGSclass->updateSettings($obj);
				print "<script>location.href='./?page=".$CURRENT_PAGE."'</script>";
				exit();
			}
			
			
			/*****************/
				
				
			echo "<div class=\"content\">";	
						
			$settings = $SETTINGSclass->getAllSettings();
						
			$header = array("Key","Value","Description","Locked","","");
			printTableOpen();
			printRowHeader($header);
			foreach ($settings as $settingsObj) {
				printTr();
				
				printRow($settingsObj->getKey());
				
				if (strcmp($settingsObj->getType(), 'bool') ==0) {
					printRow((bool)$settingsObj->getValue());
				} else {
					printRow($settingsObj->getValue());
				}
				
				printRow($settingsObj->getDescription());
				printRow((bool)$settingsObj->isProtected());
				printEditRow($settingsObj->getID(), $CURRENT_PAGE);
				printDeleteRow($settingsObj->getID(), $CURRENT_PAGE, "Delete settings ?");
				
				printTr(false);
			}
			printTableClose();
			
			
			echo "</div>";
			
			}
			
			
			
			
				
			
			
			
			/*
				Case USER Roles	
			*/
			if ($CURRENT_PAGE == "roles") { 
			require("forms/addRole.php");	
			
			$USERclass = new vcd_user();
			
			/* Add Settings */
			if (isset($_POST['save'])) {
					
				$data = array("",$_POST['key'],$_POST['value'],$_POST['description'],$protected);
				
				
			}
			/*****************/
							
				
			echo "<div class=\"content\">";	
						
			$roles = $USERclass->getAllUserRoles();
			$defaultRoleObj = $USERclass->getDefaultRole();
			
						
			$header = array("Role name","Description","&nbsp;","&nbsp;");
			printTableOpen();
			printRowHeader($header);
			foreach ($roles as $userRolesObj) {
				
				printTr();
				printRow($userRolesObj->getRoleName());			
				printRow($userRolesObj->getRoleDescription());			
				
				if ($userRolesObj === $defaultRoleObj) { 
					printCustomRow($userRolesObj->getRoleID(), $CURRENT_PAGE, "../rssuser", "This is the default role", "void");
				} else {
					printCustomRow($userRolesObj->getRoleID(), $CURRENT_PAGE, "icon_user_purple", "Set as default role", "setDefaultRole");
				}
				
			
				printDeleteRow($userRolesObj->getRoleID(), $CURRENT_PAGE, "Delete role ?");
				printTr(false);
			}
			printTableClose();
			
			
			echo "</div>";
			
			}
			
			
			
			
			
			
			
			/*
				Case Source Sites
			*/
			if ($CURRENT_PAGE == "sites") { 
				
			$SETTINGSclass = new vcd_settings();
			require("forms/addSite.php");	
			
			/* Add Settings */
			if (isset($_POST['save'])) {
				$fetchable = 0;
				if (isset($_POST['isFetchable']))
					$fetchable = 1;
				
				$data = array("",$_POST['name'],$_POST['alias'],$_POST['homepage'],$_POST['command'],$fetchable);
				$sObj = new sourceSiteObj($data);
				$SETTINGSclass->addSourceSite($sObj);
				print "<script>location.href='./?page=".$CURRENT_PAGE."'</script>";
				exit();
		
			}
			/* Update Source site */
			elseif (isset($_POST['update'])) {
				$fetchable = 0;
				if (isset($_POST['isFetchable']))
					$fetchable = 1;
					
				$data = array($_POST['id'],$_POST['name'],$_POST['alias'],$_POST['homepage'],$_POST['command'],$fetchable);
				$obj = new sourceSiteObj($data);
				$SETTINGSclass->updateSourceSite($obj);
				print "<script>location.href='./?page=".$CURRENT_PAGE."'</script>";
				exit();
			}
			/****************/
				
			echo "<div class=\"content\">";	
						
			$sites = $SETTINGSclass->getSourceSites();
						
			$header = array("Site name","Alias", "Fetchable", "","");
			printTableOpen();
			printRowHeader($header);
			foreach ($sites as $sourceSiteObj) {
				printTr();

				$name = "<a href=\"".$sourceSiteObj->getHomepage()."\" target=\"_new\">".$sourceSiteObj->getName()."</a>";
				printRow($name);			
				printRow($sourceSiteObj->getAlias());
				printRow((bool)$sourceSiteObj->isFetchable());			
				printEditRow($sourceSiteObj->getsiteID(), $CURRENT_PAGE);
				printDeleteRow($sourceSiteObj->getsiteID(), $CURRENT_PAGE, "Delete site?");
				
				printTr(false);
			}
			printTableClose();
			unset($sites);
			
			echo "</div>";
			
			}
			
			
			
			
			
			
			
			
			/*
				Case Media Types
			*/
			if ($CURRENT_PAGE == "media_types") { 
						
			$SETTINGSclass = new vcd_settings();
			$mtypes = $SETTINGSclass->getAllMediatypes();
			
			require("forms/addMediaType.php");	
			
			/* Add Media Type */
			if (isset($_POST['save'])) {
				
				// Get the default DB NULL value
				//$parent = $SETTINGSclass->getSettingsByKey("DB_NULL");
				$parent = "NULL";
								
				if (strcmp($_POST['parent'],"null") != 0) {
					$parent = $_POST['parent'];
				}
				
				$data = array("",$_POST['name'], $parent, $_POST['description']);
				$obj = new mediaTypeObj($data);
				$SETTINGSclass->addMediaType($obj);	
				
				// Update the new RecordSet
				$mtypes = $SETTINGSclass->getAllMediatypes();
				unset($data);
				
			}
			/*****************/
			/* Update Media Type */
			elseif (isset($_POST['update'])) {
				$obj = $SETTINGSclass->getMediaTypeByID($_POST['id']);
				$obj->setDescription($_POST['description']);
				$SETTINGSclass->updateMediaType($obj);
				print "<script>location.href='./?page=".$CURRENT_PAGE."'</script>";
				exit();
			}
			
			
			/*****************/
			
			
			echo "<div class=\"content\">";	
						
			if (is_array($mtypes) && sizeof($mtypes) > 0) {
						
				$header = array("Type name", "Description", "");
				printTableOpen();
				printRowHeader($header);
				foreach ($mtypes as $mediaTypeObj) {
					printTr();
					printRow($mediaTypeObj->getName());			
					//printRow($mediaTypeObj->getChildrenCount());			
					printRow($mediaTypeObj->getDescription());	
					printEditRow($mediaTypeObj->getmediaTypeID(), $CURRENT_PAGE);
					printDeleteRow($mediaTypeObj->getmediaTypeID(), $CURRENT_PAGE, "Delete mediatype?");
					printTr(false);
					
					// Printout each child	
					if ($mediaTypeObj->getChildrenCount() > 0) {
						foreach ($mediaTypeObj->getChildren() as $childObj) {
							printTr();
							printRow($childObj->getName(), "child");			
							//printRow("");			
							printRow($childObj->getDescription(),"child");	
							printEditRow($childObj->getmediaTypeID(), $CURRENT_PAGE);
							printDeleteRow($childObj->getmediaTypeID(), $CURRENT_PAGE, "Delete mediatype?");
							printTr(false);
						}
					}
					
					
				}
				printTableClose();
				unset($mtypes);
				
			} else {
				print "<strong>No media types available.</strong>";
			}
			
			echo "</div>";
			
			}
			
			
			
			
			
			
			/*
				Case Movie Categorie
			*/
			if ($CURRENT_PAGE == "categories") { 
			require("forms/addMovieCategorie.php");	
			
			$SETTINGSclass = new vcd_settings();
			
			/* Add Movie Categorie */
			if (isset($_POST['save'])) {
				$data = array("",$_POST['name']);
				$obj = new movieCategoryObj($data);
				$SETTINGSclass->addMovieCategory($obj);
				print "<script>location.href='./?page=".$CURRENT_PAGE."'</script>";
				exit();
		
			}
			/*****************/
				
				
			echo "<div class=\"content\">";	
						
			$categories = $SETTINGSclass->getAllMovieCategories();
						
			$header = array("Category name", "");
			printTableOpen();
			printRowHeader($header);
			foreach ($categories as $obj) {
				printTr();
			
				printRow($obj->getName());
				printDeleteRow($obj->getID(), $CURRENT_PAGE, "Delete category?");
				
				printTr(false);
			}
			printTableClose();
			unset($categories);
			
			echo "</div>";
			
			}
			
			
			
			
			
			
			
			
			
			
			/*
				Allowed Cover types
			*/
			if ($CURRENT_PAGE == "allowed_types") { 
			$SETTINGSclass = new vcd_settings();
			$CDclass = new vcd_cdcover();
			require("forms/addCoverToMedia.php");	
									
			/* Add Allowed Cover types */
			if (isset($_POST['save'])) {
				$coverTypeArr = split("#",$_POST['id_list']);
				
				if (sizeof($coverTypeArr) == 1 && strcmp($coverTypeArr[0],"") == 0)
					$coverTypeArr = array();
				
				$CDclass->addCoverTypesToMedia($_POST['media_id'], $coverTypeArr);
				print "<script>location.href='./?page=".$CURRENT_PAGE."'</script>";
				exit();
				
			}
			/*****************/
				
				
			echo "<div class=\"content\">";	
						
			$mtypes = $SETTINGSclass->getAllMediatypes();
						
			$header = array("Media Type", "Covers used","Cover description","");
			printTableOpen();
			printRowHeader($header);
			foreach ($mtypes as $obj) {
				printTr();
			
				printRow($obj->getName());
				printRow("");
				printRow("");
				printEditRow($obj->getmediaTypeID(), $CURRENT_PAGE);
				printTr(false);
				
					// Get allowed covers for this Media type
					$covertypes = $CDclass->getCDcoverTypesOnMediaType($obj->getmediaTypeID());
					foreach ($covertypes as $coverTypeObj) {
						printTr();
						printRow("");
						printRow($coverTypeObj->getCoverTypeName(),"child");
						printRow($coverTypeObj->getCoverTypeDescription(),"child");
						printRow("");
						printTr(false);
					}
				
			}
			printTableClose();
			unset($mtypes);
			unset($covertypes);
			
			echo "</div>";
			
			}
			
			
			
			
			
			/*
				Case User Properties
			*/
			if ($CURRENT_PAGE == "properties") { 
			
			$USERclass = new vcd_user();
			require("forms/addProperty.php");	
			
			
			
			/* Add User Properties */
			if (isset($_POST['save'])) {
				$data = array("",$_POST['name'], $_POST['description']);
				$obj = new userPropertiesObj($data);
				$USERclass->addProperty($obj);
				print "<script>location.href='./?page=".$CURRENT_PAGE."'</script>";
				exit();
			}
			/* Update Properties */
			elseif (isset($_POST['update'])) {
				$obj = $USERclass->getPropertyById($_POST['id']);
				$obj->setPropertyDescription($_POST['description']);
				$USERclass->updateProperty($obj);
				print "<script>location.href='./?page=".$CURRENT_PAGE."'</script>";
				exit();
			}
			/*****************/
				
				
			echo "<div class=\"content\">";	
						
			$properties = $USERclass->getAllProperties();
						
			$header = array("Property name", "Description", "", "");
			printTableOpen();
			printRowHeader($header);
			foreach ($properties as $obj) {
				printTr();
			
				printRow($obj->getpropertyName());
				printRow($obj->getpropertyDescription());
				
				printEditRow($obj->getpropertyID(), $CURRENT_PAGE);
				printDeleteRow($obj->getpropertyID(), $CURRENT_PAGE, "Delete property?");
				
				printTr(false);
			}
			printTableClose();
			unset($categories);
			
			echo "</div>";
			
			}
			
			
			/*
				Case Backup
			*/
			if ($CURRENT_PAGE == "backup") { 
				
				$header = array("Backup Type", "Download","Display");
				printTableOpen();
				printRowHeader($header);
				
				printTr();
				printRow("Get CORE data as XML (settings, users, mediatypes and so on)");
				printRow("<a href='export_xml.php'>Here</a>");
				printRow("<a href='export_xml.php?show'>Here</a>");
				printTr(false);
				
				printTr();
				printRow("Get VCD data as XML (movies, covers and so on)");
				printRow("<a href='export_xml.php'>Here</a>");
				printRow("<a href='export_xml.php?show'>Here</a>");
				printTr(false);
				
				printTr();
				printRow("Get SQL Dump");
				printRow("<a href='export_sql.php'>Here</a>");
				printRow("<a href='export_sql.php?show'>Here</a>");
				printTr(false);
							
				printTableClose();
			}
			
			
			
			
			
			/*
				Case Log
			*/
			if ($CURRENT_PAGE == "log") { 
				
				if (isset($_POST['update'])) {
					$logTypes = "";
					if (isset($_POST['logoptions'])) {
						$logTypes = implode("#", $_POST['logoptions']);
					}

					$metaObj = new metadataObj(array('',0,0,'logtypes', $logTypes));
					$SETTINGSclass->addMetadata($metaObj);
				}
				
				require_once('forms/log.php');				
			}
			
			
			
			
			/*
				Case View Log
			*/
			if ($CURRENT_PAGE == "viewlog") { 
				
				$arrLog = VCDLog::getLogEntries();
				$CLASSUser = new vcd_user();
				
				$arrAllUsers = $CLASSUser->getAllUsers();
				
				$header = array("Event", "Message", "User", "Date", "Remote IP");
				printTableOpen();
				printRowHeader($header);
				foreach ($arrLog as $obj) {
					
					$strUserName = "anonymous";
					foreach ($arrAllUsers as $userObj) {
						if ($userObj->getUserID() == $obj->getUserID()) {
							$strUserName = $userObj->getUsername();
							break;
						}
					}
					
					printTr();
			
					printRow(VCDLog::getLogTypeDescription($obj->getType())) ;
					printRow($obj->getMessage());
					printRow($strUserName);	
					printRow($obj->getDate());
					print "<td onmouseover=\"return escape('<iframe src=iptodns.php?ip={$obj->getIP()} width=150 height=22></iframe>')\">{$obj->getIP()}</td>";
				
					printTr(false);
				}
				printTableClose();
				
				unset($arrLog);
				unset($arrAllUsers);
				
			}
			
			
			/*
				Case Import
			*/
			if ($CURRENT_PAGE == "import") { 
				?>
				<form name="xmlsettings" method="post" action="import_xml.php" enctype="multipart/form-data">
				<table class="add">
				<tr>
					<td>Import settings XML file: </td>
					<td><input type="file" name="sxml" value="sxml" class="add"/>&nbsp; 
						<input class="add" type="submit" value="Process"/>
					</td>
				</tr>
				<tr>
					<td colspan="2"></td>
				</tr>
				</table>
				</form>
				
				
				<?
			}
			
			
			/*  Porn related Stuff */
			if ($CURRENT_PAGE == "pornstars") { 
			
			
			$PORNClass = new vcd_pornstar();
			$SETTINGSclass = new vcd_settings();
			
			/* Add pornstar */
			if (isset($_POST['save'])) {
				$data = array("",$_POST['name']);
				$obj = new movieCategoryObj($data);
				$SETTINGSclass->addMovieCategory($obj);
				print "<script>location.href='./?page=".$CURRENT_PAGE."'</script>";
				exit();
		
			}
			/*****************/
				
				
			echo "<div class=\"bigcontent\">";	
						
			$pornstars = $PORNClass->getAllPornstars();
						
			$header = array("Name", "");
			printTableOpen('96%');
			printRowHeader($header);
			foreach ($pornstars as $obj) {
				printTr();
			
				printRow($obj->getName());
				printDeleteRow($obj->getID(), $CURRENT_PAGE, "Delete category?");
				
				printTr(false);
			}
			printTableClose();
			unset($pornstars);
			
			echo "</div>";
			
			}
			
			
			
			/* 
				Case Porn categories
			*/
			
			if ($CURRENT_PAGE == "porncategories") { 
			require("forms/addPornCategory.php");	
			
			$PORNClass = new vcd_pornstar();
			$SETTINGSclass = new vcd_settings();
			
			/* Add Porn category */
			if (isset($_POST['save'])) {
				$data = array("",$_POST['name']);
				$obj = new porncategoryObj($data);
				$PORNClass->addAdultCategory($obj);
				print "<script>location.href='./?page=".$CURRENT_PAGE."'</script>";
				exit();
		
			}
			/*****************/
				
				
			echo "<div class=\"bigcontent\">";	
						
			$adultCats = $PORNClass->getSubCategories();
						
			$header = array("Name", "");
			printTableOpen('96%');
			printRowHeader($header);
			foreach ($adultCats as $obj) {
				printTr();
			
				printRow($obj->getName());
				printDeleteRow($obj->getID(), $CURRENT_PAGE, "Delete category?");
				
				printTr(false);
			}
			printTableClose();
			unset($adultCats);
			
			echo "</div>";
			
			}
			
			
			
			
			/* 
				Case Porn Studios
			*/
			
			if ($CURRENT_PAGE == "pornstudios") { 
			require("forms/addPornStudio.php");	
			
			$PORNClass = new vcd_pornstar();
			$SETTINGSclass = new vcd_settings();
			
			/* Add Porn studio */
			if (isset($_POST['save'])) {
				$data = array("",$_POST['name']);
				$obj = new studioObj($data);
				$PORNClass->addStudio($obj);
				print "<script>location.href='./?page=".$CURRENT_PAGE."'</script>";
				exit();
		
			}
			/*****************/
				
				
			echo "<div class=\"bigcontent\">";	
						
			$studios = $PORNClass->getAllStudios();
						
			$header = array("Name", "");
			printTableOpen('96%');
			printRowHeader($header);
			foreach ($studios as $obj) {
				printTr();
			
				printRow($obj->getName());
				printDeleteRow($obj->getID(), $CURRENT_PAGE, "Delete studio?");
				
				printTr(false);
			}
			printTableClose();
			unset($studios);
			
			echo "</div>";
			
			}
			
			
			
			
			
			if ($CURRENT_PAGE == "versioncheck") {
				checkVersion();
			}
			
			
			
			
			
			
			/*
				XML Feeds
			*/
			if ($CURRENT_PAGE == "xmlfeeds") { 
			
			$SETTINGSclass = new vcd_settings();
			require("forms/addFeed.php");	
			
			
			
			/* Add XML feed */
			if (isset($_POST['save'])) {
				if (isset($_POST['name']) && strlen($_POST['name']) > 0 
					&& isset($_POST['url']) && strlen($_POST['url']) > 0) {
					$SETTINGSclass->addRssfeed(0, $_POST['name'], $_POST['url']);
				}
				
				print "<script>location.href='./?page=".$CURRENT_PAGE."'</script>";
				exit();
			}
			/* Update XML feed */
			elseif (isset($_POST['update'])) {
				$SETTINGSclass->updateRssfeed($_POST['id'], $_POST['name'], $_POST['url']);
				print "<script>location.href='./?page=".$CURRENT_PAGE."'</script>";
				exit();
			}
			/*****************/
				
			
			if (isset($_GET['view'])) {
				$fid = $_GET['view'];
				$RSSClass = new lastRSS(); 
								
				// setup transparent cache
				$RSSClass->cache_dir = '../upload'; 
				$RSSClass->cache_time = 3600; // one hour
				
				$view_feed = $SETTINGSclass->getRssfeed($fid);
				ShowOneRSS($view_feed['url'], $RSSClass);

			}
			
			
			echo "<div class=\"content\">";	
				
			$arrFeeds = $SETTINGSclass->getRssFeedsByUserId(0);
			
						
			$header = array("Feed name", "", "", "");
			printTableOpen();
			printRowHeader($header);
			foreach ($arrFeeds as $item) {
				printTr();
			
				printRow($item['name']);
				
				printEditRow($item['id'], $CURRENT_PAGE);
				printDeleteRow($item['id'], $CURRENT_PAGE, "Delete XML feed?");
				printCustomRow($item['id'],$CURRENT_PAGE,"icon_xml","View feed","viewFeed");
				
				printTr(false);
			}
			printTableClose();
			unset($arrFeeds);
			
			echo "</div>";
			
			}
			
			
			
			
			
			
			
			
			elseif ($CURRENT_PAGE == '') {
				$serverInfo = $conn->getServerInfo();
				print "<strong>VCD-db</strong> v." . VCDDB_VERSION . " admin console.<br/>";
				print "Running on " .$serverInfo['description'];
				?>
				<p>
				Here you can edit the settings for the VCD-db.<br>
				Beware that some of the core settings for VCD-db can be changed <br>and doing so without
				knowing what you are doing can cause the application to function improperly.<br>
				
				<br>
				
				
				</p>
				<?
			}
			
			
			
	 ?>
	
	
	</td>
</tr>
</table>
<?php if (strcmp($WORKING_MODE, "edit") == 0) {
	print "<script>showLayer();</script>";
}

if ($CURRENT_PAGE == "viewlog") {
?>
<script language="JavaScript" type="text/javascript" src="../includes/js/wz_tooltip.js"></script> 
<?
}

?>
</body>
</html>