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
class VCDFetch_imdb extends VCDFetch {
	
	
	private $regexArray = array(
		'title' 	=> '<STRONG CLASS=\"title\">([^\<]*) <SMALL>\(<A HREF=\"/Sections/Years/([0-9]{4})',
		'year'  	=> '<STRONG CLASS=\"title\">([^\<]*) <SMALL>\(<A HREF=\"/Sections/Years/([0-9]{4})',
		'poster' 	=> '/<img border="0" alt="cover" src="([^"]+)"/is',
		'director' 	=> '#Directed by.*\n[^<]*<a href="/Name?[^"]*">([^<]*)</a>#i',
		'genre' 	=> '<A HREF=\"/Sections/Genres/[a-zA-Z\\-]*/\">([a-zA-Z\\-]*)</A>',
		'rating' 	=> '<B>([0-9]).([0-9])/10</B> \([0-9,]+ votes\)',
		'cast' 		=> '<td valign="top"><a href="/name/nm([^"]+)">([^<]*)</a></td><td valign="top" nowrap="1"> .... </td><td valign="top">([^<]*)</td>',
		'plot'		=> '<p class="plotpar">([^<]*)</p>',
		'runtime' 	=> '#<b class="ch">Runtime:</b>\n([0-9]+) min#i',
		'akas' 		=> 'Also Known As</b>:</b><br>(.*)<b class="ch"><a href="/mpaa">MPAA</a>',
		'country' 	=> '<a href="/Sections/Countries/([^>]*)>([^<]*)</a>'
		);
		
	private $multiArray = array(
		'genre', 'cast', 'akas', 'country'
	);
		
	
	private $servername = 'akas.imdb.com';
	private $searchpath = '/find?q=[$]';
	private $itempath   = '/title/tt[$]/';
		
	
	public function __construct() {
		$this->setSiteName("imdb");
		$this->setFetchUrls($this->servername, $this->searchpath, $this->itempath);
	}
	
	
	public function search($title) { 
		return parent::search($title);
	}
	
	public function showSearchResults() {
		
		$this->setMaxSearchResults(40);
		$regx = '<a href=\"\/title\/tt([0-9]+)\/([^\<]*)\">([^\<]*)</a>';
		$results = parent::generateSimpleSearchResults($regx, 1, 3);
		parent::generateSearchSelection($results);
	}
	
	
	public function getMovie() {
		foreach ($this->regexArray as $item => $value) {
			$multi = (bool)in_array($item, $this->multiArray);
			if ($this->getItem($value, $multi) == self::ITEM_OK ) {
				print_r($this->getFetchedItem());
			} else {
				print "Could not fetch ITEM " . $item . "<br>";
			}
		}
	}
	
	
	
	
	
}









?>