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
 * @version $Id: VCDPageBaseItem.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php

/**
 * This class acts as a base controller for all the different items VCD-db can contain
 * but share common data and methods..
 * 
 *
 */

abstract class VCDPageBaseItem extends VCDBasePage {
	
	/**
	 * The baseItem object
	 *
	 * @var vcdObj
	 */
	protected $itemObj;
	
	/**
	 * The IMDB object container
	 *
	 * @var imdbObj
	 */
	protected $sourceObj = null;
	
	
	/**
	 * Skip loading of extendted properties, used by the manager window
	 *
	 * @var bool
	 */
	protected $skipExtended = false;
	
	
	public function __construct(_VCDPageNode $node) {
		parent::__construct($node);
		
		// Check for _GET requests on the page
		$this->handleGetRequest();
		
		// load the requested item
		$this->loadItem();
		$this->doCoreElements();
		
	}
	
		
	
	/**
	 * Handle all POST requests to this controller.
	 */
	public function handleRequest() {
		if (!VCDUtils::isLoggedIn()) {
			return;
		}
		
		$action = $this->getParam('action');
		if (!is_null($action)) {
					
			switch ($action) {
				case 'addcomment':
					$this->doAddComment();
					break;
			
				default:
					break;
			}
		
		}
	}
	
	
	private function handleGetRequest() {
		
		$action = $this->getParam('action');
				
		if (!is_null($action)) {
				
			switch (strtolower($action)) {
				case 'delcomment':
					$this->doDeleteComment();
					break;
				case 'seenlist':
					$this->doSetSeenItem();
					break;
				case 'addtowishlist':
					$this->doAddToWishlist();
					break;
				default:
					break;
			}
		}		
	}
	
	
	
	/**
	 * Assigns the data from the sourceObject, needs to be called from the child controller
	 *
	 */
	protected function doSourceSiteElements() {
		if (!is_null($this->sourceObj)) {
			$this->assign('sourceTitle',$this->sourceObj->getTitle());
			$this->assign('sourceAltTitle',$this->sourceObj->getAltTitle());
			$this->assign('sourceGrade',$this->sourceObj->getRating());
			$this->assign('sourceCountries',$this->sourceObj->getCountry());
			$this->assign('sourceCategoryList',$this->sourceObj->getGenre());
			$this->assign('sourceRuntime',$this->sourceObj->getRuntime());
			$this->assign('sourceActors', $this->sourceObj->getCast(true));
			$this->assign('sourceDirector', $this->sourceObj->getDirectorLink());
			$this->assign('sourcePlot', $this->sourceObj->getPlot());
		}
	}
	
	
	/**
	 * Assign the global attributes such as title,production year,category .. etc
	 *
	 */
	private function doCoreElements() {
		
		$this->assign('itemTitle',$this->itemObj->getTitle());
		$this->assign('itemYear',$this->itemObj->getYear());
		$this->assign('itemCategoryName',$this->itemObj->getCategory()->getName(true));
		$this->assign('itemCategoryId',$this->itemObj->getCategoryID());
		$this->assign('itemCopyCount',$this->itemObj->getNumCopies());
		
		// Assign base data
		$this->doThumbnail();
		$this->doMetadata();
		$this->doCopiesList();
		$this->doSourceSiteLink();
		$this->doCovers();
		
		if (!$this->skipExtended) {
			$this->doComments();
			$this->doWishlist();
			$this->doSimilarList();
			$this->doSeenLink();
			$this->doManagerLink();
		}
		

		// Set the item ID
		$this->assign('itemId', $this->itemObj->getID());
		
	}
	
	
	/**
	 * Assign thumbnail data to the view
	 *
	 */
	private function doThumbnail() {
		$coverObj = $this->itemObj->getCover('thumbnail');
		if (!is_null($coverObj)) {
			$this->assign('itemThumbnail',$coverObj->showImage());
		} 
	}	
	
	
	/**
	 * Assign comments data to the view
	 *
	 */
	private function doComments() {
		
		$comments = SettingsServices::getAllCommentsByVCD($this->itemObj->getID());
		if (is_array($comments)) {
			$results = array();
			foreach ($comments as $commentObj) {
				$results[] = array(
					'id' => $commentObj->getID(),
					'date' => $commentObj->getDate(),
					'private' => $commentObj->isPrivate(),
					'comment' => $commentObj->getComment(),
					'owner' => $commentObj->getOwnerName(),
					'isOwner' => $commentObj->getOwnerID() == VCDUtils::getUserID()
				);
			}
			$this->assign('itemComments',$results);
		}
	}
	
	/**
	 * Assign metadata to the view
	 *
	 */
	private function doMetadata() {
		
		$metadata = SettingsServices::getMetadata($this->itemObj->getID(),VCDUtils::getUserID());
		// Filter out non user metadata
		$metadata = metadataTypeObj::filterOutSystemMeta($metadata);
			
		if (is_array($metadata) && sizeof($metadata)>0) {
			
			foreach ($metadata as $metaObj) { 
				$results[$metaObj->getMetadataID()] = 
					array('medianame' => SettingsServices::getMediaTypeByID($metaObj->getMediaTypeID())->getDetailedName(), 
					'name' => $metaObj->getMetadataName(), 'text' => $this->doMetadataFormat($metaObj));
			}
			
			$this->assign('itemMetadata', $results);
			
		}
	}
	
	
	/**
	 * Render the display values based on metadataType
	 *
	 */
	private function doMetadataFormat(metadataObj $obj) {
		
		
		
		switch ($obj->getMetadataTypeID()) {
			case metadataTypeObj::SYS_FILELOCATION:
				$cmd = '<a href="#" onclick="return false"><img src="images/icons/control_play.png" title="Play" border="0" onclick="playMovie(%s);return false"/></a>';
				return sprintf($cmd, $obj->getMetadataID());
				break;
		
			default:
				return $obj->getMetadataValue();
				break;
		}
		
	}
	
	
	/**
	 * Assign list of available copies to the view
	 *
	 */
	private function doCopiesList() {
		
		$metadata = SettingsServices::getMetadata($this->itemObj->getID(), null, null, null);
		$layerResults = $this->doCopiesItemLayers($this->itemObj, $metadata);
		$this->assign('itemLayers', $layerResults);
		
		
		$itemInstances = $this->itemObj->getInstanceArray();
		$ownersList = $itemInstances['owners'];
		$mediaList = $itemInstances['mediatypes'];
		
		$results = array();
		for ($i=0; $i<sizeof($ownersList); $i++) {
			
			$mediaTypeObj = $mediaList[$i];
			$ownerObj = $ownersList[$i];
			
			$results[] = array(
				'owner'		=> $ownerObj->getUserName(),
				'date'		=> 'somedate',
				'cdcount'	=> 4,
				'mediatype' => $mediaTypeObj->getDetailedName(),
				'dvdspecs'	=> $this->doCopiesDvdList($ownerObj,$mediaTypeObj,$metadata),
				'nfo'		=> $this->doCopiesNfoList($ownerObj, $mediaTypeObj, $metadata)
			);
		}
		
		$this->assign('itemCopies', $results);
		
		
	}
	
	
	/**
	 * Create the HTML for the hidden layers that are activated onmouseover in the list
	 *
	 * @param cdobj $vcdObj
	 * @param array $metadataArr | The metadata array
	 * @return string | The generated HTML
	 */
	private function doCopiesItemLayers(cdobj &$vcdObj, &$metadataArr) {
		
		$results = array();
		
		// First get all available owners and mediatypes
		$arrData = $vcdObj->getInstanceArray();
		if (isset($arrData['owners']) && isset($arrData['mediatypes'])) {
	
			$arrOwners = $arrData['owners'];
			$arrMediatypes = $arrData['mediatypes'];
			$i = 0;
	
			foreach ($arrMediatypes as $mediaTypeObj) {
	
				if ($mediaTypeObj instanceof mediaTypeObj && VCDUtils::isDVDType(array($mediaTypeObj))) {
	
					$arrDVDMeta = metadataTypeObj::filterByMediaTypeID($metadataArr, $mediaTypeObj->getmediaTypeID(), $arrOwners[$i]->getUserId());
					$arrDVDMeta = metadataTypeObj::getDVDMeta($arrDVDMeta);
	
					if (is_array($arrDVDMeta) && sizeof($arrDVDMeta) > 0) {
	
						$dvdObj = new dvdObj();
	
						$dvd_region = VCDUtils::getDVDMetaObjValue($arrDVDMeta, metadataTypeObj::SYS_DVDREGION);
						$dvd_format = VCDUtils::getDVDMetaObjValue($arrDVDMeta, metadataTypeObj::SYS_DVDFORMAT);
						$dvd_aspect = VCDUtils::getDVDMetaObjValue($arrDVDMeta, metadataTypeObj::SYS_DVDASPECT);
						$dvd_audio  = VCDUtils::getDVDMetaObjValue($arrDVDMeta, metadataTypeObj::SYS_DVDAUDIO);
						$dvd_subs   = VCDUtils::getDVDMetaObjValue($arrDVDMeta, metadataTypeObj::SYS_DVDSUBS);
	
						if (strcmp($dvd_region, "") != 0) {
							//$dvd_region = $dvd_region.". (". $dvdObj->getRegion($dvd_region) . ")";
							$dvd_region = $dvd_region. ".";
						}
	
						if (strcmp($dvd_aspect, "") != 0) {
							$dvd_aspect = $dvdObj->getAspectRatio($dvd_aspect);
						}
	
						if (strcmp($dvd_audio, "") != 0) {
							$arrAudio = explode("#", $dvd_audio);
							$dvd_audio = "<ul class=\"ulnorm\">";
							foreach ($arrAudio as $audioType) {
								$dvd_audio .= "<li class=\"linorm\">" . $dvdObj->getAudio($audioType) . "</li>";
							}
							$dvd_audio .= "</ul>";
						}
	
						if (strcmp($dvd_subs, "") != 0) {
							$arrSubs = explode("#", $dvd_subs);
							$dvd_subs = "<ul class=\"ulnorm\">";
							foreach ($arrSubs as $subTitle) {
								$imgsource = $dvdObj->getCountryFlag($subTitle);
								$langName = $dvdObj->getLanguage($subTitle);
								$img = "<img src=\"{$imgsource}\" alt=\"{$langName}\" hspace=\"1\"/>";
								$dvd_subs .= "<li class=\"linorm\">".$img . " " . $langName . "</li>";
							}
							$dvd_subs .= "</ul>";
						}
	
						$divid = "x". $mediaTypeObj->getmediaTypeID()."x".$arrOwners[$i]->getUserId();
						
						$results[] = array(
							'layer'	 => $divid,
							'region' => $dvd_region,
							'format' => $dvd_format,
							'aspect' => $dvd_aspect,
							'audio'	 => $dvd_audio,
							'subs'	 => $dvd_subs
						);
					}
				}
				$i++;
			}
		} 
		
		return $results;
		
	}
	
	
	/**
	 * Generate the Image and associated layer call for the available copies
	 *
	 * @param userObj $userObj
	 * @param mediaTypeObj $mediaTypeObj
	 * @param array $metaDataArr
	 * @return string
	 */
	private function doCopiesDvdList(userObj $userObj, mediaTypeObj $mediaTypeObj, &$metaDataArr = null) {

		$divid = "";
		$arrDVDMeta = null;
		if (!is_null($metaDataArr)) {
			$arrDVDMeta = metadataTypeObj::filterByMediaTypeID($metaDataArr, $mediaTypeObj->getmediaTypeID(), $userObj->getUserID());
			$arrDVDMeta = metadataTypeObj::getDVDMeta($arrDVDMeta);
			$divid = "x".$mediaTypeObj->getmediaTypeID() ."x". $userObj->getUserId();
		}
		$dhtml = "this.T_SHADOWWIDTH=1;this.T_STICKY=1;this.T_ABOVE=true;this.T_LEFT=false; this.T_WIDTH=284;";
		$img = "<img src=\"images/icon_item.gif\" onmouseover=\"{$dhtml}return escape(showDVD('{$divid}'))\" border=\"0\" hspace=\"1\" alt=\"\" align=\"middle\"/>";
	
		if (VCDUtils::isDVDType(array($mediaTypeObj)) && !is_null($arrDVDMeta) && sizeof($arrDVDMeta) > 0) {
			return $img;
		} else {
			return "&nbsp;";
		}
	}


	/**
	 * Create placeholder for the NFO file associated with the movie instance
	 *
	 * @param userObj $userObj
	 * @param mediaTypeObj $mediaTypeObj
	 * @param array $metaDataArr
	 * @return string
	 */
	private function doCopiesNfoList(userObj $userObj, mediaTypeObj $mediaTypeObj, &$metaDataArr = null) {
	
		$hasNFO = false;
		if (!is_null($metaDataArr)) {
			$currMeta = metadataTypeObj::filterByMediaTypeID($metaDataArr, $mediaTypeObj->getmediaTypeID(), $userObj->getUserID());
			// Search for NFO metadata ..
			$useNfoImage = (bool)$userObj->getPropertyByKey('NFO_IMAGE');
			if (is_array($currMeta) && sizeof($currMeta) > 0) {
				foreach ($currMeta as $metadataObj) {
					if ($metadataObj->getMetadataTypeID() == metadataTypeObj::SYS_NFO) {
						$nfofile = NFO_PATH . $metadataObj->getMetaDataValue();
						if ($useNfoImage) {
							$js = "window.open('?page=file&amp;nfo={$metadataObj->getMetaDataId()}');return false;";
						} else {
							$js = "window.open('{$nfofile}');return false;";
						}
						$img = "<a href=\"#\" onclick=\"{$js};\"><img src=\"images/icon_nfo.gif\" border=\"0\" hspace=\"1\" alt=\"NFO\" align=\"middle\"/></a>";
						$hasNFO = true;
						break;
					}
				}
			}
		}
	
	
		if ($hasNFO) {
			return $img;
		} else {
			return "&nbsp;";
		}
	}
	
	
	
	
	
	/**
	 * Assign the "Add to wishlist" link to the view
	 *
	 */
	private function doWishlist() {
		if (VCDUtils::isLoggedIn()) {
			if (SettingsServices::isOnWishList($this->itemObj->getID())) {
				$this->assign('isOnWishList',true);		
			}
		}
	}
	
	/**
	 * Assign the "Similar items" dropdown data to the view
	 *
	 */
	private function doSimilarList() {
		
		$list = MovieServices::getSimilarMovies($this->itemObj->getID());
		$results = array();
		if (is_array($list) && sizeof($list) > 0) {
			$results[null] = VCDLanguage::translate('misc.select');
			foreach ($list as $obj) {
				$results[$obj->getId()] = $obj->getTitle();
			}
			$this->assign('itemSimilar',$results);
		}
		
	}
	
	/**
	 * Assign the "Seen movie" link to the view
	 *
	 */
	private function doSeenLink() {
		
		if (VCDUtils::isLoggedIn() && VCDUtils::getCurrentUser()->getPropertyByKey('SEEN_LIST')) {
   			$list = SettingsServices::getMetadata($this->itemObj->getID(), VCDUtils::getUserID(), metadataTypeObj::SYS_SEENLIST);
			if (sizeof($list) == 1 && ($list[0]->getMetadataValue() == 1)) {
				$this->assign('itemSeen',true);
			}
		}
	}
	
	/**
	 * Assign the "Manager" link to the view
	 *
	 */
	private function doManagerLink() {
		if (VCDUtils::hasPermissionToChange($this->itemObj)) {
			$this->assign('isOwner', true);
		}
	}
	
	/**
	 * Assign the sourcesite link and image to the view
	 *
	 */
	private function doSourceSiteLink() {
		
		$sourceSiteID = $this->itemObj->getSourceSiteID();
		$external_id = $this->itemObj->getExternalID();
		
		if (is_numeric($sourceSiteID) && strcmp($external_id,'') != 0) {
			$sourceSiteObj = SettingsServices::getSourceSiteByID($sourceSiteID);	
			if ($sourceSiteObj instanceof sourceSiteObj ) {
				$image = "images/logos/".$sourceSiteObj->getImage();
				$link = str_replace("#", $external_id, $sourceSiteObj->getCommand());
				$html = "<a href=\"%s\" target=\"_blank\"><img src=\"%s\" border=\"0\"/></a>";
				$imgstring = sprintf($html, $link, $image);
				$this->assign('itemSourceSiteLogo',$imgstring);
			}
		}
	}
	
	
	/**
	 * Display the play option for launchable movies
	 *
	 */
	private function doPlaylist() {
		
		
		
	}
	
	
	/**
	 * Assign available covers data to the view
	 *
	 */
	private function doCovers() {
		
		$covers = $this->itemObj->getCovers();
		if (is_array($covers)) {
			$results = array();
			foreach ($covers as $coverObj) {
				if (!$coverObj->isThumbnail()) {
					
					$results[] = array(
						'id' => $coverObj->getId(),
						'title' => $this->itemObj->getTitle(),
						'covertype' => $coverObj->getCoverTypeName(),
						'size' => human_file_size($coverObj->getFilesize()),
						'link' => '?page=file&cover_id='.$coverObj->getId()
					);
				}
			}
			$this->assign('itemCovers',$results);
		}
		
	}
	
	/**
	 * Load the requested objects bases on query parameters.
	 * If parameter is incorrect, user is redirected to the frontpage.
	 *
	 */
	private function loadItem() {
		$itemId = $this->getParam('vcd_id');
		if (!is_numeric($itemId)) {
			redirect();
			exit();
		}

		$this->itemObj = MovieServices::getVcdByID($itemId);
		if (!$this->itemObj instanceof cdObj) {
			redirect();
			exit();
		}
		
		
		$this->sourceObj = $this->itemObj->getIMDB();
		if (!is_null($this->sourceObj)) {
			$this->assign('itemSource',true);
		}
		
	}
	
	
	/**
	 * 
	 * 
	 * Internal POST methods below ..
	 * 
	 * 
	 */
	
	
	/**
	 * Add new comment
	 *
	 */
	private function doAddComment() {
	
		$comment = $this->getParam('comment',true);
		$private = $this->getParam('private',true, '0');
		$itemId = $this->getParam('vcd_id',true);
						
		if ((!is_null($comment) && !is_null($itemId) && (is_numeric($itemId)))) {
			$commentObj = new commentObj(array('',$itemId, VCDUtils::getUserID(), '', VCDUtils::stripHTML($comment), $private));
			SettingsServices::addComment($commentObj);
		}
		
		redirect('?page=cd&vcd_id='.$itemId);
		exit();
		
		
	}
	
	/**
	 * Delete comment
	 *
	 */
	private function doDeleteComment() {
				
				
		$comment_id = $this->getParam('cid');
		$item_id = $this->getParam('vcd_id');
		if (!is_null($comment_id) && is_numeric($comment_id)) {
			
			$commentObj = SettingsServices::getCommentByID($comment_id);
			if (($commentObj instanceof commentObj) && ($commentObj->getOwnerID() == VCDUtils::getUserID())) {
				SettingsServices::deleteComment($comment_id);
				redirect('?page=cd&vcd_id='.$item_id);
				exit();
			}
		}
	}
	
	
	/**
	 * Add or remove item from the users seenlist
	 *
	 */
	private function doSetSeenItem() {
	
		$itemId = $this->getParam('vcd_id');
		$status = $this->getParam('flag');
		
		if (VCDUtils::isLoggedIn() && is_numeric($itemId) && is_numeric($status)) {
			
			$arr = SettingsServices::getMetadata($itemId, VCDUtils::getUserID(), metadataTypeObj::SYS_SEENLIST);
			if (is_array($arr) && sizeof($arr) == 1) {
				// update the Obj
				$obj = $arr[0];
				$obj->setMetadataValue($status);
				SettingsServices::updateMetadata($obj);
			} else {
				// create new Obj
				$obj = new metadataObj(array('',$itemId, VCDUtils::getUserID(), metadataTypeObj::SYS_SEENLIST , $status));
				SettingsServices::addMetadata($obj);
			}
			
			redirect('?page=cd&vcd_id='.$itemId);
			exit();
		}
	}
	
	/**
	 * Add item to users wishlist
	 *
	 */
	private function doAddToWishlist() {
		$itemId = $this->getParam('vcd_id');
		SettingsServices::addToWishList($itemId, VCDUtils::getUserID());
		redirect("?page=cd&vcd_id=".$itemId);
		exit();
	}
	

}
?>