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
	
	/**
	 * Initialize basic page variables.
	 *
	 */
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
				case 'seen':
					$this->doSeenList();
					break;
				case 'pick':
					$this->doPicker();
					break;
				default:
					break;
			}	
		
	}
	
	
	/**
	 * Help user to pick a movie to watch
	 *
	 */
	private function doPicker() {

		// Ajax enable page
		$this->registerScript(self::$JS_JSON);
		$this->registerScript(self::$JS_AJAX);
		
		$this->assign('pageTitle', VCDLanguage::translate('mymovies.helppicker'));
		
		// Populate the available categories
		$categories = getLocalizedCategories(SettingsServices::getMovieCategoriesInUse());
		$adult_id = SettingsServices::getCategoryIDByName('adult');
		$adultEnabled = VCDUtils::showAdultContent();
		$results = array();
		$results[null] = VCDLanguage::translate('misc.any');
		foreach ($categories as $obj) {
			if ($adult_id == $obj['id'] && !$adultEnabled) {continue;}
			$results[$obj['id']] = $obj['name'];
		}
		$this->assign('myCategoryList',$results);
		
		
	}
	
	/**
	 * Save the current seenlist that is being posted.
	 *
	 */
	private function saveSeenList() {

		$keyString = $this->getParam('currentIds',true);
		$checks = $this->getParam('k',true);
		$keys = explode(':',$keyString);

		// Loop through the keys and set seen bit
		foreach ($keys as $key) {
			list($movieid, $mediatypeid) = explode('|',$key);
			if (key_exists($key, $checks)) {
				// Mark the movie seen
				$data = array('',$movieid,VCDUtils::getUserID(),metadataTypeObj::SYS_SEENLIST,'1', $mediatypeid);
				$metaObj = new metadataObj($data);
				SettingsServices::addMetadata($metaObj);
			} else {
				// Check if movie is marked seen in db .. if so delete the entry
				$item = SettingsServices::getMetadata($movieid,VCDUtils::getUserID(),metadataTypeObj::SYS_SEENLIST,$mediatypeid);
				if (is_array($item) && sizeof($item)==1) {
					$metaId = $item[0]->getMetadataID();
					SettingsServices::deleteMetadata($metaId);
				}
			}
		}
		
		
		$nextIndex = ((int)$this->getParam('index',false,0))+1;
		redirect('?page=movies&do=seen&index='.$nextIndex);
		exit();
		
	}
	
	
	/**
	 * Populate the seenlist controls
	 *
	 */
	private function doSeenList() {
		
		$this->assign('pageTitle', VCDLanguage::translate('mymovies.seenlist'));
				
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
		
		
		$currentIndex = $this->getParam('index',false,1);
		if (!is_numeric($currentIndex) || $currentIndex>$totalPages) {
			redirect('?page=movies&do=seen');
			exit();
		}
		
		$this->assign('itemPage',$currentIndex);
		$btnText = VCDLanguage::translate('misc.savenext'). ' ' . $itemsPerPage;
					
		// Generate dataset to work with
		$results = array();
		$indexEnd = $itemsPerPage*$currentIndex;
		$indexStart = $indexEnd - $itemsPerPage;
		if ($indexEnd > $itemsTotal) {
			$indexEnd = $itemsTotal;
			$btnText = VCDLanguage::translate('misc.save');
		}
		
		$arrIds = array();
		for ($i=$indexStart;$i<$indexEnd;$i++) {
			$movieObj = $movies[$i];
			$mediaType = $movieObj->getMediaType();
			$mediaTypeId = $mediaType[0]->getMediaTypeId();
			$key = $movieObj->getID()."|".$mediaTypeId;
			$checked = false;
			$valueArr = SettingsServices::getMetadata($movieObj->getId(), VCDUtils::getUserID(), 
				metadataTypeObj::SYS_SEENLIST, $mediaTypeId);
			if (is_array($valueArr) && sizeof($valueArr)==1) {
				if ((int)$valueArr[0]->getMetadataValue() == 1) {
					$checked = true;
				}
			}
			
			$results[$key] = array('id' => $movieObj->getID(), 'title' => $movieObj->getTitle(), 
				'mediatype' => $movieObj->showMediaTypes(), 'checked' => $checked);
			$arrIds[] = $key;
		}
		
		$this->assign('currentList', implode(':',$arrIds));
		$this->assign('keyList', $results);
		$this->assign('itemBtnSave',$btnText);
		
		
	
	}
	
	
	/**
	 * Save the assigned custom keys
	 *
	 */
	private function saveKeys() {
		
		$keys = $this->getParam('k',true);
		foreach ($keys as $key => $value) {
			list($id,$mediatype) = explode('|',$key);
			
			$data = SettingsServices::getMetadata($id, VCDUtils::getUserID(), metadataTypeObj::SYS_MEDIAINDEX, $mediatype);
			if (is_array($data) && sizeof($data)==1) {
				$metaObj = $data[0];
				if (strcmp(trim($value), '') != 0) {
					$metaObj->setMetadataValue(trim($value));
					SettingsServices::updateMetadata($metaObj);
				} else {
					// if the value is empty we just delete the metadata
					SettingsServices::deleteMetadata($metaObj->getMetadataID());
				}
			} else {
				$metaObj = new metadataObj(
					array('',$id,VCDUtils::getUserID(),metadataTypeObj::SYS_MEDIAINDEX, trim($value), $mediatype));
				SettingsServices::addMetadata($metaObj);
				
			}
		}
		
		$nextIndex = ((int)$this->getParam('index',false,0))+1;
		redirect('?page=movies&do=keys&index='.$nextIndex);
		/*
		$url = ereg_replace('([0-9])$',(string)$nextIndex,$_SERVER['HTTP_REFERER']);
		if (strcmp($url,$_SERVER['HTTP_REFERER'])==0) {
			redirect('?page=movies&do=keys&index='.$nextIndex);
		} else {
			header("Location: {$url}");
			exit();	
		}
		*/
	}
	
	/**
	 * Assign properties to the custom keys/id's page
	 *
	 */
	private function doCustomKeys() {
		$this->assign('pageTitle', VCDLanguage::translate('mymovies.keys'));
				
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
		
		
		$currentIndex = $this->getParam('index',false,1);
		if (!is_numeric($currentIndex) || $currentIndex>$totalPages) {
			redirect('?page=movies&do=keys');
			exit();
		}
		
		$this->assign('itemPage',$currentIndex);
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
			$value = '';
			$valueArr = SettingsServices::getMetadata($movieObj->getId(), VCDUtils::getUserID(), 
				metadataTypeObj::SYS_MEDIAINDEX, $mediaTypeId);
			if (is_array($valueArr) && sizeof($valueArr)==1) {
				$value = $valueArr[0]->getMetadataValue();
			}
			
			$results[$key] = array('id' => $movieObj->getID(), 'title' => $movieObj->getTitle(), 
				'mediatype' => $movieObj->showMediaTypes(), 'key' => $value);
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

	/**
	 * Find the diss based on the submitted variables.
	 *
	 */
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
			case 'keys':
				$this->saveKeys();
				break;
			case 'seen':
				$this->saveSeenList();
				break;
			default:
				break;
		}
		
	}
	
	
	
}



?>