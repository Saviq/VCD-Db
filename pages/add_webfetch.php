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


if (strcmp($sTitle, "") != 0) {
	print "<h1>{$sourceObj->getName()} &gt;&gt; {$sTitle}</h1><br/>";
}



if (isset($_GET['fid'])) {
	// Get specific movie ..
	
	$id = $_GET['fid'];
	$fetchClass->fetchItemByID($id);
	$fetchClass->fetchValues();
	$fetchedObj = $fetchClass->getFetchedObject();
		
	$obj = &$fetchedObj;
	
	if ($obj instanceof imdbObj ) {
		
		/*
		// Grab the image
		if ($im->getPosterUrl() != ITEM_NOTFOUND) {
			$filename = VCDUtils::grabImage($im->getPosterUrl());
			$obj->setImage($filename);
		}
		*/
				
		// Grab the image
		$filename = VCDUtils::grabImage($obj->getImage());
		$obj->setImage($filename);
		require_once('pages/imdb_confirm.php');
	}
	
	
	
	
} else {
	// Display search results
	$fetchResults =	$fetchClass->Search($sTitle);
	if ($fetchResults == VCDFetch::SEARCH_EXACT) {
	 	$fetchClass->fetchItemByID();
	 	$fetchClass->fetchValues();
	 	$obj = $fetchClass->getFetchedObject();
	 	print_r($obj);
	} else {
	 	$fetchClass->showSearchResults();	
	}
}



?>