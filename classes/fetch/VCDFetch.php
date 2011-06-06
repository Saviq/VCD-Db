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
 * @subpackage WebFetch
 * @version $Id$
 */

?>
<?php
require_once(VCDDB_BASE.'/classes/external/snoopy/Snoopy.class.php');

if (!defined('CACHE_FOLDER')) {
	define("CACHE_FOLDER","upload/cache/");
}


abstract class VCDFetch {


	private $itemID = null;
	private $siteID = null; 		// For example "imdb" or "dvdempire".

	private $fetchDomain;
	private $fetchSearchPath;
	private $fetchItemPath;
	private $siteEncoding = "UTF-8";

	private $errorMessage;			// Container for error messages

	private $useProxy = false;		// Use proxy server for fetching ?
	private $proxyHost;				// The proxy server hostname
	private $proxyPort;				// The proxy server port

	private $searchKey;				// The search key used in the search query
	private $searchMaxResults = 50; // Maximum search results
	private $searchRedirectUrl=null;// The url that is redirected to if search is exact match.

	private $fetchContents;			// The fetched page.
	private $fetchItem;				// The item filled after getItem() has been called
	private $isCached = false;		// Flags if contents are Cached.

	private $useSnoopy = false;
	/**
	 * Instance of a Snoppy Class
	 *
	 * @var Snoopy
	 */
	protected $snoopy = null;
	private $isAdult = false;		// Flag to tell if the fetched site is an adult site.
	protected $cookies = array();		// Add cookies to be sent
	protected $headers = array();		// Add raw headers to be sent

	protected $workerArray = array();

	/**
	 * The fetched Object to be populated.
	 *
	 * @var fetchObj
	 */
	protected $fetchedObj = null;

	CONST ITEM_ERROR 	= 0;
	CONST ITEM_OK 	 	= 1;
	CONST ITEM_NOTFOUND = 2;

	CONST SEARCH_ERROR  = 0;
	CONST SEARCH_DONE   = 1;
	CONST SEARCH_EXACT  = 2;

	protected function __construct() {}


	/**********************
	 *
	 * Abstract functions.
	 * These functions must be implemented by this class inheritors
	 *
	 **********************/
	public abstract function showSearchResults();
	protected abstract function processResults();
	protected abstract function fetchDeeper($item);






	/********************
	 *
	 * Public functions.
	 *
	 *******************/



	/**
	 * Get contents of Exact title, Fills the local page buffer and returns the status of the fetch.
	 *
	 * @param string $id
	 * @return int
	 */
	public function fetchItemByID($id = null) {
		if (!is_null($id)) {
			$this->itemID = $id;
		}

		if (is_null($id) && is_null($this->getItemID())) {
			throw new Exception("Fetch ID is null");
		}

		$itemUrl = str_replace('[$]', $this->getItemID(), $this->fetchItemPath);
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


	/**
	 * Print out the entire populated RegEx array.
	 *
	 */
	public function toString() {
		print "<pre>";
		$results = print_r($this->workerArray, true);
		print htmlentities($results, ENT_QUOTES);
		print "</pre>";
	}


	/**
	 * Get the populated Object.
	 *
	 * @return fetchedObj
	 */
	public function getFetchedObject() {
		return $this->fetchedObj;
	}

	/**
	 * Check weither the fecthed site contains adult movies or not.
	 *
	 * @return bool
	 */
	public function isAdultSite() {
		return $this->isAdult;
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
		if(strcasecmp(VCDUtils::getCharSet(), $this->siteEncoding) != 0) {
			$this->searchKey = rawurlencode(mb_convert_encoding($title, $this->siteEncoding, VCDUtils::getCharSet()));
		} else {
			$this->searchKey = rawurlencode($title);
		}
		if ($this->useSnoopy || ((int)ini_get('allow_url_fopen') == 0)) {
			$this->fetchSearchPath = str_replace('[$]', $this->searchKey, $this->fetchSearchPath);
		}

		$referer = "http://".$this->fetchDomain;
		$searchPath = str_replace("[$]", $this->searchKey, $this->fetchSearchPath);
		$header = $this->getHeader($searchPath, $referer, $this->fetchDomain);
		$iResults = $this->fetchPage($this->fetchDomain, $searchPath, $referer, false, $header);

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
	 * @param int $indexYear | The index of the YEAR in the array created with $regex
	 * @return array
	 */
	protected function generateSimpleSearchResults($regex, $indexId=null, $indexTitle=null, $indexYear=null) {
		$this->getItem($regex, true);
		$results = $this->getFetchedItem();

		if (is_null($indexId) || is_null($indexTitle)) {
			return $results;
		}

		$arrSearchResults = array();


		for ($i = 0; $i < sizeof($results); $i++) {
			if ($i > $this->searchMaxResults) { break; }

			$searchItem = $results[$i];
			if (is_null($indexYear)) {
				array_push($arrSearchResults, array('id' => $searchItem[$indexId], 'title' => strip_tags($searchItem[$indexTitle])));
			} else {
				array_push($arrSearchResults, array('id' => $searchItem[$indexId], 'title' => strip_tags($searchItem[$indexTitle]), 'year' => $searchItem[$indexYear]));
			}


		}

		return $arrSearchResults;
	}


	/**
	 * Generate simple selection from the current search results so user can choose a title to fetch.
	 * The array $arrSearchResults must be assoc and contain key [id] and [title].
	 *
	 * @param array $arrSearchResults
	 * @return array | Array of search results
	 */
	protected function generateSearchSelection($arrSearchResults) {
		if (!is_array($arrSearchResults) || sizeof($arrSearchResults) == 0) {
			return null;
		}

		$testItem = $arrSearchResults[0];
		if (!isset($testItem['id']) || !isset($testItem['title']))	{
			throw new Exception('Results array must contain keys [id] and [title]');
		}


		$extUrl = "http://".$this->fetchDomain.$this->fetchItemPath;
				
		
		// Add keys needed by the template engine ..
		for ($i=0;$i<sizeof($arrSearchResults);$i++) {
			$arrSearchResults[$i]['fetchlink'] = "?page=add&amp;source=webfetch&site={$this->siteID}&amp;fid=".$arrSearchResults[$i]['id'];
			$arrSearchResults[$i]['sourcelink'] = str_replace('[$]', $arrSearchResults[$i]['id'], $extUrl);
		}
				
		return $arrSearchResults;
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
		
		// check for proxy status ..
		$this->checkProxySettings();
	}

	/**
	 * 
	 * Set the correct encoding for the fetch site if it's not UTF-8
	 * 
	 * @param string $encoding | The site encoding
	 */
	protected function setEncoding($encoding) {
		$this->siteEncoding = $encoding;
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
			$this->snoopy->cookies = $this->cookies;
			$this->snoopy->rawheaders = $this->headers;
			$this->snoopy->fetch($snoopyurl);

			// Clean hex garbage from results.
			$this->fetchContents = preg_replace('[\x00]','',$this->snoopy->results);

		} else // If url_fopen is disabled, but CURL is available
		if (((int)ini_get('allow_url_fopen') == 0) && function_exists('curl_init')) {
			
			$curlUrl = "http://".$host.$url;
			$results = $this->fetchWithCurl($curlUrl);
			
			
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

		$this->writeToCache($url);

		// Only do the conversion if needed ..
		if (ereg('"text/html; *charset=([^"]+)"', $this->fetchContents, $enc) && strcasecmp(VCDUtils::getCharSet(), $enc[1]) != 0)
			$this->fetchContents = mb_convert_encoding($this->fetchContents, VCDUtils::getCharSet(), $enc[1]);
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
		if (is_null($this->getSearchRedirectUrl())) {
			return $this->itemID;
		} else {

			/*  Since the item was a direct hit we have to figure out
			    the id from the given redirect url */

			$dvdempitempath = 'item_id=[$]';
			$regex = str_replace("[$]", "([0-9]+)", $this->fetchItemPath);
			@ereg($regex, $this->getSearchRedirectUrl(), $results);

			if (isset($results[1])) {
				// IMDB and most generic sites
				$this->itemID = $results[1];
				return $this->itemID;

			} elseif (@ereg(str_replace("[$]", "([0-9]+)", $dvdempitempath), $this->getSearchRedirectUrl(), $results)) {
				// dvdempire only ..
				$this->itemID = $results[1];
				return $this->itemID;

			} else {
				return null;
			}
		}
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
		    	preg_match_all($expression, $contents, $retval, PREG_SET_ORDER);
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

	protected function getSiteName() {
		return($this->siteID);
	}

	protected function setID($id) {
		$this->itemID = $id;
	}

	protected function getID() {
		return($this->itemID);
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
	 * Flag the current site that is being fethed contains adult movies.
	 *
	 */
	protected function setAdult() {
		$this->isAdult = true;
	}


	/**
	 * Check if a remote file/image exists on the remote server.
	 *
	 * @param string $strUrl | The url string to check
	 * @return bool
	 */
	protected function remote_file_exists($strUrl) {
		$handle = @fopen($strUrl, "r");
 		if ($handle === false)
  		return false;
 		fclose($handle);
 		return true;
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
	 * Check the config file if proxy server should be used, and if so
	 * set the correct proxy settings.
	 *
	 */
	private function checkProxySettings()	{
		try {
			
			$useProxy = (bool)USE_PROXY;
			$proxyUrl = PROXY_URL;
			$proxyPort = PROXY_PORT;
			
			if ($useProxy) {
			
				if (!isset($proxyUrl) || (strcmp($proxyUrl, '0') ==0)) {
					throw new VCDInvalidArgumentException('Proxy server address must be defined in config.php');
				}
			
				$this->setProxy($proxyUrl, $proxyPort);
					
			}
						
		} catch (Exception $ex) {
			throw $ex;
		}
	}
	

	/**
	 * Get the page from Cache if it exists.  Otherwise function returns null.
	 *
	 * @param string $url
	 * @return string
	 */
	private function fetchCachedPage($url) {
		$cacheFileName = md5($url);
		$cacheFileName = CACHE_FOLDER."{$this->siteID}-".$cacheFileName;

		if(file_exists($cacheFileName)) {
			$this->isCached = true;
			$site = implode("", file($cacheFileName));
			if (ereg('"text/html; *charset=([^"]+)"', $site, $enc) && strcasecmp(VCDUtils::getCharSet(), $enc[1]) != 0) return(mb_convert_encoding($site, $enc[1], VCDUtils::getCharSet()));
			else return($site);
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
			$cacheFileName = md5($url);
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
		if(!empty($this->cookies)) foreach($this->cookies as $k => $v) {
			$header .= "Cookie: $k=$v\r\n";
		}
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


	/**
	 * Fetch webpage with the CURL extension
	 *
	 * @param string $url | The url to fetch
	 * @param string $referrer | The http referer to use
	 * @return bool
	 */
	private function fetchWithCurl($url, $referer=null) {
		
	   
	   $ch = curl_init();
	   curl_setopt($ch, CURLOPT_URL, $url);
	   curl_setopt($ch, CURLOPT_HEADER, 1);
	   curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	   curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	   if (!is_null($referer)) {
	   	curl_setopt($ch, CURLOPT_REFERER, $referer);	
	   }
	   
	   $data = curl_exec($ch);
	   	   
	   curl_close($ch);
	   if ($data) {
	       $this->fetchContents = substr($data, strpos($data,"\r\n\r\n")+4);
	       return true;
	   } else {
	       return false;
	   }
	}

	private function clean($strData) {
		while(ereg("&#([0-9]{3});", $strData, $x)) {
			$strData = str_replace("&#".$x[1].";", chr($x[1]), $strData);
		}
		return $strData;
	}



}

?>
