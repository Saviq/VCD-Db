<?php

/**
 * Print HTML code for table start tags
 *
 * @param string $width
 * @param int $cellpadding
 * @param int $cellspacing
 */
function printTableOpen($width = "100%", $cellpadding = 1, $cellspacing = 1) {
	print "<table cellspacing=\"{$cellspacing}\" cellpadding=\"{$cellpadding}\" border=\"0\" class=\"datatable\" width=\"".$width."%\">";
}


/**
 * Print HTML code for closing table
 *
 */
function printTableClose() {
	print "</table>";
}

/**
 * Print HTML code for table tr tag
 *
 * @param bool $open open or close the tr
 * @param bool $hover
 */
function printTr($open = true, $hover = true) {
	if ($open) {
		$js = "onMouseOver=\"trOn(this)\" onMouseOut=\"trOff(this)\"";
		if ($hover)
			print "<tr $js>";
		else 
			print "<tr>";
			
	} else {
		print "</tr>";
	}
}

/**
 * Create a HTML table row header
 *
 * @param array $arrHeader
 */
function printRowHeader($arrHeader) {
	if (is_array($arrHeader)) {
		printTr(true, false);
		foreach ($arrHeader as $item) {
			printRow($item, "header");
		}
		printTr(false);
	}
}

/**
 * Print HTML table row
 *
 * @param string $rowdata
 * @param string $cssClass
 */
function printRow($rowdata = "", $cssClass = "") {
	
	if (is_bool($rowdata)) {
		if ($rowdata) 
			$rowdata = "True";
		 else 
			$rowdata = "False";
	}
	
	if ($cssClass != "") {
		$cssClass = " class=".$cssClass;
	}
	print "<td valign=top$cssClass>$rowdata</td>";
}


/**
 * Print HTML delete row
 *
 * @param int $recordID
 * @param string $recordType
 * @param string $warningCaption
 */
function printDeleteRow($recordID, $recordType, $warningCaption ) {
	print "<td width=5><img src=\"../images/admin/icon_del.gif\" border=0 title=\"Delete record\" onClick=\"deleteRecord($recordID,'$recordType','$warningCaption')\"></td>";
}

/**
 * Print HTML edit row
 *
 * @param int $recordID
 * @param string $recordType
 */
function printEditRow($recordID, $recordType) {
	print "<td width=5><img src=\"../images/admin/icon_edit.gif\" border=0 hspace=3 title=\"Edit record\" onClick=\"editRecord($recordID,'$recordType')\"></td>";
}

/**
 * Print a custom table cell.
 *
 * @param int $recordID
 * @param string $recordType
 * @param string $image
 * @param string $alt_text
 * @param string $jsfunction
 */
function printCustomRow($recordID, $recordType, $image, $alt_text, $jsfunction) {
	print "<td><img src=\"../images/admin/".$image.".gif\" border=0 hspace=3 title=\"".$alt_text."\" onClick=\"".$jsfunction."($recordID,'$jsfunction')\"></td>";	
}

/**
 * Create a HTML drop down control
 *
 * @param array $objArr
 * @param string $selectName
 * @param string $firstIndex
 * @param string $cssClass
 * @param string $selectedIndex
 */
function createDropDown($objArr, $selectName, $firstIndex = "", $cssClass = "",$selectedIndex = "") {
	if (is_array($objArr)) {
		
		if ($cssClass != "") {
			$cssClass = "class=\"$cssClass\"";
		}
		
	
		
		print "<select name=\"".$selectName."\" size=\"1\" ".$cssClass.">";
		if ($firstIndex != "") {
			print "<option value=\"null\">".$firstIndex."</option>";
		}
		
		foreach ($objArr as $obj) {
			$data = $obj->getList();
			if (strcmp($data['id'],$selectedIndex) == 0) {
				print "<option value=\"".$data['id']."\" selected>".$data['name']."</option>";
			} else {
				print "<option value=\"".$data['id']."\">".$data['name']."</option>";
			}
			
		}
		
		print "</select>";	
	}
}


/**
 * Add slashes to the current string
 *
 * @param string $item
 */
function cleanItem(&$item) {
	$item = addslashes($item);
}

/**
 * Delete record.
 * The action depends on the recordType
 *
 * @param int $recordID
 * @param string $recordType
 */
function deleteRecord($recordID, $recordType) {
	if (!is_numeric($recordID))
		return;
	
	switch ($recordType) {
		case 'settings';
			$CLASSsettings = new vcd_settings();
			if ($CLASSsettings->deleteSettings($recordID))
				header("Location: ./?page=".$recordType.""); /* Redirect browser */
			else 
				print "<script>history.back(-1)</script>";
		break;
		
		case 'roles';
			$USERClass = new vcd_user();
			if ($USERClass->deleteUserRole($recordID))
				header("Location: ./?page=".$recordType.""); /* Redirect browser */
			else 
				print "<script>history.back(-1)</script>";
		break;
		
		case 'media_types';
			$SETTINGSclass = new vcd_settings();
			if ($SETTINGSclass->deleteMediaType($recordID))
				header("Location: ./?page=".$recordType.""); /* Redirect browser */
			else 
				print "<script>history.back(-1)</script>";
		break;
		
		case 'categories';
			$SETTINGSclass = new vcd_settings();
			if ($SETTINGSclass->deleteMovieCategory($recordID))
				header("Location: ./?page=".$recordType.""); /* Redirect browser */
			else 
				print "<script>history.back(-1)</script>";
		break;
		
		
		case 'cover_types';
			$COVERSclass = new vcd_cdcover();
			if ($COVERSclass->deleteCoverType($recordID))
				header("Location: ./?page=".$recordType.""); /* Redirect browser */
			else 
				print "<script>history.back(-1)</script>";
		break;
		
		case 'users';
			$USERClass = new vcd_user();
			if ($USERClass->deleteUser($recordID))
				header("Location: ./?page=".$recordType.""); /* Redirect browser */
			else 
				print "<script>history.back(-1)</script>";
		break;
		
		case 'deleteUser';
			
			// Check if user is trying to delete himself
			if ($recordID == VCDUtils::getUserID()) {
				VCDException::display('You cannot delete your own account!', true);
				return;
			}
		
			$USERClass = new vcd_user();
						
			// check if all his data should be deleted as well
			if (isset($_GET['mode']) && strcmp($_GET['mode'], 'full') == 0) {
				
				
				if ($USERClass->deleteUser($recordID, true))
					header("Location: ./?page=".$recordType.""); 
				else 
					print "<script>history.back(-1)</script>";	

				
			} else {
				if ($USERClass->deleteUser($recordID, false))
					header("Location: ./?page=".$recordType.""); /* Redirect browser */
				else 
					print "<script>history.back(-1)</script>";	
			}
			
			
			
			
		break;
		
		case 'sites';
			$SETTINGSclass = new vcd_settings();
			if ($SETTINGSclass->deleteSourceSite($recordID))
				header("Location: ./?page=".$recordType.""); /* Redirect browser */
			else 
				print "<script>history.back(-1)</script>";
		break;
		
		case 'properties';
			$USERClass = new vcd_user();
			if ($USERClass->deleteProperty($recordID))
				header("Location: ./?page=".$recordType.""); /* Redirect browser */
			else 
				print "<script>history.back(-1)</script>";
		break;
		
		case 'xmlfeeds';
			$SETTINGSClass = new vcd_settings();
			if ($SETTINGSClass->delFeed($recordID))
				header("Location: ./?page=".$recordType.""); /* Redirect browser */
			else 
				print "<script>history.back(-1)</script>";
		break;
		
		
		case 'log':
			VCDLog::clearLog();
			header("Location: ./?page=".$recordType.""); /* Redirect browser */
			break;
		
		
		case 'porncategories':
			$PORNClass = new vcd_pornstar();
			if ($PORNClass->deleteAdultCategory($recordID)) 
				header("Location: ./?page=".$recordType.""); /* Redirect browser */
			else 
				print "<script>history.back(-1)</script>";
		break;
		
		
		case 'pornstudios':
			$PORNClass = new vcd_pornstar();
			if ($PORNClass->deleteStudio($recordID))
				header("Location: ./?page=".$recordType.""); /* Redirect browser */
			else 
				print "<script>history.back(-1)</script>";
			
		break;
		
			
	
	}
		
}

/**
 * Refresh the parent webpage and close the child window.
 *
 */
function refreshAndClose() {
	print "<script language=\"JavaScript\">";
	print "window.opener.location.reload();";
	print "window.close();";
	print "</script>";
}

/**
 * Format datestamp
 *
 * @param date $datestamp
 * @param string $format
 * @return date
 */
function getADODBdate($datestamp, $format="d-m-Y") {
	return date ($format, mktime (0,0,0, getADODBdatePart($datestamp, "month"),getADODBdatePart($datestamp, "day"),getADODBdatePart($datestamp, "year")));

}

/**
 * Format datepart of an datestamp
 *
 * @param date $datestamp
 * @param string $format
 * @return string
 */
function getADODBdatePart($datestamp, $format) {
	$datestring = "";
	if (strcmp($format, "day") == 0) {
		$datestring =  substr($datestamp ,0 ,2);
	}
	elseif (strcmp($format, "month") == 0) {
		$datestring =  substr($datestamp ,3 ,5);
	}
	elseif (strcmp($format, "year") == 0) {
		$datestring =  substr($datestamp ,6 ,10);
	}
	
	return $datestring;
}

/**
 * Check if the "Add Record" button should be shown
 *
 * @param string $do
 * @return bool
 */
function showAddRecord($do) {

	if ($do == "versioncheck" || $do == "statistics" || $do == "backup" || $do == "import" || $do == "" || 
		$do == "statistics" || $do == "roles" || $do == "log" || $do == "viewlog")  {
		return false;
	} 
	
	return true;
	
}

/**
 * Check for new version of VCD-db.
 * Connects to the master server (vcddb.konni.com) and reads the current latest version.
 *
 */
function checkVersion() {
	print "<b>Checking for new version .....</b>";
	
	// Flush errors ..
	error_reporting(0);
	$home = "http://vcddb.konni.com/vcddbversion.xml";
	$xml = simplexml_load_file($home);
	
	if ($xml && isset($xml->error)) {
		print $xml->error;
		return;
	}
	if (!$xml) {
		print "<p>Could not retrive latest version information, try again later.</p>";
		return;
	} 
	
	$application = $xml->application;
    $version = $xml->current_version;
    
    print "<p>Application: " . $application . "<br>";
    print "Installed version => " . VCDDB_VERSION . "<br>";
    print "Latest version => " . $version . "<br><br>";
    
    if (strcmp(VCDDB_VERSION, $version) == 0) {
    	print "<b>You have latest version installed.</b>";
    } else {
    	print "<b>New version available, check it out => <a href=\"http://vcddb.konni.com\" target=\"new\">http://vcddb.konni.com</a></b>";
    }
    
    print "</p>";
    
    
    
    
    // Reset error reporting
	error_reporting(ini_get('error_reporting'));
}

/**
 * Export users movie list as a XML file.
 *
 * @param int $user_id
 */
function exportUserXML($user_id) {
	
	$CLASSVcd = VCDClassFactory::getInstance("vcd_movie");
	$CLASSUSer = VCDClassFactory::getInstance('vcd_user');
	$usr = $CLASSUSer->getUserByID($user_id);
	if (!$usr instanceof userObj ) {
		VCDException::display('Invalid user selection', true);
		return;
	}
	
	$arrMovies = $CLASSVcd->getAllVcdByUserId($user_id, false);
	
	
	if (sizeof($arrMovies) == 0) {
		VCDException::display('User has no movies', true);
		return;
	}
	
	$filename = "vcddb_export_" . $usr->getUserName() . ".xml";
	
	header("Content-type: application/xml");
	header("Content-Disposition: attachment; filename=".$filename."");
	$xml = "<?xml version=\"1.0\" encoding=\"iso-8859-1\" ?>";
	$xml .= "<vcdmovies>";
	
	
	foreach ($arrMovies as $vcdObj) {
		$xml .= $vcdObj->toXML();
	}
	
	$xml .= "</vcdmovies>";
	unset($arrMovies);
	print $xml;
	exit();
	
}


/**
 * Set a new default role
 *
 * @param int $recordID
 */
function setDefaultRole($recordID) {
	$CLASSUser = VCDClassFactory::getInstance('vcd_user');
	$CLASSUser->setDefaultRole($recordID);
	redirect('?page=roles');
}

?>