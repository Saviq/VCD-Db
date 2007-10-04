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
 * @version $Id: VCDPageUserAddItem.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php

class VCDPageUserAddItem extends VCDBasePage {
	
	
	public function __construct(_VCDPageNode $node) {
		
		parent::__construct($node);
		

	}
	
	
	
	public function handleRequest() {
		
		$source = $this->getParam('source');
		if (!is_null($source)) {
		
			switch ($source) {
				
				/**
				 * Handle request from the fetch classes
				 */
				case 'webfetch':
					$searchTitle = $this->getParam('searchTitle',true);
					$searchSite  = $this->getParam('fetchsite',true);
										
					if ((!is_null($searchTitle)) && (!is_null($searchSite))) {
						// Show the search results
						$this->doFetchSiteResults($searchSite, $searchTitle);
					} else {
						// Show the selected fetched item
						$site   = $this->getParam('site');
						$itemId = $this->getParam('fid');
					}
					
					break;
			
					
					
					
					
					
					
				default:
					break;
			}
			
			
		
		}
		
		
	}
	
	
	
	
	private function doFetchSiteResults($sourceSite, $searchTitle) {
				
		
		// Load the correct fetch class
		$sourceObj = SettingsServices::getSourceSiteByAlias($sourceSite);
		if (!($sourceObj instanceof sourceSiteObj)) {
			throw new VCDProgramException('Invalid source site: ' . $sourceSite);
		}
		
		$className = $sourceObj->getClassName();
		$fetchClass = VCDClassFactory::loadClass($className);
		if (!($fetchClass instanceof VCDFetch)) {
			throw new VCDProgramException("Class {$className} could not be loaded.");
		}
		
		
		// Fetch class data seems all ok .. lets continue
		
		
		// Save the current fetch class in use for next time user fetches movie
		$metaDefaultClass = SettingsServices::getMetadata(0, VCDUtils::getUserID(), metadataTypeObj::SYS_LASTFETCH);
		if (!($metaDefaultClass instanceof metadataObj && strcmp($metaDefaultClass->getMetadataValue(), $sourceSite)==0)) {
			// Default class changed or not found .. add "last used class" to database
			$metaLastUsedClass = new metadataObj(array('',0,VCDUtils::getUserID(),metadataTypeObj::SYS_LASTFETCH,$sourceSite));
			SettingsServices::addMetadata(array($metaLastUsedClass));
		}
		
		

		// Make the fetchClass search it's site ..
		$fetchResults =	$fetchClass->Search($searchTitle);
		if ($fetchResults == VCDFetch::SEARCH_EXACT) {
		 	$fetchClass->fetchItemByID();
		 	$fetchClass->fetchValues();
		 	$obj = $fetchClass->getFetchedObject();
		 	$obj->setSourceSite($sourceObj->getsiteID());
		 	//displayFetchedObject($obj);
		} else {
	 		$results = $fetchClass->showSearchResults();
	 		$this->assign('fetchList', $results);
	 		
		}
		
			
		
		
		
	}
	
	
}

?>