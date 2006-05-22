<?
$sTitle = "";
$sSource = "";
if (isset($_POST['searchTitle'])) {
	$sTitle = $_POST['searchTitle'];
}

if (isset($_POST['fetchsite'])) {
	$sSource = $_POST['fetchsite'];
} elseif(isset($_GET['site'])) {
	$sSource = $_GET['site'];
} else {
	VCDException::display("Malformed Url.", true);
	exit();
}


// Dynamically load the correct fetch class
$SettingsClass = VCDClassFactory::getInstance('vcd_settings');
$sourceObj = $SettingsClass->getSourceSiteByAlias($sSource);
if (is_null($sourceObj)) {
	VCDException::display("Malformed Url.", true);
	exit();
}

$className = $sourceObj->getClassName();
$fetchClass = VCDClassFactory::loadClass($className);
if (is_null($fetchClass)) {
	VCDException::display("Class {$className} could not be loaded.", true);
}

// Save the current fetch class in use for next time user fetches movie
$metaDefaultClass = $SettingsClass->getMetadata(0,VCDUtils::getUserID(), metadataTypeObj::SYS_LASTFETCH);
if (!($metaDefaultClass instanceof metadataObj && strcmp($metaDefaultClass->getMetadataValue(), $sSource)==0)) {
	// Default class changed or not found .. add "last used class" to database
	$metaLastUsedClass = new metadataObj(array('',0,VCDUtils::getUserID(),metadataTypeObj::SYS_LASTFETCH,$sSource));
	$SettingsClass->addMetadata(array($metaLastUsedClass));
}




if (strcmp($sTitle, "") != 0) {
	print "<h1>{$sourceObj->getName()} &gt;&gt; {$sTitle}</h1><br/>";
}



if (isset($_GET['fid'])) {
	// Get specific movie ..
	
	$id = $_GET['fid'];
	$fetchClass->fetchItemByID($id);
	$fetchClass->fetchValues();
	$obj = $fetchClass->getFetchedObject();
	$obj->setSourceSite($sourceObj->getsiteID());
	
	displayFetchedObject($obj);
	
	
	
	
} else {
	// Display search results
	$fetchResults =	$fetchClass->Search($sTitle);
	if ($fetchResults == VCDFetch::SEARCH_EXACT) {
	 	$fetchClass->fetchItemByID();
	 	$fetchClass->fetchValues();
	 	$obj = $fetchClass->getFetchedObject();
	 	displayFetchedObject($obj);
	} else {
	 	$fetchClass->showSearchResults();	
	}
}


function displayFetchedObject($fetchedObj) {
	try {
		if (!$fetchedObj instanceof fetchedObj ) {
			throw new Exception("Invalid fetched object.");
		}
		
		
		// Generic Fetched Object actions ..
		if (strcmp($fetchedObj->getImage(), "") != 0) {
			$filename = VCDUtils::grabImage($fetchedObj->getImage());
			$fetchedObj->setImage($filename);	
		}
		
		
		
		if ($fetchedObj instanceof imdbObj ) {
			
			require_once('pages/confirm_movie.php');
		
		
		} elseif ($fetchedObj instanceof adultObj ) {
		
			
			require_once('pages/confirm_adult.php');
			
			/*
			print "<pre>";
			print_r($fetchedObj)	;
			print "</pre>";
			*/
				
		}
		
		
	} catch (Exception $ex) {
		VCDException::display($ex, true);
	}
}


?>