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
	
	
	private $regexArray = array(
		'title' 	=> '<STRONG CLASS=\"title\">([^\<]*) <SMALL>\(<A HREF=\"/Sections/Years/([0-9]{4})',
		'year'  	=> '<STRONG CLASS=\"title\">([^\<]*) <SMALL>\(<A HREF=\"/Sections/Years/([0-9]{4})',
		'poster' 	=> '/<img border="0" alt="cover" src="([^"]+)"/is',
		'director' 	=> '#Directed by.*\n[^<]*<a href="/Name?[^"]*">([^<]*)</a>#i',
		'genre' 	=> '#Genre:</b>(.*?)<br>#msi',
		'rating' 	=> '<B>([0-9]).([0-9])/10</B> \([0-9,]+ votes\)',
		'cast' 		=> '<td valign="top"><a href="/name/nm([^"]+)">([^<]*)</a></td><td valign="top" nowrap="1"> .... </td><td valign="top">([^<]*)</td>',
		'plot'		=> '<p class="plotpar">([^<]*)</p>',
		'runtime' 	=> '#<b class="ch">Runtime:</b>\n([0-9]+) min#i',
		'akas' 		=> 'Also Known As</b>:</b><br>(.*)<b class="ch"><a href="/mpaa">MPAA</a>',
		'country' 	=> '<a href="/Sections/Countries/([^>]*)>([^<]*)</a>'
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