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
 * @version $Id: VCDPage.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php

class VCDPageUserSettings extends VCDBasePage {

	public function __construct(_VCDPageNode $node) {
		
		parent::__construct($node);
		
		
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
					
	}
	
	
	/**
	 * Handle _GET Calls to the VIEW
	 *
	 */
	private function doGet() {
		try {
			
			// Handle edit borrower
			if (strcmp($this->getParam('edit'),"borrower")==0) {
				$borrowerObj = SettingsServices::getBorrowerByID($this->getParam('bid'));
				if ($borrowerObj instanceof borrowerObj ) {
					$this->assign('editBorrower', true);
					$this->assign('borrowerName', $borrowerObj->getName());
					$this->assign('borrowerEmail', $borrowerObj->getEmail());
					$this->assign('borrowerId', $borrowerObj->getID());
					$this->assign('selectedBorrower',$borrowerObj->getID());
				}
			}
			
		} catch (Exception $ex) {
			VCDException::display($ex);
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
			$this->assign('jsAudio',$arrDvdData['audio']);
			$this->assign('jsSubs',$arrDvdData['subs']);
			
			$arrSubs = @explode("#", $arrDvdData['subs']);
			$arrAudio = @explode("#", $arrDvdData['audio']);
			
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
			$checked = "";
			$data = "";
			if ($propertyObj->getpropertyName() == 'RSS' && $userObj->getPropertyByKey($propertyObj->getpropertyName())) {
				$data = "<a href=\"rss/?rss=".$userObj->getUsername()."\">(".VCDLanguage::translate('usersettings.ownfeed').")</a>";
			}
	
			if ($propertyObj->getpropertyName() == 'PLAYOPTION' && $userObj->getPropertyByKey($propertyObj->getpropertyName())) {
				$data = "<a href=\"#\" onclick=\"adjustPlayer()\">(".VCDLanguage::translate('usersettings.player').")</a>";
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
	 * Handle all POST requests to this controller
	 *
	 */
	public function handleRequest() {
		
		$deligate = true;
		
		try {
			
			// Check if user profile is being updated
			if (strcmp($this->getParam('action'),'updateprofile')==0) {
				$this->updateUser();
				$deligate = false;
			}
			
			
		} catch (Exception $ex) {
			VCDException::display($ex,true);
		}
		
		
		// Deligate to parent if the request was not meant for this Controller
		if ($deligate) {
			parent::handleRequest();	
		}
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
	}
	
}



?>