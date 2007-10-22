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
	
	/**
	 * The rss grabber object
	 *
	 * @var lastRss
	 */
	private $rssFetch=null;
	
	public function __construct(_VCDPageNode $node) {
		
		parent::__construct($node);
		
		
		if (VCDUtils::isLoggedIn()) {
			
			if (is_null($this->rssFetch)) {
				$this->rssFetch = new lastRSS(CACHE_FOLDER,RSS_CACHE_TIME);
				$this->rssFetch->cp = 'UTF-8';
			}
			
			$this->doUserRssList();
			
			$this->registerScript(self::$JS_AJAX);
			$this->registerScript(self::$JS_JSON);
			
		}
		
		
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
				
				$items = null;
				if ($this->rssFetch->isCached($obj->getFeedUrl())) {
					$rssData = $this->doRssItem($obj->getFeedUrl());
					if (isset($rssData['items'])) {
						$items = $rssData['items'];	
					}
				} else {
					$items = 'notInCache';
				}
				
				$results[$obj->getId()] = array('title' => $obj->getName(), 'link' => $obj->getFeedUrl(), 'items' => $items);
				
				
				
			}
			
			$this->assign('frontpageFeeds', $results);
			
		}
		
	}
	
	private function doRssItem($link) {
		
		$results = array();
	
		$rss = $this->rssFetch->Get($link);
		
		if ($rss && $rss['items_count']>0) {
			
			$results['desc'] = $rss['description'];
			$results['link'] = $rss['link'];
			$results['title'] = $rss['title'];
			
			foreach ($rss['items'] as $item) {
	
				$hover = $item['description'];
				$title = $item['title'];
				$link  = $item['link'];
								
				$rssItem = array('title' => $title, 'link' => $link, 'hover' => html_entity_decode($hover,ENT_COMPAT));
				$results['items'][] = $rssItem;
				
			}
		}
		
		return $results;
		
	}
	
	
}


?>