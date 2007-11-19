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
 * @version $Id: VCDPageItemAdultMovie.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php
require_once(dirname(__FILE__).'/VCDPageBaseItem.php');
class VCDPageItemAdultMovie extends VCDPageBaseItem  {
	
	private $hasScreenshots = false;	
	
	public function __construct(_VCDPageNode $node) {
		try {
		
			parent::__construct($node);

			if (!is_null($this->sourceObj))	{
				$this->doSourceSiteElements();
			}
			
			// do the pornstar list
			$this->doActorList();
			// do the studio item
			$this->doStudio();
			// do the adult categories
			$this->doCategories();
			// Set the screenshot view option
			$this->doScreenshots();
			
			if ($this->hasScreenshots) {
				$this->registerScript(self::$JS_JSON);
				$this->registerScript(self::$JS_AJAX);
			}
				
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	
	/**
	 * Assign screenshots if any
	 *
	 */
	private function doScreenshots() {
		if (MovieServices::getScreenshots($this->itemObj->getID()) ) {
			$this->assign('itemScreenshots',true);
			$this->hasScreenshots = true;
		}
	}
	
	
	/**
	 * Assign the studio item
	 *
	 */
	private function doStudio() {
		$studioObj = PornstarServices::getStudioByMovieID($this->itemObj->getID());
		if ($studioObj instanceof studioObj ) {
			$this->assign('itemStudioId', $studioObj->getID());
			$this->assign('itemStudioName',htmlspecialchars($studioObj->getName()));
		}
	}
	
	
	/**
	 * Assign the adult categories
	 *
	 */
	private function doCategories() {
		$adultCategories = PornstarServices::getSubCategoriesByMovieID($this->itemObj->getID());
		if (is_array($adultCategories) && sizeof($adultCategories)>0) {
			$results = array();
			foreach ($adultCategories as $cateoryObj) {
				$results[$cateoryObj->getID()] = $cateoryObj->getName();
			}
			$this->assign('itemAdultCategories',$results);
		}
	}
	
	/**
	 * Assign the actor list
	 *
	 */
	private function doActorList() {

		$pornstars = $this->itemObj->getPornstars();
		if (!is_array($pornstars)) {
			$pornstars = PornstarServices::getPornstarsByMovieID($this->itemObj->getID());
		}
		if (is_array($pornstars)) {
			$results = array();
			foreach ($pornstars as $pornstar) {
				$results[] = array('id' => $pornstar->getID(), 'name' => $pornstar->getName(), 'img' => $pornstar->getImageLink());
			}
			$this->assign('itemPornstars',$results);
		}
		
	}
	
	
}
?>