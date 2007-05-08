<?php
	define('BASE', substr(dirname(__FILE__), 0, strrpos(dirname(__FILE__), DIRECTORY_SEPARATOR)));
	require_once(BASE .'/classes/includes.php');
	require_once(dirname(__FILE__).'/functions/adminPageFunctions.php');
		
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
	<title>VCD-db :: Admin console</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<link rel="stylesheet" href="../includes/css/admin.css" type="text/css" media="all" />
	<script src="../includes/js/admin.js" type="text/javascript"></script>
	<?php if(strcmp($CURRENT_PAGE,'pornstarsync')==0):?>
	<script type="text/javascript" src="../includes/js/json.js"></script>
	<script type="text/javascript" src="../includes/js/ajax.js"></script>
	<script type="text/javascript">
	<?php echo $ajaxClient->getJavaScript(); ?> 
	</script>
	<?php endif;?>
</head>
<body onload="window.focus()">

<table cellspacing=0 cellpadding=0 border=0 width="100%" align="center" id="admintable">
<tr>
	<td colspan="2" align="center" class="admintitle">VCD-db Admin web <? print str_replace("_"," ",$CURRENT_PAGE) ?></td>
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
		<li><a href="./?page=deletemovies">Delete movies</a></li>
		<li><a href="./?page=settings">Web settings</a></li>
		<li><a href="./?page=xmlfeeds">XML feeds</a></li>
		<li><hr style="height:1px;"/><a href="./?page=versioncheck">Check for new version</a></li>
		
		<? 
			if (SettingsServices::getSettingsByKey('SITE_ADULT')) { ?>
			<li><hr style="height:1px;"/><a href="./?page=pornstars">Pornstars</a></li>
			<li style="text-indent:10px"><a href="./?page=pornstarsync">Get updates</a></li>
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
          DB Type: <?= DB_TYPE ?><br>
          DB Host: <?= DB_HOST ?>
          
          
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
			
			require("forms/addCoverType.php");	
		
	   		echo "<div class=\"content\">";	
			
			/**************************/
			/* Add Cover type */
			if (isset($_POST['save'])) {
				$data = array("",$_POST['name'],$_POST['description']);
				$obj = new cdcoverTypeObj($data);
				CoverServices::addCoverType($obj);
				
			}
			/* Update coverType */
			elseif (isset($_POST['update'])) {
				$data = array($_POST['id'],$_POST['name'], $_POST['description']);
				$obj = new cdcoverTypeObj($data);
				CoverServices::updateCoverType($obj);
				print "<script>location.href='./?page=".$CURRENT_PAGE."'</script>";
				exit();
			}
			/**************************/
			
			$covertypes = CoverServices::getAllCoverTypes();
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
			
			if (isset($_POST['save'])) {
				$data = array("",$_POST['username'],md5($_POST['password']), $_POST['name'], $_POST['email'], null, null, null, null);
				$obj = new userObj($data);
				UserServices::addUser($obj);
			}
			
			$users = UserServices::getAllUsers();
			
			$header = array("Full name","Username","Email","Group","Created","","","","");
			printTableOpen();
			printRowHeader($header);
			foreach ($users as $userObj) {

				printTr();
				printRow($userObj->getFullname());
				printRow($userObj->getUsername());
				printRow($userObj->getEmail());	
				printRow($userObj->getRoleName());
				printRow(date("d.m.Y",strtotime($userObj->getDateCreated())));
				
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
			
			
			$langSelectionUpdate = false;
			
			if (isset($_POST['langupdate'])) {
		
				if (!isset($_POST['languages'])) {
					VCDException::display('At least one language must be selected.');
					print "<script>location.href='./?page=languages'</script>";
					exit();
				} else {
					// Update the language restrictions
					$newLangArr = $_POST['languages'];
					$l = new VCDLanguage();
					$l->setRestrictions($newLangArr);
					$langSelectionUpdate = true;
				}
			}
			
			
			$ClassLanguage = new VCDLanguage();
			$arrFiles = $ClassLanguage->getTranslationFiles();
			$arrAvailableLangs = array();
			if ($ClassLanguage->isRestricted()) {
				foreach ($ClassLanguage->getAllLanguages() as $langObj) {
					array_push($arrAvailableLangs, $langObj->getID());	
				}
			}
			
			print "<form method=\"post\" name=\"available\">";	
		
			
			if (sizeof($arrFiles) > 0 ) {
			
				$header = array("Language name","Identifier","Filename","Strings","Available");
				printTableOpen('100%', 0, 0);
				printRowHeader($header);
				foreach ($arrFiles as $item) {
					printTr();
					printRow($item['name']);
					printRow($item['id']);
					printRow($item['filename']);
					printRow($item['num']);
					
					if (!$ClassLanguage->isRestricted() || in_array($item['id'], $arrAvailableLangs)) {
						printRow("<input type=\"checkbox\" name=\"languages[]\" value=\"{$item['id']}\" checked=\"checked\"/>");
					} else {
						printRow("<input type=\"checkbox\" name=\"languages[]\" value=\"{$item['id']}\"/>");
					}
					
					
					printTr(false);	
				}
					
				
			} else {
				print "<b>No language files could be loaded!</b>";
			}
			
			
			printTableClose();
			$updateMessage = "";
			if ($langSelectionUpdate) {
				$updateMessage = "<span id=\"langmessage\" style=\"color:red\">(Selection updated)&nbsp;&nbsp;</span>";
				print "<script>setTimeout(\"toggle('langmessage')\",3000);</script>";
			}
			print "<div align=\"right\">{$updateMessage}<input type=\"submit\" value=\"Update\" name=\"langupdate\" class=\"button\"></div></form>";
			
			echo "</div>";
			
			}
			
			
			
			
			
			
			
			
			
			/*
				Case Web Settings			
			*/
			if ($CURRENT_PAGE == "settings") { 

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
				SettingsServices::addSettings($obj);
			}
			/****************/
			/* Update Settings */
			elseif (isset($_POST['update'])) {
				
				
				$obj = SettingsServices::getSettingsByID($_POST['id']);
				
				if (!$obj->isProtected()) {
					if (isset($_POST['protect'])) {
						$obj->setProtected(true);
					}
				}
									
				$obj->setValue($_POST['value']);
				$obj->setDescription($_POST['description']);
								
				SettingsServices::updateSettings($obj);
				print "<script>location.href='./?page=".$CURRENT_PAGE."'</script>";
				exit();
			}
			
			
			/*****************/
				
				
			echo "<div class=\"content\">";	
						
			$settings = SettingsServices::getAllSettings();
						
			$header = array("Description","Value","Locked","","");
			printTableOpen();
			printRowHeader($header);
			foreach ($settings as $settingsObj) {
				printTr();
				
				//printRow($settingsObj->getKey());
				printRow($settingsObj->getDescription());
				
				if (strcmp($settingsObj->getType(), 'bool') ==0) {
					printRow((bool)$settingsObj->getValue());
				} else {
					printRow($settingsObj->getValue());
				}
				
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
									
			/* Add Settings */
			if (isset($_POST['save'])) {
					
				$data = array("",$_POST['key'],$_POST['value'],$_POST['description'],$protected);
				
			}
			/*****************/
							
				
			echo "<div class=\"content\">";	
						
			$roles = UserServices::getAllUserRoles();
			$defaultRoleObj = UserServices::getDefaultRole();
			
						
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
			
			require("forms/addSite.php");	
			
			/* Add Settings */
			if (isset($_POST['save'])) {
				$fetchable = 0;
				if (isset($_POST['isFetchable']))
					$fetchable = 1;
				
				$data = array("",$_POST['name'],$_POST['alias'],$_POST['homepage'],$_POST['command'],$fetchable,$_POST['classname'], $_POST['imagename']);
				$sObj = new sourceSiteObj($data);
				SettingsServices::addSourceSite($sObj);
				print "<script>location.href='./?page=".$CURRENT_PAGE."'</script>";
				exit();
		
			}
			/* Update Source site */
			elseif (isset($_POST['update'])) {
				$fetchable = 0;
				if (isset($_POST['isFetchable']))
					$fetchable = 1;
					
				$data = array($_POST['id'],$_POST['name'],$_POST['alias'],$_POST['homepage'],$_POST['command'],$fetchable,$_POST['classname'], $_POST['imagename']);
				$obj = new sourceSiteObj($data);
				SettingsServices::updateSourceSite($obj);
				print "<script>location.href='./?page=".$CURRENT_PAGE."'</script>";
				exit();
			}
			/****************/
				
			echo "<div class=\"content\">";	
						
			$sites = SettingsServices::getSourceSites();
						
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
						
			$mtypes = SettingsServices::getAllMediatypes();
			
			require("forms/addMediaType.php");	
			
			/* Add Media Type */
			if (isset($_POST['save'])) {
				
				// Get the default DB NULL value
				$parent = "NULL";
								
				if (strcmp($_POST['parent'],"null") != 0) {
					$parent = $_POST['parent'];
				}
				
				$data = array("",$_POST['name'], $parent, $_POST['description']);
				$obj = new mediaTypeObj($data);
				SettingsServices::addMediaType($obj);	
				
				// Update the new RecordSet
				$mtypes = SettingsServices::getAllMediatypes();
				unset($data);
				
			}
			/*****************/
			/* Update Media Type */
			elseif (isset($_POST['update'])) {
				$obj = SettingsServices::getMediaTypeByID($_POST['id']);
				$obj->setDescription($_POST['description']);
				SettingsServices::updateMediaType($obj);
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
					printRow($mediaTypeObj->getDescription());	
					printEditRow($mediaTypeObj->getmediaTypeID(), $CURRENT_PAGE);
					printDeleteRow($mediaTypeObj->getmediaTypeID(), $CURRENT_PAGE, "Delete mediatype?");
					printTr(false);
					
					// Printout each child	
					if ($mediaTypeObj->getChildrenCount() > 0) {
						foreach ($mediaTypeObj->getChildren() as $childObj) {
							printTr();
							printRow($childObj->getName(), "child");			
							printRow($childObj->getDescription(),"child");	
							printEditRow($childObj->getmediaTypeID(), $CURRENT_PAGE);
							printDeleteRow($childObj->getmediaTypeID(), $CURRENT_PAGE, "Delete mediatype?");
							printTr(false);
						}
					}
					
					
				}
				printTableClose();
				
				
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
			
						
			/* Add Movie Categorie */
			if (isset($_POST['save'])) {
				$data = array("",$_POST['name']);
				$obj = new movieCategoryObj($data);
				SettingsServices::addMovieCategory($obj);
				print "<script>location.href='./?page=".$CURRENT_PAGE."'</script>";
				exit();
		
			}
			/*****************/
				
				
			echo "<div class=\"content\">";	
						
			$categories = SettingsServices::getAllMovieCategories();
						
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
			
			echo "</div>";
			
			}
			
			
			
			
			
			
			
			
			
			
			/*
				Allowed Cover types
			*/
			if ($CURRENT_PAGE == "allowed_types") { 
			
			require("forms/addCoverToMedia.php");	
									
			/* Add Allowed Cover types */
			if (isset($_POST['save'])) {
				$coverTypeArr = split("#",$_POST['id_list']);
				
				if (sizeof($coverTypeArr) == 1 && strcmp($coverTypeArr[0],"") == 0)
					$coverTypeArr = array();
				
				CoverServices::addCoverTypesToMedia($_POST['media_id'], $coverTypeArr);
				print "<script>location.href='./?page=".$CURRENT_PAGE."'</script>";
				exit();
				
			}
			/*****************/
				
				
			echo "<div class=\"content\">";	
						
			$mtypes = SettingsServices::getAllMediatypes();
						
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
					$covertypes = CoverServices::getCDcoverTypesOnMediaType($obj->getmediaTypeID());
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
			
			echo "</div>";
			
			}
			
			
			
			
			
			/*
				Case User Properties
			*/
			if ($CURRENT_PAGE == "properties") { 
			
			require("forms/addProperty.php");	
			
			
			
			/* Add User Properties */
			if (isset($_POST['save'])) {
				$data = array("",$_POST['name'], $_POST['description']);
				$obj = new userPropertiesObj($data);
				UserServices::addProperty($obj);
				print "<script>location.href='./?page=".$CURRENT_PAGE."'</script>";
				exit();
			}
			/* Update Properties */
			elseif (isset($_POST['update'])) {
				$obj = UserServices::getPropertyById($_POST['id']);
				$obj->setPropertyDescription($_POST['description']);
				UserServices::updateProperty($obj);
				print "<script>location.href='./?page=".$CURRENT_PAGE."'</script>";
				exit();
			}
			/*****************/
				
				
			echo "<div class=\"content\">";	
						
			$properties = UserServices::getAllProperties();
						
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
				
				$updated = false;				
				
				if (isset($_POST['update'])) {
					$logTypes = "";
					if (isset($_POST['logoptions'])) {
						$logTypes = implode("#", $_POST['logoptions']);
					}

					$metaObj = new metadataObj(array('',0,0,metadataTypeObj::SYS_LOGTYPES , $logTypes));
					SettingsServices::addMetadata($metaObj);
					$updated = true;
				}
				
				require_once('forms/log.php');				
			}
			
			
			
			
			/*
				Case View Log
			*/
			if ($CURRENT_PAGE == "viewlog") { 
				
				
				$numrows = 40;
				$offset = 0;
				if (isset($_GET['offset']) && is_numeric($_GET['offset'])) {
					$offset = $_GET['offset'];
				}
				
				$logfilter = null;
				if (isset($_GET['filter_id']) && is_numeric($_GET['filter_id']) && $_GET['filter_id'] > 0) {
					$logfilter = $_GET['filter_id'];
				}
				
				drawLogBar($numrows, $offset, $logfilter);
				
				$arrLog = VCDLog::getLogEntries($numrows, $offset, $logfilter);
								
				$arrAllUsers = UserServices::getAllUsers();
				
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
					printRow(date("d/m/Y h:i:s", strtotime($obj->getDate())), "", true);
										
					print "<td valign=top onmouseover=\"return escape('<iframe src=iptodns.php?ip={$obj->getIP()} width=250 height=22></iframe>')\">{$obj->getIP()}</td>";
				
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
									
				
			echo "<div class=\"bigcontent\">";	
						
			$pornstars = PornstarServices::getAllPornstars();
												
			$header = array("Name", "");
			printTableOpen('96%');
			printRowHeader($header);
			foreach ($pornstars as $obj) {
				printTr();
			
				printRow($obj->getName());
				printDeleteRow($obj->getID(), $CURRENT_PAGE, "Delete pornstar?");
				
				printTr(false);
			}
			printTableClose();
			
			echo "</div>";
			
			}
			
			
			
			/* 
				Case Porn categories
			*/
			
			if ($CURRENT_PAGE == "porncategories") { 
			require("forms/addPornCategory.php");	
			
			/* Add Porn category */
			if (isset($_POST['save'])) {
				$data = array("",$_POST['name']);
				$obj = new porncategoryObj($data);
				PornstarServices::addAdultCategory($obj);
				print "<script>location.href='./?page=".$CURRENT_PAGE."'</script>";
				exit();
		
			}
			/*****************/
				
				
			echo "<div class=\"bigcontent\">";	
						
			$adultCats = PornstarServices::getSubCategories();
						
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
			
			echo "</div>";
			
			}
			
			
			
			
			/* 
				Case Porn Studios
			*/
			
			if ($CURRENT_PAGE == "pornstudios") { 
			require("forms/addPornStudio.php");	
						
			/* Add Porn studio */
			if (isset($_POST['save'])) {
				$data = array("",$_POST['name']);
				$obj = new studioObj($data);
				PornstarServices::addStudio($obj);
				print "<script>location.href='./?page=".$CURRENT_PAGE."'</script>";
				exit();
		
			}
			/*****************/
				
				
			echo "<div class=\"bigcontent\">";	
						
			$studios = PornstarServices::getAllStudios();
						
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
			
			echo "</div>";
			
			}
			
			
			
			
			
			if ($CURRENT_PAGE == "versioncheck") {
				checkVersion();
			}
			
			if ($CURRENT_PAGE == "statistics") {
				
				print "<h1>Statistics</h1>";
			
					
				$arrFolders = array(
					'../upload/',
					'../upload/cache/',
					'../upload/covers/',
					'../upload/pornstars/',
					'../upload/screenshots/',
					'../upload/screenshots/albums/',
					'../upload/screenshots/generated/',
					'../upload/thumbnails/',
					'../upload/nfo/',
				);
				
				$arrTotals = array('files' => 0, 'size' => 0, 'folders' => 0);
				
				
				$header = array("Folder", "Files", "Size", "Sub-folders", "");
				printTableOpen();
				printRowHeader($header);
				
				foreach ($arrFolders as $folder) {
					printTr();

					$folderInfo = getFolderContent($folder);
					
					printRow($folderInfo['folder']);
					printRow($folderInfo['files']);
					printRow(human_file_size($folderInfo['size']));
					printRow($folderInfo['subfolders']);
					
					if (substr_count($folder, 'cache') > 0) {
						printDeleteRow("'0'", $CURRENT_PAGE, "Clean up the cache folder?");
					} else {
						printRow();
					}
					
					
					
					printTr(false);
					
					if (strcmp($folder, '../upload/screenshots/') != 0) {
						$arrTotals['files'] += $folderInfo['files'];
						$arrTotals['size'] += $folderInfo['size'];
						$arrTotals['folders'] += $folderInfo['subfolders'];	
					}
					
					
				}
				
				// Print the totals
				printTr();
				printRow('Total:', 'header');
				printRow($arrTotals['files']);
				printRow(human_file_size($arrTotals['size']));
				printRow($arrTotals['folders']);
				
				printRow();
				printTr(false);
					
				printTableClose();
				
				
			}
			
			
			if ($CURRENT_PAGE == 'pornstarsync') {

				require_once('forms/pornstarsync.php');
				
			}
			
			
			
			if ($CURRENT_PAGE == 'deletemovies') {

				require_once('forms/deleteMovies.php');
				
			}
			
			
			
			
			/*
				XML Feeds
			*/
			if ($CURRENT_PAGE == "xmlfeeds") { 
			
			require("forms/addFeed.php");	
			
			
			
			/* Add XML feed */
			if (isset($_POST['save'])) {
				if (isset($_POST['name']) && strlen($_POST['name']) > 0 
					&& isset($_POST['url']) && strlen($_POST['url']) > 0) {
					
					$rssObj = new rssObj();
					$rssObj->setName($_POST['name']);
					$rssObj->setFeedUrl($_POST['url']);
					if (isset($_POST['isxrated'])) {
						$rssObj->setAdult(true);
					}
					$rssObj->setOwnerId(0);
					SettingsServices::addRssfeed($rssObj);
				}
				
				print "<script>location.href='./?page=".$CURRENT_PAGE."'</script>";
				exit();
			}
			/* Update XML feed */
			elseif (isset($_POST['update'])) {
				$rssObj = SettingsServices::getRssfeed($_POST['id']);
				$rssObj->setName($_POST['name']);
				$rssObj->setFeedUrl($_POST['url']);
				$isadult = false;
				if (isset($_POST['isxrated'])) {
					$isadult = true;	
				}
				$rssObj->setAdult($isadult);
				SettingsServices::updateRssfeed($rssObj);
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
				
				$rssObj = SettingsServices::getRssfeed($fid);
				ShowOneRSS($rssObj->getFeedUrl(), $RSSClass);

			}
			
			
			echo "<div class=\"content\">";	
				
			$arrFeeds = SettingsServices::getRssFeedsByUserId(0);
			$adultimg = "<img src=\"../images/admin/icon_tits.gif\" border=\"0\" title=\"Adult content\" alt=\"Adult content\" align=\"absmiddle\">";
						
			$header = array("Feed name", "", "", "","");
			printTableOpen();
			printRowHeader($header);
			foreach ($arrFeeds as $rssObj) {
				printTr();
				printRow($rssObj->getName());
				
				if ($rssObj->isAdultFeed()) {
					printRow($adultimg, "", false, 5);
				} else {
					printRow("", "", false, 5);
				}
				
				printEditRow($rssObj->getId(), $CURRENT_PAGE);
				printDeleteRow($rssObj->getId(), $CURRENT_PAGE, "Delete XML feed?");
				printCustomRow($rssObj->getId(),$CURRENT_PAGE,"icon_xml","View feed","viewFeed");
				
				printTr(false);
			}
			printTableClose();
			
			echo "</div>";
			
			}
			
			
			
			
			
			
			
			
			elseif ($CURRENT_PAGE == '') {
				$serverInfo = VCDConnection::getServerInfo();
				print "<strong>VCD-db</strong> v." . VCDDB_VERSION . " admin console.<br/>";
				print "Running on " . DB_TYPE . " " . $serverInfo['version'] . " (" .$serverInfo['description'].")";
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