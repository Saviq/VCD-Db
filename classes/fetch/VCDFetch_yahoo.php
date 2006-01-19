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
class VCDFetch_yahoo extends VCDFetch {
	
	
	protected $regexArray = array(
		'title' 	  => '<td bgcolor=A6B9DC width=570><h1><strong>([^<]*)</strong></h1></td>',
		'year'  	  => '<td bgcolor=A6B9DC width=570><h1><strong>([^<]*)</strong></h1></td>',
		'genre'	 	  => 'site_media_id=([0-9])">([^<]*)</a></nobr>',
		'cast' 		  => null,
		'thumbnail'	  => null,
		'frontcover'  => null,
		'backcover'   => null
		);
	
			
	protected $multiArray = array(
		'cast', 'genre', 'poster'
	);
		
		
		
	private $servername = 'movies.yahoo.com';
	private $searchpath = '/mv/search?p=[$]';
	private $itempath   = '/shop?d=hv&cf=info&id=[$]';
		
	
	public function __construct() {
		$this->useSnoopy();
		$this->setSiteName("yahoo");
		$this->setFetchUrls($this->servername, $this->searchpath, $this->itempath);
	}
	
	protected function processResults() {
		//print $this->getContents();
	}
	
	
	protected function fetchDeeper($entry) {
		
		switch ($entry) {
			case 'cast':
			
				// Save the old buffer
				$itemBuffer = $this->getContents();	
			
				// Generate urls
				$actorurl = "/movie/".$this->getItemID()."/cast";
				$referer = "http://".$this->servername.str_replace('[$]', $this->getItemID(), $this->itempath);
				
				// Set the regx
				$regx = '&cf=gen">([^"]+)</a></font></td>([^\s])<td><font face=arial size=-1>([^"]+)</font>';
				
				$isActors =  $this->fetchPage($this->servername, $actorurl, $referer);
				if ($isActors) {
					if ($this->getItem($regx, true) == self::ITEM_OK) {
						$actors = $this->getFetchedItem();
						array_push($this->workerArray, array($entry, $actors));
						
					} 
				} 
				
				break;
		
			default:
				break;
		}
		
	}
	
	public function search($title) { 
		return parent::search($title);
	}
	
	public function showSearchResults() {
		
		
		$this->setMaxSearchResults(50);
		$regx = 'd=hv&cf=info&id=([0-9]{10})">([^"]+)</a><br>';

		$results = parent::generateSimpleSearchResults($regx,1,2);
		
		
		

		parent::generateSearchSelection($results);
		
		/*
		print "<pre>";
		print_r($results);
		print "</pre>";
		*/
					
	}
	
	
	
}




?>