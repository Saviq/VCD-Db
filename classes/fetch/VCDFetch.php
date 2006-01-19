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
		
	private $errorMessage;			// Container for error messages
	
	private $useProxy = false;		// Use proxy server for fetching ?
	private $proxyHost;				// The proxy server hostname
	private $proxyPort;				// The proxy server port
	
	private $searchKey;				// The search key used in the search query
	private $searchMaxResults = 50; // Maximum search results
	private $searchRedirectUrl;		// The url that is redirected to if search is exact match.
		
	private $fetchContents;			// The fetched page.
	private $fetchItem;				// The item filled after getItem() has been called
	private $isCached;				// Flags if contents are Cached.
	
	private $useSnoopy = false;
	/**
	 * Instance of Snoppy Class
	 *
	 * @var Snoopy
	 */
	private $snoopy = null;
			
	
	protected $workerArray = array();
	
	CONST ITEM_ERROR 	= 0;
	CONST ITEM_OK 	 	= 1;
	CONST ITEM_NOTFOUND = 2;
	
	CONST SEARCH_ERROR  = 0;
	CONST SEARCH_DONE   = 1;
	CONST SEARCH_EXACT  = 2;
	
	protected function __construct() {
		$this->isCached = false;
	}
	

	// These functions must be implemented by this class inheritors
	public abstract function showSearchResults();
	protected abstract function processResults();
	protected abstract function fetchDeeper($item);
	
	
	
	/**
	 * Get contents of Exact title, Fills the local page buffer and returns the status of the fetch.
	 *
	 * @param string $id
	 * @return int
	 */
	public function fetchItemByID($id) {
		$this->itemID = $id;
		$itemUrl = str_replace('[$]', $id, $this->fetchItemPath);
		$referer = "http://".$this->fetchDomain;
		return $this->fetchPage($this->fetchDomain, $itemUrl, $referer);
	}
	
	
	
	/**
	 * Tell weither the contents of the fetched page are cached or not.
	 *
	 * @return bool
	 */
	public function isCached() {
		return $this->isCached;
	}
	
	
		
	/**
	 * Try to featch each item in $regexArray the simple way, call the fetchDeeper() on failure
	 * for deeper processing.  Each success item is pushed into array $workerArray.
	 * Functions fetchDeeper() and processResults() must have been implemented in the inhereted class.
	 *
	 */
	public function fetchValues() {
		foreach ($this->regexArray as $entry => $regex) {
			$multi = (bool)in_array($entry, $this->multiArray);
			if (!is_null($regex) && $this->getItem($regex, $multi) == self::ITEM_OK ) {
				array_push($this->workerArray, array($entry, $this->getFetchedItem()));
			} else {
				$this->fetchDeeper($entry);
			}
		}
		
		// and finally process the results
		$this->processResults();
	}
	
	
	public function toString() {
		print "<pre>";
		$results = print_r($this->workerArray, true);
		print htmlentities($results, ENT_QUOTES);
		print "</pre>";
	}
	
		
	/**
	 * 
	 * 
	 * 
	 * 
	 * Protected functions.  Used internally and by inheritor classes.
	 * 
	 * 
	 * 
	 */
	
	
	
	
	
	
	
	
	/**
	 * Search the current site for the given keyword, The search then fills the internal page buffer
	 * with the searchresults.  Returning the status of the search, SEARCH_ERROR, SEARCH_DONE or SEARCH_EXACT.
	 *
	 * @param string $title
	 * @return int
	 */
	protected function search($title) {
		$this->searchKey = rawurlencode($title);
		if ($this->useSnoopy) {
			$this->fetchSearchPath = str_replace('[$]', $this->searchKey, $this->fetchSearchPath);
		}
		
		$referer = "http://".$this->fetchDomain;
		$header = $this->getHeader($this->fetchSearchPath, $referer, $this->fetchDomain);
		$header = str_replace("[$]", $this->searchKey, $header);
		$iResults = $this->fetchPage($this->fetchDomain, $this->fetchSearchPath, $referer, false, $header);

		if (!$iResults) {
			$SEARCH_RESULTS = self::SEARCH_ERROR;
		} else {
			$SEARCH_RESULTS = self::SEARCH_DONE;	
		}
		
		
		// Check for exact match
		if ($this->useSnoopy) {
			if (strcmp($this->snoopy->lastredirectaddr, "") != 0) {
				$this->searchRedirectUrl = $this->snoopy->lastredirectaddr;
				$SEARCH_RESULTS = self::SEARCH_EXACT;
			}
		} else {
			if(strstr($this->fetchContents, "HTTP/1.0 302") || strstr($this->fetchContents, "HTTP/1.1 302")) { 
				// Break up the header
				$headerArr = split("\n", $this->fetchContents, 10);
				$neddle = "Location:";
				// Find the item with Location:
				foreach ($headerArr as $entry) {
					if (substr_count($entry, $neddle) == 1) {
						// Found it ..
						$url = trim(substr($entry, strlen($neddle)));
						$this->searchRedirectUrl = $url;
						break;
					}
				}
				
				$SEARCH_RESULTS = self::SEARCH_EXACT;
			}
		}
		
		
		return $SEARCH_RESULTS;
		
	}
	
			
	
	
	/**
	 * Generate simple array containing the search results,
	 * Returns assoc array of search results with entries [id] and [title]
	 *
	 * @param string $regex | The regular expression used to defined the item rules.
	 * @param int $indexId | The index of the ID in the array created with $regex
	 * @param int $indexTitle | The index of the TITLE in the array created with $regex
	 * @return array
	 */
	protected function generateSimpleSearchResults($regex, $indexId=null, $indexTitle=null) {
		$this->getItem($regex, true);
		$results = $this->getFetchedItem();
		
		if (is_null($indexId) || is_null($indexTitle)) {
			return $results;
		}
		
		$arrSearchResults = array();
		for ($i = 0; $i < sizeof($results); $i++) {
			if ($i > $this->searchMaxResults) { break; }
						
			$searchItem = $results[$i];
			array_push($arrSearchResults, array('id' => $searchItem[$indexId], 'title' => strip_tags($searchItem[$indexTitle])));
		}
		
		return $arrSearchResults;
	}
	
	
	/**
	 * Generate simple selection from the current search results so user can choose a title to fetch.
	 * The array $arrSearchResults must be assoc and contain key [id] and [title].
	 *
	 * @param array $arrSearchResults
	 */
	protected function generateSearchSelection($arrSearchResults) {
		if (!is_array($arrSearchResults) || sizeof($arrSearchResults) == 0) {
			print "No search results to generate from.";
			return;
		}
		
		$testItem = $arrSearchResults[0];
		if (!isset($testItem['id']) || !isset($testItem['title']))	{
			throw new Exception('Results array must contain keys [id] and [title]');
		}
		
		
		$extUrl = "http://".$this->fetchDomain.$this->fetchItemPath;
		print "<ul>";
		foreach ($arrSearchResults as $item) {
			$link = "?page=private&amp;o=add&amp;source={$this->siteID}&amp;fid={$item['id']}";
			$info = str_replace('[$]', $item['id'], $extUrl);
			$str = "<li><a href=\"{$link}\">{$item['title']}</a>&nbsp;&nbsp;<a href=\"{$info}\" target=\"_new\">[info]</a></li>";
			print $str;
		}
		print "<ul>";
		
	}
	
		
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
	 * Set the correct url parameters for the current Fetch site.
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
	 * Fetch a page from Remote HTTP server
	 *
	 * @param string $host | The host server name
	 * @param string $url | The url within the servername to fetch
	 * @param string $referer | The referer host name to use
	 * @param bool $useCache | Use the cache file if exists
	 * @param string $header | Use predefined header, otherwise it will be automatically created.
	 * @return bool
	 */
	protected function fetchPage($host, $url, $referer, $useCache=true, $header=null) {
		
		$results = true;
		
		// First check the cache
		if ($useCache) {
			$contents = $this->fetchCachedPage($url);
			if (!is_null($contents)) {
				$this->fetchContents = $contents;
				return true;
			}
		}
		
		
		// Item not found in cache
		if ($this->useSnoopy) {
			$snoopyurl = "http://".$host.$url;
			$this->snoopy->fetch($snoopyurl);
			
			// Clean hex garbage from results.
			$this->fetchContents = preg_replace('[\x00]','',$this->snoopy->results);
			
		} else {

			if ($this->useProxy) {
				
				$proxyurl = "http://".$host.$url;
				$results = $this->fetchThroughProxy($proxyurl);
				
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
							
				if (is_null($header)) {
					$requestHeader = $this->getHeader($url, $referer, $host);	
				} else {
					$requestHeader = &$header;
				}
				
							
				fputs($fp, $requestHeader);
				$site = "";
				while (!feof($fp)) {
					$site .= fgets ($fp, 1024);
				}
				
				fclose($fp);
				$this->fetchContents = $site;		
			}
		}
		
		if ($useCache) {
			$this->writeToCache($url);
		}
		
		return $results;
		
		
	}
	
	
	
	/**
	 * If search() returns SEARCH_EXACT, this function will return the url that was redirected to.
	 *
	 * @return string
	 */
	protected function getSearchRedirectUrl() {
		return $this->searchRedirectUrl;
	}
	
	
	/**
	 * Set the maximum allowed records in searh results
	 *
	 * @param int $iNum
	 */
	protected function setMaxSearchResults($iNum) {
		$this->searchMaxResults = $iNum;
	}
	
	/**
	 * Get the number of allowed search results
	 *
	 * @return int
	 */
	protected function getMaxSearchResults() {
		return $this->searchMaxResults;
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
	protected function getContents() {
		return $this->fetchContents;
	}
	
	
	/**
	 * Fill the internal page buffer with data.
	 *
	 * @param string $strContents
	 */
	protected function setContents($strContents) {
		$this->fetchContents = $strContents;
	}
	
	
	/**
	 * Get the current item ID
	 *
	 * @return string
	 */
	protected function getItemID() {
		return $this->itemID;
	}
	
	
	/**
	 * Find text from the current fetchContents using regular expressions.
	 * Returns the status code of the fetch status defined as constants in the class.
	 *
	 * @param string $expression | The Regular Expression to use in the search
	 * @param bool $multivalue | Tell weither the search values are multiple items or not
	 * @return int
	 */
	protected function getItem($expression, $multivalue=false) {
		$retval = "";
		if (!$multivalue) {
			
			 if(!@eregi($expression, $this->fetchContents, $retval)) { 
			 	// Try using preg_match instead
			 	if(!@preg_match($expression, $this->fetchContents, $retval)) { 
			 		return self::ITEM_NOTFOUND;
			 	}
			 }
			 			 
			
		} else {
			
			// Multiple values expected
			$retval = array();
			$contents = $this->fetchContents;
			while(eregi($expression, $contents, $arrRoller)) {
	  	      $contents = substr($contents,strpos($contents,$arrRoller[0])+strlen($arrRoller[0]));
		      array_push($retval, $arrRoller);
		    }
			
			if (sizeof($retval) == 0) {
				return self::ITEM_NOTFOUND;
			}
		
		}
		
		$this->fetchItem = $retval;
		return self::ITEM_OK;
		
		
	}
	
	
	/**
	 * Get the item that was succesfully found via function getItem().
	 * Return value can either be an Array or string.
	 *
	 * @return mixed
	 */
	protected function getFetchedItem() {
		return $this->fetchItem;
	}
	
		
	/**
	 * Use the external Snoopy Lib to fetch the page instead of opening a socket.
	 *
	 */
	protected function useSnoopy() {
		$this->useSnoopy = true;
		$this->initSnoopy();
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
	 * Set the current Error Message
	 *
	 * @param string $strError
	 */
	protected function setErrorMsg($strError) {
		$this->errorMessage = $strError;
	}
	
	
	/**
	 * Get the current Error Message
	 *
	 * @return string
	 */
	protected function getErrorMsg() {
		return $this->errorMessage;
	}
	
	
	
	/**
	 * 
	 * 
	 * 
	 * Private internal functions.
	 * 
	 * 
	 * 
	 */
	
	
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
	 * Write the current fetchContents of the class to CACHE
	 *
	 * @param string $url | The url that is the owner of the cache
	 */
	private function writeToCache($url) {
		if (!is_null($this->fetchContents) && strlen($this->fetchContents) > 0) {
			$cacheFileName = preg_replace("#([^a-z0-9]*)#", "", $url);
			$cacheFileName = CACHE_FOLDER."{$this->siteID}-".$cacheFileName;
			$fp = fopen($cacheFileName, "w");
			fwrite($fp, $this->fetchContents);
			fclose($fp);
		}
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
	
	
	/**
	 * Fetch a page from Remote HTTP server through the help of Proxy Server.
	 * Return true if operation succeded.
	 *
	 * @param string $proxy_url | The Url to fetch
	 * @return bool 
	 */
	private function fetchThroughProxy($proxy_url) {

	   if ((strcmp($this->proxyHost, "") == 0) || (!is_numeric($this->proxyPort))) {
	   		throw new Exception("Proxy settings are undefined.");
	   }
		
	   $contents = "";

	   $fp = fsockopen($this->proxyHost, $this->proxyPort);
	   if (!$fp)  {
	   		$this->setErrorMsg("No response from proxy server at " . $this->proxyHost);
	   		return false;
	   }

	   $urlArr = parse_url($proxy_url);
	   $domain = $urlArr['host'];

	   fputs($fp, "GET $proxy_url HTTP/1.0\r\nHost: $domain\r\n\r\n");
	   while(!feof($fp)) {$contents .= fread($fp,4096);}
	   fclose($fp);
	   $contents = substr($contents, strpos($contents,"\r\n\r\n")+4);
	   $this->fetchContents = $contents;
	   return true;
	}
	
	
	private function clean($strData) {
		while(ereg("&#([0-9]{3});", $strData, $x)) {
			$strData = str_replace("&#".$x[1].";", chr($x[1]), $strData);
		}
		return $strData;
	}
	
	
	
}

?>