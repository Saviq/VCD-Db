<?php
/**
 * VCD-db - a web based VCD/DVD Catalog system
 * Copyright (C) 2003-2004 Konni - konni.com
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 * 
 * @author  Hákon Birgsson <konni@konni.com>
 * @package Kernel
 * @subpackage WebFetch
 * @version $Id$
 */
 
?>
<?
require_once(dirname(__FILE__) . '/../external/snoopy/Snoopy.class.php');

if (!defined('CACHE_FOLDER')) {
	define("CACHE_FOLDER","upload/cache/");
}


abstract class VCDFetch {
	
	
	private $itemID = null;
	private $siteID = null; 		// For example "imdb" or "dvdempire".
		
	private $fetchDomain;
	private $fetchSearchPath;
	private $fetchItemPath;
		
	
	private $useProxy = false;
	private $proxyHost;
	private $proxyPort;
	
	private $searchKey;
	private $fetchContents;
	private $isCached;				// Flags if contents are Cached.
	
	private $useSnoopy = false;
	/**
	 * Instance of Snoppy Class
	 *
	 * @var Snoopy
	 */
	private $snoopy = null;
	
	
	
	public function __construct() {
		$this->isCached = false;
	}
	
		
	public abstract function search();
	
	public abstract function showSearchResults();
	
	/**
	 * Set the fetch class to use proxy with the defined proxy parameters
	 *
	 * @param string $host | The proxy server hostname
	 * @param int $port | The proxy server port
	 */
	protected function setProxy($host, $port) {
		$this->proxyHost = $host;
		$this->proxyPort = $port;
		$this->useProxy = true;
	}
	
	
	/**
	 * Set the correct url parameters for the current Fetch site
	 *
	 * @param string $domain | The full domain name of the site to fetch
	 * @param string $searchPath | The search path without the domain name. For example "/search?text="
	 * @param string $itemPath | The item path for specific item. For example /item/tt
	 */
	protected function setFetchUrls($domain, $searchPath, $itemPath) {
		$this->fetchDomain = $domain;
		$this->fetchSearchPath = $searchPath;
		$this->fetchItemPath = $itemPath;
	}
	
	
	
	
	/**
	 * Create the HTTP header to send in the HTTP request.
	 *
	 * @param string $url | The url to get.  For example /item/500
	 * @param string $referer | The referer hostname.
	 * @param string $host | The hostname to connect to
	 * @return string
	 */
	private function getHeader($url, $referer, $host) {
		$header  = "GET {$url} HTTP/1.0\r\n";
		$header .= "User-Agent: Mozilla/4.0 (compatible; MSIE 5.5; Windows NT 5.0)\r\n";
		$header .= "Accept: image/gif, image/x-xbitmap, image/jpeg, image/pjpeg, */*\r\n";
		$header .= "Accept-Language: de\r\n";
		$header .= "Referer: {$referer}\r\n";
		$header .= "Host: {$host}\r\n";
		$header .= "Connection: Keep-Alive\r\n";
		$header .= "Cache-Control: no-cache\r\n";
		$header .= "\r\n";
		
		return $header;
	}
	
	
	protected function fetchPage($url, $host, $referer, $useCache=true) {
		
		// First check the cache
		if ($useCache) {
			$contents = $this->fetchCachedPage($url);
			if (!is_null($contents)) {
				$this->fetchContents = $contents;
				return $this->fetchContents;
			}
		}
		
		
		// Item not found in cache
		if ($this->useSnoopy) {
			
			$this->initSnoopy();
			$snoopyurl = "http://".$host.$url;
			$this->snoopy->fetch($snoopyurl);
			$this->fetchContents = $this->snoopy->results;
			
		} else {

			$psplit = split(":",$host);
			$pserver = $psplit[0];
			if(isset($psplit[1])) {
				$pport = $psplit[1];
			} else {
				$pport = 80;
			}
			
			
			$fp = @fsockopen($pserver, $pport);
			if (!$fp) {
				throw new Exception("Could not connect to host " . $host);
			}	
						
			$requestHeader = $this->getHeader($url, $referer, $host);
						
			fputs($fp, $requestHeader);
			$site = "";
			while (!feof($fp)) {
				$site .= fgets ($fp, 1024);
			}
			
			fclose($fp);
			$this->fetchContents = $site;		
					
			
		}
		
		if ($useCache) {
			// Write the results to CACHE
			$cacheFileName = preg_replace("#([^a-z0-9]*)#", "", $url);
			$cacheFileName = CACHE_FOLDER."{$this->siteID}-".$cacheFileName;
			$fp = fopen($cacheFileName, "w");
			fwrite($fp, $this->fetchContents);
			fclose($fp);
		}
		
		
	}
	
	
	
	/**
	 * Get the page from Cache if it exists.  Otherwise function returns null.
	 *
	 * @param string $url
	 * @return string
	 */
	private function fetchCachedPage($url) {
		$cacheFileName = preg_replace("#([^a-z0-9]*)#", "", $url);
		$cacheFileName = CACHE_FOLDER."{$this->siteID}-".$cacheFileName;

		if(file_exists($cacheFileName)) {
			$this->isCached = true;
			return (implode("", file($cacheFileName)));
		} else {
			return null;
		}
	}
	
	/**
	 * Get the page contents from the search results
	 *
	 * @return string
	 */
	protected function getSearchContents() {
		return $this->searchContents;		
	}
	
	/**
	 * Get the Contents of the fetched page
	 *
	 * @return string
	 */
	public function getContents() {
		return $this->fetchContents;
	}
	
	
	/**
	 * Use the external Snoopy Lib to fetch the page instead of opening a socket.
	 *
	 */
	protected function useSnoopy() {
		$this->useSnoopy = true;
	}
	
	
	/**
	 * Set the www Site name. For example "imdb" or "dvdempire".
	 * Used for internal caching naming convention.
	 *
	 * @param string $sitename
	 */
	protected function setSiteName($sitename) {
		$this->siteID = $sitename;
	}
		
	
	
	
	
	
	
	
	/**
	 * Initilize a new snoopy object
	 *
	 */
	private function initSnoopy() {
		if (is_null($this->snoopy)) {
			$this->snoopy = new Snoopy();
			if ($this->useProxy) {
				$this->snoopy->proxy_host = $this->proxyHost;
				$this->snoopy->proxy_port = $this->proxyPort;
			}
		}
	}
	
	
	
	
	
	
	
}

?>