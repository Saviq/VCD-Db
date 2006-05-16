<?
$sTitle = "";
$sSource = "";
if (isset($_POST['searchTitle'])) {
	$sTitle = $_POST['searchTitle'];
}

if (isset($_POST['fetchsite'])) {
	$sSource= $_POST['fetchsite'];
}




if (isset($_GET['fid'])) {
	// Get specific movie ..
	
	$id = $_GET['fid'];
	
	
	
} else {
	// Display search results
	$SettingsClass = VCDClassFactory::getInstance('vcd_settings');
	$sourceObj = $SettingsClass->getSourceSiteByAlias($sSource);
	$className = $sourceObj->getClassName();
	
	$fetchClass = VCDClassFactory::loadClass($className);
	if (is_null($fetchClass)) {
		VCDException::display("Class {$className} could not be loaded.", true);
	}
	
	
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