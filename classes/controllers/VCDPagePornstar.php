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
 * @version $Id: VCDPagePornstar.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php
class VCDPagePornstar extends VCDBasePage {
	
	public function __construct(_VCDPageNode $node) {
		try {
		
			parent::__construct($node);
			$this->initPage();
				
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	
	private function initPage() {
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
		$base = VCDConfig::getWebBaseDir();
		foreach ($pornstarObj->getMovies() as $id => $title) {
			$hasThumb = false;
			$arrCovers = CoverServices::getAllCoversForVcd($id);
			foreach ($arrCovers as $obj) {
				if ($obj->isThumbnail()) {
					$scriptItems[] = array('index' => $i++, 'image' => $base.'?page=file&amp;cover_id='.$obj->getId());
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
	}
	
}
?>