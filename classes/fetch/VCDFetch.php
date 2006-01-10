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
require_once('external/snoopy/Snoopy.class.php');
if (!defined('CACHE_FOLDER')) {
	define("CACHE_FOLDER","upload/cache/");
}


abstract class VCDFetch {
	
	
	protected $itemID = null;
	
	private $fetchDomain;
	private $fetchSearchPath;
	private $fetchItemPath;
		
	
	private $useProxy = false;
	private $proxyHost;
	private $proxyPort;
	
	private $searchKey;
	private $searchContents;
	private $itemContents;
	
	private $useSnoopy = false;
	/**
	 * Instance of Snoppy Class
	 *
	 * @var Snoopy
	 */
	private $snoopy = null;
	
	
	
	public function __construct() {
		
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
	
	protected function getHeader() {
		
	}
	
	
	protected function fetchPage() {
		if ($this->useSnoopy) {
			$this->initSnoopy();
			
		} else {
						
			
		}
	}
	
	protected function fetchCachedPage() {
		
		
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
		return $this->itemContents;		
	}
	
	
	/**
	 * Use the external Snoopy Lib to fetch the page instead of opening a socket.
	 *
	 */
	protected function useSnoopy() {
		$this->useSnoopy = true;
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