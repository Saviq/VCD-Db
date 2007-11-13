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
 * @version $Id: VCDPageUserRss.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php
class VCDPageUserRss extends VCDBasePage {

	public function __construct(_VCDPageNode $node) {
		
		parent::__construct($node);
				
		$this->doRssFeeds();
	
		
	}
	
	/**
	 * Get the Rss feeds user has added and display them
	 *
	 */
	private function doRssFeeds() {
		
		$arrFeeds = SettingsServices::getRssFeedsByUserId(VCDUtils::getUserID());
		if (is_array($arrFeeds) && sizeof($arrFeeds) > 0) {
				
			$rssClass = new VCDRss();
			$results = array();
			
			$i=0;
			foreach ($arrFeeds as $rssObj) {

				$results[$i]['title'] = $rssObj->getName();
				$results[$i]['link'] = $rssObj->getFeedUrl();
							
				$xml = $rssClass->getRemoteVcddbFeed($rssObj->getName(), $rssObj->getFeedUrl());
				
				if (is_null($xml)) {
					$results[$i]['error'] = 'Feed not available.';
				} elseif (isset($xml->error)) {
					$results[$i]['error'] = $xml->error;
				} else {
					$items = $xml->channel->item;
				    $title = $xml->channel->title;
				    $link = $xml->channel->link;
			
		    		if (strpos($title, "(") === false)  {
		    			$image = "<img src=\"images/rsssite.gif\" title=\"VCD-db site feed\" border=\"0\"/>&nbsp;";	
		    		} else {
		    			$image = "<img src=\"images/rssuser.gif\" title=\"VCD-db user feed\" border=\"0\"/>&nbsp;";
		    		}
		    		$results[$i]['image'] = $image;
		    		$results[$i]['title'] = utf8_decode($xml->channel->title);
					$results[$i]['link'] = $xml->channel->link;
						
		    		$listItems = array();
					$itemCounter = 0;
					foreach ($items as $item) {
						$listItems[$itemCounter]['title'] = utf8_decode($item->title);
						$listItems[$itemCounter]['link'] = $item->link;
						if (isset($item->description)) {
							$listItems[$itemCounter]['desc'] = $item->description;
						}			
						$itemCounter++;
					}
					$results[$i]['items'] = $listItems;
					
				}
		
			$i++;
		}
				
			
			$this->assign('rssList', $results);
		
		}
		
		
	}

	
}


?>