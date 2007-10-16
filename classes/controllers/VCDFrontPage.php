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
 * @version $Id: VCDFrontPage.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php

class VCDFrontPage extends VCDBasePage {
	
	
	public function __construct(_VCDPageNode $node) {
		
		parent::__construct($node);
		
		
		if (VCDUtils::isLoggedIn()) {
			$this->doUserRssList();
		}
		
				
		/*
		foreach ($this->get_template_vars('moduleList') as $item) {
			print "module: " . $item . "<br>";
		}
		*/
		
	}
	
	
	
	private function doUserRssList() {
		
		$arr = SettingsServices::getMetadata(0, VCDUtils::getUserID(), 'frontrss');
		if (is_array($arr) && sizeof($arr) == 1 && $arr[0] instanceof metadataObj) {
			$feedstring = $arr[0]->getMetadataValue();
			$feedlist = split("#", $feedstring);
			$rsscount = sizeof($feedlist);
			
			
			$results = array();
			foreach ($feedlist as $feedItem) {
				$obj = SettingsServices::getRssfeed($feedItem);
				$results[$obj->getId()] = array('name' => $obj->getName(), 'url' => $obj->getFeedUrl());
			}
			
			$this->assign('frontpageFeeds', $results);
			
		}
		
		
	}
	
	
	
}


?>