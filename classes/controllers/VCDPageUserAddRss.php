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
 * @version $Id: VCDPageUserAddRss.php 1066 2007-08-15 17:05:56Z konni $
 * @since 0.90
 */
?>
<?php
class VCDPageUserAddRss extends VCDBasePage {

	public function __construct(_VCDPageNode $node) {
		try {
		
			parent::__construct($node);
			$this->registerScript(self::$JS_MAIN);
			$this->registerScript(self::$JS_LANG);
				
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	/**
	 * Handle all $_POST actions the the page
	 *
	 */
	public function handleRequest() {
		
		$action = $this->getParam('action');
		
		if (!is_null($action)) {

			switch ($action) {
				case 'vcddbfetch':
					$this->doFetchFeed();				
					break;
				
				case 'addvcddbfeed':
					$this->addFeeds();
					break;
					
				case 'addsitefeed':
					$this->addSiteFeed();
					break;
					
				default:
					break;
			}
			
		}
		
	}
	
	/**
	 * Add Rss feed to the users feed list
	 *
	 */
	private function addSiteFeed()	{
		$name = $this->getParam('rssname',true);
		$url = $this->getParam('rssurl',true);
		if (!is_null($name) && !is_null($url)) {
			$rssObj = new rssObj(null);
			$rssObj->setName($name);
			$rssObj->setFeedUrl($url);
			$rssObj->setOwnerId(VCDUtils::getUserID());
			$rssObj->setAsSiteFeed(false);
			SettingsServices::addRssfeed($rssObj);
			
			// Tell the window to close and reload parent
			$this->assign('reload',true);
		}
	}
	
	/**
	 * Add feeds from remote VCD-db sites
	 *
	 */
	private function addFeeds() {
		
		$feedList = $this->getParam('feeds',true);
		if (is_array($feedList) && sizeof($feedList) > 0) {
			foreach ($feedList as $feed) {
				$currFeed = explode("|",$feed);
				$rssObj = new rssObj(null);
				$rssObj->setName($currFeed[0]);
				$rssObj->setFeedUrl($currFeed[1]);
				$rssObj->setOwnerId(VCDUtils::getUserID());
				$rssObj->setAsSiteFeed(true);
				SettingsServices::addRssfeed($rssObj);
			}
			
			// Tell the window to close and reload parent
			$this->assign('reload',true);
			
		}
	}
	
	
	/**
	 * Fetch availble VCD-db feeds on the given url
	 *
	 */
	private function doFetchFeed() 	{
		
		$url = $this->getParam('feedurl',true);
		if (is_null($url)) {
			return;
		}
		
		$pos = strlen($url);
		$char = $url[($pos-1)];
		if ($char != '/') {	$url .= "/";}
		
					
		$sitefeed = $url .= "rss/";
		$feedusers = $sitefeed . "?users";
		
		$xml = simplexml_load_file($sitefeed);
		
		if ($xml && isset($xml->error)) {
			$this->assign('rssError', $xml->error);
			return;
		}
		if (!$xml) {
			return;
		} 
		
		$xml_users = simplexml_load_file($feedusers);
		$title = $xml->channel->title;
		$link = $xml->channel->link;
		$description = $xml->channel->description;
		$usersfeeds = $xml_users->rssusers->user;
		
		$results = array();
		if (sizeof($usersfeeds)>0) {
			foreach ($usersfeeds as $userfeed) {
			$results[] = array(
				'name'	=> $userfeed->fullname,
				'link'	=> $userfeed->rsspath
				);
			}	
		}
		
		
		
		$this->assign('rssTitle',$title);
		$this->assign('rssLink',$sitefeed);
		$this->assign('rssList',$results);
			
		
	}
	
	
	
}



?>