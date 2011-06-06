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
 * @version $Id: VCDPageUserSettings.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php
class VCDPageUserSettings extends VCDBasePage {

	public function __construct(_VCDPageNode $node) {
		try {
			
			parent::__construct($node);
			
			// Register javascripts
			$this->registerScript(self::$JS_JSON);
			$this->registerScript(self::$JS_AJAX);
			
			// populate basic userdata
			$this->assign('fullname', VCDUtils::getCurrentUser()->getFullname());
			$this->assign('username', VCDUtils::getCurrentUser()->getUsername());
			$this->assign('email', VCDUtils::getCurrentUser()->getEmail());
		
			// Check for get parameters
			$this->doGet();
					
			// populate user properties
			$this->doProperties();
			
			// populate available page styles
			$this->doTemplates();
			
			// populate the borrowerlist
			$this->doBorrowers();
			
			// populate the RSS Feed list
			$this->doFeedList();
			
			// populate the frontpage settings
			$this->doFrontpageSettings();
			
			// populate the default DVD Settings
			$this->doDVDSettings();
			
			// populate my metadata
			$this->doMetadata();
			
			// populate the ignorelist
			$this->doIgnoreList();
		
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	
	/**
	 * Handle all POST requests to this controller
	 *
	 */
	public function handleRequest() {
		
		$action = $this->getParam('action');
		if (is_null($action)) {
			return;
		}
		
		try {
			
			
			switch ($action) {
				case 'updateprofile':
					// Update user's profile
					$this->updateUser();			
					break;
			
				case 'addmetadata':
					$this->addMetadata();
					break;
					
				case 'update_dvdsettings':
					$this->updateDefaultDVDSettings();
					break;
				
				case 'update_borrower':
					$this->updateBorrower();
					break;
					
				case 'update_frontpage':
					$this->updateFrontpageSettings();
					break;
					
				case 'update_ignorelist':
					$this->updateIgnorelist();
					break;
					
				default:
					break;
			}
		} catch (Exception $ex) {
			VCDException::display($ex,true);
		}
	}
	
	
	/**
	 * Handle _GET Calls to the VIEW
	 *
	 */
	private function doGet() {
		try {
			
			
			$action = $this->getParam('action');
			
			switch ($action) {
				case 'editborrower':
					// Handle edit borrower
					$borrowerObj = SettingsServices::getBorrowerByID($this->getParam('bid'));
					if ($borrowerObj instanceof borrowerObj && $borrowerObj->getOwnerID()==VCDUtils::getUserID()) {
						$this->assign('editBorrower', true);
						$this->assign('borrowerName', $borrowerObj->getName());
						$this->assign('borrowerEmail', $borrowerObj->getEmail());
						$this->assign('borrowerId', $borrowerObj->getID());
						$this->assign('selectedBorrower',$borrowerObj->getID());
					} else {
						redirect('?page=settings');
					}
					
					break;
					
				case 'delrss':
					// Delete RSS feed
					$this->deleteRss();
					redirect('?page=settings');
					break;
					
				case 'delmetatype':
					// Delete metadata
					$this->deleteMetadataType();
					redirect('?page=settings');
					break;
					
					
				case 'templates':
					// Set site css template
					$this->setSiteTemplate();
					break;
					
					
				case 'delborrower':
					// Delete borrower
					$borrowerObj = SettingsServices::getBorrowerByID($this->getParam('bid'));
					if ($borrowerObj instanceof borrowerObj && $borrowerObj->getOwnerID()==VCDUtils::getUserID()) {
						SettingsServices::deleteBorrower($borrowerObj);
					}
					redirect('?page=settings');
					break;
					
			
				default:
					break;
			}
			
			
			
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * populate my metadata list
	 *
	 */
	private function doMetadata() {
		
		$metadataTypes = SettingsServices::getMetadataTypes(VCDUtils::getUserID());
		$results = array();
		if (is_array($metadataTypes)) {
			foreach ($metadataTypes as $obj) {
				$results[$obj->getMetadataTypeID()] = 
					array('name' => $obj-> getMetadataTypeName(), 
						'desc' => $obj->getMetadataDescription(),
						'public' => $obj->getMetadataTypePublic());
			}
			$this->assign('myMetadata', $results);	
		}
	}

	
	
	/**
	 * Populate the Ignore list
	 *
	 */
	private function doIgnoreList() {
		
		$arrUsers = UserServices::getActiveUsers();
		
		if (sizeof($arrUsers) > 1) {
			
			$this->assign('showIgnoreList',true);
			
			
			// Get current ignore list
			$ignorelist = array();
			$metaArr = SettingsServices::getMetadata(0, VCDUtils::getUserID(), metadataTypeObj::SYS_IGNORELIST);
			if (sizeof($metaArr) > 0) {
				$ignorelist = split("#", $metaArr[0]->getMetadataValue());
			}
			
			
			
			$results = array();
			// Populate those NOT on the list
			foreach ($arrUsers as $userObj) {
				if (!in_array($userObj->getUserID(), $ignorelist)) {
					if ($userObj->getUserID() != VCDUtils::getUserID()) {
						$results[$userObj->getUserID()] = $userObj->getUserName();
					}
				}
			}
			$this->assign('userAvailList',$results);
			
		
			// Populate those on the list	
			$results = array();
			foreach ($arrUsers as $userObj) {
				if (in_array($userObj->getUserID(), $ignorelist)) {
					$results[$userObj->getUserID()] = $userObj->getUserName();
				}
			}
			
			$this->assign('userSelList',$results);			
			
		}
	}
	
	/**
	 * Populate the default DVD settings
	 *
	 */
	private function doDVDSettings() {
		
		
		$dvdObj = new dvdObj();
		// Get the default data from user.. if any 
		$metaObjDvd = SettingsServices::getMetadata(0, VCDUtils::getUserID(), metadataTypeObj::SYS_DEFAULTDVD);
		
		$this->assign('formatList', $dvdObj->getVideoFormats());
		$this->assign('aspectList', $dvdObj->getAspectRatios());
		$this->assign('regionList', $dvdObj->getRegionList());
		$this->assign('audioList', $dvdObj->getAudioList());
		$this->assign('subtitleList', $dvdObj->getLanguageList());
		
				
		if (is_array($metaObjDvd) && sizeof($metaObjDvd) == 1) {
			$arrDvdData = unserialize($metaObjDvd[0]->getMetadataValue());
			
			$this->assign('selectedFormat', $arrDvdData['format']);
			$this->assign('selectedAspect', $arrDvdData['aspect']);
			$this->assign('selectedRegion', $arrDvdData['region']);
			if (isset($arrDvdData['audio'])) {
				$this->assign('jsAudio',$arrDvdData['audio']);
			}
			if (isset($arrDvdData['subs'])) {
				$this->assign('jsSubs',$arrDvdData['subs']);
			}
			if (isset($arrDvdData['lang'])) {
				$this->assign('jsLang',$arrDvdData['lang']);
			}
			
			
			$arrSubs = @explode("#", $arrDvdData['subs']);
			$arrAudio = @explode("#", $arrDvdData['audio']);
			$arrSpoken = @explode("#", $arrDvdData['lang']);
			
			
			$results = array();
			if (is_array($arrSpoken) && sizeof($arrSpoken) > 0) {
				foreach ($arrSpoken as $key) {
					if (strlen($key) > 0) {
						$results[] = array('key' => $key, 'name' => $dvdObj->getLanguage($key), 'img' => $dvdObj->getCountryFlag($key));
					}
				}
				$this->assign('selectedSpoken', $results);
			}
			
			$results = array();
			if (is_array($arrSubs) && sizeof($arrSubs) > 0) {
				foreach ($arrSubs as $key) {
					if (strlen($key) > 0) {
						$results[] = array('key' => $key, 'name' => $dvdObj->getLanguage($key), 'img' => $dvdObj->getCountryFlag($key));
					}
				}
				$this->assign('selectedSubs', $results);
			}
			
			$results = array();
			if (is_array($arrAudio) && sizeof($arrAudio) > 0) {
				foreach ($arrAudio as $key) {
					if (strlen($key) > 0) {
						$name = $dvdObj->getAudio($key);
						$results[] = array('key' => $key, 'name' => $name);
					}
				}
				$this->assign('selectedAudio', $results);
			}
		}
		
	}
	
	/**
	 * Assign the frontpage settings
	 *
	 */
	private function doFrontpageSettings() {
				
		$metaObjA = SettingsServices::getMetadata(0, VCDUtils::getUserID(), 'frontstats');
		$metaObjB = SettingsServices::getMetadata(0, VCDUtils::getUserID(), 'frontbar');
		$metaObjC = SettingsServices::getMetadata(0, VCDUtils::getUserID(), 'frontrss');
		$arrSelectedFeeds = array();
		
		if (is_array($metaObjA) && sizeof($metaObjA) == 1 && $metaObjA[0]->getMetadataValue() == 1) {
			$this->assign('statChecked', 'checked="checked"');
		}
		if (is_array($metaObjB) && sizeof($metaObjB) == 1 && $metaObjB[0]->getMetadataValue() == 1) {
			$this->assign('sideChecked', 'checked="checked"');
		}
		if (is_array($metaObjC) && sizeof($metaObjC) == 1) {
			$arrSelectedFeeds = split("#", $metaObjC[0]->getMetadataValue());
		}
		
		$results = array();
		$arrFeeds = SettingsServices::getRssFeedsByUserId(0);
		foreach ($arrFeeds as $rssObj) {
			if (!in_array($rssObj->getId(), $arrSelectedFeeds)) {
				if ($rssObj->isAdultFeed() && !VCDUtils::showAdultContent()) { continue; }
				$results[$rssObj->getId()] = $rssObj->getName();
			}
		}
		$this->assign('rssAvailList', $results);
		
		$results = array();
		foreach ($arrFeeds as $rssObj) {
			if (is_array($arrSelectedFeeds) && in_array($rssObj->getId(), $arrSelectedFeeds)) {
				$results[$rssObj->getId()] = $rssObj->getName();
			}
		}
		
		$this->assign('rssChList', $results);
		
	}
	
	
	/**
	 * Assign the feedlist
	 *
	 */
	private function doFeedList() {
	
		$arrFeeds = SettingsServices::getRssFeedsByUserId(VCDUtils::getUserID());
		$results = array();
		if (is_array($arrFeeds) && sizeof($arrFeeds) > 0) {
			foreach ($arrFeeds as $rssObj) {
				if (!$rssObj->isVcddbFeed()) {continue;}
				
				$pos = strpos($rssObj->getFeedUrl(), "?rss=");
				if ($pos === false) {
					$title = VCDLanguage::translate('rss.site');
					$image = "images/rsssite.gif";
				} else {
					$title = VCDLanguage::translate('rss.user');
					$image = "images/rssuser.gif";
				}
				
				$results[] = array('id' => $rssObj->getId(), 'name' => $rssObj->getName(), 
					'title' => $title, 'image' => $image, 'link' => $rssObj->getFeedUrl());
			}
			$this->assign('feedList', $results);
		}
		
	}
	
	/**
	 * Populate and assign the borrowers list.
	 *
	 */
	private function doBorrowers() {
		
		$results = array();
		$arrBorrowers = SettingsServices::getBorrowersByUserID(VCDUtils::getUserID());
		if (is_array($arrBorrowers) && sizeof($arrBorrowers) > 0) {
			$results[null] = VCDLanguage::translate('loan.select');
			foreach ($arrBorrowers as $borrowerObj) {
				$results[$borrowerObj->getID()] = $borrowerObj->getName();
			}
		}
		
		$this->assign('borrowerList', $results);

	}
	
	/**
	 * Populate all CSS style templates available
	 *
	 */
	private function doTemplates() {
		$this->assign('templates', VCDUtils::getStyleTemplates());
	}
	
	/**
	 * Populate the users properties list.
	 *
	 */
	private function doProperties() {
		
		$properties = UserServices::getAllProperties();
		$show_adult = (bool)SettingsServices::getSettingsByKey('SITE_ADULT');
		$userObj = VCDUtils::getCurrentUser();
		$results = array();
		
		foreach ($properties as $propertyObj) {
			$checked = '';
			$data = '';
			if ($propertyObj->getpropertyName() == 'RSS') {
				if  (!(bool)SettingsServices::getSettingsByKey('RSS_USERS')) {
					continue;
				} elseif ($userObj->getPropertyByKey($propertyObj->getpropertyName())) {
					$data = "<a href=\"rss/?rss=".$userObj->getUsername()."\">(".VCDLanguage::translate('usersettings.ownfeed').")</a>";
				}
			}
	
			if (!($propertyObj->getpropertyName() == 'SHOW_ADULT' && !VCDUtils::showAdultContent(true))) {
				if ($userObj->getPropertyByKey($propertyObj->getpropertyName())) {
					$checked = "checked=\"checked\"";
				}
	
				// Check if translation for property exists
				$langkey = "userproperties.".strtolower($propertyObj->getpropertyName());
				$description = VCDLanguage::translate($langkey);
				if (strcmp($description, "undefined") == 0) {
					$description = $propertyObj->getpropertyDescription();
				}
	
				$results[$propertyObj->getpropertyID()] = 
					array('description' => $description, 'extra' => $data, 'checked' => $checked);
	
			}
		}
	
		// Assign the array to the template
		$this->assign('properties', $results);
	
	}
	
	
	
	/**
	 * Update the users ignorelist
	 *
	 */
	private function updateIgnorelist() {
		if (!is_null($this->getParam('id_list',true))) {
			// Save the ignore list to database
			$metaObj = new metadataObj(array('',0, VCDUtils::getUserID(), metadataTypeObj::SYS_IGNORELIST , $this->getParam('id_list',true)));
			SettingsServices::addMetadata($metaObj);
		} else {
			// Remove all entries from the ignorelist
			$metaObj = new metadataObj(array('',0, VCDUtils::getUserID(), metadataTypeObj::SYS_IGNORELIST , ''));
			SettingsServices::addMetadata($metaObj);
		}
		redirect('?page=settings');
	}
	
	
	
	/**
	 * Update the frontpage settings, rss feeds and the right sidebar
	 *
	 */
	private function updateFrontpageSettings() {
		if (!is_null($this->getParam('stats',true)) && strcmp($this->getParam('stats',true), "yes") == 0) {
			// User wants to see statistics
			$frontstatsObj = new metadataObj(array('',0, VCDUtils::getUserID(), metadataTypeObj::SYS_FRONTSTATS, 1));
		} else {
			$frontstatsObj = new metadataObj(array('',0, VCDUtils::getUserID(), metadataTypeObj::SYS_FRONTSTATS, 0));
		}

		if (!is_null($this->getParam('sidebar',true)) && strcmp($this->getParam('sidebar',true), "yes") == 0) {
			// User wants to see sidebar
			$frontbarObj = new metadataObj(array('',0, VCDUtils::getUserID(), metadataTypeObj::SYS_FRONTBAR, 1));
		} else {
			$frontbarObj = new metadataObj(array('',0, VCDUtils::getUserID(), metadataTypeObj::SYS_FRONTBAR, 0));
		}

		if (!is_null($this->getParam('rss_list',true)) && strlen($this->getParam('rss_list',true)) > 1) {
			$frontRssObj = new metadataObj(array('',0, VCDUtils::getUserID(), metadataTypeObj::SYS_FRONTRSS, $this->getParam('rss_list',true)));
		} else {
			$frontRssObj = new metadataObj(array('',0, VCDUtils::getUserID(), metadataTypeObj::SYS_FRONTRSS , $this->getParam('rss_list',true)));
		}

		SettingsServices::addMetadata(array($frontbarObj, $frontRssObj, $frontstatsObj));


		redirect('?page=settings');
	}
	
	
	
	/**
	 * Update borrower entry
	 *
	 */
	private function updateBorrower() {
		
		$borrower_id = $this->getParam('borrower_id',true);
		if (is_numeric($borrower_id)) {
			$borrowerObj = SettingsServices::getBorrowerByID($borrower_id);
			$borrowerObj->setEmail($this->getParam('borrower_email',true));
			$borrowerName = $this->getParam('borrower_name',true);
			if (!is_null($borrowerName) && strlen($borrowerName) > 0) {
				$borrowerObj->setName($borrowerName);
			}
			SettingsServices::updateBorrower($borrowerObj);
			VCDUtils::setMessage("(".$borrowerObj->getName()." has been updated)");
		}
		redirect('?page=settings');
	}
	
	
	/**
	 * Delete user defined metadata Type
	 *
	 */
	private function deleteMetadataType() {
		
		$metadataTypeId = $this->getParam('meta_id');
		if (!is_null($metadataTypeId) && is_numeric($metadataTypeId)) {
			// Verification of the request is done in the services.
			SettingsServices::deleteMetaDataType($metadataTypeId);
		}
		
	}
	
	/**
	 * Delete rss feed from users profile
	 *
	 */
	private function deleteRss() {
		
		$id = $this->getParam('rss_id');
		if (!is_null($id) && is_numeric($id)) {
			
			// check if current user is actually the owner of this item
			$rssObj = SettingsServices::getRssfeed($id);
			if ($rssObj instanceof rssObj && ($rssObj->getOwnerId()==VCDUtils::getUserID())) {
				SettingsServices::delFeed($id);
			}
			
		}
	}
	
	/**
	 * Update the default DVD settings to use when new movie is added.
	 *
	 */
	private function updateDefaultDVDSettings() {
		
		$dvd = array();
		$dvd['format'] = $this->getParam('format',true);
		$dvd['aspect'] = $this->getParam('aspect',true);
		$dvd['region'] = $this->getParam('region',true);
		if (!is_null($this->getParam('dvdaudio',true))) {
			$dvdaudio = implode('#', array_unique(explode("#", $this->getParam('dvdaudio',true))));
			$dvd['audio'] = $dvdaudio;
			if (strrpos($dvd['audio'], "#") == strlen($dvd['audio'])-1) {
				$dvdaudio = substr($dvd['audio'], 0, strlen($dvd['audio'])-1);
				$dvd['audio'] = $dvdaudio;
			}

		}
		if (!is_null($this->getParam('dvdsubs',true))) {
			$dvdsubs = implode('#', array_unique(explode("#", $this->getParam('dvdsubs',true))));
			$dvd['subs'] = $dvdsubs;
			if (strrpos($dvd['subs'], "#") == strlen($dvd['subs'])-1) {
				$dvdsubs = substr($dvd['subs'], 0, strlen($dvd['subs'])-1);
				$dvd['subs'] = $dvdsubs;
			}
		}
		
		if (!is_null($this->getParam('dvdlang',true))) {
			$dvdspoken = implode('#', array_unique(explode("#", $this->getParam('dvdlang',true))));
			$dvd['lang'] = $dvdspoken;
			if (strrpos($dvd['lang'], "#") == strlen($dvd['lang'])-1) {
				$dvdspoken = substr($dvd['lang'], 0, strlen($dvd['lang'])-1);
				$dvd['lang'] = $dvdspoken;
			}
		}
		
		$obj = new metadataObj(array('', 0, VCDUtils::getUserID(), 
			metadataTypeObj::SYS_DEFAULTDVD , serialize($dvd)));
		SettingsServices::addMetaData($obj);
		
		redirect('?page=settings');
	}

	
	/**
	 * Add an a new metadata entry to the database
	 *
	 */
	private function addMetadata() {

		$metaId   = $this->getParam('metadataid',true, -1);
		$metaName = $this->getParam('metadataname',true);
		$metaDesc = $this->getParam('metadatadescription',true);
		$metaPublic = (bool)$this->getParam('metadatapublic', true, false);

		if (!is_null($metaName) && !is_null($metaDesc)) {
			
			$metaName = preg_replace('/\s/', '',trim($metaName));
			$metaDesc = trim($metaDesc);
			$obj = new metadataTypeObj($metaId, $metaName, $metaDesc, VCDUtils::getUserID(), $metaPublic);
		}

		if ($metaId == -1) {
			SettingsServices::addMetaDataType($obj);
		} else if ($metaId > 30) {
			SettingsServices::updateMetadataType($obj);
		} else {
			throw new Exception("Wrong metadata id posted");
		}
		
		redirect('?page=settings');
	}

	
	/**
	 * Update the users css template choice for the UI
	 *
	 */
	private function setSiteTemplate() {
		
		$template = $this->getParam('template');
		
		// Set the new template in cookie
		// but we must keep existing data in the cookie
		SiteCookie::extract('vcd_cookie');
		$Cookie = new SiteCookie("vcd_cookie");
		$Cookie->clear();
		
		if (isset($_COOKIE['session_id']) && isset($_COOKIE['session_uid'])) { 
			$session_id    = $_COOKIE['session_id'];			
			$user_id 	   = $_COOKIE['session_uid'];
			$session_time  = $_COOKIE['session_time'];	
			
			$Cookie->put("session_id", $session_id);	
			$Cookie->put("session_time", $session_time);
			$Cookie->put("session_uid", $user_id);
		}
		
		if (isset($_COOKIE['language'])) {
			$langname = $_COOKIE['language'];
			$Cookie->put("language",$langname);
		}
				
		$Cookie->put("template",$template);
		$Cookie->set();
			
		redirect('?page=settings');
	}
	
	/**
	 * Update the user profile.
	 *
	 */
	private function updateUser()	{
		$userObj = VCDUtils::getCurrentUser();
		
		$fullname = $this->getParam('name',true);
		$email = $this->getParam('email',true);
		$password = $this->getParam('password',true);
		
		if (is_null($fullname) || strlen($fullname)<3) {
			throw new VCDInvalidInputException('Your name cannot be empty');
		}
		
		if (!preg_match( "/^([a-zA-Z0-9])+([a-zA-Z0-9._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9._-]+)+$/", $email)) {
			throw new VCDInvalidInputException('Invalid email.');
		}
		
		if (!is_null($password)) {
			if (strlen($password) < 5) {
				throw new VCDInvalidInputException('Your password must be at least 4 characters.');
			}
			if ($userObj->isDirectoryUser()) {
				throw new VCDConstraintException('Password cannot be changed for Directory authenticated users.');
			}
			
			// new password seems ok .. update it
			$userObj->setPassword(md5($password));	
		} 
		
		// Everything seems ok .. update the user data
		$userObj->setName($fullname);
		$userObj->setEmail($email);
		
		// Check for properties
		$userObj->flushProperties();
		
		$properties = $this->getParam('property',true);
		if (is_array($properties) && sizeof($properties) > 0) {
			foreach ($properties as $property_id) {
				$userObj->addProperty(UserServices::getPropertyById($property_id));
			}
		}
		
		if (UserServices::updateUser($userObj)) {
			// update the user in session as well
			$_SESSION['user'] = $userObj;
			VCDUtils::setMessage("(".VCDLanguage::translate('usersettings.updated').")");
		} else {
			VCDUtils::setMessage("(".VCDLanguage::translate('usersettings.update_failed').")");
		}
		
		redirect('?page=settings');
		
	}
	
}



?>