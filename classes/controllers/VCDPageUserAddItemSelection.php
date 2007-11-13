<?php
/**
 * VCD-db - a web based VCD/DVD Catalog system
 * Copyright (C) 2003-2007 Konni - konni.com
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 * 
 * @author  Hákon Birgisson <konni@konni.com>
 * @package Kernel
 * @subpackage Controller
 * @version $Id: VCDPageUserAddItemSelection.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php
class VCDPageUserAddItemSelection extends VCDBasePage {
	
	public function __construct(_VCDPageNode $node) {

		parent::__construct($node);
	
		$this->doFetchSiteList();
	
		// Set the max fileSize
		$this->assign('maxFileSize', ini_get('upload_max_filesize'));
		
	}
	
	
	/**
	 * Populate the sourceSite dropdown list.
	 *
	 */
	private function doFetchSiteList() {
	
		$arrFetchableSites = $this->getFetchClassList(VCDUtils::showAdultContent());
	
		if (sizeof($arrFetchableSites) == 0) {
			VCDException::display("No Fetchclasses available.<break>Enable some fetch classes from the Control Panel.");
			return;
		}
		
		// Check for the last used fetch class and make it default if we find one ..
		$metaDefaultArr = SettingsServices::getMetadata(0,VCDUtils::getUserID(), metadataTypeObj::SYS_LASTFETCH);
		$defaultClassName = "";
		if (is_array($metaDefaultArr) && sizeof($metaDefaultArr) > 0 && $metaDefaultArr[0] instanceof metadataObj ) {
			$defaultClassName = $metaDefaultArr[0]->getMetadataValue();
		}
		
		$results = array();
		
		foreach ($arrFetchableSites as $sourceSiteObj) {
			if (strcmp(strtolower($sourceSiteObj->getAlias()), strtolower($defaultClassName)) == 0) {
				$this->assign('selectedFetchSite', $sourceSiteObj->getAlias());
			}
			$results[$sourceSiteObj->getAlias()] = $sourceSiteObj->getName();
		}
		
		$this->assign('fetchSiteList', $results);
		
	}
	
	
	/**
	 * Get array of all the fetch classes available.
	 *
	 * @param bool $bShowAdult | Include adult fetch classes or not
	 * @return array
	 */
	private function getFetchClassList($bShowAdult = false) {
		$arrSourceSites = SettingsServices::getSourceSites();
		$arrSourceList = array();
		foreach ($arrSourceSites as $siteObj) {
			if ($siteObj->isFetchable() && strcmp($siteObj->getClassName(), "") != 0) {
			// Try to instanceate the class ..
			$className = $siteObj->getClassName();
			if (!class_exists($className)) {
				// Check if the $VCDClassfactory can load the class.                    
				if (!is_null(VCDClassFactory::loadClass($className))) {
					// Create instance and check class status
					$fetchClass = new $className;
					$adultStatus = $fetchClass->isAdultSite();
					if ((!$adultStatus) || ($adultStatus && $bShowAdult)) {
						array_push($arrSourceList, $siteObj);
					}
				}
				}
			}
		}
		return $arrSourceList;
	}
	
	
}

?>