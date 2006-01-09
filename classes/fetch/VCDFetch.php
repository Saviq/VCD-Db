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
abstract class VCDFetch {
	
	
	protected $itemID = null;
	protected $fetchServer;
	
	protected $useProxy = false;
	protected $proxyUrl = null;
	
	protected $searchKey;
	protected $searchContents;
	protected $itemContents;
	
	
	
	public function __construct() {
		
	}
	
	public function setProxyServer($strProxy) {
		$this->proxyUrl = $strProxy;
		$this->useProxy = true;
	}
	
		
	public abstract function search();
	
	public abstract function showSearchResults();
	
	protected function getHeader() {
		
	}
	
	protected function fetchPage() {
		
	}
	
	protected function fetchCachedPage() {
		
		
	}
	
	
	
	
	
	
	
}

?>