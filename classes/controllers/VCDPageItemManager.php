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
 * @version $Id: VCDPageItemManager.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php
require_once(dirname(__FILE__).'/VCDPageBaseItem.php');

class VCDPageItemManager extends VCDPageBaseItem  {
		
	private $metadata = null;
	private $dvdId = null;
	
	private $tabs = array(
		'basic'		=> array('basic.tpl','translate.manager.basic'),
		'imdb'		=> array('imdb.tpl','translate.manager.imdb'),
		'cast'		=> array('cast.tpl','translate.movie.actors'),
		'adultcast'	=> array('cast.adult.tpl','translate.movie.actors'),
		'covers'	=> array('cover.tpl','Covers'),
		'metadata'	=> array('metadata.tpl','Metadata'),
		'adult'		=> array('adult.tpl', 'translate.manager.empire'),
		'dvd'		=> array('dvd.tpl', 'DVD')
	);
	
	private $tabsMovie = array('basic','imdb','cast','covers','dvd','metadata'); 
	private $tabsAdultMovie = array('basic','adult','adultcast','covers','dvd','metadata'); 
	
	public function __construct(_VCDPageNode $node) {

		// Tell parent not to load the extended properties
		$this->skipExtended = true;
		parent::__construct($node);
		
		
		$this->initTabs();
		
		$this->initPage();
			
	}
	
	
	/**
	 * Dynamically create the tabs for the manager
	 *
	 */
	private function initTabs() {
		
		$tabs = $this->getTabsToLoad();
		$results = array();
		
		foreach ($tabs as $name => $item) {
			$template = 'window.manager.tab.'.$item[0];
			$title = $item[1];
			$pos = strpos($title,'translate.');
			if (!($pos === false)) {
				$title = VCDLanguage::translate(substr($title,strlen('translate.')));
			}
			$results[$name] = array('template' => $template, 'title' => $title);
		}
				
		$this->assign('pageTabs',$results);
	}
	
	
	/**
	 * Locate the correct tabs to load, based on item type and user preferences.
	 *
	 * @return array | array of tabs to be loaded.
	 */
	private function getTabsToLoad() {
		
		// Check if DVD tab should be loaded ..
		$copies = $this->itemObj->getInstancesByUserID(VCDUtils::getUserID());
		$tabDvd = false;
		if (is_array($copies) && sizeof($copies) > 0) {
			$tabDvd = VCDUtils::isDVDType($copies['mediaTypes']);
			
			// Set the default dvdItemId 
			if (is_null($this->dvdId)) {
				foreach ($copies['mediaTypes'] as $mediaTypeObj) {
					if (VCDUtils::isDVDType(array($mediaTypeObj))) {
						$this->dvdId = $mediaTypeObj->getmediaTypeID();
						break;
					}
				}
			}
		}
			
		// Check if metadata tab should be loaded
		$tabMeta = false;
		$metadata = SettingsServices::getMetadataTypes(VCDUtils::getUserID());
		if (is_array($metadata) && sizeof($metadata) > 0) {
			$tabMeta = true;
		} else {
			// Dig deeper .. check if user is using custom Index keys or Playoption
			$user =& VCDUtils::getCurrentUser();
			if ((bool)$user->getPropertyByKey(vcd_user::$PROPERTY_NFO) || 
				(bool)$user->getPropertyByKey(vcd_user::$PROPERTY_INDEX) || 
				(bool)$user->getPropertyByKey(vcd_user::$PROPERTY_PLAYMODE)) {
				$tabMeta = true;
			}
		}
	
		
		$tabs = array();
		if ($this->itemObj->isAdult()) {
			foreach ($this->tabsAdultMovie as $tabName) {
				if ((strcmp($tabName,'metadata') == 0) && !$tabMeta) continue;
				if ((strcmp($tabName,'dvd') == 0) && !$tabDvd) continue;
				$tabs[$tabName] = $this->tabs[$tabName];
			}	
		} else {
			foreach ($this->tabsMovie as $tabName) {
				if ((strcmp($tabName,'metadata') == 0) && !$tabMeta) continue;
				if ((strcmp($tabName,'dvd') == 0) && !$tabDvd) continue;
				$tabs[$tabName] = $this->tabs[$tabName];
			}	
		}
		
		return $tabs;
	}
	
	
	private function initPage() {
		
		$this->assign('isAdult', $this->itemObj->isAdult());
		
		if (!is_null($this->sourceObj)) {
			$this->assign('itemExternalId', $this->sourceObj->getObjectID());
			$sourceName = SettingsServices::getSourceSiteByID($this->itemObj->getSourceSiteID());
			$this->assign('itemSourceSiteName', '('.$sourceName->getName().')');
				
				
			if (!$this->itemObj->isAdult()) {
				$this->doSourceSiteElements();
				// Overwrite the director entry
				$this->assign('sourceDirector', $this->sourceObj->getDirector());
				// Overwrite the cast entry
				$this->assign('sourceActors', $this->sourceObj->getCast(false));
			} 
		}
		
		// Set the metadata
		$this->metadata = SettingsServices::getMetadata($this->itemObj->getId(), VCDUtils::getUserID(),'');
		
		
		$this->doCategoryList();
		$this->doYearList();
		$this->doCovers();
		$this->doDvdSettings();
		
		if ($this->itemObj->isAdult()) {
			$this->doAdultData();
		}
		
		
	}
	
	
	private function doDvdSettings() {

		if (is_null($this->dvdId)) {
			return;
		}
		
		$dvdObj = new dvdObj();
		$arrDVDMetaObj = metadataTypeObj::filterByMediaTypeID($this->metadata, $this->dvdId);
		$arrDVDMetaObj = metadataTypeObj::getDVDMeta($arrDVDMetaObj);
			
		$dvd_region = VCDUtils::getDVDMetaObjValue($arrDVDMetaObj, metadataTypeObj::SYS_DVDREGION);
		$dvd_format = VCDUtils::getDVDMetaObjValue($arrDVDMetaObj, metadataTypeObj::SYS_DVDFORMAT);
		$dvd_aspect = VCDUtils::getDVDMetaObjValue($arrDVDMetaObj, metadataTypeObj::SYS_DVDASPECT);
		$dvd_audio =  VCDUtils::getDVDMetaObjValue($arrDVDMetaObj, metadataTypeObj::SYS_DVDAUDIO);
		$dvd_subs =   VCDUtils::getDVDMetaObjValue($arrDVDMetaObj, metadataTypeObj::SYS_DVDSUBS);
		
		if (strcmp($dvd_audio, '') != 0) {
			$dvd_audio = explode('#', $dvd_audio);
		} else {
			$dvd_audio = array();
		}
		
		if (strcmp($dvd_subs, '') != 0) {
			$dvd_subs = explode('#', $dvd_subs);
		} else {
			$dvd_subs = array();
		}
		
		$this->assign('itemRegionList',$dvdObj->getRegionList());
		$this->assign('itemRegion', $dvd_region);
		
		$this->assign('itemFormatList', $dvdObj->getVideoFormats());
		$this->assign('itemFormat', $dvd_format);
		
		$this->assign('itemAspectList', $dvdObj->getAspectRatios());
		$this->assign('itemAspect', $dvd_aspect);

		
		//$this->assign('itemAudioList', array_diff($dvdObj->getAudioList(), $dvd_audio));
		
		$this->assign('itemSubtitleList', array_diff($dvdObj->getLanguageList(false), $dvd_subs));
		
	}
	
	
	private function doCovers() {
		
		$coverTypes = CoverServices::getAllowedCoversForVcd($this->itemObj->getMediaType());
		if (is_array($coverTypes) && sizeof($coverTypes)>0) {
			$results = array();
			foreach ($coverTypes as $coverTypeObj) {
				$coverFile = '';
				$coverSize = '';
				$coverId = '';
				$cover = $this->itemObj->getCover($coverTypeObj->getCoverTypeName());
				if ($cover instanceof cdcoverObj ) {
					$coverFile = $cover->getFilename();
					$coverSize = human_file_size($cover->getFilesize());
					$coverId = $cover->getId();
				}
				$results[$coverTypeObj->getCoverTypeID()] = array(
					'type' => $coverTypeObj->getCoverTypeName(), 'file' => $coverFile,
					'size' => $coverSize, 'id' => $coverId);
			}
			$this->assign('itemCovers',$results);
		}
		
	}
	
	private function doCategoryList() {
		$categories = getLocalizedCategories(SettingsServices::getAllMovieCategories());
		
		$results = array();
		foreach ($categories as $obj) {
			$results[$obj['id']] = $obj['name'];
		}

		$this->assign('itemCategoryList',$results);
	}
	
	private function doYearList() {
		$results = array();
		for ($i = date("Y"); $i > 1900; $i--) {
			$results[$i] = $i;
		}
		$this->assign('itemYearList', $results);
	}
	
	private function doAdultData() {
		
		// populate studio list
		$results = array();
		$studios =  PornstarServices::getAllStudios();
		foreach ($studios as $studioObj) {
			$results[$studioObj->getId()] = $studioObj->getName();
		}
		$this->assign('itemStudioList', $results);
		$this->assign('selectedStudio', $this->itemObj->getStudioID());
		
		// populate current categories
		$results = array();
		$categoriesUsed = PornstarServices::getSubCategoriesByMovieID($this->itemObj->getID());
		foreach ($categoriesUsed as $categoryObj) {
			$results[$categoryObj->getId()] = $categoryObj->getName();
		}
		$this->assign('subCategoriesUsed', $results);
				
		// populate available categories
		$results2 = array();
		$categories = PornstarServices::getSubCategories();
		foreach ($categories as $categoryObj) {
			$results2[$categoryObj->getId()] = $categoryObj->getName();
		}
		$this->assign('subCategoriesAvailable', array_diff($results2, $results));
		
		// Set the adult actors
		$results = array();
		$pornstars = PornstarServices::getPornstarsByMovieID($this->itemObj->getID());
		if (is_array($pornstars) && sizeof($pornstars)>0) {
			foreach ($pornstars as $pornstarObj) {
				$results[$pornstarObj->getId()] = $pornstarObj->getName();
			}
			$this->assign('itemPornstars', $results);
		}
		
		
	}
	
	
}
?>