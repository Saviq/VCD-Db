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
class VCDFetch_dvdempire extends VCDFetch {
	
	
	protected $regexArray = array(
		'title' 	=> '<title>Adult DVD Empire - (.*) - Adult DVD',
		'year'  	=> 'Production Year: ([0-9]{4})',
		'poster' 	=>  null,
		'studio' 	=> '<font color=\"white\">i</font><a href=\"/Exec/studio.asp[?]userid=([0-9]{14})&amp;studio_id=([0-9]{3})\">([^<]*)</a><br>',
		'studio2'	=> 'studio_id=([0-9])">([^<]*)</a>',
		'screens'	=> 'topoftabs\">([^<]*) Screen Shots</a>',
		'genre'		=> 'site_media_id=([0-9])">([^<]*)</a></nobr>',
		'cast' 		=> 'sort=2\'>([^<]*)</a>'
		);
	
	protected $multiArray = array(
		'cast', 'genre', 'poster'
	);
		
		
		
	private $servername = 'adult.dvdempire.com';
	//private $searchpath = '/exec/v1_search_titles.asp?userid=0000&string=[$]&include_desc=0&used=0&view=0&pp=4&sort=';
	private $searchpath = '/exec/v1_search_titles.asp?userid=00000000000001&string=[$]&include_desc=0&used=0&view=1&sort=5';
	private $itempath   = '/Exec/v1_item.asp?item_id=[$]';
		
	
	public function __construct() {
		$this->useSnoopy();
		$this->setSiteName("empire");
		$this->setFetchUrls($this->servername, $this->searchpath, $this->itempath);
	}
	
	protected function processResults() {
		print $this->getContents();
	}
	
	
	protected function fetchDeeper($entry) {
		
		print "Deeper " . $entry . "<br>";
		
	}
	
	public function search($title) { 
		return parent::search($title);
	}
	
	public function showSearchResults() {
		
		$this->setMaxSearchResults(50);
		//$regx = '<a href=\"\/title\/tt([0-9]+)\/([^\<]*)\">([^\<]*)</a>';
		
		
		//print $this->getContents();
		//exit();
		
		//$regx = '<b><a href="/Exec/v1_item.asp?userid=([0-9]+)&amp;item_id=([0-9]+)&amp;searchID=([0-9]+)">([^\<]*)</a></b>';
		//$regx = 'item_id=([^"]+)">([^<]*)</a></b>';
		$regx = 'item_id=([^"]+)">([^<]*)</a></b>';
		$results = parent::generateSimpleSearchResults($regx, 1, 2);
		
		parent::generateSearchSelection($results);
		
		/*
		print "<pre>";
		print_r($results);
		print "</pre>";
		*/
		
		
						
	}
	
	
	
	
}




?>