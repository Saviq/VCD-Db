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
		try {
		
			parent::__construct($node);

			if (VCDUtils::isLoggedIn()) {
				
				if (is_null($this->rssFetch)) {
					$this->rssFetch = new lastRSS(CACHE_FOLDER,RSS_CACHE_TIME);
					$this->rssFetch->cp = 'UTF-8';
					$this->rssFetch->items_limit = 10;
				}
				
				$this->doUserRssList();
				
				$this->registerScript(self::$JS_AJAX);
				$this->registerScript(self::$JS_JSON);
				
			}
			
			$this->doTopTenLists();
			$this->doSiteStatistics();
				
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	/**
	 * Populate the TOP Ten list for the right sidebar
	 *
	 */
	private function doTopTenLists() {
	
		// Check if user is logged in and wished to disable sidebar
		if (VCDUtils::isLoggedIn()) {
			$arr = SettingsServices::getMetadata(0, VCDUtils::getUserID(), metadataTypeObj::SYS_FRONTBAR);
			if (is_array($arr) && sizeof($arr) == 1 && $arr[0] instanceof metadataObj && $arr[0]->getMetadataValue() == 0) {
				return;
			}
		}
				
		// Gather data for the top ten lists
		$results = array();
		$cat_tv = SettingsServices::getCategoryIDByName("tv shows");
		$cat_xxx = SettingsServices::getCategoryIDByName("adult");
		
		// Top Ten list for all movies , except for TV Shows and Adult movies
		$movies = MovieServices::getTopTenList(0, array($cat_tv,$cat_xxx));
		if (sizeof($movies) > 0) {
			$items = array();
			$results[] = array('name' => VCDLanguage::translate('misc.latestmovies'), 'items' => &$items);
			foreach ($movies as $obj) { $items[$obj->getId()] = $obj->getTitle();}
			unset($items);
		}
		
		
		// Top ten list for TV shows if any ..
		$movies = MovieServices::getTopTenList($cat_tv);
		if (sizeof($movies) > 0) {
			$items = array();
			$results[] = array('name' => VCDLanguage::translate('misc.latesttv'), 'items' => &$items);
			foreach ($movies as $obj) { $items[$obj->getId()] = $obj->getTitle();}
			unset($items);
		}
		
		// Top ten list for adult movies if any ..
		if (VCDUtils::showAdultContent() && $cat_tv>0) {
			$movies = MovieServices::getTopTenList($cat_xxx);
			if (sizeof($movies) > 0) {
				$items = array();
				$results[] = array('name' => VCDLanguage::translate('misc.latestblue'), 'items' => &$items);
				foreach ($movies as $obj) { $items[$obj->getId()] = $obj->getTitle();}
				unset($items);
			}
		}
		
		
		// Display the right sidebar ..
		if (sizeof($results)>0) {
			$this->assign('toptenLists',$results);
			$this->assign('showRightbar',true);
			
			// Check if the visibility should be hidden because of a cookie settings
			if (isset($_COOKIE['rbar']) && $_COOKIE['rbar'] == 0) {
				$this->assign('rightbarHidden',true);
			}
		}
	}
	
	
	/**
	 * Populate and display users selected RSS feeds
	 *
	 */
	private function doUserRssList() {
		
		$arr = SettingsServices::getMetadata(0, VCDUtils::getUserID(), 'frontrss');
		if (is_array($arr) && sizeof($arr) == 1 && $arr[0] instanceof metadataObj) {
			$feedstring = $arr[0]->getMetadataValue();
			$feedlist = split("#", $feedstring);
			$rsscount = sizeof($feedlist);
			
			
			$results = array();
			foreach ($feedlist as $feedItem) {
				if (is_numeric($feedItem)) {
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
			}
			
			$this->assign('frontpageFeeds', $results);
		}
	}
	
	/**
	 * Populate and display single RSS feed
	 *
	 * @param string $link | The url to the RSS feed
	 * @return array | Array containing the rss items
	 */
	private function doRssItem($link) {
		
		$results = array();
	
		$rss = $this->rssFetch->Get($link);
		
		if ($rss && $rss['items_count']>0) {
			
			$results['desc'] = $rss['description'];
			$results['link'] = $rss['link'];
			$results['title'] = $rss['title'];
			
			foreach ($rss['items'] as $item) {
	
				$hover = mb_convert_encoding($item['description'], 'UTF-8', $rss['encoding']);
				$title = mb_convert_encoding($item['title'], 'UTF-8', $rss['encoding']);
				$link  = $item['link'];
								
				$rssItem = array('title' => $title, 'link' => $link, 'hover' => html_entity_decode($hover,ENT_COMPAT));
				$results['items'][] = $rssItem;
				
			}
		}
		
		return $results;
		
	}

	
	/**
	 * Assign the site statistics data
	 *
	 */
	private function doSiteStatistics() {
		
		$adultId = SettingsServices::getCategoryIDByName('adult');
		$showAdult = VCDUtils::showAdultContent();
		$stats = SettingsServices::getStatsObj();
		// Assign data from the statistics objects
		
		$this->assign('statsMovieCount',$stats->getMovieCount());
		$this->assign('statsMovieCountToday',$stats->getMovieTodayCount());
		$this->assign('statsMovieCountWeek',$stats->getMovieWeeklyCount());
		$this->assign('statsMovieCountMonth',$stats->getMovieMonthlyCount());
		
		$this->assign('statsCoverCount',$stats->getTotalCoverCount());
		$this->assign('statsCoverCountWeek',$stats->getWeeklyCoverCount());
		$this->assign('statsCoverCountMonth',$stats->getMonthlyCoverCount());
		
		// Overall Top categories
		$results = array();
		foreach ($stats->getBiggestCats() as $categoryObj) {
			if ($categoryObj->getId() == $adultId && !$showAdult) continue;
			$results[] = array('id' => $categoryObj->getID(), 'name' => $categoryObj->getName(true), 
				'count' => $categoryObj->getCategoryCount());
		}
		$this->assign('statsTopCategories',$results);
		
		// Currently most active categories
		$results = array();
		foreach ($stats->getBiggestMonhtlyCats() as $categoryObj) {
			if ($categoryObj->getId() == $adultId && !$showAdult) continue;
			$results[] = array('id' => $categoryObj->getID(), 'name' => $categoryObj->getName(true), 
				'count' => $categoryObj->getCategoryCount());
		}
		
		if (sizeof($results)>0) {
			$this->assign('statsTopCurrentCategories',$results);
		}
		
		
	}
	
}


?>