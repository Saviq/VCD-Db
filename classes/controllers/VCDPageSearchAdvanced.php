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
 * @version $Id: VCDPageSearchAdvanced.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php
class VCDPageSearchAdvanced extends VCDBasePage {
	
	public function __construct(_VCDPageNode $node) {
		try {
			
			parent::__construct($node);
			$this->doCategoryList();
			$this->doYearList();
			$this->doMediatypeList();
			$this->doOwnersList();
			$this->doGradeList();
			$this->doMetatypeList();
			
			if (!is_null($this->getParam('s'))) {
				$this->doSearch();
			}	
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	private function doSearch() {

		$query = base64_decode($this->getParam('s'));
		$keyset = unserialize($query);
		if (is_array($keyset)) {
			
			// Now we have a key-value pairs to work with ..
			$title = isset($keyset['title']) ? $keyset['title'] : null;
			$cat   = isset($keyset['category']) ? $keyset['category'] : null;
			$year  = isset($keyset['year']) ? $keyset['year'] : null;
			$media = isset($keyset['mediatype']) ? $keyset['mediatype'] : null;
			$owner = isset($keyset['owner']) ? $keyset['owner'] : null;
			$grade = isset($keyset['grade']) ? $keyset['grade'] : null;
			$meta  = isset($keyset['metadata']) ? $keyset['metadata'] : null;
			$unseen = isset($keyset['unseen']) ? $keyset['unseen'] : null;
			
			// We do not perform the search if no input is specified
			if (is_null($title) && is_null($cat) && is_null($year) 
				&& is_null($media) && is_null($owner) && is_null($grade) && is_null($meta)) {
				redirect('?page=detailed_search');
				exit();
			}
				
			// Get the search results
			$results = MovieServices::advancedSearch($title,$cat,$year,$media,$owner,$grade,$meta,$unseen);
			$this->assign('searchResults',$results);

			// Set the current search parameters visible
			$this->doSearchSelection($keyset);


		} else {
			redirect('?page=detailed_search');
			exit();
		}
		
		
	}
	
	
	private function doSearchSelection($arrSelected) {
	
		if (isset($arrSelected['title'])) {
			$this->assign('searchTitle',$arrSelected['title']);
		}
		
		if (isset($arrSelected['category'])) {
			$this->assign('selectedCategory',$arrSelected['category']);
		}
		
		if (isset($arrSelected['year'])) {
			$this->assign('selectedYear',$arrSelected['year']);
		}
		
		if (isset($arrSelected['mediatype'])) {
			$this->assign('selectedMediatype',$arrSelected['mediatype']);
		}
		
		if (isset($arrSelected['owner'])) {
			$this->assign('selectedOwner',$arrSelected['owner']);
		}
		
		if (isset($arrSelected['grade'])) {
			$this->assign('selectedGrade',$arrSelected['grade']);
		}
		
		if (isset($arrSelected['metadata'])) {
			$this->assign('searchMetadata', $arrSelected['metadata']);
		}
		
		if (isset($arrSelected['unseen'])) {
			$this->assign('unseenCheck', "checked");
		}
		
	}
	
	public function handleRequest() {
		$query = array();
		foreach ($_POST as $key => $value) {
			if(!empty($value)) $query[$key] = $value;
		}
		redirect('?page=detailed_search&s='.base64_encode(serialize($query)));
		exit();
	
	}
	
	private function doGradeList() {
		$results = array();
		$results[null] = VCDLanguage::translate('misc.any');
		$counter = 1;
		while($counter<10) {
			$results[(string)$counter] = $counter;
			$counter += 0.5;
		}
		$this->assign('searchGradeList', $results);
	}
	
	private function doOwnersList() {
		$results = array();
		$results[null] = VCDLanguage::translate('misc.any');
		foreach (UserServices::getActiveUsers() as $userObj) {
			$results[$userObj->getUserID()] = $userObj->getFullName();
		}
		$this->assign('searchOwnerList', $results);
	}
	
	private function doMediatypeList() {
		$results = array();
		$results[null] = VCDLanguage::translate('misc.any');
		
		foreach (SettingsServices::getAllMediatypes() as $mediaTypeObj) {
			$results[$mediaTypeObj->getmediaTypeID()] = $mediaTypeObj->getDetailedName();
			if ($mediaTypeObj->getChildrenCount() > 0) {
				foreach ($mediaTypeObj->getChildren() as $childObj) { 
					$results[$childObj->getmediaTypeID()] = '&nbsp;&nbsp;'.$childObj->getDetailedName();
				}
			}
		}
		
		$this->assign('searchMediatypeList', $results);
	}
	
	private function doYearList() {
		$results = array();
		$results[null] = VCDLanguage::translate('misc.any');
		for ($i = date("Y"); $i > 1900; $i--) {
			$results[$i] = $i;
		}
		$this->assign('searchYearList', $results);
	}
	
	private function doCategoryList() {
		
		$categories = getLocalizedCategories(SettingsServices::getMovieCategoriesInUse());
		$adult_id = SettingsServices::getCategoryIDByName('adult');
		$adultEnabled = VCDUtils::showAdultContent();
		
		$results = array();
		$results[null] = VCDLanguage::translate('misc.any');
		
		foreach ($categories as $obj) {
			if ($adult_id == $obj['id'] && !$adultEnabled) {continue;}
			$results[$obj['id']] = $obj['name'];
		}

		$this->assign('searchCategoryList',$results);
	}
	
	private function doMetatypeList() {
		$user_id = null;
		if (VCDUtils::isLoggedIn()) {
			$user_id = VCDUtils::getCurrentUser()->getUserID();
		}
		$mdtypes = SettingsServices::getMetadataTypes();

		foreach($mdtypes as $obj) {
			if ($obj->getMetadataTypeLevel() === $user_id || $obj->getMetadataTypePublic()) {
				$results[] = array('id'   => $obj->getMetadataTypeID(),
								   'name' => $obj->isSystemObj()?VCDLanguage::translate('metadata.systemtype.'.$obj->getMetadataTypeID()):$obj->getMetadataDescription(),
								   'owner'=> $obj->isSystemObj()?VCDLanguage::translate('metadata.system'):UserServices::getUserByID($obj->getMetadataTypeLevel())->getFullname());
			}
		}
		$this->assign('searchMetadataList', $results);
	}
}


?>