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
 * @version $Id: VCDPageUserAddItemManually.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php

class VCDPageUserAddItemManually extends VCDBasePage {
	
	public function __construct(_VCDPageNode $node) {

		parent::__construct($node);
		
		$this->doInitPage();
	
	}
	
	
	
	/**
	 * Initialize the item selection.
	 *
	 */
	private function doInitPage() {
	
		// Set the movie category
		$results = array();
		
		$categories = SettingsServices::getAllMovieCategories();
		foreach ($categories as $categoryObj) {
			$results[$categoryObj->getId()] = $categoryObj->getName(true);
		}
		$results[null] = VCDLanguage::translate('misc.select');
		asort($results);
		$results = array(null => VCDLanguage::translate('misc.select')) + $results;
		$this->assign('itemCategoryList',$results);
				
		
		
		// Set the mediaType list
		$results = array();
		$results[null] = VCDLanguage::translate('misc.select');
		
		foreach (SettingsServices::getAllMediatypes() as $mediaTypeObj) {
			$results[$mediaTypeObj->getmediaTypeID()] = $mediaTypeObj->getDetailedName();
			if ($mediaTypeObj->getChildrenCount() > 0) {
				foreach ($mediaTypeObj->getChildren() as $childObj) { 
					$results[$childObj->getmediaTypeID()] = '&nbsp;&nbsp;'.$childObj->getDetailedName();
				}
			}
		}
		
		$this->assign('mediatypeList', $results);
		
		
		
		// Set the number of cd's list
		$results = array();
		$results[null] = VCDLanguage::translate('misc.select');
		for($i=1;$i<11;$i++) {
			$results[$i] = $i;
		}
		$this->assign('cdList',$results);
		
		
		// Set the year list
		$results = array();
		for ($i = date("Y"); $i >= 1900; $i--) {
			$results[$i] = $i;
		}
		$this->assign('yearList',$results);
		
		
	}
	
	
}

?>