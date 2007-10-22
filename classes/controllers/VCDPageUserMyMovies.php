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
 * @version $Id: VCDPageUserMyMovies.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php

class VCDPageUserMyMovies extends VCDBasePage {

	public function __construct(_VCDPageNode $node) {
		
		parent::__construct($node);
		$this->initPage();
		
	}
	
	private function initPage() {
		
		$this->assign('pageTitle',VCDLanguage::translate('menu.movies'));
		
		if (VCDUtils::getCurrentUser()->getPropertyByKey('USE_INDEX')) {
			$this->assign('isIndex',true);
		}
		if(VCDUtils::getCurrentUser()->getPropertyByKey('SEEN_LIST'))  {
			$this->assign('isSeenlist',true);
		}
		
		switch ($this->getParam('do')) {
				case 'join':
					$this->doDiscJoin();
					break;
				case 'keys':
					$this->doCustomKeys();
					break;
				default:
					break;
			}	
		
	}
	
	
	/**
	 * Assign properties to the custom keys/id's page
	 *
	 */
	private function doCustomKeys() {
		$this->assign('pageTitle', VCDLanguage::translate('mymovies.keys'));
		
		
		$currentIndex = $this->getParam('index',false,1);
		if (!is_numeric($currentIndex)) {
			$currentIndex = 1;
		}
		$this->assign('itemPage',$currentIndex);
		
		// Get the movie list
		$movies = MovieServices::getAllVcdByUserId(VCDUtils::getUserID(), true);
			
		// Create the dropdownlist of pageIndex
		$itemsPerPage = 25;
		$itemsTotal = sizeof($movies);
		$indexArray = array();
		$totalPages = ceil($itemsTotal/$itemsPerPage);
		for ($i=0;$i<$totalPages;$i++) {
			$end = (($i*$itemsPerPage)+$itemsPerPage) > $itemsTotal ? $itemsTotal : (($i*$itemsPerPage)+$itemsPerPage);
			$indexArray[($i+1)] = (($i*$itemsPerPage)+1)." - " . $end;
		}
		$this->assign('pagesList', $indexArray);
		
		
		$btnText = VCDLanguage::translate('misc.savenext'). ' ' . $itemsPerPage;
					
		// Generate dataset to work with
		$results = array();
		$indexEnd = $itemsPerPage*$currentIndex;
		$indexStart = $indexEnd - $itemsPerPage;
		if ($indexEnd > $itemsTotal) {
			$indexEnd = $itemsTotal;
			$btnText = VCDLanguage::translate('misc.save');
		}
		
		for ($i=$indexStart;$i<$indexEnd;$i++) {
			$movieObj = $movies[$i];
			$mediaType = $movieObj->getMediaType();
			$mediaTypeId = $mediaType[0]->getMediaTypeId();
			$key = $movieObj->getID()."|".$mediaTypeId;
			$results[$key] = array('id' => $movieObj->getID(), 'title' => $movieObj->getTitle(), 'mediatype' => $movieObj->showMediaTypes());
		}
		
		$this->assign('keyList', $results);
		$this->assign('itemBtnSave',$btnText);
		
		
	}
	
	/**
	 * Assign properties to the discjoin page.
	 *
	 */
	private function doDiscJoin() {
		$this->assign('pageTitle',VCDLanguage::translate('mymovies.joinmovies'));
		
		// check for old mysql version ..
		$dbInfo = VCDConnection::getServerInfo();
		if ((strcmp(DB_TYPE,'mysql')==0) && ($dbInfo['version']<4.1)) {
			$this->assign('noJoin',true);
			return;
		}
		
		// Assign the active users list
		$results = array();
		$results[null] = VCDLanguage::translate('misc.select');
		foreach (UserServices::getActiveUsers() as $userObj) {
			if ($userObj->getUserId() != VCDUtils::getUserID()) {
				$results[$userObj->getUserId()] = $userObj->getUserName();	
			}
		}
		$this->assign('ownerList',$results);
		
		// Assign the media types list
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
		$this->assign('mediatypeList', $results);
		
		// Assign the category list
		$categories = getLocalizedCategories(SettingsServices::getMovieCategoriesInUse());
		$adult_id = SettingsServices::getCategoryIDByName('adult');
		$adultEnabled = VCDUtils::showAdultContent();
		$results = array();
		$results[null] = VCDLanguage::translate('misc.any');
		foreach ($categories as $obj) {
			if ($adult_id == $obj['id'] && !$adultEnabled) {continue;}
			$results[$obj['id']] = $obj['name'];
		}
		$this->assign('categoryJoinList',$results);

		// Assign the available join actions list
		$results = array();
		$results[null] = VCDLanguage::translate('misc.select');
		$results[1] = VCDLanguage::translate('mymovies.j1');
		$results[2] = VCDLanguage::translate('mymovies.j2');
		$results[3] = VCDLanguage::translate('mymovies.j3');
		$this->assign('methodList',$results);
		
	}
		
	private function discJoin() {
		try {
			
			$ownerid = $this->getParam('owner',true);
			$typeid = $this->getParam('mediatype',true);
			$catid  = $this->getParam('category',true);
			$taskid = $this->getParam('method',true);
			
			if (!is_numeric($ownerid)) {
				throw new VCDInvalidInputException('Please select some user to compare with.');
			}
			
			if (!is_numeric($taskid)) {
				throw new VCDInvalidInputException('Please select action to perform.');
			}
					
			$this->assign('isJoin',true);
			
			$arrJoins = MovieServices::crossJoin($ownerid, $typeid, $catid, $taskid);	
			$results = array();
			if (is_array($arrJoins)) {
				foreach ($arrJoins as $vcdObj) {
					$results[$vcdObj->getId()] = $vcdObj->getTitle();
				}
			}
			
			$this->assign('joinResults',$results);
			
			
		} catch (Exception $ex) {
			VCDException::display($ex->getMessage(),true);
		}
	}
	
	/**
	 * Handle post request to the controller
	 *
	 */
	public function handleRequest() {
		
		$action = $this->getParam('do');
		switch ($action) {
			case 'join':
				$this->discJoin();
				break;
		
			default:
				break;
		}
		
	}
	
	
	
}



?>