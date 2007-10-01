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
	
	
	public function __construct(_VCDPageNode $node) {
		parent::__construct($node);

		// load the requested item
		$this->loadItem();
		$this->doCoreElements();
		
	}
	
		
	
	/**
	 * Handle all POST requests to this controller.
	 */
	public function handleRequest() {
		
		
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
		$this->doComments();
		$this->doMetadata();
		$this->doCopiesList();
		$this->doWishlist();
		$this->doSimilarList();
		$this->doSeenLink();
		$this->doManagerLink();
		$this->doSourceSiteLink();
		$this->doCovers();

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
		
	}
	
	/**
	 * Assign list of available copies to the view
	 *
	 */
	private function doCopiesList() {
		
	}
	
	/**
	 * Assign the "Add to wishlist" link to the view
	 *
	 */
	private function doWishlist() {
		
	}
	
	/**
	 * Assign the "Similar items" dropdown data to the view
	 *
	 */
	private function doSimilarList() {
		
		$list = MovieServices::getSimilarMovies($this->itemObj->getID());
		if (is_array($list) && sizeof($list) > 0) {
			$this->assign('itemSimilar',$list);
		}
		
	}
	
	/**
	 * Assign the "Seen movie" link to the view
	 *
	 */
	private function doSeenLink() {
		
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
						'link' => './?page=file&cover_id='.$coverObj->getId()
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
	
}
?>