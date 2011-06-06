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
function printRow($rowdata = "", $cssClass = "", $nowrap=false, $width=null) {

	if (is_bool($rowdata)) {
		if ($rowdata)
			$rowdata = "True";
		 else
			$rowdata = "False";
	}

	if ($cssClass != "") {
		$cssClass = " class=".$cssClass;
	}
	
	$wrap = "";
	if ($nowrap) {
		$wrap = " nowrap=\"nowrap\"";
	}
	
	$tdwidth = "";
	if (!is_null($width)) {
		$tdwidth = " width=\"{$width}\"";
	}
	
	print "<td valign=top{$cssClass}{$wrap}{$tdwidth}>$rowdata</td>";
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


function executeTask($task_id) {
	if (is_numeric($task_id)) {
		
		switch ($task_id) {
			
			case 1:		// Clean up orphan movies
				
				break;
				
				
			case 2:		// Move covers from hd to db
				
				$affectedCovers = CoverServices::moveCoversToDatabase();
				$message = $task_id."|"."Moved {$affectedCovers} covers from hd to db.";
				VCDLog::addEntry(VCDLog::EVENT_TASKS, $message);
				break;
				
			case 3:		// Move covers from db to hd
			
				$affectedCovers = CoverServices::moveCoversToDisk();
				$message = $task_id."|"."Moved {$affectedCovers} covers from db to hd.";
				VCDLog::addEntry(VCDLog::EVENT_TASKS, $message);
				break;
				
			case 4:		// Clean up the cache folder
				$cacheFolder = BASE.DIRECTORY_SEPARATOR.CACHE_FOLDER;
				$it = new DirectoryIterator($cacheFolder);
				$filesToKeep = array('vcddb.db', 'index.html');
				$iDeletecounter = 0;
				foreach ($it as $file) {
					if (!$file->isDir()) {
						if (!in_array($file->getFileName(), $filesToKeep)) {
							$fileToDel = $cacheFolder.$file->getFileName();
							fs_unlink($fileToDel);
							$iDeletecounter++;
						}
					}
				}	
				$message = $task_id."|"."Deleted {$iDeletecounter} files from cache folder.";
				VCDLog::addEntry(VCDLog::EVENT_TASKS, $message);
				break;
				
				
			case 5:		// Fix and update duplicate entries
				$list = MovieServices::getDuplicationList();

				if (sizeof($list) > 0) {
					// Store the list in session for further processing
					$_SESSION['duplicatelist'] = $list;
					header("Location: ./?page=tools&task_id={$task_id}&do=process"); /* Redirect browser */
					exit();
				} else {
					$message = $task_id."|"."No duplicates found to process.";
					VCDLog::addEntry(VCDLog::EVENT_TASKS, $message);
				}
				
				break;
				
				
			case 6:		// Fix broken pornstar images
				
				$stars = PornstarServices::getAllPornstars();
				$updateCounter = 0;
				foreach ($stars as $star) {
					$img = $star->getImageName();
					if ($img != '') {
						$img= 'upload/pornstars/'.$img;
						$size = filesize($img);
						$kb = $size/1024;
						if ($kb < 3) {
						   $star->setImageName('');	
						   PornstarServices::updatePornstar($star);
						   fs_unlink($img);
						   $updateCounter++;
						}
					}
				}
				
				$message = $task_id."|"."Fixed {$updateCounter} pornstar images.";
				VCDLog::addEntry(VCDLog::EVENT_TASKS, $message);
				break;
				
				
			case 7:	// Create .htaccess file
				try {
					if (VCDConfig::createHTAccessFile()) {
						$message = $task_id."|"."Created .htaccess file for mod_rewrite.";
					} else {
						$message = $task_id."|"."Failed to create .htaccess file.";
					}
					VCDLog::addEntry(VCDLog::EVENT_TASKS, $message);
				} catch (Exception $ex) {
					VCDException::display($ex->getMessage(),true);
				}
				
			
		}
		
		
		header("Location: ./?page=tools&task_id={$task_id}"); /* Redirect browser */
		
	}
}

/**
 * Get the date when a specfic task was last ran
 *
 * @param int $task_id | The task ID 
 * @return string | The date or the message to return
 */
function getTaskStatus($task_id) {
	$strLastrun = "Never";

	$logItems = VCDLog::getLogEntries(null,null,VCDLog::EVENT_TASKS );
	foreach ($logItems as $item) {
		
		$arr = explode("|", $item->getMessage());
		if (sizeof($arr) == 2 && $arr[0]==$task_id) {
			$strLastrun = date("d/m/Y", strtotime($item->getDate()));
			break;
		}
				
	}
	
	return $strLastrun;
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
	if (!isset($recordID))
		return;

	switch ($recordType) {
		case 'settings';
			if (SettingsServices::deleteSettings($recordID))
				header("Location: ./?page=".$recordType.""); /* Redirect browser */
			else
				print "<script>history.back(-1)</script>";
		break;

		case 'roles';
			if (UserServices::deleteUserRole($recordID))
				header("Location: ./?page=".$recordType.""); /* Redirect browser */
			else
				print "<script>history.back(-1)</script>";
		break;

		case 'media_types';
			if (SettingsServices::deleteMediaType($recordID))
				header("Location: ./?page=".$recordType.""); /* Redirect browser */
			else
				print "<script>history.back(-1)</script>";
		break;

		case 'metadata_types';
			if (SettingsServices::deleteMetaDataType($recordID))
				header("Location: ./?page=".$recordType.""); /* Redirect browser */
			else
				print "<script>history.back(-1)</script>";
		break;
		
		case 'categories';
			if (SettingsServices::deleteMovieCategory($recordID))
				header("Location: ./?page=".$recordType.""); /* Redirect browser */
			else
				print "<script>history.back(-1)</script>";
		break;


		case 'cover_types';
			if (CoverServices::deleteCoverType($recordID))
				header("Location: ./?page=".$recordType.""); /* Redirect browser */
			else
				print "<script>history.back(-1)</script>";
		break;

		
		case 'deletemovies':
			
			$items = explode('|', $recordID);
			$movie_id = $items[0];
			$media_id = $items[1];
			$user_id = $items[2];
						
			MovieServices::deleteVcdFromUser($movie_id, $media_id, 'full', $user_id);
			header("Location: ./?page=".$recordType."&deleted"); /* Redirect browser */
			
			break;
	
		case 'deleteUser';

			// Check if user is trying to delete himself
			if ($recordID == VCDUtils::getUserID()) {
				VCDException::display('You cannot delete your own account!', true);
				return;
			}

			// check if all his data should be deleted as well
			if (isset($_GET['mode']) && strcmp($_GET['mode'], 'full') == 0) {

				// Add time limit since this can be very time consuming operation.
				@set_time_limit(300);

				if (UserServices::deleteUser($recordID, true))
					header("Location: ./?page=".$recordType."");
				else
					print "<script>history.back(-1)</script>";


			} else {
				if (UserServices::deleteUser($recordID, false))
					header("Location: ./?page=".$recordType.""); /* Redirect browser */
				else
					print "<script>history.back(-1)</script>";
			}
		break;

		case 'sites';
			if (SettingsServices::deleteSourceSite($recordID))
				header("Location: ./?page=".$recordType.""); /* Redirect browser */
			else
				print "<script>history.back(-1)</script>";
		break;

		case 'properties';
			if (UserServices::deleteProperty($recordID))
				header("Location: ./?page=".$recordType.""); /* Redirect browser */
			else
				print "<script>history.back(-1)</script>";
		break;

		case 'xmlfeeds';
			if (SettingsServices::delFeed($recordID))
				header("Location: ./?page=".$recordType.""); /* Redirect browser */
			else
				print "<script>history.back(-1)</script>";
		break;

		
		// Clear the cache directory
		case 'statistics':
			$cacheFolder = BASE.DIRECTORY_SEPARATOR.CACHE_FOLDER;
			
			$it = new DirectoryIterator($cacheFolder);
						
			$filesToKeep = array('vcddb.db', 'index.html');
			
			foreach ($it as $file) {
				if (!$file->isDir()) {
					
					if (!in_array($file->getFileName(), $filesToKeep)) {
						$fileToDel = $cacheFolder.$file->getFileName();
						fs_unlink($fileToDel);
					}
				}
			}
			header("Location: ./?page=".$recordType.""); /* Redirect browser */

			break;
		

		case 'log':
			VCDLog::clearLog();
			header("Location: ./?page=".$recordType.""); /* Redirect browser */
			break;

		case 'pornstars':
			if (PornstarServices::deletePornstar($recordID)) {
				header("Location: ./?page=".$recordType.""); /* Redirect browser */
			} else {
				print "<script>history.back(-1)</script>";
			}
			break;
			
		case 'porncategories':
			if (PornstarServices::deleteAdultCategory($recordID))
				header("Location: ./?page=".$recordType.""); /* Redirect browser */
			else
				print "<script>history.back(-1)</script>";
		break;


		case 'pornstudios':
			if (PornstarServices::deleteStudio($recordID))
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
		$do == "statistics" || $do == "roles" || $do == "log" || $do == "viewlog" || $do == "pornstarsync" ||
		$do == "deletemovies" || $do == "tools")  {
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
	UserServices::setDefaultRole($recordID);
	redirect('?page=roles');
}


/**
 * Print the Log UI
 *
 * @param int $numrows | Number of rows to show
 * @param int $offset | The offset to start at
 * @param string $logfilter | The log filter to use
 */
function drawLogBar($numrows, $offset, $logfilter) {
	print "<div align=\"right\" id=\"newObj\">";
	
	$backDisabled = "";
	if (!isset($_GET['offset']) || $offset<=0) {
		$backDisabled = "disabled=disabled";
	}
	
	
	
	$totalrows = VCDLog::getLogCount($logfilter);
	$rowsto = $offset + $numrows;
	$rowsback = $offset - $numrows;
	
	$frontDisabled = "";
	if ($rowsto >= $totalrows) {
		$rowsto = $totalrows;
		$frontDisabled = "disabled=disabled";
	}
	
	
	$msg = "<span style=\"font-weight:bold;font-size:10px\">(Showing records {$offset} to {$rowsto} of {$totalrows} records.)</span> &nbsp;&nbsp;&nbsp;";
	
	print $msg;
	
	$filters = "<option value=\"-1\">Show All</option>";
	for ($i=1; $i <= VCDLog::$numEventTypes; $i++) {
		$selected = "";
		if ($logfilter == $i) { $selected = " selected=\"selected\""; }
		$filters .= "<option value=\"{$i}\" {$selected}>".VCDLog::getLogTypeDescription($i)."</option>";
	}
	
	$filterdrop = "<select onchange=\"setLogFilter()\" id=\"filter\" name=\"filter\" title=\"Filter By ..\">{$filters}</select>";
	print $filterdrop;
	
	print "&nbsp;";
	
	$jsBack = "./?page=viewlog&offset=".$rowsback."&filter_id=".$logfilter;
	$btnBack = "<input type=\"button\" value=\"&lt;&lt;\" title=\"Previous entries\" {$backDisabled} onclick=\"location.href='{$jsBack}'\">";
	
	$jsForward = "./?page=viewlog&offset=".$rowsto."&filter_id=".$logfilter;
	$btnForward = "<input type=\"button\" value=\"&gt;&gt;\"  title=\"Next entries\" {$frontDisabled} onclick=\"location.href='{$jsForward}'\">";
	
	print $btnBack;
	print $btnForward;
	
	
	print "</div>";

}


/**
 * Iterate through the upload/ folders and display a summary of it's content
 *
 * @param string $folder | The base folder to start at
 * @return array | Assoc array containg the data about the folder
 */
function getFolderContent($folder) {
	
	$info = array('folder' => str_replace('../', '', $folder), 'files' => 0, 'size' => 0, 'subfolders' => 0);

	if (strcmp($info['folder'], 'upload/') == 0) {
		$base = true;
	} else {
		$base = false;
		if (substr_count($folder, 'albums') == 1 || substr_count($folder, 'generated') == 1) {
			$info['folder'] = '&nbsp;&nbsp;&nbsp; - ' . $info['folder'];
		} else {
			$info['folder'] = '&nbsp; - ' . $info['folder'];
		}
	}
	
	
	$files = 0;
	$subfolders = 0;
	$size = 0;
	
	$it = new DirectoryIterator($folder);
	$arrSubFolders = array();
	
	foreach ($it as $file) {
		if (!$file->isDir()) {
			$files++;
			$size += $file->getSize();
		} else if ($file->isDir() && !$file->isDot()) {
			array_push($arrSubFolders, $file->getPathname());
			$subfolders++;
		}
	}
	
	if (!$base) {
		while (sizeof($arrSubFolders) > 0) {
			$it = new DirectoryIterator(array_pop($arrSubFolders));
			foreach($it as $file) {
				if (!$file->isDir()) {
					$files++;
					$size += $file->getSize();
				} else if ($file->isDir() && !$file->isDot()) {
					array_push($arrSubFolders, $file->getPathname());
					$subfolders++;
				}
			}
		}
	}
	
	
	$info['files'] = $files;
	$info['size'] = $size;
	$info['subfolders'] = $subfolders;
	
	
	
	
	return $info;
	
}


?>