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
 */
?>
<?php

require_once(dirname(__FILE__) . '/external/smarty/Smarty.class.php');

error_reporting(E_ALL | E_NOTICE | E_COMPILE_WARNING | E_CORE_ERROR | E_WARNING | E_STRICT);

abstract class VCDBasePage extends Smarty  {

	private $template = null;
	private $debug = false;
	
	protected function __construct($template, $doTranslate = true) {
	
		parent::Smarty();
				
		$this->template = $template;
		$this->template_dir = VCDDB_BASE.DIRECTORY_SEPARATOR.'pages'.DIRECTORY_SEPARATOR;
		$this->compile_dir = VCDDB_BASE.DIRECTORY_SEPARATOR.CACHE_FOLDER;
		$this->cache_dir = VCDDB_BASE.DIRECTORY_SEPARATOR.CACHE_FOLDER;
		$this->debugging = $this->debug;
		
		if ($doTranslate == true) {
			$this->doTranslate();
		}	
	}
	
	
	private function doTranslate() {
		$template = $this->template_dir.$this->template;
		preg_match_all('/{\$translate_(.*?)}/',implode('',file($template)),$vars,2);
		foreach ($vars as $v){
			$key = str_replace('_','.',$v[1]);
			$this->assign('translate_'.$v[1], VCDLanguage::translate($key));
		}
	}
	
	/**
	 * Get a value from url parameter that is passed to the page.
	 * If $param does not exists, null is returned.
	 *
	 * @param string $param | The parameter name
	 * @return string | The paramter value
	 */
	protected function getParam($param) {
		if (isset($_GET[$param]) && strcmp($_GET[$param],"") != 0) {
			return $_GET[$param];
		} else {
			return null;
		}
	}
	
	protected function render() {
		$this->display($this->template);
	}
	
}


class VCDPageUserSettings extends VCDBasePage {
	
	private $template = 'page.user.settings.tpl';
	
	public function __construct() {
		parent::__construct($this->template);
		
		
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
		
		// render the page
		$this->render();
			
	}
	
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
	
	private function doTemplates() {

		$results = array();
		
		// Check if user has cookie set for template
		if (isset($_COOKIE['template'])) {
			$this->assign('selectedTemplate', $_COOKIE['template']);
		}

		$this->assign('templates', VCDUtils::getStyleTemplates());
	}
	
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
	
	private function doPost() {
		
	}
	
	
}

class VCDPagePornstar extends VCDBasePage  {

	private $template = 'page.pornstar.tpl';
	
	public function __construct() {

		parent::__construct($this->template);
		
		$pornstarID = $this->getParam('pornstar_id');
		$pornstarObj = PornstarServices::getPornstarByID($pornstarID);
		if (!$pornstarObj instanceof pornstarObj ) {
			redirect();
		}
		
		$this->assign('name', $pornstarObj->getName());
		$this->assign('homeapage', $pornstarObj->getHomepage());
		$this->assign('moviecount', $pornstarObj->getMovieCount());
		$this->assign('biography', $pornstarObj->getBiography());
		$this->assign('iafdlink', $pornstarObj->getIAFD());
		$this->assign('image', $pornstarObj->getImageLink());
		
		// Generate the javascript items
		$i = 0;
		$scriptItems = array();
		foreach ($pornstarObj->getMovies() as $id => $title) {
			$hasThumb = false;
			$arrCovers = CoverServices::getAllCoversForVcd($id);
			foreach ($arrCovers as $obj) {
				if ($obj->isThumbnail()) {
					$scriptItems[] = array('index' => $i++, 'image' => $obj->getImagePath());
					$hasThumb = true;
					continue;
				}	
			}
			if (!$hasThumb) {
				$scriptItems[] = array('index' => $i++, 'image' => '');
			}
		}
					
		// Generate the movie list
		$movies = array();
		if ($pornstarObj->getMovieCount() > 0) {
			$i = 0;
			foreach ($pornstarObj->getMovies() as $id => $title) {
				$movies[$id] = array('title' => $title, 'index' => $i++);
			}
		}

		$this->assign('scriptItem', $scriptItems);
		$this->assign('movies', $movies);
		
		$this->render();
	}
	
}


?>