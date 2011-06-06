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
 * @version $Id: VCDPageUserCollectionOverview.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php
class VCDPageUserCollectionOverview extends VCDBasePage {

	public function __construct(_VCDPageNode $node) {
		try {
		
			parent::__construct($node);
			
			if ($this->initPage()===false) {
				VCDException::display(VCDLanguage::translate('misc.nocats'));
				$this->registerScriptBlock('self.close()');
			}
			
			
				
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	
	/**
	 * Initilize the list contents. Returns false if user owns no movies.
	 *
	 * @return bool
	 */
	private function initPage() {
		
		$userCategories = SettingsServices::getCategoriesInUseByUserID(VCDUtils::getUserID());
        if (sizeof($userCategories) == 0) {
           return false;
        }
        
        $mediaTypes = SettingsServices::getMediaTypesInUseByUserID(VCDUtils::getUserID());
		$arrMediaTypes = array();
        
        // Assign the mediatypes
        $results = array();
        foreach ($mediaTypes as $list) {
        	$results[] = $list[1];
        	array_push($arrMediaTypes, $list[0]);
        }
        $this->assign('statsMediatypes',$results);

        $arrMediaTypes = array_flip($arrMediaTypes);

        $results = array();
        foreach ($userCategories as $categoryObj) {

        	// Get data for this category from the services
        	$mediaData = SettingsServices::getMediaCountByCategoryAndUserID(VCDUtils::getUserID(),$categoryObj->getID());
        	// Create calculated data array
        	$arrResults = $this->getCategoryResults($arrMediaTypes,  $mediaData);
            // Push the total sum as the last entry
            $arrResults[] = array_sum($arrResults);
            // Pust to the results
            $results[$categoryObj->getName(true)] = $arrResults;
        }
        
        // Assign to the template
        $this->assign('statsCategories',$results);
       
        $results = array();
        $totalSum = 0;
        foreach ($mediaTypes as $mediaCount) {
            $totalSum += $mediaCount[2];
            $results[] = $mediaCount[2];
        }
        $results[] = $totalSum;
		$this->assign('statsSums',$results);
		
		return true;
		
	}
	
	
	
	
	/**
	 * Get count of movies in each category
	 *
	 * @param array $catArr | Array of categories
	 * @param array $dataArray | array of movies
	 * @return array
	 */
	private function getCategoryResults($catArr, $dataArray) {
		$resultArr = $catArr;
		$keys = array_keys($resultArr);
		foreach ($keys as $key) {
			$resultArr[$key] = 0;
		}
		foreach ($dataArray as $inArr) {
			$resultArr[$inArr[0]] = $inArr[1];
		}
		return $resultArr;
	}
		
}

?>