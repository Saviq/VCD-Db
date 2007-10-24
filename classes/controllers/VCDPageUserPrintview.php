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
 * @version $Id: VCDPageUserPrintview.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php

class VCDPageUserPrintview extends VCDBasePage  {
	
	public function __construct(_VCDPageNode $node) {

		parent::__construct($node);
		
		$action = $this->getParam('mode');
		switch ($action) {
			case 'text':
				$this->doPrintViewText();
				break;
			default:
				$this->doPrintViewImages($action);
				break;
		}
				
	}
	
	private function doPrintViewImages($listType) {
		$list = MovieServices::getPrintViewList(VCDUtils::getUserID(), $listType);
		$results = array();
		$src = '<img src="?page=file&amp;cover_id=%s" border="0"/>';
		foreach ($list as $vcdObj) {
			$img = '';
			$thumbnail = $vcdObj->getCover('thumbnail');
			if ($thumbnail instanceof cdcoverObj ) {
				$img = sprintf($src, $thumbnail->getId());
			}
			
			$results[$vcdObj->getId()] = array('title' => $vcdObj->getTitle(), 'image' => $img);
		}
		
		$this->assign('itemCount',sizeof($results));
		$this->assign('itemList',$results);
	}
	
	
	private function doPrintViewText() {
		$list = MovieServices::getPrintViewList(VCDUtils::getUserID(), 'text');
		$results = array();
		foreach ($list as $vcdObj) {
			$results[$vcdObj->getId()] = 
				array('title' => $vcdObj->getTitle(), 'year' => $vcdObj->getYear(), 'date' => $vcdObj->getDateAdded(),
				'category' => $vcdObj->getCategory()->getName(true), 'mediatype' => $vcdObj->showMediaTypes());
		}
		$this->assign('itemList',$results);
		
	}
	
		
	
}
?>