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
 * @version $Id: VCDPageItemManager.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php
require_once(dirname(__FILE__).'/VCDPageBaseItem.php');
class VCDPageItemManager extends VCDPageBaseItem  {
		
	/**
	 * A container for the metadata on the object based on
	 * the currently logged in user and selected item/movie.
	 *
	 * @var array
	 */
	private $metadata = null;
	/**
	 * A container with all information about the users copy
	 * of the item/movie.  Assoc array with [mediatypes] and [discs]
	 *
	 * @var array
	 */
	private $userCopies = null;
	/**
	 * The currently selected DVD based medium.
	 *
	 * @var int
	 */
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
		try {
		
			// Tell parent not to load the extended properties
			$this->skipExtended = true;
			parent::__construct($node);
			
			// Assert permission
			if (!$this->checkPermissions()) {
				redirect();
			}
			
			// Register javascripts
			$this->registerScript(self::$JS_TABS);
			$this->registerScript(self::$JS_MAIN);
			$this->registerScript(self::$JS_LANG);
			
			// Check for get parameters
			$this->doGet();
			
			if (is_numeric($this->getParam('dvd'))) {
				$this->dvdId = $this->getParam('dvd');
			}
			
			$this->initTabs();
			$this->initPage();
				
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	/**
	 * Handle _POST requests to the controller
	 *
	 */
	public function handleRequest() {
		try {
			// The only request to the page is the update function call
			if (strcmp($this->getParam('action'),'updatemovie')==0) {
				$this->updateItem();

				// Redirect to the manager if no error occurred
				if (is_null($this->getParam('submit',true))) {
					redirect('?page=manager&vcd_id='.$this->itemObj->getID());
				} else {
					// Close window and reload parent
					redirect('?page=manager&close=true&vcd_id='.$this->itemObj->getID());
				}	
				
			}	
			
		} catch (Exception $ex) {
			VCDException::display($ex,true);
		}
	}
	
	/**
	 * Handle specific _GET actions to the controller
	 *
	 */
	private function doGet() {
		$action = $this->getParam('action');
		
		switch ($action) {
			case 'deletecover':
				$cover_id = $this->getParam('cover_id');
				if (is_numeric($cover_id)) {
					CoverServices::deleteCover($cover_id);
				}
				redirect('?page=manager&vcd_id='.$this->itemObj->getID());
				break;
				
			case 'deletemeta':
				$meta_id = $this->getParam('meta_id');
				if (is_numeric($meta_id)) {
					// Check if metadata is a NFO item
					$metadataObj = SettingsServices::getMetadataById($meta_id);
					if ($metadataObj->getMetadataTypeID() == metadataTypeObj::SYS_NFO) {
						SettingsServices::deleteNFO($meta_id);
					} else {
						SettingsServices::deleteMetadata($meta_id);	
					}
				}
				redirect('?page=manager&vcd_id='.$this->itemObj->getID());
				break;
				
			case 'removeactor':
				$actor_id = $this->getParam('actor_id');
				if (is_numeric($actor_id)) {
					PornstarServices::deletePornstarFromMovie($actor_id, $this->itemObj->getID());
				}
				redirect('?page=manager&vcd_id='.$this->itemObj->getID());
				break;
				
			case 'deletecopy':
				$media_id = $this->getParam('media_id');
				$mode = $this->getParam('mode',false,'single');
				if (is_numeric($media_id)) {
					MovieServices::deleteVcdFromUser($this->itemObj->getID(),
						$media_id,$mode, VCDUtils::getUserID());
				}
				redirect('?page=manager&vcd_id='.$this->itemObj->getID());
				break;
				
				
			case 'refetch':
				// Refetch the data from the sourcesite
				$this->doRefetch();
				break;
				
		}
		
		
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
		$this->userCopies =& $copies;
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
			$user = VCDUtils::getCurrentUser();
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
	
	
	/**
	 * Initilize the page default values
	 *
	 */
	private function initPage() {
		
		$this->assign('isAdult', $this->itemObj->isAdult());
		
		if (!is_null($this->sourceObj)) {
			$this->assign('itemExternalId', $this->sourceObj->getObjectID());
			$sourceName = SettingsServices::getSourceSiteByID($this->itemObj->getSourceSiteID());
			$this->assign('itemSourceSiteName', '('.$sourceName->getName().')');
			$this->assign('itemSourceSiteAlias', '('.$sourceName->getAlias().')');
				
				
			if (!$this->itemObj->isAdult()) {
				$this->doSourceSiteElements();
				// Overwrite the director entry
				$this->assign('sourceDirector', $this->sourceObj->getDirector());
				// Overwrite the cast entry
				$this->assign('sourceActors', $this->sourceObj->getCast(false));
				// Overwrite the imdb genre list, we don't want it translated
				$this->assign('sourceCategoryList',$this->sourceObj->getGenre());
			} 
		}
		
		// Set the metadata
		$this->metadata = SettingsServices::getMetadata($this->itemObj->getId(), VCDUtils::getUserID(),'');
		
		
		$this->doCategoryList();
		$this->doYearList();
		$this->doUserCopies();
		$this->doCovers();
		$this->doDvdSettings();
		$this->doMetadata();
		
		if ($this->itemObj->isAdult()) {
			$this->doAdultData();
		}
		
		
	}
	
	/**
	 * Populate the metadata objects
	 *
	 */
	private function doMetadata() {
		if (!isset($this->userCopies['mediaTypes']) || !is_array($this->metadata)) return;
		
		$results = array();
		$mediaTypes =& $this->userCopies['mediaTypes'];

		// Set the media types in the result array
		foreach ($mediaTypes as $mediaTypeObj) {
			$results[$mediaTypeObj->getmediaTypeID()] = array('name' => $mediaTypeObj->getDetailedName());
		}
		
		// User defined metadata
		$userMeta = SettingsServices::getMetadataTypes(VCDUtils::getUserID());
		$userMetaTypeIds = array();
		if (is_array($userMeta)) {
			foreach ($userMeta as $metatypeObj) {
				$userMetaTypeIds[] = $metatypeObj->getMetadataTypeID();
			}
		}
		
		
		$metadataCounter = 0;
		$hitArray = array();
		foreach ($this->metadata as $metadataObj) {
			if ($metadataObj->getMediaTypeID()==0) continue;
			$type_id = $metadataObj->getMetadataTypeID();
			$metadataCounter++;
			$hitArray[] = array('id' => $type_id,'mid' => $metadataObj->getMediaTypeID());
			
			switch ($type_id) {
				
				case (int)metadataTypeObj::SYS_MEDIAINDEX: 
					if (VCDUtils::getCurrentUser()->getPropertyByKey('USE_INDEX')) {
						$this->addMeta(&$results, $metadataObj->getMediaTypeID(), $type_id, $metadataObj->getMetadataID(), 'value',$metadataObj->getMetadataValue(),$metadataCounter);
						$this->addMeta(&$results, $metadataObj->getMediaTypeID(), $type_id, $metadataObj->getMetadataID(), 'name',$metadataObj->getMetadataTypeName(),$metadataCounter);
						if ($metadataObj->getMetadataValue() != '' ) {
							$this->addMeta(&$results, $metadataObj->getMediaTypeID(), $type_id, $metadataObj->getMetadataID(), 'delete',true,$metadataCounter);	
						}
					}
					break;
					
				case (int)metadataTypeObj::SYS_NFO:
					if (VCDUtils::getCurrentUser()->getPropertyByKey('NFO')) {
						$this->addMeta(&$results, $metadataObj->getMediaTypeID(), $type_id, $metadataObj->getMetadataID(), 'value',$metadataObj->getMetadataValue(),$metadataCounter);
						$this->addMeta(&$results, $metadataObj->getMediaTypeID(), $type_id, $metadataObj->getMetadataID(), 'name',$metadataObj->getMetadataTypeName(),$metadataCounter);
						$this->addMeta(&$results, $metadataObj->getMediaTypeID(), $type_id, $metadataObj->getMetadataID(), 'readonly', true,$metadataCounter);
						if ($metadataObj->getMetadataValue() != '') {
							$this->addMeta(&$results, $metadataObj->getMediaTypeID(), $type_id, $metadataObj->getMetadataID(), 'delete',true,$metadataCounter);
						}
					}
					break;
				
				case (int)metadataTypeObj::SYS_FILELOCATION:
					if (VCDUtils::getCurrentUser()->getPropertyByKey('PLAYOPTION')) {
						
						$this->addMeta(&$results, $metadataObj->getMediaTypeID(), $type_id, $metadataObj->getMetadataID(), 'value',$metadataObj->getMetadataValue(),$metadataCounter);
						$this->addMeta(&$results, $metadataObj->getMediaTypeID(), $type_id, $metadataObj->getMetadataID(), 'name',$metadataObj->getMetadataTypeName(),$metadataCounter);
						if ($metadataObj->getMetadataValue() != '') {
							$this->addMeta(&$results, $metadataObj->getMediaTypeID(), $type_id, $metadataObj->getMetadataID(), 'delete',true,$metadataCounter);
						}
					}
					break;
					
					
				default:
					// Check if metadata type exists in the users profile
					if (in_array($metadataObj->getMetadataTypeID(),$userMetaTypeIds)) {
						$this->addMeta(&$results, $metadataObj->getMediaTypeID(), $type_id, $metadataObj->getMetadataID(), 'value',$metadataObj->getMetadataValue(),$metadataCounter);
						$this->addMeta(&$results, $metadataObj->getMediaTypeID(), $type_id, $metadataObj->getMetadataID(), 'name',$metadataObj->getMetadataTypeName(),$metadataCounter);
						if ($metadataObj->getMetadataValue() != '') {
							$this->addMeta(&$results, $metadataObj->getMediaTypeID(), $type_id, $metadataObj->getMetadataID(), 'delete',true,$metadataCounter);
						}
					}
					break;
			}
		}
		
		
		// Check the results for missing metadata definitions
		foreach ($mediaTypes as $mediaTypeObj) {
			if (VCDUtils::getCurrentUser()->getPropertyByKey('USE_INDEX') && 
				!$this->isMetaAdded(&$hitArray,metadataTypeObj::SYS_MEDIAINDEX,$mediaTypeObj->getmediaTypeID())) {
					$this->addMeta(&$results, $mediaTypeObj->getmediaTypeID(), metadataTypeObj::SYS_MEDIAINDEX, null, 'value','',$metadataCounter);
					$this->addMeta(&$results, $mediaTypeObj->getmediaTypeID(), metadataTypeObj::SYS_MEDIAINDEX, null, 'delete',false,$metadataCounter);
					$this->addMeta(&$results, $mediaTypeObj->getmediaTypeID(), metadataTypeObj::SYS_MEDIAINDEX , null, 'name', metadataTypeObj::getSystemTypeMapping(metadataTypeObj::SYS_MEDIAINDEX),$metadataCounter);
					$metadataCounter++;
			}
			
			if (VCDUtils::getCurrentUser()->getPropertyByKey('NFO') && 
				!$this->isMetaAdded(&$hitArray,metadataTypeObj::SYS_NFO,$mediaTypeObj->getmediaTypeID())) {
					$this->addMeta(&$results, $mediaTypeObj->getmediaTypeID(), metadataTypeObj::SYS_NFO, null, 'value','',$metadataCounter);
					$this->addMeta(&$results, $mediaTypeObj->getmediaTypeID(), metadataTypeObj::SYS_NFO, null, 'delete',false,$metadataCounter);
					$this->addMeta(&$results, $mediaTypeObj->getmediaTypeID(), metadataTypeObj::SYS_NFO , null, 'name', metadataTypeObj::getSystemTypeMapping(metadataTypeObj::SYS_NFO),$metadataCounter);
					$metadataCounter++;
			}
			
			if (VCDUtils::getCurrentUser()->getPropertyByKey('PLAYOPTION') && 
				!$this->isMetaAdded(&$hitArray,metadataTypeObj::SYS_FILELOCATION,$mediaTypeObj->getmediaTypeID())) {
					$this->addMeta(&$results, $mediaTypeObj->getmediaTypeID(), metadataTypeObj::SYS_FILELOCATION, null, 'value','',$metadataCounter);
					$this->addMeta(&$results, $mediaTypeObj->getmediaTypeID(), metadataTypeObj::SYS_FILELOCATION, null, 'delete',false,$metadataCounter);
					$this->addMeta(&$results, $mediaTypeObj->getmediaTypeID(), metadataTypeObj::SYS_FILELOCATION , null, 'name', metadataTypeObj::getSystemTypeMapping(metadataTypeObj::SYS_FILELOCATION),$metadataCounter);
					$metadataCounter++;
			}
			
			if (is_array($userMeta)) {
				foreach ($userMeta as $metadataObj) {
					if (!$this->isMetaAdded(&$hitArray, $metadataObj->getMetadataTypeID(), $mediaTypeObj->getmediaTypeID())) {
						$this->addMeta(&$results, $mediaTypeObj->getmediaTypeID(), $metadataObj->getMetadataTypeID(), null, 'value','',$metadataCounter);
						$this->addMeta(&$results, $mediaTypeObj->getmediaTypeID(), $metadataObj->getMetadataTypeID(), null, 'delete',false,$metadataCounter);
						$this->addMeta(&$results, $mediaTypeObj->getmediaTypeID(), $metadataObj->getMetadataTypeID(), null, 'name', $metadataObj->getMetadataTypeName(),$metadataCounter);
						$metadataCounter++;
					}
				}
			}
			
			
			
		}
		
		$this->assign('itemMetadataList', $results);
		
	}
	
	private function isMetaAdded(&$hitArray, $metatype_id, $mediatype_id) {
		foreach ($hitArray as $item) {
			if ($item['mid'] == $mediatype_id && $item['id'] == $metatype_id) {
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Add metadata entry to the metadata page results
	 *
	 * @param array $arr | The page metadata array
	 * @param int $mediatypeId | The media typeId
	 * @param int $metadataTypeId | The metadata typeId
	 * @param int $metadataId | The metadata Id
	 * @param string $key | The metadata key
	 * @param mixed $value | The metadata value
	 */
	private function addMeta(&$arr, $mediatypeId, $metadataTypeId, $metadataId, $key, $value, $index) {
		$arr[$mediatypeId]['metadata'][$index][$key] = $value;
		// Set the id
		if (!isset($arr[$mediatypeId]['metadata'][$index]['id'])) {
			$arr[$mediatypeId]['metadata'][$index]['id'] = $metadataId;	
			$arr[$mediatypeId]['metadata'][$index]['mediatype_id'] = $mediatypeId;
			$arr[$mediatypeId]['metadata'][$index]['metadatatype_id'] = $metadataTypeId;
		}
		// Set the html id
		if ((strcmp($key,'name')==0) && (!isset($arr[$mediatypeId]['metadata'][$index]['htmlid']))) {
			$htmlid = 'meta:'.$value.':'.$metadataTypeId.':'.$mediatypeId.':'.$metadataId;
			$arr[$mediatypeId]['metadata'][$index]['htmlid'] = $htmlid;
		}
	}
	
	/**
	 * Assign the DVD settings to the page
	 *
	 */
	private function doDvdSettings() {

		if (is_null($this->dvdId)) return;
		
		// Assign the current DVD to the page
		$this->assign('itemCurrentDvd', $this->dvdId);
				
		$dvdObj = new dvdObj();
		$arrDVDMetaObj = metadataTypeObj::filterByMediaTypeID($this->metadata, $this->dvdId);
		$arrDVDMetaObj = metadataTypeObj::getDVDMeta($arrDVDMetaObj);
			
		$dvd_region = VCDUtils::getDVDMetaObjValue($arrDVDMetaObj, metadataTypeObj::SYS_DVDREGION);
		$dvd_format = VCDUtils::getDVDMetaObjValue($arrDVDMetaObj, metadataTypeObj::SYS_DVDFORMAT);
		$dvd_aspect = VCDUtils::getDVDMetaObjValue($arrDVDMetaObj, metadataTypeObj::SYS_DVDASPECT);
		$dvd_audio =  VCDUtils::getDVDMetaObjValue($arrDVDMetaObj, metadataTypeObj::SYS_DVDAUDIO);
		$dvd_subs =   VCDUtils::getDVDMetaObjValue($arrDVDMetaObj, metadataTypeObj::SYS_DVDSUBS);
		$dvd_lang =   VCDUtils::getDVDMetaObjValue($arrDVDMetaObj, metadataTypeObj::SYS_DVDLANG);
		
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
		
		if (strcmp($dvd_lang, '') != 0) {
			$dvd_lang = explode('#', $dvd_lang);
		} else {
			$dvd_lang = array();
		}
		
		$results = array();
		foreach ($dvdObj->getRegionList() as $id => $region) { $results[$id] = $id.". ".$region; }
		$this->assign('itemRegionList',$results);
		$this->assign('itemRegion', $dvd_region);
		
		$this->assign('itemFormatList', $dvdObj->getVideoFormats());
		$this->assign('itemFormat', $dvd_format);
		
		$this->assign('itemAspectList', $dvdObj->getAspectRatios());
		$this->assign('itemAspect', $dvd_aspect);

		// Assign audiochannels
		if (sizeof($dvd_audio)>0) {
			$selectedAudio = array();
			foreach ($dvd_audio as $key) { $selectedAudio[$key] = $dvdObj->getAudio($key);}
			$this->assign('itemAudioListSelected', $selectedAudio);
			$dvd_audio =& $selectedAudio;
		}
		$this->assign('itemAudioList', array_diff($dvdObj->getAudioList(), $dvd_audio));
		
		// Assign subtitles
		if (sizeof($dvd_subs)>0) {
			$selectedSubs = array();
			foreach ($dvd_subs as $key) { $selectedSubs[$key] = $dvdObj->getLanguage($key);}
			$this->assign('itemSubtitleListSelected', $selectedSubs);
			$dvd_subs =& $selectedSubs;
		} 
		$this->assign('itemSubtitleList', array_diff($dvdObj->getLanguageList(true), $dvd_subs));
		
		// Assign spoken languages
		if (sizeof($dvd_lang)>0) {
			$selectedLang = array();
			foreach ($dvd_lang as $key) { $selectedLang[$key] = $dvdObj->getLanguage($key);}
			$this->assign('itemSpokenListSelected', $selectedLang);
			$dvd_lang =& $selectedLang;
		} 
		$this->assign('itemSpokenList', array_diff($dvdObj->getLanguageList(true), $dvd_lang));

		/*
		Display the current DVD selected media, if user owns only 1 DVD based medium
		The item is printed out as text, otherwise we need to create a dropdown with
		the option to also edit the other DVD based mediums.
		*/
		
		$mediaTypes = $this->userCopies['mediaTypes'];
		if (is_array($mediaTypes)) {
			if (sizeof($mediaTypes)==1) {
				$this->assign('itemDvdTypeList', $mediaTypes[0]->getDetailedName());
			} elseif (sizeof($mediaTypes)>1) {
				// Could be a mix of dvd types and non dvd types .. need to filter out
				// non dvd types
				$dvdTypes = array();
				foreach ($mediaTypes as $mediaTypeObj) {
					if (VCDUtils::isDVDType(array($mediaTypeObj))) {
						$dvdTypes[] = $mediaTypeObj;
					}
				}
				if (sizeof($dvdTypes)==1) {
					$this->assign('itemDvdTypeList', $dvdTypes[0]->getDetailedName());
				} else {
					$results = array();
					foreach ($dvdTypes as $mediaTypeObj) {
						$results[$mediaTypeObj->getmediaTypeID()] = $mediaTypeObj->getDetailedName();
					}
					$this->assign('itemDvdTypeList', $results);
				}
			}
		}
	}
	
	
	/**
	 * Assign the covers to the page
	 *
	 */
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
	
	
	private function doUserCopies() {
		
	
		// populate the dropdowns
		$results = array();
		$results[null] = VCDLanguage::translate('manager.addmedia');
		foreach (SettingsServices::getAllMediatypes() as $mediaTypeObj) {
			$results[$mediaTypeObj->getmediaTypeID()] = $mediaTypeObj->getDetailedName();
			if ($mediaTypeObj->getChildrenCount() > 0) {
				foreach ($mediaTypeObj->getChildren() as $childObj) { 
					$results[$childObj->getmediaTypeID()] = '&nbsp;&nbsp;'.$childObj->getDetailedName();
				}
			}
		}
		
		$this->assign('usercopyMediaList', array_slice($results,1, sizeof($results),true));
		$this->assign('usercopyMediaListNew', $results);
		
		$results = array();
		for($i=1;$i<11;$i++) {
			$results[$i] = $i;
		}
		$this->assign('usercopyYearList', $results);
				
		$results = array();
		$mediatypes = $this->userCopies['mediaTypes'];
		$discs = $this->userCopies['discs'];
		for ($i=0;$i<sizeof($mediatypes);$i++) {
			$results[$mediatypes[$i]->getmediaTypeID()] = array('cdcount' => $discs[$i],
				'mediaid' => 'userMediaType_'.$i, 'yearid' => 'usernumcds_'.$i);
		}
		
		
		$this->assign('itemUserMediaTypes',$results);
		$this->assign('itemUserCount', sizeof($mediatypes));
		$this->assign('itemTotalCount', $this->itemObj->getNumCopies());
		
		
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
		
		// Check for screenshots
		if (MovieServices::getScreenshots($this->itemObj->getID()) ) {
			$this->assign('itemScreenshots',true);
		}
		
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
	
	
	
	/**
	 * 
	 * Update item data functions below
	 * 
	 */
	
	/**
	 * Update the item.  Since there are so many things to update, each section
	 * has it's own update function, this function just calls them one by one.
	 *
	 */
	private function updateItem() {
		
		// Load the movie item if it's null
		if (is_null($this->itemObj)) {
			$this->loadItem();
			$this->userCopies = $this->itemObj->getInstancesByUserID(VCDUtils::getUserID());
		}
				
		// Update the basic data
		$this->updateBasics();
		
		// Update the metadata if instance has not been updated
		if (!$this->updateInstance()) {
			$this->updateMetadata();	
		}
			
		// Update the DVD settings if any ..
		$this->updateDvdSettings();
		
		// Handle uploaded files
		$this->updateUploadedFiles();

		// Update sourcesite data
		$this->updateSourcesiteData();
		
		// Update adult settings
		$this->updateAdultSettings();
		
		// Finally call update in the services
		MovieServices::updateVcd($this->itemObj);
			
	}


	/**
	 * Update the adult settings, such as subcategories and studio information
	 *
	 */
	private function updateAdultSettings()	{
		if (!$this->itemObj->isAdult()) {return;}
		
		$categoryList = $this->getParam('id_list',true);
		if (!is_null($categoryList)) {
	     	$subCatArr = split('#',$categoryList);
	     	foreach ($subCatArr as $adult_catid) {
	     		$adultCatObj = null;
	     		if (is_numeric($adult_catid)) {
	     			$adultCatObj = PornstarServices::getSubCategoryByID($adult_catid);
	     		}
	     		if ($adultCatObj instanceof porncategoryObj ) {
	     			$this->itemObj->addAdultCategory($adultCatObj);
	     		}
	     	}
	     }

	     if (is_numeric($this->getParam('studio',true)))  {
	     	$this->itemObj->setStudioID($this->getParam('studio',true));
	     }
	}
	
	/**
	 * Update the sourceSite data.. such as imdb details
	 *
	 */
	private function updateSourcesiteData() {
		if ((!is_null($this->sourceObj)) && ($this->itemObj->getExternalID() > 0)) {
			
			$this->sourceObj->setTitle($this->getParam('imdbtitle',true));
			$this->sourceObj->setAltTitle($this->getParam('imdbalttitle',true));
			$this->sourceObj->setRating($this->getParam('imdbgrade',true));
			$this->sourceObj->setRuntime($this->getParam('imdbruntime',true));
			$this->sourceObj->setDirector($this->getParam('imdbdirector',true));
			$this->sourceObj->setCountry($this->getParam('imdbcountries',true));
			$this->sourceObj->setGenre($this->getParam('imdbcategories',true));
			$this->sourceObj->setPlot($this->getParam('plot',true));
			$this->sourceObj->setCast($this->getParam('actors',true));
			
		}
	}
	
	/**
	 * Update basic data such as title, production year and movie category.
	 *
	 */
	private function updateBasics() {
		try {
		
			$title = $this->getParam('title',true);
			$year = $this->getParam('year',true);
			$categoryId = $this->getParam('category',true);
			$externalId = $this->getParam('externalId',true);
			
			if (!is_null($title)) {
				$this->itemObj->setTitle(trim($title));
			}
			
			if (is_numeric($year)) {
				$this->itemObj->setYear($year);
			}
			
			if (is_numeric($categoryId)) {
				$movieCategoryObj = SettingsServices::getMovieCategoryByID($categoryId);
				if ($movieCategoryObj instanceof movieCategoryObj ) {
	     			$this->itemObj->setMovieCategory($movieCategoryObj);
	     		}
			}
			
			if (!is_null($externalId) && ($this->itemObj->getSourceSiteID()>0)) {
				$this->itemObj->setSourceSite($this->itemObj->getSourceSiteID(), $externalId);
			}
			
	     
			
			
		} catch (Exception $ex) {
			throw $ex;
		} 
	}
	
	
	/**
	 *  Handle uploaded files, covers and metadata such as NFO's
	 *
	 */
	private function updateUploadedFiles() {
		try {
			
			// Set the allowed extensions for the upload
			$arrExt = array(VCDUploadedFile::FILE_JPEG, VCDUploadedFile::FILE_JPG, VCDUploadedFile::FILE_GIF,
							VCDUploadedFile::FILE_NFO, VCDUploadedFile::FILE_TXT );
			$VCDUploader = new VCDFileUpload($arrExt);
	
			if ($VCDUploader->getFileCount() > 0) {
	
				for ($i=0; $i<$VCDUploader->getFileCount(); $i++) {
					$fileObj = $VCDUploader->getFileAt($i);
					$cover_typeid = $fileObj->getHTMLFieldName();
	
		      		// Check if this uploaded file is a NFO file ..
		      		$nfostart = "meta:nfo";
		      		if (substr_count($cover_typeid, $nfostart) > 0)  {

		      			// Yeap it's a NFO file
	      				try {
	      					// Keep the original filename and do not overwrite
	      					$fileObj->setRandomFileName(false);
	      					$fileObj->setOverWrite(false);

		      				if (!$fileObj->move(NFO_PATH)) {
		      					throw new VCDException("Could not move NFO file {$fileObj->getFileName()} to NFO folder!");
		      				} else {
		      					// Everything is OK ... add the metadata
								$entry = explode(":", $cover_typeid);
								$metadataName = $entry[1];
								$metadatatype_id = $entry[2];
								$mediatype_id = $entry[3];

								// Create the MetadataObject
								$obj = new metadataObj(array('',$this->itemObj->getID(), VCDUtils::getUserID(), $metadataName, $fileObj->getFileName()));
								$obj->setMetaDataTypeID($metadatatype_id);
								$obj->setMediaTypeID($mediatype_id);
								// And save to DB
								SettingsServices::addMetadata($obj, true);
		      				}

	      				} catch (Exception $ex) {
	      					VCDException::display($ex,true);
	      					exit();
	      				}


		      		} else {
		      			$coverType = CoverServices::getCoverTypeById($cover_typeid);

		      			try {
		      				$fileObj->move(TEMP_FOLDER);
		      			} catch (Exception $ex) {
		      				VCDException::display($ex, true);
		      				exit();
		      			}

		      			
		      			// Resize the image if this is thumbnail
		      			if ($coverType->isThumbnail()) {
			      			$fileLocation = $fileObj->getFileLocation();
							$fileExtension = $fileObj->getFileExtenstion();
				  	   		$im = new Image_Toolbox($fileLocation);
				  	   		if ($this->itemObj->getCategoryID() == SettingsServices::getCategoryIDByName('adult')) {
				  	   			$im->newOutputSize(0,190);	
				  	   		} else {
				  	   			$im->newOutputSize(0,140);	
				  	   		}
							$im->save(TEMP_FOLDER.$fileObj->getFileName(), $fileExtension);
		      			}

			      		$imginfo = array('', $this->itemObj->getID(), $fileObj->getFileName(), $fileObj->getFileSize(), VCDUtils::getUserID(),
			      					date(time()), $cover_typeid, $coverType->getCoverTypeName(), '');
			      		$cdcover = new cdcoverObj($imginfo);
			      		$this->itemObj->addCovers(array($cdcover));
		      		}
				}
			}
			
			
			
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	/**
	 * Update/add new metadata
	 *
	 */
	private function updateMetadata() {
		
		$arrMetaData = array();
		foreach ($_POST as $key => $value) {
			if ((int)substr_count($key, 'meta') == 1) {
		 		array_push($arrMetaData, array('key' => $key, 'value' => $value));
		 	}
		}

		$itemId = $this->getParam('vcd_id');
		
		if (sizeof($arrMetaData) > 0) {
			$metadataCommit = array();
			foreach ($arrMetaData as $itemArr) {
				$key   = $itemArr['key'];
				$value = $itemArr['value'];
				$entry = explode(":", $key);
				$metadataName = $entry[1];
				$metadatatype_id = $entry[2];
				$mediatype_id = $entry[3];
				$record_id = isset($entry[4]) ? $entry[4] : null;


				// Skip empty metadata
				if (strcmp($value, "") != 0 && $metadatatype_id != metadataTypeObj::SYS_NFO) {
					$obj = new metadataObj(array($record_id, $itemId, VCDUtils::getUserID(), $metadataName, $value));
					$obj->setMetaDataTypeID($metadatatype_id);
					$obj->setMediaTypeID($mediatype_id);
					array_push($metadataCommit, $obj);
				}
			}
			SettingsServices::addMetadata($metadataCommit, true);
		}
	}

	/**
	 * Update the DVD settings for the selected DVD item
	 *
	 */
	private function updateDvdSettings() {
		
		$dvdId = $this->getParam('current_dvd',true);
		if (!is_numeric($dvdId)) return;
		
		$id = $this->itemObj->getID();
		
		$dvd_region = $this->getParam('dvdregion',true);
    	$dvd_format = $this->getParam('dvdformat',true);
    	$dvd_aspect = $this->getParam('dvdaspect',true);
    	$audio_list = $this->getParam('audio_list',true);
    	$sub_list = $this->getParam('sub_list',true);
    	$spoken_list = $this->getParam('spoken_list',true);

    	$arrDVDMeta = array();
    	$arrDVDMeta[] = new metadataObj(array('', $id, VCDUtils::getUserID(), metadataTypeObj::SYS_DVDREGION, $dvd_region));
    	$arrDVDMeta[] = new metadataObj(array('', $id, VCDUtils::getUserID(), metadataTypeObj::SYS_DVDFORMAT, $dvd_format));
    	$arrDVDMeta[] = new metadataObj(array('', $id, VCDUtils::getUserID(), metadataTypeObj::SYS_DVDASPECT, $dvd_aspect));
    	$arrDVDMeta[] = new metadataObj(array('', $id, VCDUtils::getUserID(), metadataTypeObj::SYS_DVDAUDIO, $audio_list));
    	$arrDVDMeta[] = new metadataObj(array('', $id, VCDUtils::getUserID(), metadataTypeObj::SYS_DVDSUBS, $sub_list));
    	$arrDVDMeta[] = new metadataObj(array('', $id, VCDUtils::getUserID(), metadataTypeObj::SYS_DVDLANG, $spoken_list));
    	
    	foreach ($arrDVDMeta as $metadataObj) {
    		$metadataObj->setMediaTypeID($dvdId);
    		$metadataObj->setMetadataTypeName(metadataTypeObj::getSystemTypeMapping($metadataObj->getMetadataTypeID()));
    	}
    	    	
    	// Add / Update the DVD metadata
    	SettingsServices::addMetadata($arrDVDMeta, true);
	}
	
	/**
	 * Update movie instance if it was changed, and/or add new instance if so was selected.
	 *
	 * @return bool | true if instance has been updated.
	 */
	private function updateInstance() {
		try {
			
			
			$instanceUpdated = false;
					
			if (sizeof($this->userCopies) > 0) {
				$arrMediaTypes = $this->userCopies['mediaTypes'];
				$arrNumcds = $this->userCopies['discs'];
				
				// Loop through the instances and compare
				for ($i = 0; $i < sizeof($arrMediaTypes); $i++) {
					$postedMediaType = $this->getParam('userMediaType_'.$i,true);
					$media_id = $arrMediaTypes[$i]->getmediaTypeID();
					$postedCDCount = $this->getParam('usernumcds_'.$i,true);
					if (!($media_id == $postedMediaType && $arrNumcds[$i] == $postedCDCount)) {
						
						for($j = 0; $j < sizeof($arrMediaTypes); $j++) {
							$MediaType = $arrMediaTypes[$j];
							if (($MediaType->getmediaTypeID() == $postedMediaType) && ($i != $j)) {
								throw new VCDProgramException('You cannot add two exact copies.');
							} 
						}
						
						// Either media type or numCD's have been updated .. update entry to DB
						MovieServices::updateVcdInstance($this->itemObj->getID(), $postedMediaType, 
							$media_id, $postedCDCount, $arrNumcds[$i]);
						$instanceUpdated = true;
					}
				}
			}
			
			
			// Check if user has added a cd item
			if (!is_null($this->getParam('mediatype_new',true))) {
				$postedMediaType = $this->getParam('mediatype_new',true);
				$postedCDCount = $this->getParam('year_new',true);
				foreach($arrMediaTypes as $MediaType) {
					if ($MediaType->getmediaTypeID() == $postedMediaType) {
						throw new VCDProgramException('You cannot add two exact copies.');
					} 
					
					// Add new instance
					MovieServices::addVcdToUser(VCDUtils::getUserID(), $this->itemObj->getID(), 
						$postedMediaType, $postedCDCount);
				}
			}
			
			return $instanceUpdated;
			
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Check if the current user has access to the managager page.
	 * User has to be either an owner of the item or a VCD-db admin.
	 * Returns true if user can procees and load the page.
	 *
	 * @return bool
	 */
	private function checkPermissions() {
		if (VCDUtils::isOwner($this->itemObj)) {
			return true;
		} else {
			return VCDUtils::getCurrentUser()->isAdmin();
		}
	}
	
	
	/**
	 * Refetch movie data from the original source site.
	 *
	 */
	private function doRefetch() {
		try {
			$sourceObj = SettingsServices::getSourceSiteByID($this->itemObj->getSourceSiteID());
			$fetchClassName = $sourceObj->getClassName();
			$fetchClass = VCDClassFactory::loadClass($fetchClassName);
			$status = $fetchClass->fetchItemByID($this->itemObj->getExternalID());
			if ($status == VCDFetch::ITEM_OK) {
				$fetchClass->fetchValues();
				$fetchedObj = $fetchClass->getFetchedObject();
				$this->sourceObj = $fetchedObj;
				$cast = $fetchedObj->getCast(false);
				$countries = $fetchedObj->getCountry();
				if (is_array($cast)) {
					$this->sourceObj->setCast(implode(chr(13),$cast));	
				}
				if (is_array($countries)) {
					$this->sourceObj->setCountry(implode(',',$countries));
				}
				
				// check for updated thumbnail if movie has none ..
				if (is_null($this->itemObj->getCover('thumbnail'))) {
					try {
						$fetchedCover = $fetchedObj->getImage();
						$coverObj = $this->addImageFromRefetch($fetchedCover);
						if ($coverObj instanceof cdcoverObj) {
							// Adding cover was a success .. add it to the template
							$this->assign('itemThumbnail',$coverObj->showImage());
						}
					} catch (Exception $ex) {}
					
				}
				
				// Update the title as well, override the assignment
				$this->assign('itemTitle',$this->sourceObj->getTitle());
				
			}
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	
	
	/**
	 * Add thumbnail from the refetch process, only if movie did not have an existing thumbnail.
	 * Returns the inserted thumbnail, upon error, NULL is returned.
	 *
	 * @param string $strImageFile | The url to the image file on a remote server
	 * @return cdcoverObj | The inserted cdcoverObj
	 */
	private function addImageFromRefetch($strImageFile) {

		if (!is_null($strImageFile) && strlen($strImageFile)>3) {
			try {
				
				$thumbnailType = CoverServices::getCoverTypeByName('thumbnail');
				$savedFile = VCDUtils::grabImage($strImageFile,true,VCDDB_BASE.DIRECTORY_SEPARATOR.TEMP_FOLDER);
				
				$cover = new cdcoverObj(null);
				$cover->setOwnerId(VCDUtils::getUserID());
				$cover->setVcdId($this->itemObj->getID());
				$cover->setCoverTypeID($thumbnailType->getCoverTypeID());
				$cover->setCoverTypeName($thumbnailType->getCoverTypeName());
				
				
				// Create temporary unique ID for the image
                $imageName = VCDUtils::generateUniqueId();
                $newName = $imageName.'.'.VCDUtils::getFileExtension($savedFile);
                $cover->setFilename($newName);
                $cover->setFilesize(filesize(VCDDB_BASE.DIRECTORY_SEPARATOR.TEMP_FOLDER.$savedFile));
				
				// Store the image in the right container
				if ((bool)SettingsServices::getSettingsByKey('DB_COVERS')) {
				
					$imageClass = new VCDImage();
                    if (VCDUtils::getFileExtension($cover->getFilename()) == 'gif') {
						$imageType = "gif";
					} else {
                    	$imageType = "pjpeg";
					}

                    // Use File info
                    $arrFileInfo = array('name' => $newName, 'type' => 'image/'.$imageType);
                    $image_id = $imageClass->addImageFromPath(VCDDB_BASE.DIRECTORY_SEPARATOR.TEMP_FOLDER.$savedFile, $arrFileInfo, true);
                    
                    // Set the DB imageID to the cover
                    $cover->setImageID($image_id);
					
				} else {
					rename(VCDDB_BASE.DIRECTORY_SEPARATOR.TEMP_FOLDER.$savedFile,VCDDB_BASE.DIRECTORY_SEPARATOR.THUMBNAIL_PATH.$newName);
				}
				
				CoverServices::addCover($cover);
				
				// Get the inserted cover
				$covers = CoverServices::getAllCoversForVcd($this->itemObj->getID());
				if (is_array($covers) && sizeof($covers)>0) {
					foreach ($covers as $coverObj) {
						if ($coverObj->getCoverTypeID() == $thumbnailType->getCoverTypeID()) {
							// Override doThumbnail
							return $coverObj;
						}
					}
				}
				// Nothing found .. return null
				return null;
				
			} catch (Exception $ex) {}
		}
		
	}
	
}

?>