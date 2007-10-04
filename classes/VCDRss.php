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
 * @version $Id$
 */
?>
<?



class VCDRss {

	private $site_rss = false;
	private $user_rss = false;
	private $rssUsers = array();
	private $baseurl;

	private $rss_version = "2.0";
	private $rss_encoding = "UTF-8";

	// Cache settings
	private $use_cache = true;
	private $cache_folder = CACHE_FOLDER;
	private $cache_time = RSS_CACHE_TIME;

	/**
	 * Object constructor
	 *
	 */
	public function __construct() {
		$this->cache_folder = "../" . $this->cache_folder;
		$this->site_rss = SettingsServices::getSettingsByKey('RSS_SITE');
		$this->user_rss = SettingsServices::getSettingsByKey('RSS_USERS');
		$this->baseurl = SettingsServices::getSettingsByKey('SITE_HOME');
		$pObj = UserServices::getPropertyByKey('RSS');
		if ($pObj instanceof userPropertiesObj) {
			$this->rssUsers = UserServices::getAllUsersWithProperty($pObj->getpropertyID());
		} 
	}


	/**
	 * Get paths to all users with RSS enabled on their movie list
	 *
	 * @return string
	 */
	public function getRSSUsers() {
		if (($this->user_rss) && (sizeof($this->rssUsers) > 0)) {
			$xml = "<?xml version=\"1.0\" encoding=\"{$this->rss_encoding}\"?>\n";
			$xml .= "<sitedata>";
			$xml .= "<url>".$this->baseurl."</url>";
			$xml .= "<rssusers>";
			foreach ($this->rssUsers as $user) {
				$xml .= "<user>";
				$xml .= "<username>".$user->getUsername()."</username>";
				$xml .= "<fullname>".$user->getFullname()."</fullname>";
				$xml .= "<rsspath>".$this->baseurl."rss/?rss=".$user->getUsername()."</rsspath>";
				$xml .= "</user>";
			}

			$xml .= "</rssusers>";
			$xml .= "</sitedata>";
			return $xml;
		} else {
			$xml = "<?xml version=\"1.0\" encoding=\"{$this->rss_encoding}\"?>\n";
			$xml .= "<sitedata><error>No users sharing RSS feeds</error></sitedata>";
			return $xml;
		}
	}

	/**
	 * Get single RSS feed by user name
	 *
	 * @param string $user_name
	 * @return string
	 */
	public function getRSSbyUser($user_name) {
		if ($this->site_rss && $this->user_rss && $this->isValidUser($user_name)) {


			// Check for cached feed if cache is enabled.
			$usecache = false;
			if ($this->use_cache && strcmp($this->cache_folder, "") != 0) {

				$usecache = true;
				$cache_file = $this->cache_folder . 'vcddbrss_' . md5($user_name);
				$timedif = @(time() - filemtime($cache_file));
				if ($timedif < $this->cache_time) {
					// cached file is fresh enough, return cached array
					$xml = unserialize(join('', file($cache_file)));
					return $xml;

				}

			}


	   		$builddate = date("r", time());

			$xml = "<?xml version=\"1.0\" encoding=\"{$this->rss_encoding}\"?>\n";
			$xml .= "<rss version=\"".$this->rss_version."\" xmlns:dc=\"http://purl.org/dc/elements/1.1/\">\n";
  			$xml .= "<channel>\n";
    		$xml .= "<title>".htmlspecialchars(SettingsServices::getSettingsByKey('SITE_NAME'), ENT_QUOTES)." (".$user_name.")</title>\n";
    		$xml .= "<link>".$this->baseurl."</link>\n";
    		$xml .= "<description>VCD-db movie list</description>\n";
    		$xml .= "<language>en-us</language>\n";
    		$xml .= "<lastBuildDate>{$builddate}</lastBuildDate>\n";
    		$xml .= "<generator>VCD-db ".VCDDB_VERSION."</generator>\n";
    		$xml .= "<image>\n<url>".$this->baseurl."images/logo.gif</url>\n<title>VCD-db</title>\n<link>{$this->baseurl}</link>\n</image>\n";


    		$uobj = UserServices::getUserByUsername($user_name);
    		$movies = MovieServices::getLatestVcdsByUserID($uobj->getUserID(),10, true);

    		if (sizeof($movies) > 0) {
    			foreach ($movies as $smallMovie) {
    				$movie = MovieServices::getVcdByID($smallMovie->getID());
    				$arr = $movie->getRSSData();
    				$xml .= "<item>\n";
				    $xml .= "<title>".htmlspecialchars($movie->getTitle(),ENT_QUOTES)."</title>\n";
				    $xml .= "<link>".$this->baseurl."?page=cd&amp;vcd_id=".$movie->getID()."</link>\n";
				    $xml .= "<description>".htmlspecialchars($arr['description'],ENT_QUOTES)."</description>\n";
				    $xml .= "<dc:creator>".htmlspecialchars($arr['creator'],ENT_QUOTES)."</dc:creator>\n";
				    $xml .= "<dc:date>".htmlspecialchars(date('c', $arr['date']),ENT_QUOTES)."</dc:date>\n";
				    $xml .= "</item>\n";
    			}
    		}

  			$xml .= "</channel>\n";
			$xml .= "</rss>\n";


			// Check if we need to write the results to cache because the existing one was to old.
			if ($usecache) {
				$serialized = serialize($xml);
				if ($f = @fopen($cache_file, 'w')) {
					fwrite ($f, $serialized, strlen($serialized));
					fclose($f);
				}
			}


			return $xml;

		} else {
			$xml = "<?xml version=\"1.0\" encoding=\"{$this->rss_encoding}\"?>\n";
			$xml .= "<sitedata><error>Invalid username</error></sitedata>";
			return $xml;
		}
	}


	/**
	 * Get the sites RSS feed
	 *
	 * @return string
	 */
	public function getSiteRss() {
		if ($this->site_rss) {


			// Check for cached feed if cache is enabled.
			$usecache = false;
			if ($this->use_cache && strcmp($this->cache_folder, "") != 0) {

				$usecache = true;
				$cache_file = $this->cache_folder . 'vcddbrss_' . md5($this->baseurl);
				$timedif = @(time() - filemtime($cache_file));
				if ($timedif < $this->cache_time) {
					// cached file is fresh enough, return cached array
					$xml = unserialize(join('', file($cache_file)));
					return $xml;

				}

			}

    		
    		
    		$sInfo = VCDConnection::getServerInfo();
    		$db_env = DB_TYPE . " - " . $sInfo['description'];

			$builddate = date("r", time());

			$xml = "<?xml version=\"1.0\" encoding=\"{$this->rss_encoding}\"?>\n";
			$xml .= "<rss version=\"".$this->rss_version."\" xmlns:dc=\"http://purl.org/dc/elements/1.1/\">\n";
  			$xml .= "<channel>\n";
    		$xml .= "<title>".htmlspecialchars(SettingsServices::getSettingsByKey('SITE_NAME'),ENT_QUOTES)."</title>\n";
    		$xml .= "<link>".$this->baseurl."</link>\n";
    		$xml .= "<description>VCD-db Movie List</description>\n";
    		$xml .= "<language>en-us</language>\n";
    		$xml .= "<lastBuildDate>{$builddate}</lastBuildDate>\n";
    		$xml .= "<generator>VCD-db ".VCDDB_VERSION." ({$db_env})</generator>\n";
    		$xml .= "<image>\n<url>".$this->baseurl."images/logo.gif</url>\n<title>VCD-db</title>\n<link>{$this->baseurl}</link>\n</image>\n";

    		$movies = MovieServices::getTopTenList();
    		if (sizeof($movies) > 0) {
    			foreach ($movies as $smallMovie) {
    				$movie = MovieServices::getVcdByID($smallMovie->getID());
    				$arr = $movie->getRSSData();
    				$xml .= "<item>\n";
				    $xml .= "<title>".htmlspecialchars($movie->getTitle(),ENT_QUOTES)."</title>\n";
				    $xml .= "<link>".$this->baseurl."?page=cd&amp;vcd_id=".$movie->getID()."</link>\n";
				    $xml .= "<description>".htmlspecialchars($arr['description'],ENT_QUOTES)."</description>\n";
				    $xml .= "<dc:creator>".htmlspecialchars($arr['creator'],ENT_QUOTES)."</dc:creator>\n";
				    $xml .= "<dc:date>".htmlspecialchars(date('c', $arr['date']),ENT_QUOTES)."</dc:date>\n";
				    $xml .= "</item>\n";
    			}
    		}

  			$xml .= "</channel>\n";
			$xml .= "</rss>\n";


			// Check if we need to write the results to cache because the existing one was to old.
			if ($usecache) {
				$serialized = serialize($xml);
				if ($f = @fopen($cache_file, 'w')) {
					fwrite ($f, $serialized, strlen($serialized));
					fclose($f);
				}
			}

			return $xml;

		} else {
			$xml = "<?xml version=\"1.0\" encoding=\"{$this->rss_encoding}\"?>\n";
			$xml .= "<sitedata>";
			$xml .= "<error>RSS disabled by administrator</error>";
			$xml .= "</sitedata>";
			return $xml;
		}
	}
	
	
	
	public function getRemoteVcddbFeed($name, $url) {
	
		
		// Check for cached feed if cache is enabled.
		$this->cache_folder = CACHE_FOLDER;
		$xmlLoaded = false;
		if ($this->use_cache && strcmp($this->cache_folder, "") != 0) {

			$cache_file = $this->cache_folder . 'vcddbrss_' . md5($url);
			$timedif = @(time() - filemtime($cache_file));
			if ($timedif < $this->cache_time) {
				// cached file is fresh enough
				$xml = unserialize(join('', file($cache_file)));
				$xmlLoaded = true;
			}
		}
		
		
		if ($xmlLoaded) {
			$xml = simplexml_load_string($xml);
		} else {
			$xml = simplexml_load_file($url);
		}
		
		
		// Check if we need to write the results to cache because the existing one was to old.
		if ($this->use_cache && !$xmlLoaded) {
			$serialized = serialize($xml->asXML());
			if ($f = @fopen($cache_file, 'w')) {
				fwrite ($f, $serialized, strlen($serialized));
				fclose($f);
			} 
		}
		
			
		return $xml;
		
	}
	


	/**
	 * Check if requested user name is valid
	 *
	 * @param string $username
	 * @return bool
	 */
	private function isValidUser($username) {
		foreach ($this->rssUsers as $user) {
			if (strcmp($user->getUserName(), $username) == 0) {
				return true;
			}
		}
		return false;
	}


}

?>